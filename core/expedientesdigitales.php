<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';


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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No se pudo conectar a la base de datos.']);
    exit;
}

// Recoge y valida los datos del formulario
$numero_expediente = $_POST['exp'] ?? null; // Número de expediente manual
$materia = $_POST['materia'] ?? null;
$lugar = $_POST['lugar'] ?? null;
$tipo_organo = $_POST['tipo_organo'] ?? null;
$organo_jur = $_POST['organo_jur'] ?? null;
$tipo_asunto = $_POST['tipo_asunto'] ?? null;
$fecha_creacion = $_POST['fecha_registro'] ?? null;
$parte = $_POST['parte'] ?? null;
// Guardar el valor real de expediente_unico (NEUN)
$expediente_unico = isset($_POST['expediente_unico']) ? trim($_POST['expediente_unico']) : null;
$asunto = $_POST['asunto'] ?? null;
$cliente = $_POST['cliente'] ?? null;
$demandante = $_POST['demandante'] ?? null;

// Validar que organo_jur exista en exp_list_org_jur
if ($organo_jur) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM exp_list_org_juris WHERE clave = ?');
    $stmt->execute([$organo_jur]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'El órgano jurisdiccional seleccionado no existe.']);
        exit;
    }
}

// Validar que tipo_organo exista en exp_tipos_org_juris
if ($tipo_organo) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM exp_tipos_org_juris WHERE clave = ?');
    $stmt->execute([$tipo_organo]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'El tipo de órgano seleccionado no existe.']);
        exit;
    }
}

// Validar que el cliente exista en sys_clientes
if ($cliente) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM sys_clientes WHERE id_cliente = ?');
    $stmt->execute([$cliente]);
    if ($stmt->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'El cliente seleccionado no existe.']);
        exit;
    }
}

// Validación básica

if (!$materia || !$lugar || !$organo_jur || !$tipo_asunto || !$fecha_creacion || !$parte || !$asunto || !$cliente || !$demandante) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios.']);
    exit;
}

// Generar expediente con numero_expediente manual o automático
try {
    // Si se proporciona un número de expediente manual, usarlo; si no, se generará automáticamente
    $stmt = $conn->prepare("INSERT INTO exp_expedientes (numero_expediente, expediente_unico, materia, lugar, parte, tipo_organo, organo_jur, tipo_asunto, asunto, fecha_creacion, cliente, demandante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$numero_expediente, $expediente_unico, $materia, $lugar, $parte, $tipo_organo, $organo_jur, $tipo_asunto, $asunto, $fecha_creacion, $cliente, $demandante]);
    $nuevo_id = $conn->lastInsertId();
    
    // Si no se proporcionó un número de expediente manual, generar uno automático
    if (empty($numero_expediente)) {
        $anio = date('Y', strtotime($fecha_creacion));
        $numero_expediente_auto = $nuevo_id . '/' . $anio;
        // Actualizar el campo numero_expediente con el formato automático
        $stmt = $conn->prepare("UPDATE exp_expedientes SET numero_expediente = ? WHERE id_expediente = ?");
        $stmt->execute([$numero_expediente_auto, $nuevo_id]);
    }
    
    echo json_encode(['success' => true, 'message' => 'Expediente guardado correctamente.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()]);
}
