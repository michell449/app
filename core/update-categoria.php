<?php
// core/update-categoria.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
if ($id <= 0 || $nombre === '' || $descripcion === '') {
    echo json_encode(['success' => false, 'msg' => 'Datos inválidos']);
    exit;
}
$stmt = $db->prepare("UPDATE arch_categorias SET nombre = ?, descripcion = ? WHERE id_categoria = ?");
$ok = $stmt->execute([$nombre, $descripcion, $id]);
echo json_encode(['success' => $ok]);
