<?php

require_once __DIR__ . '/../config/database.php';

$vacantes = [];

try {

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM vacantes ORDER BY fecha_creacion DESC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $vacantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $exception) {
    error_log("Error al cargar las vacantes: " . $exception->getMessage());
    $vacantes = [];
}
?>