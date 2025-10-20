<!-- Categorías - Maquetado Bootstrap/DataTables -->
<div class="container-fluid mt-4">
    <div class="card bg-white shadow-sm mx-auto" style="max-width:95vw;">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">
                <i class="bi bi-card-checklist me-2"></i>
                Listado de citas
            </h2>
        </div>
        <div class="card-body">   
            <div class="row mb-2">
                <div class="col-12 mb-2 d-flex justify-content-end">
                <div class="mt-3 w-100 d-flex justify-content-end">
                    <button class="btn btn-secondary px-3 py-1 d-flex align-items-center" style="font-size:1rem;" onclick="window.history.back();">
                        <i class="bi bi-arrow-left-circle me-2" style="font-size:1.2rem;"></i>
                        <span>Volver</span>
                    </button>
                </div>
                </div>
            </div>
            <table id="tablaCategorias" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Asunto</th>
                        <th>Ubicación</th>
                        <th>Fecha inicio</th>
                        <th>Todo el día</th>
                        <th>Detalles</th>
                        <th>Status</th>
                        <th>Hora de inicio</th>
                        <th>Enviar correo</th>
                        <th>Asistirá</th>
                    </tr>
                    </thead>
                    <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>