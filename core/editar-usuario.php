<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$response = ['success' => false, 'message' => 'No se pudo actualizar el usuario, intente nuevamente.'];

if (
    isset($_POST['id']) &&
    isset($_POST['nombre']) &&
    isset($_POST['apellido']) &&
    isset($_POST['correo']) &&
    isset($_POST['telefono']) &&
    isset($_POST['status']) &&
    (isset($_POST['perfil']) || isset($_POST['id_perfil']))
) {
    $id = $_POST['id'];
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : $_POST['id_perfil'];
    $data = [
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'email' => $_POST['correo'],
        'telefono' => $_POST['telefono'],
        'status' => $_POST['status'],
        'id_perfil' => $perfil
    ];
    $modificacion = date('Y-m-d H:i:s');
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    $crud->db_table = 'us_usuarios';
    $crud->id_key = 'id_usuario';
    $crud->id_param = $id;
    $crud->data = [
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'email' => $_POST['correo'],
        'telefono' => $_POST['telefono'],
        'status' => $_POST['status'],
        'id_perfil' => $perfil,
        'modificacion' => $modificacion
    ];
    error_log('EDITAR USUARIO - ID: ' . print_r($id, true));
    error_log('EDITAR USUARIO - DATA: ' . print_r($data, true));
    $result = $crud->update();
    error_log('EDITAR USUARIO - RESULT: ' . print_r($result, true));
    if ($result) {
        $response = [
            'success' => true,
            'message' => '¡Usuario actualizado con éxito!<br>Los datos han sido guardados correctamente.'
        ];
    }
}
echo json_encode($response);
