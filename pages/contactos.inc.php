<div class="container-fluid mt-4">
  <!-- Título -->
  <div class="card bg-primary text-white shadow mb-3">
    <div class="card-body">
      <h2 class="mb-0">Administración de Contactos</h2>
    </div>
  </div>

  <div class="card shadow">
    <div class="card-body">
      <div class="mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarContacto">
          Agregar Contacto
        </button>
          <!-- Botón para editar contacto, ejemplo: -->
          <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarContacto" onclick="cargarDatosContacto(id)">Editar Contacto</button> -->
      </div>
      <div class="table-responsive">
        <?php include __DIR__ . '/../core/list-contactos.php'; ?>
      </div>
    </div>
  </div>

    <!-- Modal para editar contacto -->
    <div class="modal fade" id="modalEditarContacto" tabindex="-1" aria-labelledby="modalEditarContactoLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="formEditarContacto">
            <div class="modal-header" style="background-color: #1976d2; color: #fff;">
              <h5 class="modal-title" id="modalEditarContactoLabel">Editar Contacto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" style="background: #fff;">
              <input type="hidden" name="id_contacto" id="edit_id_contacto">
              <div class="mb-3">
                <label class="form-label">Nombre completo</label>
                <input type="text" class="form-control" name="nombre" id="edit_nombre" placeholder="Ingrese el nombre">
              </div>
              <div class="mb-3">
                <label class="form-label">Teléfono(s)</label>
                <input type="text" class="form-control" name="telefono" id="edit_telefono" placeholder="Ingrese el teléfono">
              </div>
              <div class="mb-3">
                <label class="form-label">WhatsApp</label>
                <input type="text" class="form-control" name="whatsapp" id="edit_whatsapp" placeholder="Ingrese el WhatsApp">
              </div>
              <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="correo" id="edit_correo" placeholder="correo@ejemplo.com">
              </div>
              <div class="mb-3">
                <label class="form-label">Cliente / Empresa</label>
                <select class="form-select" name="cliente_empresa" id="edit_cliente_empresa">
                  <option value="">Seleccione un cliente</option>
                  <?php include __DIR__ . '/../core/list-clientes-select.php'; ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Puesto o cargo</label>
                <input type="text" class="form-control" name="puesto" id="edit_puesto" placeholder="Ingrese el puesto o cargo">
              </div>
              <div class="mb-3">
                <label class="form-label">Departamento</label>
                <input type="text" class="form-control" name="departamento" id="edit_departamento" placeholder="Ingrese el departamento">
              </div>
              <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" id="edit_direccion" placeholder="Ingrese la dirección">
              </div>
            </div>
            <div class="modal-footer" style="background: #fff;">
              <button type="submit" class="btn btn-primary">Actualizar</button>
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  <!-- Modal para agregar contacto -->
  <div class="modal fade" id="modalAgregarContacto" tabindex="-1" aria-labelledby="modalAgregarContactoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
  <form id="formAgregarContacto">
          <div class="modal-header" style="background-color: #1976d2; color: #fff;">
            <h5 class="modal-title" id="modalAgregarContactoLabel">Agregar Contacto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" style="filter: invert(1);"></button>
          </div>
          <div class="modal-body" style="background: #fff;">
            <div class="mb-3">
              <label class="form-label">Nombre completo</label>
              <input type="text" class="form-control" name="nombre" placeholder="Ingrese el nombre">
            </div>
            <div class="mb-3">
              <label class="form-label">Teléfono(s)</label>
              <input type="text" class="form-control" name="telefono" placeholder="Ingrese el teléfono">
            </div>
            <div class="mb-3">
              <label class="form-label">WhatsApp</label>
              <input type="text" class="form-control" name="whatsapp" placeholder="Ingrese el WhatsApp">
            </div>
            <div class="mb-3">
              <label class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" name="correo" placeholder="correo@ejemplo.com">
            </div>
            <div class="mb-3">
              <label class="form-label">Cliente / Empresa</label>
              <select class="form-select" name="cliente_empresa">
                <option value="">Seleccione un cliente</option>
                <?php include __DIR__ . '/../core/list-clientes-select.php'; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Puesto o cargo</label>
              <input type="text" class="form-control" name="puesto" placeholder="Ingrese el puesto o cargo">
            </div>
            <div class="mb-3">
              <label class="form-label">Departamento</label>
              <input type="text" class="form-control" name="departamento" placeholder="Ingrese el departamento">
            </div>
            <div class="mb-3">
              <label class="form-label">Dirección</label>
              <input type="text" class="form-control" name="direccion" placeholder="Ingrese la dirección">
            </div>
          </div>
          <div class="modal-footer" style="background: #fff;">
            <button type="submit" class="btn btn-success">Guardar</button>
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

