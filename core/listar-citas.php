<?php

    require_once __DIR__ . '/class/db.php';

    $database = new Database();
    $db = $database->getConnection();

    // Consulta todas las citas con todos los campos necesarios, incluyendo status
    $stmt = $db->prepare("SELECT id_cita, asunto, fecha_inicio, todo_dia, detalles, ubicacion, duracion, id_colab, status FROM citas_citas");
    $stmt->execute(); // <-- Ejecuta la consulta
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC); // <-- Obtiene los resultados

    $eventos = [];
    foreach ($citas as $cita) {
        // Asignar color según status
        $color = '#0dcaf0'; // info (azul claro)
        if ($cita['status'] === 'Realizada') {
            $color = '#198754'; // verde
        } elseif ($cita['status'] === 'Cancelada') {
            $color = '#dc3545'; // rojo
        } elseif ($cita['status'] === 'Pospuesta') {
            $color = '#ffc107'; // amarillo
        }

        $eventos[] = [
            'id' => $cita['id_cita'],
            'title' => $cita['asunto'],
            'start' => $cita['fecha_inicio'],
            'allDay' => $cita['todo_dia'] ? true : false,
            'description' => $cita['detalles'],
            'ubicacion' => $cita['ubicacion'],
            'duracion' => $cita['duracion'],
            'colaborador' => $cita['id_colab'],
            'status' => $cita['status'],
            'backgroundColor' => $color,
            'borderColor' => $color
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($eventos);
?>