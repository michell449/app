<?php
include_once '../config.php';
include_once 'class/class_users.php';

$token = isset($_POST['token']) ? $_POST['token'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$url = HOMEURL . '/reset-password?key=' . urlencode($token);
$msg = '';

if ($password !== $confirm) {
    $msg = 'Las contraseñas no coinciden.';
    $alert = 'danger';
    header('Location: ' . $url . '&msg=' . urlencode($msg) . '&alert=' . $alert);
    exit;
}
if (strlen($password) < 8) {
    $msg = 'La contraseña es muy corta. Debe tener al menos 8 caracteres.';
    $alert = 'danger';
    header('Location: ' . $url . '&msg=' . urlencode($msg) . '&alert=' . $alert);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$item = new users($db);

// Buscar email por token
$sql = "SELECT email FROM us_usuarios WHERE token_recuperacion = :token LIMIT 1";
$query = $db->prepare($sql);
$query->bindParam(':token', $token);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    $msg = 'Token inválido o expirado.';
    $alert = 'danger';
    header('Location: ' . $url . '&msg=' . urlencode($msg) . '&alert=' . $alert);
    exit;
}
$email = $row['email'];

$item->data = [
    'email' => $email,
    'password' => $password // texto plano
];

if ($item->isValidPassword($password) && $item->isValidToken($token, $email)) {
    if ($item->changepassword()) {
        $msg = 'Contraseña cambiada correctamente. Ya puedes iniciar sesión.';
        $alert = 'success';
    } else {
        $msg = $item->error;
        $alert = 'danger';
    }
} else {
    $msg = $item->error;
    $alert = 'danger';
}
header('Location: ' . $url . '&msg=' . urlencode($msg) . '&alert=' . $alert);