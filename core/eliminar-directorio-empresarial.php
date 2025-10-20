<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
header('Content-Type: application/json');

$id = isset($_POST['id_directorio']) ? intval($_POST['id_directorio']) : 0;
if ($id < 1) {
    echo json_encode(['success' => false, 'error' => 'ID de registro inválido']);
    exit;
}
$db = new Database();
$conn = $db->getConnection();
$crud = new Crud($conn);
$crud->db_table = 'directorio_empresarial';
$crud->id_key = 'id_directorio';
$crud->id_param = $id;
if ($crud->delete()) {
    echo json_encode(['success' => true, 'message' => 'Registro eliminado correctamente']);
} else {
    echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el registro']);
}
