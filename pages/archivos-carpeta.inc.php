<!-- archivos-carpeta.inc.php -->
<div class="container-fluid py-4">
  <div class="card mb-4">
    <div class="card-body">
      <h2 class="mb-0"><i class="bi bi-folder2-open"></i> Archivos de la carpeta: <span id="nombreCarpeta"></span></h2>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="input-group mb-3">
        <input type="text" class="form-control" id="buscadorArchivosCarpeta" placeholder="Buscar archivos en la carpeta">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="tablaArchivosCarpeta">
          <thead class="bg-secondary text-white">
            <tr>
              <th>Archivo</th>
              <th>Descripción</th>
              <th>Tipo</th>
              <th>Proyecto</th>
              <th>Institución</th>
              <!-- <th>Categoría</th> -->
              <!-- <th>Última modificación</th> -->
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal para subir/editar archivo -->
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
            <select name="id_proyecto" id="id_proyecto" class="form-select" required>
              <option value="">Selecciona proyecto</option>
              <?php require_once __DIR__ . '/../core/listar-proyectos.php'; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select name="id_categoria" id="id_categoria" class="form-select" required>
              <option value="">Selecciona categoría</option>
              <?php require_once __DIR__ . '/../core/listar-categorias.php'; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="id_institucion" class="form-label">Institución</label>
            <select name="id_institucion" id="id_institucion" class="form-select" required>
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
