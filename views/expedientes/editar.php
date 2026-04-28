<style>
    :root {
        --step-bg: #edf2f7;
        --step-active: var(--accent-color);
    }
    .form-container {
        background: white; padding: 0; border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        max-width: 900px; margin: 0 auto; overflow: hidden;
    }
    .form-body { padding: 30px; }
    .form-section {
        margin-bottom: 35px; border-bottom: 1px solid #eee; padding-bottom: 20px;
    }
    .section-title {
        color: var(--primary-color); font-weight: bold; margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
        border-left: 4px solid #38a169; padding-left: 10px;
    }
    .form-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;
    }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .full-width { grid-column: 1 / -1; }
    label { font-weight: 600; font-size: 0.85rem; color: #4a5568; }
    input, select, textarea {
        padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.95rem;
    }
    input:read-only { background: #f7fafc; cursor: not-allowed; border-color: #edf2f7; }
    
    .btn-save {
        width: 100%; padding: 15px; background: #38a169; color: white;
        border: none; border-radius: 8px; font-weight: bold;
        cursor: pointer; transition: all 0.3s;
    }
    .btn-save:hover { background: #2f855a; }

    .condecoracion-row {
        display: flex; gap: 15px; margin-bottom: 10px; background: #f8fafc; 
        padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; align-items: flex-end;
    }
    .btn-remove {
        background: #fff5f5; color: #c53030; border: 1px solid #feb2b2;
        padding: 10px 12px; border-radius: 6px; cursor: pointer;
    }
</style>

<div class="header-page" style="margin-bottom: 20px;">
    <h2><i class="fas fa-edit"></i> Editar Expediente</h2>
    <p>Editando a: <strong><?php echo htmlspecialchars($militante['nombre_completo']); ?></strong></p>
</div>

<div class="form-container">
    <form id="expedienteForm" action="index.php?controller=militante&action=editar&id=<?php echo $militante['id']; ?>" method="POST">
        <div class="form-body">
            
            <div class="form-section">
                <div class="section-title"><i class="fas fa-id-card"></i> Datos de Identidad</div>
                <div class="form-grid">
                    <div class="form-group"><label>1er Nombre</label><input type="text" name="nombre1" value="<?php echo $militante['nombre1']; ?>" required></div>
                    <div class="form-group"><label>2do Nombre</label><input type="text" name="nombre2" value="<?php echo $militante['nombre2']; ?>"></div>
                    <div class="form-group"><label>1er Apellido</label><input type="text" name="apellido1" value="<?php echo $militante['apellido1']; ?>" required></div>
                    <div class="form-group"><label>2do Apellido</label><input type="text" name="apellido2" value="<?php echo $militante['apellido2']; ?>" required></div>
                    
                    <div class="form-group">
                        <label>Carnet de Identidad</label>
                        <input type="text" name="ci" id="ci" value="<?php echo $militante['ci']; ?>" maxlength="11" required>
                    </div>

                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac" id="fecha_nac" value="<?php echo $militante['fecha_nacimiento']; ?>" required readonly>
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input type="text" id="edad" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="sexo" required>
                            <option value="M" <?php echo ($militante['sexo'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                            <option value="F" <?php echo ($militante['sexo'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Color de Piel</label>
                        <select name="color_piel" required>
                            <?php $colores = ['Blanco', 'Negro', 'Mulato']; 
                            foreach($colores as $c): ?>
                                <option value="<?php echo $c; ?>" <?php echo ($militante['color_piel'] == $c) ? 'selected' : ''; ?>><?php echo $c; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title"><i class="fas fa-briefcase"></i> Información Política y Laboral</div>
                <div class="form-grid">
                    <div class="form-group"><label>Fecha de Ingreso al PCC</label><input type="date" name="fecha_pcc" value="<?php echo $militante['fecha_pcc']; ?>" required></div>
                    <div class="form-group">
                        <label>Núcleo de Base</label>
                        <select name="nucleo_id" required>
                            <?php foreach($nucleos as $n): ?>
                                <option value="<?php echo $n['id']; ?>" <?php echo ($militante['nucleo_id'] == $n['id']) ? 'selected' : ''; ?>>
                                    <?php echo $n['numero_nucleo'] . " - " . $n['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Centro de Trabajo Actual</label><input type="text" name="centro_trabajo" value="<?php echo htmlspecialchars($militante['centro_trabajo']); ?>" required></div>
                    <div class="form-group"><label>Cargo que Ocupa</label><input type="text" name="cargo" value="<?php echo htmlspecialchars($militante['cargo']); ?>" required></div>
                    <div class="form-group">
                        <label>Nivel Escolar</label>
                        <select name="nivel_escolar" id="nivel_escolar" required>
                            <option value="Primaria" <?php echo ($militante['nivel_escolar'] == 'Primaria') ? 'selected' : ''; ?>>Primaria</option>
                            <option value="Medio" <?php echo ($militante['nivel_escolar'] == 'Medio') ? 'selected' : ''; ?>>Medio</option>
                            <option value="Superior" <?php echo ($militante['nivel_escolar'] == 'Superior') ? 'selected' : ''; ?>>Superior</option>
                        </select>
                    </div>
                    <div class="form-group" id="campo_graduacion" style="<?php echo ($militante['nivel_escolar'] == 'Superior') ? 'display:block;' : 'display:none;'; ?>">
                        <label>¿De qué se graduó?</label>
                        <input type="text" name="graduado_de" id="input_graduado" value="<?php echo htmlspecialchars($militante['graduado_de']); ?>">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title"><i class="fas fa-medal"></i> Reconocimientos</div>
                <datalist id="lista-condecoraciones">
                    <?php foreach($listaCondecoraciones as $nombre): ?>
                        <option value="<?php echo htmlspecialchars($nombre); ?>">
                    <?php endforeach; ?>
                </datalist>

                <div id="reconocimientos-container">
                    <?php 
                    $premios = !empty($militante['condecoraciones']) ? explode(" | ", $militante['condecoraciones']) : [];
                    foreach($premios as $p): ?>
                        <div class="condecoracion-row">
                            <div style="flex:1;">
                                <label style="font-size:0.75rem; color:#718096;">Buscar Condecoración</label>
                                <input type="text" class="buscador-cond" list="lista-condecoraciones" value="<?php echo htmlspecialchars($p); ?>" oninput="sincronizarFila(this)">
                            </div>
                            <div style="flex:1;">
                                <label style="font-size:0.75rem; color:#718096;">Confirmada</label>
                                <input type="text" name="condecoraciones[]" class="cond-final" value="<?php echo htmlspecialchars($p); ?>" readonly required>
                            </div>
                            <button type="button" class="btn-remove" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" onclick="agregarReconocimiento()" style="background:none; border:1px dashed #38a169; color:#38a169; padding:8px 15px; cursor:pointer; border-radius:5px; margin-top:10px; font-weight:600;">
                    <i class="fas fa-plus"></i> Agregar otra condecoración
                </button>
            </div>

            <div class="form-section">
                <div class="section-title"><i class="fas fa-map-marker-alt"></i> Localización</div>
                <div class="form-grid">
                    <div class="form-group full-width"><label>Dirección Particular</label><input type="text" name="direccion" value="<?php echo htmlspecialchars($militante['direccion']); ?>" required></div>
                    <div class="form-group"><label>Teléfono</label><input type="tel" name="telefono" value="<?php echo $militante['telefono']; ?>" required></div>
                </div>
            </div>

            <button type="submit" class="btn-save">
                <i class="fas fa-sync-alt"></i> ACTUALIZAR DATOS DEL MILITANTE
            </button>
        </div>
    </form>
</div>

<script>
    const ciInput = document.getElementById('ci');
    const fechaNacInput = document.getElementById('fecha_nac');
    const edadInput = document.getElementById('edad');
    const nivelEscolar = document.getElementById('nivel_escolar');
    const campoGraduacion = document.getElementById('campo_graduacion');
    const opcionesValidas = <?php echo json_encode($listaCondecoraciones); ?>;

    function calcularTodo() {
        const ci = ciInput.value;
        if (ci.length >= 6) {
            let año = ci.substring(0, 2);
            let mes = ci.substring(2, 4);
            let dia = ci.substring(4, 6);
            let siglo = (parseInt(año) > 26) ? "19" : "20";
            let añoCompleto = siglo + año;
            
            if (parseInt(mes) >= 1 && parseInt(mes) <= 12) {
                const fecha = `${añoCompleto}-${mes}-${dia}`;
                fechaNacInput.value = fecha;
                
                const birthDate = new Date(fecha);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) age--;
                edadInput.value = age + " años";
            }
        }
    }

    // Ejecutar al cargar para mostrar la edad inicial
    window.onload = calcularTodo;
    ciInput.addEventListener('input', calcularTodo);

    nivelEscolar.addEventListener('change', function() {
        campoGraduacion.style.display = (this.value === 'Superior') ? 'block' : 'none';
    });

    function sincronizarFila(buscador) {
        const fila = buscador.closest('.condecoracion-row');
        const inputFinal = fila.querySelector('.cond-final');
        inputFinal.value = opcionesValidas.includes(buscador.value.trim()) ? buscador.value.trim() : "";
    }

    function agregarReconocimiento() {
        const container = document.getElementById('reconocimientos-container');
        const div = document.createElement('div');
        div.className = 'condecoracion-row';
        div.innerHTML = `
            <div style="flex:1;">
                <label style="font-size:0.75rem; color:#718096;">Buscar Condecoración</label>
                <input type="text" class="buscador-cond" list="lista-condecoraciones" oninput="sincronizarFila(this)">
            </div>
            <div style="flex:1;">
                <label style="font-size:0.75rem; color:#718096;">Confirmada</label>
                <input type="text" name="condecoraciones[]" class="cond-final" readonly required>
            </div>
            <button type="button" class="btn-remove" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    }
</script>