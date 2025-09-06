<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$vacantes = [];

try {
    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, nombre_puesto, ubicacion, resumen, requisitos, edad, sexo, escolaridad, conocimientos, funciones, beneficios, sueldo, prestaciones FROM vacantes ORDER BY fecha_creacion DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $vacantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode($vacantes);

} catch(PDOException $exception) {
    // Si hay un error, devolver un JSON con el mensaje de error
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Error al cargar las vacantes: ' . $exception->getMessage()]);
    error_log("Error al cargar las vacantes públicas: " . $exception->getMessage());
}
?>