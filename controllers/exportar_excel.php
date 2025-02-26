<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Iniciar el buffer de salida
ob_start();

// Obtener los parámetros de búsqueda
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellidos = isset($_GET['apellidos']) ? $_GET['apellidos'] : '';
$profesion = isset($_GET['profesion']) ? $_GET['profesion'] : '';
$color_promocion = isset($_GET['color_promocion']) ? $_GET['color_promocion'] : '';
$anio_promocion = isset($_GET['anio_promocion']) ? $_GET['anio_promocion'] : '';

// Construcción de la consulta
$query = "SELECT * FROM exalumno WHERE 1=1";

if ($nombre) {
    $query .= " AND nombres LIKE :nombre";
}

if ($apellidos) {
    $query .= " AND apellidos LIKE :apellidos";
}

if ($profesion) {
    $query .= " AND profesion LIKE :profesion";
}

if ($color_promocion) {
    $query .= " AND color_promocion = :color_promocion";
}

if ($anio_promocion) {
    $query .= " AND anio_promocion = :anio_promocion";
}

$stmt = $pdo->prepare($query);

if ($nombre) {
    $stmt->bindValue(':nombre', '%' . $nombre . '%');
}

if ($apellidos) {
    $stmt->bindValue(':apellidos', '%' . $apellidos . '%');
}

if ($profesion) {
    $stmt->bindValue(':profesion', '%' . $profesion . '%');
}

if ($color_promocion) {
    $stmt->bindValue(':color_promocion', $color_promocion);
}

if ($anio_promocion) {
    $stmt->bindValue(':anio_promocion', $anio_promocion);
}

$stmt->execute();
$exalumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el archivo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Escribir encabezados
$headers = [
    'A1' => 'Código',
    'B1' => 'Documento de Identidad',
    'C1' => 'Nombres',
    'D1' => 'Apellidos',
    'E1' => 'Profesión',
    'F1' => 'Empresa Labora',
    'G1' => 'Cargo',
    'H1' => 'Año Retiro',
    'I1' => 'Color Promoción',
    'J1' => 'Año Promoción',
    'K1' => 'Delegado Promoción',
    'L1' => 'País Residencia',
    'M1' => 'Correo',
    'N1' => 'Celular',
    'O1' => 'Fecha de Nacimiento',
    'P1' => 'Política de datos',
    'Q1' => 'Permiso de uso de datos'
];

// Aplicar formato de negrita y ajuste de ancho a los encabezados
foreach ($headers as $cell => $headerText) {
    $sheet->setCellValue($cell, $headerText);
    $sheet->getStyle($cell)->getFont()->setBold(true); // Negrita
    $sheet->getColumnDimension(substr($cell, 0, 1))->setAutoSize(true); // Ajustar el ancho de la columna automáticamente
}

// Escribir datos de exalumnos
$row = 2; // Empezamos desde la segunda fila, después de los encabezados
foreach ($exalumnos as $exalumno) {
    $sheet->setCellValue('A' . $row, $exalumno['codigo']);
    $sheet->setCellValue('B' . $row, $exalumno['documentoidentidad']);
    $sheet->setCellValue('C' . $row, $exalumno['nombres']);
    $sheet->setCellValue('D' . $row, $exalumno['apellidos']);
    $sheet->setCellValue('E' . $row, $exalumno['profesion']);
    $sheet->setCellValue('F' . $row, $exalumno['empresa_labora']);
    $sheet->setCellValue('G' . $row, $exalumno['cargo']);
    $sheet->setCellValue('H' . $row, $exalumno['anio_retiro']);
    $sheet->setCellValue('I' . $row, $exalumno['color_promocion']);
    $sheet->setCellValue('J' . $row, $exalumno['anio_promocion']);
    $sheet->setCellValue('K' . $row, $exalumno['delegado_promocion']);
    $sheet->setCellValue('L' . $row, $exalumno['pais_residencia']);
    $sheet->setCellValue('M' . $row, $exalumno['correo']);
    $sheet->setCellValue('N' . $row, $exalumno['celular']);
    $sheet->setCellValue('O' . $row, $exalumno['fecha_nacimiento']);
    $sheet->setCellValue('P' . $row, $exalumno['politica'] ? 'Sí' : 'No');
    $sheet->setCellValue('Q' . $row, $exalumno['permiso'] ? 'Sí' : 'No');
    $row++;
}

// Aplicar formato de alineación centrada a los encabezados
$sheet->getStyle('A1:Q1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Descargar archivo Excel
$writer = new Xlsx($spreadsheet);
$filename = 'exalumnos_filtro_' . date('Y-m-d') . '.xlsx';

// Cabeceras para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Limpiar el buffer de salida para evitar problemas con el archivo
ob_clean();
flush();

// Crear el archivo Excel y enviarlo a la salida
$writer->save('php://output');

// Finalizar el script
exit;
