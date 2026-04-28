<?php

class BackupController {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Verificación de seguridad: solo administradores
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'admin') {
            header("Location: index.php?controller=dashboard&action=index&error=permisos");
            exit;
        }
        $this->db = Database::getConnection();
    }

    public function index() {
        // Obtenemos el historial de backups con los datos del usuario que lo generó
        $stmt = $this->db->query("SELECT b.*, u.usuario as nick, u.nombre_completo as nombre_usuario 
                                  FROM backups b 
                                  LEFT JOIN usuarios u ON b.usuario_id = u.id 
                                  ORDER BY b.fecha DESC");
        $backups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $view = __DIR__ . '/../../views/admin/backups.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }

    public function generar() {
        try {
            // 1. Obtener listado de todas las tablas
            $tablas = [];
            $result = $this->db->query("SHOW TABLES");
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tablas[] = $row[0];
            }

            $sqlContent = "-- Backup ArchiveX - " . date('Y-m-d H:i:s') . "\n";
            $sqlContent .= "-- Generado por: " . $_SESSION['usuario_nombre'] . "\n\n";
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            // 2. Iterar por cada tabla para extraer estructura y datos
            foreach ($tablas as $tabla) {
                // No respaldar la tabla logs para evitar que el backup sea infinitamente grande (Opcional)
                // if ($tabla == 'logs') continue;

                // Crear estructura (DROP y CREATE)
                $result = $this->db->query("SHOW CREATE TABLE `$tabla` ");
                $row = $result->fetch(PDO::FETCH_NUM);
                $sqlContent .= "DROP TABLE IF EXISTS `$tabla`;\n";
                $sqlContent .= $row[1] . ";\n\n";

                // Extraer datos
                $result = $this->db->query("SELECT * FROM `$tabla` ");
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $columnas = array_keys($row);
                    $valores = array_values($row);
                    $valoresFormat = array_map(function($v) {
                        if (is_null($v)) return 'NULL';
                        return "'" . addslashes($v) . "'";
                    }, $valores);
                    
                    $sqlContent .= "INSERT INTO `$tabla` (`" . implode('`,`', $columnas) . "`) VALUES (" . implode(',', $valoresFormat) . ");\n";
                }
                $sqlContent .= "\n";
            }
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;";

            // 3. Guardar el archivo físicamente
            $nombreArchivo = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $rutaCarpeta = __DIR__ . '/../../backups/';
            $rutaCompleta = $rutaCarpeta . $nombreArchivo;

            if (!is_dir($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0777, true);
            }
            
            file_put_contents($rutaCompleta, $sqlContent);

            // 4. Registrar en la base de datos de historial de backups
            $stmt = $this->db->prepare("INSERT INTO backups (ruta_archivo, usuario_id) VALUES (?, ?)");
            $stmt->execute([$nombreArchivo, $_SESSION['usuario_id']]);

            // REGISTRO EN LOGS
            Logger::log("Generó un respaldo manual de la base de datos: $nombreArchivo");

            header("Location: index.php?controller=backup&action=index&msg=generado");
            exit;
        } catch (Exception $e) {
            Logger::log("Error al intentar generar un backup: " . $e->getMessage());
            die("Error al generar backup: " . $e->getMessage());
        }
    }

    public function importar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sql_file'])) {
            $archivo = $_FILES['sql_file'];
            
            // Validación básica de extensión
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            if ($extension !== 'sql') {
                header("Location: index.php?controller=backup&action=index&error=formato_invalido");
                exit;
            }

            try {
                $fileContent = file_get_contents($archivo['tmp_name']);
                
                // Desactivar restricciones para evitar errores de integridad durante la carga
                $this->db->exec("SET FOREIGN_KEY_CHECKS=0;");
                
                // Ejecutar el script SQL
                // Nota: exec() puede tener límites con archivos muy grandes, pero para este sistema funcionará bien.
                $this->db->exec($fileContent);
                
                $this->db->exec("SET FOREIGN_KEY_CHECKS=1;");

                // REGISTRO EN LOGS (Crítico)
                Logger::log("RESTAURÓ la base de datos usando el archivo: " . $archivo['name']);

                header("Location: index.php?controller=backup&action=index&msg=importado");
                exit;
            } catch (Exception $e) {
                Logger::log("FALLO CRÍTICO al importar base de datos: " . $e->getMessage());
                die("Error crítico al importar la base de datos: " . $e->getMessage());
            }
        }
    }
}