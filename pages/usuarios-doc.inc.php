<!--revise todo y no falta ningun contenedor-->
<section class="content">
  <div class="container-fluid">
      <div class="card card-outline mt-4 w-100" style="max-width:100%;">
        <div class="container-fluid p-0">
      <div class="card-header bg-secondary text-white">
        <h4 class="m-0">Administración de usuarios</h4>
      </div>
      <div class="card-body">
        <!-- Botón nuevo separado -->
        <div class="row mt-3 mb-3">
          <div class="col-12">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="limpiarFormularioUsuario()">
              <i class="fas fa-plus me-2"></i> Nuevo
            </button>
          </div>
        </div>
        <!-- Filtros de búsqueda -->
        <div class="row mb-2">
          <div class="col-md-6">
            <label>Mostrar 
              <select class="custom-select custom-select-sm w-auto d-inline-block">
                <option value="5" selected>5</option>
                <option value="10">10</option>
                <option value="25">25</option>
              </select> registros por página
            </label>
          </div>
          <div class="col-md-6 text-end d-flex align-items-center justify-content-end">
            <label class="mb-0">Buscar:
              <input type="search" class="form-control form-control-sm d-inline-block w-auto ms-2" placeholder="">
            </label>
          </div>
        </div>
        <!-- Tabla -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="bg-navy text-white">
              <tr>
                <th>ID</th>
                <th>Nombres</th>
                <th>Tipo de usuario</th>
                <th>Usuario</th>
                <th>Contraseña</th>
                <th>Correo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Administrador</td>
                <td>Admin</td>
                <td>admin</td>
                <td>admin</td>
                <td>admin@gmail.com</td>
                <td class="text-center">
                  <div class="d-inline-flex gap-2">
                    <button class="btn btn-info px-3 me-2" data-bs-toggle="modal" data-bs-target="#modalUsuario"
                      onclick="cargarDatosUsuario('1','Administrador','Admin','admin','admin','admin@gmail.com')">
                      <i class="fas fa-edit me-1"></i> Editar
                    </button>
                    <button class="btn btn-danger px-3" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario1">
                      <i class="fas fa-trash-alt me-1"></i> Eliminar
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Joel Pérez</td>
                <td>Usuario</td>
                <td>joel</td>
                <td>joel</td>
                <td>joel@gmail.com</td>
                <td class="text-center">
                  <div class="d-inline-flex gap-2">
                    <button class="btn btn-info px-3 me-2" data-bs-toggle="modal" data-bs-target="#modalUsuario"
                      onclick="cargarDatosUsuario('2','Joel Pérez','Usuario','joel','joel','joel@gmail.com')">
                      <i class="fas fa-edit me-1"></i> Editar
                    </button>
                    <button class="btn btn-danger px-3" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario2">
                      <i class="fas fa-trash-alt me-1"></i> Eliminar
                    </button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Javier López</td>
                <td>Usuario</td>
                <td>javier</td>
                <td>javier</td>
                <td>javier@gmail.com</td>
                <td class="text-center">
                  <div class="d-inline-flex gap-2">
                    <button class="btn btn-info px-3 me-2" data-bs-toggle="modal" data-bs-target="#modalUsuario"
                      onclick="cargarDatosUsuario('3','Javier López','Usuario','javier','javier','javier@gmail.com')">
                      <i class="fas fa-edit me-1"></i> Editar
                    </button>
                    <button class="btn btn-danger px-3" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario3">
                      <i class="fas fa-trash-alt me-1"></i> Eliminar
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Info y paginación -->
        <div class="row mt-2">
          <div class="col-md-6">
            <p>Mostrando registros del 1 al 15 de un total de 15 registros</p>
          </div>
          <div class="col-md-6 text-end">
            <nav>
              <ul class="pagination pagination-sm mb-0 justify-content-end">
                <li class="page-item disabled"><a class="page-link">Anterior</a></li>
                <li class="page-item active"><a class="page-link">1</a></li>
                <li class="page-item disabled"><a class="page-link">Siguiente</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Agregar/Editar Usuario -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border border-secondary">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="modalUsuarioLabel">Formulario de Usuario</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formUsuario">
          <div class="mb-3">
            <label for="usuarioId" class="form-label">ID</label>
            <input type="text" class="form-control" id="usuarioId" readonly>
          </div>
          <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" class="form-control" id="nombres" required>
          </div>
          <div class="mb-3">
            <label for="tipoUsuario" class="form-label">Tipo de Usuario</label>
            <select class="form-select" id="tipoUsuario" required>
              <option value="">Seleccione</option>
              <option value="Admin">Admin</option>
              <option value="Usuario">Usuario</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="usuario" required>
          </div>
          <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" required>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" required>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-primary" form="formUsuario">
          <i class="fas fa-save me-1"></i> Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modales de Eliminación -->

<!-- Usuario ID 1 -->
<div class="modal fade" id="modalEliminarUsuario1" tabindex="-1" aria-labelledby="modalEliminarUsuario1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarUsuario1Label">Confirmar Eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar al usuario <strong>Administrador</strong>?
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-danger" onclick="eliminarUsuario(1)">
          <i class="fas fa-trash-alt me-1"></i> Eliminar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Usuario ID 2 -->
<div class="modal fade" id="modalEliminarUsuario2" tabindex="-1" aria-labelledby="modalEliminarUsuario2Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarUsuario2Label">Confirmar Eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar al usuario <strong>Joel Pérez</strong>?
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-danger" onclick="eliminarUsuario(2)">
          <i class="fas fa-trash-alt me-1"></i> Eliminar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Usuario ID 3 -->
<div class="modal fade" id="modalEliminarUsuario3" tabindex="-1" aria-labelledby="modalEliminarUsuario3Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalEliminarUsuario3Label">Confirmar Eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que deseas eliminar al usuario <strong>Javier López</strong>?
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-danger" onclick="eliminarUsuario(3)">
          <i class="fas fa-trash-alt me-1"></i> Eliminar
        </button>
      </div>
    </div>
  </div>
</div>


