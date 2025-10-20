<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
header('Content-Type: application/json');

$fields = [
    'id_cliente', 'empresa', 'clasificacion', 'contacto', 'telefono', 'puesto', 'referencia', 'notas'
];
$data = [];
foreach ($fields as $field) {
    $data[$field] = $_POST[$field] ?? '';
}
if (empty($data['id_cliente']) || empty($data['empresa'])) {
    echo json_encode(['success' => false, 'error' => 'Cliente y empresa son obligatorios']);
    exit;
}
$db = new Database();
$conn = $db->getConnection();
$crud = new Crud($conn);
$crud->db_table = 'directorio_empresarial';
$crud->data = $data;
if ($crud->create()) {
    echo json_encode(['success' => true, 'message' => 'Registro agregado correctamente']);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo agregar el registro']);
}
