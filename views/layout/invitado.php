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
            border-left-color: var(--danger-color);
        }

        .menu-item i { margin-right: 15px; width: 20px; text-align: center; }

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
            <a href="index.php?controller=invitado" class="menu-item">
                <i class="fas fa-search"></i> Consultas
            </a>

            <div style="flex: 1;"></div> 

            <a href="index.php?controller=auth&action=logout" class="menu-item" style="color: #e74c3c; margin-bottom: 20px;">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <div>
                <h4 style="margin:0; color: var(--primary-color);">Sistema de Archivo</h4>
            </div>
            
            <div class="user-meta" style="color: #7f8c8d; display: flex; align-items: center; gap: 15px;">
                <span class="d-none-mobile"><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y'); ?></span>
                
                <div class="user-info" style="border-left: 1px solid #eee; padding-left: 15px; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-user-circle" style="color: var(--primary-color); font-size: 1.2rem;"></i> 
                    <strong><span><?php echo $_SESSION['usuario_nombre'] ?? 'Invitado'; ?></span></strong>
                    <span class="badge-role"><?php echo $_SESSION['usuario_rol'] ?? 'Invitado'; ?></span>
                </div>
            </div>
        </div>

        <div class="container">
            <?php 
                if(isset($view) && file_exists($view)) { 
                    include($view); 
                } else { 
                    echo "
                    <div style='text-align:center; padding: 50px; color: #7f8c8d;'>
                        <i class='fas fa-shield-alt fa-3x' style='margin-bottom:15px; color:#cbd5e0;'></i>
                        <h2>Acceso Limitado</h2>
                        <p>No se ha podido cargar la vista de consulta. Por favor, contacte al administrador.</p>
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
        }

        if(menuToggle) menuToggle.addEventListener('click', toggleMenu);
        if(overlay) overlay.addEventListener('click', toggleMenu);
    </script>
</body>
</html>