<?php
require_once __DIR__ . '/../core/ver-expediente-controller.php';
?>
<div class="container-fluid px-2 mb-3">
    <div class="card bg-white shadow-sm mt-4 mb-3">
        <div class="card-header bg-primary text-white p-3 ">
            <h2 class="fw-bold m-0">Expediente digital</h2>
        </div>
       
    </div>

<!-- Modal subir archivo -->
<div class="modal fade" id="modalSubirArchivo" tabindex="-1" aria-labelledby="modalSubirArchivoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formSubirArchivo" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirArchivoLabel">Subir archivo al expediente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_expediente" value="<?php echo isset($expedienteDatos['id_expediente']) ? $expedienteDatos['id_expediente'] : ''; ?>">
                    <div class="mb-3">
                        <label for="tipo_archivo" class="form-label">Tipo de documento</label>
                        <select class="form-select" id="tipo_archivo" name="tipo_archivo" required>
                            <option value="">Selecciona tipo</option>
                            <option value="acuerdo">Acuerdo</option>
                            <option value="promocion">Promoción</option>
                            <option value="constancia">Constancia</option>
                            <option value="juicio">Juicio</option>
                            <option value="audiencia">Audiencia</option>
                            <option value="caratula">Carátula</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_presentacion" class="form-label">Fecha de presentación</label>
                        <input class="form-control" type="date" id="fecha_presentacion" name="fecha_presentacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label">Selecciona archivo</label>
                        <input class="form-control" type="file" id="archivo" name="archivo" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_archivo" class="form-label">Nombre para mostrar del archivo</label>
                        <input class="form-control" type="text" id="nombre_archivo" name="nombre_archivo" maxlength="255" placeholder="Ejemplo: Contrato firmado.pdf">
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
<!-- Layout con sidebar y carátula tipo libro -->
<div class="row g-0">
    <!-- Sidebar izquierdo -->
    <div id="sidebar-expediente" class="col-12 col-md-5 col-lg-4 col-xl-4 bg-dark text-white d-flex flex-column py-4 shadow-lg" style="min-width:450px; max-width:600px; height:calc(100vh - 120px); overflow-x: hidden !important; overflow-y: auto; border-right: 3px solid #495057; transition: all 0.3s ease; position: relative; box-sizing: border-box;">
        
        <!-- Botón único para expandir/contraer sidebar -->
        <!--<button id="expand-sidebar-btn"><i class="bi bi-chevron-left"></i></button>-->

        <!-- CSS para eliminar completamente el scroll horizontal -->
       
        <div class="px-4 mb-4" id="sidebar-content" style="overflow-x: hidden; width: 100%; box-sizing: border-box;">
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-secondary" style="overflow-x: hidden; width: 100%; box-sizing: border-box;">
                <a href="javascript:history.back()" class="btn btn-outline-light btn-sm me-3 rounded-circle flex-shrink-0" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-arrow-left"></i></a>
                <div class="d-flex align-items-center flex-fill" style="overflow: hidden; min-width: 0;">
                    <span class="text-light opacity-75 me-2 flex-shrink-0" style="font-size: 0.9rem;">Número de expediente:</span>
                    <h4 class="fw-bold text-white mb-0 text-truncate" style="font-size: 1.3rem;"><?php echo $expedienteDatos['numero_expediente']; ?></h4>
                </div>
            </div>
            <!-- Campo Asunto -->
            <div class="card bg-white shadow-sm mb-2 border-0" style="border-radius: 8px;">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center" title="<?php echo $expedienteDatos['tipo_asunto']; ?>">
                        <i class="bi bi-briefcase me-3" style="font-size: 1.4rem; color: #f39c12; font-weight: bold;"></i>
                        <div class="flex-fill">
                            <small class="text-muted">Asunto</small>
                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;"><?php echo $expedienteDatos['tipo_asunto']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Campo Cliente -->
            <div class="card bg-white shadow-sm mb-2 border-0" style="border-radius: 8px;">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center" title="<?php echo isset($expedienteDatos['nombre_comercial']) ? $expedienteDatos['nombre_comercial'] : '-'; ?>">
                        <i class="bi bi-person me-3" style="font-size: 1.4rem; color: #3498db; font-weight: bold;"></i>
                        <div class="flex-fill">
                            <small class="text-muted">Cliente</small>
                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                                <?php echo isset($expedienteDatos['nombre_comercial']) ? $expedienteDatos['nombre_comercial'] : '-'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Campo Demandante -->
            <div class="card bg-white shadow-sm mb-2 border-0" style="border-radius: 8px;">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center" title="<?php echo isset($expedienteDatos['demandante']) ? $expedienteDatos['demandante'] : '-'; ?>">
                        <i class="bi bi-person-check me-3" style="font-size: 1.4rem; color: #27ae60; font-weight: bold;"></i>
                        <div class="flex-fill">
                            <small class="text-muted">Demandante</small>
                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                                <?php echo isset($expedienteDatos['demandante']) ? $expedienteDatos['demandante'] : '-'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Campo NEUN -->
            <div class="card bg-white shadow-sm mb-2 border-0" style="border-radius: 8px;">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center" title="<?php echo isset($expedienteDatos['expediente_unico']) ? $expedienteDatos['expediente_unico'] : '-'; ?>">
                        <i class="bi bi-hash me-3" style="font-size: 1.4rem; color: #8e44ad; font-weight: bold;"></i>
                        <div class="flex-fill">
                            <small class="text-muted">NEUN</small>
                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                                <?php echo isset($expedienteDatos['expediente_unico']) ? $expedienteDatos['expediente_unico'] : '-'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-2">
                <span class="fw-bold text-white" style="font-size:1.1rem;">Contenido del expediente</span>
            </div>
            <hr class="bg-secondary mb-2 mt-0">
        </div>
        <div class="px-3 mb-2">

        </div>
        <ul class="nav flex-column nav-pills gap-1 px-2 flex-fill" style="overflow-y: auto;">
            <li class="nav-item">
                <a class="nav-link text-white bg-secondary bg-opacity-25 text-truncate" href="#caratulas" data-cat="caratula" title="Carátulas">Carátulas (<span id="count-caratula">0</span>)</a>
                <ul class="list-group list-group-flush" id="list-caratula" style="display:none;"></ul>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white bg-secondary bg-opacity-25 text-truncate" href="#acuerdos" data-cat="acuerdo" title="Acuerdos">Acuerdos (<span id="count-acuerdo">0</span>)</a>
                <ul class="list-group list-group-flush" id="list-acuerdo" style="display:none;"></ul>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white bg-secondary bg-opacity-25 text-truncate" href="#promociones" data-cat="promocion" title="Promociones">Promociones (<span id="count-promocion">0</span>)</a>
                <ul class="list-group list-group-flush" id="list-promocion" style="display:none;"></ul>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white bg-secondary bg-opacity-25 text-truncate" href="#constancias" data-cat="constancia" title="Constancias">Constancias (<span id="count-constancia">0</span>)</a>
                <ul class="list-group list-group-flush" id="list-constancia" style="display:none;"></ul>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white bg-secondary bg-opacity-25 text-truncate" href="#juicios" data-cat="juicio" title="Juicios">Juicios (<span id="count-juicio">0</span>)</a>
                <ul class="list-group list-group-flush" id="list-juicio" style="display:none;"></ul>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white bg-secondary bg-opacity-25 text-truncate" href="#audiencias" data-cat="audiencia" title="Audiencias">Audiencias (<span id="count-audiencia">0</span>)</a>
                <ul class="list-group list-group-flush" id="list-audiencia" style="display:none;"></ul>
            </li>
        </ul>
    </div>

    <!-- Panel de visualización tipo libro -->
    <div class="col d-flex flex-column align-items-center justify-content-center" style="height:calc(100vh - 120px); min-height:calc(100vh - 120px); background:#f4f6fa;">
        <!-- Botón circular fijo arriba a la derecha -->
        <div style="width:100%; display:flex; justify-content:flex-end; position:relative;">
            <button type="button" class="btn btn-primary rounded-circle" style="width:60px; height:60px; font-size:2.5rem; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,0.18); border:3px solid #fff; outline:2px solid #1976d2; background:linear-gradient(135deg,#1976d2 80%,#42a5f5 100%); color:#fff; margin:24px 32px 0 0; position:absolute; top:0; right:0; z-index:10;" data-bs-toggle="modal" data-bs-target="#modalSubirArchivo">
                <span aria-hidden="true" style="font-weight:bold;">+</span>
            </button>
            <button type="button" class="btn rounded-circle" onclick="window.location.href='panel?pg=papelera'" style="width:60px; height:60px; font-size:2.5rem; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(220,53,69,0.18); border:3px solid #fff; outline:2px solid #dc3545; background:linear-gradient(135deg,#dc3545 80%,#ff6f91 100%); color:#fff; margin-right:32px; position:absolute; top:110px; right:0; z-index:10;">
                <i class="bi bi-trash3" style="color:#fff;"></i>
            </button>
        </div>
        <!-- Carátula centrada y sin scroll -->
        <div id="visor-archivo-expediente" class="expediente-caratula-hoja d-flex flex-column justify-content-center align-items-center" style="background:#fff; box-shadow:0 0 16px rgba(0,0,0,0.10); border:1px solid #e0e0e0; width:816px; height:1056px; max-width:90vw; max-height:90vh; margin:0 auto; padding:48px 32px; overflow:hidden;">
            <div class="w-100">
                <div class="text-center mb-4">
                    <h4 class="fw-bold">PODER JUDICIAL DE LA FEDERACIÓN</h4>
                    <div class="small">Juzgado Primero de Distrito en Materia Administrativa en la Ciudad de México</div>
                </div>
                <hr>
                <div class="mb-4"></div>
                <div class="mb-3"><span class="fw-semibold">Materia:</span> <span class="text-primary"><?php echo $expedienteDatos['materia']; ?></span></div>
                <div class="mb-3"><span class="fw-semibold">Número de Expediente Asignado:</span> <span class="text-primary"><?php echo $expedienteDatos['numero_expediente']; ?></span></div>
                <div class="mb-3"><span class="fw-semibold">Parte:</span> <span class="text-primary"><?php echo $expedienteDatos['parte']; ?></span></div>
                <div class="mb-3"><span class="fw-semibold">Órgano Jurisdiccional:</span> <span class="text-primary"><?php echo $expedienteDatos['organo_jur']; ?></span></div>
                <div class="mb-3"><span class="fw-semibold">Tipo de Asunto:</span> <span class="text-primary"><?php echo $expedienteDatos['tipo_asunto']; ?></span></div>
                <div class="mb-3"><span class="fw-semibold">Fecha de registro:</span> <span class="text-primary"><?php echo $expedienteDatos['fecha_creacion']; ?></span></div>
                <div class="mb-3"><span class="fw-semibold">Cliente:</span> <span class="text-primary"><?php echo isset($expedienteDatos['cliente']) ? $expedienteDatos['cliente'] : '-'; ?></span></div>
                <div class="mb-3"><span class="fw-semibold">Demandante:</span> <span class="text-primary"><?php echo isset($expedienteDatos['demandante']) ? $expedienteDatos['demandante'] : '-'; ?></span></div>
            </div>
        </div>
     </div>
    </div>


