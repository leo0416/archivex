<?php
class EstanteModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function listarTodo() {
        $sql = "SELECT id, numero_consecutivo, capacidad FROM estantes ORDER BY numero_consecutivo ASC";
        $estantes = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($estantes as &$estante) {
            // Contar solo ubicaciones realmente ocupadas por militantes activos
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM ubicaciones WHERE estante_id = ? AND estado = 'ocupada'");
            $stmt->execute([$estante['id']]);
            $estante['ocupados'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // El JOIN ahora incluye la condición AND m.deleted_at IS NULL
            $sqlDetalle = "SELECT u.id, u.cajuela, u.posicion_global, u.estado, m.id as militante_id 
                           FROM ubicaciones u 
                           LEFT JOIN militantes m ON u.id = m.ubicacion_id AND m.deleted_at IS NULL 
                           WHERE u.estante_id = ? 
                           ORDER BY u.posicion_global ASC";
            
            $stmt = $this->db->prepare($sqlDetalle);
            $stmt->execute([$estante['id']]);
            $estante['detalles_ubicaciones'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $estantes;
    }

    public function obtenerUbicacionDisponible() {
        $sql = "SELECT id FROM ubicaciones WHERE estado = 'libre' ORDER BY estante_id ASC, posicion_global ASC LIMIT 1";
        $res = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        
        return $res ? $res['id'] : $this->generarNuevoEstante();
    }

    private function generarNuevoEstante() {
        $stmt = $this->db->query("SELECT MAX(numero_consecutivo) as ultimo FROM estantes");
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);
        $nuevoNum = ($ultimo['ultimo'] ?? 0) + 1;

        $this->db->prepare("INSERT INTO estantes (numero_consecutivo, capacidad) VALUES (?, 396)")->execute([$nuevoNum]);
        $estanteId = $this->db->lastInsertId();

        $this->db->beginTransaction();
        try {
            $stmtInsert = $this->db->prepare("INSERT INTO ubicaciones (estante_id, cajuela, posicion_global, estado) VALUES (?, ?, ?, 'libre')");
            $posicionesPorCajuela = 11;

            for ($i = 1; $i <= 396; $i++) {
                $cajuela = ceil($i / $posicionesPorCajuela);
                $stmtInsert->execute([$estanteId, $cajuela, $i]);
            }
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }

        $stmtFirst = $this->db->prepare("SELECT id FROM ubicaciones WHERE estante_id = ? AND posicion_global = 1 LIMIT 1");
        $stmtFirst->execute([$estanteId]);
        return $stmtFirst->fetch(PDO::FETCH_ASSOC)['id'];
    }
}