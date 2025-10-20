<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$response = ['success' => false, 'message' => 'No se pudo actualizar el empleado, intente nuevamente.'];

if (
    isset($_POST['id']) &&
    isset($_POST['nombre']) &&
    isset($_POST['apellidos']) &&
    isset($_POST['correo']) &&
    isset($_POST['telefono']) &&
    isset($_POST['departamento']) &&
    isset($_POST['area'])
) {
    $id = $_POST['id'];
    $data = [
        'nombre' => $_POST['nombre'],
        'apellidos' => $_POST['apellidos'],
        'correo' => $_POST['correo'],
        'telefono' => $_POST['telefono'],
        'departamento' => $_POST['departamento'],
        'area' => $_POST['area']
    ];
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    $crud->db_table = 'sys_colaboradores';
    $crud->id_key = 'id_colab';
    $crud->id_param = $id;
    $crud->data = $data;
    error_log('EDITAR EMPLEADO - ID: ' . print_r($id, true));
    error_log('EDITAR EMPLEADO - DATA: ' . print_r($data, true));
    $result = $crud->update();
    error_log('EDITAR EMPLEADO - RESULT: ' . print_r($result, true));
    if ($result) {
        $response = [
            'success' => true,
            'message' => '¡Empleado actualizado con éxito!<br>Los datos han sido guardados correctamente.'
        ];
    }
}
echo json_encode($response);
