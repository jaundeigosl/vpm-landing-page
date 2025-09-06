<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: ../views/login.php?error=empty");
        exit();
    }

    try {

        $database = new Database();
        $db = $database->getConnection();
        $query = "SELECT id, password, username, email, activo FROM usuarios WHERE username = :username LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                $update_query = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id";
                $update_stmt = $db->prepare($update_query);
                $update_stmt->bindParam(':id', $user['id']);
                $update_stmt->execute();

                header("Location: ../views/dashboard.php");
                exit();

            }else {
                header("Location: ../views/login.php?error=invalid");
                exit();
            }
        }
    } catch(PDOException $exception) {
        // En caso de error de conexión o consulta
        error_log("Error de autenticación: " . $exception->getMessage());
        header("Location: ../views/login.php?error=internal");
        exit();
    }

}
?>