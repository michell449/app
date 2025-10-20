<?php
require_once __DIR__ . '/class/db.php';

$proyecto_id = $_GET['id'] ?? null;
if (!$proyecto_id) {
  echo '<tr><td colspan="4" class="text-center">No hay tareas para este proyecto.</td></tr>';
  return;
}

$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT t.*, c.nombre AS responsable_nombre, c.apellidos AS responsable_apellidos
        FROM proy_tareas t
        INNER JOIN proy_tareasproyectos ptp ON t.id_tarea = ptp.id_tarea
        LEFT JOIN sys_colaboradores c ON t.propietario = c.id_colab
        WHERE ptp.id_proyecto = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$proyecto_id]);
$tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($tareas && count($tareas) > 0) {
  foreach ($tareas as $idx => $tarea) {
    $responsable = trim(($tarea['responsable_nombre'] ?? '') . ' ' . ($tarea['responsable_apellidos'] ?? ''));
    $porcentaje = intval($tarea['porcentaje'] ?? 0);

    // Determina el color de la barra según el porcentaje
    if ($porcentaje < 30) {
      $progressColor = 'bg-danger';
    } elseif ($porcentaje < 70) {
      $progressColor = 'bg-warning';
    } else {
      $progressColor = 'bg-success';
    }

    echo '<tr>';
    echo '<td>' . ($idx + 1) . '</td>';
    echo '<td>' . htmlspecialchars($responsable) . '</td>';
    echo '<td>' . htmlspecialchars($tarea['asunto'] ?? '') . '</td>';
    echo '<td>'  . htmlspecialchars($tarea['fecha_vencimiento'] ?? '') . '</td>';
    echo '<td>';
    echo '<div class="progress" style="height: 20px;">';
    echo '<div class="progress-bar ' . $progressColor . '" role="progressbar" style="width: ' . $porcentaje . '%;" aria-valuenow="' . $porcentaje . '" aria-valuemin="0" aria-valuemax="100">' . $porcentaje . '%</div>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
  }
  
} else {
  echo '<tr><td colspan="5" class="text-center">No hay tareas para este proyecto.</td></tr>';
}
?>