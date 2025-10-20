<!--begin::Archivos Configuración-->
<div class="container-fluid py-4">
  <div class="card" style="margin-bottom:0; border-radius:0;">
    <div class="card-body p-3" style="text-align:left;">
      <h2 class="mb-0" style="font-size:1.7rem;"><i class="bi bi-folder2-open"></i> Gestión de Archivos</h2>
    </div>
  </div>
  <div class="card mt-2">
    <div class="card-body">
      <div class="d-flex align-items-center mb-2">
        <div class="me-3">
          
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSubirArchivo">
            <i class="bi bi-upload"></i> Subir Archivo
          </button>
          <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#modalNuevaCarpeta">
            <i class=""></i>Nueva categoría
          </button>
        </div>
        <h5 class="mb-0">Categorias por Carpetas</h5>
      </div>
      <div class="container-fluid">
        <div class="row">
          <!-- Sidebar izquierda -->
          <div class="col-md-3">
            <!-- Modal Nueva Carpeta -->
            <div class="modal fade" id="modalNuevaCarpeta" tabindex="-1" aria-labelledby="modalNuevaCarpetaLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="core/crear-carpeta.php" method="post">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalNuevaCarpetaLabel">Crear nueva carpeta de categoría</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="nombreCarpeta" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombreCarpeta" class="form-control" placeholder="Nombre de la carpeta/categoría" required>
                      </div>
                      <div class="mb-3">
                        <label for="descripcionCarpeta" class="form-label">Descripción</label>
                        <input type="text" name="descripcion" id="descripcionCarpeta" class="form-control" placeholder="Descripción" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- Área principal -->
          <div class="col-md-9">
            <!-- Modal para subir archivo -->
            <div class="modal fade" id="modalSubirArchivo" tabindex="-1" aria-labelledby="modalSubirArchivoLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="core/upload.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalSubirArchivoLabel">Subir archivo</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="archivo" class="form-label">Archivo</label>
                        <input type="file" name="archivo" id="archivo" class="form-control" required>
                      </div>
                      <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="2" required></textarea>
                      </div>
                      <div class="mb-3">
                        <label for="tipo_mime" class="form-label">Tipo de archivo (MIME)</label>
                        <select name="tipo_mime" id="tipo_mime" class="form-select" required>
                          <option value="">Selecciona tipo de archivo</option>
                          <option value="application/pdf">PDF (.pdf)</option>
                          <option value="application/msword">Word (.doc)</option>
                          <option value="application/vnd.openxmlformats-officedocument.wordprocessingml.document">Word (.docx)</option>
                          <option value="application/vnd.ms-excel">Excel (.xls)</option>
                          <option value="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">Excel (.xlsx)</option>
                          <option value="image/jpeg">Imagen JPEG (.jpg, .jpeg)</option>
                          <option value="image/png">Imagen PNG (.png)</option>
                          <option value="image/gif">Imagen GIF (.gif)</option>
                          <option value="text/plain">Texto (.txt)</option>
                          <option value="application/zip">ZIP (.zip)</option>
                          <option value="application/vnd.ms-powerpoint">PowerPoint (.ppt)</option>
                          <option value="application/vnd.openxmlformats-officedocument.presentationml.presentation">PowerPoint (.pptx)</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="id_proyecto" class="form-label">Proyecto</label>
                        <select name="id_proyecto" id="id_proyecto" class="form-select">
                          <option value="">Selecciona proyecto</option>
                          <?php require_once __DIR__ . '/../core/listar-proyectos.php'; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="id_categoria" class="form-label">Categoría</label>
                        <select name="id_categoria" id="id_categoria" class="form-select" >
                          <option value="">Selecciona categoría</option>
                          <?php require_once __DIR__ . '/../core/listar-categorias.php'; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="id_institucion" class="form-label">Institución</label>
                        <select name="id_institucion" id="id_institucion" class="form-select">
                          <option value="">Selecciona institución</option>
                          <?php require_once __DIR__ . '/../core/listar-insti.php'; ?>
                        </select>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                      <button type="submit" class="btn btn-primary">Subir archivo</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="row">
              <?php include __DIR__ . '/../core/listar-carpetas.php'; ?>
            </div>
            <!-- Aquí se eliminó la tabla y el buscador de últimos archivos subidos -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>