<section class="content">
<div class="container-fluid">
    <div class="card card-white shadow-sm">
    <div class="card-body">
        <!-- Título con fondo -->
        <div class="row">
        <div class="col-12">
            <div class="card-header bg-primary text-white">
            <h2 class="m-0" style="font-size: 1.8rem; font-weight: 600;">Administración de CFDI's</h2>
            </div>
        </div>
        <!-- Botón Cargar CFDIs -->
                <div class="d-flex justify-content-end mt-4 mb-4 me-3 gap-3">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-cargar-cfdis">
                            <i class="fas fa-upload "></i> Cargar CFDI's
                        </button>
                    </div>
                </div>
        </div>

    <!-- Main content -->
    
        <div class="container-fluid">  
            <!-- Buscador de CFDIs -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-secondary">
                        <div class="card-body">
                            <form id="form-filtros-cfdis" class="mb-0">
                                <div class="row align-items-end g-2">
                                    <div class="col-md-2">
                                        <label for="filtro_folio" class="form-label mb-1">Folio:</label>
                                        <input type="text" class="form-control" id="filtro_folio" name="folio" placeholder="Folio">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filtro_emisor" class="form-label mb-1">Emisor:</label>
                                        <input type="text" class="form-control" id="filtro_emisor" name="emisor" placeholder="Nombre del emisor">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filtro_rfc" class="form-label mb-1">RFC:</label>
                                        <input type="text" class="form-control" id="filtro_rfc" name="rfc" placeholder="RFC123456789">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filtro_tipo" class="form-label mb-1">Tipo:</label>
                                        <select class="form-select" id="filtro_tipo" name="tipo">
                                            <option value="">Todos</option>
                                            <option value="I">Ingreso</option>
                                            <option value="E">Egreso</option>
                                            <option value="T">Traslado</option>
                                            <option value="P">Pago</option>
                                            <option value="N">Nómina</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="filtro_fecha" class="form-label mb-1">Fecha:</label>
                                        <input type="date" class="form-control" id="filtro_fecha" name="fecha">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary w-100" id="btn-buscar-cfdis" title="Buscar CFDIs">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-secondary w-100" id="btn-limpiar-filtros" title="Limpiar filtros">
                                            <i class="fas fa-eraser"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen de CFDIs -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-info" style="background:#f0f8ff;border:none;">
                        <div class="card-body py-2">
                            <div class="row text-center">
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <span class="fw-bold text-secondary" style="font-size:1.1rem;"># de Documentos:</span>
                                    <span class="fw-bold text-primary" id="resumen_total_cfdis" style="font-size:1.3rem;">0</span>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0">
                                    <span class="fw-bold text-secondary" style="font-size:1.1rem;">Total IVA/Impuesto:</span>
                                    <span class="fw-bold text-success" id="resumen_total_impuestos" style="font-size:1.3rem;">$0.00</span>
                                </div>
                                <div class="col-md-4">
                                    <span class="fw-bold text-secondary" style="font-size:1.1rem;">Total General:</span>
                                    <span class="fw-bold text-success" id="resumen_total_total" style="font-size:1.3rem;">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de CFDIs -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-table"></i> Listado de CFDIs</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 text-right">
                                    <span class="text-muted">Total: <strong id="total_cfdis">0</strong> CFDIs cargados</span>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-hover" id="tabla-cfdis" style="width: 100%;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 10%;">Fecha</th>
                                        <th style="width: 8%;">Folio</th>
                                        <th style="width: 20%;">Emisor</th>
                                        <th style="width: 12%;">RFC</th>
                                        <th style="width: 6%;">Tipo</th>
                                        <th style="width: 10%;">IVA/Impuesto</th>
                                        <th style="width: 10%;">Total</th>
                                        <th style="width: 16%;">Comisiones</th>
                                        <th style="width: 8%;">Estado</th>
                                        <th style="width: 10%;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cfdis-tbody">
                                    <?php include __DIR__ . '/../core/list-cfdis-html.php'; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background:#f5f5f5;font-weight:bold;">
                                        <td colspan="5" class="text-end">Totales:</td>
                                        <td id="total-impuestos">$0.00</td>
                                        <td id="total-total">$0.00</td>
                                        <td id="total-comisiones">0.00%</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- Modal para cargar CFDIs -->
<div class="modal fade" id="modal-cargar-cfdis" tabindex="-1" aria-labelledby="modalCargarCfdisLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="modalCargarCfdisLabel">Cargar CFDI's</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form-cargar-cfdis" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="mb-2">Subir un solo XML</label>
                        <input type="file" class="form-control mb-2" id="archivo_xml" name="archivo_xml" accept=".xml">
                        <button type="button" class="btn btn-success w-100" id="btn-cargar-xml"><i class="fas fa-upload"></i> Cargar Archivo</button>
                    </div>
                    <div class="mb-2">
                        <label class="mb-2">Subir archivo ZIP con varios CFDI</label>
                        <input type="file" class="form-control mb-2" id="archivo_zip" name="archivo_zip" accept=".zip">
                        <button type="button" class="btn btn-success w-100" id="btn-cargar-zip"><i class="fas fa-upload"></i> Cargar Archivo</button>
                    </div>
                    <div id="info-auto-fields" style="display:none;"></div>
                    <!-- modal-zip-preview relocated below (no debe estar dentro del form) -->
                </form>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Modal para revisión de CFDIs cargados desde ZIP (ubicado fuera del formulario) -->
<div class="modal fade" id="modal-zip-preview" tabindex="-1" aria-labelledby="modalZipPreviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalZipPreviewLabel">Revisión de facturas cargadas (ZIP)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="zip-preview-table-container"></div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Cliente *</label>
                        <select class="form-control" id="cliente_zip_modal" name="cliente_zip_modal" required>
                            <option value="">Seleccionar cliente...</option>
                            <?php include __DIR__ . '/../core/list-clientes-cfdis-select.php'; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Estado *</label>
                        <select class="form-control" id="estado_zip_modal" name="estado_zip_modal" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="pendiente" selected>Pendiente</option>
                            <option value="pagado">Pagado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="btn-importar-zip">Importar seleccionados</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar datos extraídos del XML (ubicado fuera de cualquier formulario y modal principal) -->
<div class="modal fade" id="modal-xml-preview" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Datos extraídos del XML</h5>
            </div>
            <div class="modal-body">
                <form id="form-xml-preview">
                    <div class="mb-3">
                        <label>Cliente *</label>
                        <select class="form-control" id="cliente_cfdi_modal" name="cliente_cfdi_modal" required>
                            <option value="">Seleccionar cliente...</option>
                            <?php include __DIR__ . '/../core/list-clientes-cfdis-select.php'; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Estado *</label>
                        <select class="form-control" id="estado_cfdi_modal" name="estado_cfdi_modal" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="pendiente" selected>Pendiente</option>
                            <option value="pagado">Pagado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div id="xml-fields-preview"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-guardar-xml-cfdi">Guardar CFDI</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para visualizar comprobante PDF -->
<div class="modal fade" id="modal-ver-comprobante" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comprobante de Pago (PDF)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="height:80vh;">
                <iframe id="iframe-comprobante-pdf" src="" width="100%" height="100%" style="border:none;"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-cargar-pagos" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Subir comprobante de pago (PDF)</h4>
            </div>
            <div class="modal-body">
                <form id="form-comprobante-pago" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="folio_comprobante">Folio CFDI:</label>
                        <input type="text" class="form-control" id="folio_comprobante" name="folio" required placeholder="Folio del CFDI" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="archivo_comprobante">Archivo PDF:</label>
                        <input type="file" class="form-control" id="archivo_comprobante" name="archivo" accept="application/pdf" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-subir-comprobante">
                    <i class="fas fa-upload"></i> Subir comprobante
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal para ver CFDI -->
<div class="modal fade" id="modal-ver-cfdi" tabindex="-1" aria-labelledby="modalVerCfdiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title" id="modalVerCfdiLabel">Detalle del CFDI</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body bg-light-blue" style="background-color: #e3f0ff;">
                <div id="ver-cfdi-loader" class="text-center my-3" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-2x"></i> Cargando...
                </div>
                <form id="form-ver-cfdi" autocomplete="off">
                    <input type="hidden" id="ver-id-cfdi" />
                    <div id="ver-cfdi-datos" style="display:none;">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Folio:</label>
                                    <input type="text" class="form-control" id="ver-folio" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de emisión:</label>
                                    <input type="text" class="form-control" id="ver-fecha-emision" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Emisor:</label>
                                    <input type="text" class="form-control" id="ver-emisor" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>RFC:</label>
                                    <input type="text" class="form-control" id="ver-rfc" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo:</label>
                                    <input type="text" class="form-control" id="ver-tipo" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Importe:</label>
                                    <input type="text" class="form-control" id="ver-importe" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total:</label>
                                    <input type="text" class="form-control" id="ver-total" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado:</label>
                                    <select class="form-control" id="ver-estado" name="ver-estado" disabled>
                                        <option value="pendiente">Pendiente</option>
                                        <option value="pagado">Pagado</option>
                                        <option value="cancelado">Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cliente:</label>
                                    <input type="text" class="form-control" id="ver-cliente" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Total de comisiones:</label>
                                    <input type="text" class="form-control" id="ver-comisiones-list" readonly style="font-size:0.95rem; background:#eafbe7; color:#222;" placeholder="No hay comisiones para este cliente.">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-modificar-cfdi">
                    <i class="fas fa-edit"></i> Modificar
                </button>
                <button type="button" class="btn btn-success d-none" id="btn-guardar-cfdi">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</div>
</section>


