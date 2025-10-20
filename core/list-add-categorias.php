<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$stmt = $db->query('SELECT id_categoria, nombre FROM arch_categorias ORDER BY nombre ASC');
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'categorias' => $categorias]);
