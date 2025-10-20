<?php
// Papelera: listar archivos eliminados y restaurar
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$action = $_GET['action'] ?? '';

if ($action === 'listar') {
    $sql = "SELECT * FROM arch_archivos WHERE status = 'eliminado' ORDER BY id_archivo DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'archivos' => $archivos]);
    exit;
}

if ($action === 'restaurar') {
    $id_archivo = trim($_POST['id_archivo'] ?? '');
    if ($id_archivo === '') {
        echo json_encode(['success' => false, 'msg' => 'Falta el ID']);
        exit;
    }
    $stmt = $db->prepare("UPDATE arch_archivos SET status='activo' WHERE id_archivo=?");
    $ok = $stmt->execute([$id_archivo]);
    echo json_encode(['success' => $ok]);
    exit;
}

echo json_encode(['success' => false, 'msg' => 'Acción no válida']);
