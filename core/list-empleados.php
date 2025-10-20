<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$db = new Database();
$conn = $db->getConnection();


$crud = new Crud($conn);
$crud->db_table = 'sys_colaboradores';
$crud->read();
$empleados = $crud->data;

// Encabezado de la tabla
echo '<div class="table-responsive">';
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Nombre</th>';
echo '<th>Apellidos</th>';
echo '<th>Correo</th>';
echo '<th>Teléfono</th>';
echo '<th>Departamento</th>';
echo '<th>Área</th>';
echo '<th>Acciones</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (is_array($empleados) && count($empleados) > 0) {
  foreach ($empleados as $e) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($e['nombre']) . '</td>';
    echo '<td>' . htmlspecialchars($e['apellidos']) . '</td>';
    echo '<td>' . htmlspecialchars($e['correo']) . '</td>';
    echo '<td>' . htmlspecialchars($e['telefono']) . '</td>';
    echo '<td>' . htmlspecialchars($e['departamento']) . '</td>';
    echo '<td>' . htmlspecialchars($e['area']) . '</td>';
    echo '<td>';
    echo '<div class="d-flex justify-content-center gap-2 flex-wrap">';
    echo '<button class="btn fw-bold text-white me-1" style="min-width:90px;height:38px;background:#17c9f7;border-radius:16px;font-size:1rem;" data-bs-toggle="modal" data-bs-target="#modalEmpleado" onclick="cargarDatosEmpleado(\'' . $e['id_colab'] . '\',\'' . htmlspecialchars($e['nombre'], ENT_QUOTES) . '\',\'' . htmlspecialchars($e['apellidos'], ENT_QUOTES) . '\',\'' . htmlspecialchars($e['correo'], ENT_QUOTES) . '\',\'' . htmlspecialchars($e['telefono'], ENT_QUOTES) . '\',\'' . htmlspecialchars($e['departamento'], ENT_QUOTES) . '\',\'' . htmlspecialchars($e['area'], ENT_QUOTES) . '\')" title="Ver">Ver</button>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
  }
} else {
  echo '<tr><td colspan="7" class="text-center">No hay empleados registrados.</td></tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';
