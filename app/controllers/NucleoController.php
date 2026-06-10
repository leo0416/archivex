<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class NucleoController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Solo admin y operador pueden acceder
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] === 'invitado') {
            header("Location: index.php?controller=auth&action=index");
            exit;
        }
        $this->db = Database::getConnection();
    }

    /**
     * Vista principal: selector de núcleo y listado de militantes (si se selecciona uno)
     */
    public function index() {
        // Obtener todos los núcleos para el select
        $stmt = $this->db->query("SELECT id, nombre, numero_nucleo FROM nucleos ORDER BY numero_nucleo ASC");
        $nucleos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $militantes = [];
        $nucleo_seleccionado = null;

        if (isset($_GET['nucleo_id']) && is_numeric($_GET['nucleo_id'])) {
            $nucleo_id = $_GET['nucleo_id'];
            // Obtener datos del núcleo seleccionado
            $stmtN = $this->db->prepare("SELECT * FROM nucleos WHERE id = ?");
            $stmtN->execute([$nucleo_id]);
            $nucleo_seleccionado = $stmtN->fetch(PDO::FETCH_ASSOC);

            if ($nucleo_seleccionado) {
                // Obtener militantes activos (no eliminados) de ese núcleo, con ubicación
                $sql = "SELECT m.id, m.nombre_completo, m.ci, 
                               u.posicion_global, u.cajuela, e.numero_consecutivo as num_estante
                        FROM militantes m
                        LEFT JOIN ubicaciones u ON m.ubicacion_id = u.id
                        LEFT JOIN estantes e ON u.estante_id = e.id
                        WHERE m.nucleo_id = ? AND m.deleted_at IS NULL
                        ORDER BY m.nombre_completo ASC";
                $stmtM = $this->db->prepare($sql);
                $stmtM->execute([$nucleo_id]);
                $militantes = $stmtM->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // Cargar la vista
        $view = __DIR__ . '/../../views/nucleos/lista_militantes.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }

    /**
     * Genera PDF con el listado de militantes de un núcleo específico
     */
    public function pdfListado() {
        if (!isset($_GET['nucleo_id']) || !is_numeric($_GET['nucleo_id'])) {
            die("Núcleo no especificado.");
        }

        $nucleo_id = $_GET['nucleo_id'];

        // Obtener datos del núcleo
        $stmtN = $this->db->prepare("SELECT * FROM nucleos WHERE id = ?");
        $stmtN->execute([$nucleo_id]);
        $nucleo = $stmtN->fetch(PDO::FETCH_ASSOC);
        if (!$nucleo) {
            die("Núcleo no encontrado.");
        }

        // Obtener militantes activos del núcleo con ubicación
        $sql = "SELECT m.nombre_completo, m.ci, 
                       u.posicion_global, u.cajuela, e.numero_consecutivo as num_estante
                FROM militantes m
                LEFT JOIN ubicaciones u ON m.ubicacion_id = u.id
                LEFT JOIN estantes e ON u.estante_id = e.id
                WHERE m.nucleo_id = ? AND m.deleted_at IS NULL
                ORDER BY m.nombre_completo ASC";
        $stmtM = $this->db->prepare($sql);
        $stmtM->execute([$nucleo_id]);
        $militantes = $stmtM->fetchAll(PDO::FETCH_ASSOC);

        // Registrar en logs
        Logger::log("Generó PDF del listado de militantes del núcleo: " . $nucleo['nombre'] . " (ID: $nucleo_id)");

        // Configurar DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        ob_start();
        include __DIR__ . '/../../views/nucleos/pdf_listado.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // horizontal para más columnas
        $dompdf->render();

        $nombreArchivo = "Listado_Nucleo_" . preg_replace('/[^a-zA-Z0-9]/', '_', $nucleo['nombre']) . ".pdf";
        $dompdf->stream($nombreArchivo, ["Attachment" => false]);
        exit;
    }
}