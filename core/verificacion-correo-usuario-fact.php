<?php
// core/verificacion-correo-usuario-fact.php


require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json; charset=utf-8');

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
if (empty($token)) {
    echo json_encode(['success' => false, 'message' => 'No se recibió el código de verificación.']);
    exit;
}

$db = new Database();
$conn = $db->getConnection();
$stmt = $conn->prepare("SELECT correo_electronico, verificacion FROM usuarios_facturacion WHERE token_verificacion = ?");
$stmt->execute([$token]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $user['verificacion'] == 0) {
    // Marcar como verificado
    $stmtUpdate = $conn->prepare("UPDATE usuarios_facturacion SET verificacion = 1 WHERE token_verificacion = ?");
    $stmtUpdate->execute([$token]);
    echo json_encode(['success' => true, 'message' => '¡Verificación exitosa! Redirigiendo al panel de facturación...']);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'El código de verificación es incorrecto o ya fue usado.']);
    exit;
}
