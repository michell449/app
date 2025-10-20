<section class="content">
  <div class="container-fluid">
    <div class="card card-white shadow-sm">
      <div class="card-body">
        <!-- Título con fondo -->
        <div class="row">
          <div class="col-12">
            <div class="card-header bg-primary text-white">
              <h2 class="m-0">Instituciones</h2>
            </div>
          </div>
        </div>

        <!-- Botón nuevo separado -->
        <div class="row mt-3 mb-3">
          <div class="col-12">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalInstitucion" onclick="limpiarFormularioInstitucion()">
              <i class="fas fa-plus me-2"></i> Agregar Institución
            </button>
          </div>
        </div>

  <!-- ...eliminado: controles manuales de búsqueda y paginación, DataTables los genera automáticamente... -->

    <!-- Tabla -->
    <div class="table-responsive">
      <table id="tablaInstituciones" class="table table-bordered table-striped">
        <thead class="bg-navy text-white">
          <tr>
            <th>ID</th>
            <th>Nombre de la Institución</th>
            <th>Tipo</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <!-- Instituciones dinámicas -->
        </tbody>
      </table>
    </div>

        <!-- Info y paginación -->
        <!--<div class="row mt-2">
          <div class="col-md-6">
            <p>Mostrando registros del 1 al 3 de un total de 3 registros</p>
          </div>
          <div class="col-md-6 text-end">
            <nav>
              <ul class="pagination pagination-sm mb-0 justify-content-end">
                <li class="page-item disabled">
                  <a class="page-link border-0 bg-transparent text-secondary">Anterior</a>
                </li>
                <li class="page-item active">
                  <a class="page-link border-0">1</a>
                </li>
                <li class="page-item disabled">
                  <a class="page-link border-0 bg-transparent text-secondary">Siguiente</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>-->
      </div>
    </div>
  </div>
</section>

<!-- Modal Ver Institución -->
<div class="modal fade" id="modalVerInstitucion" tabindex="-1" aria-labelledby="modalVerInstitucionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:850px; width:98%;">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title" id="modalVerInstitucionLabel">Detalles de la Institución</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Aquí se cargan los datos -->
      </div>
      <div class="modal-footer d-flex flex-column align-items-center gap-2">
        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Agregar/Editar Institución -->
<div class="modal fade" id="modalInstitucion" tabindex="-1" aria-labelledby="modalInstitucionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:850px; width:98%;">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white text-center rounded-top-4">
        <h5 class="modal-title w-100" id="modalInstitucionLabel">Registro de Nueva Institución</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formInstitucion">
          <input type="hidden" id="institucionId">
          <div class="row g-2">
            <div class="col-md-12">
              <label for="nombreInstitucion" class="form-label">Nombre de la Institución</label>
              <input type="text" class="form-control" id="nombreInstitucion" name="nombreInstitucion" required>
            </div>
            <div class="col-md-12">
              <label for="direccionInstitucion" class="form-label">Dirección</label>
              <input type="text" class="form-control" id="direccionInstitucion" name="direccionInstitucion">
            </div>
            <div class="col-md-4">
              <label for="tipoInstitucion" class="form-label">Tipo</label>
              <select class="form-select" id="tipoInstitucion" name="tipoInstitucion" required>
                <option value="">Seleccione</option>
                <option value="publica">Publica</option>
                <option value="privada">Privada</option>
                <option value="social">Social</option>
                <option value="cultural">Cultural</option>
                <option value="politica">Politica</option>
                <option value="religiosa">Religiosa</option>
                <option value="otra">Otra</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="telefonoInstitucion" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefonoInstitucion" name="telefonoInstitucion">
            </div>
            <div class="col-md-8">
              <label for="correoInstitucion" class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" id="correoInstitucion" name="correoInstitucion" required>
            </div>
            <div class="col-md-12">
              <label for="webInstitucion" class="form-label">Web</label>
              <input type="text" class="form-control" id="webInstitucion" name="webInstitucion">
            </div>
            <div class="col-md-12">
              <label for="ubicacionUrlInstitucion" class="form-label">Ubicación URL</label>
              <input type="text" class="form-control" id="ubicacionUrlInstitucion" name="ubicacionUrlInstitucion">
            </div>
            <div class="col-md-12">
              <label for="descripcionInstitucion" class="form-label">Descripción</label>
              <textarea class="form-control" id="descripcionInstitucion" name="descripcionInstitucion" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer d-flex flex-column flex-md-row align-items-center gap-2">
            <button type="submit" class="btn btn-success w-100 w-md-auto">
              <i class="fas fa-save me-1"></i> Guardar
            </button>
            <button type="button" class="btn btn-outline-secondary w-100 w-md-auto" data-bs-dismiss="modal">
              <i class="fas fa-times me-1"></i> Cancelar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



