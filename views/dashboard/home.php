<style>
    .welcome-container { padding: 10px; }
    .welcome-header { margin-bottom: 2rem; }
    .welcome-header h1 { color: #2c3e50; font-size: 1.8rem; margin-bottom: 0.5rem; }
    .welcome-header p { color: #7f8c8d; font-size: 1.1rem; }

    /* Grid de Estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        border: 1px solid #e2e8f0;
    }

    .stat-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }

    .stat-info h3 {
        margin: 0; color: #7f8c8d; font-size: 0.85rem;
        text-transform: uppercase; letter-spacing: 1px;
    }

    .stat-info p { margin: 8px 0 0 0; font-size: 2.2rem; font-weight: bold; color: #2c3e50; }
    .stat-icon { font-size: 2.5rem; opacity: 0.25; transition: opacity 0.3s; }
    .stat-card:hover .stat-icon { opacity: 0.5; }

    /* Colores Temáticos */
    .card-militantes { border-left: 5px solid var(--accent-color); }
    .card-estantes { border-left: 5px solid #3498db; }
    .card-libres { border-left: 5px solid #f39c12; }
    .card-papelera { border-left: 5px solid var(--danger-color); }

    @media (max-width: 768px) {
        .welcome-header h1 { font-size: 1.5rem; }
        .stat-info p { font-size: 1.8rem; }
    }
.dashboard-actions {
        margin-top: 30px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .btn-pdf {
        background-color: #e74c3c;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-pdf:hover {
        background-color: #c0392b;
    }

    .action-text h2 { font-size: 1.1rem; margin: 0; color: #2c3e50; }
    .action-text p { font-size: 0.9rem; margin: 5px 0 0 0; color: #7f8c8d; }
</style>

<div class="welcome-container">
    <div class="welcome-header">
        <h1><i class="fas fa-tachometer-alt"></i> Panel de Control</h1>
        <p>Bienvenido al sistema Archivex, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></strong>.</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card card-militantes">
            <div class="stat-info">
                <h3>Militantes Activos</h3>
                <p><?php echo number_format($stats['militantes']); ?></p>
            </div>
            <div class="stat-icon" style="color: var(--accent-color);"><i class="fas fa-users"></i></div>
        </div>

        <a href="index.php?controller=estante" class="stat-card card-estantes">
            <div class="stat-info">
                <h3>Estantes del Archivo</h3>
                <p><?php echo number_format($stats['estantes']); ?></p>
            </div>
            <div class="stat-icon" style="color: #3498db;"><i class="fas fa-boxes"></i></div>
        </a>

        <div class="stat-card card-libres">
            <div class="stat-info">
                <h3>Capacidad Disponible</h3>
                <p><?php echo number_format($stats['libres']); ?></p>
            </div>
            <div class="stat-icon" style="color: #f39c12;"><i class="fas fa-th"></i></div>
        </div>

        <a href="index.php?controller=papelera" class="stat-card card-papelera">
            <div class="stat-info">
                <h3>En Papelera</h3>
                <p><?php echo number_format($stats['papelera']); ?></p>
            </div>
            <div class="stat-icon" style="color: var(--danger-color);"><i class="fas fa-trash-alt"></i></div>
        </a>
    </div>

    <div class="dashboard-actions">
        <div class="action-text">
            <h2>Reportes Oficiales</h2>
            <p>Genera un inventario detallado del estado actual de los estantes y ocupación.</p>
        </div>
        <a href="index.php?controller=reporte&action=inventarioEstantes" target="_blank" class="btn-pdf">
            <i class="fas fa-file-pdf"></i> Generar Inventario PDF
        </a>
    </div>
</div>