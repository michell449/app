<!-- Categorías - Maquetado Bootstrap/DataTables -->
<div class="container-fluid mt-4">
    <div class="card bg-white shadow-sm mx-auto" style="max-width:95vw;">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Catálogo de productos</h2>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-start align-items-center mb-2 gap-3">
                <button id="btnAgregarNuevoProducto" class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center gap-2" style="font-size:1.15rem;" data-bs-toggle="modal" data-bs-target="#modalCategoria" disabled>
                    <i class="fas fa-plus-circle"></i>
                    Agregar nuevo producto
                </button>
                            <div class="input-group" style="max-width:300px;">
                                <input type="text" class="form-control" id="filtroClienteExterior" placeholder="Buscar cliente por RFC">
                                <button class="btn btn-outline-primary" type="button" id="btnBuscarClienteExterior">Buscar</button>
                            </div>
                            <div class="input-group mt-2" style="max-width:300px;">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="clienteSeleccionadoExterior" placeholder="Cliente seleccionado" readonly>
                            </div>
                        </div>
                        <!-- Modal para agregar nueva categoría -->
                        <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalCategoriaLabel">Agregar nueva categoría</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <form>
                                            <div class="mb-3">
                                                <div class="collapse show mt-2" id="filtrosCategoria">
                                                    <div class="card card-body mb-3">
                                                        <form class="row g-2">
                                                            <div class="col-12 mb-2">
                                                                <label for="filtroCategoria" class="form-label">Selecciona categoría</label>
                                                                <select class="form-select" id="filtroCategoria">
                                                                    <option value="">Seleccione una categoría</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-12 mb-2">
                                                                <button type="button" class="btn btn-outline-primary" id="btnSeleccionarTodos">Seleccionar todos</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- Tabla de productos agregados -->
                                                <div class="table-responsive">
                                                    <div style="max-height:300px; overflow-y:auto;">
                                                        <table class="table table-bordered table-striped mb-0" id="tablaProductosModal">
                                                            <thead class="table-primary">
                                                                <tr>
                                                                    <th>Clave SAT</th>
                                                                    <th>Descripción</th>
                                                                    <th>Facturable</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="productosPorCategoria">
                                                                <!-- Aquí se agregarán dinámicamente los productos -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-success" id="btnGuardarProductosModal">
                                            <i class="fas fa-save"></i> Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
            <!-- Botón Nuevo arriba del filtro -->
            <div class="row mb-2">
                <div class="col-12 mb-2 d-flex justify-content-end d-md-none">
                    <!-- Botón para pantallas pequeñas, opcional -->
                </div>
            </div>
            <table id="tablaCategorias" class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Clave SAT</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <!-- Categorías dinámicas -->
                </tbody>
            </table>
                </tbody>
            </table>
        </div>
    </div>
</div>
