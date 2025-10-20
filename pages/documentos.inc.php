<section class="content">
  <div class="container-fluid">
    <div class="card card-white shadow-sm">
      <div class="card-body">

        <!-- Título -->
        <div class="row">
          <div class="col-12">
            <div class="card-header bg-primary text-white rounded mb-3">
              <h4 class="m-0">Documentos</h4>
            </div>
          </div>

        </div>

        <!-- Botón nuevo -->
        <div class="row mt-3 mb-3">
          <div class="col-12">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDocumento">
              <i class="fas fa-plus me-1"></i> Nuevo
            </button>
              <button class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#modalEliminados">
                <i class="fas fa-trash-alt me-1"></i> Eliminados
              </button>
          </div>
        </div>
    <!-- Modal Ver Eliminados -->
    <div class="modal fade" id="modalEliminados" tabindex="-1" aria-labelledby="modalEliminadosLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border border-danger">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="modalEliminadosLabel">Documentos Eliminados</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead class="bg-danger text-white">
                  <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Archivo</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tbodyModalEliminados">
                  <!-- Aquí se insertan los eliminados dinámicamente -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Agregar Documento -->
    <div class="modal fade" id="modalDocumento" tabindex="-1" aria-labelledby="modalDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border border-primary">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalDocumentoLabel">Agregar Documento</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form id="formDocumento">
              <div class="row">
                <!-- Columna izquierda -->
                <div class="col-md-6">
                  <div class="card p-3 mb-3">
                    <div class="mb-2">
                      <label class="form-label" for="addCategoria">Categoría</label>
                      <select class="form-select" id="addCategoria">
                        <option value="">Cargando categorías...</option>
                      </select>
                    </div>
                    <div class="mb-2">
                      <label class="form-label" for="addInstitucion">Institución</label>
                      <select class="form-select" id="addInstitucion">
                        <option value="">Cargando instituciones...</option>
                      </select>
                    </div>
                      <div class="mb-2">
                        <label class="form-label" for="addProyecto">Proyecto</label>
                        <select class="form-select" id="addProyecto">
                          <option value="">Cargando proyectos...</option>
                        </select>
                      </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="addTitulo">Nombre</label>
                    <input type="text" class="form-control" id="addTitulo">
                  </div>
                  <div class="mb-2">
                    <label class="form-label" for="editorDescripcion">Descripción</label>
                    <div class="btn-toolbar mb-2" role="toolbar">
                      <div class="btn-group btn-group-sm me-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('bold', false, '')"><i class="fas fa-bold"></i></button>
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('italic', false, '')"><i class="fas fa-italic"></i></button>
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('underline', false, '')"><i class="fas fa-underline"></i></button>
                      </div>
                      <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('insertUnorderedList', false, '')"><i class="fas fa-list-ul"></i></button>
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('insertOrderedList', false, '')"><i class="fas fa-list-ol"></i></button>
                      </div>
                    </div>
                    <div id="editorDescripcion" class="form-control" contenteditable="true" style="min-height: 100px;"></div>
                    <input type="hidden" name="descripcionHtml" id="descripcionHtml">
                  </div>
                </div>
                <!-- Columna derecha -->
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label" for="addArchivo">Archivo</label>
                    <input type="file" class="form-control" id="addArchivo" name="addArchivo">
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="addTipo">Tipo de archivo</label>
                    <input type="text" class="form-control" id="addTipo">
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="addTamano">Tamaño de archivo</label>
                    <input type="text" class="form-control" id="addTamano">
                  </div>
                    <div class="mb-3">
                      <label class="form-label" for="addColab">Colaborador</label>
                      <select class="form-control" id="addColab">
                        <option value="">Cargando colaboradores...</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="addCompartido">Compartido</label>
                      <select class="form-control" id="addCompartido">
                        <option value="0" selected>No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="addDescargable">Descargable</label>
                      <select class="form-control" id="addDescargable">
                        <option value="0" selected>No</option>
                        <option value="1">Sí</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="addPassword">Password</label>
                        <input type="password" class="form-control" id="addPassword" disabled>
                    </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Cerrar</button>
            <button type="submit" class="btn btn-primary" form="formDocumento"><i class="fas fa-save me-1"></i> Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Editar Documento -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border border-info">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalEditarLabel">Editar Documento</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form id="formEditar">
              <div class="row">
                <!-- Columna izquierda -->
                <div class="col-md-6">
                  <div class="card p-3 mb-3">
                    <div class="mb-2">
                      <label class="form-label" for="editCategoria">Categoría</label>
                      <select class="form-select" id="editCategoria">
                        <option value="">Selecciona una categoría</option>
                      </select>
                    </div>
                    <div class="mb-2">
                      <label class="form-label" for="editInstitucion">Institución</label>
                      <select class="form-select" id="editInstitucion">
                        <option value="">Selecciona una institución</option>
                      </select>
                    </div>
                    <div class="mb-2">
                      <label class="form-label" for="editProyecto">Proyecto</label>
                      <select class="form-select" id="editProyecto">
                        <option value="">Selecciona un proyecto</option>
                      </select>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="editTitulo">Nombre</label>
                    <input type="text" class="form-control" id="editTitulo">
                  </div>
                  <div class="mb-2">
                    <label class="form-label" for="editDescripcion">Descripción</label>
                    <div class="btn-toolbar mb-2" role="toolbar">
                      <div class="btn-group btn-group-sm me-2">
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('bold', false, '')"><i class="fas fa-bold"></i></button>
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('italic', false, '')"><i class="fas fa-italic"></i></button>
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('underline', false, '')"><i class="fas fa-underline"></i></button>
                      </div>
                      <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('insertUnorderedList', false, '')"><i class="fas fa-list-ul"></i></button>
                        <button type="button" class="btn btn-outline-secondary" onclick="document.execCommand('insertOrderedList', false, '')"><i class="fas fa-list-ol"></i></button>
                      </div>
                    </div>
                    <div id="editDescripcion" class="form-control" contenteditable="true" style="min-height: 100px;"></div>
                  </div>
                </div>
                <!-- Columna derecha -->
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="form-label" for="editColab">Colaborador</label>
                    <select class="form-control" id="editColab">
                      <option value="">Selecciona un colaborador</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="editCompartido">Compartido</label>
                    <select class="form-control" id="editCompartido">
                      <option value="0">No</option>
                      <option value="1">Sí</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="editDescargable">Descargable</label>
                    <select class="form-control" id="editDescargable">
                      <option value="0">No</option>
                      <option value="1">Sí</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="editPassword">Password</label>
                    <input type="password" class="form-control" id="editPassword">
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Cancelar</button>
            <button type="submit" class="btn btn-info" form="formEditar"><i class="fas fa-save me-1"></i> Guardar cambios</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Eliminar Documento -->
    <div class="modal fade" id="modalEliminar" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content border border-danger">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Eliminar Documento</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>¿Estás seguro de que deseas eliminar este documento?</p>
          </div>
          <div class="modal-footer justify-content-between">
            <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Cancelar</button>
            <button class="btn btn-danger"><i class="fas fa-trash me-1"></i> Eliminar</button>
          </div>
        </div>
      </div>
    </div>

        <!-- Tabla -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="bg-navy text-white">
              <tr>
                <th>ID</th>
                <th>Categoría</th>
                <th>Institución</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Proyecto</th>
                <th>Tipo</th>
                <th>Tamaño</th>
                <th>Archivo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="tbodyDocumentos">
              <!-- Aquí se insertan los documentos dinámicamente -->
            </tbody>
          </table>
        </div>
        <!-- Modal Descargar con contraseña -->
        <div class="modal fade" id="modalDescargar" tabindex="-1" aria-labelledby="modalDescargarLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content border border-success">
              <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalDescargarLabel">Descargar Documento</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label for="inputPasswordDescargar" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" id="inputPasswordDescargar">
                  <div id="descargarError" class="text-danger mt-2" style="display:none;"></div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Cancelar</button>
                <button class="btn btn-success" id="btnConfirmarDescarga"><i class="fas fa-download me-1"></i> Descargar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>



