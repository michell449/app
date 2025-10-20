<?php
    // core/mover-papelera-notariales.php
    // Controlador para mover un archivo notarial a la papelera en exp_archivos_notariales

    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/class/db.php';
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $id_doc = isset($data['id_doc']) ? intval($data['id_doc']) : 0;

    if ($id_doc <= 0) {
        echo json_encode(['ok' => false, 'msg' => 'ID de documento inválido']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();
    if (!$conn) {
        echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
        exit;
    }

    // Actualizar el archivo para marcarlo como en papelera en la tabla exp_archivos_notariales
    $sql = "UPDATE exp_archivos_notariales SET en_papelera = 1 WHERE id_doc = :id_doc";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_doc', $id_doc, PDO::PARAM_INT);
    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'msg' => 'Documento movido a la papelera']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'No se pudo mover a la papelera']);
    }
?>