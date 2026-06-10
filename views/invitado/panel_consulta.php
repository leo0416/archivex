<style>
    /* --- Reset Básico --- */
    *, *::before, *::after { box-sizing: border-box; }

    /* --- Variables y Base --- */
    :root { --primary-color: #2d3748; --accent-color: #27ae60; }
    
    .search-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #edf2f7; }
    .search-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end; }
    .form-group { display: flex; flex-direction: column; gap: 8px; }
    .form-group label { font-size: 0.85rem; font-weight: 700; color: #4a5568; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control { padding: 12px; border: 2px solid #e2e8f0; border-radius: 8px; outline: none; transition: all 0.3s; font-size: 0.95rem; width: 100%; }
    .form-control:focus { border-color: var(--accent-color); box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1); }
    
    .search-actions { display: flex; gap: 10px; }
    .btn-submit { background: var(--accent-color); color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; flex: 2; }
    .btn-submit:hover { background: #219150; transform: translateY(-1px); }
    .btn-clear { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; text-decoration: none; text-align: center; display: flex; align-items: center; justify-content: center; flex: 1; }

    /* --- Tabla Responsive (Cards en Móvil) --- */
    .results-card { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #edf2f7; width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 600px; } /* Mantiene estructura en PC */
    th { background: #f8fafc; color: #64748b; font-size: 0.8rem; text-transform: uppercase; padding: 15px; border-bottom: 2px solid #edf2f7; text-align: left; }
    td { padding: 15px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
    .militante-name { color: #2d3748; font-weight: 600; display: block; font-size: 1rem; word-break: break-word; }
    .badge-info { padding: 6px 12px; background: #ebf8ff; color: #3182ce; border-radius: 20px; font-weight: 700; font-size: 0.75rem; border: 1px solid #bee3f8; display: inline-block; }

    /* --- Media Queries Generales --- */
    @media screen and (max-width: 768px) {
        /* Ajuste de formulario para móvil */
        .search-grid { grid-template-columns: 1fr; }
        .search-actions { flex-direction: row; }
        
        /* Ajuste de tabla a tarjetas */
        table { min-width: 100%; }
        table, thead, tbody, th, td, tr { display: block; }
        thead { display: none; }
        tr { border: 1px solid #e2e8f0; border-radius: 12px; margin: 15px; background: #fff; padding: 5px 0; }
        td { border: none; border-bottom: 1px solid #f1f5f9; position: relative; padding: 12px 15px 12px 45% !important; text-align: right; min-height: 45px; display: flex; flex-direction: column; justify-content: center; align-items: flex-end; }
        td:last-child { border-bottom: 0; align-items: center; padding-left: 15px !important; margin-top: 5px; }
        td::before { content: attr(data-label); position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 40%; text-align: left; font-weight: 700; color: #94a3b8; font-size: 0.7rem; text-transform: uppercase; }
        
        /* Botón de ver perfil a 100% en móvil */
        td[data-label="Acción"] button { width: 100%; justify-content: center; padding: 12px; }
    }

    @media screen and (max-width: 480px) {
        /* En pantallas muy pequeñas, botones de búsqueda uno debajo del otro */
        .search-card { padding: 15px; }
        .search-actions { flex-direction: column; }
        .btn-submit, .btn-clear { width: 100%; flex: none; }
    }

    /* --- Modal Corregido y Optimizado para Móvil --- */
    .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(26, 32, 44, 0.8); backdrop-filter: blur(5px); }
    .modal-content { 
        background: white; 
        margin: 5vh auto; /* Margen superior/inferior relativo */
        width: 95%; 
        max-width: 650px; 
        max-height: 90vh; /* Nunca sobrepasa la pantalla */
        border-radius: 16px; 
        box-shadow: 0 25px 50px rgba(0,0,0,0.3); 
        position: relative; 
        animation: slideDown 0.3s ease-out;
        display: flex;
        flex-direction: column;
    }
    .modal-header { background: var(--primary-color); color: white; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 16px 16px 0 0; flex-shrink: 0; }
    .modal-body { padding: 25px; display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; overflow-y: auto; /* Scroll interno solo aquí */ }
    .modal-footer { background: #f1f5f9; padding: 15px 20px; text-align: right; border-radius: 0 0 16px 16px; flex-shrink: 0; border-top: 1px solid #e2e8f0; }
    
    .info-item { border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; }
    .info-item label { display: block; font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; }
    .info-item span { font-weight: 600; color: #1e293b; font-size: 1rem; word-break: break-word; }
    
    @media screen and (max-width: 550px) {
        .modal-body { grid-template-columns: 1fr; gap: 15px; padding: 20px 15px; }
        .info-item { grid-column: span 1 !important; }
        .modal-content { margin: 2vh auto; max-height: 96vh; }
    }

    @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<!-- Formulario con todos los filtros -->
<div class="search-card">
    <form action="index.php" method="GET" class="search-grid">
        <input type="hidden" name="controller" value="invitado">
        <input type="hidden" name="action" value="index">
        
        <div class="form-group">
            <label><i class="fas fa-user-tag"></i> Nombre o CI</label>
            <input type="text" name="q" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label><i class="fas fa-users"></i> Núcleo</label>
            <select name="nucleo" class="form-control">
                <option value="">-- Todos --</option>
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

<!-- Tabla de resultados responsive -->
<div class="results-card">
    <?php if (!empty($resultados)): ?>
        <table>
            <thead>
                <tr>
                    <th>Identificación</th>
                    <th>Núcleo</th>
                    <th>Ubicación</th>
                    <th style="text-align: center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $r): ?>
                    <tr>
                        <td data-label="Identificación">
                            <span class="militante-name"><?php echo htmlspecialchars($r['nombre_completo']); ?></span>
                            <small style="color: #718096;">CI: <?php echo htmlspecialchars($r['ci']); ?></small>
                        </td>
                        <td data-label="Núcleo">
                            <?php echo htmlspecialchars($r['nombre_nucleo'] ?? 'N/A'); ?>
                        </td>
                        <td data-label="Ubicación">
                            <span class="badge-info">
                                E-<?php echo $r['num_estante'] ?? '??'; ?> / C-<?php echo $r['cajuela'] ?? '??'; ?>
                            </span>
                        </td>
                        <td  style="text-align: center;">
                            <button class="btn-submit" style="padding: 8px 15px; font-size: 0.8rem;" 
                                    onclick='verPerfil(<?php echo json_encode($r, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                <i class="fas fa-eye"></i> Perfil
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div style="text-align: center; padding: 40px 20px; color: #94a3b8;">
            <i class="fas fa-search fa-3x" style="margin-bottom: 15px;"></i>
            <p style="margin: 0;">No se encontraron resultados.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de Perfil -->
<div id="modalPerfil" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0; font-size: 1.2rem;"><i class="fas fa-id-card"></i> Expediente Digital</h3>
            <span style="cursor:pointer; font-size: 1.5rem; line-height: 1;" onclick="cerrarModal()">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="info-item" style="grid-column: span 2;">
                <label>Nombre Completo</label>
                <span id="m_nombre" style="color: var(--primary-color); font-size: 1.2rem;"></span>
            </div>
            <div class="info-item">
                <label>Carnet de Identidad</label>
                <span id="m_ci"></span>
            </div>
            <div class="info-item">
                <label>Núcleo Político</label>
                <span id="m_nucleo"></span>
            </div>
            <div class="info-item" style="grid-column: span 2;">
                <label>Condecoraciones</label>
                <span id="m_condecoraciones" style="color: #d69e2e;"></span>
            </div>
            <div class="info-item" style="grid-column: span 2; background: #f8fafc; padding: 12px; border-radius: 8px; border: none;">
                <label>Localización en Archivo</label>
                <span id="m_ubicacion"></span>
            </div>
        </div>

        <div class="modal-footer">
            <button onclick="cerrarModal()" class="btn-clear" style="display: inline-flex; width: auto; padding: 10px 25px; margin-left: auto;">Cerrar</button>
        </div>
    </div>
</div>

<script>
function verPerfil(data) {
    if(!data) return;
    
    document.getElementById('m_nombre').textContent = data.nombre_completo || '';
    document.getElementById('m_ci').textContent = data.ci || '';
    document.getElementById('m_nucleo').textContent = data.nombre_nucleo || 'Sin asignar';
    document.getElementById('m_condecoraciones').textContent = data.condecoraciones || 'Ninguna';
    document.getElementById('m_ubicacion').textContent = 'Estante: ' + (data.num_estante || 'N/A') + ' | Cajuela: ' + (data.cajuela || 'N/A');
    
    let modal = document.getElementById('modalPerfil');
    modal.style.display = 'block';
    // Fuerza un redibujado para evitar glithes en móviles
    modal.offsetHeight; 
    document.body.style.overflow = 'hidden'; 
}

function cerrarModal() {
    document.getElementById('modalPerfil').style.display = 'none';
    document.body.style.overflow = ''; // Permite que el CSS decida (mejor que 'auto' forzado)
}

// Cerrar con tecla Escape (Mejora de accesibilidad)
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") cerrarModal();
});

// Cerrar al hacer clic fuera del contenido
window.onclick = function(event) {
    let modal = document.getElementById('modalPerfil');
    if (event.target == modal) cerrarModal();
}
</script>