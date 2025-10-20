<?php
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';
    
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    $crud->db_table = 'sys_clientes';

    // Leer todos los clientes inactivos (papelera)
    $sql = "SELECT id_cliente, nombre_comercial, correo, telefono, razon_social, rfc, contacto FROM sys_clientes WHERE activo = 0 ORDER BY id_cliente DESC";
    $result = $crud->customQuery($sql);

    header('Content-Type: application/json');
    echo json_encode($result ? $result : []);
?>