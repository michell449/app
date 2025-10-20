<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 100vh;">
    <!-- Encabezado de la página con tarjeta azul -->
    <div class="card shadow-sm w-100 bg-primary border-0" style="margin-bottom: 18px;">
      <div class="card-header bg-primary text-white d-flex align-items-center" style="gap: 16px; border-bottom: none;">
        <h4 class="mb-0 text-white"><i class="fas fa-envelope me-2"></i> Mensajes Internos</h4>
      </div>
    </div>

    <!-- Contenido principal -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <!-- Botón Redactar centrado y separado del encabezado -->
          <div align="center" style="margin-bottom: 20px; margin-top: 10px;">
            <button type="button" class="btn btn-primary" id="btnRedactar">
              <i class="fas fa-edit"></i> <strong>Redactar</strong>
            </button>
          </div>
          <div class="card" style="margin-left: 10px;">
            <header class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
              <h2 class="card-title" style="font-size: 1.3rem; margin: 0;">
                <i class="fas fa-folder-open"></i> Carpetas
              </h2>
              <nav class="card-tools">
                <button type="button" class="btn btn-tool" id="btnMinimizarCarpetas" title="Colapsar" onclick="minimizarCarpetas()">
                  <i class="fas fa-minus" id="iconMinimizarCarpetas"></i>
                </button>
              </nav>
            </header>
            <div class="card-body p-0">
              <nav>
                <ul class="nav nav-pills flex-column" style="list-style: none; padding-left: 0;">
                  <li class="nav-item active">
                    <a href="#" class="nav-link d-flex justify-content-between align-items-center" id="btnBandejaEntrada" onclick="mostrarBandejaEntrada(); actualizarContadoresCarpetas();">
                      <span><i class="fas fa-inbox"></i> <strong>Bandeja de Entrada</strong></span>
                      <span class="badge bg-primary" id="contadorEntrada">0</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link d-flex justify-content-between align-items-center" id="btnEnviados" onclick="mostrarEnviados(); actualizarContadoresCarpetas();">
                      <span><i class="far fa-envelope"></i> <strong>Enviados</strong></span>
                      <span class="badge bg-primary" id="contadorEnviados">0</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link d-flex justify-content-between align-items-center" id="btnPapelera" onclick="mostrarPapelera(); actualizarContadoresCarpetas();">
                      <span><i class="far fa-trash-alt"></i> <strong>Papelera</strong></span>
                      <span class="badge bg-primary" id="contadorPapelera">0</span>
                    </a>
                  </li>
                </ul>
              </nav>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
            <!-- Sección de etiquetas eliminada -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline" style="margin-top: 20px;">
            <div class="card-header">
              <h3 class="card-title">Bandeja de Entrada</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" placeholder="Buscar Correo">
                  <div class="input-group-append">
                    <div class="btn btn-primary">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Botón de seleccionar todo -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" id="btnArchivarSeleccionados">
                    <i class="far fa-trash-alt"></i>
                  </button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm">
                  <i class="fas fa-sync-alt"></i>
                </button>
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                  <!-- Las filas de mensajes serán generadas dinámicamente por JavaScript -->
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div id="paginacionMensajes" class="d-flex justify-content-between align-items-center px-3 py-2">
                <span id="infoPaginacion"></span>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm" id="btnAnterior">
                    <i class="fas fa-chevron-left"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm" id="btnSiguiente">
                    <i class="fas fa-chevron-right"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Modal Redactar Mensaje -->
<div class="modal" id="modalRedactar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit"></i> Redactar Mensaje</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" onclick="document.getElementById('modalRedactar').style.display='none'">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formRedactar">
          <div class="form-group">
            <label>Para:</label>
            <div id="participantesCheckboxes" class="d-flex flex-wrap" style="gap: 8px;"></div>
            <small class="text-muted">Selecciona uno o más colaboradores.</small>
          </div>
          <div class="form-group mb-3 d-flex flex-column">
              <label for="mensaje" class="mb-2">Mensaje: </label>
              <textarea class="form-control" id="mensaje" name="mensaje" style="min-height: 120px; resize: vertical;"></textarea>
          </div>
          <!--
          <div class="form-group">
            <label for="fecha_vencimiento">Fecha de vencimiento (opcional):</label>
            <input type="datetime-local" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
          </div>
          -->
          <input type="hidden" id="id_padre" name="id_padre">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="enviarMensaje()">Enviar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="document.getElementById('modalRedactar').style.display='none'">Cancelar</button>
      </div>
    </div>
  </div>
</div>


