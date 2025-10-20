<?php
if (!isset($_GET['file'])) {
    die("Archivo no especificado.");
}
$id_nombre = basename($_GET['file']);
require_once(dirname(__DIR__) . '/config.php');
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Buscar el archivo y su ruta completa
$stmt = $db->prepare('SELECT id, nombre, idpadre FROM archivos_directorios WHERE CONCAT(id, "_", nombre) = ? AND tipo = "A" LIMIT 1');
$stmt->execute([$id_nombre]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    http_response_code(404);
    echo '<h1>404</h1><p>Archivo no encontrado en la base de datos.</p>';
    exit;
}
// Reconstruir la ruta física
$ruta = [$row['id'] . '_' . $row['nombre']];
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
$path = __DIR__ . '/../uploads/archivos/' . implode('/', $ruta);
if (!file_exists($path)) {
    http_response_code(404);
    echo '<h1>404</h1><p>Archivo físico no encontrado.</p>';
    exit;
}
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
switch ($ext) {
    case 'pdf': $type = 'pdf'; break;
    case 'jpg': case 'jpeg': case 'png': case 'gif': $type = 'image'; break;
    case 'txt': case 'csv': $type = 'text'; break;
    case 'doc': case 'docx': case 'xls': case 'xlsx': $type = 'office'; break;
    default: die("Tipo de archivo no soportado.");
}
?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Vista Previa</title>
<style>
body, html { margin:0; padding:0; height:100%; }
iframe, img, pre { width:100%; height:100%; border:none; }
</style>
</head>
<body>
<?php
$url_rel = '/app/uploads/archivos/' . implode('/', $ruta);
switch($type) {
    case 'pdf':
        echo '<iframe src="' . $url_rel . '#toolbar=0" frameborder="0"></iframe>';
        break;
    case 'image':
        echo '<img src="' . $url_rel . '" alt="Vista Previa">';
        break;
    case 'text':
        echo '<pre>'.htmlspecialchars(file_get_contents($path)).'</pre>';
        break;
    case 'office':
        $url = urlencode("http://" . $_SERVER['HTTP_HOST'] . $url_rel);
        echo '<iframe src="https://docs.google.com/gview?url=' . $url . '&embedded=true"></iframe>';
        break;
}
?>
</body>
</html>