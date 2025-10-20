<?php
// core/update-institucion.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();

$id = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$tipo = trim($_POST['tipo'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$ubicacion_url = trim($_POST['ubicación_url'] ?? '');
$web = trim($_POST['web'] ?? '');

if ($id <= 0 || $nombre === '' || $tipo === '' || $correo === '') {
    echo json_encode(['success' => false, 'msg' => 'Datos inválidos']);
    exit;
}

$stmt = $db->prepare("UPDATE sys_instituciones SET nombre = ?, descripcion = ?, tipo = ?, direccion = ?, telefono = ?, correo = ?, ubicación_url = ?, web = ? WHERE id_institucion = ?");
$ok = $stmt->execute([
    $nombre,
    $descripcion,
    $tipo,
    $direccion,
    $telefono,
    $correo,
    $ubicacion_url,
    $web,
    $id
]);
echo json_encode(['success' => $ok]);
