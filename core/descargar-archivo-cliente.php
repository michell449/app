<?php
/**
 * Descarga segura de archivos para clientes autenticados
 * Valida que el archivo pertenezca al cliente logueado antes de permitir la descarga
 */

require_once __DIR__ . '/auth-cliente.php';
require_once __DIR__ . '/class/db.php';

// Verificar que el cliente esté autenticado
if (!AuthCliente::esCliente()) {
    http_response_code(403);
    die('Acceso denegado');
}

// Verificar que se envió la ruta del archivo
if (!isset($_POST['ruta_archivo']) || empty($_POST['ruta_archivo'])) {
    http_response_code(400);
    die('Archivo no especificado');
}

$ruta_archivo = $_POST['ruta_archivo'];
$vista_previa = isset($_POST['vista_previa']) && $_POST['vista_previa'] === '1';
$cliente_id = AuthCliente::obtenerClienteId();

try {
    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->getConnection();
    
    // Verificar que el archivo pertenece al cliente autenticado (tabla unificada)
    $sql = "SELECT nombre_archivo, ruta_archivo, tipo_documento 
            FROM ctrl_documentos_clientes 
            WHERE ruta_archivo = ? AND id_cliente = ? AND activo = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$ruta_archivo, $cliente_id]);
    $archivo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$archivo) {
        http_response_code(403);
        die('Archivo no encontrado o acceso denegado');
    }
    
    // Construir la ruta completa del archivo
    $ruta_completa = __DIR__ . '/../' . $ruta_archivo;
    
    // Verificar que el archivo existe físicamente
    if (!file_exists($ruta_completa)) {
        http_response_code(404);
        die('Archivo no encontrado en el servidor');
    }
    
    // Registrar la descarga en bitácora
    registrarDescarga($conn, $cliente_id, $archivo['nombre_archivo']);
    
    // Configurar headers según si es descarga o vista previa
    $nombre_archivo = $archivo['nombre_archivo'];
    $tipo_mime = obtenerTipoMime($ruta_completa);
    
    header('Content-Type: ' . $tipo_mime);
    
    if ($vista_previa) {
        // Para vista previa (abrir en navegador)
        header('Content-Disposition: inline; filename="' . $nombre_archivo . '"');
    } else {
        // Para descarga
        header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
    }
    
    header('Content-Length: ' . filesize($ruta_completa));
    header('Cache-Control: no-cache');
    
    // Enviar el archivo
    readfile($ruta_completa);
    
} catch (Exception $e) {
    error_log("Error en descarga de archivo: " . $e->getMessage());
    http_response_code(500);
    die('Error interno del servidor');
}

/**
 * Registra la descarga en la bitácora
 */
function registrarDescarga($conn, $cliente_id, $nombre_archivo) {
    try {
        $sql = "INSERT INTO us_bitacora (id_usuario, fecha, notas, accion) 
                VALUES (?, NOW(), ?, 'Descarga de documento')";
        $stmt = $conn->prepare($sql);
        $notas = "Cliente descargó: " . $nombre_archivo;
        $stmt->execute([$cliente_id, $notas]);
    } catch (Exception $e) {
        error_log("Error al registrar descarga: " . $e->getMessage());
    }
}

/**
 * Obtiene el tipo MIME del archivo
 */
function obtenerTipoMime($ruta_archivo) {
    $extension = strtolower(pathinfo($ruta_archivo, PATHINFO_EXTENSION));
    
    $tipos_mime = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed'
    ];
    
    return $tipos_mime[$extension] ?? 'application/octet-stream';
}
?>