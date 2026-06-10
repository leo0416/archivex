<style>
    .nucleo-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }
    .select-nucleo {
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 15px;
    }
    .select-nucleo .form-group {
        flex: 1;
        min-width: 200px;
    }
    .select-nucleo label {
        display: block;
        font-weight: 600;
        margin-bottom: 5px;
        color: #4a5568;
    }
    .select-nucleo select {
        width: 100%;
        padding: 10px;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        background: white;
    }
    .btn-primary {
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary:hover { background: #219150; }
    .btn-pdf {
        background: #e74c3c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .tabla-militantes {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .tabla-militantes th {
        background: #f8fafc;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        color: #4a5568;
    }
    .tabla-militantes td {
        padding: 12px;
        border-bottom: 1px solid #edf2f7;
    }
    .acciones-btns {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-view {
        color: #2c3e50;
        background: #e2e8f0;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-edit {
        color: #3182ce;
        background: #ebf8ff;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .btn-delete {
        color: #e53e3e;
        background: #fff5f5;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .empty-msg {
        text-align: center;
        padding: 40px;
        color: #a0aec0;
    }
    .total-count {
        margin-top: 15px;
        font-weight: bold;
        text-align: right;
        padding: 10px;
        background: #f8fafc;
        border-radius: 6px;
    }
    @media (max-width: 768px) {
        .select-nucleo {
            flex-direction: column;
        }
        .tabla-militantes, .tabla-militantes thead, .tabla-militantes tbody, .tabla-militantes tr, .tabla-militantes td {
            display: block;
        }
        .tabla-militantes thead { display: none; }
        .tabla-militantes tr {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 15px;
            padding: 10px;
        }
        .tabla-militantes td {
            border: none;
            padding: 8px 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .tabla-militantes td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #718096;
            font-size: 0.75rem;
        }
        .acciones-btns {
            justify-content: flex-end;
        }
    }
</style>

<div class="nucleo-container">
    <h2><i class="fas fa-users"></i> Gestión por Núcleos</h2>
    <p>Seleccione un núcleo para ver sus militantes activos y realizar acciones.</p>

    <form method="GET" action="index.php" class="select-nucleo">
        <input type="hidden" name="controller" value="nucleo">
        <input type="hidden" name="action" value="index">
        <div class="form-group">
            <label><i class="fas fa-building"></i> Núcleo de Base</label>
            <select name="nucleo_id" required onchange="this.form.submit()">
                <option value="">-- Seleccione un núcleo --</option>
                <?php foreach ($nucleos as $n): ?>
                    <option value="<?= $n['id'] ?>" <?= (isset($_GET['nucleo_id']) && $_GET['nucleo_id'] == $n['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($n['numero_nucleo'] . ' - ' . $n['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($nucleo_seleccionado): ?>
            <div>
                <a href="index.php?controller=nucleo&action=pdfListado&nucleo_id=<?= $nucleo_seleccionado['id'] ?>" target="_blank" class="btn-pdf">
                    <i class="fas fa-file-pdf"></i> Generar PDF
                </a>
            </div>
        <?php endif; ?>
    </form>

    <?php if ($nucleo_seleccionado): ?>
        <h3>Militantes del núcleo: <strong><?= htmlspecialchars($nucleo_seleccionado['nombre']) ?></strong></h3>
        
        <?php if (empty($militantes)): ?>
            <div class="empty-msg">
                <i class="fas fa-info-circle"></i> No hay militantes activos en este núcleo.
            </div>
        <?php else: ?>
            <table class="tabla-militantes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $contador = 1; foreach ($militantes as $m): ?>
                        <tr>
                            <td data-label="#"><?= $contador++ ?></td>
                            <td data-label="Nombre"><?= htmlspecialchars($m['nombre_completo']) ?></td>
                            <td data-label="CI"><?= htmlspecialchars($m['ci']) ?></td>
                            <td data-label="Ubicación">
                                Estante <?= $m['num_estante'] ?? 'N/A' ?> / 
                                Cajuela <?= $m['cajuela'] ?? 'N/A' ?> / 
                                Pos. <?= $m['posicion_global'] ?? 'N/A' ?>
                            </td>
                            <td data-label="Acciones" class="acciones-btns">
                                <a href="index.php?controller=expediente&action=ver&id=<?= $m['id'] ?>" class="btn-view">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="index.php?controller=militante&action=editar&id=<?= $m['id'] ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="index.php?controller=militante&action=eliminar&id=<?= $m['id'] ?>" class="btn-delete" onclick="return confirm('¿Eliminar este expediente? Se moverá a la papelera.')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total-count">
                Total de militantes en este núcleo: <?= count($militantes) ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>