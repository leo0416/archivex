<?php
require_once '../app/models/EstanteModel.php';

class EstanteController {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=index");
            exit;
        }

        // BLOQUEO: Los invitados no deben navegar por la lista de estantes física
        // Solo pueden ver expedientes específicos mediante la búsqueda.
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'invitado') {
            header("Location: index.php?controller=invitado&action=index");
            exit;
        }
    }

    public function index() {
        // Instanciamos el modelo para obtener la estructura de estantes y ubicaciones
        $model = new EstanteModel();
        $estantes = $model->listarTodo(); 
        
        // Cargamos la vista dentro del layout principal
        $view = '../views/estantes/lista.php';
        require_once '../views/layout/main.php';
    }
}