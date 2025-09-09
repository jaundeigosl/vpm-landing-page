<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

authMiddleware(['admin']);

header('Content-Type: application/json');

try {
    $database = new Database();
    $db = $database->getConnection();

    $current_user_id = $_SESSION['user_id'];
    
    $query = "SELECT id, username FROM usuarios WHERE role != 'admin' AND id != :current_user_id";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'users' => $users
    ]);

} catch (PDOException $exception) {
    http_response_code(500);
    error_log("Error al obtener usuarios: " . $exception->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al cargar la lista de usuarios.'
    ]);
}
?>