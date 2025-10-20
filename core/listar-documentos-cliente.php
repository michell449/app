<?php
/**
 * Lista los documentos del cliente autenticado
 * Devuelve datos en formato JSON para el portal de clientes
 */

require_once __DIR__ . '/auth-cliente.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json');

// Verificar que el cliente esté autenticado
if (!AuthCliente::esCliente()) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

$cliente_id = AuthCliente::obtenerClienteId();
$cliente_datos = AuthCliente::obtenerDatosCliente();

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Obtener documentos por categoría
    $documentos = [
        'fiscales' => obtenerDocumentosPorCategoria($conn, $cliente_id, 'fiscales'),
        'legales' => obtenerDocumentosPorCategoria($conn, $cliente_id, 'legales'),
        'bancarios' => obtenerEstadosCuenta($conn, $cliente_id),
        'corporativos' => obtenerRecursosCorporativos($conn, $cliente_id)
    ];
    
    // Resumen de datos del cliente
    $resumen = [
        'cliente' => $cliente_datos,
        'estadisticas' => [
            'total_documentos' => contarTotalDocumentos($documentos),
            'ultima_actualizacion' => obtenerUltimaActualizacion($conn, $cliente_id)
        ]
    ];
    
    echo json_encode([
        'success' => true,
        'cliente' => $resumen,
        'documentos' => $documentos
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    error_log("Error en listar-documentos-cliente: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error interno del servidor']);
}

/**
 * Obtiene documentos por categoría
 */
function obtenerDocumentosPorCategoria($conn, $cliente_id, $categoria) {
    $sql = "SELECT 
                tipo_documento,
                COUNT(*) as cantidad_archivos,
                MAX(fecha_actualizacion) as ultima_actualizacion,
                GROUP_CONCAT(CONCAT(id_documento, '|', nombre_archivo, '|', ruta_archivo, '|', fecha_subida, '|', COALESCE(tamaño_kb, 0)) SEPARATOR '###') as archivos
            FROM ctrl_documentos_clientes 
            WHERE id_cliente = ? AND categoria = ? AND activo = 1
            GROUP BY tipo_documento
            ORDER BY tipo_documento";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id, $categoria]);
    
    $documentos = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $archivos = [];
        if (!empty($row['archivos'])) {
            $archivos_raw = explode('###', $row['archivos']);
            foreach ($archivos_raw as $archivo_data) {
                $parts = explode('|', $archivo_data);
                if (count($parts) >= 5) {
                    $tamañoKB = (float)$parts[4];
                    $archivos[] = [
                        'id' => $parts[0],
                        'nombre' => $parts[1],
                        'ruta' => $parts[2],
                        'fecha_subida' => $parts[3],
                        'tamaño_kb' => $tamañoKB,
                        'tamaño_formato' => formatearTamañoKB($tamañoKB)
                    ];
                }
            }
        }
        
        $documentos[] = [
            'tipo' => $row['tipo_documento'],
            'cantidad' => (int)$row['cantidad_archivos'],
            'ultima_actualizacion' => $row['ultima_actualizacion'],
            'archivos' => $archivos
        ];
    }
    
    return $documentos;
}

/**
 * Obtiene estados de cuenta bancarios
 */
function obtenerEstadosCuenta($conn, $cliente_id) {
    $sql = "SELECT 
                nombre_original as nombre_archivo,
                ruta_archivo,
                fecha_subida,
                id_documento,
                descripcion
            FROM ctrl_documentos_clientes 
            WHERE id_cliente = ? AND categoria = 'bancarios' AND tipo_documento = 'estado_cuenta' AND activo = 1
            ORDER BY fecha_subida DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id]);
    
    $estados = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Extraer información del banco y período de la descripción
        $descripcion = $row['descripcion'] ?? '';
        $banco = '';
        $periodo = '';
        $numero_cuenta = '';
        
        if (preg_match('/Banco: ([^|]+)/', $descripcion, $matches)) {
            $banco = trim($matches[1]);
        }
        if (preg_match('/Período: ([^|]+)/', $descripcion, $matches)) {
            $periodo = trim($matches[1]);
        }
        if (preg_match('/Cuenta: ([^|]+)/', $descripcion, $matches)) {
            $numero_cuenta = trim($matches[1]);
        }
        
        $estados[] = [
            'banco' => $banco,
            'numero_cuenta' => $numero_cuenta,
            'periodo' => $periodo,
            'nombre_archivo' => $row['nombre_archivo'],
            'ruta_archivo' => $row['ruta_archivo'],
            'fecha_subida' => $row['fecha_subida'],
            'id_estado_cuenta' => $row['id_documento']
        ];
    }
    
    return $estados;
}

/**
 * Obtiene recursos corporativos (logos, etc.)
 */
function obtenerRecursosCorporativos($conn, $cliente_id) {
    $sql = "SELECT 
                CASE 
                    WHEN tipo_documento = 'logo_empresa' THEN 'principal'
                    ELSE tipo_documento 
                END as tipo,
                nombre_original as nombre_archivo,
                ruta_archivo,
                fecha_subida,
                id_documento as id,
                descripcion
            FROM ctrl_documentos_clientes 
            WHERE id_cliente = ? AND categoria = 'corporativos' AND activo = 1
            ORDER BY fecha_subida DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Cuenta total de documentos
 */
function contarTotalDocumentos($documentos) {
    $total = 0;
    foreach ($documentos['fiscales'] as $doc) {
        $total += $doc['cantidad'];
    }
    foreach ($documentos['legales'] as $doc) {
        $total += $doc['cantidad'];
    }
    $total += count($documentos['bancarios']);
    $total += count($documentos['corporativos']);
    
    return $total;
}

/**
 * Obtiene la fecha de la última actualización
 */
function obtenerUltimaActualizacion($conn, $cliente_id) {
    $sql = "SELECT MAX(fecha_actualizacion) as ultima_fecha 
            FROM ctrl_documentos_clientes 
            WHERE id_cliente = ? AND activo = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cliente_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['ultima_fecha'] ?? null;
}

/**
 * Formatea el tamaño de archivo en formato legible (desde KB)
 */
function formatearTamañoKB($kb) {
    if ($kb == 0) return 'N/A';
    
    if ($kb < 1024) {
        return round($kb, 2) . ' KB';
    } else if ($kb < 1024 * 1024) {
        return round($kb / 1024, 2) . ' MB';
    } else {
        return round($kb / (1024 * 1024), 2) . ' GB';
    }
}
?>