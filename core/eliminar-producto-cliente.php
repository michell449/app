<?php
// core/eliminar-producto-cliente.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id_cliente']) || !isset($data['clave'])) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}
$id_cliente = intval($data['id_cliente']);
$clave = trim($data['clave']);

try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('DELETE FROM cat_productos_clientes WHERE id_cliente = ? AND clave = ?');
    $stmt->execute([$id_cliente, $clave]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
