<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nombre_puesto = trim($_POST['nombre_puesto']);
    $ubicacion = trim($_POST['ubicacion']);
    $resumen = trim($_POST['resumen']);
    $requisitos = trim($_POST['requisitos']);
    $edad = trim($_POST['edad']);
    $sexo = trim($_POST['sexo']);
    $escolaridad = trim($_POST['escolaridad']);
    $conocimientos = trim($_POST['conocimientos']);
    $funciones = trim($_POST['funciones']);
    $beneficios = trim($_POST['beneficios']);
    $sueldo = trim($_POST['sueldo']);
    $prestaciones = trim($_POST['prestaciones']);

    if (empty($nombre_puesto) || empty($ubicacion) || empty($resumen) || empty($requisitos) || empty($edad) || empty($sexo) || empty($escolaridad) || empty($conocimientos) || empty($funciones) || empty($beneficios) || empty($sueldo) || empty($prestaciones)) {
        header("Location: ../views/dashboard.php?message=add_error");
        exit();
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        $usuario_id = $_SESSION['user_id'];

        $query = "INSERT INTO vacantes 
                  (nombre_puesto, ubicacion, resumen, requisitos, edad, sexo, escolaridad, conocimientos, funciones, beneficios, sueldo, prestaciones, usuario_id) 
                  VALUES (:nombre_puesto, :ubicacion, :resumen, :requisitos, :edad, :sexo, :escolaridad, :conocimientos, :funciones, :beneficios, :sueldo, :prestaciones, :usuario_id)";
        
        $stmt = $db->prepare($query);

        // Vincular los parámetros
        $stmt->bindParam(':nombre_puesto', $nombre_puesto);
        $stmt->bindParam(':ubicacion', $ubicacion);
        $stmt->bindParam(':resumen', $resumen);
        $stmt->bindParam(':requisitos', $requisitos);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':escolaridad', $escolaridad);
        $stmt->bindParam(':conocimientos', $conocimientos);
        $stmt->bindParam(':funciones', $funciones);
        $stmt->bindParam(':beneficios', $beneficios);
        $stmt->bindParam(':sueldo', $sueldo);
        $stmt->bindParam(':prestaciones', $prestaciones);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../views/dashboard.php?message=add_success");
            exit();
        } else {
            header("Location: ../views/dashboard.php?message=add_error");
            exit();
        }

    } catch (PDOException $exception) {
        error_log("Error al agregar vacante: " . $exception->getMessage());
        header("Location: ../views/dashboard.php?message=add_error");
        exit();
    }
} else {
    header("Location: ../views/dashboard.php");
    exit();
}
?>