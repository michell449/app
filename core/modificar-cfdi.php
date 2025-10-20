<?php
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');

// Solo acepta POST y JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? intval($input['id']) : 0;
$estado = isset($input['estado']) ? trim($input['estado']) : '';

// Permitir también el estado 'EFO' y 'efo'
if ($id <= 0 || !in_array(strtolower($estado), ['pendiente', 'pagado', 'cancelado', 'efo'])) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    $sql = "UPDATE cf_cfdis SET estado = :estado WHERE id_cfdi = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':estado' => $estado, ':id' => $id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se actualizó ningún registro']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al actualizar: ' . $e->getMessage()]);
}
