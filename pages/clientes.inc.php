<div class="container-fluid mt-4">
  <!-- Carta azul de título -->
  <div class="card bg-primary text-white shadow mb-3">
    <div class="card-body">
      <h2 class="mb-0">Administración de Clientes</h2>
    </div>
  </div>

  <!-- Carta blanca que contiene todo -->
  <div class="card shadow">
    <div class="card-body">
      <!-- Buscador solo por nombre -->
      <form class="row g-2 mb-3" id="form-buscar-clientes" autocomplete="off">
        <div class="col-md-7">
          <input type="text" class="form-control" id="input-busqueda-clientes" placeholder="Buscar por nombre comercial">
        </div>
        <div class="col-md-2 d-flex align-items-start">
          <button type="submit" class="btn btn-secondary w-100" style="min-width:110px;">Buscar</button>
        </div>
        <div class="col-md-3 d-flex justify-content-end gap-2">
          <a href="panel?pg=agregar-cliente" class="btn btn-success" style="min-width:130px;">+ Nuevo cliente</a>
          <a href="panel?pg=clientes-delete" class="btn btn-danger" style="min-width:130px;" title="Ver clientes suspendidos"><i class="bi bi-clipboard-x-fill"></i> Suspendidos</a>
        </div>
      </form>
      <!-- Tabla de clientes -->
      <div class="table-responsive" id="tabla-clientes-resultados">
        <?php include __DIR__ . '/../core/list-clientes.php'; ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Cliente -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarClienteLabel">Editar Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formEditarCliente">
        <div class="modal-body">
          <input type="hidden" id="edit_id_cliente" name="id_cliente">
          <div class="row g-2">
            <div class="col-md-6">
              <label class="form-label">Razón Social</label>
              <input type="text" class="form-control" id="edit_razon_social" name="razon_social">
            </div>
            <div class="col-md-6">
              <label class="form-label">Nombre Comercial</label>
              <input type="text" class="form-control" id="edit_nombre_comercial" name="nombre_comercial">
            </div>
            <div class="col-md-6">
              <label class="form-label">Régimen Fiscal</label>
              <input type="text" class="form-control" id="edit_regimen_fiscal" name="regimen_fiscal">
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="edit_telefono" name="telefono">
            </div>
            <div class="col-md-6">
              <label class="form-label">RFC</label>
              <input type="text" class="form-control" id="edit_rfc" name="rfc">
            </div>
            <div class="col-md-6">
              <label class="form-label">Contacto</label>
              <input type="text" class="form-control" id="edit_contacto" name="contacto">
            </div>
            <div class="col-md-6">
              <label class="form-label">Correo</label>
              <input type="email" class="form-control" id="edit_correo" name="correo">
            </div>
            <div class="col-md-6">
              <label class="form-label">Calle</label>
              <input type="text" class="form-control" id="edit_calle" name="calle">
            </div>
            <div class="col-md-3">
              <label class="form-label">N° Exterior</label>
              <input type="text" class="form-control" id="edit_n_exterior" name="n_exterior">
            </div>
            <div class="col-md-3">
              <label class="form-label">N° Interior</label>
              <input type="text" class="form-control" id="edit_n_interior" name="n_interior">
            </div>
            <div class="col-md-3">
              <label class="form-label">Entre Calle</label>
              <input type="text" class="form-control" id="edit_entre_calle" name="entre_calle">
            </div>
            <div class="col-md-3">
              <label class="form-label">Y Calle</label>
              <input type="text" class="form-control" id="edit_y_calle" name="y_calle">
            </div>
            <div class="col-md-3">
              <label class="form-label">País</label>
              <input type="text" class="form-control" id="edit_pais" name="pais">
            </div>
            <div class="col-md-3">
              <label class="form-label">CP</label>
              <input type="text" class="form-control" id="edit_cp" name="cp">
            </div>
            <div class="col-md-3">
              <label class="form-label">Estado</label>
              <input type="text" class="form-control" id="edit_estado" name="estado">
            </div>
            <div class="col-md-3">
              <label class="form-label">Municipio</label>
              <input type="text" class="form-control" id="edit_municipio" name="municipio">
            </div>
            <div class="col-md-3">
              <label class="form-label">Población</label>
              <input type="text" class="form-control" id="edit_poblacion" name="poblacion">
            </div>
            <div class="col-md-3">
              <label class="form-label">Colonia</label>
              <input type="text" class="form-control" id="edit_colonia" name="colonia">
            </div>
            <div class="col-md-3">
              <label class="form-label">Referencia</label>
              <input type="text" class="form-control" id="edit_referencia" name="referencia">
            </div>
            <!-- Campos comentados temporalmente
            <div class="col-md-3">
              <label class="form-label">Descuento (%)</label>
              <input type="number" step="0.01" class="form-control" id="edit_descuento" name="descuento">
            </div>
            <div class="col-md-3">
              <label class="form-label">Límite Crédito</label>
              <input type="number" step="0.01" class="form-control" id="edit_limite_credito" name="limite_credito">
            </div>
            <div class="col-md-3">
              <label class="form-label">Días Crédito</label>
              <input type="number" class="form-control" id="edit_dias_credito" name="dias_credito">
            </div>
            -->
            <!-- Nuevos campos agregados -->
            <!--
            <div class="col-md-3">
              <label class="form-label">Comisión A (%)</label>
              <input type="number" step="0.01" class="form-control" id="edit_comision_a" name="comision_a" placeholder="0.00">
            </div>
            <div class="col-md-3">
              <label class="form-label">Comisión B (%)</label>
              <input type="number" step="0.01" class="form-control" id="edit_comision_b" name="comision_b" placeholder="0.00">
            </div>
            <div class="col-md-3">
              <label class="form-label">Socio</label>
              <input type="text" class="form-control" id="edit_socio" name="socio" maxlength="100" placeholder="Nombre del socio">
            </div>
            -->
            <div class="col-md-3 d-flex align-items-end">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="edit_admin_cfdis" name="admin_cfdis" value="1">
                <label class="form-check-label" for="edit_admin_cfdis">
                  Admin CFDIs
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

