<?php
    require_once dirname(__DIR__) . '/core/class/db.php';
    require_once dirname(__DIR__) . '/core/class/crud.php';

    // Controlador para consultar la tabla exp_notariales
    date_default_timezone_set('America/Mexico_City');

    header('Content-Type: application/json; charset=utf-8');

    $response = [
        'success' => false,
        'data' => [],
        'message' => ''
    ];

    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $crud = new crud($db);
        $crud->db_table = 'exp_notariales';
        $count = $crud->read();
        
        if ($count > 0) {
            $response['success'] = true;
            $response['data'] = $crud->data;
        } else {
            $response['message'] = 'No se encontraron registros.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error al consultar la base de datos: ' . $e->getMessage();
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>