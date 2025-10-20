<section>
    <div class="container-fluid">
        <div class="card card-white shadow-sm">
            <div class="card-body">
                <!-- Título con fondo -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-header bg-primary text-white">
                            <h2 class="m-0" style="font-size: 1.8rem; font-weight: 600;">Reporte de CFDI's</h2>
                        </div>
                    </div>
                </div>
                
                <!-- Filtros -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-secondary mb-3">
                                    <i class="bi bi-funnel me-2"></i>Filtros de Búsqueda
                                </h5>
                                <form id="filtros-form" method="POST">
                                    <div class="row align-items-end">
                                        <div class="col-md-3">
                                            <label for="fecha_inicio" class="form-label fw-bold">Fecha Inicio</label>
                                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fecha_final" class="form-label fw-bold">Fecha Final</label>
                                            <input type="date" class="form-control" id="fecha_final" name="fecha_final">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cliente" class="form-label fw-bold">Cliente</label>
                                            <select class="form-select" id="cliente" name="cliente">
                                                <option value="">Seleccionar cliente...</option>
                                                <!-- Opciones dinámicas desde la base de datos -->
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-search me-1"></i>Filtrar
                                            </button>
                                            <button type="button" id="btn-limpiar" class="btn btn-outline-secondary w-100 mt-2">
                                                <i class="bi bi-eraser me-1"></i>Limpiar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Resultados -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title text-secondary mb-0">
                                        <i class="bi bi-table me-2"></i>Resultados del Reporte
                                    </h5>
                                    <button class="btn btn-success btn-sm" id="exportar-excel">
                                        <i class="bi bi-file-earmark-excel me-1"></i>Exportar Excel
                                    </button>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tabla-cfdis">
                                        <!-- Actualizar encabezado de la tabla para mostrar comisionistas y total de comisiones -->
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Cliente</th>
                                                <th>RFC</th>
                                                <th>No. CFDI's</th>
                                                <th>Total</th>
                                                <th>Comisionistas</th>
                                                <th>Total Comisión</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody-cfdis">
                                            <!-- Datos dinámicos -->
                                        </tbody>
                                        <!-- Actualizar pie de la tabla para mostrar el total de comisiones -->
                                        <tfoot class="table-secondary">
                                            <tr class="fw-bold">
                                                <td colspan="2">TOTALES</td>
                                                <td id="total-cfdis">0</td>
                                                <td id="total-general">$0.00</td>
                                                <td></td>
                                                <td id="total-comisionistas">$0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard de Métricas -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-file-earmark-text fs-1 mb-2"></i>
                                <h4 class="mb-1" id="card-total-cfdis">0</h4>
                                <p class="mb-0">Total CFDI's</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-success text-white">
                            <div class="card-body text-center">
                                <i class="bi bi-people fs-1 mb-2"></i>
                                <h4 class="mb-1" id="card-total-comisionistas">$0.00</h4>
                                <p class="mb-0">Total Comisión Comisionistas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-warning text-dark">
                            <div class="card-body text-center">
                                <i class="bi bi-graph-up fs-1 mb-2"></i>
                                <h4 class="mb-1" id="card-total-general">$0.00</h4>
                                <p class="mb-0">Total General</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficas -->
                <div class="row mt-4">
                    <!-- Gráfica de Comisionistas -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-secondary mb-0">
                                    <i class="bi bi-pie-chart me-2"></i>Distribución de Comisionistas
                                </h5>
                                <div style="height: 300px;">
                                    <canvas id="grafica-comisionistas"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Gráfica de Estados de Pago -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-secondary">
                                    <i class="bi bi-bar-chart me-2"></i>Estados de Pago
                                </h5>
                                <div style="height: 300px;">
                                    <canvas id="grafica-estados"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de Tendencias -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-secondary">
                                    <i class="bi bi-graph-up-arrow me-2"></i>Tendencia de Pagos vs Pendientes
                                </h5>
                                <div style="height: 250px;">
                                    <canvas id="grafica-tendencias"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section> 


