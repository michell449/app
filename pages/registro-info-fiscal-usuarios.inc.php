<!-- Contenedor principal -->
<div class="content-wrapper" style="min-height: 100vh; background: linear-gradient(135deg, #f4f6f9 100%); padding-top: 2rem;">
    <!-- Encabezado de la página -->
    <div class="card shadow-sm bg-primary text-white border-0 mb-4 animate__animated animate__fadeInDown">
        <div class="card-body py-3 px-4">
            <h4 class="mb-0 text-center" style="letter-spacing:1px;"><i class="bi bi-receipt-cutoff me-2"></i> Facturación Electrónica <span class="fw-light">- Información fiscal</span></h4>
        </div>
    </div>

    <div class="row justify-content-center align-items-start g-4">
        <!-- Información Fiscal -->
        <div class="col-12 col-md-10 col-lg-8 animate__animated animate__fadeInUp" id="infoRegistroContainer">
            <div class="card shadow-lg border-0" style="border-radius: 1.5rem; background: #fff;">
                <div class="card-body p-4">
                    <h4 class="text-center text-primary mb-4" style="font-weight:700; letter-spacing:1px;">
                        <i class="bi bi-person-lines-fill me-2"></i> Información Fiscal
                    </h4>
                    <form id="formInfoFiscal">
                        <div class="mb-4 text-center">
                            <label class="form-label mb-2 fw-semibold text-secondary">Registrar constancia de situación fiscal</label>
                            <input type="file" class="form-control w-75 mx-auto" id="constanciaFiscal" accept="application/pdf,image/*">
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="nombreFiscal" class="form-label">Nombre o Razón Social</label>
                                <input type="text" class="form-control" id="nombreFiscal" placeholder="Ej. Juan Pérez" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="rfcFiscal" class="form-label">RFC</label>
                                <input type="text" class="form-control" id="rfcFiscal" placeholder="Ej. PEPJ8001019Q8" maxlength="13" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="cpFiscal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="cpFiscal" placeholder="Ej. 12345" maxlength="5" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="correoFiscal" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correoFiscal" placeholder="Ej. juan.perez@email.com" required>
                            </div>
                            <div class="col-12 col-md-6">
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
                            <div class="col-12 col-md-6">
                                <label for="usoCfdi" class="form-label">Uso CFDI</label>
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
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg fw-semibold shadow-sm" style="border-radius:0.7rem;">
                                <i class="bi bi-check-circle me-2"></i> Guardar Información Fiscal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sucursales -->

</div>
</div>
</div>
</div>