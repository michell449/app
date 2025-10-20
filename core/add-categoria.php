<?php
// core/add-categoria.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
if ($nombre === '' || $descripcion === '') {
    echo json_encode(['success' => false, 'msg' => 'Faltan datos']);
    exit;
}
$stmt = $db->prepare("INSERT INTO arch_categorias (nombre, descripcion) VALUES (?, ?)");
$ok = $stmt->execute([$nombre, $descripcion]);
echo json_encode(['success' => $ok]);
