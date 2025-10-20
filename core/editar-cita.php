<?php
    header('Content-Type: application/json');
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Recibir datos JSON
    $data = json_decode(file_get_contents('php://input'), true);

    if (
        isset($data['id_cita'], $data['asunto'], $data['fecha_inicio'], $data['todo_dia'],
            $data['detalles'], $data['ubicacion'], $data['duracion'], $data['id_colab'], $data['status'])
    ) {
        require_once __DIR__ . '/class/db.php';
        $database = new Database();
        $db = $database->getConnection();

        $id = $data['id_cita'];
        $asunto = isset($data['asunto']) ? trim(strip_tags($data['asunto'])) : '';
        $fecha_inicio = isset($data['fecha_inicio']) ? $data['fecha_inicio'] : '';
        $todo_dia = isset($data['todo_dia']) ? $data['todo_dia'] : 0;
        $detalles = isset($data['detalles']) ? trim(strip_tags($data['detalles'])) : '';
        $ubicacion = isset($data['ubicacion']) ? trim(strip_tags($data['ubicacion'])) : '';
        $duracion = isset($data['duracion']) ? $data['duracion'] : '';
        $id_colab = isset($data['id_colab']) ? $data['id_colab'] : null;
        // Validar status: si está vacío, mantener el valor anterior
        if (isset($data['status']) && trim($data['status']) !== '') {
            $status = trim($data['status']);
        } else {
            // Obtener el status actual de la cita
            $stmtStatus = $db->prepare("SELECT status FROM citas_citas WHERE id_cita = ? LIMIT 1");
            $stmtStatus->execute([$id]);
            $rowStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC);
            $status = $rowStatus ? $rowStatus['status'] : 'Pendiente';
        }

        try {
            $stmt = $db->prepare("UPDATE citas_citas SET asunto = ?, fecha_inicio = ?, todo_dia = ?, detalles = ?, ubicacion = ?, duracion = ?, id_colab = ?, status = ? WHERE id_cita = ?");
            $stmt->execute([$asunto, $fecha_inicio, $todo_dia, $detalles, $ubicacion, $duracion, $id_colab, $status, $id]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Campos incompletos']);
    }
?>