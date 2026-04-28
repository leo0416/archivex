<?php
require_once '../app/models/EstanteModel.php';

class MilitanteController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Seguridad: Bloqueo de acceso si no hay sesión
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=index");
            exit;
        }
    }

    // Método auxiliar para denegar acceso a invitados en funciones de escritura
    private function checkWriteAccess() {
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'invitado') {
            Logger::log("INTENTO NO AUTORIZADO: Un invitado intentó realizar una acción de escritura/borrado.");
            die("Error: No tienes permisos suficientes para realizar esta acción.");
        }
    }

    public function nuevo() {
        $this->checkWriteAccess(); // Bloqueo para invitados
        
        $db = Database::getConnection();
        $estanteModel = new EstanteModel();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $ci = trim($_POST['ci']);
                $nombre1 = trim($_POST['nombre1']);
                $nombre2 = trim($_POST['nombre2'] ?? '');
                $apellido1 = trim($_POST['apellido1']);
                $apellido2 = trim($_POST['apellido2']);
                $nombre_completo = trim("$nombre1 $nombre2 $apellido1 $apellido2");
                
                $stmtCheck = $db->prepare("SELECT id FROM militantes WHERE ci = ? AND deleted_at IS NULL LIMIT 1");
                $stmtCheck->execute([$ci]);
                if ($stmtCheck->fetch()) {
                    header('Location: index.php?controller=militante&action=nuevo&error=ci_duplicado');
                    exit;
                }

                $stmtValidas = $db->query("SELECT nombre FROM condecoraciones");
                $oficiales = $stmtValidas->fetchAll(PDO::FETCH_COLUMN);
                $condecoracionesString = "";
                if (isset($_POST['condecoraciones']) && is_array($_POST['condecoraciones'])) {
                    $listaLimpia = array_unique(array_filter($_POST['condecoraciones'], function($v) use ($oficiales) {
                        return in_array(trim($v), $oficiales);
                    }));
                    $condecoracionesString = implode(" | ", $listaLimpia);
                }

                $ubicacionId = $estanteModel->obtenerUbicacionDisponible();

                $sql = "INSERT INTO militantes (
                            nombre1, nombre2, apellido1, apellido2, nombre_completo, 
                            ci, fecha_nacimiento, sexo, color_piel, fecha_pcc, 
                            nucleo_id, centro_trabajo, cargo, nivel_escolar, graduado_de, 
                            direccion, telefono, condecoraciones, ubicacion_id, creado_at
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    $nombre1, $nombre2 ?: null, $apellido1, $apellido2, $nombre_completo, 
                    $ci, $_POST['fecha_nac'], $_POST['sexo'], $_POST['color_piel'], $_POST['fecha_pcc'], 
                    $_POST['nucleo_id'], $_POST['centro_trabajo'], $_POST['cargo'], $_POST['nivel_escolar'], 
                    ($_POST['nivel_escolar'] === 'Superior' ? $_POST['graduado_de'] : null), 
                    $_POST['direccion'], $_POST['telefono'], $condecoracionesString, $ubicacionId
                ]);

                $db->prepare("UPDATE ubicaciones SET estado = 'ocupada' WHERE id = ?")->execute([$ubicacionId]);
                
                Logger::log("CREÓ el expediente de: $nombre_completo | CI: $ci");

                header('Location: index.php?controller=estante&mensaje=expediente_creado');
                exit;

            } catch (Exception $e) {
                Logger::log("Error al crear expediente: " . $e->getMessage());
                die("Error al crear expediente.");
            }
        } else {
            $nucleos = $db->query("SELECT id, nombre, numero_nucleo FROM nucleos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
            $listaCondecoraciones = $db->query("SELECT nombre FROM condecoraciones ORDER BY nombre ASC")->fetchAll(PDO::FETCH_COLUMN);
            $view = '../views/expedientes/nuevo.php';
            require_once '../views/layout/main.php';
        }
    }

    public function ver() {
        if (isset($_GET['id'])) {
            $db = Database::getConnection();
            $sql = "SELECT m.*, u.posicion_global, u.cajuela, e.numero_consecutivo as num_estante 
                    FROM militantes m
                    JOIN ubicaciones u ON m.ubicacion_id = u.id
                    JOIN estantes e ON u.estante_id = e.id
                    WHERE m.id = ? AND m.deleted_at IS NULL";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$_GET['id']]);
            $militante = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($militante) {
                $view = '../views/expedientes/ver.php';
                require_once '../views/layout/main.php';
            } else {
                header('Location: index.php?controller=estante&error=no_encontrado');
            }
        }
    }

    public function eliminar() {
        $this->checkWriteAccess(); // Bloqueo para invitados

        if (isset($_GET['id'])) {
            $db = Database::getConnection();
            try {
                $id = $_GET['id'];
                $stmt = $db->prepare("SELECT nombre_completo, ci, ubicacion_id FROM militantes WHERE id = ?");
                $stmt->execute([$id]);
                $militante = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($militante) {
                    $ubicacionId = $militante['ubicacion_id'];
                    $db->beginTransaction();
                    
                    $db->prepare("UPDATE militantes SET deleted_at = NOW(), ubicacion_id = NULL WHERE id = ?")->execute([$id]);
                    
                    if ($ubicacionId) {
                        $db->prepare("UPDATE ubicaciones SET estado = 'libre' WHERE id = ?")->execute([$ubicacionId]);
                    }
                    
                    Logger::log("ELIMINÓ (Papelera) el expediente de: " . $militante['nombre_completo'] . " | CI: " . $militante['ci']);
                    
                    $db->commit();
                    header('Location: index.php?controller=estante&mensaje=movido_a_papelera');
                } else {
                    header('Location: index.php?controller=estante&error=no_encontrado');
                }
                exit;
            } catch (Exception $e) {
                if ($db->inTransaction()) $db->rollBack();
                Logger::log("Error al eliminar ID $id: " . $e->getMessage());
                die("Error al eliminar.");
            }
        }
    }

    public function editar() {
        $this->checkWriteAccess(); // Bloqueo para invitados

        $db = Database::getConnection();
        $id = $_GET['id'] ?? null;

        if (!$id) { header('Location: index.php?controller=estante'); exit; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $ci = trim($_POST['ci']);
                $nombre_completo = trim($_POST['nombre1'] . ' ' . ($_POST['nombre2'] ?? '') . ' ' . $_POST['apellido1'] . ' ' . $_POST['apellido2']);

                $stmtValidas = $db->query("SELECT nombre FROM condecoraciones");
                $oficiales = $stmtValidas->fetchAll(PDO::FETCH_COLUMN);
                $condecoracionesString = "";
                if (isset($_POST['condecoraciones']) && is_array($_POST['condecoraciones'])) {
                    $listaLimpia = array_unique(array_filter($_POST['condecoraciones'], function($v) use ($oficiales) {
                        return in_array(trim($v), $oficiales);
                    }));
                    $condecoracionesString = implode(" | ", $listaLimpia);
                }

                $sql = "UPDATE militantes SET 
                        nombre1=?, nombre2=?, apellido1=?, apellido2=?, nombre_completo=?, ci=?,
                        fecha_nacimiento=?, sexo=?, color_piel=?, fecha_pcc=?, 
                        nucleo_id=?, centro_trabajo=?, cargo=?, nivel_escolar=?, graduado_de=?, 
                        direccion=?, telefono=?, condecoraciones=?
                        WHERE id=? AND deleted_at IS NULL";
                
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    $_POST['nombre1'], $_POST['nombre2'], $_POST['apellido1'], $_POST['apellido2'],
                    $nombre_completo, $ci, $_POST['fecha_nac'], $_POST['sexo'], $_POST['color_piel'],
                    $_POST['fecha_pcc'], $_POST['nucleo_id'], $_POST['centro_trabajo'],
                    $_POST['cargo'], $_POST['nivel_escolar'], 
                    ($_POST['nivel_escolar'] === 'Superior' ? $_POST['graduado_de'] : null),
                    $_POST['direccion'], $_POST['telefono'], $condecoracionesString, $id
                ]);

                Logger::log("EDITÓ los datos del expediente: $nombre_completo | CI: $ci");

                header("Location: index.php?controller=militante&action=ver&id=$id&mensaje=actualizado");
                exit;
            } catch (Exception $e) {
                Logger::log("Error al editar ID $id: " . $e->getMessage());
                die("Error al actualizar.");
            }
        } else {
            $stmt = $db->prepare("SELECT * FROM militantes WHERE id = ? AND deleted_at IS NULL");
            $stmt->execute([$id]);
            $militante = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$militante) { header('Location: index.php?controller=estante&error=no_encontrado'); exit; }

            $nucleos = $db->query("SELECT id, nombre, numero_nucleo FROM nucleos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
            $listaCondecoraciones = $db->query("SELECT nombre FROM condecoraciones ORDER BY nombre ASC")->fetchAll(PDO::FETCH_COLUMN); 
            
            $view = '../views/expedientes/editar.php';
            require_once '../views/layout/main.php';
        }
    }
}