<style>
    /* Estilos de tabla responsiva */
    .tabla-usuarios { width: 100%; border-collapse: collapse; }
    .tabla-usuarios thead tr { background: #edf2f7; }
    .tabla-usuarios th { padding: 15px; border-bottom: 2px solid #cbd5e0; text-align: left; color: #4a5568; font-weight: 700; }
    .tabla-usuarios td { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; color: #2d3748; }
    .actions-column-header { text-align: right !important; }
    .actions-cell { text-align: right; }

    @media (max-width: 768px) {
        .tabla-usuarios thead { display: none; }
        .tabla-usuarios, .tabla-usuarios tbody, .tabla-usuarios tr, .tabla-usuarios td { display: block; width: 100%; }
        .tabla-usuarios tr { margin-bottom: 15px; border: 1px solid #e2e8f0; border-radius: 10px; background: #fff; padding: 10px; }
        .tabla-usuarios td { text-align: right; padding: 10px 5px; position: relative; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f7fafc !important; }
        .tabla-usuarios td::before { content: attr(data-label); font-weight: bold; color: #718096; font-size: 0.75rem; text-transform: uppercase; }
        .actions-cell { justify-content: flex-end !important; }
    }
    .fade-in { animation: fadeIn 0.5s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<div class="usuarios-container fade-in">
    <div class="card-ficha">
        <div class="card-title" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <span style="font-size: 1.25rem; font-weight: bold; color: #2d3748;">
                <i class="fas fa-history" style="color: #4a90e2;"></i> Historial de Auditoría
            </span>
            <a href="index.php?controller=admin&action=exportarLogsExcel" class="btn-confirm" style="background: #27ae60; color: white; text-decoration: none; font-size: 0.8rem; padding: 8px 15px; border-radius: 6px; font-weight: bold;">
                <i class="fas fa-file-excel"></i> Exportar CSV
            </a>
        </div>

        <form action="index.php" method="GET" 
              style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
            
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="verLogs">

            <div class="form-group">
                <label style="display:block; margin-bottom:5px; font-size:0.85rem; font-weight:600; color: #4a5568;">Usuario</label>
                <select name="usuario_id" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; background:white; outline: none;">
                    <option value="">-- Todos los usuarios --</option>
                    <?php foreach($usuarios as $u): ?>
                        <option value="<?php echo $u['id']; ?>" <?php echo (isset($_GET['usuario_id']) && $_GET['usuario_id'] == $u['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($u['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label style="display:block; margin-bottom:5px; font-size:0.85rem; font-weight:600; color: #4a5568;">Desde</label>
                <input type="date" name="fecha_inicio" value="<?php echo $_GET['fecha_inicio'] ?? ''; ?>" style="width:100%; padding:9px; border:1px solid #cbd5e0; border-radius:6px; outline: none;">
            </div>

            <div class="form-group">
                <label style="display:block; margin-bottom:5px; font-size:0.85rem; font-weight:600; color: #4a5568;">Hasta</label>
                <input type="date" name="fecha_fin" value="<?php echo $_GET['fecha_fin'] ?? ''; ?>" style="width:100%; padding:9px; border:1px solid #cbd5e0; border-radius:6px; outline: none;">
            </div>

            <div style="display: flex; align-items: flex-end; gap: 5px;">
                <button type="submit" style="flex:1; padding: 11px; background: #4a90e2; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold; transition: background 0.3s;">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="index.php?controller=admin&action=verLogs" style="padding: 11px; background: #cbd5e0; color:#4a5568; border-radius:6px; text-decoration:none; text-align:center; transition: background 0.3s;" title="Limpiar filtros">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
        </form>

        <div style="overflow-x: auto;">
            <table class="tabla-usuarios">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Usuario</th>
                        <th>Acción Realizada</th>
                        <th class="actions-column-header">IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($logs)): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:30px; color:#94a3b8; font-style: italic;">
                                <i class="fas fa-info-circle"></i> No se encontraron registros de actividad.
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                    <?php foreach($logs as $l): ?>
                    <tr>
                        <td data-label="Fecha" style="color: #64748b; font-size: 0.9rem; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                            <?php echo date('d/m/Y H:i:s', strtotime($l['fecha_hora'])); ?>
                        </td>
                        <td data-label="Usuario" style="font-weight: 600; color: #2d3748;">
                            <?php 
                                // nombre_usuario_real viene del JOIN en el controlador
                                // usuario_nombre es el respaldo que ya tienes en la tabla logs
                                echo htmlspecialchars($l['nombre_usuario_real'] ?? $l['usuario_nombre'] ?? 'Sistema/Desconocido'); 
                            ?>
                        </td>
                        <td data-label="Acción">
                            <span style="color: #4a5568; font-size: 0.9rem; line-height: 1.4;">
                                <?php echo htmlspecialchars($l['accion']); ?>
                            </span>
                        </td>
                        <td data-label="IP" class="actions-cell" style="font-family: 'Courier New', Courier, monospace; font-size: 0.85rem; color: #718096; font-weight: bold;">
                            <?php echo htmlspecialchars($l['ip_address'] ?? '0.0.0.0'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>