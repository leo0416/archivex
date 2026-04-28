<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivex - Gestión de Expedientes</title>
    <link rel="icon" type="image/png" href="public/img/favicon.png">
    <link rel="stylesheet" href="public/css/css/all.min.css">
    <style>
        :root { 
            --sidebar-width: 260px; 
            --header-height: auto; 
            --primary-color: #2c3e50; 
            --accent-color: #27ae60;
            --danger-color: #e74c3c;
            --text-muted: #bdc3c7;
            --bg-body: #f4f7f6;
        }

        * { box-sizing: border-box; }

        body { 
            margin: 0; 
            display: flex; 
            background: var(--bg-body); 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }
        
        .sidebar { 
            width: var(--sidebar-width); 
            background: var(--primary-color); 
            height: 100vh; 
            color: white; 
            position: fixed; 
            left: 0; top: 0;
            transition: all 0.3s ease;
            z-index: 1100;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header { 
            padding: 20px; 
            text-align: center; 
            background: #1a252f; 
            font-weight: bold;
            font-size: 1.4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo {
            width: 60px; height: 60px; border-radius: 50%;
            border: 2px solid var(--accent-color);
            background: white; object-fit: cover;
        }

        .menu-container { flex: 1; overflow-y: auto; padding-top: 10px; }

        .menu-item { 
            padding: 15px 25px; 
            display: flex;
            align-items: center;
            color: var(--text-muted); 
            text-decoration: none; 
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .menu-item:hover, .menu-item.active { 
            background: #34495e; 
            color: white; 
            border-left-color: var(--accent-color);
        }

        .menu-item i { margin-right: 15px; width: 20px; text-align: center; }

        .menu-ayuda { color: #a29bfe !important; }
        .menu-ayuda:hover, .menu-ayuda.active { color: white !important; }

        .main-content { 
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width)); 
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .header { 
            min-height: 65px;
            background: white; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 10px 20px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky; top: 0; z-index: 900;
            flex-wrap: wrap;
        }

        .search-container {
            flex: 1;
            max-width: 400px;
            margin: 0 20px;
        }

        .search-form { position: relative; display: flex; align-items: center; }

        .search-input {
            width: 100%;
            padding: 8px 15px 8px 35px;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: #f8fafc;
            outline: none;
        }

        .search-input:focus { border-color: var(--accent-color); background: white; }

        .search-icon { position: absolute; left: 12px; color: #94a3b8; }

        .menu-toggle {
            display: none; 
            background: var(--primary-color); 
            border: none; color: white;
            padding: 8px 12px; border-radius: 4px; cursor: pointer;
        }

        .container { padding: 20px; max-width: 1200px; margin: 0 auto; }

        .sidebar-overlay {
            display: none; position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 1050;
        }

        .badge-role {
            font-size: 0.7rem; padding: 2px 8px; border-radius: 10px;
            background: #e2e8f0; color: #4a5568; text-transform: uppercase;
        }

        @media (max-width: 992px) {
            .sidebar { left: -100%; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; width: 100%; }
            .menu-toggle { display: block; }
            .sidebar-overlay.active { display: block; }
            .header { justify-content: space-between; }
            .search-container { order: 3; max-width: 100%; margin: 10px 0 0 0; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="public/img/favicon.png" alt="Logo" class="sidebar-logo">
            ARCHIVEX
        </div>
        <div class="menu-container">
            <a href="index.php?controller=dashboard" class="menu-item"><i class="fas fa-chart-line"></i> Inicio</a>
            <a href="index.php?controller=militante&action=nuevo" class="menu-item"><i class="fas fa-file-medical"></i> Nuevo</a>
            <a href="index.php?controller=estante" class="menu-item"><i class="fas fa-boxes"></i> Estantes</a>
            <a href="index.php?controller=papelera&action=index" class="menu-item"><i class="fas fa-trash-restore"></i> Papelera</a>
            
            <a href="index.php?controller=ayuda&action=index" class="menu-item menu-ayuda">
                <i class="fas fa-question-circle"></i> Ayuda
            </a>

            <?php if(isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] === 'admin'): ?>
                <div style="padding: 20px 25px 5px; font-size: 0.75rem; color: #7f8c8d; text-transform: uppercase; letter-spacing: 1px;">SISTEMA</div>
                
                <a href="index.php?controller=backup" class="menu-item" style="color: #3498db;">
                    <i class="fas fa-database"></i> Respaldos SQL
                </a>
                <a href="index.php?controller=admin&action=nucleos" class="menu-item">
                    <i class="fas fa-microchip"></i> Gestión de Núcleos
                </a>
                <a href="index.php?controller=admin&action=condecoraciones" class="menu-item">
                    <i class="fas fa-medal"></i> Condecoraciones
                </a>
                <a href="index.php?controller=admin&action=usuarios" class="menu-item" style="color: #f1c40f;">
                    <i class="fas fa-user-shield"></i> Administración
                </a>
                <a href="index.php?controller=admin&action=verLogs" class="menu-item" style="color: #e67e22;">
                    <i class="fas fa-history"></i> Logs de Auditoría
                </a>
            <?php endif; ?>

            <a href="index.php?controller=auth&action=logout" class="menu-item" style="color: #e74c3c; margin-top: 20px;"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div class="search-container">
                <form action="index.php" method="GET" class="search-form">
                    <input type="hidden" name="controller" value="busqueda">
                    <input type="hidden" name="action" value="index">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" class="search-input" placeholder="Buscar por nombre, CI o centro..." required value="<?php echo $_GET['q'] ?? ''; ?>">
                </form>
            </div>
            
            <div class="user-meta" style="color: #7f8c8d; display: flex; align-items: center; gap: 15px;">
                <span class="d-none-mobile"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y'); ?></span>
                
                <div class="user-info" style="border-left: 1px solid #eee; padding-left: 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user-circle" style="color: var(--primary-color); font-size: 1.2rem;"></i> 
                    <strong><span><?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?></span></strong>
                    <span class="badge-role"><?php echo $_SESSION['usuario_rol'] ?? ''; ?></span>
                </div>
            </div>
        </div>

        <div class="container">
            <?php if(isset($view) && file_exists($view)) { include($view); } else { echo "Vista no encontrada."; } ?>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggle');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        if(menuToggle) menuToggle.addEventListener('click', toggleMenu);
        if(overlay) overlay.addEventListener('click', toggleMenu);

        const currentUrl = window.location.href;
        document.querySelectorAll('.menu-item').forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentUrl.includes(href)) {
                item.classList.add('active');
            }
        });
    </script>
</body>
</html>