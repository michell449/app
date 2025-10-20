<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

// Inicializar conexión y CRUD solo si no existen
if (!isset($db)) {
  $db = new Database();
}
if (!isset($conn)) {
  $conn = $db->getConnection();
}
$idColab = $_SESSION['ID_COLAB'];
$idPerfil = $_SESSION['USR_TYPE'];
$status = isset($_GET['status']) ? $_GET['status'] : null;

// Construir consulta según perfil y status
$whereStatus = $status ? " AND p.status = :status" : "";

if ($idPerfil == 1 || $idPerfil == 2) {
    // Admin/supervisor: todos los proyectos, con filtro opcional
    $sql = "SELECT DISTINCT p.* FROM proy_proyectos p WHERE 1" . $whereStatus;
    $params = [];
    if ($status) $params['status'] = $status;
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Usuario: solo proyectos donde participa, con filtro opcional
    $sql = "
        SELECT DISTINCT p.* 
        FROM proy_proyectos p
        LEFT JOIN proy_equiposcolab ec ON p.id_equipo = ec.id_equipo
        LEFT JOIN proy_tareasproyectos tp ON p.id_proyecto = tp.id_proyecto
        LEFT JOIN proy_tareas t ON tp.id_tarea = t.id_tarea
        WHERE (p.supervisor = :idColab OR ec.id_colab = :idColab OR t.propietario = :idColab)" . $whereStatus;
    $params = ['idColab' => $idColab];
    if ($status) $params['status'] = $status;
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener todos los colaboradores para mapear id_colab => nombre completo
if (!isset($colaboradores) || !isset($supervisores)) {
  $crud_colab = new Crud($conn);
  $crud_colab->db_table = 'sys_colaboradores';
  $crud_colab->read();
  $colaboradores = $crud_colab->data;
  $supervisores = [];
  if (is_array($colaboradores)) {
    foreach ($colaboradores as $c) {
      $supervisores[$c['id_colab']] = $c['nombre'] . ' ' . $c['apellidos'];
    }
  }
}

// Obtener todos los equipos para mapear id_equipo => nombre
if (!isset($equipos) || !isset($nombre_equipos)) {
  $crud_equipos = new Crud($conn);
  $crud_equipos->db_table = 'proy_equipos';
  $crud_equipos->read();
  $equipos = $crud_equipos->data;
  $nombre_equipos = [];
  if (is_array($equipos)) {
    foreach ($equipos as $e) {
      $nombre_equipos[$e['id_equipo']] = $e['nombre'];
    }
  }
}

// Encabezado de la tabla
echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>Equipo</th>';
echo '<th>Supervisor</th>';
echo '<th>Fecha Inicio</th>';
echo '<th>Fecha Vencimiento</th>';
echo '<th>Prioridad</th>';
echo '<th>Avance</th>';
echo '<th>Estado</th>';
echo '<th>Acciones</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (is_array($proyectos) && count($proyectos) > 0) {
  foreach ($proyectos as $p) {
    // Calcular el avance promedio de las tareas del proyecto
    $stmt = $conn->prepare("
        SELECT AVG(t.porcentaje) AS avance
        FROM proy_tareasproyectos tp
        JOIN proy_tareas t ON tp.id_tarea = t.id_tarea
        WHERE tp.id_proyecto = ?
    ");
    $stmt->execute([$p['id_proyecto']]);
    $avance = $stmt->fetchColumn();
    $avance = $avance ? round($avance, 2) : 0;

    // Actualizar el campo 'avance' en la tabla proy_proyectos
    $update = $conn->prepare("UPDATE proy_proyectos SET avance = ? WHERE id_proyecto = ?");
    $update->execute([$avance, $p['id_proyecto']]);

    // Si el avance es 100, actualizar el status del proyecto a 'Finalizado'
    if ($avance == 100 && $p['status'] !== 'Finalizado') {
        $updateStatus = $conn->prepare("UPDATE proy_proyectos SET status = 'Finalizado' WHERE id_proyecto = ?");
        $updateStatus->execute([$p['id_proyecto']]);
        $p['status'] = 'Finalizado';
    }

    echo '<tr>';
    echo '<td>' . htmlspecialchars($p['nombre']) . '</td>';
    // Mostrar nombre del equipo
    $nombre_equipo = isset($nombre_equipos[$p['id_equipo']]) ? htmlspecialchars($nombre_equipos[$p['id_equipo']]) : 'Sin equipo';
    echo '<td>' . $nombre_equipo . '</td>';
    // Mostrar nombre completo del supervisor
    $nombre_supervisor = isset($supervisores[$p['supervisor']]) ? htmlspecialchars($supervisores[$p['supervisor']]) : 'Sin asignar';
    echo '<td>' . $nombre_supervisor . '</td>';
    echo '<td>' . htmlspecialchars($p['fecha_inicio']) . '</td>';
    echo '<td>' . htmlspecialchars($p['fecha_vencimiento']) . '</td>';
    echo '<td>' . htmlspecialchars($p['prioridad']) . '</td>';
    echo '<td>' . $avance . '%</td>'; // Mostrar avance calculado
    echo '<td>' . htmlspecialchars($p['status']) . '</td>';
    echo '<td>';
    echo '<div class="d-flex justify-content-center gap-2 flex-wrap">';
    echo '<a href="panel?pg=ver-proy&id=' . $p['id_proyecto'] . '" class="btn fw-bold text-white me-1" style="min-width:90px;height:38px;background:#17c9f7;border-radius:16px;font-size:1rem;" title="Ver">Ver</a>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="9" class="text-center">No hay proyectos disponibles.</td></tr>';
}

echo '</tbody>';
echo '</table>';  
echo '</div>';