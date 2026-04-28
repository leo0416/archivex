<div class="card-ficha">
    <div class="card-title">
        <i class="fas fa-microchip"></i> Gestión de Núcleos
    </div>

    <form action="index.php?controller=admin&action=guardarNucleo" method="POST" id="formNucleo" 
          style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
        
        <input type="hidden" name="id" id="nucleo_id">
        
        <div class="form-group">
            <label style="display:block; margin-bottom:5px; font-size:0.9rem; font-weight:bold;">Número de Núcleo</label>
            <input type="text" name="numero_nucleo" id="n_numero" required placeholder="Ej: 105" 
                   style="width:100%; padding:8px; border:1px solid #cbd5e0; border-radius:4px;">
        </div>

        <div class="form-group">
            <label style="display:block; margin-bottom:5px; font-size:0.9rem; font-weight:bold;">Nombre del Núcleo</label>
            <input type="text" name="nombre" id="n_nombre" required placeholder="Ej: Núcleo Centro" 
                   style="width:100%; padding:8px; border:1px solid #cbd5e0; border-radius:4px;">
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

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #edf2f7; text-align: left;">
                    <th style="padding: 12px; border-bottom: 2px solid #cbd5e0; width: 80px;">Nro.</th>
                    <th style="padding: 12px; border-bottom: 2px solid #cbd5e0;">Nombre del Núcleo</th>
                    <th style="padding: 12px; border-bottom: 2px solid #cbd5e0; text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($nucleos as $n): ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;"><strong><?php echo htmlspecialchars($n['numero_nucleo']); ?></strong></td>
                    <td style="padding: 12px;"><?php echo htmlspecialchars($n['nombre']); ?></td>
                    <td style="padding: 12px; text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
                        <button onclick="editarNucleo(<?php echo htmlspecialchars(json_encode($n)); ?>)" 
                                style="background:none; border:1px solid #3498db; color:#3498db; padding:4px 8px; border-radius:4px; cursor:pointer;" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="confirmarEliminar(<?php echo $n['id']; ?>)" 
                                style="background:none; border:1px solid #e74c3c; color:#e74c3c; padding:4px 8px; border-radius:4px; cursor:pointer;" title="Eliminar">
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
    <div style="background:white; padding:30px; border-radius:12px; max-width:400px; width:90%; text-align:center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #e74c3c; margin-bottom: 15px;"></i>
        <h3 style="margin-bottom:10px;">¿Eliminar núcleo?</h3>
        <p style="color:#64748b; font-size:0.95rem;">Esta acción no se podrá completar si el núcleo tiene militantes asociados.</p>
        <div style="display:flex; gap:10px; justify-content:center; margin-top:25px;">
            <button onclick="cerrarModal()" style="flex:1; padding:10px; border-radius:6px; border:1px solid #cbd5e0; background:#f8fafc; cursor:pointer; font-weight:bold;">Cancelar</button>
            <a id="btnConfirmarBorrado" href="#" style="flex:1; padding:10px; border-radius:6px; background:#e74c3c; color:white; text-decoration:none; font-weight:bold; display:flex; align-items:center; justify-content:center;">Eliminar</a>
        </div>
    </div>
</div>

<script>
    function editarNucleo(data) {
        document.getElementById('nucleo_id').value = data.id;
        document.getElementById('n_numero').value = data.numero_nucleo;
        document.getElementById('n_nombre').value = data.nombre;
        document.getElementById('btnReset').style.display = 'block';
        document.getElementById('n_numero').focus();
    }

    function resetForm() {
        document.getElementById('nucleo_id').value = '';
        document.getElementById('formNucleo').reset();
        document.getElementById('btnReset').style.display = 'none';
    }

    // Funciones del Modal
    function confirmarEliminar(id) {
        const modal = document.getElementById('modalEliminar');
        const btnConfirmar = document.getElementById('btnConfirmarBorrado');
        // Construimos la URL de eliminación
        btnConfirmar.href = "index.php?controller=admin&action=eliminarNucleo&id=" + id;
        modal.style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById('modalEliminar').style.display = "none";
    }

    // Cerrar si se hace clic fuera del contenido blanco
    window.onclick = function(event) {
        const modal = document.getElementById('modalEliminar');
        if (event.target == modal) {
            cerrarModal();
        }
    }
</script>