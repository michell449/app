<?php
require_once 'class/db.php';
require_once 'class/crud.php';

header('Content-Type: application/json'); // Asegura respuesta JSON

$db = new Database();
$pdo = $db->getConnection();

$nombre = $_GET['nombre'] ?? '';
$crud = new crud($pdo);

try {
    $sql = "SELECT * FROM sys_clientes WHERE nombre_comercial LIKE ?";
    $like = "%$nombre%";
    $result = $crud->customQuery($sql, [$like]);
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode([]);
}