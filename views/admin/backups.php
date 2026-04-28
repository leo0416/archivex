<style>
    .backup-container { animation: fadeIn 0.5s ease; }
    
    .action-cards { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
        gap: 20px; 
        margin-bottom: 30px; 
    }

    .b-card { 
        background: white; 
        padding: 30px; 
        border-radius: 12px; 
        border: 1px solid #e2e8f0; 
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .b-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .b-card i { font-size: 3rem; margin-bottom: 15px; }
    
    /* Estilo de botones para coincidir con el proyecto */
    .btn-action {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        cursor: pointer;
        border: none;
        font-size: 0.9rem;
    }

    .btn-backup { background: var(--accent-color); color: white; }
    .btn-backup:hover { background: #219150; }

    .btn-import { background: #3498db; color: white; }
    .btn-import:hover { background: #2980b9; }

    /* Tabla Estilo Archivex */
    .table-wrapper {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .table-backups { width: 100%; border-collapse: collapse; }
    
    .table-backups th { 
        background: #f8fafc; 
        padding: 15px; 
        text-align: left; 
        color: #64748b; 
        font-size: 0.8rem; 
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-backups td { 
        padding: 15px; 
        border-bottom: 1px solid #f1f5f9; 
        font-size: 0.9rem; 
        color: var(--primary-color);
    }

    .table-backups tr:last-child td { border-bottom: none; }

    /* Mensajes de estado */
    .status-msg { 
        padding: 15px 20px; 
        border-radius: 8px; 
        margin-bottom: 25px; 
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        animation: slideDown 0.4s ease;
    }

    .msg-success { 
        background: #f0fdf4; 
        color: #166534; 
        border: 1px solid #bbf7d0; 
    }

    code {
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
        color: var(--danger-color);
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.85em;
    }

    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideDown { from { transform: translateY(-10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    @media (max-width: 600px) {
        .action-cards { grid-template-columns: 1fr; }
        .table-backups th:nth-child(3), .table-backups td:nth-child(3) { display: none; }
    }
</style>

<div class="backup-container">
    <div style="margin-bottom: 25px;">
        <h1 style="margin:0; color: var(--primary-color);"><i class="fas fa-database"></i> Respaldo y Restauración</h1>
        <p style="color: #64748b; margin: 5px 0 0 0;">Gestión de copias de seguridad del sistema Archivex.</p>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="status-msg msg-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $_GET['msg'] == 'generado' ? 'Punto de restauración creado con éxito.' : 'Base de datos restaurada correctamente.'; ?>
        </div>
    <?php endif; ?>

    <div class="action-cards">
        <div class="b-card">
            <i class="fas fa-file-export" style="color: var(--accent-color);"></i>
            <h3 style="margin: 10px 0;">Exportar Datos</h3>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 20px;">
                Crea una copia exacta de toda la información actual (militantes, usuarios, estantes).
            </p>
            <a href="index.php?controller=backup&action=generar" class="btn-action btn-backup">
                <i class="fas fa-plus"></i> Generar Backup SQL
            </a>
        </div>

        <div class="b-card">
            <i class="fas fa-file-import" style="color: #3498db;"></i>
            <h3 style="margin: 10px 0;">Restaurar Sistema</h3>
            <form action="index.php?controller=backup&action=importar" method="POST" enctype="multipart/form-data">
                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 15px;">
                    Sube un archivo .sql para sobrescribir la base de datos actual.
                </p>
                <input type="file" name="sql_file" accept=".sql" required 
                       style="font-size: 0.75rem; margin-bottom: 15px; width: 100%; max-width: 250px;">
                <br>
                <button type="submit" class="btn-action btn-import">
                    <i class="fas fa-upload"></i> Subir e Importar
                </button>
            </form>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
        <h3 style="margin:0; color: var(--primary-color);"><i class="fas fa-history"></i> Historial Reciente</h3>
    </div>

    <div class="table-wrapper">
        <table class="table-backups">
            <thead>
                <tr>
                    <th>Fecha de Creación</th>
                    <th>Nombre del Archivo</th>
                    <th>Realizado por</th>
                    <th style="text-align: right;">Descarga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($backups as $b): ?>
                <tr>
                    <td>
                        <div style="font-weight: 600;"><?php echo date('d/m/Y', strtotime($b['fecha'])); ?></div>
                        <div style="font-size: 0.75rem; color: #94a3b8;"><?php echo date('H:i:s', strtotime($b['fecha'])); ?></div>
                    </td>
                    <td><code><?php echo htmlspecialchars($b['ruta_archivo']); ?></code></td>
                    <td>
                        <span style="font-size: 0.85rem;">
                            <i class="fas fa-user-edit" style="color: #cbd5e0; font-size: 0.8rem;"></i> 
                            <?php echo htmlspecialchars($b['nombre_usuario'] ?? $b['nick']); ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="backups/<?php echo $b['ruta_archivo']; ?>" download 
                           class="btn-action" style="background: #f1f5f9; color: var(--primary-color); padding: 6px 12px; font-size: 0.8rem;">
                            <i class="fas fa-download"></i> SQL
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if(empty($backups)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 40px; color:#94a3b8;">
                            <i class="fas fa-info-circle"></i> No se han generado respaldos todavía.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>