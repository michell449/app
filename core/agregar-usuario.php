<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $creacion = $_POST['creacion'] ?? date('Y-m-d H:i:s');
    $modificacion = $_POST['modificacion'] ?? NULL;
    $status = $_POST['status'] ?? 1;
    $id_perfil = $_POST['id_perfil'] ?? 3;

    if ($nombre && $apellido && $email && $password) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'us_usuarios';
        $data = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => $password,
            'telefono' => $telefono,
            'creacion' => $creacion,
            'modificacion' => $modificacion,
            'status' => $status,
            'id_perfil' => $id_perfil
        ];
        $crud->data = $data;
        $result = $crud->create();

            if ($result) {
                // Obtener el id_usuario recién insertado
                $id_usuario = $conn->lastInsertId();
                // Insertar en sys_colaboradores
                $crudColab = new Crud($conn);
                $crudColab->db_table = 'sys_colaboradores';
                $dataColab = [
                    'nombre' => $nombre,
                    'apellidos' => $apellido,
                    'correo' => $email,
                    'telefono' => $telefono,
                    'departamento' => '', // Puedes modificar esto si tienes el dato
                    'area' => '', // Puedes modificar esto si tienes el dato
                    'id_equipo' => NULL, // Puedes modificar esto si tienes el dato
                    'id_usuario' => $id_usuario
                ];
                $crudColab->data = $dataColab;
                $resultColab = $crudColab->create();
                if ($resultColab) {
                    echo json_encode(['success' => true, 'message' => 'Usuario y colaborador agregados correctamente.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Usuario agregado, pero no se pudo agregar el colaborador.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo agregar el usuario.']);
            }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    }
    exit;
}