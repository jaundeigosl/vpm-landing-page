<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

authMiddleware(['admin']);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = 'user';

    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit();
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        $query_check = "SELECT id FROM usuarios WHERE username = :username";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':username', $username);
        $stmt_check->execute();
        
        if ($stmt_check->fetch(PDO::FETCH_ASSOC)) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'El nombre de usuario ya existe.']);
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query_insert = "INSERT INTO usuarios (username, password, role) VALUES (:username, :password, :role)";
        $stmt_insert = $db->prepare($query_insert);
        
        $stmt_insert->bindParam(':username', $username);
        $stmt_insert->bindParam(':password', $hashed_password);
        $stmt_insert->bindParam(':role', $role);

        if ($stmt_insert->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear el usuario.']);
        }

    } catch (PDOException $exception) {
        http_response_code(500);
        error_log("Error al crear usuario: " . $exception->getMessage());
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}
?>