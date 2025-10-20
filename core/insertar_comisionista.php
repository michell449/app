<?php
// Script de inserción de datos en la tabla 'comisionistas'
// Recibe los datos por POST y los inserta en la base de datos

require_once __DIR__ . '/class/db.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $tipo = $_POST['tipo'] ?? '';

    $sql = "INSERT INTO comisionistas (nombre, tipo) VALUES (:nombre, :tipo)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        if ($stmt->execute([':nombre' => $nombre, ':tipo' => $tipo])) {
            echo 'Registro insertado correctamente.';
        } else {
            echo 'Error al insertar: ' . implode(' | ', $stmt->errorInfo());
        }
    } else {
        echo 'Error en la preparación: ' . implode(' | ', $conn->errorInfo());
    }
} else {
    // Inserción directa de ejemplo con los datos proporcionados
    $nombre = 'sis_rje';
    $tipo = 'Interno';
    $sql = "INSERT INTO comisionistas (nombre, tipo) VALUES (:nombre, :tipo)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        if ($stmt->execute([':nombre' => $nombre, ':tipo' => $tipo])) {
            echo 'Registro de ejemplo insertado correctamente.';
        } else {
            echo 'Error al insertar ejemplo: ' . implode(' | ', $stmt->errorInfo());
        }
    } else {
        echo 'Error en la preparación del ejemplo: ' . implode(' | ', $conn->errorInfo());
    }
}
?>
