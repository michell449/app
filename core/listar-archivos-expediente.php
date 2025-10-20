<?php
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');

$id_expediente = isset($_GET['id_expediente']) ? intval($_GET['id_expediente']) : 0;
if (!$id_expediente) {
    echo json_encode(['success' => false, 'msg' => 'Expediente no especificado']);
    exit;
}

$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'msg' => 'Error de conexión']);
    exit;
}

// Obtener archivos de la base de datos
$sql = "SELECT * FROM exp_documentos WHERE id_expediente = ? AND en_papelera = 0 ORDER BY fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_expediente]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener numero_expediente de la tabla de expedientes por separado
$numero_expediente = '';
try {
    $sql2 = "SELECT numero_expediente FROM sis_rje_exp_expedientes WHERE id_expediente = ? LIMIT 1";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute([$id_expediente]);
    $expediente_info = $stmt2->fetch(PDO::FETCH_ASSOC);
    $numero_expediente = $expediente_info ? $expediente_info['numero_expediente'] : '';
} catch (Exception $e) {
    // Si falla, usar solo el id_expediente
    $numero_expediente = "Exp-" . $id_expediente;
}

$debug_info = [
    'id_expediente' => $id_expediente,
    'numero_expediente' => $numero_expediente,
    'total_registros' => count($resultados),
    'registros' => $resultados
];

$archivos = [];

foreach ($resultados as $row) {
    // Construir la ruta del archivo usando id_expediente
    $nombre_archivo_fisico = isset($row['nombre_archivo']) ? $row['nombre_archivo'] : '';
    
    // Construir ruta física del archivo
    $ruta_relativa = 'uploads/Expedientes/' . $id_expediente . '/' . $nombre_archivo_fisico;
    $ruta_absoluta = __DIR__ . '/../' . $ruta_relativa;
    
    // Agregar archivo 
    $archivos[] = [
        'id_doc' => isset($row['id_doc']) ? $row['id_doc'] : null,
        'tipo_archivo' => strtolower(trim($row['tipo_archivo'])),
        'documento' => isset($row['documento']) ? $row['documento'] : $nombre_archivo_fisico,
        'nombre_archivo' => $nombre_archivo_fisico,
        'ruta_fisica' => $ruta_relativa,
        'ruta_absoluta' => $ruta_absoluta,
        'existe_archivo' => file_exists($ruta_absoluta),
        'fecha' => $row['fecha'],
        'uuid' => isset($row['uuid']) ? $row['uuid'] : '',
        'numero_expediente' => $numero_expediente,
        'id_expediente' => $id_expediente
    ];
}

// Agrupar por tipo_archivo
$categorias = [
    'caratula' => [],
    'acuerdo' => [],
    'promocion' => [],
    'constancia' => [],
    'juicio' => [],
    'audiencia' => []
];

foreach ($archivos as $doc) {
    $tipo = $doc['tipo_archivo'];
    if (isset($categorias[$tipo])) {
        $categorias[$tipo][] = $doc;
    }
}

// Contar por categoría
$counts = [];
foreach ($categorias as $cat => $arr) {
    $counts[$cat] = count($arr);
}

// Información adicional de debug
$archivos_existentes = 0;
$archivos_faltantes = [];
foreach ($archivos as $archivo) {
    if ($archivo['existe_archivo']) {
        $archivos_existentes++;
    } else {
        $archivos_faltantes[] = [
            'nombre' => $archivo['documento'],
            'ruta_esperada' => $archivo['ruta_absoluta'],
            'nombre_archivo' => $archivo['nombre_archivo']
        ];
    }
}

$response = [
    'success' => true,
    'id_expediente' => $id_expediente,
    'numero_expediente' => $numero_expediente,
    'categorias' => $categorias,
    'counts' => $counts,
    'debug' => $debug_info,
    'total_archivos' => count($archivos),
    'debug_archivos' => $archivos,
    'archivos_existentes' => $archivos_existentes,
    'archivos_faltantes' => $archivos_faltantes,
    'carpeta_expediente' => __DIR__ . '/../uploads/Expedientes/' . $id_expediente
];

echo json_encode($response);
