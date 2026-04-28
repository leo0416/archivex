<style>
    /* 1. ESTILOS PARA ESCRITORIO (PC) */
    .tabla-usuarios {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto; /* Permite que las columnas se ajusten al contenido */
    }

    .tabla-usuarios thead tr {
        background: #edf2f7;
    }

    .tabla-usuarios th {
        padding: 15px;
        border-bottom: 2px solid #cbd5e0;
        text-align: left;
        color: #4a5568;
        font-weight: 700;
    }

    .tabla-usuarios td {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f5f9;
        color: #2d3748;
    }

    /* Alineación específica para la columna de acciones en PC */
    .actions-column-header { text-align: right !important; }
    .actions-cell { text-align: right; }

    /* 2. ESTILOS RESPONSIVE (Móvil) */
    @media (max-width: 768px) {
        .usuarios-container { padding: 5px; }

        .tabla-usuarios thead { display: none; }

        .tabla-usuarios, .tabla-usuarios tbody, .tabla-usuarios tr, .tabla-usuarios td {
            display: block;
            width: 100%;
        }

        .tabla-usuarios tr {
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .tabla-usuarios td {
            text-align: right;
            padding: 10px 5px;
            position: relative;
            border: none !important;
            display: flex; /* Flex solo en móvil para separar etiqueta de valor */
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f7fafc !important;
        }

        .tabla-usuarios td:last-child { border-bottom: none !important; }

        .tabla-usuarios td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #718096;
            font-size: 0.75rem;
            text-transform: uppercase;
            text-align: left;
        }

        .actions-cell {
            justify-content: flex-end !important;
            gap: 10px;
            padding-top: 15px !important;
        }
    }

    .fade-in { animation: fadeIn 0.5s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<div class="usuarios-container fade-in">
    <div class="card-ficha">
        <div class="card-title">
            <i class="fas fa-users-cog"></i> Gestión de Usuarios
        </div>

        <form action="index.php?controller=admin&action=guardarUsuario" method="POST" 
              style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; background: #f8fafc; padding: 20px; border-radius: 8px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
            
            <div class="form-group">
                <label style="display:block; margin-bottom:5px; font-size:0.85rem; font-weight:600;">Usuario</label>
                <input type="text" name="username" required placeholder="ej: rlopez" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; box-sizing: border-box;">
            </div>
            <div class="form-group">
                <label style="display:block; margin-bottom:5px; font-size:0.85rem; font-weight:600;">Nombre Completo</label>
                <input type="text" name="nombre" required placeholder="Nombre y Apellidos" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; box-sizing: border-box;">
            </div>
            <div class="form-group">
                <label style="display:block; margin-bottom:5px; font-size:0.85rem; font-weight:600;">Contraseña</label>
                <input type="password" name="password" required style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; box-sizing: border-box;">
            </div>
            <div class="form-group">
                <label style="display:block; margin-bottom:5px; font-size:0.85rem; font-weight:600;">Rol</label>
                <select name="rol" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; background:white;">
                    <option value="operador">Operador</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div style="display: flex; align-items: flex-end;">
                <button type="submit" class="btn-confirm" style="width:100%; padding: 12px; background: var(--primary-color); color:white; border:none; border-radius:6px; cursor:pointer; font-weight:bold;">
                    <i class="fas fa-plus-circle"></i> Registrar
                </button>
            </div>
        </form>

        <div style="overflow-x: auto;">
            <table class="tabla-usuarios">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th class="actions-column-header">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $u): ?>
                    <tr>
                        <td data-label="Nombre" style="font-weight: 600;"><?php echo htmlspecialchars($u['nombre_completo']); ?></td>
                        <td data-label="Usuario" style="color: #64748b;">@<?php echo htmlspecialchars($u['usuario']); ?></td>
                        <td data-label="Rol">
                            <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; background: <?php echo $u['rol'] === 'admin' ? '#fed7d7' : '#ebf5ff'; ?>; color: <?php echo $u['rol'] === 'admin' ? '#c53030' : '#004a99'; ?>;">
                                <?php echo strtoupper($u['rol']); ?>
                            </span>
                        </td>
                        <td data-label="Acciones" class="actions-cell">
                            <button onclick="abrirModalPassword(<?php echo $u['id']; ?>, '<?php echo $u['usuario']; ?>')" 
                                    title="Cambiar Contraseña"
                                    style="padding: 7px 10px; border: 1px solid #cbd5e0; border-radius: 6px; cursor:pointer; background:white; color:#4a5568;">
                                <i class="fas fa-key"></i>
                            </button>

                            <?php if($u['id'] != $_SESSION['usuario_id']): ?>
                                <button onclick="abrirModalAlerta(<?php echo $u['id']; ?>, '<?php echo htmlspecialchars($u['usuario']); ?>')"
                                   title="Eliminar Usuario"
                                   style="padding: 7px 10px; border: 1px solid #feb2b2; border-radius: 6px; background:#fff5f5; color:#c53030; cursor:pointer; margin-left: 5px;">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            <?php else: ?>
                                <span title="Tú (Protegido)" style="padding: 7px 10px; border: 1px solid #e2e8f0; border-radius: 6px; background:#f8fafc; color:#cbd5e0; margin-left: 5px;">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalPassword" class="modal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(3px);">
    <div style="background: white; width: 90%; max-width: 360px; margin: 15vh auto; padding: 25px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <h3 style="margin-top: 0;"><i class="fas fa-lock"></i> Nueva Clave</h3>
        <p id="txtUsuarioPassword" style="font-weight: bold; color: #4a5568; margin-bottom: 20px;"></p>
        <form action="index.php?controller=admin&action=cambiarPassword" method="POST">
            <input type="hidden" name="user_id" id="id_usuario_pass">
            <input type="password" name="new_password" placeholder="Escriba la nueva clave..." required 
                   style="width: 100%; padding: 12px; border: 1px solid #cbd5e0; border-radius: 8px; margin-bottom: 20px; box-sizing: border-box;">
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="cerrarModals()" style="flex:1; padding:12px; border:none; border-radius:8px; background:#edf2f7; font-weight:bold; cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:1; padding:12px; border:none; border-radius:8px; background:var(--primary-color); color:white; font-weight:bold; cursor:pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalAlertaEliminar" class="modal" style="display:none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); backdrop-filter: blur(3px);">
    <div style="background: white; width: 90%; max-width: 400px; margin: 15vh auto; padding: 30px; border-radius: 15px; text-align: center;">
        <div style="color: #e53e3e; font-size: 3rem; margin-bottom: 15px;"><i class="fas fa-exclamation-triangle"></i></div>
        <h2 style="margin:0;">¿Confirmar borrado?</h2>
        <p style="color: #4a5568; margin: 15px 0 25px 0;">Estás eliminando a <strong id="txtUsuarioEliminar"></strong>.</p>
        <div style="display: flex; gap: 10px;">
            <button type="button" onclick="cerrarModals()" style="flex:1; padding:12px; border:none; border-radius:8px; background:#edf2f7; font-weight:bold; cursor:pointer;">Cancelar</button>
            <a id="btnConfirmarEliminar" href="#" style="flex:1; padding:12px; border:none; border-radius:8px; background:#e53e3e; color:white; text-decoration:none; font-weight:bold; display:flex; align-items:center; justify-content:center;">Sí, eliminar</a>
        </div>
    </div>
</div>

<script>
    function cerrarModals() {
        document.getElementById('modalPassword').style.display = "none";
        document.getElementById('modalAlertaEliminar').style.display = "none";
    }

    function abrirModalPassword(id, usuario) {
        document.getElementById('id_usuario_pass').value = id;
        document.getElementById('txtUsuarioPassword').innerText = "Usuario: " + usuario;
        document.getElementById('modalPassword').style.display = "block";
    }

    function abrirModalAlerta(id, usuario) {
        document.getElementById('txtUsuarioEliminar').innerText = "@" + usuario;
        document.getElementById('btnConfirmarEliminar').href = "index.php?controller=admin&action=eliminarUsuario&id=" + id;
        document.getElementById('modalAlertaEliminar').style.display = "block";
    }

    window.onclick = function(e) {
        if (e.target.className === 'modal') cerrarModals();
    }
</script>