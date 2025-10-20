<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$id_archivo = trim($_POST['id_archivo'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

// Si no se envían, conservar los valores actuales
$query = $db->prepare('SELECT tamaño, tipo_mime, ruta_archivo FROM arch_archivos WHERE id_archivo = ?');
$query->execute([$id_archivo]);
$actual = $query->fetch(PDO::FETCH_ASSOC);

$tipo_mime = isset($_POST['tipo_mime']) && trim($_POST['tipo_mime']) !== '' ? trim($_POST['tipo_mime']) : ($actual['tipo_mime'] ?? '');
$tamano = isset($_POST['tamaño']) && trim($_POST['tamaño']) !== '' ? trim($_POST['tamaño']) : ($actual['tamaño'] ?? '');
$ruta_archivo = isset($_POST['ruta_archivo']) && trim($_POST['ruta_archivo']) !== '' ? trim($_POST['ruta_archivo']) : ($actual['ruta_archivo'] ?? '');

function nullIfEmpty($v) {
  return (isset($v) && trim($v) !== '') ? trim($v) : null;
}
$id_categoria = nullIfEmpty($_POST['id_categoria'] ?? '');
if (!is_null($id_categoria) && !is_numeric($id_categoria)) {
    $id_categoria = null;
}
$id_institucion = nullIfEmpty($_POST['id_institucion'] ?? '');
if (!is_null($id_institucion) && !is_numeric($id_institucion)) {
    $id_institucion = null;
}
$id_proyecto = nullIfEmpty($_POST['id_proyecto'] ?? '');
if (!is_null($id_proyecto) && !is_numeric($id_proyecto)) {
    $id_proyecto = null;
}
$id_tarea = nullIfEmpty($_POST['id_tarea'] ?? '');
if (!is_null($id_tarea) && !is_numeric($id_tarea)) {
    $id_tarea = null;
}
$id_colab = nullIfEmpty($_POST['id_colab'] ?? '');
if (!is_null($id_colab) && !is_numeric($id_colab)) {
    $id_colab = null;
}
$compartido = trim($_POST['compartido'] ?? '');
$descargable = trim($_POST['descargable'] ?? '');
$password = trim($_POST['password'] ?? '');
if ($id_archivo === '' || $nombre === '') {
    echo json_encode(['success' => false, 'msg' => 'Faltan datos obligatorios']);
    exit;
}
// Cambiar 'tamano' por 'tamaño' en la consulta
$stmt = $db->prepare("UPDATE arch_archivos SET nombre=?, descripcion=?, tipo_mime=?, tamaño=?, ruta_archivo=?, id_categoria=?, id_institucion=?, id_proyecto=?, id_tarea=?, id_colab=?, compartido=?, descargable=?, password=? WHERE id_archivo=?");
$ok = $stmt->execute([
    $nombre,
    $descripcion,
    $tipo_mime,
    $tamano,
    $ruta_archivo,
    $id_categoria,
    $id_institucion,
    $id_proyecto,
    $id_tarea,
    $id_colab,
    $compartido,
    $descargable,
    $password,
    $id_archivo
]);
echo json_encode(['success' => $ok]);
