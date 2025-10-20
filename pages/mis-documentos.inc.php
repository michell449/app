<div class="container-fluid mt-4">
  <!-- Carta azul de título - Igual que control-clientes -->
  <div class="card bg-primary text-white shadow mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Mis Documentos</h2>
        <div class="d-flex align-items-center gap-3">
          <div class="bg-white bg-opacity-90 rounded-3 px-3 py-2 text-dark">
            <div class="d-flex align-items-center">
              <i class="bi bi-building text-primary me-2"></i>
              <div>
                <small class="text-muted d-block mb-0">Cliente:</small>
                <strong class="fs-6" id="cliente-nombre">Cargando...</strong>
              </div>
            </div>
          </div>
          <div class="bg-white bg-opacity-90 rounded-3 px-3 py-2 text-dark">
            <div class="d-flex align-items-center">
              <i class="bi bi-check-circle-fill text-success me-2"></i>
              <div>
                <small class="text-muted d-block mb-0">RFC:</small>
                <strong class="fs-6" id="cliente-rfc">Cargando...</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Navegación por categorías - Estilo igual a control-clientes -->
  <div class="card shadow mb-3">
    <div class="card-body">
      <ul class="nav nav-pills nav-fill" id="documentosTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="fiscales-tab" data-bs-toggle="pill" data-bs-target="#fiscales" type="button" role="tab">
            <i class="bi bi-file-earmark-text"></i> Documentos Fiscales
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="legales-tab" data-bs-toggle="pill" data-bs-target="#legales" type="button" role="tab">
            <i class="bi bi-file-earmark-check"></i> Documentos Legales
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="bancarios-tab" data-bs-toggle="pill" data-bs-target="#bancarios" type="button" role="tab">
            <i class="bi bi-bank"></i> Documentos Bancarios
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="corporativos-tab" data-bs-toggle="pill" data-bs-target="#corporativos" type="button" role="tab">
            <i class="bi bi-building"></i> Identidad Corporativa
          </button>
        </li>
      </ul>
    </div>
  </div>

  <!-- Contenido de las pestañas -->
  <div class="tab-content" id="documentosTabContent">
    
    <!-- Documentos Fiscales -->
    <div class="tab-pane fade show active" id="fiscales" role="tabpanel">
      <div class="row" id="documentos-fiscales">
        <div class="col-12 text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando documentos fiscales...</span>
          </div>
          <p class="mt-2 text-muted">Cargando documentos fiscales...</p>
        </div>
      </div>
    </div>

    <!-- Documentos Legales -->
    <div class="tab-pane fade" id="legales" role="tabpanel">
      <div class="row" id="documentos-legales">
        <div class="col-12 text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando documentos legales...</span>
          </div>
          <p class="mt-2 text-muted">Cargando documentos legales...</p>
        </div>
      </div>
    </div>

    <!-- Documentos Bancarios -->
    <div class="tab-pane fade" id="bancarios" role="tabpanel">
      <div class="card shadow">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0"><i class="bi bi-bank2"></i> Estados de Cuenta Bancarios</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead class="table-light">
                <tr>
                  <th>Banco</th>
                  <th>Cuenta</th>
                  <th>Período</th>
                  <th>Fecha Subida</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="estados-cuenta-tbody">
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Cargando estados de cuenta...</span>
                    </div>
                    <p class="mt-2 text-muted">Cargando estados de cuenta...</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Recursos Corporativos -->
    <div class="tab-pane fade" id="corporativos" role="tabpanel">
      <div class="row" id="recursos-corporativos">
        <div class="col-12 text-center py-4">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando recursos corporativos...</span>
          </div>
          <p class="mt-2 text-muted">Cargando recursos corporativos...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer informativo -->
  <div class="card bg-light shadow mt-4">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h6 class="mb-1">💡 Información importante</h6>
          <p class="mb-0 text-muted small">
            Todos los documentos están actualizados y disponibles para descarga. 
            Si tienes alguna duda o necesitas documentos adicionales, contacta a tu asesor.
          </p>
        </div>
        <div class="col-md-4 text-end">
          <small class="text-muted">
            <i class="bi bi-shield-check text-success me-1"></i>
            Conexión segura
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
