<?php
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';

    $database = new Database();
    $db = $database->getConnection();
    $crud = new crud($db);

    // get::contactos
    $contactos = $crud->customQuery("
        SELECT c.id_contacto, c.nombre AS contacto_nombre, e.razon_social AS empresa_nombre
        FROM sys_contactos c
        LEFT JOIN sys_clientes e ON c.cliente_empresa = e.id_cliente
    ");

    // get::colaboradores
    $colaboradores = $crud->customQuery("SELECT id_colab, nombre FROM sys_colaboradores");

    // post ::guardarCita
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asunto'])) {
        // Validar y sanitizar todos los campos
        $id_colab = null;
        $id_contacto = null;
        if (
            isset($_POST['colaborador']) &&
            $_POST['colaborador'] !== '' &&
            is_numeric($_POST['colaborador']) &&
            intval($_POST['colaborador']) > 0
        ) {
            $id_colab = intval($_POST['colaborador']);
        }

        // Validar colaborador si se seleccionó uno
        if ($id_colab > 0) {
            $stmtCheck = $db->prepare("SELECT COUNT(*) FROM sys_colaboradores WHERE id_colab = ?");
            $stmtCheck->execute([$id_colab]);
            if ($stmtCheck->fetchColumn() == 0) {
                echo '<script>alert("Colaborador no válido"); window.history.back();</script>';
                exit;
            }
        }
        // Validar contacto
        if (isset($_POST['cliente']) && $_POST['cliente'] !== '' && is_numeric($_POST['cliente']) && intval($_POST['cliente']) > 0) {
            $id_contacto = intval($_POST['cliente']);
        }

        // Validar asunto
        $asunto = isset($_POST['asunto']) ? trim(strip_tags($_POST['asunto'])) : '';
        if ($asunto === '' || strlen($asunto) > 255) {
            echo '<script>alert("Asunto inválido o demasiado largo (máx 255)"); window.history.back();</script>';
            exit;
        }

        // Validar ubicación
        $ubicacion = isset($_POST['ubicacion']) ? trim(strip_tags($_POST['ubicacion'])) : '';
        if (strlen($ubicacion) > 255) {
            echo '<script>alert("Ubicación demasiado larga (máx 255)"); window.history.back();</script>';
            exit;
        }

        // Validar que ningún filtro/campo esté vacío (excepto todo_dia y enviar_correo)
        $duracion = isset($_POST['duracion']) ? $_POST['duracion'] : '';
        $detalles = isset($_POST['compose-textarea']) ? trim(strip_tags($_POST['compose-textarea'])) : '';
        $tipo_reunion = isset($_POST['tipo']) ? trim(strip_tags($_POST['tipo'])) : '';
        $fecha_inicio = isset($_POST['inicio']) ? $_POST['inicio'] : '';
        $camposObligatorios = [$id_colab, $id_contacto, $asunto, $ubicacion, $duracion, $detalles, $tipo_reunion, $fecha_inicio];
        foreach ($camposObligatorios as $campo) {
            if ($campo === null || $campo === '') {
                echo '<script>alert("Por favor, llena todos los filtros y campos obligatorios antes de guardar la cita."); window.history.back();</script>';
                exit;
            }
        }

        // Validar duración
        $duracion = isset($_POST['duracion']) ? $_POST['duracion'] : '';
        if ($duracion !== '' && !preg_match('/^\d{2}:\d{2}$/', $duracion)) {
            echo '<script>alert("Duración inválida (formato HH:MM)"); window.history.back();</script>';
            exit;
        }

        // Validar detalles (descripción) - permite texto largo pero sin etiquetas peligrosas
        $detalles = isset($_POST['compose-textarea']) ? trim(strip_tags($_POST['compose-textarea'])) : '';

        // Validar status (solo permitir Programada al crear)
        $status = 'Programada';

        // Validar enviar_correo
        $enviar_correo = (isset($_POST['enviarCorreo']) && $_POST['enviarCorreo'] === 'si') ? 1 : 0;

        // Validar tipo de reunión
        $tipo_reunion = isset($_POST['tipo']) ? trim(strip_tags($_POST['tipo'])) : '';
        if ($tipo_reunion === '') {
            echo '<script>alert("Debes seleccionar un tipo de reunión"); window.history.back();</script>';
            exit;
        }
        if (strlen($tipo_reunion) > 50) {
            echo '<script>alert("Tipo de reunión demasiado largo (máx 50)"); window.history.back();</script>';
            exit;
        }

        // Validar todo_dia
        $todo_dia = 0;
        if (isset($_POST['todoDia']) && $_POST['todoDia'] === 'si') {
            $todo_dia = 1;
        }

        try {
            $stmt = $db->prepare("
        INSERT INTO citas_citas 
        (id_colab, id_contacto, asunto, tipo_reunion, ubicacion, fecha_inicio, todo_dia, detalles, status, duracion, enviar_correo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $id_colab !== null ? $id_colab : null,
                $id_contacto !== null ? $id_contacto : null,
                $asunto,
                $tipo_reunion,
                $ubicacion,
                $fecha_inicio,
                $todo_dia,
                $detalles,
                $status,
                $duracion,
                $enviar_correo
            ]);
            // Obtener el id_cita recién insertado
            $id_cita = $db->lastInsertId();
            // Notificar al colaborador asignado
            if ($id_colab !== null && $id_colab > 0 && $id_cita) {
                $noti_stmt = $db->prepare("INSERT INTO sys_notificaciones (id_colab, id_cita, mensaje, tipo, fecha, leido) VALUES (?, ?, ?, ?, NOW(), 0)");
                $mensaje = 'Has sido asignado a una nueva cita: ' . $asunto;
                $noti_stmt->execute([$id_colab, $id_cita, $mensaje, 'cita']);
            }

            // Enviar correo si corresponde
            if ($enviar_correo == 1 && $id_contacto !== null && $id_cita) {
                require_once __DIR__ . '/enviar-correo.php';
                $contactos_ids = [$id_contacto];
                // Usar la conexión PDO de la clase Database
                enviarInvitacionCita($id_cita, $contactos_ids, $db);
            }

            // Redirigir después de guardar para evitar reenvío al refrescar y mostrar SweetAlert en la recarga
            header("Location: " . $_SERVER['REQUEST_URI'] . "&success=1");
            exit;
        } catch (Exception $e) {
            echo '<div style="color:red;font-weight:bold;padding:10px;">Error al guardar la cita: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }

    // Mostrar SweetAlert2 si se guardó correctamente
    if (isset($_GET['success']) && $_GET['success'] == '1') {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>Swal.fire({
            icon: "success",
            title: "¡Cita guardada!",
            text: "La cita se guardó correctamente.",
            confirmButtonText: "Aceptar"
        });</script>';
    }







    
    // Modal para agendar cita
    echo '<div class="modal fade" id="modalAgendarCita" tabindex="-1" aria-labelledby="modalAgendarCitaLabel" aria-hidden="true">';
    echo '  <div class="modal-dialog modal-lg">';
    echo '    <div class="modal-content shadow-lg">';
    echo '      <div class="modal-header bg-gradient-primary text-white py-3">';
    echo '        <h4 class="modal-title fw-bold" id="modalAgendarCitaLabel"><i class="fa fa-calendar-plus me-2"></i>Agendar Cita</h4>';
    echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
    echo '      </div>';
    echo '      <div class="modal-body bg-light">';
    echo '        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
    echo '        <form id="formCita" method="POST">';
    echo '          <div class="row g-3">';
    echo '            <div class="col-md-6">';
    echo '              <labael for="asunto" class="form-label fw-semibold">Asunto</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-briefcase"></i></span>';
    echo '                <input type="text" class="form-control" id="asunto" name="asunto" required placeholder="Ej. Reunión de seguimiento" style="background-color: #fff !important;" onfocus="this.style.backgroundColor=\"#fff\"" onblur="this.style.backgroundColor=\"#fff\"">';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6">';
    echo '              <label for="tipo" class="form-label fw-semibold">Tipo de Reunión</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-users"></i></span>';
    echo '                <select class="form-select" id="tipo" name="tipo" required>';
    echo '                  <option value="">Seleccione...</option>';
    echo '                  <option value="llamada">Llamada telefónica</option>';
    echo '                  <option value="presencial">Reunión presencial</option>';
    echo '                  <option value="video">Videoconferencia</option>';
    echo '                </select>';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6" id="datosLlamada" style="display:none;">';
    echo '              <label for="telefono" class="form-label fw-semibold">Teléfono</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-phone"></i></span>';
    echo '                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ej. 5551234567">';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6" id="datosUbicacion">';
    echo '              <label for="ubicacion" class="form-label fw-semibold">Ubicación</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-map-marker-alt"></i></span>';
    echo '                <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Dirección o URL">';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6" id="datosVideo" style="display:none;">';
    echo '              <label for="linkVideo" class="form-label fw-semibold">Link Videoconferencia</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-video"></i></span>';
    echo '                <input type="url" class="form-control" id="linkVideo" name="linkVideo" placeholder="https://...">';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6">';
    echo '              <label for="todoDia" class="form-label fw-semibold">Todo el día</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-clock"></i></span>';
    echo '                <select class="form-select" id="todoDia" name="todoDia">';
    echo '                  <option value="no">No</option>';
    echo '                  <option value="si">Sí</option>';
    echo '                </select>';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6">';
    echo '              <label for="inicio" class="form-label fw-semibold">Fecha de inicio</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-calendar"></i></span>';
    echo '                <input type="text" class="form-control" id="inicio" name="inicio" required placeholder="dd/mm/yyyy">';
    echo '                <input type="hidden" id="inicio_real" name="inicio">';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6">';
    echo '              <label for="duracion" class="form-label fw-semibold">Duración (HH:MM)</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-hourglass-half"></i></span>';
    echo '                <input type="time" class="form-control" id="duracion" name="duracion">';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-12">';
    echo '              <label for="compose-textarea" class="mb-2 fw-semibold">Descripción</label>';
    echo '              <div class="input-group">';
    echo '                <span class="input-group-text bg-white"><i class="fa fa-align-left"></i></span>';
    echo '                <textarea id="compose-textarea" name="compose-textarea" class="form-control rich-text" style="min-height: 180px; resize: vertical;" placeholder="Detalles de la cita..."></textarea>';
    echo '              </div>';
    echo '            </div>';
    echo '            <div class="col-md-6">';
    echo '              <label for="cliente" class="form-label fw-semibold">Contacto (Empresa)</label>';
    echo '              <select class="form-select" id="cliente" name="cliente">';
    echo '                <option value="">Sin contacto</option>';
    if ($contactos && is_array($contactos) && count($contactos) > 0) {
        foreach ($contactos as $contacto) {
            $empresa = $contacto['empresa_nombre'] ? $contacto['empresa_nombre'] : 'Sin empresa';
            echo '<option value="' . htmlspecialchars($contacto['id_contacto']) . '">' . htmlspecialchars($contacto['contacto_nombre']) . ' (' . htmlspecialchars($empresa) . ')</option>';
        }
    } else {
        echo '<option value="">No hay contactos registrados</option>';
    }
    echo '              </select>';
    echo '            </div>';
    echo '            <div class="col-md-6 mb-2">';
    echo '              <label for="enviarCorreo" class="form-label">Enviar invitación por correo</label>';
    echo '              <select class="form-select" id="enviarCorreo" name="enviarCorreo">';
    echo '                <option value="no">No</option>';
    echo '                <option value="si">Sí</option>';
    echo '              </select>';
    echo '            </div>';
    echo '            <div class="col-md-6 mb-2">';
    echo '            <div class="col-md-6 mb-2">';
    echo '              <label for="colaborador" class="form-label">Colaborador</label>';
    echo '              <select class="form-select" id="colaborador" name="colaborador">';
    echo '                <option value="">Seleccione colaborador...</option>';
        $ids_colab = array();
        if ($colaboradores && is_array($colaboradores) && count($colaboradores) > 0) {
            foreach ($colaboradores as $colab) {
                if (!in_array($colab['id_colab'], $ids_colab)) {
                    echo '<option value="' . htmlspecialchars($colab['id_colab']) . '">' . htmlspecialchars($colab['nombre']) . '</option>';
                    $ids_colab[] = $colab['id_colab'];
                }
            }
        } else {
            echo '<option value="">No hay colaboradores registrados</option>';
        }
    echo '              </select>';
    echo '            </div>';
    echo '          </div>';
    echo '          <button type="submit" class="btn btn-success w-100 mt-3">Guardar Cita</button>';
    echo '        </form>';
    echo '      </div>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
    echo '</div>';

   
    echo '<style>.fc-event, .fc-event-title, .fc-event-time { color: #fff !important; }</style>';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>';
    echo 'document.getElementById("formCita").addEventListener("submit", function(e) {';
    echo '  var asunto = document.getElementById("asunto").value.trim();';
    echo '  var tipo = document.getElementById("tipo").value.trim();';
    echo '  var inicio = document.getElementById("inicio").value.trim();';
    echo '  var cliente = document.getElementById("cliente").value.trim();';
    echo '  var colaborador = document.getElementById("colaborador").value.trim();';
    echo '  var ubicacion = document.getElementById("ubicacion").value.trim();';
    echo '  var detalles = document.getElementById("compose-textarea").value.trim();';
    echo '  var duracion = document.getElementById("duracion").value.trim();';
    echo '  var camposObligatorios = [asunto, tipo, inicio, cliente, colaborador, ubicacion, detalles, duracion];';
    echo '  var vacios = camposObligatorios.filter(function(v){ return v === ""; });';
    echo '  if (vacios.length > 0) {';
    echo '    Swal.fire({ icon: "warning", title: "Campos obligatorios", text: "Por favor, llena todos los filtros y campos obligatorios antes de guardar la cita.", confirmButtonText: "OK" });';
    echo '    e.preventDefault();';
    echo '    return false;';
    echo '  }';
    echo '});';
    echo '</script>';


    // Consulta la cita actual y muestra los detalles en el modal
    if (isset($_GET['id_cita'])) {
        $id_cita = intval($_GET['id_cita']);
        $stmt = $db->prepare("SELECT * FROM citas_citas WHERE id_cita = ? LIMIT 1");
        $stmt->execute([$id_cita]);
        $cita = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cita) {
            // Renderizar el modal con los datos de la cita
            echo '<div class="modal fade" id="modalDetalleCita" tabindex="-1" aria-labelledby="modalDetalleCitaLabel" aria-hidden="true">';
            echo '  <div class="modal-dialog">';
            echo '    <div class="modal-content">';
            echo '      <div class="modal-header bg-info text-white">';
            echo '        <h5 class="modal-title" id="modalDetalleCitaLabel">Detalle de la Cita</h5>';
            echo '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>';
            echo '      </div>';
            echo '      <div class="modal-body">';
            echo '        <form id="formEditarCita">';
            // Asunto
            echo '          <div class="mb-3">';
            echo '            <label for="detalleAsunto" class="form-label"><strong>Asunto:</strong></label>';
            echo '            <div class="input-group">';
            echo '              <span class="input-group-text"><i class="fa fa-briefcase"></i></span>';
            // Siempre mostrar el input con texto blanco y fondo oscuro para consistencia visual
            echo '              <input type="text" class="form-control" style="background:#fff;color:#212529;" id="detalleAsunto" name="asunto" value="' . htmlspecialchars($cita['asunto']) . '">';
            echo '            </div>';
            echo '          </div>';
            // Fecha
            echo '          <div class="mb-3">';
            echo '            <label for="detalleFecha" class="form-label"><strong>Fecha:</strong></label>';
            echo '            <div class="input-group">';
            echo '              <span class="input-group-text"><i class="fa fa-calendar"></i></span>';
    // Mostrar la fecha en formato yyyy-mm-dd para el input tipo date
    $fecha_ymd = date('Y-m-d', strtotime($cita['fecha_inicio']));
    echo '              <input type="date" class="form-control" id="detalleFecha" name="fecha_inicio" value="' . htmlspecialchars($fecha_ymd) . '">';
            echo '            </div>';
            echo '          </div>';
            // Descripción
            echo '          <div class="mb-3">';
            echo '            <label for="detalleDescripcion" class="form-label"><strong>Descripción:</strong></label>';
            echo '            <div class="input-group">';
            echo '              <span class="input-group-text"><i class="fa fa-align-left"></i></span>';
        echo '              <textarea class="form-control" id="detalleDescripcion" name="detalles" rows="4">' . htmlspecialchars($cita['detalles']) . '</textarea>';
            echo '            </div>';
            echo '          </div>';
            // Ubicación
            echo '          <div class="mb-3">';
            echo '            <label for="detalleUbicacion" class="form-label"><strong>Ubicación:</strong></label>';
            echo '            <div class="input-group">';
            echo '              <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>';
        echo '              <input type="text" class="form-control" id="detalleUbicacion" name="ubicacion" value="' . htmlspecialchars($cita['ubicacion']) . '">';
            echo '            </div>';
            echo '          </div>';
            // Duración
            echo '          <div class="mb-3">';
            echo '            <label for="detalleDuracion" class="form-label"><strong>Duración:</strong></label>';
            echo '            <div class="input-group">';
            echo '              <span class="input-group-text"><i class="fa fa-clock"></i></span>';
        echo '              <input type="time" class="form-control" id="detalleDuracion" name="duracion" value="' . htmlspecialchars($cita['duracion']) . '">';
            echo '            </div>';
            echo '          </div>';
            // Status
            echo '          <div class="mb-3">';
            echo '            <label for="detalleStatus" class="form-label"><strong>Status:</strong></label>';
        echo '            <select class="form-select" id="detalleStatus" name="status">';
            $statusOptions = ["Programada", "Realizada", "Cancelada", "Pospuesta"];
            foreach ($statusOptions as $opt) {
                $selected = ($cita['status'] === $opt) ? 'selected' : '';
                echo '<option value="' . $opt . '" ' . $selected . '>' . $opt . '</option>';
            }
            echo '            </select>';
            echo '            <div id="statusBadge" class="mt-2"></div>';
            echo '          </div>';
            // Colaborador
            echo '          <div class="mb-3">';
            echo '            <label for="detalleColaborador" class="form-label"><strong>Colaborador:</strong></label>';
            echo '            <select class="form-select" id="detalleColaborador" name="id_colab">';
            echo '<option value="">Seleccione colaborador...</option>';
            if ($colaboradores && is_array($colaboradores) && count($colaboradores) > 0) {
                foreach ($colaboradores as $colab) {
                    $selected = ($cita['id_colab'] == $colab['id_colab']) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($colab['id_colab']) . '" ' . $selected . '>' . htmlspecialchars($colab['nombre']) . '</option>';
                }
            } else {
                echo '<option value="">No hay colaboradores registrados</option>';
            }
            echo '            </select>';
            echo '          </div>';
            echo '          <div class="mb-3">';
            echo '            <label class="form-label"><strong>Todo el día:</strong></label>';
            $todoDiaClass = ($cita['todo_dia'] ? 'badge bg-info text-white' : 'badge bg-secondary text-white');
            echo '            <span class="' . $todoDiaClass . '">' . ($cita['todo_dia'] ? 'Todo el día' : 'Parcial') . '</span>';
            echo '          </div>';
            echo '          <input type="hidden" id="detalleIdCita" name="id_cita" value="' . htmlspecialchars($cita['id_cita']) . '">';
            echo '          <input type="hidden" id="detalleTodoDia" name="todo_dia" value="' . htmlspecialchars($cita['todo_dia']) . '">';
            echo '        </form>';
            echo '      </div>';
            echo '      <div class="modal-footer">';
            echo '        <button type="button" class="btn btn-success" id="btnGuardarCita">Guardar</button>';
            echo '      </div>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        }
    }
?>