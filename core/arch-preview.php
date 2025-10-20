<?php
// Parámetros originales (mantenemos compatibilidad)
$carpeta = $_GET['carpeta'] ?? '';
$file = $_GET['file'] ?? '';
$download = isset($_GET['download']);

// Nuevo parámetro para ruta completa
$ruta_completa = $_GET['ruta'] ?? '';

// Determinar la ruta del archivo
if (!empty($ruta_completa)) {
    // Usar ruta completa (nuevo método)
    $path = dirname(__DIR__) . '/' . $ruta_completa;
} else {
    // Usar método original con carpeta y file
    $dir = dirname(__DIR__) . '/uploads/' . $carpeta;
    $path = $dir . '/' . $file;
}

if (!is_file($path)) {
    http_response_code(404);
    echo 'Archivo no encontrado: ' . htmlspecialchars($path);
    exit;
}

$filename = !empty($ruta_completa) ? basename($ruta_completa) : $file;
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$mime = mime_content_type($path);

// Si se solicita descarga, forzar descarga
if ($download) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}

// Mostrar en el navegador si es imagen, pdf, word, excel, ppt, txt
if (in_array($ext, ['jpg','jpeg','png','gif','bmp','webp','pdf','doc','docx','xls','xlsx','ppt','pptx','txt'])) {
    header('Content-Type: ' . $mime);
    header('Content-Disposition: inline; filename="' . basename($filename) . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
} else {
    // Para otros tipos, forzar descarga
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}
