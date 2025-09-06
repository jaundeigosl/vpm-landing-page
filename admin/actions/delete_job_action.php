<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit();
}

authMiddleware();

if (!isset($_POST['id']) || empty(trim($_POST['id']))) {
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'ID de vacante no proporcionado.']);
    exit();
}

$id = trim($_POST['id']);

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM vacantes WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Vacante eliminada exitosamente.']);
    } else {
        http_response_code(500); 
        echo json_encode(['success' => false, 'message' => 'Error al eliminar la vacante.']);
    }

} catch (PDOException $exception) {
    http_response_code(500);
    error_log("Error al eliminar vacante: " . $exception->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
}

exit();
?>