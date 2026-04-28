<?php
require_once __DIR__ . '/../../vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

class ReporteController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id'])) { 
            header("Location: index.php"); 
            exit; 
        }
        $this->db = Database::getConnection();
    }

    public function inventarioEstantes() {
        try {
            $sql = "SELECT e.numero_consecutivo, e.capacidad,
                    (SELECT COUNT(*) FROM ubicaciones WHERE estante_id = e.id AND estado = 'ocupada') as ocupados
                    FROM estantes e ORDER BY e.numero_consecutivo ASC";
            $estantes = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

            // REGISTRO EN LOGS
            Logger::log("Generó un reporte PDF de inventario general de estantes.");

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true); 
            $dompdf = new Dompdf($options);

            ob_start();
            include __DIR__ . '/../../views/reportes/pdf_estantes.php';
            $html = ob_get_clean();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream("Reporte_Inventario_Estantes.pdf", ["Attachment" => false]);
            exit;
        } catch (Exception $e) {
            Logger::log("Error al generar reporte de estantes: " . $e->getMessage());
            die("Error al generar el reporte: " . $e->getMessage());
        }
    }

    public function fichaMilitante() {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) die("ID de militante no proporcionado.");

            // Consulta completa incluyendo ubicación física
            $sql = "SELECT m.*, e.numero_consecutivo as num_estante, u.cajuela, u.posicion_global 
                    FROM militantes m
                    LEFT JOIN ubicaciones u ON m.ubicacion_id = u.id
                    LEFT JOIN estantes e ON u.estante_id = e.id
                    WHERE m.id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $militante = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$militante) die("Militante no encontrado.");

            // REGISTRO EN LOGS DETALLADO
            Logger::log("Generó/Imprimió ficha PDF de: " . $militante['nombre_completo'] . " | CI: " . $militante['ci']);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true); 
            $dompdf = new Dompdf($options);
            
            ob_start();
            include __DIR__ . '/../../views/reportes/pdf_ficha_militante.php';
            $html = ob_get_clean();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $nombreArchivo = "Ficha_" . str_replace(' ', '_', $militante['nombre_completo']) . ".pdf";
            $dompdf->stream($nombreArchivo, ["Attachment" => false]);
            exit;

        } catch (Exception $e) {
            Logger::log("Error al generar ficha PDF de ID $id: " . $e->getMessage());
            die("Error al generar la ficha del militante: " . $e->getMessage());
        }
    }
}