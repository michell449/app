<!-- Modal para editar estado de acuerdo -->
<div class="modal fade" id="modalEditarEstadoAcuerdo" tabindex="-1" role="dialog" aria-labelledby="modalEditarEstadoAcuerdoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditarEstadoAcuerdoLabel">Editar estado del acuerdo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-editar-estado-acuerdo">
                    <div class="form-group">
                        <label for="editar-estado-acuerdo">Estado</label>
                        <select class="form-control" id="editar-estado-acuerdo" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="En proceso">En proceso</option>
                            <option value="Concluido">Concluido</option>
                            <option value="Vencido">Vencido</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-estado-acuerdo">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Editar Minuta -->
<div class="modal fade" id="modalEditarMinuta" tabindex="-1" role="dialog" aria-labelledby="modalEditarMinutaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditarMinutaLabel">Editar datos de la minuta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-editar-minuta">
                    <div class="form-group">
                        <label for="edit-minuta-lugar">Lugar</label>
                        <input type="text" class="form-control" id="edit-minuta-lugar" name="lugar" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-minuta-fecha">Fecha</label>
                        <input type="date" class="form-control" id="edit-minuta-fecha" name="fecha" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-minuta-hora">Hora</label>
                        <input type="time" class="form-control" id="edit-minuta-hora" name="hora" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-minuta-responsable">Responsable</label>
                        <select class="form-control" id="edit-minuta-responsable" name="responsable" required></select>
                    </div>
                    <div class="form-group">
                        <label for="edit-minuta-cliente">Cliente</label>
                        <select class="form-control" id="edit-minuta-cliente" name="cliente" required></select>
                    </div>
                    <div class="form-group">
                        <label for="edit-minuta-asunto">Asunto</label>
                        <input type="text" class="form-control" id="edit-minuta-asunto" name="asunto" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-editar-minuta">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<div class="card" style="margin:20px;">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h4><i class="fas fa-folder"></i> Minuta de reunión</h4>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-md-6">
                <strong>Lugar</strong><br>
                <span id="minuta-lugar"></span><br>
                <strong>Fecha</strong><br>
                <span id="minuta-fecha"></span><br>
                <strong>Responsable</strong><br>
                <span id="minuta-responsable"></span><br>
            </div>
            <div class="col-md-6">
                <b>Hora</b><br><span id="minuta-hora"></span><br>
                <strong>Cliente</strong><br>
                <span id="minuta-cliente"></span><br>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <strong>Asunto de la reunión</strong><br>
                <span id="minuta-asunto"></span><br>
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
            <div class="col-12">
                <a class="btn btn-primary" id="btn-editar-minuta"><i class="fas fa-pencil-alt"></i> Editar datos</a>
            </div>
        </div>
    </div>
    <div class="card-header">
        <h4><i class="fas fa-paperclip"></i> Temas de la reunión</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="tabla-temas">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Temas dinámicos -->
            </tbody>
        </table>
        <div style="margin-top: 10px;">
            <a class="btn btn-primary" id="btn-agregar-tema"><i class="fas fa-plus"></i> Agregar tema</a>
        </div>
        <div class="modal fade" id="modalAgregarTema" tabindex="-1" role="dialog" aria-labelledby="modalAgregarTemaLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalAgregarTemaLabel">Agregar tema</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-agregar-tema">
                            <div class="form-group">
                                <label for="tema-titulo">Título</label>
                                <input type="text" class="form-control" id="tema-titulo" required>
                            </div>
                            <div class="form-group">
                                <label for="tema-descripcion">Descripción</label>
                                <textarea class="form-control" id="tema-descripcion" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tema-observaciones">Observaciones</label>
                                <textarea class="form-control" id="tema-observaciones"></textarea>
                            </div>
                            <!-- Eliminado campo Estado -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-cancelar-tema">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-tema">Guardar tema</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para agregar acuerdo -->
        <div class="modal fade" id="modalAgregarAcuerdo" tabindex="-1" role="dialog" aria-labelledby="modalAgregarAcuerdoLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalAgregarAcuerdoLabel">Agregar acuerdo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-agregar-acuerdo">
                            <div class="form-group">
                                <label for="acuerdo-descripcion">Descripción</label>
                                <textarea class="form-control" id="acuerdo-descripcion" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="acuerdo-responsable">Responsable</label>
                                <select class="form-control" id="acuerdo-responsable" required>
                                    <option value="">Selecciona responsable</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="acuerdo-fecha">Fecha límite</label>
                                <input type="date" class="form-control" id="acuerdo-fecha" required>
                            </div>
                            <div class="form-group">
                                <label for="acuerdo-estado">Estado</label>
                                <select class="form-control" id="acuerdo-estado" required>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En proceso">En proceso</option>
                                    <option value="Concluido">Concluido</option>
                                    <option value="Vencido">Vencido</option>
                                    <option value="Cancelado">Cancelado</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-cancelar-acuerdo">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-acuerdo">Guardar acuerdo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Modales -->
<div class="modal fade" id="modal-comisionesn" tabindex="-1" role="dialog" aria-labelledby="modalComisionesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title mb-0" id="modalComisionesLabel">Entrega de Comisiones</h4>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong><i class="fas fa-info-circle mr-1 text-primary"></i> Descripción:</strong>
                    <div class="form-control-plaintext border rounded p-2 bg-light" id="modal-comisiones-descripcion"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Creada por:</strong>
                        <div class="form-control-plaintext border rounded p-2 bg-light" id="modal-comisiones-creada"></div>
                    </div>
                    <div class="col-md-6">
                        <strong>Asignada a:</strong>
                        <div class="form-control-plaintext border rounded p-2 bg-light" id="modal-comisiones-asignada"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-bell mr-1 text-warning"></i> Fecha límite:</strong>
                        <div class="form-control-plaintext border rounded p-2 bg-light" id="modal-comisiones-fecha"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <strong><i class="fas fa-comments mr-1 text-secondary"></i> Comentarios:</strong>
                    <div class="card card-body bg-light border-left border-secondary" id="modal-comisiones-comentarios"></div>
                </div>
                <div class="mb-2">
                    <strong><i class="fas fa-paperclip mr-1 text-dark"></i> Archivos Adjuntos:</strong>
                    <div class="card card-body bg-light border-left border-success" id="modal-comisiones-archivos"></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tema" tabindex="-1" role="dialog" aria-labelledby="modalComisionesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title mb-0" id="modalComisionesLabel">Datos para el acuerdo</h4>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong><i class="fas fa-info-circle mr-1 text-primary"></i> Descripción:</strong>
                    <div class="form-control-plaintext border rounded p-2 bg-light" id="modal-tema-descripcion"></div>
                </div>
                <div class="col-md-6">
                    <strong>Asignada a:</strong>
                    <div class="form-control-plaintext border rounded p-2 bg-light" id="modal-tema-asignada"></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong><i class="fas fa-bell mr-1 text-warning"></i> Fecha límite:</strong>
                        <div class="form-control-plaintext border rounded p-2 bg-light" id="modal-tema-fecha"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between bg-light">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
                <button type="button" class="btn btn-primary"><i class="fas fa-save"></i> Agregar</button>
            </div>
        </div>
    </div>
</div>

