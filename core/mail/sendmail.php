<?php

include_once '../config.php';
include 'mail/class.phpmailer.php';
include 'mail/class.smtp.php';

function enviacorreo($name, $email, $subject, $msg, $template = 'plantilla1.html', $attachments = []) {
    /* Leer plantilla de correo */
    $maitemp = "mail/plantillas/".$template;
    $fp = fopen($maitemp, "r");
    $msg_body = '';
    while (!feof($fp)) {
        $msg_body .= fgets($fp);
    }
    fclose($fp);
    $msg_body = str_replace('[$_ASUNTO]', $subject, $msg_body);
    $msg_body = str_replace('[$_NOMBRE]', $name, $msg_body);
    $msg_body = str_replace('[$_TEXTO]', $msg, $msg_body);

    $mail = new PHPMailer(true);

    try {
        $mail->IsSMTP();
        $mail->isHTML(true);
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = MAIL_AUT;
        $mail->SMTPSecure = MAIL_SEC;
        $mail->Host = MAIL_HOST;
        $mail->Port = MAIL_PORT;
        $mail->AddAddress($email);
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PSWD;
        $mail->SetFrom(MAIL_USER, 'No Reply - Mtto Pro Lab.');
        $mail->AddReplyTo(MAIL_USER, "No Reply - Mtto Pro Lab.");
        $mail->Subject = $subject;
        $mail->Body = $msg_body;
        $mail->AltBody = $msg_body;
        $mail->CharSet = "utf-8";

        // Adjuntos opcionales
        if (!empty($attachments)) {
            // Permitir string (ruta) o arreglo de rutas o arreglos con ['path'=>, 'name'=>]
            if (is_string($attachments)) {
                if (file_exists($attachments)) {
                    $mail->AddAttachment($attachments);
                }
            } elseif (is_array($attachments)) {
                foreach ($attachments as $att) {
                    if (is_string($att)) {
                        if (file_exists($att)) {
                            $mail->AddAttachment($att);
                        }
                    } elseif (is_array($att)) {
                        $p = $att['path'] ?? '';
                        $n = $att['name'] ?? '';
                        if ($p && file_exists($p)) {
                            if ($n) {
                                $mail->AddAttachment($p, $n);
                            } else {
                                $mail->AddAttachment($p);
                            }
                        }
                    }
                }
            }
        }

        if ($mail->Send()) {
           return true;
        }
    } catch (phpmailerException $ex) {
        $_SESSION['ERROR_MSG'] = $ex->errorMessage();
        return false;
    }
}
