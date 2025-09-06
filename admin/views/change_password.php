<?php
require_once '../middleware/auth_middleware.php';
authMiddleware();
require_once '../includes/header.php';
?>

<div class="change-password-container">
    <h1>Cambiar Contraseña</h1>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success">
            <?php
            $messages = [
                'success' => 'Contraseña cambiada exitosamente',
                'mismatch' => 'Las contraseñas no coinciden',
                'invalid' => 'Contraseña actual incorrecta',
                'error' => 'Error al cambiar la contraseña'
            ];
            echo $messages[$_GET['message']] ?? '';
            ?>
        </div>
    <?php endif; ?>

    <form action="../actions/change_password_action.php" method="POST">
        <div class="form-group">
            <label for="current_password">Contraseña Actual:</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>
        
        <div class="form-group">
            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required minlength="6">
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmar Nueva Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        </div>
        
        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>