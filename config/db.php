<?php
$host = 'localhost';
$dbname = 'qcydeser_exalumnos_lhs';
$user = 'qcydeser_admin';
$password = '1hs.1906.cma';
#$dbname = 'exalumnos';
#$user = 'root';
#$password = '';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
