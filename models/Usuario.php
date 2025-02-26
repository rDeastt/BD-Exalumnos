<?php
require_once __DIR__ . '/../config/db.php'; // Asegurarse de incluir el archivo de conexión

class Usuario {
    public static function login($identificador, $contrasena) {
        global $pdo; // Declarar la variable global

        // Verificar si el identificador es un correo o un código numérico
        if (filter_var($identificador, FILTER_VALIDATE_EMAIL)) {
            // Si es un correo
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE correo = :correo");
            $stmt->execute(['correo' => $identificador]);
        } else {
            // Si es un código
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE codigo = :codigo");
            $stmt->execute(['codigo' => $identificador]);
        }

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }

        return false;
    }

    public static function register($correo, $contrasena) {
        global $pdo; // Declarar la variable global
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuario (correo, contrasena, admin) VALUES (:correo, :contrasena, 0)");
        return $stmt->execute(['correo' => $correo, 'contrasena' => $hash]);
    }

    public static function existeCorreo($correo) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
