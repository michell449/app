<?php
// core/add-institucion.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$ubicacion_url = trim($_POST['ubicación_url'] ?? '');
$web = trim($_POST['web'] ?? '');

if ($nombre === '' || $tipo === '' || $correo === '') {
    echo json_encode(['success' => false, 'msg' => 'Faltan datos obligatorios']);
    exit;
}

$stmt = $db->prepare("INSERT INTO sys_instituciones (nombre, descripcion, tipo, direccion, telefono, correo, ubicación_url, web) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$ok = $stmt->execute([
    $nombre,
    $descripcion,
    $tipo,
    $direccion,
    $telefono,
    $correo,
    $ubicacion_url,
    $web
]);
echo json_encode(['success' => $ok]);
