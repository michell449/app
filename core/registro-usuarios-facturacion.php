<?php
// core/registro-usuarios-facturacion.php
// registro de usuarios para facturación electrónica
require_once __DIR__ . '/autoload-phpcfdi.php';
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/mail/class.phpmailer.php';
require_once __DIR__ . '/mail/class.smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? trim($data['email']) : '';
$password = isset($data['password']) ? $data['password'] : '';
$confirmPassword = isset($data['confirmPassword']) ? $data['confirmPassword'] : '';

if (empty($email) || empty($password) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
    exit;
}

// lógica para registrar al usuario en la base de datos
$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios_facturacion WHERE correo_electronico = ?");
$stmt->execute([$email]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está registrado.']);
    exit;
}

//generar token de verificacion
$token_verificacion = str_pad(strval(random_int(0, 999999)), 6, '0', STR_PAD_LEFT); 
$tipo_usuario = 'registrado';
$verificacion = 0;

// Si el correo no está registrado, proceder con el registro
$stmt = $conn->prepare("INSERT INTO usuarios_facturacion (correo_electronico, contrasena, tipo_usuario, verificacion, token_verificacion) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    $tipo_usuario,
    $verificacion,
    $token_verificacion
]);


// Enviar correo con token de verificación
$mail = new PHPMailer(true);
try {

    $mail->IsSMTP();
    $mail->isHTML(true);
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = MAIL_AUT;
    $mail->SMTPSecure = MAIL_SEC;
    $mail->Host = MAIL_HOST;
    $mail->Port = MAIL_PORT;
    $mail->Username = MAIL_USER;
    $mail->Password = MAIL_PSWD;
    $mail->SetFrom(MAIL_USER, 'No Reply - Mtto Pro Lab.');
    $mail->AddReplyTo(MAIL_USER, "No Reply - Mtto Pro Lab.");
    $mail->Subject = 'Verificación de correo electrónico';
    $mailContent = '<h2>Verifica tu correo electrónico</h2>' .
        '<p>Gracias por registrarte. Para completar tu registro, ingresa el siguiente código de verificación en el portal:</p>' .
        '<div style="font-size:1.5rem; font-weight:bold; color:#007bff; margin:1rem 0;">' . $token_verificacion . '</div>' .
        '<p>Si no solicitaste este registro, ignora este mensaje.</p>';
    $mail->Body = $mailContent;
    $mail->AltBody = strip_tags($mailContent);
    $mail->CharSet = "utf-8";
    $mail->addAddress($email);

    $mail->send();
} catch (Exception $e) {
    error_log("Error al enviar el correo de verificación: " . $mail->ErrorInfo);
}

// Respuesta final para el frontend
echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente. Revisa tu correo para el código de verificación.']);
exit;