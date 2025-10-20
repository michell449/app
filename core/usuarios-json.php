<?php
require_once __DIR__ . '/class/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$id_usuario = $_SESSION['USR_ID'] ?? 0;
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare('SELECT id_usuario, nombre, apellido, email FROM us_usuarios WHERE status = 1 AND id_usuario != :id_usuario ORDER BY nombre ASC');
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($usuarios);
