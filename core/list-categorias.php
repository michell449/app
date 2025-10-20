<?php
// core/list-categorias.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$sql = "SELECT id_categoria, nombre, descripcion FROM arch_categorias ORDER BY id_categoria ASC";
$stmt = $db->prepare($sql);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'categorias' => $categorias]);
