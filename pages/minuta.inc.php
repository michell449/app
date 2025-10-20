<?php require_once __DIR__ . '/../core/minuta-controller.php'; ?>
<!-- Card azul de título (ajustado) -->

<div class="container-fluid py-4">
    <div class="card shadow-sm w-100 bg-primary border-0" style="margin-bottom: 18px;">
      <div class="card-header bg-primary text-white d-flex align-items-center" style="gap: 16px; border-bottom: none;">
        <h4 class="mb-0 text-white"><i class="bi bi-clipboard-check-fill me-2"></i> Minutas</h4>
      </div>
    </div>
    <!-- Barra de búsqueda y botón Nueva -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="input-group w-50">
        <input type="text" class="form-control" placeholder="Buscar minuta..." id="buscadorMinutas">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary" type="button"><i class="fas fa-search"></i></button>
        </div>
      </div>
  <a href="panel?pg=nueva-minuta" class="btn btn-primary"><i class="fas fa-plus"></i> Nueva</a>
    </div>
    <!-- Tabla de minutas -->
    <div class="card">
      <div class="card-body p-0">
        <table class="table table-striped">
          <thead class="bg-primary text-white">
            <tr>
              <th>Título</th>
              <th>Fecha de Junta</th>
              <th>Hora</th>
              <th>Lugar</th>
              <th>Detalle</th>
            </tr>
          </thead>
          <tbody id="tablaMinutasBody">
  <!-- Las filas de la tabla serán generadas dinámicamente por JavaScript -->
</tbody>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal de detalle de temas y acuerdos eliminado -->
        </table>
      </div>
    </div>

