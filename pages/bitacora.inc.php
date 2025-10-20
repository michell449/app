
<div class="container-fluid px-5">
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4 w-100" style="min-height: 90vh; height: auto; padding: 40px;">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
          
          <div>
            <h2 class="mb-0">Minuta de junta</h2>
            <small class="text-white">Bitácora de minutas</small>
          </div>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="row">
              <!-- Izquierda -->
              <div class="col-md-6 border-end">
                <h5 class="text-primary">Datos de la Junta</h5>
                <div class="form-group mb-3">
                  <label for="empresa">Nombre de la empresa</label>
                  <input type="text" class="form-control" id="empresa" name="empresa">
                </div>
                <div class="form-group mb-3">
                  <label for="direccion">Lugar</label>
                  <input type="text" class="form-control" id="direccion" name="direccion">
                </div>
                <div class="form-group mb-3">
                  <label for="fecha">Fecha</label>
                  <input type="date" class="form-control" id="fecha" name="fecha">
                </div>
                <div class="form-group mb-3">
                  <label for="hora_inicio">Hora de inicio</label>
                  <input type="time" class="form-control" id="hora_inicio" name="hora_inicio">
                </div>
                <div class="form-group mb-3">
                  <label for="responsable">Responsable</label>
                  <input type="text" class="form-control" id="responsable" name="responsable">
                </div>
                <div class="form-group mb-3">
                  <label for="tema_principal">Tema principal</label>
                  <input type="text" class="form-control" id="tema_principal" name="tema_principal">
                </div>
                <div class="form-group mb-3">
                  <label for="objetivo_general">Objetivo general</label>
                  <input type="text" class="form-control" id="objetivo_general" name="objetivo_general">
                </div>
                <div class="form-group mb-3">
                  <label for="participantes">Participantes</label>
                  <input type="text" class="form-control" id="participantes" name="participantes">
                </div>
                <div class="row mb-3">
                  <div class="col">
                    <label for="elaborada_por">Elaborada por</label>
                    <input type="text" class="form-control" id="elaborada_por" name="elaborada_por">
                  </div>
                  <div class="col">
                    <label for="aprobada_por">Aprobada por</label>
                    <input type="text" class="form-control" id="aprobada_por" name="aprobada_por">
                  </div>
                </div>
              </div>
              <!-- Derecha -->
              <div class="col-md-6">
                <h5 class="text-secondary">Participantes</h5>
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead class="text-center">
                      <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Cargo</th>
                        <th>Correo electrónico</th>
                        <th>Acción</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input type="text" class="form-control" name="nombre[]"></td>
                        <td><input type="text" class="form-control" name="apellido[]"></td>
                        <td><input type="text" class="form-control" name="cargo[]"></td>
                        <td><input type="email" class="form-control" name="correo[]"></td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-participante">Eliminar</button></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <button type="button" class="btn btn-sm btn-success mb-4" id="addParticipante">Agregar participante</button>
                <div class="form-group mb-3 d-flex flex-column">
                  <label for="descripcion" class="mb-2">Descripción</label>
                  <textarea id="descripcion" name="descripcion" class="form-control rich-text" style="min-height: 500px; resize: vertical;"></textarea>
                </div>
              </div>
            </div>
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-primary px-5">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php