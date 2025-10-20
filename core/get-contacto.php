<?php
require_once 'class/db.php';
require_once 'class/crud.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

if (!isset($_GET['id_contacto'])) {
    echo json_encode(['success' => false, 'message' => 'ID de contacto no proporcionado.']);
    exit;
}

$id_contacto = intval($_GET['id_contacto']);
$db = new Database();
$conn = $db->getConnection();
$crud = new Crud($conn);

$sql = "SELECT id_contacto, cliente_empresa, nombre, telefono, whatsapp, correo, direccion, puesto, departamento, fecha_registro FROM sys_contactos WHERE id_contacto = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_contacto]);
$contacto = $stmt->fetch(PDO::FETCH_ASSOC);

if ($contacto) {
    echo json_encode(['success' => true, 'contacto' => $contacto]);
} else {
    echo json_encode(['success' => false, 'message' => 'Contacto no encontrado.']);
}
