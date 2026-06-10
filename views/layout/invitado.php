<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivex - Acceso Invitado</title>
    <link rel="icon" type="image/png" href="public/img/favicon.png">
    <link rel="stylesheet" href="public/css/css/all.min.css">
    <style>
        :root { 
            --sidebar-width: 260px; 
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
        
        /* --- Sidebar --- */
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

        .menu-container { flex: 1; overflow-y: auto; padding-top: 10px; display: flex; flex-direction: column; }

        .menu-item { 
            padding: 15px 25px; 
            display: flex;
            align-items: center;
            color: var(--text-muted); 
            text-decoration: none; 
            transition: 0.3s;
            border-left: 4px solid transparent;
        }

        .menu-item:hover { 
            background: #34495e; 
            color: white; 
            border-left-color: var(--accent-color);
        }

        /* Estilo especial para el botón de salir en el menú */
        .menu-item.logout-item:hover {
            border-left-color: var(--danger-color);
            color: var(--danger-color);
        }

        .menu-item i { margin-right: 15px; width: 20px; text-align: center; }

        /* --- Contenido Principal y Header --- */
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
        }

        .header-left { display: flex; align-items: center; gap: 15px; }
        .header-title { margin: 0; color: var(--primary-color); font-size: 1.2rem; white-space: nowrap; }
        
        .user-meta { color: #7f8c8d; display: flex; align-items: center; gap: 15px; font-size: 0.95rem; }
        .user-info { border-left: 1px solid #eee; padding-left: 15px; display: flex; align-items: center; gap: 8px; }
        
        .badge-role {
            font-size: 0.7rem; padding: 3px 8px; border-radius: 10px;
            background: #e2e8f0; color: #4a5568; text-transform: uppercase;
            font-weight: bold;
        }

        .menu-toggle {
            display: none; 
            background: var(--primary-color); 
            border: none; color: white;
            padding: 8px 12px; border-radius: 4px; cursor: pointer;
        }

        .container { padding: 20px; max-width: 1200px; margin: 0 auto; }

        .sidebar-overlay {
            visibility: hidden; 
            opacity: 0;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 1050;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.active { visibility: visible; opacity: 1; backdrop-filter: blur(2px); }

        /* --- Media Queries --- */
        @media (max-width: 992px) {
            .sidebar { left: -100%; box-shadow: 4px 0 15px rgba(0,0,0,0.2); }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0; width: 100%; }
            .menu-toggle { display: block; }
        }

        @media (max-width: 768px) {
            .d-none-tablet { display: none !important; }
            .user-info { border-left: none; padding-left: 0; }
        }

        @media (max-width: 480px) {
            .header-title { font-size: 1rem; }
            .badge-role { display: none; }
            .user-info strong { font-size: 0.85rem; }
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
            <!-- Botón de Consulta -->
            <a href="index.php?controller=invitado" class="menu-item">
                <i class="fas fa-search"></i> Consultas
            </a>

            <!-- Botón de Salir (Posicionado arriba como pediste) -->
            <a href="index.php?controller=auth&action=logout" class="menu-item logout-item">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>

            <div style="flex: 1;"></div> 
            
            <div style="padding: 20px; font-size: 0.7rem; color: #7f8c8d; text-align: center;">
                &copy; 2026 Archivex v1.0
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="header-title">Sistema de Archivo</h4>
            </div>
            
            <div class="user-meta">
                <span class="d-none-tablet"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y'); ?></span>
                
                <div class="user-info">
                    <i class="fas fa-user-circle" style="color: var(--primary-color); font-size: 1.3rem;"></i> 
                    <!-- Texto forzado a Invitado -->
                    <strong><span>Invitado</span></strong>
                    <span class="badge-role">Invitado</span>
                </div>
            </div>
        </div>

        <div class="container">
            <?php 
                if(isset($view) && file_exists($view)) { 
                    include($view); 
                } else { 
                    echo "
                    <div style='text-align:center; padding: 50px 20px; background: white; border-radius: 12px; border: 1px solid #edf2f7;'>
                        <i class='fas fa-search fa-3x' style='margin-bottom:15px; color:#cbd5e0;'></i>
                        <h2 style='color: #2c3e50; margin-top: 0;'>Panel de Consultas</h2>
                        <p style='margin: 0; color: #7f8c8d;'>Utilice el menú lateral para realizar búsquedas en el archivo.</p>
                    </div>"; 
                } 
            ?>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggle');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        if(menuToggle) menuToggle.addEventListener('click', toggleMenu);
        if(overlay) overlay.addEventListener('click', toggleMenu);
    </script>
</body>
</html>