<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
header('Content-Type: application/json');

$id = isset($_POST['id_directorio']) ? intval($_POST['id_directorio']) : 0;
if ($id < 1) {
    echo json_encode(['success' => false, 'error' => 'ID de registro inválido']);
    exit;
}
$fields = [
    'empresa', 'clasificacion', 'contacto', 'telefono', 'puesto', 'referencia'
];
$data = [];
foreach ($fields as $field) {
    if (isset($_POST[$field])) {
        $data[$field] = $_POST[$field];
    }
}
// Solo actualizar notas si se envía explícitamente
if (isset($_POST['notas'])) {
    $data['notas'] = $_POST['notas'];
}
$db = new Database();
$conn = $db->getConnection();
$crud = new Crud($conn);
$crud->db_table = 'directorio_empresarial';
$crud->id_key = 'id_directorio';
$crud->id_param = $id;
$crud->data = $data;
if ($crud->update()) {
    echo json_encode(['success' => true, 'message' => 'Registro actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el registro']);
}
