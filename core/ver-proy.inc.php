<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Resumen</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Tablero</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Mensajes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Colaboradores</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
              <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                <div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">


                        <div class="row">
                          <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">

                            <div class="row">
                              <div class="">
                                <div class="card-header">
                                  <h3 class="card-title">Tareas</h3>
                                  <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalAgregarTarea">
                                    <i class="fas fa-plus"></i> Agregar tarea
                                  </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                  <table class="table table-striped">
                                    <thead>
                                      <tr>
                                        <th style="width: 10%">#</th>
                                        <th style="width: 25%">Responsable</th>
                                        <th style="width: 40%">Descripcion</th>
                                        <th style="width: 20%">Vencimiento</th>
                                        <th style="width: 30%">Progreso</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        $proyecto_id = $_GET['id'] ?? 1; // O como lo manejes
                                        include __DIR__ . '/../core/list-tareas-proyecto.php';
                                      ?>
                                    </tbody>
                                  </table>
                                </div>
                                
                              </div>
                            </div>
                          </div>
                          
                        </div>
                      </div>
                      <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <?php include __DIR__ . '/../core/datos-proyecto.php'; ?>
                        <h3 class="mt-3 fw-bold text-dark">Archivos</h3>
                        <?php include __DIR__ . '/../core/listar-archivos-proyecto.php'; ?>
                        <div class="text-center mt-5 mb-3">
                          <a href="panel?pg=archivos-config" class="btn btn-sm btn-primary">Agregar archivo</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>

              </div>
              <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                <div class="content-wrapper kanban">
                  <section class="content ">
                    <div class="container-fluid h-50">
                      <div class="card card-row card-secondary">
                        <div class="card-header">
                          <h3 class="card-title">
                            Backlog
                          </h3>
                        </div>
                        <div class="card-body">
                          <div class="card card-info card-outline">
                            <div class="card-header">
                              <h5 class="card-title">Create Labels</h5>
                              <div class="card-tools">
                                <a href="#" class="btn btn-tool btn-link">#3</a>
                                <a href="#" class="btn btn-tool">
                                  <i class="fas fa-pen"></i>
                                </a>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox1" disabled="">
                                <label for="customCheckbox1" class="custom-control-label">Bug</label>
                              </div>
                              <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox2" disabled="">
                                <label for="customCheckbox2" class="custom-control-label">Feature</label>
                              </div>
                              <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox3" disabled="">
                                <label for="customCheckbox3" class="custom-control-label">Enhancement</label>
                              </div>
                              <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox4" disabled="">
                                <label for="customCheckbox4" class="custom-control-label">Documentation</label>
                              </div>
                              <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="customCheckbox5" disabled="">
                                <label for="customCheckbox5" class="custom-control-label">Examples</label>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="card card-row card-primary">
                        <div class="card-header">
                          <h3 class="card-title">
                            To Do
                          </h3>
                        </div>
                        <div class="card-body">
                          <div class="card card-primary card-outline">
                            <div class="card-header">
                              <h5 class="card-title">Create first milestone</h5>
                              <div class="card-tools">
                                <a href="#" class="btn btn-tool btn-link">#5</a>
                                <a href="#" class="btn btn-tool">
                                  <i class="fas fa-pen"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card card-row card-default">
                        <div class="card-header bg-info">
                          <h3 class="card-title">
                            In Progress
                          </h3>
                        </div>
                        <div class="card-body">
                          <div class="card card-light card-outline">
                            <div class="card-header">
                              <h5 class="card-title">Update Readme</h5>
                              <div class="card-tools">
                                <a href="#" class="btn btn-tool btn-link">#2</a>
                                <a href="#" class="btn btn-tool">
                                  <i class="fas fa-pen"></i>
                                </a>
                              </div>
                            </div>
                            <div class="card-body">
                              <p>
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
                                Aenean commodo ligula eget dolor. Aenean massa.
                                Cum sociis natoque penatibus et magnis dis parturient montes,
                                nascetur ridiculus mus.
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card card-row card-success">
                        <div class="card-header">
                          <h3 class="card-title">
                            Done
                          </h3>
                        </div>
                        <div class="card-body">
                          <div class="card card-primary card-outline">
                            <div class="card-header">
                              <h5 class="card-title">Create repo</h5>
                              <div class="card-tools">
                                <a href="#" class="btn btn-tool btn-link">#1</a>
                                <a href="#" class="btn btn-tool">
                                  <i class="fas fa-pen"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
              <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
               Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
             </div>
             <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
               Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
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
          <div class="mb-3">
            <label for="asunto" class="form-label">Asunto</label>
            <input type="text" class="form-control" id="asunto" name="asunto" required>
          </div>
          <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha inicio</label>
            <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
          </div>
          <div class="mb-3">
            <label for="fecha_ejecucion" class="form-label">Fecha ejecución</label>
            <input type="datetime-local" class="form-control" id="fecha_ejecucion" name="fecha_ejecucion" required>
          </div>
          <div class="mb-3">
            <label for="fecha_vencimiento" class="form-label">Fecha vencimiento</label>
            <input type="datetime-local" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
          </div>
          <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select class="form-control" id="prioridad" name="prioridad">
              <option value="Alta">Alta</option>
              <option value="Media">Media</option>
              <option value="Baja">Baja</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="porcentaje" class="form-label">Porcentaje</label>
            <input type="number" class="form-control" id="porcentaje" name="porcentaje" min="0" max="100" value="0">
          </div>
          <div class="mb-3">
            <label for="propietario" class="form-label">Responsable</label>
            <select class="form-control" id="propietario" name="propietario" required>
              <option value="">Selecciona un responsable</option>
              <?php include __DIR__ . '/../core/list-colaboradores-select.php'; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="detalles" class="form-label">Detalles</label>
            <textarea class="form-control" id="detalles" name="detalles"></textarea>
          </div>
          <!-- Agrega más campos si lo necesitas -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>