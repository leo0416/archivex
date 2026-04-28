<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión globalmente
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../app/config/config.php';
require_once '../app/config/Logger.php';

// Autoload mejorado con rutas absolutas basadas en el directorio actual
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../app/';
    $paths = ['controllers/', 'models/'];
    
    foreach ($paths as $path) {
        $file = $baseDir . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Enrutamiento
$controllerParam = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Construir nombre del controlador (Ej: estante -> EstanteController)
$controllerName = ucfirst($controllerParam) . 'Controller';

// Protección de seguridad: Si no hay sesión y no es Auth, mandar al login
if (!isset($_SESSION['usuario_id']) && $controllerParam !== 'auth') {
    header("Location: index.php?controller=auth&action=index");
    exit;
}

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die("Error: La acción '$action' no existe en el controlador '$controllerName'.");
    }
} else {
    die("Error: El archivo del controlador existe pero la clase '$controllerName' no está definida dentro, o el archivo no se encuentra en app/controllers/.");
}