<?php
// Controlador para la página de minutas
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$db = (new Database())->getConnection();
$crud = new crud($db);

// Obtener lista de minutas
$crud->db_table = 'min_minutas';
$crud->read();
$minutas = $crud->data;

// Generar HTML de filas de la tabla
$minutas_html = '';
if ($minutas) {
    foreach ($minutas as $minuta) {
        $minutas_html .= '<tr>';
        $minutas_html .= '<td>' . htmlspecialchars($minuta['titulo']) . '</td>';
    $minutas_html .= '<td>' . htmlspecialchars($minuta['fecha']) . '</td>';
    $minutas_html .= '<td>' . htmlspecialchars($minuta['hora_inicio']) . '</td>';
        $minutas_html .= '<td>' . htmlspecialchars($minuta['lugar']) . '</td>';
        $minutas_html .= '<td>';
        $minutas_html .= '<button class="btn btn-sm btn-info" title="Editar"><i class="fas fa-edit"></i></button> ';
        $minutas_html .= '<button class="btn btn-sm btn-danger" title="Eliminar"><i class="fas fa-trash"></i></button>';
        $minutas_html .= '</td>';
        $minutas_html .= '</tr>';
    }
}
// Puedes agregar lógica para asistentes si lo necesitas
