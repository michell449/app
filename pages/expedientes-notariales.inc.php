<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="card-title mb-0 fw-bold" style="font-size: 2.5rem;">Listado de archivos notariales</h1>
                    </div>
                </div>

<div class="container-fluid px-3 mb-3 d-flex justify-content-center">
    <div class="w-100" style="max-width: 1400px;">
    <div class="card bg-white bg-secondary shadow-lg mt-4 mb-3 border-0 rounded-3">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);" class="text-white p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-row align-items-center gap-2 w-100">
                    <button type="button" class="btn btn-secondary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modalSubirArchivoCategorias" style="border: 2px solid #fff;">
                        <i class="bi bi-upload me-2"></i> Subir archivo
                    </button>
                    <a href="panel?pg=palera-notarial" class="btn btn-danger btn-lg shadow-sm" style="border: 2px solid #fff;">
                        <i class="bi bi-recycle me-2"></i> Papelera
                    </a>
            </div>      
        </div>
        <div class="card-body p-0">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-fill" id="notarialTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="patrimonio-tab" data-bs-toggle="tab" data-bs-target="#patrimonio" type="button" role="tab" aria-controls="patrimonio" aria-selected="true">
                        <i class="bi bi-house-door"></i> Patrimonio
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sucesiones-tab" data-bs-toggle="tab" data-bs-target="#sucesiones" type="button" role="tab" aria-controls="sucesiones" aria-selected="false">
                        <i class="bi bi-people"></i> Sucesiones
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="familia-tab" data-bs-toggle="tab" data-bs-target="#familia" type="button" role="tab" aria-controls="familia" aria-selected="false">
                        <i class="bi bi-heart"></i> Familia
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="poderes-tab" data-bs-toggle="tab" data-bs-target="#poderes" type="button" role="tab" aria-controls="poderes" aria-selected="false">
                        <i class="bi bi-file-earmark-person"></i> Poderes y Actas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="societario-tab" data-bs-toggle="tab" data-bs-target="#societario" type="button" role="tab" aria-controls="societario" aria-selected="false">
                        <i class="bi bi-building"></i> Societario
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="otros-tab" data-bs-toggle="tab" data-bs-target="#otros" type="button" role="tab" aria-controls="otros" aria-selected="false">
                        <i class="bi bi-files"></i> Otros
                    </button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="notarialTabContent">
                <!-- Patrimonio Tab -->
                <div class="tab-pane fade show active" id="patrimonio" role="tabpanel" aria-labelledby="patrimonio-tab">
                    <div class="p-4">
                        
                        <!-- Compraventa Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-primary">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-primary mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-house-fill me-2 p-2 bg-primary text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Compraventa 
                                </h4>
                                <span class="badge bg-primary fs-6 px-3 py-2" id="count-compraventa">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:140px; white-space:nowrap;"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4" style="width:110px; min-width:90px; max-width:130px; white-space:nowrap;"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:150px; white-space:nowrap; text-align:center;"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-compraventa">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de compraventa</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Préstamo Hipotecario Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-success">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-success mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-bank me-2 p-2 bg-success text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Préstamo Hipotecario 
                                </h4>
                                <span class="badge bg-success fs-6 px-3 py-2" id="count-prestamo_hipotecario">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:140px; white-space:nowrap;"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4" style="width:110px; min-width:90px; max-width:130px; white-space:nowrap;"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:150px; white-space:nowrap; text-align:center;"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-prestamo_hipotecario">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de préstamo hipotecario</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Préstamo Personal Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-info">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-info mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-cash-coin me-2 p-2 bg-info text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Préstamo Personal 
                                </h4>
                                <span class="badge bg-info fs-6 px-3 py-2" id="count-prestamo_personal">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:140px; white-space:nowrap;"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4" style="width:110px; min-width:90px; max-width:130px; white-space:nowrap;"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:150px; white-space:nowrap; text-align:center;"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-prestamo_personal">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de préstamo personal</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Donación Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-warning">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-warning mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-gift me-2 p-2 bg-warning text-dark rounded-circle" style="font-size: 1.2rem;"></i>
                                    Donación 
                                </h4>
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2" id="count-donacion">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);" class="text-dark">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:140px; white-space:nowrap;"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4" style="width:110px; min-width:90px; max-width:130px; white-space:nowrap;"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:150px; white-space:nowrap; text-align:center;"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-donacion">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de donación</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sucesiones Tab -->
                <div class="tab-pane fade" id="sucesiones" role="tabpanel" aria-labelledby="sucesiones-tab">
                    <div class="p-4">
                        
                        <!-- Testamento Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-primary">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-primary mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-file-earmark-text me-2 p-2 bg-primary text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Testamento 
                                </h4>
                                <span class="badge bg-primary fs-6 px-3 py-2" id="count-testamento">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:140px; white-space:nowrap;"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4" style="width:110px; min-width:90px; max-width:130px; white-space:nowrap;"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4" style="width:120px; min-width:100px; max-width:150px; white-space:nowrap; text-align:center;"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-testamento">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de testamento</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Herencia Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-success">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-success mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-arrow-down-circle me-2 p-2 bg-success text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Herencia 
                                </h4>
                                <span class="badge bg-success fs-6 px-3 py-2" id="count-herencia">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-herencia">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de herencia</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Declaración de Heredero Abintestato Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-warning">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-warning mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-person-lines-fill me-2 p-2 bg-warning text-dark rounded-circle" style="font-size: 1.2rem;"></i>
                                    Declaración de Heredero Abintestato 
                                </h4>
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2" id="count-declaracion_heredero_abintestado">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-declaracion_heredero_abintestado">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de declaración de heredero abintestato</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Familia Tab -->
                <div class="tab-pane fade" id="familia" role="tabpanel" aria-labelledby="familia-tab">
                    <div class="p-4">
                        
                        <!-- Capitulaciones Matrimoniales Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-primary">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-primary mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-heart-fill me-2 p-2 bg-primary text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Capitulaciones Matrimoniales 
                                </h4>
                                <span class="badge bg-primary fs-6 px-3 py-2" id="count-capitulaciones_matrimoniales">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-capitulaciones_matrimoniales">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de capitulaciones matrimoniales</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Bodas Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-success">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-success mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-suit-heart me-2 p-2 bg-success text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Bodas 
                                </h4>
                                <span class="badge bg-success fs-6 px-3 py-2" id="count-bodas">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-bodas">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de bodas</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Separaciones Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-warning">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-warning mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-arrow-left-right me-2 p-2 bg-warning text-dark rounded-circle" style="font-size: 1.2rem;"></i>
                                    Separaciones 
                                </h4>
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2" id="count-separaciones">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);" class="text-dark">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-separaciones">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de separaciones</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Divorcios Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-danger">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-danger mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-x-circle me-2 p-2 bg-danger text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Divorcios 
                                </h4>
                                <span class="badge bg-danger fs-6 px-3 py-2" id="count-divorcios">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #dc3545 0%, #b21f2d 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-divorcios">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de divorcios</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Poderes y Actas Tab -->
                <div class="tab-pane fade" id="poderes" role="tabpanel" aria-labelledby="poderes-tab">
                    <div class="p-4">
                        
                        <!-- Poder Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-primary">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-primary mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-person-check me-2 p-2 bg-primary text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Poder 
                                </h4>
                                <span class="badge bg-primary fs-6 px-3 py-2" id="count-poder">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-poder">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de poder</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Actas Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-success">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-success mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-file-earmark-text me-2 p-2 bg-success text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Actas 
                                </h4>
                                <span class="badge bg-success fs-6 px-3 py-2" id="count-actas">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-actas">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de actas</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Societario Tab -->
                <div class="tab-pane fade" id="societario" role="tabpanel" aria-labelledby="societario-tab">
                    <div class="p-4">
                        
                        <!-- Constitución de Sociedades Mercantiles Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-primary">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-primary mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-building-gear me-2 p-2 bg-primary text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Constitución de Sociedades Mercantiles 
                                </h4>
                                <span class="badge bg-primary fs-6 px-3 py-2" id="count-constitucion_sociedades_mercantiles">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-constitucion_sociedades_mercantiles">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de constitución de sociedades mercantiles</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                </div>


                <!-- Otros Tab -->
                <div class="tab-pane fade" id="otros" role="tabpanel" aria-labelledby="otros-tab">
                    <div class="p-4">
                        
                        <!-- Póliza Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-primary">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-primary mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-shield-check me-2 p-2 bg-primary text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Póliza 
                                </h4>
                                <span class="badge bg-primary fs-6 px-3 py-2" id="count-poliza">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-poliza">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de póliza</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Reclamación de Deudas Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-warning">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-warning mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-currency-dollar me-2 p-2 bg-warning text-dark rounded-circle" style="font-size: 1.2rem;"></i>
                                    Reclamación de Deudas 
                                </h4>
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2" id="count-reclamacion_deudas">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #ffc107 0%, #d39e00 100%);" class="text-dark">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-reclamacion_deudas">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de reclamación de deudas</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Conciliación Table -->
                        <div class="mb-5 p-4 bg-light rounded-3 shadow-sm border-start border-5 border-success">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="fw-bold text-success mb-0 me-3" style="font-size: 1.4rem;">
                                    <i class="bi bi-award me-2 p-2 bg-success text-white rounded-circle" style="font-size: 1.2rem;"></i>
                                    Conciliación 
                                </h4>
                                <span class="badge bg-success fs-6 px-3 py-2" id="count-conciliacion">0</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 border rounded-3 overflow-hidden">
                                    <thead style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);" class="text-white">
                                        <tr>
                                            <th class="py-3 px-4"><i class="bi bi-file-earmark me-2"></i> Documento</th>
                                            <th class="py-3 px-4"><i class="bi bi-calendar me-2"></i> Fecha</th>
                                            <th class="py-3 px-4"><i class="bi bi-check-circle me-2"></i> Estado</th>
                                            <th class="py-3 px-4"><i class="bi bi-gear me-2"></i> Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-conciliacion">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No hay documentos de conciliación</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
     
        </div>
    </div>
</div>
</div>

<!-- Modal para previsualizar PDF -->
<div class="modal fade" id="modalPreviewPDF" tabindex="-1" aria-labelledby="modalPreviewPDFLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPreviewPDFLabel">Vista previa del PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="height:80vh;">
                <iframe id="iframePreviewPDF" src="" width="100%" height="100%" style="border:none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Modal subir archivo con categorías -->
<div class="modal fade" id="modalSubirArchivoCategorias" tabindex="-1" aria-labelledby="modalSubirArchivoCategoriasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSubirArchivoCategorias" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirArchivoCategoriasLabel">Subir archivo al expediente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Forzar el valor de id_notarial usando la URL si no está definido en PHP
                    $idNotarial = '';
                    if (isset($expedienteDatos['id_notarial']) && $expedienteDatos['id_notarial']) {
                        $idNotarial = $expedienteDatos['id_notarial'];
                    } elseif (isset($_GET['id_notarial']) && $_GET['id_notarial']) {
                        $idNotarial = $_GET['id_notarial'];
                    }
                    ?>
                    <input type="hidden" name="id_notarial" id="id_notarial" value="<?php echo htmlspecialchars($idNotarial); ?>">
                    <div class="mb-3">
                        <label for="categoria_archivo" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria_archivo" name="categoria_archivo" required>
                            <option value="">Selecciona categoría</option>
                            <option value="actas">Actas</option>
                            <option value="bodas">Bodas</option>
                            <option value="capitulaciones_matrimoniales">Capitulaciones matrimoniales</option>
                            <option value="compraventa">Compraventa</option>
                            <option value="conciliacion">Conciliación</option>
                            <option value="constitucion_sociedades_mercantiles">Constitución de sociedades mercantiles</option>
                            <option value="declaracion_heredero_abintestado">Declaracion de heredero abintestato</option>
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
                    <!-- Filtro de tipo de documento eliminado -->
                    <div class="mb-3">
                        <label for="fecha_presentacion_cat" class="form-label">Fecha de presentación</label>
                        <input class="form-control" type="date" id="fecha_presentacion_cat" name="fecha_presentacion_cat" required>
                    </div>
                    <div class="mb-3">
                        <label for="archivo_cat" class="form-label">Selecciona archivo</label>
                        <input class="form-control" type="file" id="archivo_cat" name="archivo_cat" accept="application/pdf" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_archivo_cat" class="form-label">Nombre para mostrar del archivo</label>
                        <input class="form-control" type="text" id="nombre_archivo_cat" name="nombre_archivo_cat" maxlength="255" placeholder="Ejemplo: Contrato firmado.pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>
        </div>
    </div>
</div>