<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm mt-4" style="min-height: 400px;">
          <div class="card-header border-bottom-0 bg-primary">
            <div class="d-flex align-items-center" style="gap: 16px;">
              <h4 class="mb-0 text-white">Panel de tareas</h4>
            </div>
          </div>
          <div class="px-3 pt-3">
            <button type="button" id="btnCrearTarea" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCrearTarea">
              <i class="fas fa-plus"></i> Crear tarea
            </button>
          </div>
          <div class="card-body">
            <?php include_once __DIR__ . '/../core/list-tareas-dashboard.php'; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
