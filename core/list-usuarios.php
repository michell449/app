<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$db = new Database();
$conn = $db->getConnection();

$crud = new Crud($conn);
$crud->db_table = 'us_usuarios';
$crud->read();
$usuario = $crud->data;

// Encabezado de la tabla
echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>Apellido</th>';
echo '<th>Email</th>';
echo '<th>Teléfono</th>';
echo '<th>Status</th>';
echo '<th>Acciones</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (is_array($usuario) && count($usuario) > 0) {
  foreach ($usuario as $a) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($a['nombre']) . '</td>';
    echo '<td>' . htmlspecialchars($a['apellido']) . '</td>';
    echo '<td>' . htmlspecialchars($a['email']) . '</td>';
    echo '<td>' . htmlspecialchars($a['telefono']) . '</td>';
    // Mostrar status como texto
    $statusText = ($a['status'] == 1) ? 'Activo' : 'Inactivo';
    echo '<td>' . $statusText . '</td>';
    echo '<td>';
    echo '<div class="d-flex justify-content-center gap-2 flex-wrap">';
    echo '<button class="btn fw-bold text-white me-1" style="min-width:90px;height:38px;background:#17c9f7;border-radius:16px;font-size:1rem;" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="cargarDatosUsuario(\'' . $a['id_usuario'] . '\',\'' . htmlspecialchars($a['nombre'], ENT_QUOTES) . '\',\'' . htmlspecialchars($a['apellido'], ENT_QUOTES) . '\',\'' . htmlspecialchars($a['email'], ENT_QUOTES) . '\',\'' . htmlspecialchars($a['telefono'], ENT_QUOTES) . '\',\'' . $a['status'] . '\',\'' . $a['id_perfil'] . '\')" title="Ver">Ver</button>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="5" class="text-center">No hay usuarios registrados.</td></tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';
