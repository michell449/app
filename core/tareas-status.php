<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();
$idUsuario = $_SESSION['ID_COLAB'];
$usrType = isset($_SESSION['USR_TYPE']) ? $_SESSION['USR_TYPE'] : 0;

if ($usrType == 1 || $usrType == 2) {
    // Super admin o admin: ver todas las tareas
    $stmt = $conn->prepare("
      SELECT t.*, c.nombre AS propietario_nombre, c.apellidos AS propietario_apellidos, a.nombre AS archivo_nombre, p.nombre AS nombre_proyecto
      FROM proy_tareas t
      LEFT JOIN sys_colaboradores c ON t.propietario = c.id_colab
      LEFT JOIN arch_archivos a ON t.id_tarea = a.id_tarea
      LEFT JOIN proy_tareasproyectos tp ON t.id_tarea = tp.id_tarea
      LEFT JOIN proy_proyectos p ON tp.id_proyecto = p.id_proyecto
    ");
    $stmt->execute();
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Usuario normal: solo sus tareas
    $stmt = $conn->prepare("
      SELECT t.*, c.nombre AS propietario_nombre, c.apellidos AS propietario_apellidos, a.nombre AS archivo_nombre, p.nombre AS nombre_proyecto
      FROM proy_tareas t
      LEFT JOIN sys_colaboradores c ON t.propietario = c.id_colab
      LEFT JOIN arch_archivos a ON t.id_tarea = a.id_tarea
      LEFT JOIN proy_tareasproyectos tp ON t.id_tarea = tp.id_tarea
      LEFT JOIN proy_proyectos p ON tp.id_proyecto = p.id_proyecto
      WHERE t.propietario = ?
    ");
    $stmt->execute([$idUsuario]);
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$fecha_hoy = date('Y-m-d');
$result = [
  'por_hacer' => [],
  'hoy' => [],
  'iniciadas' => [],
  'finalizadas' => [],
];

foreach ($tareas as $tarea) {
  $status = isset($tarea['status']) ? strtolower($tarea['status']) : '';
  $fecha_inicio = isset($tarea['fecha_inicio']) ? substr($tarea['fecha_inicio'], 0, 10) : '';
  if ($status === 'pendiente') {
    if ($fecha_inicio === $fecha_hoy) {
      $result['hoy'][] = $tarea;
    } else {
      $result['por_hacer'][] = $tarea;
    }
  } elseif ($status === 'en proceso') {
    $result['iniciadas'][] = $tarea;
  } elseif ($status === 'completada') {
    $result['finalizadas'][] = $tarea;
  }
}

function renderTarjetas($tareas) {
  if (empty($tareas)) {
    echo '<p class="text-muted">No hay tareas.</p>';
    return;
  }
  $idUsuario = $_SESSION['ID_COLAB'];
  foreach ($tareas as $tarea) {
    $id = (isset($tarea['id_tarea']) && is_numeric($tarea['id_tarea']) && $tarea['id_tarea'] > 0) ? $tarea['id_tarea'] : '';
    $status = isset($tarea['status']) ? strtolower($tarea['status']) : '';
    echo '<div class="card mb-2 shadow-sm" data-id="' . htmlspecialchars($id) . '">';
    echo '<div class="card-body p-2">';
    echo '<strong>' . (isset($tarea['asunto']) ? htmlspecialchars($tarea['asunto']) : 'Sin nombre') . '</strong><br>';
    echo '<span class="text-secondary">Responsable: ' . (isset($tarea['propietario_nombre']) ? htmlspecialchars($tarea['propietario_nombre']) : '') . ' ' . (isset($tarea['propietario_apellidos']) ? htmlspecialchars($tarea['propietario_apellidos']) : '') . '</span><br>';
    echo '<span class="text-secondary">Fecha: ' . (isset($tarea['fecha_inicio']) ? htmlspecialchars($tarea['fecha_inicio']) : 'Sin fecha') . '</span><br>';

    // Solo mostrar botones de acción si la tarea es del usuario logueado
    if (isset($tarea['propietario']) && $tarea['propietario'] == $idUsuario) {
      if ($status === 'pendiente') {
        echo '<button class="btn btn-success btn-sm mt-2 btn-iniciar-tarea w-100" data-id="' . htmlspecialchars($id) . '"><i class="fas fa-play"></i> Iniciar</button>';
      } elseif ($status === 'en proceso') {
        echo '<button class="btn btn-warning btn-sm mt-2 btn-finalizar-tarea w-100" data-id="' . htmlspecialchars($id) . '"><i class="fas fa-check"></i> Finalizar</button>';
      }
    }

    $esProyecto = !empty($tarea['nombre_proyecto']);
    $detalle = $esProyecto
      ? ($tarea['nombre_proyecto'] . ' - ' . (isset($tarea['asunto']) ? $tarea['asunto'] : ''))
      : (isset($tarea['detalle']) ? $tarea['detalle'] : (isset($tarea['detalles']) ? $tarea['detalles'] : ''));
    echo '<button class="btn btn-sm btn-secondary float-end" title="Ver tarea" data-bs-toggle="modal" data-bs-target="#modalTarea" '
        . 'onclick="window.cargarDatosTarea('
        . htmlspecialchars(json_encode($id)) . ','
        . htmlspecialchars(json_encode($tarea['asunto'])) . ','
        . htmlspecialchars(json_encode($tarea['fecha_inicio'])) . ','
        . htmlspecialchars(json_encode($tarea['fecha_ejecucion'] ?? '')) . ','
        . htmlspecialchars(json_encode($tarea['fecha_vencimiento'])) . ','
        . htmlspecialchars(json_encode($tarea['status'])) . ','
        . htmlspecialchars(json_encode($tarea['prioridad'])) . ','
        . htmlspecialchars(json_encode($detalle)) . ','
        . htmlspecialchars(json_encode($tarea['archivo_nombre'] ?? ''))
        . ');"' . '>'
        . '<i class="fas fa-eye"></i></button>';
    echo '</div></div>';
  }
}
?>
