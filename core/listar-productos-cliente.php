<?php
// core/listar-productos-cliente.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';

if (!isset($_GET['id_cliente']) || !is_numeric($_GET['id_cliente'])) {
    echo json_encode(['success' => false, 'error' => 'ID de cliente inválido']);
    exit;
}

$id_cliente = intval($_GET['id_cliente']);

try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('SELECT cpc.clave, cp.descripcion FROM cat_productos_clientes cpc INNER JOIN cat_productos cp ON cpc.clave = cp.clave WHERE cpc.id_cliente = ?');
    $stmt->execute([$id_cliente]);
    $productos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $productos[] = [
            'clave' => $row['clave'],
            'descripcion' => $row['descripcion']
        ];
    }
    if (count($productos) > 0) {
        echo json_encode(['success' => true, 'productos' => $productos]);
    } else {
        echo json_encode(['success' => true, 'productos' => []]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
