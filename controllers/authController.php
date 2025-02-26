<?php
session_start();
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = $_POST['identificador'];  // Ahora este campo puede ser el correo o el código
    $contrasena = $_POST['contrasena'];

    if ($_POST['action'] === 'login') {
        $usuario = Usuario::login($identificador, $contrasena);  // Llamada a la nueva función que acepta código o correo
        
        if ($usuario) {
            $_SESSION['correo'] = $usuario['correo'];  // Guardamos el correo en la sesión
            $_SESSION['codigo'] = $usuario['codigo'];
            $_SESSION['admin'] = $usuario['admin'];
            header('Location: ' . ($_SESSION['admin'] ? 'admin' : 'dashboard'));
        } else {
            $_SESSION['error'] = 'Código/Correo o contraseña incorrectos.';
            header('Location: login');
        }
        exit;
    } elseif ($_POST['action'] === 'register') {
        $correo = $_POST['correo'];
        if (Usuario::existeCorreo($correo)) {
            $_SESSION['error'] = 'Ya existe una cuenta asociada a este correo.';
            header('Location: registro');
        } else {
            if (Usuario::register($correo, $contrasena)) {
                $codigoUsuario = $pdo->lastInsertId(); // Obtener el código generado
                $_SESSION['codigo_autocompletar'] = $codigoUsuario; // Guardar el código en la sesión

                // Enviar email con PHPMailer
                enviarCorreoCredenciales($correo, $codigoUsuario, $contrasena);
                
                header('Location: login'); // Redirigir al login
            } else {
                $_SESSION['error'] = 'Error al registrar el usuario.';
                header('Location: registro');
            }
        }
        exit;
    }
}

/**
 * Enviar correo de credenciales al nuevo usuario
 */
function enviarCorreoCredenciales($correo, $codigo, $contrasena) {
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = 'cma.edu.pe';
        $mail->SMTPAuth = true;
        $mail->Username = 'exalumnos_registro@cma.edu.pe';  // Correo
        $mail->Password = 'Registro2024*';             // Contraseña
        //$mail->SMTPSecure = 'ssl';                            // Habilitar encriptación SSL*/
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;                            // Puerto para SSL
        

        // Remitente y destinatario
        $mail->setFrom('exalumnos_registro@cma.edu.pe', 'Exalumnos');
        $mail->addAddress($correo);  // Destinatario

        // Contenido del email
        $mail->isHTML(true);  // Establecer el formato del email a HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Credenciales Exalumno';
        $mail->Body    = "<h3>Se le brindan sus credenciales para entrar a Exalumnos</h3>
                          <p><strong>Codigo de Exalumno:</strong> $codigo</p>
                          <p><strong>Clave de accseso:</strong> $contrasena</p>
                          <p>Para iniciar sesion, en el primer campo puede utilizar su correo o codigo de exalumno. Luego ingresar el el segundo campo su clave de acceso.</p>";

        // Enviar el correo
        $mail->send();
    }catch (Exception $e) {
        // Manejo de errores en el envío de correo
        error_log("No se pudo enviar el correo. Error: {$mail->ErrorInfo}");
    }
}
?>
