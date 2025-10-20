<?php
// core/add-comision.php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'msg' => 'Método no permitido']);
    exit;
}

$cliente = isset($_POST['cliente']) ? trim($_POST['cliente']) : '';
$comisionista = isset($_POST['comisionista']) ? trim($_POST['comisionista']) : '';
$porcentaje = isset($_POST['porcentaje']) ? trim($_POST['porcentaje']) : '';

if ($cliente === '' || $comisionista === '' || $porcentaje === '') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'msg' => 'Todos los campos son obligatorios']);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$crud = new crud($db);

$crud->db_table = 'com_cliente';
$crud->data = [
    'id_cliente' => $cliente,
    'id_comision' => $comisionista,
    'porcentaje' => $porcentaje
];

try {
    $result = $crud->create();
    if ($result) {
        echo json_encode(['status' => 'success', 'msg' => 'Comisión agregada correctamente']);
    } else {
        throw new Exception('No se pudo guardar la comisión');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}
?>
