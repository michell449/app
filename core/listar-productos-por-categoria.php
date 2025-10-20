<?php
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');
$grupo = isset($_GET['grupo']) ? $_GET['grupo'] : '';
if ($grupo === '') {
    echo json_encode(['success' => false, 'error' => 'Categoría no válida']);
    exit;
}
try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare('SELECT clave, descripcion FROM cat_productos WHERE grupo = ? ORDER BY descripcion');
    $stmt->execute([$grupo]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'productos' => $productos]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    
}

?>