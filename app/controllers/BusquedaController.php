<?php

class BusquedaController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=index");
            exit;
        }
        $this->db = Database::getConnection();
    }

    public function index() {
        $termino = isset($_GET['q']) ? trim($_GET['q']) : '';
        $resultados = [];

        if (!empty($termino)) {
            // Eliminamos el filtro de deleted_at para que busque en TODO
            // Seleccionamos también 'm.deleted_at' para poder marcar visualmente en la vista quién está borrado
            $sql = "SELECT m.*, e.numero_consecutivo as num_estante, u.cajuela 
                    FROM militantes m
                    LEFT JOIN ubicaciones u ON m.ubicacion_id = u.id
                    LEFT JOIN estantes e ON u.estante_id = e.id
                    WHERE m.nombre_completo LIKE ? 
                       OR m.ci LIKE ? 
                       OR m.centro_trabajo LIKE ?
                    LIMIT 100"; // Subimos un poco el límite ya que ahora hay más datos
            
            $stmt = $this->db->prepare($sql);
            $likeTermino = "%$termino%";
            $stmt->execute([$likeTermino, $likeTermino, $likeTermino]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // REGISTRO EN LOGS: Auditoría de búsqueda global
            Logger::log("Realizó una búsqueda GLOBAL (activos y papelera): '$termino'");
        }

        // Definimos la vista y cargamos el layout principal
        $view = __DIR__ . '/../../views/busqueda/resultados.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }
}