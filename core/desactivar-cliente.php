<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
$db = new Database();
$conn = $db->getConnection();
$crud = new crud($conn);
$crud->db_table = 'sys_clientes';

$id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;

if ($id_cliente > 0) {
    $crud->id_param = $id_cliente;
    $crud->id_key = 'id_cliente';
    $crud->data = ['activo' => 0];
    $result = $crud->update();
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Cliente desactivado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo desactivar el cliente']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de cliente inválido']);
}
?>