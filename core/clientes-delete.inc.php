
<div class="container-fluid mt-4">
  <!-- Encabezado azul -->
  <div class="card bg-primary text-white shadow mb-3">
    <div class="card-body">
      <h2 class="mb-0">Papelera de clientes</h2>
    </div>
  </div>

  <!-- Tarjeta roja de papelera -->
  <div class="card shadow mb-4">
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="fa fa-trash me-2"></i>Papelera de clientes</h4>
  <a href="panel?pg=clientes" class="btn btn-secondary ms-auto"><i class="fa fa-arrow-left me-1"></i> Volver a clientes</a>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-8">
          <input type="text" class="form-control" id="buscar-papelera-clientes" placeholder="Buscar en papelera...">
        </div>
        <div class="col-md-4">
          <button class="btn btn-dark w-100" onclick="cargarPapeleraClientes()"><i class="fa fa-search"></i> Buscar</button>
        </div>
      </div>
      <div class="table-responsive" id="tabla-papelera-clientes">
        <!-- La tabla se carga por AJAX -->
      </div>
    </div>
  </div>
</div>

