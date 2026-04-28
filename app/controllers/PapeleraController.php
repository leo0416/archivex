<?php
require_once '../app/models/EstanteModel.php';

class PapeleraController {
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
        $busqueda = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        $sql = "SELECT id, ci, nombre_completo, deleted_at 
                FROM militantes 
                WHERE deleted_at IS NOT NULL";
        
        $params = [];
        
        if ($busqueda !== '') {
            $sql .= " AND (ci LIKE ? OR nombre_completo LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }
        
        $sql .= " ORDER BY deleted_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $eliminados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view = '../views/papelera/index.php';
        require_once '../views/layout/main.php';
    }

    public function ver() {
        if (isset($_GET['id'])) {
            $sql = "SELECT * FROM militantes WHERE id = ? AND deleted_at IS NOT NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$_GET['id']]);
            $militante = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($militante) {
                $view = '../views/papelera/ver.php';
                require_once '../views/layout/main.php';
            } else {
                header('Location: index.php?controller=papelera&error=no_encontrado');
                exit;
            }
        }
    }

    public function restaurar() {
        if (isset($_GET['id'])) {
            try {
                $id = $_GET['id'];
                
                // 1. Obtener datos antes de restaurar para el LOG y verificación
                $stmt = $this->db->prepare("SELECT nombre_completo, ci FROM militantes WHERE id = ? AND deleted_at IS NOT NULL");
                $stmt->execute([$id]);
                $militante = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$militante) {
                    header('Location: index.php?controller=papelera&error=no_encontrado');
                    exit;
                }

                $this->db->beginTransaction();

                // 2. Buscar una nueva ubicación física libre
                $estanteModel = new EstanteModel();
                $nuevaUbicacionId = $estanteModel->obtenerUbicacionDisponible();

                // 3. Restaurar registro y asignar el nuevo estante
                $stmtRestore = $this->db->prepare("UPDATE militantes SET deleted_at = NULL, ubicacion_id = ? WHERE id = ?");
                $stmtRestore->execute([$nuevaUbicacionId, $id]);

                // 4. Marcar el nuevo espacio físico como 'ocupado'
                $stmtUpdateUbi = $this->db->prepare("UPDATE ubicaciones SET estado = 'ocupada' WHERE id = ?");
                $stmtUpdateUbi->execute([$nuevaUbicacionId]);

                // REGISTRO EN LOGS: Detallado con Nombre y CI
                Logger::log("RESTAURÓ el expediente de: " . $militante['nombre_completo'] . " | CI: " . $militante['ci']);

                $this->db->commit();

                header('Location: index.php?controller=papelera&mensaje=restaurado_exitoso');
                exit;

            } catch (Exception $e) {
                if ($this->db->inTransaction()) $this->db->rollBack();
                Logger::log("Error al intentar restaurar ID $id: " . $e->getMessage());
                die("Error al restaurar: " . $e->getMessage());
            }
        }
    }
}