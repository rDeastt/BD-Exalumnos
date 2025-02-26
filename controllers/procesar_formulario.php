<?php
session_start();
require_once __DIR__ . '/../models/Exalumno.php';
require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../vendor/tfpdf/fpdf.php';

// Form data for database storage
$data = [
    'codigo' => $_POST['codigo'],
    'correo' => $_POST['correo'],
    'documentoidentidad' => $_POST['documentoidentidad'],
    'politica' => isset($_POST['politica']) ? 1 : 0, // Store as 1 or 0
    'termino_lhs' => $_POST['termino_lhs'],
    'anio_retiro' => $_POST['anio_retiro'],
    'color_promocion' => $_POST['color_promocion'],
    'anio_promocion' => $_POST['anio_promocion'],
    'delegado_promocion' => $_POST['delegado_promocion'],
    'apellidos' => $_POST['apellidos'],
    'nombres' => $_POST['nombres'],
    'pais_residencia' => $_POST['pais_residencia'],
    'celular' => $_POST['celular'],
    'fecha_nacimiento' => $_POST['fecha_nacimiento'],
    'empresa_labora' => $_POST['empresa_labora'],
    'cargo' => $_POST['cargo'],
    'permiso' => $_POST['permiso'],
    'profesion' => $_POST['profesion']
];

// Check if user exists, otherwise insert
$exalumnoExistente = Exalumno::obtenerPorCorreo($data['correo']);
if ($exalumnoExistente) {
    Exalumno::actualizar($data);
} else {
    Exalumno::insertar($data);
}

// Prepare data for PDF display
$nombreCompleto = $data['nombres'] . ' ' . $data['apellidos'];
$dni = $data['documentoidentidad'];
$permiso = $data['permiso'] == 1 ? 'autorizo' : 'no autorizo';
$fechaHoy = date("Y-m-d");

// Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.php');
$pdf->AddFont('DejaVu', 'B', 'DejaVuSansCondensed-Bold.php');
$pdf->SetFont('DejaVu', '', 12);

// Título
$pdf->SetFont('DejaVu', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Autorización para compartir datos'), 0, 1, 'C');
$pdf->Ln(10);

// Cuerpo del documento
$texto = "Yo, $nombreCompleto, identificado(a) con el Documento de Identidad N° $dni, por medio del presente documento, $permiso a la Asociación Colegio María Alvarado para compartir mis datos personales con otros exalumnos y exalumnas, con el propósito de facilitar la comunicación, la creación de redes de contacto y la colaboración entre miembros de nuestra comunidad de egresados.

Comprendo y acepto que esta información será utilizada exclusivamente con fines de contacto y networking, y que en cualquier momento podré solicitar la revocación de este consentimiento enviando una notificación a la Asociación.

Declaro que los datos proporcionados son verídicos y que he leído y comprendido los términos y condiciones establecidos para el uso de esta información.\n

Fecha de emisión: $fechaHoy.";

// Imprimir texto en el PDF
$pdf->SetFont('DejaVu', '', 12);
$pdf->MultiCell(0, 10, utf8_decode($texto), 0, 'L');

// Define the PDF file path and ensure the directory exists
$pdfFilePath = __DIR__ . '/../uploads/exalumno_' . $data['codigo'] . '.pdf';
try {
    $pdf->Output('F', $pdfFilePath);
} catch (Exception $e) {
    echo "Error saving PDF: " . $e->getMessage();
    exit;
}

// Enviar PDF por correo electrónico
$mail = new PHPMailer(true);
try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host = 'cma.edu.pe';
    $mail->SMTPAuth = true;
    $mail->Username = 'exalumnos_registro@cma.edu.pe';  // Correo        
    $mail->Password = 'Registro2024*'; // Contraseña
    /*$mail->SMTPSecure = 'ssl'; // Habilitar encriptación SSL*/
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;  

    $mail->setFrom('exalumnos_registro@cma.edu.pe', 'Exalumnos');
    $mail->addAddress($data['correo']);
    // Configuración de UTF-8 para permitir tildes y caracteres especiales
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Documento de Consentimiento para Compartir Datos';
    
    // Cuerpo del correo en UTF-8
    $mail->Body = 'En el presente correo encontrará el documento con su declaración de consentimiento para compartir datos con otras exalumnas(os).';

    // Adjuntar el PDF
    $mail->addAttachment($pdfFilePath);
} catch (Exception $e) {
    echo "Error al enviar correo: " . $mail->ErrorInfo . "<br>";
    echo "Detalles del error: " . $e->getMessage();
    die(); // Detiene la ejecución después de mostrar el error
}
// Enviar el correo
$mail->send();
header('Location: confirmar');
// Redirigir a la página de confirmación
exit;
?>
