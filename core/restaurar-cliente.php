<?php
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';

    $id = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;

    if ($id > 0) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'sys_clientes';
        $sql = "UPDATE sys_clientes SET activo = 1 WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$id]);
        unset($stmt);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Cliente restaurado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo restaurar el cliente.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID inválido.']);
    }
