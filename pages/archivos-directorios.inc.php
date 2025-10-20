

<!-- begin::modulo para archivos y directorios tipo Google Drive -->
<div class="card bg-white shadow-sm mt-4 mb-3 ">
    <div class="card-header bg-primary text-white p-3 ">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold m-0">Archivos</h2>
        </div>
    </div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 mb-4 px-3 gap-2">
        <div class="d-flex gap-2">
            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalNuevaCarpeta"><i class="bi bi-folder-plus me-2"></i>Nueva carpeta</button>
            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalSubirArchivo"><i class="bi bi-upload me-2"></i>Subir archivo</button>
            <button class="btn btn-outline-secondary me-2" id="btnVerCompartidos"><i class="bi bi-people"></i> Compartidos conmigo</button>
            <button class="btn btn-danger me-2" id="btnAbrirPapelera"><i class="bi bi-trash"></i> Papelera</button>
          </div>
        <form class="d-flex" style="min-width:260px;max-width:340px;" onsubmit="return false;">
            <input class="form-control me-2" type="search" placeholder="Buscar archivos o carpetas..." aria-label="Buscar" id="busquedaDrive">
            <button class="btn btn-outline-secondary" type="button" id="btnBuscarDrive"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="d-flex align-items-center justify-content-between mb-3 px-3">
        <div>
            <span class="fs-5 fw-bold text-secondary"><i class="bi bi-hdd-stack"></i> Espacio usado: <span id="espacioUsado">-</span></span>
            <span class="ms-4 fs-6 text-muted"><i class="bi bi-file-earmark-text"></i> Archivos: <span id="totalArchivos">-</span></span>
            <span class="ms-4 fs-6 text-muted"><i class="bi bi-folder"></i> Carpetas: <span id="totalCarpetas">-</span></span>
        </div>
    </div>
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-3 px-3" id="breadcrumbNav"></nav>
    <!-- Lista única de archivos y carpetas -->
    <div class="list-group list-group-flush animate__animated animate__fadeIn px-3" id="driveListGroup"></div>
    <!-- Modal Nueva Carpeta -->
    <div class="modal fade" id="modalNuevaCarpeta" tabindex="-1" aria-labelledby="modalNuevaCarpetaLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalNuevaCarpetaLabel">Crear nueva carpeta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <form action="core/crear-carpeta.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="idpadre" value="">
              <input type="hidden" name="id_propietario" value="">
              <input type="hidden" name="tipo" value="D">
              <div class="mb-3">
                <label for="nombreCarpeta" class="form-label">Nombre de la carpeta</label>
                <input type="text" class="form-control" id="nombreCarpeta" name="nombre" required maxlength="200">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Crear carpeta</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal Subir Archivo -->
    <div class="modal fade" id="modalSubirArchivo" tabindex="-1" aria-labelledby="modalSubirArchivoLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalSubirArchivoLabel">Subir archivo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <form action="core/upload.php" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="idpadre" value="">
              <input type="hidden" name="id_propietario" value="">
              <input type="hidden" name="tipo" value="A">
              <div class="mb-3">
                <label for="archivo" class="form-label">Seleccionar archivo</label>
                <input type="file" class="form-control" id="archivo" name="archivo" required>
              </div>
              <div class="mb-3">
                <label for="tamanoArchivo" class="form-label">Tamaño (KB)</label>
                <input type="text" class="form-control" id="tamanoArchivo" name="tamano_kb" readonly>
              </div>
              <div class="mb-3">
                <label for="tipoArchivo" class="form-label">Tipo de archivo (MIME)</label>
                <input type="text" class="form-control" id="tipoArchivo" name="tipo_archivo" readonly>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Subir</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal Compartir Archivo -->
    <div class="modal fade" id="modalCompartirArchivo" tabindex="-1" aria-labelledby="modalCompartirArchivoLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCompartirArchivoLabel">Compartir archivo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <form action="core/compartir-archivo.php" method="POST">
            <div class="modal-body">
              <input type="hidden" name="id_archivo" id="compartir_id_archivo" value="">
              <input type="hidden" name="idpadre" id="compartir_idpadre" value="">
              <div class="mb-3">
                <label class="form-label">Compartir con usuarios</label>
                <div id="usuariosCompartirChecks" class="border rounded p-2" style="max-height:160px;overflow-y:auto;"></div>
                <div class="form-text">Selecciona uno o más usuarios para compartir.</div>
              </div>
              <div class="mb-3">
                <label class="form-label">Permisos:</label><br>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="permiso_ver" id="compartirPermisoVer" value="1" checked>
                  <label class="form-check-label" for="compartirPermisoVer">Ver</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="permiso_descargar" id="compartirPermisoDescargar" value="1">
                  <label class="form-check-label" for="compartirPermisoDescargar">Descargar</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="permiso_actualizar" id="compartirPermisoActualizar" value="1">
                  <label class="form-check-label" for="compartirPermisoActualizar">Editar</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="permiso_borrar" id="compartirPermisoBorrar" value="1">
                  <label class="form-check-label" for="compartirPermisoBorrar">Quitar</label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Compartir</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Modal Editar Archivo/Carpeta -->
    <div class="modal fade" id="modalEditarRecurso" tabindex="-1" aria-labelledby="modalEditarRecursoLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditarRecursoLabel">Renombrar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <form id="formEditarRecurso">
            <div class="modal-body">
              <input type="hidden" name="id_recurso" id="editar_id_recurso" value="">
              <input type="hidden" name="tipo_recurso" id="editar_tipo_recurso" value="">
              <div class="mb-3">
                <label for="editar_nombre_recurso" class="form-label">Nuevo nombre</label>
                <input type="text" class="form-control" id="editar_nombre_recurso" name="nombre" required maxlength="200">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-warning">Guardar cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>


