<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

// Recibe datos JSON

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['id_minuta'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Datos incompletos',
        'debug' => [
            'input' => $input
        ]
    ]);
    exit;
}

$id_minuta = $input['id_minuta'];
$lugar = $input['lugar'] ?? '';
$fecha = $input['fecha'] ?? '';
$hora = $input['hora'] ?? '';
$idresponsable = $input['idresponsable'] ?? '';
$cliente = $input['cliente'] ?? '';
$asunto = $input['asunto'] ?? '';

$db = (new Database())->getConnection();
$crud = new Crud($db);
$crud->db_table = 'min_minutas';
$crud->id_key = 'id_minuta';
$crud->id_param = $id_minuta;

// Prepara los datos a actualizar (ajusta los nombres de columna si es necesario)
$updateData = [
    'lugar' => $lugar,
    'fecha' => $fecha,
    'hora_inicio' => $hora,
    'idresponsable' => $idresponsable,
    'idcliente' => $cliente, // Asegúrate que sea el ID, no el nombre
    'objetivo' => $asunto // O 'asunto' según tu base
];

// Debug: muestra los datos que se intentan actualizar
// (En producción, elimina este bloque)
if (isset($input['debug']) && $input['debug'] === true) {
    echo json_encode([
        'success' => false,
        'debug' => [
            'id_minuta' => $id_minuta,
            'updateData' => $updateData
        ]
    ]);
    exit;
}


// Debug: mostrar la consulta y los valores antes de ejecutar el update
$crud->data = $updateData;
$debug_sql = '';
if (property_exists($crud, 'query')) {
    $debug_sql = $crud->query;
}
$result = $crud->update($updateData);

// Obtener errorInfo si es posible
$errorInfo = '';
if (isset($db) && method_exists($db, 'errorInfo')) {
    $errorInfo = $db->errorInfo();
}

if ($result || (is_array($errorInfo) && isset($errorInfo[0]) && $errorInfo[0] === '00000')) {
    // Considera éxito si no hay error de SQL
    echo json_encode(['success' => true, 'debug_sql' => $debug_sql, 'debug_data' => $updateData]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'No se pudo actualizar la minuta',
        'debug' => [
            'id_minuta' => $id_minuta,
            'updateData' => $updateData,
            'crud_error' => method_exists($crud, 'getLastError') ? $crud->getLastError() : null,
            'db_error' => $errorInfo,
            'sql' => $debug_sql
        ]
    ]);
}
