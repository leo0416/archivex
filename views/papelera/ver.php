<style>
    .header-page h2 { color: var(--primary-color); margin-bottom: 5px; font-size: 1.5rem; }
    .header-page p { color: #718096; margin-top: 0; font-size: 0.9rem; }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        padding: 30px;
        max-width: 800px;
    }

    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .data-item {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #edf2f7;
    }

    .data-item label {
        display: block;
        font-size: 0.8rem;
        color: #718096;
        text-transform: uppercase;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .data-item span {
        color: #2d3748;
        font-size: 1.05rem;
        font-weight: 500;
    }

    .deleted-banner {
        background: #fff5f5;
        border-left: 4px solid var(--danger-color);
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 4px;
        color: #c53030;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .actions {
        display: flex;
        gap: 15px;
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: 0.3s;
    }
    
    .btn-success { background: var(--accent-color); color: white; }
    .btn-success:hover { background: #219a52; }
    
    .btn-secondary { background: #e2e8f0; color: #4a5568; }
    .btn-secondary:hover { background: #cbd5e0; }
</style>

<div class="header-page">
    <a href="index.php?controller=papelera&action=index" style="color: #718096; text-decoration: none; font-size: 0.9rem;">
        <i class="fas fa-arrow-left"></i> Volver a la Papelera
    </a>
    <h2 style="margin-top: 15px;"><i class="fas fa-user-times"></i> Revisión de Expediente Eliminado</h2>
    <p>Verifica los datos antes de proceder con la restauración.</p>
</div>

<div class="card">
    
    <div class="deleted-banner">
        <i class="fas fa-exclamation-triangle" style="font-size: 1.2rem;"></i>
        <div>
            <strong>Atención:</strong> Este expediente fue eliminado del sistema el <strong><?= htmlspecialchars($militante['deleted_at']) ?></strong>.
        </div>
    </div>

    <div class="data-grid">
        <div class="data-item">
            <label>Cédula de Identidad (CI)</label>
            <span><?= htmlspecialchars($militante['ci']) ?></span>
        </div>
        <div class="data-item">
            <label>Nombre Completo</label>
            <span><?= htmlspecialchars($militante['nombre_completo']) ?></span>
        </div>
        <div class="data-item">
            <label>Sexo</label>
            <span><?= htmlspecialchars($militante['sexo']) ?></span>
        </div>
        <div class="data-item">
            <label>Fecha de Nacimiento</label>
            <span><?= htmlspecialchars($militante['fecha_nacimiento']) ?></span>
        </div>
        <div class="data-item" style="grid-column: 1 / -1;">
            <label>Centro de Trabajo</label>
            <span><?= htmlspecialchars($militante['centro_trabajo']) ?></span>
        </div>
        <div class="data-item" style="grid-column: 1 / -1;">
            <label>Dirección</label>
            <span><?= htmlspecialchars($militante['direccion']) ?></span>
        </div>
    </div>

    <div class="actions">
        <a href="index.php?controller=papelera&action=restaurar&id=<?= $militante['id'] ?>" class="btn btn-success" onclick="return confirm('¿Confirmas la restauración del expediente? El sistema le asignará el primer espacio libre disponible en los estantes.');">
            <i class="fas fa-trash-restore"></i> Restaurar Expediente
        </a>
        <a href="index.php?controller=papelera&action=index" class="btn btn-secondary">
            Cancelar
        </a>
    </div>
</div>