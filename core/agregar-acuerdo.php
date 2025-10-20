<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../core/class/db.php';


$id_minuta = $_POST['id_minuta'] ?? null;
$id_tema = $_POST['id_tema'] ?? null;
$descripcion = $_POST['descripcion'] ?? '';
$idresponsable = $_POST['idresponsable'] ?? null;
$fecha_limite = $_POST['fecha_limite'] ?? null;
$estado = $_POST['estado'] ?? 'Pendiente';

if ($id_minuta && $id_tema && $descripcion) {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare("INSERT INTO min_acuerdos (id_minuta, id_tema, descripcion, idresponsable, fecha_limite, estado) VALUES (?, ?, ?, ?, ?, ?)");
    $ok = $stmt->execute([$id_minuta, $id_tema, $descripcion, $idresponsable, $fecha_limite, $estado]);
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