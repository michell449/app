<!-- Card principal para equipos -->
<div class="card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 10px;">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-left" style="border-radius: 10px 10px 0 0;">
        <span><i class="fas fa-users"></i> Equipos</span>
  <button class="btn btn-success btn-sm ms-auto" id="btnAbrirModalEquipo"><i class="fas fa-plus"></i> Agregar</button>
    </div>
    <div class="card-body" style="background: #fafbfc; min-height: 300px;">
        <div id="equipos-list" class="d-flex flex-wrap justify-content-start align-items-stretch">
            <?php include __DIR__ . '/../core/list-equipos.php'; ?>
        </div>
        <!-- Modal para colaboradores del equipo -->
        <div class="modal fade" id="modalColaboradoresEquipo" tabindex="-1" aria-labelledby="modalColaboradoresEquipoLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalColaboradoresEquipoLabel">Colaboradores del equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div id="colaboradores-equipo-list"></div>
                    <div class="mb-3">
                        <label for="edit-colaborador" class="form-label">Agregar colaborador</label>
                        <select class="form-control" id="edit-colaborador" name="colaborador">
                        <option value="">Selecciona colaborador...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-rol" class="form-label">Rol</label>
                        <input type="text" class="form-control" id="edit-rol" name="rol" placeholder="Escribe el rol">
                    </div>
                    <button type="button" class="btn btn-primary mb-2" id="btnAgregarColaborador">Agregar colaborador</button>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>

<!-- Modal para editar equipo -->
<div class="modal fade" id="modalEditEquipo" tabindex="-1" aria-labelledby="modalEditEquipoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditEquipoLabel">Editar equipo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="form-edit-equipo" autocomplete="off">
          <input type="hidden" id="edit-id" name="id">
          <div class="mb-3">
            <label for="edit-nombre" class="form-label">Nombre del equipo</label>
            <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="edit-descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="edit-descripcion" name="descripcion" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="edit-privacidad" class="form-label">Privacidad</label>
            <select class="form-control" id="edit-privacidad" name="privacidad">
              <option value="Público">Público</option>
              <option value="Privado">Privado</option>
            </select>
          </div>
          <hr>
          
          
          <div id="msg-edit-equipo" class="mb-2"></div>
          <button type="submit" class="btn btn-primary w-100">Actualizar equipo</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal para crear equipo -->
<div class="modal fade" id="modalEquipo" tabindex="-1" aria-labelledby="modalEquipoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEquipoLabel">Crear nuevo equipo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="form-equipo" autocomplete="off">
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del equipo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label for="privacidad" class="form-label">Privacidad</label>
            <select class="form-control" id="privacidad" name="privacidad">
              <option value="Público">Público</option>
              <option value="Privado">Privado</option>
            </select>
          </div>
          <div id="msg-equipo" class="mb-2"></div>
          <button type="submit" class="btn btn-primary w-100">Crear equipo</button>
        </form>
      </div>
    </div>
  </div>
</div>

