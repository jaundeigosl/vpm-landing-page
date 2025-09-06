<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

header('Content-Type: application/json');

authMiddleware();

if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de vacante no proporcionado.']);
    exit();
}

$id = trim($_GET['id']);

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM vacantes WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $vacante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vacante) {
        echo json_encode($vacante);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Vacante no encontrada.']);
    }

} catch (PDOException $exception) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Error de base de datos: ' . $exception->getMessage()]);
    error_log("Error al obtener detalles de vacante: " . $exception->getMessage());
}
?>