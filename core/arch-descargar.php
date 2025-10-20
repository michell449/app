<?php
// Descargar archivo por ID, usando archivos_directorios
require_once __DIR__ . '/../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') {
    http_response_code(400);
    echo 'ID de archivo no especificado.';
    exit;
}
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Buscar el archivo por ID
    $stmt = $db->prepare('SELECT * FROM archivos_directorios WHERE id = ? AND tipo = "A" LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        http_response_code(404);
        echo 'Archivo no encontrado.';
        exit;
    }
    $nombre = $row['nombre'];
    // Reconstruir la ruta física usando la jerarquía de carpetas
    $ruta = [$id . '_' . $nombre];
    $idpadre = $row['idpadre'];
    while ($idpadre) {
        $stmtPadre = $db->prepare('SELECT id, nombre, idpadre, tipo FROM archivos_directorios WHERE id = ?');
        $stmtPadre->execute([$idpadre]);
        $padre = $stmtPadre->fetch(PDO::FETCH_ASSOC);
        if ($padre && $padre['tipo'] === 'D') {
            array_unshift($ruta, $padre['nombre']);
            $idpadre = $padre['idpadre'];
        } else {
            break;
        }
    }
    $rutaFisica = __DIR__ . '/../uploads/archivos/' . implode('/', $ruta);
    if (!file_exists($rutaFisica)) {
        http_response_code(404);
        echo 'Archivo físico no encontrado.';
        exit;
    }
    // Descargar con el nombre original
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($nombre) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($rutaFisica));
    readfile($rutaFisica);
    exit;
} catch (Exception $ex) {
    http_response_code(500);
    echo 'Error en el servidor: ' . $ex->getMessage();
    exit;
}
