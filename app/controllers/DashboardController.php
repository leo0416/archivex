<?php

class DashboardController {
    private $db;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=index");
            exit;
        }
        $this->db = Database::getConnection();
    }

    public function index() {
        try {
            // 1. Contar Militantes activos
            $stmtM = $this->db->query("SELECT COUNT(*) as total FROM militantes WHERE deleted_at IS NULL");
            $totalMilitantes = $stmtM->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // 2. Contar Estantes totales
            $stmtE = $this->db->query("SELECT COUNT(*) as total FROM estantes");
            $totalEstantes = $stmtE->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // 3. Contar Espacios Libres (Global)
            $stmtL = $this->db->query("SELECT COUNT(*) as total FROM ubicaciones WHERE estado = 'libre'");
            $totalLibres = $stmtL->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // 4. Contar elementos en Papelera
            $stmtP = $this->db->query("SELECT COUNT(*) as total FROM militantes WHERE deleted_at IS NOT NULL");
            $totalPapelera = $stmtP->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            $stats = [
                'militantes' => $totalMilitantes,
                'estantes'   => $totalEstantes,
                'libres'     => $totalLibres,
                'papelera'   => $totalPapelera
            ];

        } catch (PDOException $e) {
            // REGISTRO EN LOGS: Si falla la carga del dashboard, es un problema técnico serio
            Logger::log("Error crítico al cargar estadísticas del Dashboard: " . $e->getMessage());
            $stats = ['militantes' => 0, 'estantes' => 0, 'libres' => 0, 'papelera' => 0];
        }

        $view = '../views/dashboard/home.php';
        require_once '../views/layout/main.php';
    }
}