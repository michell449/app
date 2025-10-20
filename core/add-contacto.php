<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
$db = new Database();
$conn = $db->getConnection();
$crud = new crud($conn);
$crud->db_table = 'sys_contactos';

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$whatsapp = isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : '';
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$cliente_empresa = isset($_POST['cliente_empresa']) ? intval($_POST['cliente_empresa']) : 0;
$puesto = isset($_POST['puesto']) ? trim($_POST['puesto']) : '';
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : '';
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';

if ($nombre && $telefono && $correo && $cliente_empresa) {
	$crud->data = [
		'nombre' => $nombre,
		'telefono' => $telefono,
		'whatsapp' => $whatsapp,
		'correo' => $correo,
		'cliente_empresa' => $cliente_empresa,
		'puesto' => $puesto,
		'departamento' => $departamento,
		'direccion' => $direccion,
		'activo' => 1,
		'fecha_registro' => date('Y-m-d H:i:s')
	];
	$result = $crud->create();
	if ($result) {
		echo json_encode(['success' => true, 'message' => 'Contacto agregado correctamente']);
	} else {
		echo json_encode(['success' => false, 'message' => 'Error al agregar el contacto']);
	}
} else {
	echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
