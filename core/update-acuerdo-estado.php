
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$input = $_POST;
$id_acuerdo = $input['id_acuerdo'] ?? null;
$estado = $input['estado'] ?? null;

if (!$id_acuerdo || !$estado) {
	echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
	exit;
}

$db = (new Database())->getConnection();
$crud = new Crud($db);
$crud->db_table = 'min_acuerdos';
$crud->id_key = 'id';
$crud->id_param = $id_acuerdo;
$crud->data = [ 'estado' => $estado ];

$result = $crud->update($crud->data);

if ($result) {
	echo json_encode(['success' => true]);
} else {
	echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el estado']);
}

