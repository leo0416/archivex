<style>
    .shelf-card {
        background: white;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    /* Encabezado del estante */
    .shelf-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        cursor: pointer;
        transition: background 0.3s;
        user-select: none;
    }

    .shelf-header:hover { background: #f8fafc; }

    .shelf-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .shelf-title i.toggle-icon {
        transition: transform 0.3s ease;
        color: #cbd5e0;
    }

    .shelf-card.open .toggle-icon {
        transform: rotate(180deg);
        color: var(--accent-color);
    }

    /* Contenido oculto */
    .shelf-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease-out, padding 0.3s ease;
        padding: 0 20px;
        background: #fcfdfe;
    }

    .shelf-card.open .shelf-content {
        max-height: 2000px; 
        padding: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .progress-container {
        height: 10px;
        background: #edf2f7;
        border-radius: 5px;
        overflow: hidden;
        margin: 15px 0;
    }

    .progress-bar {
        height: 100%;
        background: var(--accent-color);
    }

    /* Cuadrícula de cajuelas/posiciones */
    .drawer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(35px, 1fr));
        gap: 6px;
        margin-top: 15px;
    }

    .drawer-item {
        aspect-ratio: 1;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: bold;
        transition: transform 0.1s, background 0.2s;
        cursor: default;
    }

    .drawer-item:active { transform: scale(0.9); }

    /* Estilo para posiciones OCUPADAS */
    .drawer-item.ocupada { 
        background: #2c3e50; 
        padding: 0;
    }

    .drawer-item.ocupada a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .drawer-item.ocupada:hover {
        background: #34495e;
        transform: scale(1.1);
        z-index: 10;
    }

    /* Estilo para posiciones LIBRES */
    .drawer-item.libre { 
        background: #e2e8f0; 
        color: #a0aec0; 
        border: 1px dashed #cbd5e0; 
    }

    .legend {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; }
    .box { width: 14px; height: 14px; border-radius: 3px; }

    .header-page h2 { color: var(--primary-color); margin-bottom: 5px; font-size: 1.5rem; }
    .header-page p { color: #718096; margin-top: 0; font-size: 0.9rem; }

    @media (max-width: 600px) {
        .shelf-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        .drawer-grid { grid-template-columns: repeat(auto-fill, minmax(30px, 1fr)); }
    }
</style>

<div class="header-page">
    <h2><i class="fas fa-boxes"></i> Monitoreo de Estantes</h2>
    <p>Haz clic en una posición ocupada (color oscuro) para ver el expediente del militante.</p>
</div>

<div class="legend">
    <div class="legend-item"><div class="box" style="background: #2c3e50;"></div> Ocupado (Clic para ver)</div>
    <div class="legend-item"><div class="box" style="background: #e2e8f0; border: 1px dashed #cbd5e0;"></div> Libre</div>
    <div class="legend-item"><i class="fas fa-info-circle" style="color: #3182ce;"></i> Capacidad: 396 por estante</div>
</div>

<?php if(empty($estantes)): ?>
    <div style="text-align: center; padding: 50px; background: white; border-radius: 12px; border: 1px dashed #cbd5e0;">
        <i class="fas fa-folder-open" style="font-size: 3rem; color: #cbd5e0;"></i>
        <p style="margin-top: 15px; color: #718096;">No hay estantes registrados.</p>
    </div>
<?php else: ?>
    <?php foreach($estantes as $e): 
        $porcentaje = ($e['ocupados'] / $e['capacidad']) * 100;
    ?>
    <div class="shelf-card" id="shelf-<?php echo $e['numero_consecutivo']; ?>">
        <div class="shelf-header" onclick="toggleShelf(<?php echo $e['numero_consecutivo']; ?>)">
            <div class="shelf-title">
                <i class="fas fa-chevron-down toggle-icon"></i>
                <h3 style="margin: 0; font-size: 1.1rem;">Estante #<?php echo $e['numero_consecutivo']; ?></h3>
            </div>
            <span class="stats" style="color: #718096; font-size: 0.9rem;">
                <strong><?php echo $e['ocupados']; ?></strong> / <?php echo $e['capacidad']; ?> ocupados
            </span>
        </div>
        
        <div class="shelf-content">
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo $porcentaje; ?>%;"></div>
            </div>
            
            <p style="font-size: 0.8rem; color: #a0aec0; margin-bottom: 15px;">
                Mapa de posiciones (Cajuelas 1-36):
            </p>
            
            <div class="drawer-grid">
                <?php foreach($e['detalles_ubicaciones'] as $u): ?>
                    <div class="drawer-item <?php echo $u['estado']; ?>" 
                         title="Posición: <?php echo $u['posicion_global']; ?> | Cajuela: <?php echo $u['cajuela']; ?>">
                        
                        <?php if($u['estado'] == 'ocupada' && isset($u['militante_id'])): ?>
                            <a href="index.php?controller=militante&action=ver&id=<?php echo $u['militante_id']; ?>">
                                <?php echo $u['posicion_global']; ?>
                            </a>
                        <?php else: ?>
                            <?php echo $u['posicion_global']; ?>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    function toggleShelf(id) {
        const card = document.getElementById('shelf-' + id);
        card.classList.toggle('open');
    }
</script>