<?php
// core/delete-institucion.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'msg' => 'ID inválido']);
    exit;
}
$stmt = $db->prepare("DELETE FROM sys_instituciones WHERE id_institucion = ?");
$ok = $stmt->execute([$id]);
echo json_encode(['success' => $ok]);
