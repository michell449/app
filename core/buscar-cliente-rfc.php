<?php
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');
$rfc = isset($_GET['rfc']) ? trim($_GET['rfc']) : '';
if ($rfc === '') {
    echo json_encode(['success' => false, 'error' => 'RFC vacío']);
    exit;
}
try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('SELECT id_cliente, nombre_comercial FROM sys_clientes WHERE rfc = ? AND activo = 1 LIMIT 1');
    $stmt->execute([$rfc]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($cliente) {
        echo json_encode(['success' => true, 'nombre_comercial' => $cliente['nombre_comercial'], 'id_cliente' => $cliente['id_cliente']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Cliente no encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
