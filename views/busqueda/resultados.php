<style>
    .search-results-header {
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .search-query-badge {
        background: #e2e8f0;
        color: #4a5568;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .result-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 5px solid var(--primary-color);
    }

    .result-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        border-left-color: var(--accent-color);
    }

    .result-info h4 {
        margin: 0 0 5px 0;
        color: #2d3748;
        font-size: 1.1rem;
    }

    .result-meta {
        color: #718096;
        font-size: 0.85rem;
        display: flex;
        gap: 15px;
    }

    .result-location {
        margin-top: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--accent-color);
        font-weight: 600;
        font-size: 0.85rem;
        background: #f0fdf4;
        padding: 2px 10px;
        border-radius: 5px;
    }

    .btn-view {
        background: var(--primary-color);
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 0.85rem;
        transition: background 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-view:hover {
        background: #34495e;
        color: white;
    }

    .no-results {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 12px;
        border: 2px dashed #e2e8f0;
    }
</style>

<div class="container">
    <div class="search-results-header">
        <div>
            <h2 style="margin:0;">Resultados de Búsqueda</h2>
            <p style="color: #718096; margin-top: 5px;">
                Mostrando coincidencias para: <span class="search-query-badge"><?php echo htmlspecialchars($_GET['q'] ?? ''); ?></span>
            </p>
        </div>
        <div style="color: #a0aec0;">
            <i class="fas fa-search fa-2x"></i>
        </div>
    </div>

    <?php if (empty($resultados)): ?>
        <div class="no-results">
            <div style="font-size: 3rem; color: #cbd5e0; margin-bottom: 15px;">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3 style="color: #4a5568;">No se encontró nada</h3>
            <p style="color: #718096;">Intenta con otros términos o verifica el número de carné de identidad.</p>
            
            <?php 
                // Redirección dinámica según el rol
                $urlVolver = ($_SESSION['usuario_rol'] === 'invitado') 
                    ? "index.php?controller=invitado&action=index" 
                    : "index.php?controller=dashboard";
            ?>
            <a href="<?php echo $urlVolver; ?>" style="color: var(--accent-color); font-weight: bold; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    <?php else: ?>
        <div class="results-list">
            <?php foreach ($resultados as $r): ?>
                <div class="result-card">
                    <div class="result-info">
                        <h4><i class="fas fa-user-circle" style="color: #cbd5e0;"></i> <?php echo htmlspecialchars($r['nombre_completo']); ?></h4>
                        <div class="result-meta">
                            <span><i class="fas fa-id-card"></i> CI: <?php echo htmlspecialchars($r['ci']); ?></span>
                            <span><i class="fas fa-building"></i> <?php echo htmlspecialchars($r['centro_trabajo']); ?></span>
                        </div>
                        <div class="result-location">
                            <i class="fas fa-map-marker-alt"></i> 
                            Estante <?php echo $r['num_estante']; ?> / Cajuela <?php echo $r['cajuela']; ?>
                        </div>
                    </div>
                    
                    <div>
                        <a href="index.php?controller=expediente&action=ver&id=<?php echo $r['id']; ?>" class="btn-view">
                            <i class="fas fa-eye"></i> Ver Expediente
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <p style="margin-top: 20px; color: #a0aec0; font-size: 0.85rem; text-align: center;">
            Se han encontrado <?php echo count($resultados); ?> resultado(s).
        </p>
    <?php endif; ?>
</div>