<?php
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');
try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->query('SELECT clave, grupo FROM cat_grupos ORDER BY grupo');
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'categorias' => $categorias]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>