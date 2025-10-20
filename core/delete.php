<?php
    require_once __DIR__ . '/class/db.php';
    $database = new Database();
    $db = $database->getConnection();

    try {
        $sql = "DROP TABLE IF EXISTS sys_mensajes";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        echo json_encode(['success' => true, 'message' => 'Tabla sys_mensajes eliminada correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
