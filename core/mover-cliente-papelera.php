<?php
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';

    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
    if ($id_cliente < 1) {
        echo json_encode(['success' => false, 'error' => 'ID de cliente inválido']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    $crud->db_table = 'sys_clientes';

    try {
        $sql = "UPDATE sys_clientes SET activo = 0 WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_cliente]);
        echo json_encode(['success' => true, 'message' => 'Cliente desactivado (en papelera).']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
