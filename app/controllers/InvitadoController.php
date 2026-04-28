<?php

class InvitadoController {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Verificación de seguridad
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'invitado') {
            header("Location: index.php?controller=auth");
            exit;
        }
    }

    public function index() {
        $resultados = [];
        $busqueda = $_GET['q'] ?? '';
        $nucleo_id = $_GET['nucleo'] ?? '';
        $condecoracion_id = $_GET['condecoracion'] ?? '';

        // 1. Cargar catálogo de Núcleos (Usando tu columna 'nombre')
        $nucleos = $this->db->query("SELECT id, nombre FROM nucleos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

        // 2. Cargar catálogo de Condecoraciones (Usando tu tabla 'condecoraciones' y columna 'nombre')
        $condecoraciones = $this->db->query("SELECT id, nombre FROM condecoraciones ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

        // 3. Construir consulta principal con JOINs
        // Seleccionamos m.* y renombramos n.nombre como nombre_nucleo para que la vista no falle
        $query = "SELECT m.*, 
                         u.posicion_global, u.cajuela, 
                         e.numero_consecutivo as num_estante, 
                         n.nombre as nombre_nucleo
                  FROM militantes m
                  LEFT JOIN ubicaciones u ON m.ubicacion_id = u.id
                  LEFT JOIN estantes e ON u.estante_id = e.id
                  LEFT JOIN nucleos n ON m.nucleo_id = n.id
                  WHERE m.deleted_at IS NULL";

        $params = [];

        // Filtro por Nombre o CI
        if (!empty($busqueda)) {
            $query .= " AND (m.nombre_completo LIKE ? OR m.ci LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }

        // Filtro por Núcleo
        if (!empty($nucleo_id)) {
            $query .= " AND m.nucleo_id = ?";
            $params[] = $nucleo_id;
        }

        // Filtro por Condecoración 
        // (Nota: Aquí asumo que en la tabla militantes guardas el ID o el nombre de la condecoración)
        if (!empty($condecoracion_id)) {
            $query .= " AND m.condecoraciones LIKE ?";
            $params[] = "%$condecoracion_id%";
        }

        // Solo ejecutar si hay algún filtro activo para no saturar el servidor al entrar
        if (!empty($busqueda) || !empty($nucleo_id) || !empty($condecoracion_id)) {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Cargar la vista
        $view = __DIR__ . '/../../views/invitado/panel_consulta.php';
        require_once __DIR__ . '/../../views/layout/invitado.php';
    }
}