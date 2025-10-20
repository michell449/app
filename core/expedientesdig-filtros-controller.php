<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        'error' => 'PHP Error',
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ]);
    exit;
});

set_exception_handler(function($exception) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Exception',
        'message' => $exception->getMessage()
    ]);
    exit;
});

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

function fetchAllPDO($conn, $query) {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$response = [
    'lugar' => fetchAllPDO($conn, 'SELECT clave, nombre FROM exp_list_estados'),
    'materia' => fetchAllPDO($conn, 'SELECT clave, nombre FROM exp_list_materia'),
    'tipos_asunto' => fetchAllPDO($conn, 'SELECT clave, nombre FROM exp_tipos_asunto'),
    'tipo_organo' => fetchAllPDO($conn, 'SELECT clave, nombre FROM exp_tipos_org_juris'),
    'organo_jur' => fetchAllPDO($conn, 'SELECT clave, nombre FROM exp_list_org_juris'),
    // Mostrar todos los clientes activos
    'clientes' => fetchAllPDO($conn, 'SELECT id_cliente, nombre_comercial FROM sys_clientes WHERE activo = 1'),
];

echo json_encode($response);
