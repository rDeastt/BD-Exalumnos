<?php
session_start();
require_once __DIR__ . '/../models/Exalumno.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correo']) || $_SESSION['admin'] == 1) {
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
    <title>Confirmación</title>
    <link rel="stylesheet" href="/exalumnos/assets/css/confirmacion.css">
</head>
<body>
    <div class="background-container">
        <div class="form-container">
            <!-- Logo encima del mensaje -->
            <div class="logo-container">
                <img src="/exalumnos/assets/img/logo.avif" alt="Logo" class="logo">
            </div>
            
            <h2>¡Muchas gracias por participar!</h2>
                        <!-- Botones de acciones -->
            <div class="button-container">
                <a href="logout" class="button">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html>
