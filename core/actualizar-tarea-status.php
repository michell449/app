<?php

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (is_array($data) && isset($data['id_tarea'])) {
        $id_tarea = $data['id_tarea'];
        $updateData = [];
        if (isset($data['status'])) $updateData['status'] = $data['status'];
        if (isset($data['fecha_ejecucion'])) $updateData['fecha_ejecucion'] = $data['fecha_ejecucion'];
        // Si la tarea se marca como Completada, actualiza el porcentaje a 100
        if (isset($data['status']) && $data['status'] === 'Completada') {
            $updateData['porcentaje'] = 100;
        }
        // Puedes agregar más campos si lo necesitas

        if (!empty($updateData)) {
            $db = new Database();
            $conn = $db->getConnection();
            $crud = new Crud($conn);
            $crud->db_table = 'proy_tareas';
            $crud->id_key = 'id_tarea';
            $crud->id_param = $id_tarea;
            $crud->data = $updateData;
            $result = $crud->update();
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No hay datos para actualizar.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    }
    exit;
}
?>