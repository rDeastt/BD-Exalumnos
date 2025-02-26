<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cma.edu.pe/exalumnos/assets/css/auth.css">
    <link rel="icon" type="image/x-icon" href="/exalumnos/assets/img/favicon.ico">
    <title>Registro</title>
    <style>
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("contrasena");
            const confirmPasswordInput = document.getElementById("confirmar_contrasena");
            const toggleCheckbox = document.getElementById("togglePassword");
            
            if (toggleCheckbox.checked) {
                passwordInput.type = "text";
                confirmPasswordInput.type = "text";
            } else {
                passwordInput.type = "password";
                confirmPasswordInput.type = "password";
            }
        }

        function validatePasswords() {
            const password = document.getElementById("contrasena").value;
            const confirmPassword = document.getElementById("confirmar_contrasena").value;
            if (password !== confirmPassword) {
                document.getElementById("password-error").textContent = "Las contraseñas no coinciden.";
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="/exalumnos/assets/img/logo.avif" alt="Logo" class="logo">
            <h2>Registro</h2>
            <p>¡Bienvenido/a al registro de exalumnos del Colegio María Alvarado! Ingresa tu correo, crea una contraseña y confírmala. Revisa tu correo al finalizar.<p>
            <form action="auth" method="POST" onsubmit="return validatePasswords();">
                <input type="email" name="correo" placeholder="Correo" required>

                <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                
                <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" placeholder="Vuelve a escribir tu contraseña" required>

                <label>
                    <input type="checkbox" id="togglePassword" onclick="togglePasswordVisibility()"> Mostrar contraseñas
                </label>

                <p id="password-error" class="error-message"></p>
                
                <?php if (isset($error)): ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endif; ?>
                
                <input type="hidden" name="action" value="register">
                <button type="submit" class="btn-registro">Registrarse</button>
            </form>
            <p>¿Ya tienes cuenta? <a href="login">Inicia sesión aquí</a></p>
            <!-- Redes sociales y políticas de privacidad -->
            <div class="social-section">
                <div class="social-icons">
                    <a href="https://www.youtube.com/c/cmalhs/videos" target="_blank" class="social-icon youtube" aria-label="YouTube"></a>
                    <a href="https://www.flickr.com/photos/96660300@N08/collections/" target="_blank" class="social-icon flickr" aria-label="Flickr"></a>
                    <a href="https://www.facebook.com/mariaalvaradolhs/" target="_blank" class="social-icon facebook" aria-label="Facebook"></a>
                    <a href="https://www.instagram.com/mariaalvaradolhs/" target="_blank" class="social-icon instagram" aria-label="Instagram"></a>
                    <a href="https://www.linkedin.com/company/52020413/admin/feed/posts/" target="_blank" class="social-icon linkedin" aria-label="Linkedin"></a>
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
