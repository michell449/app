<?php require_once __DIR__ . '/../core/tareas-status.php'; ?>
<!-- Contenido principal -->
<section class="content">
  <div class="container-fluid">
    <!-- Tarjeta principal -->
    <div class="card">
      <div class="card-header bg-primary">
  <h1 class="card-title mb-0 text-white" style="font-size:2.2rem;font-weight:bold;">Lista de tareas</h1>
      </div>
      <div class="px-3 pt-3 pb-1">
  <?php if (isset($_SESSION['USR_TYPE']) && ($_SESSION['USR_TYPE'] == 1 || $_SESSION['USR_TYPE'] == 2)): ?>
    <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalAgregarTarea">
      <i class="fas fa-plus"></i> Agregar tarea
    </button>
  <?php endif; ?>
      </div>

  <!-- Botón Nueva Sección eliminado -->
      <div class="card-body">
        <!-- Contenedor único para todas las secciones de tareas -->
        <div class="row">
          <!-- Tarjeta Por hacer -->
          <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card card-primary h-100">
              <div class="card-header d-flex align-items-center">
                <span>Por hacer</span>
              </div>
              <div class="card-body p-2" style="max-height: 350px; overflow-y: auto;">
                <div id="tareas-por-hacer">
                  <?php renderTarjetas($result['por_hacer']); ?>
                </div>
              </div>
            </div>
          </div>
          <!-- Tarjeta Hoy -->
          <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card card-primary h-100">
              <div class="card-header d-flex align-items-center">
                <span>Hoy</span>
              </div>
              <div class="card-body p-2" style="max-height: 350px; overflow-y: auto;">
                <div id="tareas-hoy">
                  <?php renderTarjetas($result['hoy']); ?>
                </div>
              </div>
            </div>
          </div>
          <!-- Tarjeta Iniciadas -->
          <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card card-primary h-100">
              <div class="card-header d-flex align-items-center">
                <span>Iniciadas</span>
              </div>
              <div class="card-body p-2" style="max-height: 350px; overflow-y: auto;">
                      <div id="tareas-iniciadas">
                        <?php renderTarjetas($result['iniciadas']); ?>
                      </div>
              </div>
            </div>
          </div>
          <!-- Tarjeta Finalizadas -->
          <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card card-primary h-100">
              <div class="card-header d-flex align-items-center">
                <span>Finalizadas</span>
              </div>
              <div class="card-body p-2" style="max-height: 350px; overflow-y: auto;">
                <div id="tareas-finalizadas">
                  <?php renderTarjetas($result['finalizadas']); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>   
      </div>
    </div>
  </div>
</section>

<!-- Modal: Nueva sección -->
<div class="modal fade" id="modalSeccion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formSeccion">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">Agregar Nueva Sección</h5>
          <button type="button" class="close text-white" aria-label="Cerrar">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nombre de la sección</label>
            <input type="text" class="form-control" id="nombreSeccion" required>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default">Cancelar</button>
          <button type="submit" class="btn btn-success">Agregar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Agregar Tarea -->
<div class="modal fade" id="modalAgregarTarea" tabindex="-1" aria-labelledby="modalAgregarTareaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
  <h5 class="modal-title" id="modalAgregarTareaLabel">Agregar Tarea</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formAgregarTarea" method="post" action="core/agregar-tarea.php">
          <div class="modal-body">
            <div class="mb-3">
              <label for="propietario" class="form-label">Asignar colaborador</label>
              <select class="form-control" id="propietario" name="propietario" required>
                <option value="">Selecciona colaborador</option>
                <?php include __DIR__ . '/../core/listar-colaboradores-select.php'; ?>
              </select>
              <label for="asunto" class="form-label">Asunto</label>
              <input type="text" class="form-control" id="asunto" name="asunto" required>
            </div>
            <div class="mb-3">
              <label for="fecha_inicio" class="form-label">Fecha inicio</label>
              <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="mb-3">
              <!-- Fecha ejecución eliminada -->
            </div>
            <div class="mb-3">
              <label for="fecha_vencimiento" class="form-label">Fecha vencimiento</label>
              <input type="datetime-local" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
            </div>
            <div class="mb-3">
              <!-- Status eliminado -->
            </div>
            <div class="mb-3">
              <label for="prioridad" class="form-label">Prioridad</label>
              <select class="form-control" id="prioridad" name="prioridad" required>
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="detalles" class="form-label">Detalles</label>
              <textarea class="form-control" id="detalles" name="detalles"></textarea>
            </div>
            <div class="mb-3">
              <label for="id_proyecto" class="form-label">Proyecto</label>
              <select class="form-control" id="id_proyecto" name="id_proyecto">
                <option value="">Sin proyecto</option>
                <?php include __DIR__ . '/../core/listar-proyectos-select.php'; ?>
              </select>
            </div>
          </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal para ver tarea -->
<div class="modal fade" id="modalTarea" tabindex="-1" aria-labelledby="modalTareaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTareaLabel">Detalle de la Tarea</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="verTareaAsunto" class="form-label">Asunto</label>
          <input type="text" class="form-control" id="verTareaAsunto" readonly>
        </div>
        <div class="mb-3">
          <label for="verTareaPrioridad" class="form-label">Prioridad</label>
          <input type="text" class="form-control" id="verTareaPrioridad" readonly>
        </div>
        <div class="mb-3">
          <label for="verTareaEstado" class="form-label">Estado</label>
          <input type="text" class="form-control" id="verTareaEstado" readonly>
        </div>
        <div class="mb-3">
          <label for="verTareaDetalles" class="form-label">Detalle</label>
          <textarea class="form-control" id="verTareaDetalles" rows="3" readonly></textarea>
        </div>
        <div class="mb-3">
          <label for="verTareaFechaVencimiento" class="form-label">Fecha de vencimiento</label>
          <input type="text" class="form-control" id="verTareaFechaVencimiento" readonly>
        </div>
      </div>
      <div class="modal-footer">
       <!-- <button type="button" class="btn fw-bold text-white" id="btnEditarTarea" style="background:#ffc107;min-width:110px;">Editar</button>
        <button type="button" class="btn fw-bold text-white" id="btnGuardarTarea" style="background:#28a745;min-width:110px;display:none;">Guardar</button>
        <button type="button" class="btn fw-bold text-white" id="btnEliminarTarea" style="background:#dc3545;min-width:110px;">Eliminar</button>-->
        <button type="button" class="btn btn-outline-secondary" id="btnVolverTarea" style="min-width:90px;">Volver</button>
      </div>
    </div>
  </div>
</div>