<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asunto = $_POST['asunto'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_ejecucion = $_POST['fecha_ejecucion'] ?? '';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $prioridad = $_POST['prioridad'] ?? '';
    $porcentaje = $_POST['porcentaje'] ?? 0;
    $propietario = $_POST['propietario'] ?? '';
    $detalles = $_POST['detalles'] ?? '';
    $id_proyecto = $_POST['id_proyecto'] ?? '';

    if ($asunto && $fecha_inicio && $fecha_vencimiento && $prioridad && $propietario) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'proy_tareas';
        $data = [
            'asunto' => $asunto,
            'fecha_inicio' => $fecha_inicio,
            'fecha_vencimiento' => $fecha_vencimiento,
            'prioridad' => $prioridad,
            'propietario' => $propietario,
            'detalles' => $detalles
        ];
        $crud->data = $data;
        $result = $crud->create();
        if ($result) {
            $id_tarea = $conn->lastInsertId();
            // Solo relaciona la tarea con el proyecto si se seleccionó uno
            if (!empty($id_proyecto)) {
                $crudRel = new Crud($conn);
                $crudRel->db_table = 'proy_tareasproyectos';
                $crudRel->data = [
                    'id_proyecto' => $id_proyecto,
                    'id_tarea' => $id_tarea
                ];
                $resultRel = $crudRel->create();
                if ($resultRel) {
                    echo json_encode(['success' => true, 'message' => 'Tarea agregada correctamente.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'No se pudo relacionar la tarea con el proyecto.']);
                }
            } else {
                // Sin proyecto
                echo json_encode(['success' => true, 'message' => 'Tarea agregada correctamente (sin proyecto).']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo agregar la tarea.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    }
    exit;
}
?>
