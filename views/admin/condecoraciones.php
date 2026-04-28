<div class="card-ficha">
    <div class="card-title">
        <i class="fas fa-medal"></i> Gestión de Condecoraciones
    </div>

    <form action="index.php?controller=admin&action=guardarCondecoracion" method="POST" id="formCondecoracion" 
          onsubmit="return validarFormulario(this)"
          style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
        
        <input type="hidden" name="id" id="cond_id">
        
        <div class="form-group" style="grid-column: span 2;">
            <label id="form-label" style="display:block; margin-bottom:5px; font-size:0.9rem; font-weight:bold;">Nombre de la Condecoración</label>
            <input type="text" name="nombre" id="cond_nombre" required 
                   placeholder="Ej: Orden José Martí" 
                   style="width:100%; padding:8px; border:1px solid #cbd5e0; border-radius:4px;">
            <small id="error-msg" style="color: #e74c3c; display: none; font-size: 0.8rem; margin-top: 5px;">
                El nombre no puede ser puramente numérico ni estar vacío.
            </small>
        </div>

        <div class="form-group" style="display: flex; align-items: flex-end; gap:10px;">
            <button type="submit" class="btn-confirm" style="flex:1; padding: 10px; background: var(--accent-color); color:white; border:none; border-radius:4px; cursor:pointer;">
                <i class="fas fa-save"></i> Guardar
            </button>
            <button type="button" onclick="resetForm()" id="btnReset" style="display:none; padding: 10px; background: #94a3b8; color:white; border:none; border-radius:4px; cursor:pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </form>

    <?php if(isset($_GET['error'])): ?>
        <div style="background: #fff5f5; color: #c53030; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #feb2b2; font-size: 0.9rem;">
            <i class="fas fa-exclamation-circle"></i> 
            <?php 
                echo ($_GET['error'] === 'fk') ? 'No se puede eliminar: Está asociada a registros activos.' : 'Datos inválidos o duplicados.';
            ?>
        </div>
    <?php endif; ?>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #edf2f7; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #cbd5e0;">Listado de Órdenes y Medallas</th>
                    <th style="padding: 12px; border-bottom: 2px solid #cbd5e0; text-align: right; width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($condecoraciones as $c): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><?php echo htmlspecialchars($c['nombre']); ?></td>
                    <td style="padding: 12px; text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
                        <button onclick="editarCond(<?php echo htmlspecialchars(json_encode($c)); ?>)" 
                                style="background:none; border:1px solid #3498db; color:#3498db; padding:4px 8px; border-radius:4px; cursor:pointer;">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="confirmarEliminar(<?php echo $c['id']; ?>)" 
                                style="background:none; border:1px solid #e74c3c; color:#e74c3c; padding:4px 8px; border-radius:4px; cursor:pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalEliminar" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:12px; max-width:400px; width:90%; text-align:center;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #e74c3c; margin-bottom: 15px;"></i>
        <h3>¿Confirmar eliminación?</h3>
        <p>Esta acción es permanente.</p>
        <div style="display:flex; gap:10px; justify-content:center; margin-top:25px;">
            <button onclick="cerrarModal()" style="flex:1; padding:10px; border-radius:6px; border:1px solid #cbd5e0; cursor:pointer;">Cancelar</button>
            <a id="btnConfirmarBorrado" href="#" style="flex:1; padding:10px; border-radius:6px; background:#e74c3c; color:white; text-decoration:none;">Eliminar</a>
        </div>
    </div>
</div>

<script>
    function validarFormulario(form) {
        const nombre = document.getElementById('cond_nombre').value.trim();
        const errorMsg = document.getElementById('error-msg');
        
        // Validación: No permitir solo números y evitar strings vacíos
        // (Asumimos que una condecoración debe tener al menos letras)
        const soloNumeros = /^\d+$/.test(nombre);

        if (nombre === "" || soloNumeros) {
            errorMsg.style.display = 'block';
            document.getElementById('cond_nombre').style.borderColor = '#e74c3c';
            return false;
        }
        
        errorMsg.style.display = 'none';
        return true;
    }

    function editarCond(data) {
        document.getElementById('cond_id').value = data.id;
        document.getElementById('cond_nombre').value = data.nombre;
        document.getElementById('form-label').innerText = "Editando Condecoración";
        document.getElementById('btnReset').style.display = 'block';
        document.getElementById('cond_nombre').focus();
    }

    function resetForm() {
        document.getElementById('cond_id').value = '';
        document.getElementById('formCondecoracion').reset();
        document.getElementById('form-label').innerText = "Nombre de la Condecoración";
        document.getElementById('btnReset').style.display = 'none';
        document.getElementById('error-msg').style.display = 'none';
        document.getElementById('cond_nombre').style.borderColor = '#cbd5e0';
    }

    function confirmarEliminar(id) {
        const modal = document.getElementById('modalEliminar');
        const btnConfirmar = document.getElementById('btnConfirmarBorrado');
        btnConfirmar.href = "index.php?controller=admin&action=eliminarCondecoracion&id=" + id;
        modal.style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById('modalEliminar').style.display = "none";
    }
</script>