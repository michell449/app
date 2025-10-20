<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$proyecto_id = $_GET['id'] ?? 1;
$db = new Database();
$conn = $db->getConnection();
$crud = new Crud($conn);
$crud->db_table = 'proy_proyectos';
$crud->id_param = $proyecto_id;
$crud->id_key = 'id_proyecto';
$crud->read();
$proyecto = $crud->data[0] ?? null;

// Obtener supervisor
$supervisor = '';
if ($proyecto) {
  $crud_colab = new Crud($conn);
  $crud_colab->db_table = 'sys_colaboradores';
  $crud_colab->id_param = $proyecto['supervisor'];
  $crud_colab->id_key = 'id_colab';
  $crud_colab->read();
  $colab = $crud_colab->data[0] ?? null;
  $supervisor = $colab ? $colab['nombre'] . ' ' . $colab['apellidos'] : 'Sin asignar';
}
?>

<?php if ($proyecto): ?>
<div class="card mb-3">
  <div class="card-body">
    <div class="mb-3">
      <div class="card bg-primary border-primary shadow-sm mb-0 w-100">
        <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
          <h3 class="text-white mb-0 w-100" style="font-weight: bold;"><i class="bi bi-folder2-open me-2"></i><?= htmlspecialchars($proyecto['nombre']) ?></h3>
          <button class="btn btn-warning btn-sm ms-3" data-bs-toggle="modal" data-bs-target="#modalEditarProyecto">Editar</button>
        </div>
      </div>
    </div>
    <div class="d-flex flex-column gap-3">
      <div class="form-floating">
        <input type="text" class="form-control" id="nombreProyectoView" value="<?= htmlspecialchars($proyecto['nombre']) ?>" readonly>
        <label for="nombreProyectoView">Nombre del Proyecto</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="fechaInicioView" value="<?= date('d.m.Y', strtotime($proyecto['fecha_inicio'])) ?>" readonly>
        <label for="fechaInicioView">Fecha de Inicio</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="fechaVencimientoView" value="<?= date('d.m.Y', strtotime($proyecto['fecha_vencimiento'])) ?>" readonly>
        <label for="fechaVencimientoView">Fecha de Vencimiento</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="prioridadView" value="<?= htmlspecialchars($proyecto['prioridad']) ?>" readonly>
        <label for="prioridadView">Prioridad</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="supervisorView" value="<?= htmlspecialchars($supervisor) ?>" readonly>
        <label for="supervisorView">Supervisor</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="avanceView" value="<?= htmlspecialchars($proyecto['avance']) ?>%" readonly>
        <label for="avanceView">Avance</label>
      </div>
      <div class="form-floating">
        <input type="text" class="form-control" id="statusView" value="<?= htmlspecialchars($proyecto['status']) ?>" readonly>
        <label for="statusView">Status</label>
      </div>
    </div>
  </div>
</div>
<!-- Modal Editar Proyecto -->
<div class="modal fade" id="modalEditarProyecto" tabindex="-1" aria-labelledby="modalEditarProyectoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="modalEditarProyectoLabel">Editar Proyecto</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formEditarProyecto" method="post" action="core/editar-proyecto.php">
        <div class="modal-body">
          <input type="hidden" name="id_proyecto" value="<?= $proyecto_id ?>">
          <div class="mb-3">
            <label for="nombreProyecto" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombreProyecto" name="nombreProyecto" value="<?= htmlspecialchars($proyecto['nombre']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= date('Y-m-d', strtotime($proyecto['fecha_inicio'])) ?>" required>
          </div>
          <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha vencimiento</label>
            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?= date('Y-m-d', strtotime($proyecto['fecha_vencimiento'])) ?>" required>
          </div>
          <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select class="form-control" id="prioridad" name="prioridad" required>
              <option value="Alta" <?= $proyecto['prioridad']=='Alta'?'selected':'' ?>>Alta</option>
              <option value="Media" <?= $proyecto['prioridad']=='Media'?'selected':'' ?>>Media</option>
              <option value="Baja" <?= $proyecto['prioridad']=='Baja'?'selected':'' ?>>Baja</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
              <option value="Activo" <?= $proyecto['status']=='Activo'?'selected':'' ?>>Activo</option>
              <option value="En pausa" <?= $proyecto['status']=='En pausa'?'selected':'' ?>>En pausa</option>
              <option value="Cancelado" <?= $proyecto['status']=='Cancelado'?'selected':'' ?>>Cancelado</option>
              <option value="Finalizado" <?= $proyecto['status']=='Finalizado'?'selected':'' ?>>Finalizado</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
