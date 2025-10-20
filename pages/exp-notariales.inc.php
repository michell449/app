<div class="card bg-white shadow-sm mt-4 mb-3">
    <div class="card-header bg-primary text-white p-3 ">
        <h2 class="fw-bold m-0">Listado de expedientes notariales</h2>
    </div> 
    <div class="d-flex justify-content-end mt-4 mb-4 me-3">
	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExpedienteNotarial"><i class="bi bi-plus me-2"></i>Nuevo expediente</button>
</div>

<!--Tabla única con toda la información -->
<div class="card bg-white shadow-sm mt-2 mb-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>EMPRESA</th>
                        <th>INSTRUMENTO</th>
                        <th>NOTARIO</th>
                        <th>GIRO DE EMPRESA</th>
                        <th>R.L.</th>
                        <th>SOCIO</th>
                        <th>DOMICILIO</th>
                        <th>RFC</th>
                        <th>CORREO</th>
                        <th>Institución bancaria</th>
                        <th>ACCION
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
            </div>
    </div>
</div>

<!-- Modal Expediente Notarial -->
<div class="modal fade" id="modalExpedienteNotarial" tabindex="-1" aria-labelledby="modalExpedienteNotarialLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formExpedienteNotarial">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalExpedienteNotarialLabel">
                        <i class="bi bi-file-earmark-text me-2"></i>Detalles del Expediente Notarial
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bi bi-building me-2"></i>Datos Generales</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-empresa">Empresa:</label>
                                            <input type="text" class="form-control" id="modal-empresa" name="empresa" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-giro_empresa">Giro de Empresa:</label>
                                            <input type="text" class="form-control" id="modal-giro_empresa" name="giro_empresa" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-rfc">RFC:</label>
                                            <input type="text" class="form-control" id="modal-rfc" name="rfc" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-domicilio">Domicilio:</label>
                                            <input type="text" class="form-control" id="modal-domicilio" name="domicilio" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-correo">Correo Electrónico:</label>
                                            <input type="email" class="form-control" id="modal-correo" name="correo" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Información Legal y Bancaria</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-institucion_bancaria">Institución Bancaria:</label>
                                            <input type="text" class="form-control" id="modal-institucion_bancaria" name="institucion_bancaria" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-instrumento">Instrumento:</label>
                                            <input type="text" class="form-control" id="modal-instrumento" name="instrumento" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-notario">Notario:</label>
                                            <input type="text" class="form-control" id="modal-notario" name="notario" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-rl">Representante Legal (R.L.):</label>
                                            <input type="text" class="form-control" id="modal-rl" name="rl" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-socio">Socio:</label>
                                            <input type="text" class="form-control" id="modal-socio" name="socio" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-fecha_registro">Fecha de Registro:</label>
                                            <input type="datetime-local" class="form-control" id="modal-fecha_registro" name="fecha_registro" value="" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-primary" for="modal-activo">Activo:</label>
                                            <select class="form-control" id="modal-activo" name="activo">
                                                <option value="1" selected>Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary" id="btnGuardarExpediente">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
?>