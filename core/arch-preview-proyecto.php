<?php
require_once __DIR__ . '/class/db.php';

$ruta = $_GET['ruta'] ?? '';

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

// Obtener tipo MIME
$tipo_mime = mime_content_type($ruta_completa);

// Configurar headers para preview
header('Content-Type: ' . $tipo_mime);
header('Cache-Control: public, max-age=3600');

// Para imágenes, mostrar directamente
if (strpos($tipo_mime, 'image/') === 0) {
    readfile($ruta_completa);
    exit;
}

// Para PDFs, mostrar en el navegador
if ($tipo_mime === 'application/pdf') {
    header('Content-Disposition: inline; filename="' . basename($ruta) . '"');
    readfile($ruta_completa);
    exit;
}

// Para archivos de texto, mostrar como texto plano
if (strpos($tipo_mime, 'text/') === 0) {
    header('Content-Type: text/plain; charset=utf-8');
    readfile($ruta_completa);
    exit;
}

// Para otros tipos, forzar descarga
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($ruta) . '"');
readfile($ruta_completa);
exit;
?>