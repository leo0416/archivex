<?php

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        // Si ya está logueado, redirigir según su rol para evitar que vuelva al login
        if (isset($_SESSION['usuario_id'])) {
            if ($_SESSION['usuario_rol'] === 'invitado') {
                header("Location: index.php?controller=invitado&action=index");
            } else {
                header("Location: index.php?controller=dashboard&action=index");
            }
            exit;
        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    /**
     * Login tradicional (Usuario y Contraseña)
     * Para Administradores y Operadores
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';

            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = ?");
            $stmt->execute([$usuario]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nombre'] = $user['nombre_completo'];
                $_SESSION['usuario_rol'] = $user['rol']; // 'admin' u 'operador'

                Logger::log("Inicio de sesión exitoso: " . $user['usuario']);

                // REDIRECCIÓN SEGÚN ROL
                if ($_SESSION['usuario_rol'] === 'invitado') {
                    header("Location: index.php?controller=invitado&action=index");
                } else {
                    header("Location: index.php?controller=dashboard&action=index");
                }
                
            } else {
                Logger::log("Intento de inicio de sesión fallido para el usuario: " . htmlspecialchars($usuario));
                
                $_SESSION['error'] = "Usuario o contraseña incorrectos.";
                header("Location: index.php?controller=auth&action=index");
            }
            exit;
        }
    }

    /**
     * Acceso rápido para Invitados
     * No requiere contraseña, solo permite consulta
     */
    public function invitado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Creamos una sesión de sistema para el invitado
            $_SESSION['usuario_id'] = 0; // ID 0 para identificar que no es un usuario de la tabla
            $_SESSION['usuario_nombre'] = 'Invitado de Consulta';
            $_SESSION['usuario_rol'] = 'invitado'; // CRITICAL: Esto activa los bloqueos en los otros controladores

            Logger::log("Acceso de INVITADO iniciado (Solo Consulta)");

            // Redirigir directamente a la interfaz de búsqueda de invitado
            header("Location: index.php?controller=invitado&action=index");
            exit;
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        Logger::log("Cerró sesión");

        $_SESSION = array();
        session_destroy();
        
        header("Location: index.php?controller=auth&action=index&msg=logout");
        exit;
    }
}