<?php
header('Content-Type: application/json');
require_once '../config.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    $crud = new crud($db);
    $crud->db_table = 'cat_productos_clientes';
    $crud->id_key = 'id';
    $method = $_SERVER['REQUEST_METHOD'];
    $response = ['success' => false];

    if ($method === 'GET') {
        // Listar todos los productos
        $count = $crud->read();
        $response = [
            'success' => true,
            'data' => $crud->data,
            'count' => $count
        ];
    } elseif ($method === 'POST') {
        // Agregar producto
        $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
        $clave = isset($_POST['clave']) ? trim($_POST['clave']) : '';
        if ($id_cliente > 0 && $clave) {
            $crud->data = [
                'id_cliente' => $id_cliente,
                'clave' => $clave
            ];
            $ok = $crud->create();
            $response = ['success' => $ok, 'message' => $ok ? 'Producto agregado' : 'Error al agregar'];
        } else {
            $response = ['success' => false, 'error' => 'Datos insuficientes'];
        }
    } elseif ($method === 'PUT') {
        // Editar producto
        parse_str(file_get_contents('php://input'), $_PUT);
        $id = isset($_PUT['id']) ? intval($_PUT['id']) : 0;
        $clave = isset($_PUT['clave']) ? trim($_PUT['clave']) : '';
        if ($id > 0 && $clave) {
            $crud->id_param = $id;
            $crud->data = [
                'clave' => $clave
            ];
            $ok = $crud->update();
            $response = ['success' => $ok, 'message' => $ok ? 'Producto actualizado' : 'Error al actualizar'];
        } else {
            $response = ['success' => false, 'error' => 'Datos insuficientes'];
        }
    } elseif ($method === 'DELETE') {
        // Eliminar producto
        parse_str(file_get_contents('php://input'), $_DELETE);
        $id = isset($_DELETE['id']) ? intval($_DELETE['id']) : 0;
        if ($id > 0) {
            $crud->id_param = $id;
            $ok = $crud->delete();
            $response = ['success' => $ok, 'message' => $ok ? 'Producto eliminado' : 'Error al eliminar'];
        } else {
            $response = ['success' => false, 'error' => 'ID no válido'];
        }
    }
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
