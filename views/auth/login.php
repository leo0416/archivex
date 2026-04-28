<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivex - Login</title>
    <link rel="icon" type="image/png" href="public/img/favicon.png">
    <link rel="stylesheet" href="public/css/css/all.min.css">
    <style>
        * { box-sizing: border-box; }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f4f7f6; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            padding: 15px;
        }

        .login-card { 
            background: white; 
            padding: 2.5rem 2rem; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 400px; 
            text-align: center; 
        }

        .login-logo {
            width: 80px; height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #27ae60;
            padding: 5px;
            background: white;
        }

        .login-card h2 { 
            color: #2c3e50; 
            margin-bottom: 1.5rem; 
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .form-group { margin-bottom: 1.2rem; text-align: left; }

        .form-group label { 
            display: block; margin-bottom: 0.6rem; 
            color: #555; font-size: 0.9rem; font-weight: 500;
        }

        .form-group input { 
            width: 100%; padding: 12px; 
            border: 1px solid #ddd; border-radius: 6px; 
            font-size: 1rem; transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none; border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .btn-login { 
            width: 100%; padding: 14px; border: none; 
            background: #27ae60; color: white; 
            border-radius: 6px; cursor: pointer; 
            font-size: 1rem; font-weight: 600;
            transition: background 0.3s;
        }

        .btn-login:hover { background: #219150; }

        /* Separador */
        .divider {
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            text-align: center;
            color: #999;
            font-size: 0.8rem;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; border-bottom: 1px solid #eee;
        }
        .divider:not(:empty)::before { margin-right: .5em; }
        .divider:not(:empty)::after { margin-left: .5em; }

        /* Botón de Invitado */
        .btn-guest {
            width: 100%; padding: 12px;
            background: #ebf5ff; color: #007bff;
            border: 1px solid #d1e9ff; border-radius: 6px;
            cursor: pointer; font-size: 0.95rem; font-weight: 600;
            transition: all 0.3s; text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-guest:hover { background: #007bff; color: white; }

        .error-msg { 
            background: #fdf2f2; color: #e74c3c; 
            padding: 10px; border-radius: 6px;
            border: 1px solid #f9d6d6; margin-bottom: 1.5rem; 
            font-size: 0.85rem; text-align: left;
        }

        .success-msg { 
            background: #e8f4fd; color: #2980b9; 
            padding: 10px; border-radius: 6px;
            border: 1px solid #d1e9f7; margin-bottom: 1.5rem; 
            font-size: 0.85rem; text-align: left;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="public/img/favicon.png" alt="Logo Archivex" class="login-logo">
        <h2>ARCHIVEX</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'logout'): ?>
            <div class="success-msg">
                <i class="fas fa-check-circle"></i> Sesión cerrada correctamente.
            </div>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=login" method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Usuario</label>
                <input type="text" name="usuario" required autofocus placeholder="Nombre de usuario">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-login">Entrar al Sistema</button>
        </form>

        <div class="divider">O TAMBIÉN</div>

        <form action="index.php?controller=auth&action=invitado" method="POST">
            <button type="submit" class="btn-guest">
                <i class="fas fa-eye"></i> Acceso Solo Consulta
            </button>
        </form>
    </div>
</body>
</html>