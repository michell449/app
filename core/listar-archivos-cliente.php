<?php
header('Content-Type: application/json');
require_once 'class/db.php';

try {
    $cliente_id = $_GET['cliente_id'] ?? 0;
    $categoria = $_GET['categoria'] ?? '';
    $tipo_documento = $_GET['tipo_documento'] ?? '';
    
    if (empty($cliente_id) || empty($categoria)) {
        throw new Exception('Parámetros requeridos faltantes');
    }
    
    $db = new Database();
    $conn = $db->getConnection();
    
    // Construir query base
    $sql = "SELECT id_documento, nombre_original, nombre_archivo, ruta_archivo, 
                   tamaño_kb, tipo_mime, fecha_subida, descripcion
            FROM ctrl_documentos_clientes 
            WHERE id_cliente = ? AND categoria = ? AND activo = 1";
    
    $params = [$cliente_id, $categoria];
    
    // Agregar filtro por tipo si se especifica
    if (!empty($tipo_documento)) {
        $sql .= " AND tipo_documento = ?";
        $params[] = $tipo_documento;
    }
    
    $sql .= " ORDER BY fecha_subida DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear datos para mostrar
    $archivos = [];
    foreach ($documentos as $doc) {
        $archivos[] = [
            'id' => $doc['id_documento'],
            'nombre_original' => $doc['nombre_original'],
            'nombre_archivo' => $doc['nombre_archivo'],
            'ruta_archivo' => $doc['ruta_archivo'],
            'tamaño_kb' => $doc['tamaño_kb'],
            'tamaño_formato' => formatearTamaño($doc['tamaño_kb']),
            'tipo_mime' => $doc['tipo_mime'],
            'fecha_subida' => $doc['fecha_subida'],
            'fecha_formato' => date('d/m/Y H:i', strtotime($doc['fecha_subida'])),
            'descripcion' => $doc['descripcion']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'archivos' => $archivos,
        'total' => count($archivos)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function formatearTamaño($kb) {
    if ($kb < 1024) {
        return $kb . ' KB';
    } else {
        $mb = round($kb / 1024, 2);
        return $mb . ' MB';
    }
}
?>