<?php
// Devuelve el breadcrumb de una carpeta en papelera
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
$breadcrumb = [];
while ($id) {
    $stmt = $db->prepare("SELECT id, nombre, idpadre FROM archivos_directorios WHERE id=? AND en_papelera=1");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        array_unshift($breadcrumb, ['id' => $row['id'], 'nombre' => $row['nombre']]);
        $id = $row['idpadre'];
    } else {
        break;
    }
}
echo json_encode($breadcrumb);
