<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

authMiddleware();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validar que las contraseñas no estén vacías y coincidan
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit();
    }
    
    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Las contraseñas nuevas no coinciden.']);
        exit();
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        // 1. Obtener el hash de la contraseña actual del usuario
        $query = "SELECT password FROM usuarios WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
            exit();
        }

        // 2. Verificar si la contraseña actual proporcionada es correcta
        if (!password_verify($current_password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Contraseña actual incorrecta.']);
            exit();
        }
        
        // 3. Hashear la nueva contraseña de forma segura
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // 4. Actualizar la contraseña en la base de datos
        $update_query = "UPDATE usuarios SET password = :password WHERE id = :user_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':password', $hashed_new_password);
        $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => '¡Contraseña actualizada con éxito!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
        }

    } catch(PDOException $exception) {
        error_log("Error de base de datos al cambiar la contraseña: " . $exception->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error de conexión con la base de datos.']);
    }

    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
}
?>