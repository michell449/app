<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $departamento = $_POST['departamento'] ?? '';
    $area = $_POST['area'] ?? '';

    if ($nombre && $apellidos && $correo) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'sys_colaboradores';
        $data = [
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'correo' => $correo,
            'telefono' => $telefono,
            'departamento' => $departamento,
            'area' => $area
        ];
    $crud->data = $data;
    $result = $crud->create();
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Empleado agregado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo agregar el empleado.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    }
    exit;
}
