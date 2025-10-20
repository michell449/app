<div class="card card-white shadow-sm mb-4">
  <div class="card-header bg-primary border-bottom d-flex justify-content-between align-items-center">
    <h4 class="card-title mb-0 text-white">Lista de Usuarios</h4>
  </div>
  <div class="card-body">
    <div class="mb-3">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">
        <i class="fas fa-plus me-1"></i> Agregar Usuario
      </button>
    </div>
<!-- Modal Agregar Usuario -->
<div class="modal fade" id="modalAgregarUsuario" tabindex="-1" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalAgregarUsuarioLabel">Agregar Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formAgregarUsuario">
        <div class="modal-body">
          <div class="mb-3">
            <label for="nuevoNombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nuevoNombre" name="nombre" placeholder="Ingrese el nombre" required>
          </div>
          <div class="mb-3">
            <label for="nuevoApellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="nuevoApellido" name="apellido" placeholder="Ingrese el apellido" required>
          </div>
          <div class="mb-3">
            <label for="nuevoEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="nuevoEmail" name="email" placeholder="correo@ejemplo.com" required>
          </div>
          <div class="mb-3">
            <label for="nuevoPassword" class="form-label">Contraseña</label>
            <div class="input-group">
              <input type="password" class="form-control" id="nuevoPassword" name="password" placeholder="Ingrese la contraseña" required>
              <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                <i class="fas fa-eye" id="iconPassword"></i>
              </span>
            </div>
          </div>
          <div class="mb-3">
            <label for="nuevoTelefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="nuevoTelefono" name="telefono" placeholder="+1234567890">
          </div>
          <div class="mb-3">
            <label for="nuevoStatus" class="form-label">Status</label>
            <select class="form-control" id="nuevoStatus" name="status">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="nuevoPerfil" class="form-label">Perfil</label>
            <select class="form-control" id="nuevoPerfil" name="id_perfil">
              <option value="1">Super Admin</option>
              <option value="2">Admin</option>
              <option value="3">Usuario</option>
              <option value="4">Cliente</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success fw-bold" style="min-width:110px;">Guardar</button>
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="min-width:90px;">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../core/list-usuarios.php';?>
</div>
</div>
<!-- Modal Usuario con diseño tipo formulario moderno -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalUsuarioLabel">Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formUsuario">
        <div class="modal-body">
          <input type="hidden" id="usuarioId" name="usuarioId">
          <div class="mb-3">
            <label for="usuarioNombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="usuarioNombre" name="usuarioNombre" placeholder="Ingrese el nombre" readonly>
          </div>
          <div class="mb-3">
            <label for="usuarioApellido" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="usuarioApellido" name="usuarioApellido" placeholder="Ingrese el apellido" readonly>
          </div>
          <div class="mb-3">
            <label for="usuarioCorreo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="usuarioCorreo" name="usuarioCorreo" placeholder="correo@ejemplo.com" readonly>
          </div>
          <div class="mb-3">
            <label for="usuarioTelefono" class="form-label">Número de Teléfono</label>
            <input type="text" class="form-control" id="usuarioTelefono" name="usuarioTelefono" placeholder="+1234567890" readonly>
          </div>
          <div class="mb-3">
            <label for="usuarioStatus" class="form-label">Status</label>
            <select class="form-control" id="usuarioStatus" name="status" disabled>
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="usuarioPerfil" class="form-label">Perfil</label>
            <select class="form-control" id="usuarioPerfil" name="id_perfil" disabled>
              <option value="1">Super Admin</option>
              <option value="2">Admin</option>
              <option value="3">Usuario</option>
              <option value="4">Cliente</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn fw-bold text-white" id="btnEditarUsuario" style="background:#ffc107;min-width:110px;">Editar</button>
          <button type="button" class="btn fw-bold text-white" id="btnGuardarUsuario" style="background:#28a745;min-width:110px;display:none;">Guardar</button>
          <button type="button" class="btn btn-outline-secondary" id="btnVolverUsuario" style="min-width:90px;">Volver</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- FIN DEL MODAL USUARIO -->
<?php require_once __DIR__ . '/../pages/app-footer.inc.php';?>
