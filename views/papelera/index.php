<style>
    /* Estilos generales */
    .header-page h2 { color: var(--danger-color); margin-bottom: 5px; font-size: 1.5rem; }
    .header-page p { color: #718096; margin-top: 0; font-size: 0.9rem; }

    .card {
        background: white;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        padding: 20px;
    }

    /* Buscador */
    .search-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .form-control {
        padding: 10px 15px;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        font-size: 0.95rem;
        flex: 1;
        min-width: 200px;
        outline: none;
        transition: border-color 0.3s;
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
        transition: background 0.3s, transform 0.1s;
    }
    
    .btn-primary { background: var(--primary-color); color: white; }
    .btn-outline { background: white; color: #4a5568; border: 1px solid #cbd5e0; }

    /* Estructura de Tabla Responsiva */
    .table-container { overflow-x: hidden; background: transparent !important; border: none !important; box-shadow: none !important; }
    
    table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    th { background-color: #f8fafc; color: #4a5568; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
    
    /* Botones de acción */
    .action-btns-container { display: flex; gap: 8px; justify-content: flex-end; }
    .action-btn {
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-view { color: #3182ce; background: #ebf8ff; }
    .btn-restore { color: var(--accent-color); background: #e6f6ec; }

    /* MODO MÓVIL (Cards) */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr { display: block; }
        
        thead tr { position: absolute; top: -9999px; left: -9999px; }
        
        table { border: none; background: transparent; box-shadow: none; }
        
        tr {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 15px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
        
        td {
            border: none;
            position: relative;
            padding-left: 45% !important;
            text-align: right !important;
            padding: 10px 15px !important;
        }
        
        td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 40%;
            text-align: left;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .action-btns-container {
            justify-content: flex-end;
            width: 100%;
            margin-top: 10px;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }

        .search-form { flex-direction: column; }
        .form-control { min-width: 100%; }
        .btn { width: 100%; justify-content: center; }
    }

    .empty-state { text-align: center; padding: 40px 20px; color: #a0aec0; background: white; border-radius: 12px; border: 1px solid #e2e8f0; }
</style>

<div class="header-page">
    <h2><i class="fas fa-trash-alt"></i> Papelera de Recuperación</h2>
    <p>Busca y restaura expedientes eliminados.</p>
</div>

<div class="card">
    <form method="GET" action="index.php" class="search-form">
        <input type="hidden" name="controller" value="papelera">
        <input type="hidden" name="action" value="index">
        
        <input type="text" name="q" class="form-control" placeholder="Buscar por CI o Nombre..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>
        
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        <?php if(!empty($_GET['q'])): ?>
            <a href="index.php?controller=papelera&action=index" class="btn btn-outline"><i class="fas fa-times"></i> Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-container">
    <?php if (!empty($eliminados)): ?>
        <table>
            <thead>
                <tr>
                    <th>CI</th>
                    <th>Nombre Completo</th>
                    <th>Fecha de Eliminación</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eliminados as $m): ?>
                    <tr>
                        <td data-label="CI"><strong><?= htmlspecialchars($m['ci']) ?></strong></td>
                        <td data-label="Nombre"><?= htmlspecialchars($m['nombre_completo']) ?></td>
                        <td data-label="Eliminado" style="color: var(--danger-color);">
                            <i class="far fa-calendar-times"></i> <?= htmlspecialchars($m['deleted_at']) ?>
                        </td>
                        <td data-label="Acciones">
                            <div class="action-btns-container">
                                <a href="index.php?controller=papelera&action=ver&id=<?= $m['id'] ?>" class="action-btn btn-view" title="Ver Detalles">
                                    <i class="fas fa-eye"></i> <span class="btn-text">Ver</span>
                                </a>
                                <a href="index.php?controller=papelera&action=restaurar&id=<?= $m['id'] ?>" class="action-btn btn-restore" onclick="return confirm('¿Restaurar expediente?');">
                                    <i class="fas fa-trash-restore"></i> <span class="btn-text">Restaurar</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p>No hay expedientes en la papelera.</p>
        </div>
    <?php endif; ?>
</div>