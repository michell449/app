<!-- Módulo de Control de Pagos -->
<div class="container-fluid mt-4">
  <!-- Encabezado principal -->
  <div class="card bg-primary text-white shadow mb-3">
    <div class="card-body">
      <h2 class="mb-0"><i class="fas fa-credit-card me-2"></i>Control de Pagos</h2>
    </div>
  </div>



  <!-- Acciones principales -->
  <div class="card shadow">
    <div class="card-header bg-light  justify-content-between align-items-center">
      <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Registro de Pagos</h5>
      <div class="card-tools">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPago">
          <i class="fas fa-plus me-1"></i>Registrar Pago
        </button>
        </div>
    </div>
    <div class="card-body">
      <!-- Filtros -->
      <div class="row mb-3">
        <div class="col-md-4">
          <label for="filtroEmpresa" class="form-label">Filtrar por Empresa:</label>
          <input type="text" class="form-control" id="filtroEmpresa" placeholder="Buscar empresa..." onkeyup="cargarPagos()">
        </div>
        <div class="col-md-3">
          <label for="filtroStatus" class="form-label">Filtrar por Estado:</label>
          <select class="form-select" id="filtroStatus" onchange="cargarPagos()">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="pagado">Pagado</option>
            <option value="vencido">Vencido</option>
            <option value="cancelado">Cancelado</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">&nbsp;</label>
          <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="cargarPagos()" title="Actualizar">
              <i class="fas fa-sync-alt"></i>
            </button>
           
          </div>
        </div>
        <div class="col-md-2">
          <label class="form-label">Total:</label>
          <div class="badge bg-info fs-6" id="totalPagos">0</div>
        </div>
      </div>
      
      <!-- Tabla de pagos -->
      <div class="table-responsive">
        <table class="table table-bordered table-hover" id="tabla-pagos">
            <thead class="table-secondary">
            <tr>
              <th width="15%">Empresa</th>
              <th width="15%">Compañía</th>
              <th width="12%">Cuenta/Contrato</th>
              <th width="10%">Monto</th>
              <th width="12%">Fecha Vencimiento</th>
              <th width="10%">Estado</th>
              <th width="16%">Acciones</th>
            </tr>
          </thead>
            <tbody id="tablaPagosBody">
            <!-- Los datos se cargarán dinámicamente -->
            </tbody>
        </table>
      </div>

      
    </div>
  </div>
</div>

<!-- Modal para Agregar -->
<div class="modal fade" id="modalAgregarPago" tabindex="-1" aria-labelledby="modalAgregarPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalAgregarPagoLabel">
          <i class="fas fa-plus me-2"></i>Registrar Nuevo Pago
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="form-pago" autocomplete="off">
          <input type="hidden" id="pago-id" name="id">
          
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-empresa" class="form-label">Empresa <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pago-empresa" name="empresa" required placeholder="Nombre de la empresa cliente">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-compania" class="form-label">Compañía <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pago-compania" name="compania" required placeholder="Compañía proveedora del servicio">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-cuenta-contrato" class="form-label">Cuenta/Contrato <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="pago-cuenta-contrato" name="cuenta_contrato" required placeholder="Ej: CT-2024-001">
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-monto" class="form-label">Monto <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" class="form-control" id="pago-monto" name="monto" required step="0.01" min="0" placeholder="0.00">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-fecha-vencimiento" class="form-label">Fecha Vencimiento <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="pago-fecha-vencimiento" name="fecha_vencimiento" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-fecha-pago" class="form-label">Fecha de Pago</label>
                <input type="date" class="form-control" id="pago-fecha-pago" name="fecha_pago">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-metodo-pago" class="form-label">Método de Pago</label>
                <select class="form-select" id="pago-metodo-pago" name="metodo_pago">
                  <option value="">Seleccionar método...</option>
                  <option value="transferencia">Transferencia</option>
                  <option value="cheque">Cheque</option>
                  <option value="deposito">Depósito</option>
                  <option value="efectivo">Efectivo</option>
                  <option value="tarjeta">Tarjeta</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-referencia" class="form-label">Referencia</label>
                <input type="text" class="form-control" id="pago-referencia" name="referencia" placeholder="Número de referencia">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-status" class="form-label">Estado del Pago</label>
                <select class="form-select" id="pago-status" name="status">
                  <option value="pendiente">Pendiente</option>
                  <option value="pagado">Pagado</option>
                  <option value="vencido">Vencido</option>
                  <option value="cancelado">Cancelado</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="pago-observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="pago-observaciones" name="observaciones" rows="2" placeholder="Notas adicionales"></textarea>
              </div>
            </div>
          </div>

          <!-- Datos de Acceso -->
          <div class="card mt-3">
            <div class="card-header bg-info text-white">
              <h6 class="mb-0"><i class="fas fa-key me-2"></i>Datos de Acceso (Opcional)</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="pago-usuario-acceso" class="form-label">Usuario de Acceso</label>
                    <input type="text" class="form-control" id="pago-usuario-acceso" name="usuario_acceso" placeholder="Usuario del portal">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="pago-password-acceso" class="form-label">Contraseña de Acceso</label>
                    <input type="password" class="form-control" id="pago-password-acceso" name="password_acceso" placeholder="Contraseña del portal">
                  </div>
                </div>
              </div>
            </div>
          </div>

         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Cancelar
        </button>
        <button type="button" class="btn btn-primary" onclick="guardarPago()">
          <i class="fas fa-save me-1"></i>Guardar Pago
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Editar Pago -->
<div class="modal fade" id="modalEditarPago" tabindex="-1" aria-labelledby="modalEditarPagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="modalEditarPagoLabel">
          <i class="fas fa-edit me-2"></i>Editar Información del Pago
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="form-editar-pago" autocomplete="off">
          <input type="hidden" id="edit-pago-id" name="id">
          
          <!-- Información Básica -->
          <div class="card mb-3">
            <div class="card-header bg-primary text-white">
              <h6 class="mb-0"><i class="fas fa-building me-2"></i>Información Básica del Pago</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-empresa" class="form-label">Empresa <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit-empresa" name="empresa" required placeholder="Nombre de la empresa cliente">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-compania" class="form-label">Compañía <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit-compania" name="compania" required placeholder="Compañía proveedora del servicio">
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-cuenta-contrato" class="form-label">Cuenta/Contrato <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit-cuenta-contrato" name="cuenta_contrato" required placeholder="Ej: CT-2024-001">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-monto" class="form-label">Monto <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" class="form-control" id="edit-monto" name="monto" required step="0.01" min="0" placeholder="0.00">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Información de Fechas y Pago -->
          <div class="card mb-3">
            <div class="card-header bg-success text-white">
              <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Información de Fechas y Pago</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-fecha-vencimiento" class="form-label">Fecha Vencimiento <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="edit-fecha-vencimiento" name="fecha_vencimiento" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-fecha-pago" class="form-label">Fecha de Pago</label>
                    <input type="date" class="form-control" id="edit-fecha-pago" name="fecha_pago">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-metodo-pago" class="form-label">Método de Pago</label>
                    <select class="form-select" id="edit-metodo-pago" name="metodo_pago">
                      <option value="">Seleccionar método...</option>
                      <option value="transferencia">Transferencia</option>
                      <option value="cheque">Cheque</option>
                      <option value="deposito">Depósito</option>
                      <option value="efectivo">Efectivo</option>
                      <option value="tarjeta">Tarjeta</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-referencia" class="form-label">Referencia</label>
                    <input type="text" class="form-control" id="edit-referencia" name="referencia" placeholder="Número de referencia">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-estado" class="form-label">Estado del Pago</label>
                    <select class="form-select" id="edit-estado" name="status">
                      <option value="pendiente">Pendiente</option>
                      <option value="pagado">Pagado</option>
                      <option value="vencido">Vencido</option>
                      <option value="cancelado">Cancelado</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" id="edit-observaciones" name="observaciones" rows="2" placeholder="Notas adicionales"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Credenciales de Acceso -->
          <div class="card mb-3">
            <div class="card-header bg-info text-white">
              <h6 class="mb-0"><i class="fas fa-user-lock me-2"></i>Credenciales de Acceso</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-usuario-acceso" class="form-label">Usuario de Acceso</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                      <input type="text" class="form-control" id="edit-usuario-acceso" name="usuario_acceso" placeholder="usuario@empresa.com">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="edit-password-acceso" class="form-label">Contraseña de Acceso</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fas fa-lock"></i></span>
                      <input type="password" class="form-control" id="edit-password-acceso" name="password_acceso" placeholder="Contraseña de acceso">
                      <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordEdit('edit-password-acceso')">
                        <i class="fas fa-eye" id="eye-icon-edit"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Cancelar
        </button>
        <button type="button" class="btn btn-primary" onclick="resetearFormulario()">
          <i class="fas fa-undo me-1"></i>Resetear
        </button>
        <button type="button" class="btn btn-warning" onclick="guardarEdicionPago()">
          <i class="fas fa-save me-1"></i>Guardar Cambios
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Ver Detalle del Pago -->
<div class="modal fade" id="modalDetallePago" tabindex="-1" aria-labelledby="modalDetallePagoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalDetallePagoLabel">
          <i class="fas fa-eye me-2"></i>Detalle del Pago
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="detalle-pago-content">
        <!-- El contenido se cargará dinámicamente -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="imprimirDetalle()">
          <i class="fas fa-print me-1"></i>Imprimir
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Datos de Acceso -->
<div class="modal fade" id="modalDatosAcceso" tabindex="-1" aria-labelledby="modalDatosAccesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalDatosAccesoLabel">
          <i class="fas fa-key me-2"></i>Datos de la Cuenta
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="datos-acceso-content">
        <!-- El contenido se cargará dinámicamente -->
        <div class="row">
          <div class="col-md-6">
            <div class="card border-primary">
              <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Información de la Empresa</h6>
              </div>
              <div class="card-body">
                <p><strong>Empresa:</strong> <span id="datos-empresa">-</span></p>
                <p><strong>Compañía:</strong> <span id="datos-compania">-</span></p>
                <p><strong>Cuenta/Contrato:</strong> <span id="datos-cuenta">-</span></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-success">
              <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-user-lock me-2"></i>Credenciales de Acceso</h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label"><strong>Usuario:</strong></label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="usuario-acceso-display" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copiarTexto('usuario-acceso-display')">
                      <i class="fas fa-copy"></i>
                    </button>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label"><strong>Contraseña:</strong></label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="password-acceso-display" readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password-acceso-display')">
                      <i class="fas fa-eye" id="eye-icon"></i>
                    </button>
                    <button class="btn btn-outline-secondary" type="button" onclick="copiarTexto('password-acceso-display')">
                      <i class="fas fa-copy"></i>
                    </button>
                  </div>
                </div>
                
              </div>
            </div>
          </div>
        </div>
        
        <div class="row mt-3">
          <div class="col-12">
            <div class="card border-warning">
              <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información Adicional</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <p><strong>Fecha de Pago:</strong> <span id="fecha-creacion">-</span></p>
                  </div>
                  <div class="col-md-6">
                    <p><strong>Estado del Pago:</strong> <span id="estado-pago">-</span></p>
                    <p><strong>Próximo Vencimiento:</strong> <span id="proximo-vencimiento">-</span></p>
                  </div>
                </div>
                <div class="mt-2">
                  <p><strong>Notas:</strong></p>
                  <textarea class="form-control" id="notas-cuenta" rows="3" readonly placeholder="Sin notas adicionales"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     
    </div>
  </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos al inicio
    cargarPagos();
    
    // Eventos de los modales
    configurarEventosModales();
});

// Cargar pagos desde el servidor
function cargarPagos() {
    const filtroEmpresa = document.getElementById('filtroEmpresa').value;
    const filtroStatus = document.getElementById('filtroStatus').value;
    
    let url = 'core/list-pagos.php?';
    const params = new URLSearchParams();
    
    if (filtroEmpresa) params.append('empresa', filtroEmpresa);
    if (filtroStatus) params.append('status', filtroStatus);
    
    url += params.toString();
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarPagos(data.pagos);
                document.getElementById('totalPagos').textContent = data.total;
            } else {
                mostrarError('Error al cargar pagos: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión al cargar pagos');
        });
}

// Mostrar pagos en la tabla
function mostrarPagos(pagos) {
    const tbody = document.getElementById('tablaPagosBody');
    tbody.innerHTML = '';
    
    if (pagos.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <div class="text-muted">No hay pagos registrados</div>
                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalAgregarPago">
                        <i class="fas fa-plus me-1"></i>Agregar Primer Pago
                    </button>
                </td>
            </tr>
        `;
        return;
    }
    
    pagos.forEach(pago => {
        const statusClass = getStatusClass(pago.status);
        const statusText = getStatusText(pago.status);
        
        const row = `
            <tr>
                <td>${pago.empresa}</td>
                <td>${pago.compania}</td>
                <td><code>${pago.cuenta_contrato}</code></td>
                <td class="text-end">$${pago.monto}</td>
                <td>${pago.fecha_vencimiento_formato}</td>
                <td><span class="badge ${statusClass}">${statusText}</span></td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-outline-primary btn-sm" onclick="editarPago(${pago.id_pago})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="verDatosAcceso(${pago.id_pago})" title="Ver datos de acceso">
                            <i class="fas fa-key"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="eliminarPago(${pago.id_pago})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Obtener clase CSS para el status
function getStatusClass(status) {
    switch(status) {
        case 'pagado': return 'bg-success';
        case 'pendiente': return 'bg-warning text-dark';
        case 'vencido': return 'bg-danger';
        case 'cancelado': return 'bg-secondary';
        default: return 'bg-primary';
    }
}

// Obtener texto para el status
function getStatusText(status) {
    switch(status) {
        case 'pagado': return 'Pagado';
        case 'pendiente': return 'Pendiente';
        case 'vencido': return 'Vencido';
        case 'cancelado': return 'Cancelado';
        default: return status;
    }
}

// Configurar eventos de los modales
function configurarEventosModales() {
    // Modal agregar pago
    const formAgregar = document.getElementById('form-pago');
    if (formAgregar) {
        formAgregar.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarPago();
        });
    }
    
    // Modal editar pago
    const formEditar = document.getElementById('form-editar-pago');
    if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            guardarEdicionPago();
        });
    }
}

// Guardar nuevo pago
function guardarPago() {
    const form = document.getElementById('form-pago');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    fetch('core/agregar-pago.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                confirmButtonText: 'Aceptar'
            }).then(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarPago'));
                modal.hide();
                form.reset();
                cargarPagos();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonText: 'Cerrar'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonText: 'Cerrar'
        });
    });
}

// Editar pago existente
function editarPago(id) {
    fetch(`core/get-pago.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pago = data.pago;
                
                // Llenar formulario de edición
                document.getElementById('edit-pago-id').value = pago.id_pago;
                document.getElementById('edit-empresa').value = pago.empresa;
                document.getElementById('edit-compania').value = pago.compania;
                document.getElementById('edit-cuenta-contrato').value = pago.cuenta_contrato;
                document.getElementById('edit-monto').value = pago.monto;
                document.getElementById('edit-fecha-vencimiento').value = pago.fecha_vencimiento;
                document.getElementById('edit-fecha-pago').value = pago.fecha_pago || '';
                document.getElementById('edit-metodo-pago').value = pago.metodo_pago || '';
                document.getElementById('edit-referencia').value = pago.referencia || '';
                document.getElementById('edit-estado').value = pago.status;
                document.getElementById('edit-observaciones').value = pago.observaciones || '';
                document.getElementById('edit-usuario-acceso').value = pago.usuario_acceso || '';
                document.getElementById('edit-password-acceso').value = pago.password_acceso || '';
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalEditarPago'));
                modal.show();
            } else {
                mostrarError('Error al cargar datos del pago: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión al cargar datos del pago');
        });
}

// Guardar edición de pago
function guardarEdicionPago() {
    const form = document.getElementById('form-editar-pago');
    const formData = new FormData(form);
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    Swal.fire({
        title: '¿Guardar cambios?',
        text: "Se actualizará la información del pago",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('core/editar-pago.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarPago'));
                        modal.hide();
                        cargarPagos();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Cerrar'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    confirmButtonText: 'Cerrar'
                });
            });
        }
    });
}

// Ver datos de acceso
function verDatosAcceso(id) {
    fetch(`core/get-pago.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pago = data.pago;
                
                // Llenar modal de datos de acceso
                document.getElementById('datos-empresa').textContent = pago.empresa;
                document.getElementById('datos-compania').textContent = pago.compania;
                document.getElementById('datos-cuenta').textContent = pago.cuenta_contrato;
                document.getElementById('usuario-acceso-display').value = pago.usuario_acceso || 'No definido';
                document.getElementById('password-acceso-display').value = pago.password_acceso || 'No definido';
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalDatosAcceso'));
                modal.show();
            } else {
                mostrarError('Error al cargar datos del pago: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarError('Error de conexión al cargar datos del pago');
        });
}

// Eliminar pago
function eliminarPago(id) {
    Swal.fire({
        title: '¿Eliminar pago?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_pago', id);
            
            fetch('core/eliminar-pago.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Eliminado!',
                        text: data.message,
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        cargarPagos();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message,
                        confirmButtonText: 'Cerrar'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor',
                    confirmButtonText: 'Cerrar'
                });
            });
        }
    });
}

// Mostrar errores
function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        confirmButtonText: 'Cerrar'
    });
}

// Funciones auxiliares que se necesitan
function copiarTexto(elementId) {
    var elemento = document.getElementById(elementId);
    elemento.select();
    elemento.setSelectionRange(0, 99999); // Para móviles
    
    try {
        document.execCommand('copy');
        Swal.fire({
            icon: 'success',
            title: 'Copiado!',
            text: 'Texto copiado al portapapeles',
            timer: 1500,
            showConfirmButton: false
        });
    } catch (err) {
        console.error('Error al copiar: ', err);
    }
}

function togglePassword(elementId) {
    var elemento = document.getElementById(elementId);
    var icon = document.getElementById('eye-icon');
    
    if (elemento.type === 'password') {
        elemento.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        elemento.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function togglePasswordEdit(elementId) {
    var elemento = document.getElementById(elementId);
    var icon = document.getElementById('eye-icon-edit');
    
    if (elemento.type === 'password') {
        elemento.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        elemento.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function resetearFormulario() {
    Swal.fire({
        title: '¿Resetear formulario?',
        text: "Se perderán todos los cambios no guardados",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#28a745',
        confirmButtonText: 'Sí, resetear',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-editar-pago').reset();
            Swal.fire(
                'Reseteado!',
                'El formulario ha sido reseteado.',
                'success'
            );
        }
    });
}
</script>

<style>
/* Estilos específicos para el módulo de control de pagos */
.small-box {
    border-radius: 10px;
    position: relative;
    display: block;
    margin-bottom: 20px;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
}

.small-box > .inner {
    padding: 10px;
}

.small-box > .small-box-footer {
    position: relative;
    text-align: center;
    padding: 3px 0;
    color: #fff;
    color: rgba(255,255,255,0.8);
    display: block;
    z-index: 10;
    background: rgba(0,0,0,0.1);
    text-decoration: none;
}

.small-box .icon {
    -webkit-transition: all .3s linear;
    -o-transition: all .3s linear;
    transition: all .3s linear;
    position: absolute;
    top: -10px;
    right: 10px;
    z-index: 0;
    font-size: 90px;
    color: rgba(0,0,0,0.15);
}

.small-box h3 {
    font-size: 2.2rem;
    font-weight: bold;
    margin: 0 0 10px 0;
    white-space: nowrap;
    padding: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.btn-group-sm > .btn, .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

@media print {
    .modal-header, .modal-footer {
        display: none;
    }
}
</style>