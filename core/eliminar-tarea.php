<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_tarea = $_POST['id_tarea'] ?? '';
    if ($id_tarea) {
        $db = new Database();
        $conn = $db->getConnection();
        // Elimina relaciones en proy_tareasproyectos
        $stmtRel = $conn->prepare("DELETE FROM proy_tareasproyectos WHERE id_tarea = ?");
        $stmtRel->execute([$id_tarea]);
        // Elimina la tarea principal
        $crud = new Crud($conn);
        $crud->db_table = 'proy_tareas';
        $crud->id_key = 'id_tarea';
        $crud->id_param = $id_tarea;
        $result = $crud->delete();
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Tarea eliminada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la tarea.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    }
    exit;
}
?>
