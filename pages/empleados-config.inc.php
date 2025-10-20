<div class="card card-white shadow-sm mb-4">
  <div class="card-header bg-primary border-bottom d-flex justify-content-between align-items-center">
    <h4 class="card-title mb-0 text-white">Lista de Empleados</h4>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarEmpleado">
        <i class="fas fa-plus me-1"></i> Agregar Empleado
      </button>
    </div>
<!-- Modal Agregar Empleado -->
  <div class="modal fade" id="modalAgregarEmpleado" tabindex="-1" aria-labelledby="modalAgregarEmpleadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalAgregarEmpleadoLabel">Agregar Empleado</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form id="formAgregarEmpleado">
          <div class="modal-body">
            <div class="mb-3">
              <label for="nuevoNombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nuevoNombre" name="nombre" placeholder="Ingrese el nombre" required>
            </div>
            <div class="mb-3">
              <label for="nuevoApellidos" class="form-label">Apellidos</label>
              <input type="text" class="form-control" id="nuevoApellidos" name="apellidos" placeholder="Ingrese el apellido" required>
            </div>
            <div class="mb-3">
              <label for="nuevoCorreo" class="form-label">Correo</label>
              <input type="email" class="form-control" id="nuevoCorreo" name="correo" placeholder="correo@ejemplo.com" required>
            </div>
            <div class="mb-3">
              <label for="nuevoTelefono" class="form-label">Número de Teléfono</label>
              <input type="text" class="form-control" id="nuevoTelefono" name="telefono" placeholder="+1234567890">
            </div>
            <div class="mb-3">
              <label for="nuevoDepartamento" class="form-label">Departamento</label>
              <input type="text" class="form-control" id="nuevoDepartamento" name="departamento" placeholder="Ingrese el departamento">
            </div>
            <div class="mb-3">
              <label for="nuevoArea" class="form-label">Área</label>
              <input type="text" class="form-control" id="nuevoArea" name="area" placeholder="Ingrese el área">
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
<?php require_once __DIR__ . '/../core/list-empleados.php';?>
</div>
</div>
<!-- Modal Empleado con diseño tipo formulario moderno -->
<div class="modal fade" id="modalEmpleado" tabindex="-1" aria-labelledby="modalEmpleadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalEmpleadoLabel">Empleado</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formEmpleado">
        <div class="modal-body">
          <input type="hidden" id="empleadoId" name="empleadoId">
          <div class="mb-3">
            <label for="empleadoNombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="empleadoNombre" name="empleadoNombre" placeholder="Ingrese el nombre" readonly>
          </div>
          <div class="mb-3">
            <label for="empleadoApellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="empleadoApellidos" name="empleadoApellidos" placeholder="Ingrese el apellido" readonly>
          </div>
          <div class="mb-3">
            <label for="empleadoCorreo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="empleadoCorreo" name="empleadoCorreo" placeholder="correo@ejemplo.com" readonly>
          </div>
          <div class="mb-3">
            <label for="empleadoTelefono" class="form-label">Número de Teléfono</label>
            <input type="text" class="form-control" id="empleadoTelefono" name="empleadoTelefono" placeholder="+1234567890" readonly>
          </div>
          <div class="mb-3">
            <label for="empleadoDepartamento" class="form-label">Departamento</label>
            <input type="text" class="form-control" id="empleadoDepartamento" name="empleadoDepartamento" placeholder="Ingrese el departamento" readonly>
          </div>
          <div class="mb-3">
            <label for="empleadoArea" class="form-label">Área</label>
            <input type="text" class="form-control" id="empleadoArea" name="empleadoArea" placeholder="Ingrese el área" readonly>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn fw-bold text-white" id="btnEditarEmpleado" style="background:#ffc107;min-width:110px;">Editar</button>
          <button type="button" class="btn fw-bold text-white" id="btnGuardarEmpleado" style="background:#28a745;min-width:110px;display:none;">Guardar</button>
          <button type="button" class="btn fw-bold text-white" id="btnEliminarEmpleado" style="background:#dc3545;min-width:110px;">Eliminar</button>
          <button type="button" class="btn btn-outline-secondary" id="btnVolverEmpleado" style="min-width:90px;">Volver</button>
        </div>
      </form>
    </div>
  </div>
</div>