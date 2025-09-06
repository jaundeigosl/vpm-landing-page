<?php
require_once '../includes/auth_middleware.php';
require_once '../config/database.php';

header('Content-Type: application/json');

authMiddleware();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit();
}

$id = trim($_POST['id'] ?? '');
$nombre_puesto = trim($_POST['nombre_puesto'] ?? '');
$ubicacion = trim($_POST['ubicacion'] ?? '');
$resumen = trim($_POST['resumen'] ?? '');
$requisitos = trim($_POST['requisitos'] ?? '');
$edad = trim($_POST['edad'] ?? '');
$sexo = trim($_POST['sexo'] ?? '');
$escolaridad = trim($_POST['escolaridad'] ?? '');
$conocimientos = trim($_POST['conocimientos'] ?? '');
$funciones = trim($_POST['funciones'] ?? '');
$beneficios = trim($_POST['beneficios'] ?? '');
$sueldo = trim($_POST['sueldo'] ?? '');
$prestaciones = trim($_POST['prestaciones'] ?? '');

if (empty($id) || empty($nombre_puesto) || empty($ubicacion) || empty($resumen) || 
    empty($requisitos) || empty($edad) || empty($sexo) || empty($escolaridad) || 
    empty($conocimientos) || empty($funciones) || empty($beneficios) || 
    empty($sueldo) || empty($prestaciones)) {
    
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos obligatorios.']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Lógica para mapear el valor de 'sexo' a un solo carácter
    $sexo_char = '';
    switch ($sexo) {
        case 'Masculino':
            $sexo_char = 'M';
            break;
        case 'Femenino':
            $sexo_char = 'F';
            break;
        case 'Indistinto':
            $sexo_char = 'I';
            break;
        default:
            $sexo_char = 'I'; // Valor por defecto
            break;
    }

    $query = "UPDATE vacantes SET 
                nombre_puesto = :nombre_puesto,
                ubicacion = :ubicacion,
                resumen = :resumen,
                requisitos = :requisitos,
                edad = :edad,
                sexo = :sexo,
                escolaridad = :escolaridad,
                conocimientos = :conocimientos,
                funciones = :funciones,
                beneficios = :beneficios,
                sueldo = :sueldo,
                prestaciones = :prestaciones
              WHERE id = :id";
    
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':nombre_puesto', $nombre_puesto);
    $stmt->bindParam(':ubicacion', $ubicacion);
    $stmt->bindParam(':resumen', $resumen);
    $stmt->bindParam(':requisitos', $requisitos);
    $stmt->bindParam(':edad', $edad);
    $stmt->bindParam(':sexo', $sexo_char); // Usa la variable con el carácter
    $stmt->bindParam(':escolaridad', $escolaridad);
    $stmt->bindParam(':conocimientos', $conocimientos);
    $stmt->bindParam(':funciones', $funciones);
    $stmt->bindParam(':beneficios', $beneficios);
    $stmt->bindParam(':sueldo', $sueldo);
    $stmt->bindParam(':prestaciones', $prestaciones);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Vacante actualizada exitosamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la vacante.']);
    }
} catch (PDOException $exception) {
    http_response_code(500);
    error_log("Error PDO al actualizar vacante: " . $exception->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error de conexión a la base de datos.',
        'error' => $exception->getMessage()
    ]);
} catch (Exception $exception) {
    http_response_code(500);
    error_log("Error general al actualizar vacante: " . $exception->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error interno del servidor. Inténtalo de nuevo.',
        'error' => $exception->getMessage()
    ]);
}
?>