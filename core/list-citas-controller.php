<?php
    // list-citas-controller.php
    // Controlador para obtener y mostrar las citas en la tabla
    require_once __DIR__ . '/class/db.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $db = new Database();
    $conn = $db->getConnection();

    if (isset($_SESSION['USR_TYPE']) && $_SESSION['USR_TYPE'] == 1) {
        // Super administrador: ver todas las citas
        $sql = "SELECT asunto, ubicacion, fecha_inicio, todo_dia, detalles, status, duracion, enviar_correo, asistira FROM citas_citas";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($rows && count($rows) > 0) {
            foreach ($rows as $row) {
            // determinar clase para columna de asistencia
            // la columna `asistira` en la BD es tinyint(1): 1 = asistirá, 0 = no ha contestado/No, etc.
            $asistiraVal = isset($row['asistira']) ? (int)$row['asistira'] : 0;
            $asistiraClass = 'badge bg-warning text-dark';
            $asistiraLabel = 'Sin respuesta';
            if ($asistiraVal === 1) {
                $asistiraClass = 'badge bg-success text-white';
                $asistiraLabel = 'Asistirá';
            } elseif ($asistiraVal === 0) {
                // mantener amarillo para sin respuesta (valor 0)
                $asistiraClass = 'badge bg-warning text-dark';
                $asistiraLabel = 'Sin respuesta';
            }

            // si el status indica cancelada, forzar rojo
            $status = isset($row['status']) ? $row['status'] : '';
            $statusBadgeClass = 'badge bg-secondary text-white';
            if (strtolower($status) === 'cancelada' || strtolower($status) === 'cancelado') {
                $statusBadgeClass = 'badge bg-danger text-white';
            } elseif (strtolower($status) === 'programada' || strtolower($status) === 'programado') {
                $statusBadgeClass = 'badge bg-primary text-white';
            } elseif (strtolower($status) === 'realizada' || strtolower($status) === 'realizado') {
                $statusBadgeClass = 'badge bg-success text-white';
            } elseif (strtolower($status) === 'pospuesta') {
                $statusBadgeClass = 'badge bg-warning text-dark';
            }

            echo '<tr>';
            echo '<td class="text-dark">' . htmlspecialchars($row['asunto']) . '</td>';
            echo '<td class="text-dark">' . htmlspecialchars($row['ubicacion']) . '</td>';
            echo '<td class="text-center align-middle text-dark" style="vertical-align:middle;">' . htmlspecialchars($row['fecha_inicio']) . '</td>';
            // Badge para "Todo el día" — azul si es todo el día, gris (secondary) si es parcial
            $todoDiaClass = ($row['todo_dia'] ? 'badge bg-info text-white' : 'badge bg-secondary text-white');
            echo '<td class="text-center align-middle text-dark" style="vertical-align:middle;"><span class="' . $todoDiaClass . '" style="display:inline-block; padding:6px 8px; font-size:0.95rem; min-width:80px;">' . ($row['todo_dia'] ? 'Todo el día' : 'Parcial') . '</span></td>';
            echo '<td class="text-dark">' . htmlspecialchars($row['detalles']) . '</td>';
            echo '<td class="text-center align-middle text-dark" style="vertical-align:middle;"><span class="' . $statusBadgeClass . '" style="display:inline-block; padding:6px 8px; font-size:0.95rem; min-width:80px;">' . htmlspecialchars($status) . '</span></td>';
            echo '<td class="text-center align-middle text-dark" style="vertical-align:middle;">' . htmlspecialchars($row['duracion']) . '</td>';
            echo '<td class="text-center align-middle text-dark" style="vertical-align:middle;">' . ($row['enviar_correo'] ? 'Sí' : 'No') . '</td>';
            // celda centrada y badge más grande para mejor legibilidad
            echo '<td class="text-center align-middle text-dark" style="vertical-align:middle;">';
            echo '<span class="' . $asistiraClass . '" style="display:inline-block; padding:6px 10px; font-size:0.95rem; min-width:90px;">' . $asistiraLabel . '</span>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="9">No hay citas registradas.</td></tr>';
    }
} else if (isset($_SESSION['id_colab']) && $_SESSION['id_colab'] !== null && $_SESSION['id_colab'] !== '' && is_numeric($_SESSION['id_colab'])) {
    // Colaborador: ver solo sus citas
    $id_colab = intval($_SESSION['id_colab']);
    $sql = "SELECT asunto, ubicacion, fecha_inicio, todo_dia, detalles, status, duracion, enviar_correo, asistira FROM citas_citas WHERE id_colab = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_colab]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows && count($rows) > 0) {
        foreach ($rows as $row) {
            // determinar clase para columna de asistencia
            // la columna `asistira` en la BD es tinyint(1): 1 = asistirá, 0 = no ha contestado/No, etc.
            $asistiraVal = isset($row['asistira']) ? (int)$row['asistira'] : 0;
            $asistiraClass = 'badge bg-warning text-dark';
            $asistiraLabel = 'Sin respuesta';
            if ($asistiraVal === 1) {
                $asistiraClass = 'badge bg-success text-white';
                $asistiraLabel = 'Asistirá';
            } elseif ($asistiraVal === 0) {
                // mantener amarillo para sin respuesta (valor 0)
                $asistiraClass = 'badge bg-warning text-dark';
                $asistiraLabel = 'Sin respuesta';
            }

            // si el status indica cancelada, forzar rojo
            $status = isset($row['status']) ? $row['status'] : '';
            $statusBadgeClass = 'badge bg-secondary text-white';
            if (strtolower($status) === 'cancelada' || strtolower($status) === 'cancelado') {
                $statusBadgeClass = 'badge bg-danger text-white';
            } elseif (strtolower($status) === 'programada' || strtolower($status) === 'programado') {
                $statusBadgeClass = 'badge bg-primary text-white';
            } elseif (strtolower($status) === 'realizada' || strtolower($status) === 'realizado') {
                $statusBadgeClass = 'badge bg-success text-white';
            } elseif (strtolower($status) === 'pospuesta') {
                $statusBadgeClass = 'badge bg-warning text-dark';
            }

            echo '<tr>';
            echo '<td class="text-white">' . htmlspecialchars($row['asunto']) . '</td>';
            echo '<td class="text-white">' . htmlspecialchars($row['ubicacion']) . '</td>';
            echo '<td class="text-center align-middle text-white" style="vertical-align:middle;">' . htmlspecialchars($row['fecha_inicio']) . '</td>';
            // Badge para "Todo el día" — azul si es todo el día, gris (secondary) si es parcial
            $todoDiaClass = ($row['todo_dia'] ? 'badge bg-info text-white' : 'badge bg-secondary text-white');
            echo '<td class="text-center align-middle text-white" style="vertical-align:middle;"><span class="' . $todoDiaClass . '" style="display:inline-block; padding:6px 8px; font-size:0.95rem; min-width:80px;">' . ($row['todo_dia'] ? 'Todo el día' : 'Parcial') . '</span></td>';
            echo '<td class="text-white">' . htmlspecialchars($row['detalles']) . '</td>';
            echo '<td class="text-center align-middle text-white" style="vertical-align:middle;"><span class="' . $statusBadgeClass . '" style="display:inline-block; padding:6px 8px; font-size:0.95rem; min-width:80px;">' . htmlspecialchars($status) . '</span></td>';
            echo '<td class="text-center align-middle text-white" style="vertical-align:middle;">' . htmlspecialchars($row['duracion']) . '</td>';
            echo '<td class="text-center align-middle text-white" style="vertical-align:middle;">' . ($row['enviar_correo'] ? 'Sí' : 'No') . '</td>';
            // celda centrada y badge más grande para mejor legibilidad
            echo '<td class="text-center align-middle text-white" style="vertical-align:middle;">';
            echo '<span class="' . $asistiraClass . '" style="display:inline-block; padding:6px 10px; font-size:0.95rem; min-width:90px;">' . $asistiraLabel . '</span>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="9">No hay citas registradas para este colaborador.</td></tr>';
    }
} else {
    echo '<tr><td colspan="9">No tienes permisos para ver citas. Solo los colaboradores pueden ver sus citas asignadas.</td></tr>';
}
?>