<?php
    // core/get-archivo-notarial.php
    // Consulta un archivo notarial por UUID y devuelve todos los campos
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/class/db.php';
    header('Content-Type: application/json');

    $uuid = isset($_GET['uuid']) ? trim($_GET['uuid']) : '';
    if ($uuid === '') {
        echo json_encode(['ok' => false, 'msg' => 'UUID requerido']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();
    if (!$conn) {
        echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos']);
        exit;
    }

    $sql = "SELECT * FROM exp_archivos_notariales WHERE uuid = :uuid LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
    $stmt->execute();
    $archivo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($archivo) {
        echo json_encode(['ok' => true, 'archivo' => $archivo]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Archivo no encontrado']);
    }
?>