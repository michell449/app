<!-- Categorías - Maquetado Bootstrap/DataTables -->

<!-- Categorías - Maquetado Bootstrap/DataTables -->
<div class="container-fluid mt-4">
    <div class="card bg-white shadow-sm mx-auto" style="max-width:95vw;">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Categorías</h2>
        </div>
        <div class="card-body">
                        <div class="d-flex justify-content-start mb-2">
                                <button class="btn btn-primary px-5 py-0 fw-bold" style="font-size:1.25rem;" data-bs-toggle="modal" data-bs-target="#modalCategoria">Agregar nueva categoria</button>
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
                                                <label for="nombreCategoria" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" id="nombreCategoria" placeholder="Nombre de la categoría">
                                            </div>
                                            <div class="mb-3">
                                                <label for="descripcionCategoria" class="form-label">Descripción</label>
                                                <textarea class="form-control" id="descripcionCategoria" rows="3" placeholder="Descripción"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary">Guardar</button>
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
                        <th>ID</th>
                        <th>Nombre</th>
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


