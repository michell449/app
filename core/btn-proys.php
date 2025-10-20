<?php
echo '<div style="width:100%;">';
// Botón para mostrar/ocultar el formulario
echo '<button onclick="document.getElementById(\'formCrearProyecto\').style.display = (document.getElementById(\'formCrearProyecto\').style.display === \'none\' ? \'block\' : \'none\');" style="background:#28a745;color:#fff;padding:10px 24px;border:none;border-radius:16px;font-size:1.1rem;margin-bottom:12px;cursor:pointer;">+ Crear Proyecto</button>';

// Formulario de creación de proyecto (oculto por defecto)
echo '<div id="formCrearProyecto" style="display:none;margin-bottom:24px;border:1px solid #ccc;padding:16px;border-radius:12px;background:#f9f9f9;">';
echo '<form method="POST">';
echo '<h3 style="margin-top:0;color:#28a745;">Nuevo Proyecto</h3>';
echo '<div style="margin-bottom:8px;"><label>Nombre:</label><br><input type="text" name="nombre" required style="width:100%;padding:6px;"></div>';
echo '<div style="margin-bottom:8px;"><label>ID Equipo:</label><br><input type="text" name="id_equipo" required style="width:100%;padding:6px;"></div>';
echo '<div style="margin-bottom:8px;"><label>Supervisor:</label><br><input type="text" name="supervisor" required style="width:100%;padding:6px;"></div>';
echo '<div style="margin-bottom:8px;"><label>Fecha Inicio:</label><br><input type="date" name="fecha_inicio" required style="width:100%;padding:6px;"></div>';
echo '<div style="margin-bottom:8px;"><label>Fecha Vencimiento:</label><br><input type="date" name="fecha_vencimiento" required style="width:100%;padding:6px;"></div>';
echo '<div style="margin-bottom:8px;"><label>Prioridad:</label><br><input type="text" name="prioridad" required style="width:100%;padding:6px;"></div>';
echo '<div style="margin-bottom:8px;"><label>Descripción:</label><br><textarea name="descripcion" required style="width:100%;padding:6px;"></textarea></div>';
echo '<div style="margin-bottom:8px;"><label>Avance:</label><br><input type="number" name="avance" min="0" max="100" value="0" required style="width:100%;padding:6px;"></div>';
echo '<div style="margin-bottom:8px;"><label>Status:</label><br><select name="status" style="width:100%;padding:6px;"><option value="Activo">Activo</option><option value="Pausado">Pausado</option><option value="Finalizado">Finalizado</option></select></div>';
echo '<button type="submit" name="crear_proyecto" style="background:#007bff;color:#fff;padding:8px 20px;border:none;border-radius:10px;font-size:1rem;">Guardar</button>';
echo '</form>';
echo '</div>';
// ...existing code...
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$db = new Database();
$conn = $db->getConnection();


$crud = new Crud($conn);
$crud->db_table = 'proy_proyectos'; // Cambia al nombre real de la tabla de proyectos
// Procesar formulario de creación de proyecto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_proyecto'])) {
    $nuevoProyecto = [
        'nombre' => $_POST['nombre'],
        'id_equipo' => $_POST['id_equipo'],
        'supervisor' => $_POST['supervisor'],
        'fecha_inicio' => $_POST['fecha_inicio'],
        'fecha_vencimiento' => $_POST['fecha_vencimiento'],
        'prioridad' => $_POST['prioridad'],
        'descripcion' => $_POST['descripcion'],
        'avance' => $_POST['avance'],
        'status' => $_POST['status'],
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    $crud->data = $nuevoProyecto;
    if ($crud->create()) {
        // Redirigir para evitar doble guardado al recargar
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}
$crud->read();
$proyectos = $crud->data;
// Encabezado de la tabla
//
echo '<div style="width:100%;">';
echo '<style>';
// Botón para abrir el modal de creación de proyecto
echo '<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCrearProyecto" style="border-radius:16px;font-size:1rem;">Crear Proyecto</button>';

// Modal de formulario para crear proyecto
echo '<div class="modal fade" id="modalCrearProyecto" tabindex="-1" aria-labelledby="modalCrearProyectoLabel" aria-hidden="true">';
echo '  <div class="modal-dialog">';
echo '    <div class="modal-content">';
echo '      <form method="POST">';
echo '        <div class="modal-header">';
echo '          <h5 class="modal-title" id="modalCrearProyectoLabel">Crear Proyecto</h5>';
echo '          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
echo '        </div>';
echo '        <div class="modal-body">';
echo '          <div class="mb-2"><label>Nombre</label><input type="text" name="nombre" class="form-control" required></div>';
echo '          <div class="mb-2"><label>ID Equipo</label><input type="text" name="id_equipo" class="form-control" required></div>';
echo '          <div class="mb-2"><label>Supervisor</label><input type="text" name="supervisor" class="form-control" required></div>';
echo '          <div class="mb-2"><label>Fecha Inicio</label><input type="date" name="fecha_inicio" class="form-control" required></div>';
echo '          <div class="mb-2"><label>Fecha Vencimiento</label><input type="date" name="fecha_vencimiento" class="form-control" required></div>';
echo '          <div class="mb-2"><label>Prioridad</label><input type="text" name="prioridad" class="form-control" required></div>';
echo '          <div class="mb-2"><label>Descripción</label><textarea name="descripcion" class="form-control" required></textarea></div>';
echo '          <div class="mb-2"><label>Avance</label><input type="number" name="avance" class="form-control" min="0" max="100" value="0" required></div>';
echo '          <div class="mb-2"><label>Status</label><select name="status" class="form-control"><option value="Activo">Activo</option><option value="Pausado">Pausado</option><option value="Finalizado">Finalizado</option></select></div>';
echo '        </div>';
echo '        <div class="modal-footer">';
echo '          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>';
echo '          <button type="submit" name="crear_proyecto" class="btn btn-primary">Guardar</button>';
echo '        </div>';
echo '      </form>';
echo '    </div>';
echo '  </div>';
echo '</div>';
echo '  .table td, .table th { white-space: normal !important; word-break: break-word !important; vertical-align: middle; }';
echo '  .table { table-layout: auto !important; width:100% !important; }';
echo '  .table-responsive { overflow-x: unset !important; overflow-y: unset !important; }';
echo '</style>';
echo '<table class="table table-bordered table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>#</th>';
echo '<th>ID Proyecto</th>';
echo '<th>Nombre</th>';
echo '<th>ID Equipo</th>';
echo '<th>Supervisor</th>';
echo '<th>Fecha Inicio</th>';
echo '<th>Fecha Vencimiento</th>';
echo '<th>Prioridad</th>';
echo '<th>Descripción</th>';
echo '<th>Avance</th>';
echo '<th>Status</th>';
echo '<th>Actualizado</th>';
echo '<th>Acciones</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (is_array($proyectos) && count($proyectos) > 0) {
    $num = 1;
    foreach ($proyectos as $p) {
        echo '<tr>';
        echo '<td>' . $num++ . '</td>';
        echo '<td>' . htmlspecialchars($p['id_proyecto']) . '</td>';
        echo '<td>' . htmlspecialchars($p['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars($p['id_equipo']) . '</td>';
        echo '<td>' . htmlspecialchars($p['supervisor']) . '</td>';
        echo '<td>' . htmlspecialchars($p['fecha_inicio']) . '</td>';
        echo '<td>' . htmlspecialchars($p['fecha_vencimiento']) . '</td>';
        echo '<td>' . htmlspecialchars($p['prioridad']) . '</td>';
        echo '<td>' . htmlspecialchars($p['descripcion']) . '</td>';
        echo '<td>' . htmlspecialchars($p['avance']) . '</td>';
        echo '<td>' . htmlspecialchars($p['status']) . '</td>';
        echo '<td>' . htmlspecialchars($p['updated_at']) . '</td>';
    
    echo '<td>';
    echo '<div class="dropdown">';
    echo '<button class="btn btn-primary dropdown-toggle fw-bold" type="button" id="dropdownMenuButton'.$p['id_proyecto'].'" data-bs-toggle="dropdown" aria-expanded="false" style="min-width:120px;height:38px;border-radius:16px;font-size:1rem;">Acciones</button>';
    echo '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$p['id_proyecto'].'" style="min-width:220px;max-height:none;overflow-y:visible;">';
    echo '  <li>';
    echo '    <a class="dropdown-item" href="?pg=panel-Tareas&id_proyecto='.urlencode($p['id_proyecto']).'">';
    echo '      <i class="fas fa-eye text-primary mr-1"></i> <i class=""></i> Visualizar/Editar';
    echo '    </a>';
    echo '  </li>';
    echo '  <li>';
    echo '    <button type="button" class="dropdown-item btn-warning deshabilitar-proyecto" data-id_proyecto="'.htmlspecialchars($p['id_proyecto']).'" title="Pausar proyecto">';
    echo '      <i class="fas fa-ban text-warning mr-1"></i> Pausar';
    echo '    </button>';
    echo '  </li>';
    echo '  <li>';
    echo '    <button type="button" class="dropdown-item btn-success activar-proyecto" data-id_proyecto="'.htmlspecialchars($p['id_proyecto']).'" title="Activar proyecto">';
    echo '      <i class="fas fa-play text-success mr-1"></i> Activar';
    echo '    </button>';
    echo '  </li>';
    echo '  <li>';
    echo '    <button type="button" class="dropdown-item btn-danger finalizar-proyecto" data-id_proyecto="'.htmlspecialchars($p['id_proyecto']).'" title="Finalizar proyecto">';
    echo '      <i class="fas fa-check text-primary mr-1"></i> Finalizar';
    echo '    </button>';
    echo '  </li>';
    echo '</ul>';
    echo '</div>';
    echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="13" class="text-center">No hay proyectos registrados.</td></tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';