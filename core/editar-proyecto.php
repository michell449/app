<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proyecto = $_POST['id_proyecto'] ?? '';
    $nombre = $_POST['nombreProyecto'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $prioridad = $_POST['prioridad'] ?? '';
    $avance = $_POST['avance'] ?? 0;
    $status = $_POST['status'] ?? '';

    if ($id_proyecto && $nombre && $fecha_inicio && $fecha_vencimiento) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'proy_proyectos';
        $crud->id_param = $id_proyecto;
        $crud->id_key = 'id_proyecto';
        $crud->data = [
            'nombre' => $nombre,
            'fecha_inicio' => $fecha_inicio,
            'fecha_vencimiento' => $fecha_vencimiento,
            'prioridad' => $prioridad,
            'avance' => $avance,
            'status' => $status
        ];
        $result = $crud->update();
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Proyecto actualizado correctamente.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el proyecto.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Faltan datos obligatorios.'
        ]);
    }
    exit;
}
?>
