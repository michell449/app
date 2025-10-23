<!-- Contenedor principal -->
<div class="content-wrapper" style="min-height: 100vh; background-color: #f4f6f9; padding-top: 2rem;">
    <!-- Encabezado de la página -->
    <div class="card shadow-sm bg-primary text-white border-0 mb-4">
        <div class="card-body ">
            <h4 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Facturación Electrónica</h4>
        </div>
    </div>

    <div class="container">
        <div class=" justify-content-center">
            <!-- Sección buscar ticket -->
            <div class="col-md-6 col-lg-5 mb-4">
                <div class="card shadow-sm" style="border-radius: 1rem; padding: 2rem; background-color: white;">
                    <h3 class="text-center text-primary mb-4">Facturar como Invitado</h3>
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
                        <div>
                            <label for="lugarCompra" class="form-label">Lugar de Compra</label>

                                    <label>Disabled Result</label>
                                    <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="9" tabindex="-1" aria-hidden="true">
                                        <option selected="selected" data-select2-id="11">Alabama</option>
                                        <option data-select2-id="90">Alaska</option>
                                        <option disabled="disabled" data-select2-id="91">California (disabled)</option>
                                        <option data-select2-id="92">Delaware</option>
                                        <option data-select2-id="93">Tennessee</option>
                                        <option data-select2-id="94">Texas</option>
                                        <option data-select2-id="95">Washington</option>
                                    </select><span class="select2 select2-container select2-container--default select2-container--below select2-container--focus" dir="ltr" data-select2-id="10" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-zakz-container"><span class="select2-selection__rendered" id="select2-zakz-container" role="textbox" aria-readonly="true" title="Alabama">Alabama</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


</div>