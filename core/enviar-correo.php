<?php
    // enviar-correo.inc.php
    // Función para enviar correo de invitación de cita personalizada
    // Requiere PHPMailer instalado y configurado


    require_once __DIR__ . '/mail/class.phpmailer.php';
    require_once __DIR__ . '/mail/class.smtp.php';
    require_once 'config.php'; // Tu configuración de BD

    function enviarInvitacionCita($id_cita, $contactos_ids, $conn) {
    // 1. Obtener datos de la cita desde la tabla citas_citas (PDO)
    $sqlCita = "SELECT * FROM citas_citas WHERE id_cita = ?";
    $stmtCita = $conn->prepare($sqlCita);
    $stmtCita->execute([$id_cita]);
    $cita = $stmtCita->fetch(PDO::FETCH_ASSOC);
    if (!$cita) return false;

        // 2. Obtener contactos
        // Asegurarse de incluir el contacto principal de la cita
        $contactos_ids[] = $cita['id_contacto'];
        $contactos_ids = array_unique(array_filter($contactos_ids));
        $ids = implode(',', array_map('intval', $contactos_ids));
    // Obtener contactos de sys_contactos
    $sqlContactos = "SELECT id_contacto, nombre, correo FROM sys_contactos WHERE id_contacto IN ($ids)";
    $resultContactos = $conn->query($sqlContactos);
    if (!$resultContactos) return false;
    $contactosArray = $resultContactos->fetchAll(PDO::FETCH_ASSOC);

    // Obtener colaborador asignado de sys_colaboradores
    $colabArray = [];
    if (!empty($cita['id_colab'])) {
        $stmtColab = $conn->prepare("SELECT id_colab, nombre, correo FROM sys_colaboradores WHERE id_colab = ? LIMIT 1");
        $stmtColab->execute([$cita['id_colab']]);
        $colab = $stmtColab->fetch(PDO::FETCH_ASSOC);
        if ($colab) {
            if (!empty($colab['correo'])) {
                // Para el link de confirmación, usar id_colab como id_contacto en el enlace para el colaborador
                $colab['id_contacto'] = $colab['id_colab'];
                $colabArray[] = $colab;
            } else {
                echo '<div style="color:orange;font-weight:bold;padding:10px;">El colaborador asignado (ID: ' . htmlspecialchars($colab['id_colab']) . ') no tiene correo registrado y no se le envió invitación.</div>';
            }
        } else {
            echo '<div style="color:red;font-weight:bold;padding:10px;">No se encontró el colaborador asignado (ID: ' . htmlspecialchars($cita['id_colab']) . ').</div>';
        }
    }
    // Unir ambos arrays para enviar a todos
    $destinatarios = array_merge($contactosArray, $colabArray);
    // Log de depuración: mostrar destinatarios
    echo '<pre style="background:#f8f9fa;border:1px solid #ccc;padding:10px;">Destinatarios: ' . print_r($destinatarios, true) . '</pre>';

        // 3. Preparar datos para el correo
        $asunto = "Invitación a cita: " . $cita['asunto'];
        // Todos los datos de la cita
        $tipo_reunion = isset($cita['tipo_reunion']) ? $cita['tipo_reunion'] : '';
        $lugar = isset($cita['ubicacion']) ? $cita['ubicacion'] : '';
        $todo_dia = $cita['todo_dia'] ? 'Sí' : 'No';
        // Formatear fecha a dd/mm/yyyy
        $fecha_inicio = '';
        if (!empty($cita['fecha_inicio'])) {
            $fecha = date_create($cita['fecha_inicio']);
            if ($fecha) {
                $fecha_inicio = date_format($fecha, 'd/m/Y');
            } else {
                $fecha_inicio = $cita['fecha_inicio'];
            }
        }
        $hora_inicio = $cita['hora_inicio'];
        $duracion = isset($cita['duracion']) ? $cita['duracion'] : '';
        $descripcion = isset($cita['detalles']) ? $cita['detalles'] : '';
        $id_contacto = $cita['id_contacto'];
        $enviar_correo = $cita['enviar_correo'] ? 'Sí' : 'No';
        $id_colab = $cita['id_colab'];
        $fecha_registro = $cita['fecha_registro'];
        $activo = $cita['activo'] ? 'Activa' : 'Inactiva';

    // 4. Enviar correo a cada contacto y colaborador
    foreach ($destinatarios as $contacto) {
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->isHTML(true);
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = MAIL_AUT;
        $mail->SMTPSecure = MAIL_SEC;
        $mail->Host = MAIL_HOST;
        $mail->Port = MAIL_PORT;
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PSWD;
        $mail->setFrom(MAIL_USER, 'No Reply - Mtto Pro Lab.');
        $mail->addAddress($contacto['correo'], $contacto['nombre']);
        $mail->addReplyTo(MAIL_USER, 'No Reply - Mtto Pro Lab.');
        $mail->Subject = $asunto;
        $mail->CharSet = 'utf-8';

            // Usar plantilla HTML personalizada
            $plantilla_path = __DIR__ . '/mail/plantillas/plantilla1.html';
            if (!file_exists($plantilla_path)) {
                echo '<div style="color:red;font-weight:bold;padding:10px;">No se encontró la plantilla de correo.</div>';
                continue;
            }
            $plantilla = file_get_contents($plantilla_path);
        // Detectar el protocolo y dominio actual
        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $dominio = $_SERVER['HTTP_HOST'];
        $link_confirmacion = '';
        // Solo mostrar el botón de confirmación si es contacto, no colaborador
        if (isset($contacto['id_contacto']) && (!isset($contacto['id_colab']) || $contacto['id_contacto'] != $cita['id_colab'])) {
            $link_confirmacion = $protocolo . $dominio . '/app/core/confirmar-asistencia.php?id_cita=' . $id_cita . '&id_contacto=' . $contacto['id_contacto'];
        }
            $texto = "Estimado(a) {$contacto['nombre']},<br><br>Le invitamos cordialmente a la siguiente cita:<br><br>"
                . "<strong>Asunto:</strong> {$cita['asunto']}<br>"
                . "<strong>Tipo de reunión:</strong> {$tipo_reunion}<br>"
                . "<strong>Lugar:</strong> {$lugar}<br>"
                . "<strong>Todo el día:</strong> {$todo_dia}<br>"
                . "<strong>Fecha de inicio:</strong> {$fecha_inicio}<br>"
                . "<strong>Duración:</strong> {$duracion}<br>"
                . "<strong>Descripción:</strong> {$descripcion}<br><br>";
        if ($link_confirmacion) {
            $texto .= "<a href='" . $link_confirmacion . "' style='display:inline-block;padding:10px 20px;background:#4caf50;color:#fff;text-decoration:none;border-radius:5px;'>Confirmar asistencia</a><br><br>";
        }
            $body = str_replace([
                '[$_ASUNTO]',
                '[$_NOMBRE]',
                '[$_TEXTO]'
            ], [
                $asunto,
                $contacto['nombre'],
                $texto
            ], $plantilla);
            $mail->Body = $body;
            if (!$mail->send()) {
                echo '<div style="color:red;font-weight:bold;padding:10px;">Error al enviar correo a ' . htmlspecialchars($contacto['correo']) . ': ' . htmlspecialchars($mail->ErrorInfo) . '</div>';
                continue;
            }
        }
        return true;
    }
?>