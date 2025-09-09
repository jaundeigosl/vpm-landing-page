<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

authMiddleware(['admin']);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit();
}

$user_id = trim($_POST['id'] ?? '');

if (empty($user_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado.']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();

    if ($_SESSION['user_id'] == $user_id) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propia cuenta.']);
        exit();
    }

    $query = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente.']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado.']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
    }

} catch (PDOException $exception) {
    http_response_code(500);
    error_log("Error al eliminar usuario: " . $exception->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
}
?>