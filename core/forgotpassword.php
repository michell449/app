<?php
include_once '../config.php';
include_once 'class/class_users.php';
include_once 'mail/sendmail.php';
$lOk = false;
//Recibiendo datos del formulario
if (isset($_POST['forgotpsw'])) {
    $data['email'] = $_POST['email'];
    $data['token'] = bin2hex(openssl_random_pseudo_bytes(32));
   
    $expFormat = mktime(
            date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y")
    );
    $data['expDate'] = date("Y-m-d H:i:s", $expFormat);
    $database = new Database();
    $db = $database->getConnection();
    $item = new users($db);
    $item->data = $data;
   
    if ($item->resetpassword($data['email'])) {
        $name = $data['email'];
        $email = $data['email'];
        $subject = "Recuperación de Contraseña";
        $msg = '<p>Por favor haga clic en el siguiente link o copie y pegue en su navegador para reiniciar su contraseña</p>';
        $msg .= '<p>-------------------------------------------------------------</p>';
        $resetUrl = HOMEURL . '/reset-password?key=' . $data['token'];
        $msg .= '<p><a href=' . $resetUrl . ' target="_blank">';
        $msg .= $resetUrl . '</a></p>';
        $msg .= '<p>-------------------------------------------------------------</p>';
        $msg .= '<p>Asegúrese de copiar el enlace completo en su navegador. El enlace caducará después de 24 horas por motivos de seguridad.</p>';
        $msg .= '<p>Si no solicitó reestablecer su contraseña, no es necesario realizar nunguna acción, su contraseña no se restablecerá. Sin embargo, es posible que desee iniciar sesión en su cuenta y cambie su contraseña por seguridad.</p>';
        $lOk = enviacorreo($name, $email, $subject, $msg);
        if (!$lOk && isset($_SESSION['ERROR_MSG'])) {
            echo '<div style="color:red; font-weight:bold;">Error al enviar correo: ' . $_SESSION['ERROR_MSG'] . '</div>';
        } else if ($lOk) {
            echo '<div style="color:green; font-weight:bold;">Correo enviado correctamente.</div>';
        }
    } else {
        echo '<div style="color:red; font-weight:bold;">' . $item->error . '</div>';
        $_SESSION['ERROR_MSG'] = $item->error;
    }
} else {
    $_SESSION['ERROR_MSG'] = 'Formulario no valido.';
}
if ($lOk) {
    $_SESSION['DEFAULT_MSG'] = "Se ha enviado un correo electrónico a " . $data['email'] . " con las instrucciones para recuperar su contraseña.";
    $url = '/forgotpassword';
} else {
    $_SESSION['ERROR_MSG'] = $_SESSION['ERROR_MSG'] ?? 'No se pudo enviar el correo.';
    $url = '/forgotpassword';
}

header('Location: ' .HOMEURL. '/forgotpassword?msg=' . urlencode($mensaje));
