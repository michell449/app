<?php require_once __DIR__ . '/../core/ver-proy-controller.php'; ?>
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-sm-12">
  <div class="card card-tabs" style="background: #fff; border: none;">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-home-tab" data-bs-toggle="tab" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Resumen</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-profile-tab" data-bs-toggle="tab" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Tareas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-messages-tab" data-bs-toggle="tab" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Archivos</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
              <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                <!-- Resumen del proyecto y gestión de colaboradores -->
                <div class="row justify-content-center">
                  <div class="col-12">
                    <!-- Datos del proyecto -->
                    <div class="card mb-3 shadow rounded-3 border-0">
                    
                      <div class="card-body bg-light rounded-bottom-3">
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
                                <input type="text" class="form-control" id="fechaInicioView" value="<?= date('d/m/Y', strtotime($proyecto['fecha_inicio'])) ?>" readonly>
                                <label for="fechaInicioView">Fecha de Inicio</label>
                              </div>
                              <div class="form-floating">
                                <input type="text" class="form-control" id="fechaVencimientoView" value="<?= date('d/m/Y', strtotime($proyecto['fecha_vencimiento'])) ?>" readonly>
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
                                <!-- Avance eliminado de la vista -->
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
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= !empty($proyecto['fecha_inicio']) ? date('Y-m-d', strtotime($proyecto['fecha_inicio'])) : '' ?>" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="fecha_vencimiento" class="form-label">Fecha vencimiento</label>
                                    <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?= !empty($proyecto['fecha_vencimiento']) ? date('Y-m-d', strtotime($proyecto['fecha_vencimiento'])) : '' ?>" required>
                                  </div>
                                  <div class="mb-3">
                                    <label for="prioridad" class="form-label">Prioridad</label>
                                    <select class="form-control" id="prioridad" name="prioridad">
                                      <option value="Alta" <?= $proyecto['prioridad']=='Alta'?'selected':'' ?>>Alta</option>
                                      <option value="Media" <?= $proyecto['prioridad']=='Media'?'selected':'' ?>>Media</option>
                                      <option value="Baja" <?= $proyecto['prioridad']=='Baja'?'selected':'' ?>>Baja</option>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <!-- Avance eliminado -->
                                  </div>
                                  <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                      <option value="Activo" <?= $proyecto['status']=='Activo'?'selected':'' ?>>Activo</option>
                                      <option value="En Pausa" <?= $proyecto['status']=='En Pausa'?'selected':'' ?>>En Pausa</option>
                                      <option value="Cancelado" <?= $proyecto['status']=='Cancelado'?'selected':'' ?>>Cancelado</option>
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
                      </div>
                    </div>
                  </div>
                  <div class="col-12">
                    <!-- Gestión de colaboradores debajo de la tarjeta de proyecto -->
                    <div class="card mb-3 shadow rounded-3 border-0">
                        <!-- El controlador prepara $colab_count -->
                        <div class="card-header d-flex align-items-center bg-primary rounded-top-3" style="gap: 1rem;">
                          <h5 class="mb-0 flex-grow-1 text-white"><i class="bi bi-person me-2"></i>Agregar colaborador</h5>
                          <button class="btn btn-success btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#modalAgregarColaborador"><i class="bi bi-plus"></i> Agregar</button>
                        </div>
                      <div class="card-body bg-light rounded-bottom-3">
                        <ul class="list-group list-group-flush" id="listaColaboradores">
                          <?= $lista_colaboradores_html ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Modal para agregar colaborador -->
                <div class="modal fade" id="modalAgregarColaborador" tabindex="-1" aria-labelledby="modalAgregarColaboradorLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form action="core/agregar-colaborador-proyecto.php" method="post">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalAgregarColaboradorLabel">Agregar colaborador</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="id_proyecto" value="<?= $proyecto_id ?>">
                          <div class="mb-3">
                            <label for="id_colaborador" class="form-label">Selecciona colaborador</label>
                            <select name="id_colaborador" id="id_colaborador" class="form-select" required>
                              <option value="">Selecciona colaborador</option>
                              <?= $colaboradores_select_html ?>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-primary">Agregar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                
              
              <!-- Tareas del proyecto -->
                <div class="card mb-3 shadow">
                  <div class="card-header bg-primary d-flex align-items-center" style="gap: 1rem;">
                    <h3 class="card-title text-white mb-0 flex-grow-1"><i class="fas fa-tasks me-2"></i>Tareas del Proyecto</h3>
                    <button class="btn btn-light btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#modalAgregarTarea">
                      <i class="fas fa-plus"></i> Agregar tarea
                    </button>
                  </div>
                  <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                      <thead class="table-primary">
                        <tr>
                          <th style="width: 10%">#</th>
                          <th style="width: 25%">Responsable</th>
                          <th style="width: 40%">Descripción</th>
                          <th style="width: 20%">Vencimiento</th>
                          <th style="width: 30%">Progreso</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?= $list_tareas_html ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                
              
              <!-- Archivos del proyecto -->
                <div class="card mb-3 shadow">
                  <div class="card-header bg-primary d-flex align-items-center" style="gap: 1rem;">
                    <?= $get_nombre_proyecto_html ?>
                    <h4 class="mb-0 text-white flex-grow-1"><i class="bi bi-folder2-open me-2"></i>Archivos del proyecto: <?= htmlspecialchars($nombre_proyecto) ?></h4>
                    <button class="btn btn-light btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#modalAgregarArchivo"><i class="bi bi-plus"></i> Agregar archivo</button>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <input type="text" class="form-control" placeholder="Buscar archivos en el proyecto" id="busquedaArchivos" onkeyup="filtrarArchivos()">
                    </div>
                    <table class="table table-bordered table-hover mb-0" id="tablaArchivos">
                      <thead class="table-primary">
                        <tr>
                          <th>Archivo</th>
                          <th>Descripción</th>
                          <th>Categoría</th>
                          <th>Tipo</th>
                          <th>Institución</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?= $listar_archivos_html ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                <!-- Aquí puedes agregar contenido adicional para colaboradores si lo deseas -->
              </div>
            </div>
         </div>
         <!-- /.card -->
       </div>
     </div>

   </div>
 </div>

<!-- Default box -->

<!-- /.card -->

</section>

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
          <input type="hidden" name="id_proyecto" value="<?= $proyecto_id ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="asunto" class="form-label">Asunto</label>
              <input type="text" class="form-control" id="asunto" name="asunto" required>
            </div>
            <div class="col-md-6">
              <label for="prioridad" class="form-label">Prioridad</label>
              <select class="form-control" id="prioridad" name="prioridad">
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="fecha_inicio" class="form-label">Fecha inicio</label>
              <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <!-- Fecha ejecución eliminada -->
            <div class="col-md-6">
              <label for="fecha_vencimiento" class="form-label">Fecha vencimiento</label>
              <input type="datetime-local" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
            </div>
            <!-- Porcentaje eliminado -->
            <div class="col-md-12">
              <label for="propietario" class="form-label">Responsable</label>
              <select class="form-control" id="propietario" name="propietario" required>
                <option value="">Selecciona un responsable</option>
                <?= $colaboradores_select_html ?>
              </select>
            </div>
            <div class="col-md-12">
              <label for="detalles" class="form-label">Detalles</label>
              <textarea class="form-control" id="detalles" name="detalles"></textarea>
            </div>
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

<!-- Modal Agregar Archivo -->
<div class="modal fade" id="modalAgregarArchivo" tabindex="-1" aria-labelledby="modalAgregarArchivoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalAgregarArchivoLabel">Subir Archivo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formAgregarArchivo" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="id_proyecto" value="<?= $proyecto_id ?>">
          <div class="row g-3">
            <div class="col-12">
              <label for="addArchivo" class="form-label">Seleccionar archivo</label>
              <input type="file" class="form-control" id="addArchivo" name="addArchivo" required>
              <div class="form-text">Ningún archivo seleccionado</div>
            </div>
            <div class="col-12">
              <label for="descripcion" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción del archivo"></textarea>
            </div>
            <div class="col-md-6">
              <label for="id_categoria" class="form-label">Categoría</label>
              <select class="form-control" id="id_categoria" name="id_categoria">
                <option value="">Selecciona categoría</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="id_institucion" class="form-label">Institución</label>
              <select class="form-control" id="id_institucion" name="id_institucion">
                <option value="">Selecciona institución</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Subir archivo
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
