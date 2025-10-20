<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();
$idColab = $_SESSION['ID_COLAB'];
$idPerfil = $_SESSION['USR_TYPE'];
$status = isset($_GET['status']) ? $_GET['status'] : null;

// Tarjetas y datos para gráficas
if ($idPerfil == 1 || $idPerfil == 2) {
    // Admin/supervisor: todos los datos
    $totalProy = $conn->query("SELECT COUNT(*) FROM proy_proyectos")->fetchColumn();
    $activosProy = $conn->query("SELECT COUNT(*) FROM proy_proyectos WHERE status='Activo'")->fetchColumn();
    $finalProy = $conn->query("SELECT COUNT(*) FROM proy_proyectos WHERE status='Finalizado'")->fetchColumn();
    $totalTareas = $conn->query("SELECT COUNT(*) FROM proy_tareas")->fetchColumn();
    $tareasCompletadas = $conn->query("SELECT COUNT(*) FROM proy_tareas WHERE status='Completada'")->fetchColumn();
    $tareasPendientes = $conn->query("SELECT COUNT(*) FROM proy_tareas WHERE status='Pendiente'")->fetchColumn();

    // Datos para gráficas
    $proyPorEstado = $conn->query("
        SELECT status, COUNT(*) as total
        FROM proy_proyectos
        GROUP BY status
    ")->fetchAll(PDO::FETCH_ASSOC);

    $tareasPorEstado = $conn->query("
        SELECT status, COUNT(*) as total
        FROM proy_tareas
        GROUP BY status
    ")->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Usuario: solo datos relacionados
    $totalProy = $conn->prepare("
        SELECT COUNT(DISTINCT p.id_proyecto)
        FROM proy_proyectos p
        LEFT JOIN proy_equiposcolab ec ON p.id_equipo = ec.id_equipo
        LEFT JOIN proy_tareasproyectos tp ON p.id_proyecto = tp.id_proyecto
        LEFT JOIN proy_tareas t ON tp.id_tarea = t.id_tarea
        WHERE p.supervisor = ? OR ec.id_colab = ? OR t.propietario = ?
    ");
    $totalProy->execute([$idColab, $idColab, $idColab]);
    $totalProy = $totalProy->fetchColumn();

    $activosProy = $conn->prepare("
        SELECT COUNT(DISTINCT p.id_proyecto)
        FROM proy_proyectos p
        LEFT JOIN proy_equiposcolab ec ON p.id_equipo = ec.id_equipo
        LEFT JOIN proy_tareasproyectos tp ON p.id_proyecto = tp.id_proyecto
        LEFT JOIN proy_tareas t ON tp.id_tarea = t.id_tarea
        WHERE (p.supervisor = ? OR ec.id_colab = ? OR t.propietario = ?) AND p.status='Activo'
    ");
    $activosProy->execute([$idColab, $idColab, $idColab]);
    $activosProy = $activosProy->fetchColumn();

    $finalProy = $conn->prepare("
        SELECT COUNT(DISTINCT p.id_proyecto)
        FROM proy_proyectos p
        LEFT JOIN proy_equiposcolab ec ON p.id_equipo = ec.id_equipo
        LEFT JOIN proy_tareasproyectos tp ON p.id_proyecto = tp.id_proyecto
        LEFT JOIN proy_tareas t ON tp.id_tarea = t.id_tarea
        WHERE (p.supervisor = ? OR ec.id_colab = ? OR t.propietario = ?) AND p.status='Finalizado'
    ");
    $finalProy->execute([$idColab, $idColab, $idColab]);
    $finalProy = $finalProy->fetchColumn();

    $totalTareas = $conn->prepare("SELECT COUNT(*) FROM proy_tareas WHERE propietario = ?");
    $totalTareas->execute([$idColab]);
    $totalTareas = $totalTareas->fetchColumn();

    $tareasCompletadas = $conn->prepare("SELECT COUNT(*) FROM proy_tareas WHERE propietario = ? AND status='Completada'");
    $tareasCompletadas->execute([$idColab]);
    $tareasCompletadas = $tareasCompletadas->fetchColumn();

    $tareasPendientes = $conn->prepare("SELECT COUNT(*) FROM proy_tareas WHERE propietario = ? AND status='Pendiente'");
    $tareasPendientes->execute([$idColab]);
    $tareasPendientes = $tareasPendientes->fetchColumn();

    // Datos para gráficas
    $proyPorEstado = $conn->prepare("
        SELECT p.status, COUNT(DISTINCT p.id_proyecto) as total
        FROM proy_proyectos p
        LEFT JOIN proy_equiposcolab ec ON p.id_equipo = ec.id_equipo
        LEFT JOIN proy_tareasproyectos tp ON p.id_proyecto = tp.id_proyecto
        LEFT JOIN proy_tareas t ON tp.id_tarea = t.id_tarea
        WHERE p.supervisor = :idColab OR ec.id_colab = :idColab OR t.propietario = :idColab
        GROUP BY p.status
    ");
    $proyPorEstado->execute(['idColab' => $idColab]);
    $proyPorEstado = $proyPorEstado->fetchAll(PDO::FETCH_ASSOC);

    $tareasPorEstado = $conn->prepare("
        SELECT status, COUNT(*) as total
        FROM proy_tareas
        WHERE propietario = :idColab
        GROUP BY status
    ");
    $tareasPorEstado->execute(['idColab' => $idColab]);
    $tareasPorEstado = $tareasPorEstado->fetchAll(PDO::FETCH_ASSOC);
}

// Tarjetas informativas
$boxData = [
    ['bg-info', 'ion ion-social-buffer', 'Total de proyectos', $totalProy, 'panel?pg=proyectos-dashboard'],
    ['bg-info', 'ion ion-clipboard', 'Proyectos activos', $activosProy, 'panel?pg=proyectos-dashboard&status=Activo'],
    ['bg-info', 'ion ion-person-stalker', 'Proyectos finalizados', $finalProy, 'panel?pg=proyectos-dashboard&status=Finalizado'],
    ['bg-secondary', 'ion ion-ios-list', 'Total de tareas', $totalTareas, 'panel?pg=tareas-dashboard'],
    ['bg-secondary', 'ion ion-checkmark-circled', 'Tareas completadas', $tareasCompletadas, 'panel?pg=tareas-dashboard&status=Completada'],
    ['bg-secondary', 'ion ion-close-circled', 'Tareas pendientes', $tareasPendientes, 'panel?pg=tareas-dashboard&status=Pendiente'],
];
echo '<div class="row">';
for ($i = 0; $i < count($boxData); $i++) {
    list($color, $icon, $desc, $valor, $link) = $boxData[$i];
    echo '<div class="col-lg-4 col-6 mb-3">';
    echo '<div class="small-box ' . $color . '">';
    echo '<div class="inner">';
    echo '<h3 class="text-white">' . $valor . '</h3>';
    echo '<p class="text-white">' . $desc . '</p>';
    echo '</div>';
    echo '<div class="icon">';
    echo '<i class="' . $icon . '"></i>';
    echo '</div>';
    echo '<a href="' . $link . '" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>';
    echo '</div>';
    echo '</div>';
}
echo '</div>';

// Preparar datos para gráficas
$labelsProy = [];
$valuesProy = [];
foreach ($proyPorEstado as $row) {
    $labelsProy[] = $row['status'];
    $valuesProy[] = $row['total'];
}

$labelsTareas = [];
$valuesTareas = [];
foreach ($tareasPorEstado as $row) {
    $labelsTareas[] = $row['status'];
    $valuesTareas[] = $row['total'];
}

// Gráficas
$chartTypes = [
    ['titulo' => 'Proyectos - Barras Horizontales', 'tipo' => 'horizontalBar'],
    ['titulo' => 'Proyectos - Barras Verticales', 'tipo' => 'bar'],
    ['titulo' => 'Proyectos - Pastel', 'tipo' => 'pie'],
    ['titulo' => 'Tareas - Barras Horizontales', 'tipo' => 'horizontalBar'],
];
echo '<div class="container-fluid">';
echo '<div class="row">';
for ($i = 0; $i < 4; $i++) {
    echo '<div class="col-md-6 mb-4">';
    echo '<div class="card">';
    echo '<div class="card-header">';
    echo $chartTypes[$i]['titulo'];
    echo '</div>';
    echo '<div class="card-body">';
    echo '<canvas id="chart' . $i . '" style="width:100%;min-height:250px;max-height:250px;"></canvas>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    if (($i + 1) % 2 == 0 && $i != 3) {
        echo '</div><div class="row">';
    }
}
echo '</div>'; // Cierra la última fila
?>