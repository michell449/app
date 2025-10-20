<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$db = new Database();
$conn = $db->getConnection();

$crud = new Crud($conn);
$crud->db_table = 'proy_proyectos'; // Cambia al nombre real de la tabla de proyectos


// Consulta condicional por id_proyecto
if (isset($_GET['id_proyecto']) && !empty($_GET['id_proyecto'])) {
    $crud->id_key = 'id_proyecto';
    $crud->id_param = $_GET['id_proyecto'];
    $crud->read();
    $proyectos = $crud->data;
} else {
    $crud->id_key = null;
    $crud->id_param = null;
    $crud->read();
    $proyectos = $crud->data;
}

// Procesar el formulario POST para actualizar una tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_tarea'])) {
    $id_tarea = $_POST['id_tarea'];
    $data = $_POST;
    unset($data['id_tarea']);
    require_once 'panelTareasController.php';
    $tareasController = new PanelTareasController($conn);
    // Filtrar solo los campos válidos para proy_tareas
    $camposTarea = [
        'asunto', 'fecha_inicio', 'fecha_ejecucion', 'fecha_vencimiento', 'status', 'prioridad', 'porcentaje',
        'aviso', 'programar', 'propietario', 'tipo_repeticion', 'detalles', 'accion', 'id_cita'
    ];
    $dataTarea = array_filter(
        $data,
        function($key) use ($camposTarea) { return in_array($key, $camposTarea); },
        ARRAY_FILTER_USE_KEY
    );
    $tareasController->updateTarea($id_tarea, $dataTarea);
}

// Procesar el formulario POST para actualizar el proyecto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_proyecto'])) {
    $id_proyecto = $_POST['id_proyecto'];
    $data = $_POST;
    unset($data['id_proyecto']);
    if (isset($data['nueva_tarea'])) {
        unset($data['nueva_tarea']);
    }
    require_once 'panelTareasController.php';
    $tareasController = new PanelTareasController($conn);
    if (method_exists($tareasController, 'updateProyecto')) {
        // Filtrar solo los campos válidos para proy_proyectos
        $camposProyecto = [
            'nombre', 'id_equipo', 'supervisor', 'fecha_inicio', 'fecha_vencimiento',
            'prioridad', 'avance', 'status', 'updated_at', 'descripcion'
        ];
        $dataProyecto = array_filter(
            $data,
            function($key) use ($camposProyecto) { return in_array($key, $camposProyecto); },
            ARRAY_FILTER_USE_KEY
        );
        $tareasController->updateProyecto($id_proyecto, $dataProyecto);
        // Opcional: recargar datos del proyecto
        $crud->read();
        $proyectos = $crud->data;
    }
}

// Diseño tipo tarjeta para cada proyecto
if (is_array($proyectos) && count($proyectos) > 0) {
    $p = $proyectos[0];
    echo '<div class="card mb-4 shadow-sm">';
    echo '  <div class="card-header bg-primary text-white">';
    echo '    <h5 class="m-0">Proyecto: ' . htmlspecialchars($p['nombre']) . ' (ID: ' . htmlspecialchars($p['id_proyecto']) . ')</h5>';
    echo '  </div>';
    echo '  <div class="card-body">';
    // Formulario de edición de proyecto (oculto por defecto)
    echo '<div style="height:24px;"></div>';
    echo '<form id="formEditarProyecto" class="row g-3 d-none" method="POST" action="" style="margin-top:64px;">';
    echo '<input type="hidden" name="id_proyecto" value="' . htmlspecialchars($p['id_proyecto']) . '">';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">ID Equipo</label>';
    echo '    <input type="text" class="form-control" name="id_equipo" value="' . htmlspecialchars($p['id_equipo']) . '">';
    echo '  </div>';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">Supervisor</label>';
    echo '    <input type="text" class="form-control" name="supervisor" value="' . htmlspecialchars($p['supervisor']) . '">';
    echo '  </div>';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">Fecha Inicio</label>';
    echo '    <input type="date" class="form-control" name="fecha_inicio" value="' . htmlspecialchars($p['fecha_inicio']) . '">';
    echo '  </div>';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">Fecha Vencimiento</label>';
    echo '    <input type="date" class="form-control" name="fecha_vencimiento" value="' . htmlspecialchars($p['fecha_vencimiento']) . '">';
    echo '  </div>';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">Prioridad</label>';
    echo '    <input type="text" class="form-control" name="prioridad" value="' . htmlspecialchars($p['prioridad']) . '">';
    echo '  </div>';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">Avance (%)</label>';
    echo '    <input type="number" class="form-control" name="avance" value="' . htmlspecialchars($p['avance']) . '" min="0" max="100">';
    echo '  </div>';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">Status</label>';
    echo '    <input type="text" class="form-control" name="status" value="' . htmlspecialchars($p['status']) . '">';
    echo '  </div>';
    echo '  <div class="col-md-6">';
    echo '    <label class="form-label fw-bold">Actualizado</label>';
    echo '    <input type="text" class="form-control" name="updated_at" value="' . htmlspecialchars($p['updated_at']) . '" readonly>';
    echo '  </div>';
    echo '  <div class="col-12">';
    echo '    <label class="form-label fw-bold">Descripción</label>';
    echo '    <textarea class="form-control" name="descripcion" rows="3">' . htmlspecialchars($p['descripcion']) . '</textarea>';
    echo '  </div>';
    echo '  <div class="col-12 d-flex justify-content-end">';
    echo '    <button type="submit" class="btn btn-success">Guardar cambios</button>';
    echo '    <button type="button" class="btn btn-secondary ms-2" id="btnCancelarEdicion">Cancelar</button>';
    echo '  </div>';
    echo '</form>';

    // Vista normal (no editable)
    echo '<div style="height:24px;"></div>';
    echo '<form id="formDatosProyecto" class="row g-3" style="margin-top:64px;">';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">ID Equipo</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['id_equipo']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">Supervisor</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['supervisor']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">Fecha Inicio</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['fecha_inicio']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">Fecha Vencimiento</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['fecha_vencimiento']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">Prioridad</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['prioridad']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">Avance (%)</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['avance']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">Status</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['status']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-md-6">';
    echo '        <label class="form-label fw-bold">Actualizado</label>';
    echo '        <input type="text" class="form-control" value="' . htmlspecialchars($p['updated_at']) . '" disabled>';
    echo '      </div>';
    echo '      <div class="col-12">';
    echo '        <label class="form-label fw-bold">Descripción</label>';
    echo '        <textarea class="form-control" rows="3" disabled>' . htmlspecialchars($p['descripcion']) . '</textarea>';
    echo '      </div>';
    echo '    </form>';
    echo '    <div class="mt-3">';
    echo '      <div class="dropdown">';
    echo '        <button class="btn btn-primary dropdown-toggle fw-bold" type="button" id="dropdownMenuButton'.$p['id_proyecto'].'" data-bs-toggle="dropdown" aria-expanded="false" style="min-width:200px;min-height:64px;border-radius:16px;font-size:1.4rem;display:flex;align-items:center;justify-content:center;gap:14px;">Acciones</button>';
    echo '        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$p['id_proyecto'].'" style="min-width:220px;max-height:none;overflow-y:visible;">';
    echo '          <li>';
    echo '            <a class="dropdown-item" href="#" id="btnEditarProyecto">';
    echo '              <i class=""></i> <i class=""></i> Editar Proyecto';
    echo '            </a>';
    echo '          </li>';
    echo '        </ul>';
    echo '      </div>';
    echo '    </div>';
        
    // Botones debajo de la tabla
    echo '<div class="d-flex justify-content-center gap-3 mt-4">';
        echo '  <button id="btnListaTareas" class="btn btn-primary" type="button" style="border-radius:16px;min-width:200px;min-height:64px;font-size:1.4rem;display:flex;align-items:center;justify-content:center;gap:14px;">';
        echo '    <i class="fas fa-list" style="font-size:1.7rem;"></i> Lista';
        echo '  </button>';
        echo '  <button id="btnTablero" class="btn btn-primary" type="button" style="border-radius:16px;min-width:200px;min-height:64px;font-size:1.4rem;display:flex;align-items:center;justify-content:center;gap:14px;">';
        echo '    <i class="fas fa-th-large" style="font-size:1.7rem;"></i> Tablero';
        echo '  </button>';
        // echo '  <button class="btn btn-info" style="border-radius:16px;min-width:100px;display:flex;align-items:center;gap:8px;">';
        // echo '    <i class="fas fa-clock"></i> Cronograma';
        // echo '  </button>';
        // echo '  <button class="btn btn-danger" style="border-radius:16px;min-width:100px;display:flex;align-items:center;gap:8px;">';
        // echo '    <i class="fas fa-columns"></i> Panel';
        // echo '  </button>';
        // echo '  <button class="btn btn-success" style="border-radius:16px;min-width:100px;display:flex;align-items:center;gap:8px;">';
        // echo '    <i class="fas fa-sticky-note"></i> Notas';
        // echo '  </button>';
        echo '</div>';
        
    // Mostrar la lista de tareas relacionadas al proyecto actual (oculta por defecto)
        require_once 'panelTareasController.php';
    $tareasController = new PanelTareasController();
    $tareasProyecto = $tareasController->getTareasPorProyecto($p['id_proyecto']);

        // Tabla de tareas (restaurada y funcional)
    echo '<div style="height:24px;"></div>';
    echo '<div id="listaTareasProyecto" style="display:block; margin-top:30px;">';
        echo '<h4 class="fw-bold mb-3">Tareas del proyecto</h4>';
        if (count($tareasProyecto) > 0) {
            $camposLista = [
                'Asunto' => 'asunto',
                'Fecha Inicio' => 'fecha_inicio',
                'Fecha Vencimiento' => 'fecha_vencimiento',
                'Status' => 'status',
                'Prioridad' => 'prioridad',
                'Porcentaje' => 'porcentaje'
            ];
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered align-middle">';
            echo '<thead class="table-light">';
            echo '<tr>';
            foreach ($camposLista as $label => $key) {
                echo '<th>' . $label . '</th>';
            }
            echo '<th>Acciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($tareasProyecto as $idx => $tarea) {
                echo '<tr>';
                foreach ($camposLista as $label => $key) {
                    $valor = isset($tarea[$key]) ? htmlspecialchars($tarea[$key]) : '';
                    echo '<td>' . $valor . '</td>';
                }
                echo '<td class="text-center">';
                echo '<button class="btn btn-outline-info btn-sm me-1" type="button" data-bs-toggle="collapse" data-bs-target="#tareaDetalle' . $idx . '" aria-expanded="false" aria-controls="tareaDetalle' . $idx . '">Ver</button>';
                echo '<button class="btn btn-outline-warning btn-sm" type="button" onclick="document.getElementById(\'tareaEditar' . $idx . '\').classList.toggle(\'d-none\')">Editar</button>';
                echo '</td>';
                echo '</tr>';
                // Fila de detalles ocultos (Ver)
                echo '<tr class="collapse" id="tareaDetalle' . $idx . '">';
                echo '<td colspan="' . (count($camposLista)+1) . '">';
                echo '<div class="p-3 bg-light rounded">';
                $camposDetalle = [
                    'Fecha Ejecución' => 'fecha_ejecucion',
                    'Aviso' => 'aviso',
                    'Programar' => 'programar',
                    'Propietario' => 'propietario',
                    'Tipo Repetición' => 'tipo_repeticion',
                    'Acción' => 'accion',
                    'Detalles' => 'detalles'
                ];
                echo '<div class="row g-3">';
                $i = 0;
                foreach ($camposDetalle as $label => $key) {
                    $valor = isset($tarea[$key]) ? $tarea[$key] : '';
                    if ($key === 'detalles') {
                        $valor = nl2br(htmlspecialchars($valor));
                    } else {
                        $valor = htmlspecialchars($valor);
                    }
                    if ($i % 2 === 0) echo '<div class="row">';
                    echo '<div class="col-md-6 mb-2">';
                    echo '<label class="form-label fw-bold">' . $label . '</label>';
                    if ($key === 'detalles') {
                        echo '<textarea class="form-control" rows="2" readonly>' . strip_tags($valor) . '</textarea>';
                    } else {
                        echo '<input type="text" class="form-control" value="' . $valor . '" readonly>';
                    }
                    echo '</div>';
                    if ($i % 2 === 1) echo '</div>';
                    $i++;
                }
                if ($i % 2 === 1) echo '</div>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
                // Fila de edición oculta
                echo '<tr id="tareaEditar' . $idx . '" class="d-none">';
                echo '<td colspan="' . (count($camposLista)+1) . '">';
                $actionUrl = htmlspecialchars($_SERVER['REQUEST_URI']);
                echo '<form class="row g-3" method="POST" action="' . $actionUrl . '">';
                echo '<input type="hidden" name="id_tarea" value="' . htmlspecialchars($tarea['id_tarea']) . '">';
                $i = 0;
                foreach ($camposDetalle as $label => $key) {
                    $valor = isset($tarea[$key]) ? htmlspecialchars($tarea[$key]) : '';
                    if ($i % 2 === 0) echo '<div class="row">';
                    echo '<div class="col-md-6 mb-2">';
                    echo '<label class="form-label fw-bold">' . $label . '</label>';
                    if ($key === 'detalles') {
                        echo '<textarea class="form-control" name="' . $key . '" rows="2">' . $valor . '</textarea>';
                    } else {
                        echo '<input type="text" class="form-control" name="' . $key . '" value="' . $valor . '">';
                    }
                    echo '</div>';
                    if ($i % 2 === 1) echo '</div>';
                    $i++;
                }
                if ($i % 2 === 1) echo '</div>';
                echo '<div class="d-flex justify-content-end mt-3">';
                echo '<button type="submit" class="btn btn-success btn-sm">Guardar cambios</button>';
                echo '</div>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-info">No hay tareas para este proyecto.</div>';
        }
        echo '</div>';

        // Tablero tipo Kanban
    echo '<div style="height:24px;"></div>';
    echo '<div id="tableroTareasProyecto" style="display:none; margin-top:64px;">';
        echo '<h4 class="fw-bold mb-3">Tablero de tareas</h4>';
        $estados = ["Pendiente", "En Proceso", "Completada"];
        echo '<div class="row">';
        foreach ($estados as $estado) {
            echo '<div class="col-md-4">';
            echo '<div class="card">';
            echo '<div class="card-header bg-secondary text-white">' . $estado . '</div>';
            echo '<div class="card-body" style="min-height:200px;">';
            foreach ($tareasProyecto as $tarea) {
                if (isset($tarea['status']) && $tarea['status'] === $estado) {
                    echo '<div class="card mb-2">';
                    echo '<div class="card-body">';
                    echo '<h6 class="fw-bold">' . htmlspecialchars($tarea['asunto']) . '</h6>';
                    echo '<div><small>Responsable: ' . htmlspecialchars($tarea['propietario']) . '</small></div>';
                    echo '<div><small>Vence: ' . htmlspecialchars($tarea['fecha_vencimiento']) . '</small></div>';
                    echo '<div><small>Prioridad: ' . htmlspecialchars($tarea['prioridad']) . '</small></div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';

        // Script para alternar entre tabla y tablero
    echo '<script>';
    echo 'document.getElementById("btnListaTareas").addEventListener("click", function() {';
    echo '  document.getElementById("listaTareasProyecto").style.display = "block";';
    echo '  document.getElementById("tableroTareasProyecto").style.display = "none";';
    echo '});';
    echo 'document.getElementById("btnTablero").addEventListener("click", function() {';
    echo '  document.getElementById("listaTareasProyecto").style.display = "none";';
    echo '  document.getElementById("tableroTareasProyecto").style.display = "block";';
    echo '});';
    echo 'document.getElementById("btnEditarProyecto").addEventListener("click", function(e) {';
    echo '  e.preventDefault();';
    echo '  document.getElementById("formDatosProyecto").classList.add("d-none");';
    echo '  document.getElementById("formEditarProyecto").classList.remove("d-none");';
    echo '});';
    echo 'document.getElementById("btnCancelarEdicion").addEventListener("click", function() {';
    echo '  document.getElementById("formEditarProyecto").classList.add("d-none");';
    echo '  document.getElementById("formDatosProyecto").classList.remove("d-none");';
    echo '});';
    echo '</script>';
    echo '  </div>';
    echo '</div>';
}
else {
        echo '<div class="alert alert-warning">No hay proyectos registrados.</div>';
}