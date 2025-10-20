<?php
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header('Content-Type: application/json');

    $db = new Database();
    $pdo = $db->getConnection();

    $id = $_GET['id'] ?? '';
    if ($id == '') {
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
    }

    $crud = new crud($pdo);
    $sql = "SELECT * FROM sys_clientes WHERE id_cliente = ? LIMIT 1";
    $result = $crud->customQuery($sql, [$id]);

    if ($result && count($result) > 0) {
        echo json_encode(['success' => true, 'data' => $result[0]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
    }
