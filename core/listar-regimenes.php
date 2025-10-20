<?php
// Devuelve los regimenes fiscales de la tabla cat_regimen como JSON
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';

$db = new Database();
$pdo = $db->getConnection();
try {
    $stmt = $pdo->query('SELECT clave, descripcion FROM cat_regimen ORDER BY clave ASC');
    $regimenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $regimenes]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
}
