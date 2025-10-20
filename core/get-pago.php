<?php
header('Content-Type: application/json');
require_once 'class/db.php';
require_once 'class/crud.php';

function jsonError($msg, $extra = []) {
    echo json_encode(array_merge(['success' => false, 'message' => $msg], $extra));
    exit;
}

try {
    // Verificar método GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        jsonError('Método no permitido');
    }
    
    // Obtener ID del pago
    $id_pago = trim($_GET['id'] ?? '');
    
    if (empty($id_pago) || !is_numeric($id_pago)) {
        jsonError('ID de pago inválido');
    }
    
    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    
    // Buscar el pago
    $sql = "SELECT 
                id_pago,
                empresa,
                compania,
                cuenta_contrato,
                monto,
                fecha_vencimiento,
                fecha_pago,
                metodo_pago,
                referencia,
                status,
                observaciones,
                usuario_acceso,
                password_acceso,
                fecha_creacion,
                fecha_actualizacion
            FROM sys_pagos 
            WHERE id_pago = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_pago]);
    $pago = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$pago) {
        jsonError('Pago no encontrado');
    }
    
    // Formatear datos para la respuesta
    $pago_formateado = [
        'id_pago' => $pago['id_pago'],
        'empresa' => $pago['empresa'],
        'compania' => $pago['compania'],
        'cuenta_contrato' => $pago['cuenta_contrato'],
        'monto' => $pago['monto'],
        'fecha_vencimiento' => $pago['fecha_vencimiento'],
        'fecha_pago' => $pago['fecha_pago'],
        'metodo_pago' => $pago['metodo_pago'],
        'referencia' => $pago['referencia'],
        'status' => $pago['status'],
        'observaciones' => $pago['observaciones'],
        'usuario_acceso' => $pago['usuario_acceso'],
        'password_acceso' => $pago['password_acceso'],
        'fecha_creacion' => $pago['fecha_creacion'],
        'fecha_actualizacion' => $pago['fecha_actualizacion']
    ];
    
    echo json_encode([
        'success' => true,
        'pago' => $pago_formateado
    ]);
    
} catch (Exception $e) {
    jsonError('Error interno del servidor: ' . $e->getMessage());
}
?>