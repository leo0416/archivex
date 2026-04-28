<?php

class AyudaController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit;
        }
    }

    // Ahora todas las acciones cargan la misma vista centralizada
    public function index() {
        $titulo = "Centro de Ayuda ArchiveX";
        $view = __DIR__ . '/../../views/ayuda/index.php';
        require_once __DIR__ . '/../../views/layout/main.php';
    }

    public function guias() { $this->index(); }
    public function faq() { $this->index(); }
    public function contacto() { $this->index(); }
}