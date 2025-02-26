<?php
session_start();
require_once __DIR__ . '/../models/Exalumno.php';

// Verificar si el usuario ha iniciado sesión y si es administrador
if (!isset($_SESSION['correo']) || $_SESSION['admin'] != 1) {
    header('Location: login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/exalumnos/assets/img/favicon.ico">
    <link rel="stylesheet" href="/exalumnos/assets/css/admin.css">
    <title>Admin - Gestión de Exalumnos</title>
</head>
<body>
    <div class="admin-container">
        <div class="admin-box">
            <!-- Logo -->
            <div class="logo-container">
                <img src="/exalumnos/assets/img/logo.avif" alt="Logo" class="logo">
            </div>
            <h2>Panel de Administración</h2>

            <!-- Formulario de búsqueda -->
            <form method="GET" action="">
                <input type="text" name="nombre" placeholder="Nombres" value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>">
                <input type="text" name="apellidos" placeholder="Apellidos" value="<?php echo isset($_GET['apellidos']) ? htmlspecialchars($_GET['apellidos']) : ''; ?>">
                <input type="text" name="profesion" placeholder="Profesión" value="<?php echo isset($_GET['profesion']) ? htmlspecialchars($_GET['profesion']) : ''; ?>">

                <!-- Color de promoción -->
                <select name="color_promocion">
                    <option value="">Selecciona color de promoción</option>
                    <option value="Red" <?php echo (isset($_GET['color_promocion']) && $_GET['color_promocion'] == 'Red') ? 'selected' : ''; ?>>Red</option>
                    <option value="Green" <?php echo (isset($_GET['color_promocion']) && $_GET['color_promocion'] == 'Green') ? 'selected' : ''; ?>>Green</option>
                    <option value="Pink" <?php echo (isset($_GET['color_promocion']) && $_GET['color_promocion'] == 'Pink') ? 'selected' : ''; ?>>Pink</option>
                    <option value="Blue" <?php echo (isset($_GET['color_promocion']) && $_GET['color_promocion'] == 'Blue') ? 'selected' : ''; ?>>Blue</option>
                    <option value="Yellow" <?php echo (isset($_GET['color_promocion']) && $_GET['color_promocion'] == 'Yellow') ? 'selected' : ''; ?>>Yellow</option>
                </select>

                <!-- Año de promoción -->
                <input type="text" name="anio_promocion" placeholder="Año de Promoción" value="<?php echo isset($_GET['anio_promocion']) ? htmlspecialchars($_GET['anio_promocion']) : ''; ?>">

                <button type="submit">Buscar</button>
            </form>

            <!-- Contenedor de la tabla con desplazamiento horizontal y vertical -->
            <div class="table-container">
                <table>
                    <!-- Cabecera de la tabla -->
                    <thead>
                        <tr>
                            <th>PDF</th>
                            <th>Código</th>
                            <th>Documento de Identidad</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Profesión</th>
                            <th>Empresa donde labora</th>
                            <th>Cargo</th>
                            <th>Año de retiro</th>
                            <th>Color de promoción</th>
                            <th>Año de promoción</th>
                            <th>Delegado de promoción</th>
                            <th>País de residencia</th>
                            <th>Correo</th>
                            <th>Celular</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Política de datos</th>
                            <th>Permiso de compartir datos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Relleno de la tabla desde la base de datos -->
                        <?php
                        require_once __DIR__ . '/../config/db.php';

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

                        foreach ($exalumnos as $exalumno) {
                            echo '<tr>';
                            echo '<td><a href="/exalumnos/uploads/exalumno_' . htmlspecialchars($exalumno['codigo']) . '.pdf" target="_blank"><img src="/exalumnos/assets/img/download-pdf.png" alt="PDF" style="width:24px;height:24px;"></a></td>';
                            echo '<td>' . htmlspecialchars($exalumno['codigo']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['documentoidentidad']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['nombres']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['apellidos']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['profesion']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['empresa_labora']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['cargo']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['anio_retiro']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['color_promocion']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['anio_promocion']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['delegado_promocion']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['pais_residencia']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['correo']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['celular']) . '</td>';
                            echo '<td>' . htmlspecialchars($exalumno['fecha_nacimiento']) . '</td>';
                            echo '<td>' . ($exalumno['politica'] ? 'Sí' : 'No') . '</td>';
                            echo '<td>' . ($exalumno['permiso'] ? 'Sí' : 'No') . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Contenedor de los botones debajo de la tabla -->
            <div class="button-container">
                <form method="GET" action="exportar">
                    <input type="hidden" name="nombre" value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>">
                    <input type="hidden" name="apellidos" value="<?php echo isset($_GET['apellidos']) ? htmlspecialchars($_GET['apellidos']) : ''; ?>">
                    <input type="hidden" name="profesion" value="<?php echo isset($_GET['profesion']) ? htmlspecialchars($_GET['profesion']) : ''; ?>">
                    <input type="hidden" name="color_promocion" value="<?php echo isset($_GET['color_promocion']) ? htmlspecialchars($_GET['color_promocion']) : ''; ?>">
                    <input type="hidden" name="anio_promocion" value="<?php echo isset($_GET['anio_promocion']) ? htmlspecialchars($_GET['anio_promocion']) : ''; ?>">
                    <button type="submit" class="export-btn">Exportar a Excel</button>
                </form>

                <a href="logout" class="logout-btn">Cerrar sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
