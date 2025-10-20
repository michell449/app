<?php
require_once __DIR__ . '/class/db.php';

$ruta = $_GET['ruta'] ?? '';
$nombre = $_GET['nombre'] ?? 'archivo';

if (empty($ruta)) {
    http_response_code(400);
    echo 'Ruta de archivo no especificada';
    exit;
}

// Construir la ruta completa del archivo
$ruta_completa = __DIR__ . '/..' . $ruta;

// Verificar que el archivo existe
if (!file_exists($ruta_completa)) {
    http_response_code(404);
    echo 'Archivo no encontrado';
    exit;
}

// Verificar que la ruta está dentro del directorio uploads (seguridad)
$uploads_dir = realpath(__DIR__ . '/../uploads/');
$archivo_real = realpath($ruta_completa);

if (strpos($archivo_real, $uploads_dir) !== 0) {
    http_response_code(403);
    echo 'Acceso denegado';
    exit;
}

// Obtener información del archivo
$tipo_mime = mime_content_type($ruta_completa);
$tamano = filesize($ruta_completa);

// Configurar headers para descarga
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($nombre) . '"');
header('Content-Length: ' . $tamano);
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Leer y enviar el archivo
readfile($ruta_completa);
exit;
?>