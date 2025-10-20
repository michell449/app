<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tarea = $_POST['id_tarea'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_ejecucion = $_POST['fecha_ejecucion'] ?? '';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $status = $_POST['status'] ?? '';
    $prioridad = $_POST['prioridad'] ?? '';
    $porcentaje = $_POST['porcentaje'] ?? 0;
    $detalles = $_POST['detalles'] ?? '';

    if ($id_tarea && $asunto && $fecha_inicio && $fecha_ejecucion && $fecha_vencimiento && $prioridad) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'proy_tareas';
        $crud->id_key = 'id_tarea';
        $crud->id_param = $id_tarea;
        $crud->data = [
            'asunto' => $asunto,
            'fecha_inicio' => $fecha_inicio,
            'fecha_ejecucion' => $fecha_ejecucion,
            'fecha_vencimiento' => $fecha_vencimiento,
            'status' => $status,
            'prioridad' => $prioridad,
            'porcentaje' => $porcentaje,
            'detalles' => $detalles,
            'propietario' => $_SESSION['ID_COLAB']
        ];
        $result = $crud->update();
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Tarea actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la tarea.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    }
    exit;
}
?>
