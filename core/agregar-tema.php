<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../core/class/db.php';

$id_minuta = $_POST['id_minuta'] ?? null;
$titulo = $_POST['titulo'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

if ($id_minuta && $titulo && $descripcion) {
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->prepare("INSERT INTO min_temas (id_minuta, titulo, descripcion, observaciones) VALUES (?, ?, ?, ?)");
        $ok = $stmt->execute([$id_minuta, $titulo, $descripcion, $observaciones]);
        if ($ok) {
            echo json_encode(['success' => true, 'id' => $conn->lastInsertId()]);
        } else {
            $errorInfo = $stmt->errorInfo();
            echo json_encode(['success' => false, 'error' => $errorInfo[2]]);
        }
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}
?>