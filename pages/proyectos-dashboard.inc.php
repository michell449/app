<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm mt-4" style="min-height: 400px;">
          <div class="card-header border-bottom-0 bg-primary">
            <div class="d-flex align-items-center" style="gap: 16px;">
              <h4 class="mb-0 text-white">Panel de proyectos</h4>
            </div>
          </div>
          <div class="px-3 pt-3">
            <button type="button" id="btnCrearProyecto" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearProyecto">
              <i class="fas fa-plus"></i> Crear proyecto
            </button>
          </div>

          <!-- Modal Crear Proyecto -->
          <div class="modal fade" id="modalCrearProyecto" tabindex="-1" aria-labelledby="modalCrearProyectoLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title" id="modalCrearProyectoLabel">Crear nuevo proyecto</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="formCrearProyecto" method="post">
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="nombreProyecto" class="form-label">Nombre del proyecto</label>
                      <input type="text" class="form-control" id="nombreProyecto" name="nombreProyecto" required>
                    </div>
                    <div class="mb-3">
                      <label for="id_equipo" class="form-label">Equipo</label>
                      <select class="form-select" id="id_equipo" name="id_equipo">
                        <option value="">Cargando equipos...</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="supervisorProyecto" class="form-label">Supervisor</label>
                      <select class="form-select" id="supervisorProyecto" name="supervisorProyecto" required>
                        <option value="">Cargando supervisores...</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="fechaInicio" class="form-label">Fecha inicio</label>
                      <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required>
                    </div>
                    <div class="mb-3">
                      <label for="fechaVencimiento" class="form-label">Fecha vencimiento</label>
                      <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento" required>
                    </div>
                    <div class="mb-3">
                      <label for="prioridadProyecto" class="form-label">Prioridad</label>
                      <select class="form-select" id="prioridadProyecto" name="prioridadProyecto" required>
                        <option value="">Seleccionar</option>
                        <option value="Alta">Alta</option>
                        <option value="Media">Media</option>
                        <option value="Baja">Baja</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label for="descripcionProyecto" class="form-label">Descripción</label>
                      <textarea class="form-control" id="descripcionProyecto" name="descripcionProyecto" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="avanceProyecto" class="form-label">Avance (%)</label>
                      <input type="number" class="form-control" id="avanceProyecto" name="avanceProyecto" min="0" max="100" value="0">
                    </div>
                    <div class="mb-3">
                      <label for="statusProyecto" class="form-label">Status</label>
                      <select class="form-select" id="statusProyecto" name="statusProyecto" required>
                        <option value="">Seleccionar</option>
                        <option value="Activo">Activo</option>
                        <option value="En Pausa">En Pausa</option>
                        <option value="Finalizado">Finalizado</option>
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar proyecto</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <div class="card-body">
            <?php include_once __DIR__ . '/../core/list-proyecto-dashboard.php'; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>