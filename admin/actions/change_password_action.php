<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';
authMiddleware();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        header("Location: ../views/change_password.php?message=mismatch");
        exit();
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT password FROM usuarios WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        //no existe o la consulta falla
        if (!$user) {
            header("Location: ../views/change_password.php?message=error");
            exit();
        }

        //verificacion contraseña actual es correcta
        if (!password_verify($current_password, $user['password'])) {
            header("Location: ../views/change_password.php?message=invalid");
            exit();
        }
        
        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $update_query = "UPDATE usuarios SET password = :password WHERE id = :user_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':password', $hashed_new_password);
        $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        if ($update_stmt->execute()) {
            header("Location: ../views/change_password.php?message=success");
        } else {
            header("Location: ../views/change_password.php?message=error");
        }

    } catch(PDOException $exception) {
        error_log("Error de base de datos al cambiar la contraseña: " . $exception->getMessage());
        header("Location: ../views/change_password.php?message=error");
    }

    exit();
}
?>