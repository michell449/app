<?php
// Tarjetas informativas
$boxData = [
    ['bg-info', 'ion ion-social-buffer', 'Total de proyectos', '#'],
    ['bg-info', 'ion ion-clipboard', 'Proyectos activos', '#'],
    ['bg-info', 'ion ion-person-stalker', 'Proyectos finalizados', '#'],
    ['bg-secondary', 'ion ion-ios-list', 'Total de tareas', '#'],
    ['bg-secondary', 'ion ion-checkmark-circled', 'Tareas completadas', '#'],
    ['bg-secondary', 'ion ion-close-circled', 'Tareas pendientes', '#'],
];
echo '<div class="row">';
for ($i = 0; $i < count($boxData); $i++) {
    list($color, $icon, $desc, $link) = $boxData[$i];
    echo '<div class="col-lg-4 col-6 mb-3">';
    echo '<div class="small-box ' . $color . '">';
    echo '<div class="inner">';
    echo '<h3 class="text-white">Ver</h3>';
    echo '<p class="text-white">' . $desc . '</p>';
    echo '</div>';
    echo '<div class="icon">';
    echo '<i class="' . $icon . '"></i>';
    echo '</div>';
    echo '<a href="' . $link . '" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>';
    echo '</div>';
    echo '</div>';
}
// end boxes

echo '</div>';
$chartTypes = [
    ['titulo' => 'Proyectos - Barras Horizontales', 'tipo' => 'horizontalBar'],
    ['titulo' => 'Proyectos - Barras Verticales', 'tipo' => 'bar'],
    ['titulo' => 'Proyectos - Pastel', 'tipo' => 'pie'],
    ['titulo' => 'Tareas - Barras Horizontales', 'tipo' => 'horizontalBar'],
    ['titulo' => 'Tareas - Líneas', 'tipo' => 'line'],
    ['titulo' => 'Tareas - Barras Verticales', 'tipo' => 'bar'],
];
// Charts not touched
echo '<div class="container-fluid">';
echo '<div class="row">';
for ($i = 0; $i < 4; $i++) {
    echo '<div class="col-md-6 mb-4">';
    echo '<div class="card">';
    echo '<div class="card-header">';
    echo $chartTypes[$i]['titulo'];
    echo '</div>';
    echo '<div class="card-body">';
    echo '<canvas id="chart' . $i . '" style="min-height:250px"></canvas>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    // Salto de fila cada dos charts
    if (($i + 1) % 2 == 0 && $i != 3) {
        echo '</div><div class="row">';
    }
}
echo '</div>';
