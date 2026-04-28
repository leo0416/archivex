<style>
    /* Estilos del Contenedor de Búsqueda */
    .search-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #edf2f7; }
    .search-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; align-items: end; }
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group label { font-size: 0.85rem; font-weight: 700; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control { padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; outline: none; transition: all 0.3s; font-size: 0.95rem; }
    .form-control:focus { border-color: var(--accent-color); box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1); }
    
    /* Botones y Acciones */
    .search-actions { display: flex; gap: 10px; }
    .btn-submit { background: var(--accent-color); color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; flex: 2; }
    .btn-submit:hover { background: #219150; transform: translateY(-1px); }
    
    /* Botón Limpiar */
    .btn-clear { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px; flex: 1; }
    .btn-clear:hover { background: #e2e8f0; color: #1e293b; }

    /* Tablas y Resultados */
    .results-card { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f8fafc; color: #64748b; font-size: 0.8rem; text-transform: uppercase; padding: 15px; border-bottom: 2px solid #edf2f7; text-align: left; }
    td { padding: 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
    .militante-name { color: #2d3748; font-weight: 600; display: block; font-size: 1rem; }
    .badge-info { padding: 6px 12px; background: #ebf8ff; color: #3182ce; border-radius: 20px; font-weight: 700; font-size: 0.75rem; border: 1px solid #bee3f8; }
    
    /* Modal de Perfil */
    .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(26, 32, 44, 0.8); backdrop-filter: blur(5px); }
    .modal-content { background: white; margin: 50px auto; width: 95%; max-width: 650px; border-radius: 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; animation: slideDown 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
    .modal-header { background: var(--primary-color); color: white; padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; }
    .modal-body { padding: 30px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 25px; }
    .info-item { border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
    .info-item label { display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; }
    .info-item span { font-weight: 600; color: #1e293b; font-size: 1.05rem; }
    .no-results-box { text-align: center; padding: 50px; color: #94a3b8; }

    @keyframes slideDown { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<div class="search-card">
    <form action="index.php" method="GET" class="search-grid">
        <input type="hidden" name="controller" value="invitado">
        <input type="hidden" name="action" value="index">
        
        <div class="form-group">
            <label><i class="fas fa-user-tag"></i> Nombre o CI</label>
            <input type="text" name="q" class="form-control" placeholder="Buscar militante..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label><i class="fas fa-users"></i> Núcleo</label>
            <select name="nucleo" class="form-control">
                <option value="">-- Todos los Núcleos --</option>
                <?php foreach($nucleos as $n): ?>
                    <option value="<?php echo $n['id']; ?>" <?php echo (isset($_GET['nucleo']) && $_GET['nucleo'] == $n['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($n['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label><i class="fas fa-medal"></i> Condecoración</label>
            <select name="condecoracion" class="form-control">
                <option value="">-- Todas --</option>
                <?php foreach($condecoraciones as $c): ?>
                    <option value="<?php echo htmlspecialchars($c['nombre']); ?>" <?php echo (isset($_GET['condecoracion']) && $_GET['condecoracion'] == $c['nombre']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($c['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="search-actions">
            <button type="submit" class="btn-submit">
                <i class="fas fa-search"></i> BUSCAR
            </button>
            <a href="index.php?controller=invitado" class="btn-clear">
                <i class="fas fa-eraser"></i> LIMPIAR
            </a>
        </div>
    </form>
</div>

<div class="results-card">
    <?php if (!empty($resultados)): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Identificación</th>
                        <th>Núcleo</th>
                        <th>Ubicación Archivo</th>
                        <th style="text-align: center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $r): ?>
                        <tr>
                            <td>
                                <span class="militante-name"><?php echo htmlspecialchars($r['nombre_completo']); ?></span>
                                <small style="color: #718096;">CI: <?php echo htmlspecialchars($r['ci']); ?></small>
                            </td>
                            <td style="color: #4a5568; font-weight: 500;">
                                <?php echo htmlspecialchars($r['nombre_nucleo'] ?? 'N/A'); ?>
                            </td>
                            <td>
                                <span class="badge-info">
                                    <i class="fas fa-box-open"></i> E-<?php echo $r['num_estante'] ?? '??'; ?> / C-<?php echo $r['cajuela'] ?? '??'; ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <button class="btn-submit" style="padding: 6px 15px; font-size: 0.8rem; margin: 0 auto;" 
                                        onclick='verPerfil(<?php echo json_encode($r); ?>)'>
                                    <i class="fas fa-eye"></i> Perfil
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="no-results-box">
            <i class="fas fa-search-minus fa-4x" style="margin-bottom: 15px; opacity: 0.3;"></i>
            <h3>No se encontraron registros</h3>
            <p>Ajuste los filtros o ingrese un nombre para comenzar.</p>
        </div>
    <?php endif; ?>
</div>

<div id="modalPerfil" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0;"><i class="fas fa-id-card"></i> Expediente Digital</h3>
            <span style="cursor:pointer; font-size: 1.5rem;" onclick="cerrarModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="info-item" style="grid-column: span 2;">
                <label>Nombre Completo</label>
                <span id="m_nombre" style="font-size: 1.4rem; color: var(--primary-color);"></span>
            </div>
            <div class="info-item">
                <label>Carnet de Identidad</label>
                <span id="m_ci"></span>
            </div>
            <div class="info-item">
                <label>Núcleo Político</label>
                <span id="m_nucleo"></span>
            </div>
            <div class="info-item">
                <label>Centro de Trabajo</label>
                <span id="m_trabajo"></span>
            </div>
            <div class="info-item">
                <label>Fecha Ingreso</label>
                <span id="m_ingreso"></span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <label>Condecoraciones Recibidas</label>
                <span id="m_condecoraciones" style="color: #d69e2e;"></span>
            </div>
            <div class="info-item" style="grid-column: span 2; background: #f8fafc; padding: 15px; border-radius: 8px;">
                <label><i class="fas fa-archive"></i> Localización Física</label>
                <span id="m_ubicacion"></span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <label>Observaciones del Archivo</label>
                <span id="m_obs" style="font-weight: 400; font-style: italic;"></span>
            </div>
        </div>
        <div style="background: #f1f5f9; padding: 15px; text-align: right;">
            <button onclick="cerrarModal()" class="btn-clear" style="display: inline-flex; border: 1px solid #cbd5e0;">Cerrar</button>
        </div>
    </div>
</div>

<script>
function verPerfil(data) {
    document.getElementById('m_nombre').innerText = data.nombre_completo;
    document.getElementById('m_ci').innerText = data.ci;
    document.getElementById('m_nucleo').innerText = data.nombre_nucleo || 'Sin asignar';
    document.getElementById('m_trabajo').innerText = data.centro_trabajo || 'No especificado';
    document.getElementById('m_ingreso').innerText = data.fecha_ingreso || 'N/A';
    document.getElementById('m_condecoraciones').innerText = data.condecoraciones || 'Ninguna';
    document.getElementById('m_ubicacion').innerText = 'ESTANTE: ' + (data.num_estante || 'N/A') + ' | CAJUELA: ' + (data.cajuela || 'N/A');
    document.getElementById('m_obs').innerText = data.observaciones || 'Sin observaciones.';
    document.getElementById('modalPerfil').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalPerfil').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target == document.getElementById('modalPerfil')) cerrarModal();
}
</script>