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
        border-left: 4px solid var(--accent-color); padding-left: 10px;
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
        width: 100%; padding: 15px; background: #cbd5e0; color: white;
        border: none; border-radius: 8px; font-weight: bold;
        cursor: not-allowed; transition: all 0.3s;
    }
    .btn-save.enabled { background: var(--accent-color); cursor: pointer; }
    .error-msg { color: #e74c3c; font-size: 0.75rem; display: none; }
    
    .condecoracion-row {
        display: flex; gap: 15px; margin-bottom: 10px; background: #f8fafc; 
        padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; align-items: flex-end;
    }
    .btn-remove {
        background: #fff5f5; color: #c53030; border: 1px solid #feb2b2;
        padding: 10px 12px; border-radius: 6px; cursor: pointer;
    }
    .btn-remove:hover { background: #fc8181; color: white; }

    @media (max-width: 600px) {
        .form-body { padding: 15px; }
        .form-grid { grid-template-columns: 1fr; }
        .condecoracion-row { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="header-page" style="margin-bottom: 20px;">
    <h2><i class="fas fa-file-signature"></i> Creación de Expediente</h2>
    <p>Número de Expediente: <strong id="next-exp-id">Autogenerado</strong></p>
</div>

<?php if(isset($_GET['error']) && $_GET['error'] == 'ci_duplicado'): ?>
    <div class="alert-error" style="background:#fff5f5; color:#c53030; padding:15px; border-radius:8px; margin-bottom:20px; border:1px solid #feb2b2;">
        <i class="fas fa-exclamation-circle"></i> 
        Error: Ya existe un militante registrado con ese Carnet de Identidad.
    </div>
<?php endif; ?>

<div class="form-container">
    <form id="expedienteForm" action="index.php?controller=militante&action=nuevo" method="POST">
        <div class="form-body">
            
            <div class="form-section">
                <div class="section-title"><i class="fas fa-id-card"></i> Datos de Identidad</div>
                <div class="form-grid">
                    <div class="form-group"><label>1er Nombre</label><input type="text" name="nombre1" required></div>
                    <div class="form-group"><label>2do Nombre</label><input type="text" name="nombre2"></div>
                    <div class="form-group"><label>1er Apellido</label><input type="text" name="apellido1" required></div>
                    <div class="form-group"><label>2do Apellido</label><input type="text" name="apellido2" required></div>
                    
                    <div class="form-group">
                        <label>Carnet de Identidad</label>
                        <input type="text" name="ci" id="ci" maxlength="11" pattern="\d{11}" required placeholder="Ej: 99041600000">
                        <span class="error-msg" id="ci-error">Debe tener 11 dígitos numéricos.</span>
                    </div>

                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nac" id="fecha_nac" required readonly>
                    </div>
                    <div class="form-group">
                        <label>Edad</label>
                        <input type="text" name="edad" id="edad" readonly placeholder="Auto-calculada">
                    </div>
                    
                    <div class="form-group">
                        <label>Sexo</label>
                        <select name="sexo" required>
                            <option value="">- Seleccione -</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Color de Piel</label>
                        <select name="color_piel" required>
                            <option value="">- Seleccione -</option>
                            <option value="Blanco">Blanco</option>
                            <option value="Negro">Negro</option>
                            <option value="Mulato">Mulato</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title"><i class="fas fa-briefcase"></i> Información Política y Laboral</div>
                <div class="form-grid">
                    <div class="form-group"><label>Fecha de Ingreso al PCC</label><input type="date" name="fecha_pcc" required></div>
                    <div class="form-group">
                        <label>Núcleo de Base</label>
                        <select name="nucleo_id" required>
                            <option value="">- Seleccione Núcleo -</option>
                            <?php foreach($nucleos as $n): ?>
                                <option value="<?php echo $n['id']; ?>"><?php echo $n['numero_nucleo'] . " - " . $n['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label>Centro de Trabajo Actual</label><input type="text" name="centro_trabajo" required></div>
                    <div class="form-group"><label>Cargo que Ocupa</label><input type="text" name="cargo" required></div>
                    <div class="form-group">
                        <label>Nivel Escolar</label>
                        <select name="nivel_escolar" id="nivel_escolar" required>
                            <option value="">- Seleccione -</option>
                            <option value="Primaria">Primaria</option>
                            <option value="Medio">Medio</option>
                            <option value="Superior">Superior</option>
                        </select>
                    </div>
                    <div class="form-group" id="campo_graduacion" style="display:none;">
                        <label>¿De qué se graduó?</label>
                        <input type="text" name="graduado_de" id="input_graduado">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title"><i class="fas fa-medal"></i> Reconocimientos y Otros</div>
                
                <datalist id="lista-condecoraciones">
                    <?php foreach($listaCondecoraciones as $nombre): ?>
                        <option value="<?php echo htmlspecialchars($nombre); ?>">
                    <?php endforeach; ?>
                </datalist>

                <div id="reconocimientos-container">
                    <div class="condecoracion-row">
                        <div style="flex:1;">
                            <label style="font-size:0.75rem; color:#718096;">Buscar Condecoración</label>
                            <input type="text" class="buscador-cond" list="lista-condecoraciones" placeholder="Escriba para buscar..." oninput="sincronizarFila(this)">
                        </div>
                        <div style="flex:1;">
                            <label style="font-size:0.75rem; color:#718096;">Confirmada (Para guardar)</label>
                            <input type="text" name="condecoraciones[]" class="cond-final" readonly placeholder="No seleccionada" required>
                        </div>
                        <button type="button" class="btn-remove" onclick="this.parentElement.remove(); verificarFormulario();">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <button type="button" onclick="agregarReconocimiento()" style="background:none; border:1px dashed var(--accent-color); color:var(--accent-color); padding:8px 15px; cursor:pointer; border-radius:5px; margin-top:10px; font-weight:600;">
                    <i class="fas fa-plus"></i> Agregar otra condecoración
                </button>
            </div>

            <div class="form-section">
                <div class="section-title"><i class="fas fa-map-marker-alt"></i> Localización y Contacto</div>
                <div class="form-grid">
                    <div class="form-group full-width"><label>Dirección Particular</label><input type="text" name="direccion" required></div>
                    <div class="form-group"><label>Teléfono</label><input type="tel" name="telefono" required></div>
                </div>
            </div>

            <button type="submit" id="btnSubmit" class="btn-save">
                <i class="fas fa-save"></i> GUARDAR EXPEDIENTE EN SISTEMA
            </button>
        </div>
    </form>
</div>

<script>
    const form = document.getElementById('expedienteForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const ciInput = document.getElementById('ci');
    const fechaNacInput = document.getElementById('fecha_nac');
    const edadInput = document.getElementById('edad');
    const nivelEscolar = document.getElementById('nivel_escolar');
    const campoGraduacion = document.getElementById('campo_graduacion');
    
    const opcionesValidas = <?php echo json_encode($listaCondecoraciones); ?>;

    // Lógica para calcular fecha y edad desde el CI
    ciInput.addEventListener('input', function() {
        // Solo números
        this.value = this.value.replace(/[^0-9]/g, '');
        const ci = this.value;
        
        // Mostrar error si no tiene 11
        document.getElementById('ci-error').style.display = (ci.length === 11) ? 'none' : 'block';

        if (ci.length >= 6) {
            let año = ci.substring(0, 2);
            let mes = ci.substring(2, 4);
            let dia = ci.substring(4, 6);

            // Determinar el siglo (Basado en el año actual 2026)
            // Si el año es mayor que 26, asumimos 1900, si no 2000
            let siglo = (parseInt(año) > 26) ? "19" : "20";
            let añoCompleto = siglo + año;

            // Validar que el mes y día sean razonables antes de asignar
            if (parseInt(mes) >= 1 && parseInt(mes) <= 12 && parseInt(dia) >= 1 && parseInt(dia) <= 31) {
                const fechaFormateada = `${añoCompleto}-${mes}-${dia}`;
                fechaNacInput.value = fechaFormateada;
                
                // Calcular Edad inmediatamente
                const birthDate = new Date(fechaFormateada);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                edadInput.value = age + " años";
            }
        } else {
            fechaNacInput.value = "";
            edadInput.value = "";
        }
        verificarFormulario();
    });

    function sincronizarFila(buscador) {
        const fila = buscador.closest('.condecoracion-row');
        const inputFinal = fila.querySelector('.cond-final');
        const valor = buscador.value.trim();

        if (opcionesValidas.includes(valor)) {
            inputFinal.value = valor;
            buscador.style.borderColor = "#cbd5e0";
        } else {
            inputFinal.value = "";
            buscador.style.borderColor = "#e74c3c";
        }
        verificarFormulario();
    }

    function agregarReconocimiento() {
        const container = document.getElementById('reconocimientos-container');
        const div = document.createElement('div');
        div.className = 'condecoracion-row';
        div.innerHTML = `
            <div style="flex:1;">
                <label style="font-size:0.75rem; color:#718096;">Buscar Condecoración</label>
                <input type="text" class="buscador-cond" list="lista-condecoraciones" placeholder="Escriba para buscar..." oninput="sincronizarFila(this)">
            </div>
            <div style="flex:1;">
                <label style="font-size:0.75rem; color:#718096;">Confirmada (Para guardar)</label>
                <input type="text" name="condecoraciones[]" class="cond-final" readonly placeholder="No seleccionada" required>
            </div>
            <button type="button" class="btn-remove" onclick="this.parentElement.remove(); verificarFormulario();">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
        verificarFormulario();
    }

    function verificarFormulario() {
        const isValid = form.checkValidity() && ciInput.value.length === 11;
        btnSubmit.disabled = !isValid;
        btnSubmit.classList.toggle('enabled', isValid);
    }

    nivelEscolar.addEventListener('change', function() {
        const isSuperior = this.value === 'Superior';
        campoGraduacion.style.display = isSuperior ? 'block' : 'none';
        document.getElementById('input_graduado').required = isSuperior;
    });

    form.addEventListener('input', verificarFormulario);
</script>