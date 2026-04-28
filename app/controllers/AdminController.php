<?php

class AdminController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // SEGURIDAD: Solo el rol 'admin' entra aquí
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            header("Location: index.php?controller=dashboard&action=index&error=permisos");
            exit;
        }
        $this->db = Database::getConnection();
    }

    /* --- GESTIÓN DE USUARIOS --- */

    public function usuarios() {
        $stmt = $this->db->query("SELECT id, usuario, nombre_completo, rol FROM usuarios ORDER BY rol ASC");
        $usuarios = $stmt->fetchAll();
        $view = __DIR__ . '/../../views/admin/usuarios.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }

    public function guardarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = trim($_POST['username']);
            $nombre = trim($_POST['nombre']);
            $rol = $_POST['rol'];
            
            if (empty($user) || empty($nombre) || empty($_POST['password'])) {
                header('Location: index.php?controller=admin&action=usuarios&error=empty_fields');
                exit;
            }

            $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("INSERT INTO usuarios (usuario, password_hash, nombre_completo, rol) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user, $pass, $nombre, $rol]);

            Logger::log("Creó al usuario: $user con rol $rol");

            header('Location: index.php?controller=admin&action=usuarios&msg=user_created');
        }
    }

    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['new_password'])) {
            $pass = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?");
            $stmt->execute([$pass, $_POST['user_id']]);

            Logger::log("Cambió la contraseña del usuario ID: " . $_POST['user_id']);

            header('Location: index.php?controller=admin&action=usuarios&msg=pass_ok');
        }
    }

    public function eliminarUsuario() {
        if (isset($_GET['id'])) {
            $idAEliminar = $_GET['id'];
            if ($idAEliminar == $_SESSION['usuario_id']) {
                header('Location: index.php?controller=admin&action=usuarios&error=self_delete');
                exit;
            }

            try {
                // Obtener nombre antes de borrar para el log
                $stmtName = $this->db->prepare("SELECT usuario FROM usuarios WHERE id = ?");
                $stmtName->execute([$idAEliminar]);
                $u = $stmtName->fetch();

                $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$idAEliminar]);

                Logger::log("Eliminó al usuario: " . ($u['usuario'] ?? $idAEliminar));

                header('Location: index.php?controller=admin&action=usuarios&msg=user_deleted');
            } catch (Exception $e) {
                header('Location: index.php?controller=admin&action=usuarios&error=fk_user');
            }
        }
    }

    /* --- GESTIÓN DE NÚCLEOS --- */

    public function nucleos() {
        $stmt = $this->db->query("SELECT * FROM nucleos ORDER BY numero_nucleo ASC");
        $nucleos = $stmt->fetchAll();
        $view = __DIR__ . '/../../views/admin/nucleos.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }

    public function guardarNucleo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $numero = trim($_POST['numero_nucleo']);
            $id = $_POST['id'] ?? null;

            if (empty($nombre) || !is_numeric($numero)) {
                header('Location: index.php?controller=admin&action=nucleos&error=invalid_data');
                exit;
            }

            if (!empty($id)) {
                $stmt = $this->db->prepare("UPDATE nucleos SET nombre = ?, numero_nucleo = ? WHERE id = ?");
                $stmt->execute([$nombre, $numero, $id]);
                Logger::log("Editó núcleo: $nombre (#$numero)");
            } else {
                $stmt = $this->db->prepare("INSERT INTO nucleos (nombre, numero_nucleo) VALUES (?, ?)");
                $stmt->execute([$nombre, $numero]);
                Logger::log("Creó nuevo núcleo: $nombre (#$numero)");
            }
            header('Location: index.php?controller=admin&action=nucleos&msg=success');
        }
    }

    public function eliminarNucleo() {
        if (isset($_GET['id'])) {
            try {
                $stmt = $this->db->prepare("DELETE FROM nucleos WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                Logger::log("Eliminó núcleo ID: " . $_GET['id']);
                header('Location: index.php?controller=admin&action=nucleos&msg=deleted');
            } catch (Exception $e) {
                header('Location: index.php?controller=admin&action=nucleos&error=fk');
            }
        }
    }

    /* --- GESTIÓN DE CONDECORACIONES --- */

    public function condecoraciones() {
        $stmt = $this->db->query("SELECT * FROM condecoraciones ORDER BY nombre ASC");
        $condecoraciones = $stmt->fetchAll();
        $view = __DIR__ . '/../../views/admin/condecoraciones.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }

    public function guardarCondecoracion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $id = $_POST['id'] ?? null;

            if (empty($nombre) || is_numeric($nombre)) {
                header('Location: index.php?controller=admin&action=condecoraciones&error=invalid_name');
                exit;
            }

            $check = $this->db->prepare("SELECT id FROM condecoraciones WHERE nombre = ? AND id != ?");
            $check->execute([$nombre, $id ? $id : 0]);
            if ($check->fetch()) {
                header('Location: index.php?controller=admin&action=condecoraciones&error=duplicate');
                exit;
            }

            if (!empty($id)) {
                $stmt = $this->db->prepare("UPDATE condecoraciones SET nombre = ? WHERE id = ?");
                $stmt->execute([$nombre, $id]);
                Logger::log("Editó condecoración: $nombre");
            } else {
                $stmt = $this->db->prepare("INSERT INTO condecoraciones (nombre) VALUES (?)");
                $stmt->execute([$nombre]);
                Logger::log("Creó condecoración: $nombre");
            }
            header('Location: index.php?controller=admin&action=condecoraciones&msg=success');
        }
    }

    public function eliminarCondecoracion() {
        if (isset($_GET['id'])) {
            try {
                $stmt = $this->db->prepare("DELETE FROM condecoraciones WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                Logger::log("Eliminó condecoración ID: " . $_GET['id']);
                header('Location: index.php?controller=admin&action=condecoraciones&msg=deleted');
            } catch (Exception $e) {
                header('Location: index.php?controller=admin&action=condecoraciones&error=fk');
            }
        }
    }

    /* --- SISTEMA DE AUDITORÍA (LOGS) --- */

    public function verLogs() {
        $usuario_id = $_GET['usuario_id'] ?? '';
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';

        // Ajustado a: fecha_hora, usuario_id, ip_address
        $sql = "SELECT l.*, u.nombre_completo as nombre_usuario_real 
                FROM logs l 
                LEFT JOIN usuarios u ON l.usuario_id = u.id 
                WHERE 1=1";
        
        $params = [];

        if (!empty($usuario_id)) {
            $sql .= " AND l.usuario_id = ?";
            $params[] = $usuario_id;
        }

        if (!empty($fecha_inicio)) {
            $sql .= " AND DATE(l.fecha_hora) >= ?";
            $params[] = $fecha_inicio;
        }

        if (!empty($fecha_fin)) {
            $sql .= " AND DATE(l.fecha_hora) <= ?";
            $params[] = $fecha_fin;
        }

        $sql .= " ORDER BY l.fecha_hora DESC LIMIT 1000";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = $this->db->query("SELECT id, nombre_completo FROM usuarios ORDER BY nombre_completo ASC")->fetchAll();

        $view = __DIR__ . '/../../views/admin/logs.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }

    public function exportarLogsExcel() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Logs_ArchiveX_' . date('Y-m-d_H-i') . '.csv');
        
        $output = fopen('php://output', 'w');
        // BOM para UTF-8 (Asegura que Excel lea bien los acentos)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['ID', 'Fecha y Hora', 'ID Usuario', 'Usuario', 'Acción', 'Dirección IP']);

        $stmt = $this->db->query("SELECT * FROM logs ORDER BY fecha_hora DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
        fclose($output);
        Logger::log("Exportó el historial de logs a Excel");
        exit;
    }

    /* --- SISTEMA DE BACKUP (PRÓXIMO PASO) --- */

    public function backup() {
        $view = __DIR__ . '/../../views/admin/backup.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }
}