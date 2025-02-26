<?php
$request = $_SERVER['REQUEST_URI'];

// Elimina el prefijo /exalumnos/ para tener solo la ruta de la página
$request = str_replace('/exalumnos', '', $request);

// Si la solicitud contiene parámetros de consulta, los ignoramos en el enrutamiento principal
$path = parse_url($request, PHP_URL_PATH);

switch ($path) { // Cambiado a $path en lugar de $request
    case '/' :
    case '' :
    case '/login' :
        require __DIR__ . '/views/login.php';
        break;
    case '/admin' :
        require __DIR__ . '/views/admin.php';
        break;
    case '/dashboard' :
        require __DIR__ . '/views/exalumno_form.php';
        break;
    case '/registro' :
        require __DIR__ . '/views/registro.php';
        break;
    case '/confirmacion' :
        require __DIR__ . '/views/confirmacion.php';
        break;
    case '/logout' :
        require __DIR__ . '/logout.php';
        break;
    case '/auth':
        require __DIR__ . '/controllers/authController.php';
        break;
    case '/processform':
        require __DIR__ . '/controllers/procesar_formulario.php';
        break;
    case '/confirmar':
        require __DIR__ . '/views/confirmacion.php';
        break;
    case '/exportar':
        require __DIR__ . '/controllers/exportar_excel.php';        
        break;
    default:
        http_response_code(404);
        echo "ERROR 404 - Página no encontrada";
        break;
}
?>
