<?php
require_once __DIR__ . '/../config/db.php';

class Exalumno {
    public static function obtenerPorCorreo($correo) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM exalumno WHERE correo = :correo");
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function actualizar($data) {
        global $pdo;
        $stmt = $pdo->prepare("REPLACE INTO exalumno 
            (codigo, documentoidentidad, politica, termino_lhs, anio_retiro, color_promocion, anio_promocion, delegado_promocion, apellidos, nombres, pais_residencia, correo, celular, fecha_nacimiento, empresa_labora, cargo, permiso, profesion) 
            VALUES 
            (:codigo, :documentoidentidad, :politica, :termino_lhs, :anio_retiro, :color_promocion, :anio_promocion, :delegado_promocion, :apellidos, :nombres, :pais_residencia, :correo, :celular, :fecha_nacimiento, :empresa_labora, :cargo, :permiso, :profesion)");

        return $stmt->execute($data);
    }

    public static function insertar($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO exalumno 
            (codigo, documentoidentidad, politica, termino_lhs, anio_retiro, color_promocion, anio_promocion, delegado_promocion, apellidos, nombres, pais_residencia, correo, celular, fecha_nacimiento, empresa_labora, cargo, permiso, profesion) 
            VALUES 
            (:codigo, :documentoidentidad, :politica, :termino_lhs, :anio_retiro, :color_promocion, :anio_promocion, :delegado_promocion, :apellidos, :nombres, :pais_residencia, :correo, :celular, :fecha_nacimiento, :empresa_labora, :cargo, :permiso, :profesion)");

        return $stmt->execute($data);
    }

    public static function buscar($nombre, $apellidos, $profesion) {
        global $pdo;
        $sql = "SELECT * FROM exalumno WHERE 1=1";
        $params = [];
    
        // Búsqueda parcial en los nombres
        if (!empty($nombre)) {
            $sql .= " AND nombres LIKE :nombre";
            $params['nombre'] = "%$nombre%";
        }
    
        // Búsqueda parcial en los apellidos
        if (!empty($apellidos)) {
            $sql .= " AND apellidos LIKE :apellidos";
            $params['apellidos'] = "%$apellidos%";
        }
    
        // Búsqueda parcial en la profesión
        if (!empty($profesion)) {
            $sql .= " AND profesion LIKE :profesion";
            $params['profesion'] = "$profesion%";
        }
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
