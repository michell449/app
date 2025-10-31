<?php
// core/verificar-correo-facturacion.php
require_once __DIR__ . '/autoload-phpcfdi.php';
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json; charset=utf-8');
// Verificar el correo electrónico usando el token de verificación
$token = isset($_GET['token']) ? $_GET['token'] : '';
if (empty($token)) {    
    echo json_encode(['success' => false, 'message' => 'Token de verificación no proporcionado.']);
    exit;
}
$db = new Database();
$conn = $db->getConnection();   

$stmt = $conn->prepare("SELECT id_usuario FROM usuarios_facturacion WHERE token_verificacion = ?");
$stmt->execute([$token]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    $stmt = $conn->prepare("UPDATE usuarios_facturacion SET verificacion = 1, token_verificacion = NULL WHERE id_usuario = ?");
    $stmt->execute([$user['id_usuario']]);
    echo json_encode(['success' => true, 'message' => 'Correo electrónico verificado exitosamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Token de verificación inválido o expirado.']);
}
