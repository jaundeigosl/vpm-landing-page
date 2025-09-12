<?php
// Incluir la clase de conexión a la base de datos
require_once '../config/database.php';

// Limpiar cualquier output previo
if (ob_get_length()) ob_clean();

header('Content-Type: application/json');

if (!isset($_POST['vacante_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de vacante no proporcionado.']);
    exit;
}

$vacante_id = $_POST['vacante_id']; 

try {
    // Instanciar la clase Database para obtener la conexión
    $database = new Database();
    $pdo = $database->getConnection();

    // Preparar y ejecutar la consulta de actualización
    $sql_update = "UPDATE vacantes SET aplicaciones = aplicaciones + 1 WHERE id = :vacante_id";
    $stmt = $pdo->prepare($sql_update);
    $stmt->bindParam(':vacante_id', $vacante_id, PDO::PARAM_INT);
    $stmt->execute();
    
    echo json_encode(['success' => true, 'message' => 'Aplicación recibida y contador de aplicaciones actualizado.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al procesar la aplicación: ' . $e->getMessage()]);
}

exit;
?>