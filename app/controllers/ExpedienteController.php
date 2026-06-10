<?php

class ExpedienteController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Seguridad: Si no hay sesión, al login
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=index");
            exit;
        }
        $this->db = Database::getConnection();
    }

    public function ver() {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                header("Location: index.php?controller=dashboard&action=index&error=id_requerido");
                exit;
            }

            // Consulta completa uniendo militante, ubicación, estante y NÚCLEO
            $sql = "SELECT m.*, 
                           e.numero_consecutivo as num_estante, 
                           u.cajuela, 
                           u.posicion_global,
                           n.nombre as nombre_nucleo
                    FROM militantes m
                    LEFT JOIN ubicaciones u ON m.ubicacion_id = u.id
                    LEFT JOIN estantes e ON u.estante_id = e.id
                    LEFT JOIN nucleos n ON m.nucleo_id = n.id
                    WHERE m.id = ? AND m.deleted_at IS NULL";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $militante = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$militante) {
                header("Location: index.php?controller=dashboard&action=index&error=no_existe");
                exit;
            }

            // REGISTRO EN LOGS: Auditoría de acceso a datos sensibles
            Logger::log("Consultó el expediente detallado de: " . $militante['nombre_completo'] . " (ID: $id)");

            // Definimos la vista para el layout main.php
            $view = __DIR__ . '/../../views/expedientes/ver.php';
            require_once __DIR__ . '/../../views/layout/main.php';

        } catch (Exception $e) {
            Logger::log("Error al intentar ver expediente ID $id: " . $e->getMessage());
            die("Error al cargar el expediente: " . $e->getMessage());
        }
    }
}