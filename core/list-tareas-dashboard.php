<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();
$idColab = $_SESSION['ID_COLAB'];
$idPerfil = $_SESSION['USR_TYPE'];
$status = isset($_GET['status']) ? $_GET['status'] : null;

// Construir consulta según perfil y status
$whereStatus = $status ? " AND t.status = :status" : "";

if ($idPerfil == 1 || $idPerfil == 2) {
    // Admin/supervisor: todas las tareas, con filtro opcional
    $sql = "SELECT t.*, p.nombre AS proyecto, c.nombre AS propietario_nombre, c.apellidos AS propietario_apellidos
            FROM proy_tareas t
            LEFT JOIN proy_tareasproyectos tp ON t.id_tarea = tp.id_tarea
            LEFT JOIN proy_proyectos p ON tp.id_proyecto = p.id_proyecto
            LEFT JOIN sys_colaboradores c ON t.propietario = c.id_colab
            WHERE 1" . $whereStatus;
    $params = [];
    if ($status) $params['status'] = $status;
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Usuario: solo sus tareas y tareas de sus proyectos, con filtro opcional
    $sql = "SELECT t.*, p.nombre AS proyecto, c.nombre AS propietario_nombre, c.apellidos AS propietario_apellidos
            FROM proy_tareas t
            LEFT JOIN proy_tareasproyectos tp ON t.id_tarea = tp.id_tarea
            LEFT JOIN proy_proyectos p ON tp.id_proyecto = p.id_proyecto
            LEFT JOIN proy_equiposcolab ec ON p.id_equipo = ec.id_equipo
            LEFT JOIN sys_colaboradores c ON t.propietario = c.id_colab
            WHERE (t.propietario = :idColab OR ec.id_colab = :idColab)" . $whereStatus;
    $params = ['idColab' => $idColab];
    if ($status) $params['status'] = $status;
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Renderizar tabla de tareas
echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-striped">';
echo '<thead><tr>';
echo '<th>Nombre</th><th>Propietario</th><th>Estado</th><th>Prioridad</th><th>Avance</th><th>Fecha de vencimiento</th>';
echo '</tr></thead><tbody>';

if (is_array($tareas) && count($tareas) > 0) {
    foreach ($tareas as $t) {
        $nombreCompleto = trim($t['propietario_nombre'] . ' ' . $t['propietario_apellidos']);
        echo '<tr>';
        echo '<td>' . htmlspecialchars($t['asunto']) . '</td>';
        echo '<td>' . htmlspecialchars($nombreCompleto) . '</td>';
        echo '<td>' . htmlspecialchars($t['status']) . '</td>';
        echo '<td>' . htmlspecialchars($t['prioridad']) . '</td>';
        echo '<td>' . htmlspecialchars($t['porcentaje']) . '%</td>';
        echo '<td>' . htmlspecialchars($t['fecha_vencimiento']) . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="6" class="text-center">No hay tareas disponibles.</td></tr>';
}
echo '</tbody></table></div>';