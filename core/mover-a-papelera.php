<?php
// core/mover-a-papelera.php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id_archivo = isset($data['id_archivo']) ? intval($data['id_archivo']) : 0;
$categoria = isset($data['categoria']) ? $data['categoria'] : '';

if ($id_archivo <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'ID de archivo inválido']);
    exit;
}

// Usar la clase Database en vez de mysqli directo
$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
    exit;
}

// Actualizar el archivo para marcarlo como en papelera
$sql = "UPDATE exp_documentos SET en_papelera = 1 WHERE id_doc = :id_archivo";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_archivo', $id_archivo, PDO::PARAM_INT);
if ($stmt->execute()) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'No se pudo mover a papelera']);
}
// No es necesario cerrar PDO
