<?php
session_start();
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);  // Limpiar el mensaje de error después de mostrarlo
}

// Obtener el código a autocompletar si existe en la sesión
$codigoAutocompletar = isset($_SESSION['codigo_autocompletar']) ? $_SESSION['codigo_autocompletar'] : '';
unset($_SESSION['codigo_autocompletar']); // Eliminar el código de la sesión después de mostrarlo
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cma.edu.pe/exalumnos/assets/css/auth.css">
    <title>Inicio de Sesión</title>
    <link rel="icon" type="image/x-icon" href="/exalumnos/assets/img/favicon.ico">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="/exalumnos/assets/img/logo.avif" alt="Logo" class="logo"> <!-- Aquí el logo -->
            <h2>Inicio de Sesión</h2>
            <p>¡Bienvenido/a al registro de exalumnos del Colegio María Alvarado! Regístrate o inicia sesión para actualizar tus datos y mantenernos en contacto.<p>
            <form action="auth" method="POST">
                <input type="text" name="identificador" placeholder="Código de exalumno o Correo" value="<?php echo htmlspecialchars($codigoAutocompletar); ?>" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                
                <?php if (isset($error)): ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endif; ?>
                
                <input type="hidden" name="action" value="login">
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>
            <p>¿No tienes cuenta? <a href="registro">Regístrate aquí</a></p>

            <!-- Redes sociales y políticas de privacidad -->
            <div class="social-section">
                <div class="social-icons">
                    <a href="https://www.youtube.com/c/cmalhs/videos" target="_blank" class="social-icon youtube" aria-label="YouTube" loading="lazy"></a>
                    <a href="https://www.flickr.com/photos/96660300@N08/collections/" target="_blank" class="social-icon flickr" aria-label="Flickr" loading="lazy"></a>
                    <a href="https://www.facebook.com/mariaalvaradolhs/" target="_blank" class="social-icon facebook" aria-label="Facebook" loading="lazy"></a>
                    <a href="https://www.instagram.com/mariaalvaradolhs/" target="_blank" class="social-icon instagram" aria-label="Instagram" loading="lazy"></a>
                    <a href="https://www.linkedin.com/company/52020413/admin/feed/posts/" target="_blank" class="social-icon linkedin" aria-label="Linkedin" loading="lazy"></a>
                </div>
                <p class="privacy-policy">
                    <a href="https://cma.edu.pe/privacy-statement/" target="_blank">Políticas de Privacidad</a>
                </p>
            </div>
        </div>
    </div>
</body>
<a href="https://api.whatsapp.com/send?phone=51955843730&text=" target="_blank" class="whatsapp-icon" aria-label="WhatsApp">
    <img src="https://cma.edu.pe/exalumnos/assets/img/whatsapp.png" alt="WhatsApp">
</a>
</html>