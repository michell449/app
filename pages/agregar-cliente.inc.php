<div class="container mt-4">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Agregar Cliente</h5>
    </div>
    <div class="card-body">
      <form id="formAgregarCliente">
        <!-- Datos Generales -->
        <div class="card mb-4">
          <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">Datos Generales</h6>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="razon_social" class="form-label">*Razón social</label>
                <input type="text" class="form-control" id="razon_social" name="razon_social" required>
              </div>
              <div class="col-md-4">
                <label for="nombre_comercial" class="form-label">Nombre comercial</label>
                <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial">
              </div>
              <div class="col-md-4">
                <label for="rfc" class="form-label">*RFC</label>
                <input type="text" class="form-control" id="rfc" name="rfc" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="regimen_fiscal" class="form-label">*Régimen fiscal</label>
                <select class="form-select" id="regimen_fiscal" name="regimen_fiscal" required>
                  <option value="">Cargando...</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="contacto" class="form-label">Contacto</label>
                <input type="text" class="form-control" id="contacto" name="contacto">
              </div>
              <div class="col-md-4">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" id="correo" name="correo">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono">
              </div>
              <div class="col-md-8">
                <div class="card bg-light border-info">
                  <div class="card-body p-3">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="generar_usuario" name="generar_usuario" checked>
                      <label class="form-check-label" for="generar_usuario">
                        <strong>Generar usuario para portal de cliente</strong>
                      </label>
                    </div>
                    <small class="text-muted">
                      <i class="bi bi-info-circle me-1"></i>
                      Si está marcado, se creará automáticamente un usuario para que el cliente pueda acceder a su portal de documentos. 
                      La contraseña será su RFC.
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Dirección -->
        <div class="card mb-4">
          <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">Dirección</h6>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-md-3">
                <label for="calle" class="form-label">Calle</label>
                <input type="text" class="form-control" id="calle" name="calle">
              </div>
              <div class="col-md-2">
                <label for="no_interior" class="form-label">No. Interior</label>
                <input type="text" class="form-control" id="no_interior" name="no_interior">
              </div>
              <div class="col-md-2">
                <label for="no_exterior" class="form-label">No. Exterior</label>
                <input type="text" class="form-control" id="no_exterior" name="no_exterior">
              </div>
              <div class="col-md-2">
                <label for="entre_calle" class="form-label">Entre calle</label>
                <input type="text" class="form-control" id="entre_calle" name="entre_calle">
              </div>
              <div class="col-md-2">
                <label for="y_calle" class="form-label">Y calle</label>
                <input type="text" class="form-control" id="y_calle" name="y_calle">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-3">
                <label for="pais" class="form-label">*País</label>
                <select class="form-select" id="pais" name="pais" required>
                  <option value="MEX">MEX. México</option>
                </select>
              </div>
              <div class="col-md-2">
                <label for="cp" class="form-label">*C.P.</label>
                <input type="text" class="form-control" id="cp" name="cp" required>
              </div>
              <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado">
              </div>
              <div class="col-md-2">
                <label for="municipio" class="form-label">Municipio</label>
                <input type="text" class="form-control" id="municipio" name="municipio">
              </div>
              <div class="col-md-3">
                <label for="colonia" class="form-label">Colonia</label>
                <input type="text" class="form-control" id="colonia" name="colonia">
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-3">
                <label for="poblacion" class="form-label">Población</label>
                <input type="text" class="form-control" id="poblacion" name="poblacion">
              </div>
              <div class="col-md-3">
                <label for="referencia" class="form-label">Referencia</label>
                <input type="text" class="form-control" id="referencia" name="referencia">
              </div>
            </div>
          </div>
        </div>
        <!-- Datos Comerciales -->
        <div class="card mb-4">
          <div class="card-header bg-secondary text-white">
            <h6 class="mb-0">Datos Comerciales</h6>
          </div>
          <div class="card-body">
            <!-- Fila para comisiones y configuraciones -->
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="form-check mt-4">
                  <input type="checkbox" class="form-check-input" id="admin_cfdis" name="admin_cfdis" value="1">
                  <label class="form-check-label fw-bold" for="admin_cfdis">
                    Habilitar administración de CFDI's para este cliente
                    <span class="text-muted d-block" style="font-size:0.95em;">Marca esta opción si el cliente tendrá acceso a la administración de CFDI's.</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
