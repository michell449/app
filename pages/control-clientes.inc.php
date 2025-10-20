<?php
// Incluir lógica y datos del control de clientes
require_once __DIR__ . '/../core/control-clientes-data.php';
?>

<div class="container-fluid mt-4">
  <!-- Carta azul de título -->
  <div class="card bg-primary text-white shadow mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Control de Clientes</h2>
        <div class="d-flex align-items-center gap-3">
          <div class="bg-white bg-opacity-90 rounded-3 px-3 py-2 text-dark" id="clienteSeleccionado" style="display: none;">
            <div class="d-flex align-items-center">
              <i class="bi bi-building text-primary me-2"></i>
              <div>
                <small class="text-muted d-block mb-0">Cliente:</small>
                <strong class="fs-6" id="cliente-nombre">Cargando...</strong>
              </div>
            </div>
          </div>
          <a href="panel?pg=clientes" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Clientes
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Control de documentos del cliente -->
  <div id="seccionControlCliente">

    <!-- Barra de navegación de secciones -->
    <div class="card shadow mb-3">
      <div class="card-body">
        <ul class="nav nav-pills nav-fill" id="controlClientesTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="documentos-fiscales-tab" data-bs-toggle="pill" data-bs-target="#documentos-fiscales" type="button" role="tab">
              <i class="bi bi-file-earmark-text"></i> Documentos Fiscales
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="documentos-legales-tab" data-bs-toggle="pill" data-bs-target="#documentos-legales" type="button" role="tab">
              <i class="bi bi-file-earmark-check"></i> Documentos Legales
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contactos-empresariales-tab" data-bs-toggle="pill" data-bs-target="#contactos-empresariales" type="button" role="tab">
              <i class="bi bi-people"></i> Contactos
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="documentos-bancarios-tab" data-bs-toggle="pill" data-bs-target="#documentos-bancarios" type="button" role="tab">
              <i class="bi bi-bank"></i> Documentos Bancarios
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="identidad-corporativa-tab" data-bs-toggle="pill" data-bs-target="#identidad-corporativa" type="button" role="tab">
              <i class="bi bi-building"></i> Identidad Corporativa
            </button>
          </li>
        </ul>
      </div>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="controlClientesTabContent">
      
      <!-- Documentos Fiscales -->
      <div class="tab-pane fade show active" id="documentos-fiscales" role="tabpanel">
        <div class="row">
          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-pen"></i> Firma Electrónica</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Certificados digitales y firmas electrónicas</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="documentos" data-tipo="firma_electronica">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <input type="file" class="form-control form-control-sm" accept=".cer,.key,.p12,.pfx">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="fiscales" data-tipo="firma_electronica">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> Constancia de Situación Fiscal</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Constancia actualizada del SAT</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="documentos" data-tipo="constancia_fiscal">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <input type="file" class="form-control form-control-sm" accept=".pdf,.jpg,.png">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="fiscales" data-tipo="constancia_fiscal">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="bi bi-mailbox"></i> Buzón Tributario</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Documentos del buzón tributario del SAT</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="documentos" data-tipo="buzon_tributario">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <input type="file" class="form-control form-control-sm" accept=".pdf,.xml">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="fiscales" data-tipo="buzon_tributario">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="bi bi-check-circle"></i> Opinión de Cumplimiento</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Opinión del cumplimiento fiscal</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="documentos" data-tipo="opinion_cumplimiento">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <input type="file" class="form-control form-control-sm" accept=".pdf">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="fiscales" data-tipo="opinion_cumplimiento">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="bi bi-house"></i> INFONAVIT</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Documentos y constancias del INFONAVIT</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="documentos" data-tipo="infonavit">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <input type="file" class="form-control form-control-sm" accept=".pdf,.jpg,.png">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="fiscales" data-tipo="infonavit">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4 mb-3">
            <div class="card h-100">
              <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="bi bi-heart-pulse"></i> IMSS</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Documentos del IMSS</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="documentos" data-tipo="imss">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <input type="file" class="form-control form-control-sm" accept=".pdf,.jpg,.png">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="fiscales" data-tipo="imss">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Documentos Legales -->
      <div class="tab-pane fade" id="documentos-legales" role="tabpanel">
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card h-100">
              <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-person-badge"></i> Identificaciones de Representantes</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Identificaciones oficiales de representantes</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="expedientes" data-tipo="identificacion_representante">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <select class="form-select form-select-sm mb-1">
                    <option value="">Tipo de identificación</option>
                    <option value="ine">INE/IFE</option>
                    <option value="pasaporte">Pasaporte</option>
                    <option value="cedula">Cédula Profesional</option>
                  </select>
                  <input type="file" class="form-control form-control-sm" accept=".pdf,.jpg,.png">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="legales" data-tipo="identificacion_representante">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="card h-100">
              <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> Actas Constitutivas</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Acta constitutiva y modificaciones</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="expedientes" data-tipo="acta_constitutiva">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="mb-2">
                  <select class="form-select form-select-sm mb-1">
                    <option value="">Tipo de acta</option>
                    <option value="constitutiva">Acta Constitutiva</option>
                    <option value="modificacion">Modificación</option>
                    <option value="protocolizacion">Protocolización</option>
                  </select>
                  <input type="file" class="form-control form-control-sm" accept=".pdf">
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="legales" data-tipo="acta_constitutiva">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="bi bi-folder"></i> Carátulas</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Carátulas de expedientes y documentos importantes</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="expedientes" data-tipo="caratula">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="row">
                  <div class="col-md-4 mb-2">
                    <select class="form-select form-select-sm">
                      <option value="">Tipo de carátula</option>
                      <option value="expediente-fiscal">Expediente Fiscal</option>
                      <option value="expediente-legal">Expediente Legal</option>
                      <option value="expediente-laboral">Expediente Laboral</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-2">
                    <input type="file" class="form-control form-control-sm" accept=".pdf,.jpg,.png">
                  </div>
                  <div class="col-md-4">
                  </div>
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Archivos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="legales" data-tipo="caratula">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Contactos Empresariales -->
      <div class="tab-pane fade" id="contactos-empresariales" role="tabpanel">
        <div class="card">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-envelope"></i> Correos de Contacto por Empresa</h5>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoContacto">
              <i class="bi bi-plus"></i> Nuevo Contacto
            </button>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Contraseña</th>
                    <th>Tipo de Cuenta</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tablaContactos">
                  <tr>
                    <td colspan="5" class="text-center text-muted">No hay contactos registrados</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Documentos Bancarios -->
      <div class="tab-pane fade" id="documentos-bancarios" role="tabpanel">
        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-bank2"></i> Estados de Cuenta Bancarios</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoEstadoCuenta">
                  <i class="bi bi-plus"></i> Nuevo Estado de Cuenta
                </button>
              </div>
              <div class="card-body">
                <p class="text-muted small mb-3">Gestiona los estados de cuenta bancarios del cliente. Cada estado de cuenta debe incluir banco, período y número de cuenta.</p>
                
                <!-- Lista de archivos existentes para estados de cuenta -->
                <div class="mt-3">
                  <h6 class="text-muted mb-2">Estados de cuenta subidos:</h6>
                  <div class="archivos-existentes" data-categoria="bancarios" data-tipo="estado_cuenta">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
                
                <div class="table-responsive d-none">
                  <!-- Tabla anterior ocultada -->
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Banco</th>
                        <th>Cuenta</th>
                        <th>Período</th>
                        <th>Fecha Subida</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="tablaEstadosCuenta">
                      <tr>
                        <td colspan="5" class="text-center text-muted">No hay estados de cuenta registrados</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Identidad Corporativa -->
      <div class="tab-pane fade" id="identidad-corporativa" role="tabpanel">
        <div class="row">
          <div class="col-md-12 mb-3">
            <div class="card">
              <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-image"></i> Logo de la Empresa</h6>
              </div>
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <p class="text-muted small mb-0">Logotipo corporativo y variantes</p>
                  <button class="btn btn-success btn-sm btn-subir-documento" data-seccion="logos-empresa" data-tipo="logo_empresa">
                    <i class="bi bi-upload"></i> Subir
                  </button>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-2">
                      <label class="form-label small">Logo Principal</label>
                      <input type="file" class="form-control form-control-sm" accept=".png,.jpg,.jpeg,.svg,.gif">
                      <small class="text-muted">PNG, JPG, SVG (máx. 5MB)</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-2">
                      <label class="form-label small">Logo Alternativo</label>
                      <input type="file" class="form-control form-control-sm" accept=".png,.jpg,.jpeg,.svg,.gif">
                      <small class="text-muted">Versión monocromática</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="border rounded p-2 bg-light text-center d-flex align-items-center justify-content-center" style="height: 80px;">
                      <div class="logo-preview">
                        <span class="text-muted small">
                          <i class="bi bi-image"></i><br>
                          Selecciona un logo para ver la vista previa
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Lista de archivos existentes -->
                <div class="mt-3">
                  <h6 class="text-muted small mb-2">Logos subidos:</h6>
                  <div class="archivos-existentes" data-categoria="corporativos" data-tipo="logo_empresa">
                    <div class="text-center text-muted small">
                      <i class="bi bi-hourglass-split"></i> Cargando...
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal Nuevo Contacto -->
<div class="modal fade" id="modalNuevoContacto" tabindex="-1" aria-labelledby="modalNuevoContactoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoContactoLabel">Nuevo Contacto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formNuevoContacto">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre Completo</label>
            <input type="text" class="form-control" name="nombre" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" name="correo" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" class="form-control" name="password">
            <small class="text-muted">Dejar en blanco si no se conoce</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipo de Cuenta</label>
            <select class="form-select" name="tipo_cuenta" required>
              <option value="">Seleccionar tipo</option>
              <option value="empresarial">Empresarial</option>
              <option value="personal">Personal</option>
              <option value="sat">SAT</option>
              <option value="infonavit">INFONAVIT</option>
              <option value="imss">IMSS</option>
              <option value="bancario">Bancario</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Notas (Opcional)</label>
            <textarea class="form-control" name="notas" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Contacto</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Nuevo Estado de Cuenta -->
<div class="modal fade" id="modalNuevoEstadoCuenta" tabindex="-1" aria-labelledby="modalNuevoEstadoCuentaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoEstadoCuentaLabel">Nuevo Estado de Cuenta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form id="formNuevoEstadoCuenta">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Banco</label>
            <select class="form-select" name="banco" required>
              <option value="">Seleccionar banco</option>
              <option value="bbva">BBVA</option>
              <option value="santander">Santander</option>
              <option value="banamex">Banamex</option>
              <option value="banorte">Banorte</option>
              <option value="hsbc">HSBC</option>
              <option value="scotia">Scotia Bank</option>
              <option value="inbursa">Inbursa</option>
              <option value="azteca">Banco Azteca</option>
              <option value="otro">Otro</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Número de Cuenta</label>
            <input type="text" class="form-control" name="numero_cuenta" placeholder="Últimos 4 dígitos">
          </div>
          <div class="mb-3">
            <label class="form-label">Período</label>
            <input type="month" class="form-control" name="periodo" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Archivo</label>
            <input type="file" class="form-control" name="archivo" accept=".pdf,.jpg,.png" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Estado de Cuenta</button>
        </div>
      </form>
    </div>
  </div>
</div>


