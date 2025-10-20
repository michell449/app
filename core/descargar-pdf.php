<?php
// descargar-pdf.php: Sirve un archivo PDF por uuid aunque no tenga extensión

$id_notarial = isset($_GET['id_notarial']) ? intval($_GET['id_notarial']) : 0;
$uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';
$nombre_archivo = isset($_GET['nombre_archivo']) ? $_GET['nombre_archivo'] : 'documento.pdf';

if (!$id_notarial || !$uuid) {
    http_response_code(400);
    echo 'Parámetros inválidos';
    exit;
}


// Obtener la extensión del archivo original
$ext = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
$ruta = __DIR__ . "/../uploads/Notariales/{$id_notarial}/{$uuid}.{$ext}";

if (!file_exists($ruta)) {
    http_response_code(404);
    echo 'Archivo no encontrado';
    exit;
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($nombre_archivo) . '"');
header('Content-Length: ' . filesize($ruta));
readfile($ruta);
exit;
