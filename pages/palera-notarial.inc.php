<div class="container-fluid px-2 mb-3">
    <div class="card bg-white shadow-sm mt-4 mb-3" style="min-height: 80vh; padding-bottom: 2rem;">
        <div class="card-header bg-primary text-white p-4 ">
            <h2 class="fw-bold m-0">Papelera de archivos de archivos notariales</h2>
        </div>
        <div class="card-body py-5">
            <div class="card shadow-sm w-100 mb-4" style="max-width: 100vw;">
                <div class="card-body">
                    <form id="formFiltroPapelera" class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="filtroCategoria" class="form-label">Categoría</label>
                            <select id="filtroCategoria" class="form-select">
                                <option value="">Todas</option>
                                <option value="actas">Actas</option>
                                <option value="bodas">Bodas</option>
                                <option value="capitulaciones_matrimoniales">Capitulaciones matrimoniales</option>
                                <option value="compraventa">Compraventa</option>
                                <option value="conciliacion">Conciliación</option>
                                <option value="constitucion_sociedades_mercantiles">Constitución de sociedades mercantiles</option>
                                <option value="declaracion_heredero_abintestado">Declaración de heredero abintestato</option>
                                <option value="divorcios">Divorcios</option>
                                <option value="donacion">Donación</option>
                                <option value="herencia">Herencia</option>
                                <option value="poliza">Póliza</option>
                                <option value="poder">Poder</option>
                                <option value="prestamo_hipotecario">Préstamo hipotecario</option>
                                <option value="prestamo_personal">Préstamo personal</option>
                                <option value="reclamacion_deudas">Reclamación de deudas</option>
                                <option value="separaciones">Separaciones</option>
                                <option value="testamento">Testamento</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filtroExpediente" class="form-label">ID Expediente</label>
                            <input type="number" id="filtroExpediente" class="form-control" placeholder="ID expediente">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary w-100" id="btnBuscarPapelera"><i class="bi bi-search"></i> Buscar</button>
                        </div>
                    </form>
                    <div class="table-responsive" style="min-width:1200px;">
                        <table class="table table-bordered table-hover align-middle w-100" id="tablaPapelera" style="min-width:1200px;">
                            <thead class="table-secondary">
                                <tr>
                                    <th>ID Notarial</th>
                                    <th>ID Notarial</th>
                                    <th>Categoría</th>
                                    <th>Fecha</th>
                                    <th>Documento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenan los archivos vía JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>