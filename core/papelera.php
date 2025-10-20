<?php
// core/papelera.php
// Controlador para manejar la papelera de archivos por expediente y categoría
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json');

// Conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener parámetros
$id_expediente = isset($_GET['id_expediente']) ? intval($_GET['id_expediente']) : 0;
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Consulta archivos en papelera filtrando por expediente y categoría si aplica
$sql = "SELECT id_doc, id_expediente, tipo_archivo, fecha, documento, nombre_archivo FROM exp_documentos WHERE en_papelera = 1";
if ($id_expediente > 0) {
    $sql .= " AND id_expediente = :id_expediente";
}
if ($categoria) {
    $sql .= " AND tipo_archivo = :categoria";
}
$sql .= " ORDER BY fecha DESC";

$stmt = $conn->prepare($sql);
if ($id_expediente > 0) {
    $stmt->bindParam(':id_expediente', $id_expediente, PDO::PARAM_INT);
}
if ($categoria) {
    $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
}
$stmt->execute();
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'ok' => true,
    'archivos' => $archivos,
    'categoria' => $categoria
]);
// No es necesario cerrar PDO
