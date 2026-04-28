<style>
    .ficha-container { max-width: 1000px; margin: 0 auto; padding: 20px; }
    .btn-volver { margin-bottom: 20px; display: inline-block; color: #666; text-decoration: none; transition: color 0.3s; font-weight: 500; }
    .btn-volver:hover { color: var(--accent-color); }
    
    .grid-expediente {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 25px;
    }

    .card-ficha {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid #eef2f7;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: bold;
        color: var(--primary-color);
        border-bottom: 2px solid #f4f7f6;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-row { display: flex; margin-bottom: 15px; border-bottom: 1px solid #f9f9f9; padding-bottom: 8px; }
    .info-label { font-weight: 600; width: 180px; color: #7f8c8d; font-size: 0.9rem; }
    .info-value { color: #2c3e50; font-weight: 500; }

    .badge-ubicacion {
        background: #ebf5ff;
        color: #007bff;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #d1e9ff;
    }

    .reconocimiento-item {
        background: #fff9db;
        border-left: 4px solid #f1c40f;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 0 5px 5px 0;
        font-size: 0.9rem;
    }

    /* Acciones */
    .actions-container { display: flex; flex-direction: column; gap: 10px; margin-top: 15px; }
    
    .btn-action {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid;
    }

    .btn-print { background: #ebf5ff; color: #007bff; border-color: #d1e9ff; }
    .btn-print:hover { background: #007bff; color: white; }

    .btn-edit { background: #f0fff4; color: #38a169; border-color: #c6f6d5; }
    .btn-edit:hover { background: #38a169; color: white; }

    .btn-delete { background: #fff5f5; color: #e53e3e; border-color: #feb2b2; cursor: pointer; }
    .btn-delete:hover { background: #e53e3e; color: white; }

    /* Modal */
    .modal {
        display: none; position: fixed; z-index: 1000; 
        left: 0; top: 0; width: 100%; height: 100%; 
        background-color: rgba(0,0,0,0.5); backdrop-filter: blur(3px);
    }
    .modal-content {
        background-color: white; margin: 10% auto; padding: 30px;
        border-radius: 12px; width: 90%; max-width: 400px;
        text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    .modal-buttons { display: flex; gap: 10px; justify-content: center; margin-top: 25px; }
    .btn-confirm { background: #e53e3e; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; text-decoration: none; }
    .btn-cancel { background: #edf2f7; color: #4a5568; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; }

    @media (max-width: 768px) { .grid-expediente { grid-template-columns: 1fr; } }
</style>

<div class="ficha-container">
    <?php 
        // El botón volver cambia según el rol: el invitado vuelve a su buscador, el resto a estantes
        $urlVolver = ($_SESSION['usuario_rol'] === 'invitado') 
            ? "index.php?controller=invitado&action=index" 
            : "index.php?controller=estante";
    ?>
    <a href="<?php echo $urlVolver; ?>" class="btn-volver">
        <i class="fas fa-arrow-left"></i> Volver <?php echo ($_SESSION['usuario_rol'] === 'invitado') ? 'a la búsqueda' : 'a Estantes'; ?>
    </a>

    <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'actualizado'): ?>
        <div style="background: #f0fff4; color: #2f855a; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c6f6d5;">
            <i class="fas fa-check-circle"></i> Los datos han sido actualizados correctamente.
        </div>
    <?php endif; ?>

    <div class="grid-expediente">
        <div>
            <div class="card-ficha" style="text-align: center;">
                <div style="font-size: 4rem; color: #cbd5e0; margin-bottom: 15px;">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h3 style="margin:0; color: #2d3748;"><?php echo htmlspecialchars($militante['nombre_completo']); ?></h3>
                <p style="color:#718096; font-weight: 500;">CI: <?php echo $militante['ci']; ?></p>
                
                <div class="actions-container">
                    <a href="index.php?controller=reporte&action=fichaMilitante&id=<?php echo $militante['id']; ?>" 
                       target="_blank" class="btn-action btn-print">
                        <i class="fas fa-file-pdf"></i> Imprimir Ficha PDF
                    </a>

                    <?php if ($_SESSION['usuario_rol'] !== 'invitado'): ?>
                        <a href="index.php?controller=militante&action=editar&id=<?php echo $militante['id']; ?>" 
                           class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Editar Expediente
                        </a>

                        <button class="btn-action btn-delete" onclick="openModal()">
                            <i class="fas fa-trash-alt"></i> Eliminar Expediente
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-ficha">
                <div class="card-title"><i class="fas fa-box-open"></i> Ubicación Física</div>
                <div class="badge-ubicacion">
                    <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Estante</div>
                    <div style="font-size: 2.2rem; font-weight: 800;"><?php echo $militante['num_estante']; ?></div>
                    <hr style="border:0; border-top:1px solid #d1e9ff; margin:10px 0;">
                    <div style="font-size: 1rem;">Cajuela: <strong><?php echo $militante['cajuela']; ?></strong></div>
                    <div style="font-size: 1rem;">Posición: <strong><?php echo $militante['posicion_global']; ?></strong></div>
                </div>
            </div>
        </div>

        <div>
            <div class="card-ficha">
                <div class="card-title"><i class="fas fa-id-card"></i> Datos de Identidad</div>
                <div class="info-row"><div class="info-label">Sexo:</div><div class="info-value"><?php echo ($militante['sexo'] == 'M') ? 'Masculino' : 'Femenino'; ?></div></div>
                <div class="info-row"><div class="info-label">Color de Piel:</div><div class="info-value"><?php echo $militante['color_piel']; ?></div></div>
                <div class="info-row"><div class="info-label">Fecha Nacimiento:</div><div class="info-value"><?php echo date('d/m/Y', strtotime($militante['fecha_nacimiento'])); ?></div></div>
                
                <?php 
                    $nacimiento = new DateTime($militante['fecha_nacimiento']);
                    $hoy = new DateTime();
                    $edad = $hoy->diff($nacimiento)->y;
                ?>
                <div class="info-row"><div class="info-label">Edad actual:</div><div class="info-value"><?php echo $edad; ?> años</div></div>
            </div>

            <div class="card-ficha">
                <div class="card-title"><i class="fas fa-briefcase"></i> Información Política y Laboral</div>
                <div class="info-row"><div class="info-label">Ingreso PCC:</div><div class="info-value"><?php echo date('d/m/Y', strtotime($militante['fecha_pcc'])); ?></div></div>
                <div class="info-row"><div class="info-label">Centro Trabajo:</div><div class="info-value"><?php echo htmlspecialchars($militante['centro_trabajo']); ?></div></div>
                <div class="info-row"><div class="info-label">Cargo:</div><div class="info-value"><?php echo htmlspecialchars($militante['cargo']); ?></div></div>
                <div class="info-row"><div class="info-label">Escolaridad:</div><div class="info-value"><?php echo $militante['nivel_escolar']; ?> <?php echo !empty($militante['graduado_de']) ? '('.htmlspecialchars($militante['graduado_de']).')' : ''; ?></div></div>
            </div>

            <div class="card-ficha">
                <div class="card-title"><i class="fas fa-map-marker-alt"></i> Localización</div>
                <div class="info-row"><div class="info-label">Teléfono:</div><div class="info-value"><?php echo $militante['telefono']; ?></div></div>
                <div class="info-row"><div class="info-label">Dirección:</div><div class="info-value"><?php echo htmlspecialchars($militante['direccion']); ?></div></div>
            </div>

            <div class="card-ficha">
                <div class="card-title"><i class="fas fa-medal"></i> Reconocimientos</div>
                <?php 
                if(!empty($militante['condecoraciones'])):
                    $premios = explode(" | ", $militante['condecoraciones']);
                    foreach($premios as $p): ?>
                        <div class="reconocimiento-item"><?php echo htmlspecialchars($p); ?></div>
                    <?php endforeach; 
                else: ?>
                    <p style="color:#bdc3c7; font-style: italic;">Sin reconocimientos registrados.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($_SESSION['usuario_rol'] !== 'invitado'): ?>
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div style="color: #e53e3e; font-size: 3rem; margin-bottom: 15px;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h2 style="margin-bottom: 10px;">¿Estás seguro?</h2>
        <p style="color: #718096; line-height: 1.5;">
            Estás a punto de eliminar el expediente de <strong><?php echo htmlspecialchars($militante['nombre_completo']); ?></strong>.<br>
            Esta acción liberará el espacio en el estante y no se puede deshacer.
        </p>
        <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <a id="confirmDeleteBtn" href="index.php?controller=militante&action=eliminar&id=<?php echo $militante['id']; ?>" class="btn-confirm">Sí, Eliminar</a>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById("deleteModal");

    function openModal() {
        modal.style.display = "block";
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        modal.style.display = "none";
        document.body.style.overflow = "auto";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
<?php endif; ?>