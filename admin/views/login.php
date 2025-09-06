<?php
require_once '../includes/auth_middleware.php';
guestMiddleware();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-container { max-width: 400px; margin: 100px auto; padding: 20px; background: white; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Acceso Administrativo</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <?php
                $errors = [
                    'invalid' => 'Credenciales inválidas',
                    'empty' => 'Por favor complete todos los campos',
                    'session' => 'Sesión expirada'
                ];
                echo $errors[$_GET['error']] ?? 'Error desconocido';
                ?>
            </div>
        <?php endif; ?>

        <form action="../actions/login_action.php" method="POST">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>