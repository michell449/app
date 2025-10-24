<!-- Contenedor principal -->
<div class="content-wrapper" style="min-height: 100vh; background-color: #f4f6f9; padding-top: 2rem;">
    <!-- Encabezado de la página -->
    <div class="card shadow-sm bg-primary text-white border-0 mb-4">
        <div class="card-body ">
            <h4 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Facturación Electrónica como invitado</h4>
        </div>
    </div>

    <div class="row justify-content-center g-4">

        <!-- Buscar Ticket -->
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="text-center text-primary mb-4"> <i class="bi bi-card-heading me-2"></i>Buscar Ticket</h4>
                    <form>
                        <div class="mb-3">
                            <label for="numeroTicket" class="form-label">Número de Venta (Folio)</label>
                            <input type="text" class="form-control" id="numeroTicket" placeholder="Ingresa el número de tu ticket" required>
                        </div>
                        <div class="mb-3">
                            <label for="montoTotal" class="form-label">Monto Total</label>
                            <input type="text" class="form-control" id="montoTotal" placeholder="Ingresa el monto total" required>
                        </div>
                        <div class="mb-3">
                            <label for="fechaCompra" class="form-label">Fecha de Compra</label>
                            <input type="date" class="form-control" id="fechaCompra" required>
                        </div>
                        <div class="mb-3">
                            <label for="lugarCompraInput" class="form-label">Lugar de Compra</label>
                            <input type="text" class="form-control bg-white" id="lugarCompraInput" placeholder="Selecciona la sucursal" readonly data-bs-toggle="modal" data-bs-target="#modalSucursales" style="cursor:pointer;">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary btn-lg" id="btnBuscarTicket">
                                <i class="bi bi-search me-2"></i> Buscar Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna derecha: Información Fiscal -->
        <div class="col-md-6 col-lg-5 d-none" id="infoRegistroContainer">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="text-center text-success mb-4">
                        <i class="bi bi-person-lines-fill me-2"></i> Información Fiscal
                    </h4>
                    <form id="formInfoFiscal">
                        <div class="mb-3">
                            <label for="nombreFiscal" class="form-label">Nombre o Razón Social</label>
                            <input type="text" class="form-control" id="nombreFiscal" placeholder="Ej. Juan Pérez" required>
                        </div>
                        <div class="mb-3">
                            <label for="rfcFiscal" class="form-label">RFC</label>
                            <input type="text" class="form-control" id="rfcFiscal" placeholder="Ej. PEPJ8001019Q8" maxlength="13" required>
                        </div>
                        <div class="mb-3">
                            <label for="correoFiscal" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correoFiscal" placeholder="Ej. juan.perez@email.com" required>
                        </div>
                        <div>
                            <label for="cpFiscal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="cpFiscal" placeholder="Ej. 12345" maxlength="5" required>
                        </div>
                        <div class="mb-3">
                            <label for="regimenFiscal" class="form-label">Régimen Fiscal</label>
                            <select class="form-select" id="regimenFiscal" required>
                                <option value="">Selecciona una opción</option>
                                <option value="601">General de Ley Personas Morales</option>
                                <option value="603">Personas Morales con Fines no Lucrativos</option>
                                <option value="605">Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
                                <option value="606">Arrendamiento</option>
                                <option value="608">Demás ingresos</option>
                                <option value="609">Consolidación</option>
                                <option value="610">Residentes en el Extranjero sin Establecimiento Permanente en México</option>
                                <option value="611">Ingresos por Dividendos (socios y accionistas)</option>
                                <option value="612">Personas Físicas con Actividades Empresariales y Profesionales</option>
                                <option value="614">Ingresos por intereses</option>
                                <option value="615">Régimen de los ingresos por obtención de premios</option>
                                <option value="616">Sin obligaciones fiscales</option>
                                <option value="620">Sociedades Cooperativas de Producción</option>
                                <option value="621">Incorporación Fiscal</option>
                                <option value="622">Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
                                <option value="623">Opcional para Grupos de Sociedades</option>
                                <option value="624">Coordinados</option>
                                <option value="625">Régimen de Plataformas Tecnológicas</option>
                                <option value="626">Régimen Simplificado de Confianza</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="usoCfdi">Uso CFDI</label>
                            <select class="form-select" id="usoCfdi" required>
                                <option value="">Selecciona una opción</option>
                                <option value="G01">Adquisición de mercancías</option>
                                <option value="G02">Devoluciones, descuentos o bonificaciones</option>
                                <option value="G03">Gastos en general</option>
                                <option value="I01">Construcción</option>
                                <option value="I02">Mobilario y equipo de oficina</option>
                                <option value="I03">Equipo de transporte</option>
                                <option value="I04">Equipo de cómputo</option>
                                <option value="I05">Otros activos</option>
                            </select>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg fw-semibold">
                                <i class="bi bi-check-circle me-2"></i> Guardar Información Fiscal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal de Sucursales -->
<div class="modal fade" id="modalSucursales" tabindex="-1" aria-labelledby="modalSucursalesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalSucursalesLabel">Selecciona la Sucursal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <input type="text" class="form-control mb-3" id="busquedaSucursal" placeholder="Buscar sucursal...">
                <ul class="list-group" id="listaSucursales">
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Alaska')">Alaska</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('California')">California</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Delaware')">Delaware</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Tennessee')">Tennessee</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Texas')">Texas</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Washington')">Washington</li>
                    <li class="list-group-item list-group-item-action" onclick="seleccionarSucursal('Florida')">Florida</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script>
    //boton para abrir registro de informacion fiscal
    document.getElementById('btnBuscarTicket').addEventListener('click', function() {
        var infoContainer = document.getElementById('infoRegistroContainer');
        if (infoContainer) {
            infoContainer.classList.remove('d-none');
            infoContainer.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
</script>
<script>
    // Filtrar sucursales
    document.getElementById('busquedaSucursal').addEventListener('input', function() {
        var filtro = this.value.toLowerCase();
        var items = document.querySelectorAll('#listaSucursales li');
        items.forEach(function(item) {
            if (item.textContent.toLowerCase().includes(filtro)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Seleccionar sucursal y cerrar modal
    function seleccionarSucursal(nombre) {
        document.getElementById('lugarCompraInput').value = nombre;
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalSucursales'));
        modal.hide();
    }
</script>