<!-- Contenedor principal -->
<div class="content-wrapper" style="min-height: 100vh; background-color: #f4f6f9; padding-top: 2rem;">
    <!-- Encabezado de la página -->
    <div class="card shadow-sm bg-primary text-white border-0 mb-4">
        <div class="card-body ">
            <h4 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i> Facturación Electrónica</h4>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <!-- Sección de introducción -->
            <div class="col-md-4 col-lg-6 mb-4">
                <div class="card shadow-sm" style="border-radius: 1rem; padding: 2rem; background-color: white;">
                    <h1 class="text-primary mb-3" style="font-size: 1.5rem;">Crea una cuenta en nuestro portal</h1>
                    <p><strong>Con tu cuenta podrás llevar un registro de tus compras y facturas, usar perfiles predeterminados para facturar de forma rápida y segura.</strong></p>
                    <p>También puedes acceder como invitado para generar tu factura sin registrarte.</p>
                    <p>Recuerda que solo puedes facturar tickets dentro de los 90 días naturales siguientes a la fecha de compra.</p>
                    <p class="mt-4"><strong>Para generar la factura de tu compra, necesitas los siguientes datos:</strong></p>
                    <ul>
                        <li><i class="bi bi-check-circle text-primary"></i> Número de venta (Ticket)</li>
                        <li><i class="bi bi-check-circle text-primary"></i> Fecha de compra</li>
                        <li><i class="bi bi-check-circle text-primary"></i> Lugar en la que realizaste la compra</li>
                        <li><i class="bi bi-check-circle text-primary"></i> Monto total de la compra</li>
                    </ul>
                    <p class="mt-3">Si tienes dudas, contacta a Atención a Clientes al <a href="tel:+8004559524" class="text-primary">800 455 9524</a> o por correo a <a href="mailto:admin@despacho.com" class="text-primary">admin@despacho.com</a></p>
                </div>
            </div>

            <!-- Sección de login -->
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm" style="border-radius: 1rem; padding: 2rem; background-color: white;">
                    <h3 class="text-center text-primary mb-4">Inicio de Sesión</h3>
                    <form>
                        <!-- Campo de usuario -->
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Usuario</label>
                            <input type="email" class="form-control" id="inputEmail" placeholder="Introduce tu email" required>
                        </div>

                        <!-- Campo de contraseña -->
                        <div class="mb-3">
                            <label for="inputPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="inputPassword" placeholder="Introduce tu contraseña" required>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary w-48">Acceder</button>
                            <button type="button" class="btn btn-secondary w-48" data-bs-toggle="modal" data-bs-target="#crearCuentaModal">Crear Cuenta</button>
                        </div>
                    </form>

                    <!-- Enlace de recuperación de contraseña -->
                    <div class="text-center mt-3">
                        <a href="#" class="text-primary">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
                <!-- Sección de "Ingresar como invitado" -->
                <div class="col-12 text-center mt-4">
                    <div class="card shadow-sm" style="border-radius: 1rem; background-color: white; padding: 1.5rem;">
                        <a href="panel?pg=facturar-invitado" class="btn btn-info w-100" style="color: white; font-size: 1.2rem;">
                                 Ingresar como invitado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de creación de cuenta -->

        <!-- Modal de creación de cuenta mejorado -->
        <div class="modal fade" id="crearCuentaModal" tabindex="-1" aria-labelledby="crearCuentaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title w-100 text-center" id="crearCuentaModalLabel" style="font-weight: bold;">Crear Cuenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" style="background-color: white;"></button>
                    </div>
                    <div class="modal-body text-center" style="padding: 2.5rem 2rem;">
                        <div class="mb-3">
                            <i class="bi bi-person-plus-fill text-primary" style="font-size: 3.5rem;"></i>
                        </div>
                        <form>
                            <div class="mb-4">
                                <label for="nuevoEmail" class="form-label" style="font-weight: 500;">Email</label>
                                <input type="email" class="form-control form-control-lg text-center" id="nuevoEmail" placeholder="Introduce tu email" required style="font-size: 1.1rem;">
                            </div>
                            <div class="mb-4">
                                <label for="nuevaContraseña" class="form-label" style="font-weight: 500;">Contraseña</label>
                                <input type="password" id="nuevaContraseña" class="form-control form-control-lg text-center" aria-describedby="passwordHelpBlock" placeholder="Ingresa tu contraseña" required style="font-size: 1.1rem;">
                            </div>
                            <div class="mb-4">
                                <label for="confirmarContraseña" class="form-label" style="font-weight: 500;">Confirma tu contraseña</label>
                                <input type="password" id="confirmarContraseña" class="form-control form-control-lg text-center" aria-describedby="passwordHelpBlock" placeholder="Ingresa nuevamente tu contraseña" required style="font-size: 1.1rem;">
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <button type="button" class="btn btn-secondary w-50 me-2" data-bs-dismiss="modal" style="border-radius: 0.75rem;">Cancelar</button>
                                <button type="button" class="btn btn-primary w-50 ms-2" data-bs-toggle="modal" data-bs-target="#verificacionCorreoModal" style="font-size: 1.1rem; border-radius: 0.75rem; font-weight: 600;">Crear Cuenta</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <!-- Modal de verificación de correo -->

    <!-- Modal de verificación de correo mejorado -->
    <div class="modal fade" id="verificacionCorreoModal" tabindex="-1" aria-labelledby="verificacionCorreoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title w-100 text-center" id="verificacionCorreoModalLabel" style="font-weight: bold;">Verificación de Correo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" style="background-color: white;"></button>
                </div>
                <div class="modal-body text-center" style="padding: 2.5rem 2rem;">
                    <div class="mb-3">
                        <i class="bi bi-envelope-fill text-primary" style="font-size: 4rem;"></i>
                    </div>
                    <p class="mb-2">Te hemos enviado un código de verificación a tu correo.</p>
                    <p class="mb-4">Ingresa el código para completar el registro.</p>
                    <form id="formVerificacionCorreo">
                        <div class="mb-4">
                            <label for="codigoVerificacion" class="form-label">Código de verificación</label>
                            <input type="text" class="form-control form-control-lg text-center" id="codigoVerificacion" placeholder="Ingresa el código" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" style="font-size: 1.2rem; padding: 0.75rem 0; border-radius: 0.75rem; font-weight: 600;">Verificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>