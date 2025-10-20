<?php
require_once 'class/db.php';
header('Content-Type: application/json');

if (empty($_POST['id_contacto'])) {
    echo json_encode(['success' => false, 'message' => 'ID de contacto requerido.']);
    exit;
}

$id_contacto = intval($_POST['id_contacto']);
$db = new Database();
$conn = $db->getConnection();

$sql = "UPDATE sys_contactos SET activo=0 WHERE id_contacto=?";
$stmt = $conn->prepare($sql);
$ok = $stmt->execute([$id_contacto]);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Contacto desactivado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al desactivar el contacto.']);
}
