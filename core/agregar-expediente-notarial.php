<?php
    require_once '../config.php';
    require_once __DIR__ . '/class/db.php';

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        exit;
    }

    // Recibe los datos por POST
    date_default_timezone_set('America/Mexico_City');

    $domicilio = isset($_POST['domicilio']) ? $_POST['domicilio'] : null;
    $rfc = isset($_POST['rfc']) ? $_POST['rfc'] : null;
    $correo = isset($_POST['correo']) ? $_POST['correo'] : null;
    $institucion_bancaria = isset($_POST['institucion_bancaria']) ? $_POST['institucion_bancaria'] : null;
    $empresa = isset($_POST['empresa']) ? $_POST['empresa'] : null;
    $instrumento = isset($_POST['instrumento']) ? $_POST['instrumento'] : null;
    $notario = isset($_POST['notario']) ? $_POST['notario'] : null;
    $giro_empresa = isset($_POST['giro_empresa']) ? $_POST['giro_empresa'] : null;
    $rl = isset($_POST['rl']) ? $_POST['rl'] : null;
    $socio = isset($_POST['socio']) ? $_POST['socio'] : null;
    $fecha_registro = isset($_POST['fecha_registro']) ? $_POST['fecha_registro'] : date('Y-m-d H:i:s');
    $activo = isset($_POST['activo']) ? $_POST['activo'] : 1;

    // Validación básica
    if (!$empresa) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        exit;
    }


    // Conexión usando la clase Database (PDO)
    $db = new Database();
    $conn = $db->getConnection();
    if (!$conn) {
        echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']);
        exit;
    }

    try {
        $sql = "INSERT INTO exp_notariales (domicilio, rfc, correo, institucion_bancaria, empresa, instrumento, notario, giro_empresa, rl, socio, fecha_registro, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $domicilio, $rfc, $correo, $institucion_bancaria, $empresa, $instrumento, $notario, $giro_empresa, $rl, $socio, $fecha_registro, $activo
        ]);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Expediente notarial guardado correctamente.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el expediente.']);
            exit;
        }
        $stmt = null;
        $conn = null;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
?>