<?php
/**
 * Debug: Verificar sesión del cliente
 */

session_start();

header('Content-Type: application/json');

echo json_encode([
    'session_data' => $_SESSION,
    'session_status' => session_status(),
    'session_id' => session_id(),
    'has_usr_type' => isset($_SESSION['USR_TYPE']),
    'usr_type_value' => $_SESSION['USR_TYPE'] ?? 'NO_SET',
    'has_cliente_id' => isset($_SESSION['CLIENTE_ID']),
    'cliente_id_value' => $_SESSION['CLIENTE_ID'] ?? 'NO_SET',
    'auth_cliente_check' => [
        'es_cliente' => class_exists('AuthCliente') ? 'Class exists' : 'Class missing',
    ]
], JSON_PRETTY_PRINT);
?>