<?php
require_once 'class/db.php';
require_once 'class/crud.php';
header('Content-Type: application/json');

if (empty($_POST['id_contacto'])) {
    echo json_encode(['success' => false, 'message' => 'ID de contacto requerido.']);
    exit;
}

$id_contacto = intval($_POST['id_contacto']);
$cliente_empresa = $_POST['cliente_empresa'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$whatsapp = $_POST['whatsapp'] ?? '';
$correo = $_POST['correo'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$puesto = $_POST['puesto'] ?? '';
$departamento = $_POST['departamento'] ?? '';

$db = new Database();
$conn = $db->getConnection();
$activo = isset($_POST['activo']) ? intval($_POST['activo']) : null;
if ($activo !== null) {
    $sql = "UPDATE sys_contactos SET cliente_empresa=?, nombre=?, telefono=?, whatsapp=?, correo=?, direccion=?, puesto=?, departamento=?, activo=? WHERE id_contacto=?";
    $params = [
        $cliente_empresa,
        $nombre,
        $telefono,
        $whatsapp,
        $correo,
        $direccion,
        $puesto,
        $departamento,
        $activo,
        $id_contacto
    ];
} else {
    $sql = "UPDATE sys_contactos SET cliente_empresa=?, nombre=?, telefono=?, whatsapp=?, correo=?, direccion=?, puesto=?, departamento=? WHERE id_contacto=?";
    $params = [
        $cliente_empresa,
        $nombre,
        $telefono,
        $whatsapp,
        $correo,
        $direccion,
        $puesto,
        $departamento,
        $id_contacto
    ];
}
$stmt = $conn->prepare($sql);
$ok = $stmt->execute($params);

if ($ok) {
    echo json_encode(['success' => true, 'message' => 'Contacto actualizado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al actualizar el contacto.']);
}
