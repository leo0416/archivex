<?php
class Logger {
    /**
     * Registra una acción en la base de datos
     * @param string $accion Descripción de lo que hizo el usuario
     */
    public static function log($accion) {
        // Asegurar que la sesión esté disponible para saber quién es el usuario
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        try {
            $db = Database::getConnection();
            $usuario_id = $_SESSION['usuario_id'] ?? null;
            $usuario_nombre = $_SESSION['usuario_nombre'] ?? 'Sistema/Anónimo';
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

            $stmt = $db->prepare("INSERT INTO logs (usuario_id, usuario_nombre, accion, ip_address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $usuario_nombre, $accion, $ip]);
        } catch (Exception $e) {
            // Si falla el log, que no se detenga la aplicación
            error_log("Error en el sistema de logs: " . $e->getMessage());
        }
    }
}