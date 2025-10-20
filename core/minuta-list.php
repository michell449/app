<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../core/minuta-controller.php';

// $minutas debe estar disponible desde minuta-controller.php
if (isset($minutas) && is_array($minutas)) {
    // Solo los campos principales
    $result = array_map(function($m) {
        return [
            'id_minuta' => $m['id_minuta'],
            'titulo' => $m['titulo'],
            'fecha' => $m['fecha'],
            'hora_inicio' => $m['hora_inicio'],
            'lugar' => $m['lugar']
        ];
    }, $minutas);
    echo json_encode($result);
} else {
    echo json_encode([]);
}
?>