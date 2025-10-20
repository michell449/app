<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    $crud->db_table = 'proy_proyectos';

    // Recoger datos del formulario
    $nombre = $_POST['nombreProyecto'] ?? '';
    $id_equipo = isset($_POST['id_equipo']) && $_POST['id_equipo'] !== '' ? $_POST['id_equipo'] : null;
    $supervisor = $_POST['supervisorProyecto'] ?? '';
    $fecha_inicio = $_POST['fechaInicio'] ?? '';
    $fecha_vencimiento = $_POST['fechaVencimiento'] ?? '';
    $prioridad = $_POST['prioridadProyecto'] ?? '';
    $descripcion = $_POST['descripcionProyecto'] ?? '';
    $avance = $_POST['avanceProyecto'] ?? 0;
    $status = $_POST['statusProyecto'] ?? '';
    $updated_at = date('Y-m-d H:i:s');

    $crud->data = [
        'nombre' => $nombre,
        'id_equipo' => $id_equipo,
        'supervisor' => $supervisor,
        'fecha_inicio' => $fecha_inicio,
        'fecha_vencimiento' => $fecha_vencimiento,
        'prioridad' => $prioridad,
        'descripcion' => $descripcion,
        'avance' => $avance,
        'status' => $status,
        'updated_at' => $updated_at
    ];

    $result = $crud->create();

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Proyecto creado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear el proyecto']);
    }
    exit;
}
?>
