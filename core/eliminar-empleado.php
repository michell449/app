<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$response = ['success' => false, 'message' => 'No se pudo eliminar el empleado, intente nuevamente.'];

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    $crud->db_table = 'sys_colaboradores';
    $crud->id_key = 'id_colab';
    $crud->id_param = $id;
    $result = $crud->delete();
    if ($result) {
        $response = [
            'success' => true,
            'message' => 'Empleado eliminado correctamente.'
        ];
    }
}
echo json_encode($response);
