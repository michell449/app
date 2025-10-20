<?php
// core/agregar-productos-cliente.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['productos']) || !is_array($data['productos']) || count($data['productos']) === 0) {
    echo json_encode(['success' => false, 'error' => 'No hay productos para agregar']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    $insertados = 0;
    foreach ($data['productos'] as $prod) {
        if (!isset($prod['id_cliente']) || !isset($prod['clave'])) continue;
        // Evitar duplicados
        $stmt = $conn->prepare('SELECT COUNT(*) FROM cat_productos_clientes WHERE id_cliente = ? AND clave = ?');
        $stmt->execute([$prod['id_cliente'], $prod['clave']]);
        if ($stmt->fetchColumn() == 0) {
            $stmt2 = $conn->prepare('INSERT INTO cat_productos_clientes (id_cliente, clave) VALUES (?, ?)');
            $stmt2->execute([$prod['id_cliente'], $prod['clave']]);
            $insertados++;
        }
    }
    echo json_encode(['success' => true, 'insertados' => $insertados]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>