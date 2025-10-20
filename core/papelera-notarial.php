<?php
    // core/papelera-notarial.php
    // Controlador para manejar la papelera de archivos notariales por expediente y categoría
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
    $id_notarial = isset($_GET['id_notarial']) ? intval($_GET['id_notarial']) : 0;
    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

    // Consulta archivos en papelera filtrando por expediente y categoría si aplica
    $sql = "SELECT id_doc, id_notarial, uuid, categoria, fecha, fecha_presentacion, documento, nombre_archivo FROM exp_archivos_notariales WHERE en_papelera = 1";
    if ($id_notarial > 0) {
        $sql .= " AND id_notarial = :id_notarial";
    }
    if ($categoria) {
        $sql .= " AND categoria = :categoria";
    }
    $sql .= " ORDER BY fecha DESC";

    $stmt = $conn->prepare($sql);
    if ($id_notarial > 0) {
        $stmt->bindParam(':id_notarial', $id_notarial, PDO::PARAM_INT);
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
?>