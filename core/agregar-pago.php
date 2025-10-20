<?php
header('Content-Type: application/json');
require_once 'class/db.php';
require_once 'class/crud.php';

function jsonError($msg, $extra = []) {
    echo json_encode(array_merge(['success' => false, 'message' => $msg], $extra));
    exit;
}

try {
    // Verificar método POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonError('Método no permitido');
    }
    
    // Obtener datos del formulario
    $empresa = trim($_POST['empresa'] ?? '');
    $compania = trim($_POST['compania'] ?? '');
    $cuenta_contrato = trim($_POST['cuenta_contrato'] ?? '');
    $monto = trim($_POST['monto'] ?? '');
    $fecha_vencimiento = trim($_POST['fecha_vencimiento'] ?? '');
    $fecha_pago = trim($_POST['fecha_pago'] ?? '');
    $metodo_pago = trim($_POST['metodo_pago'] ?? '');
    $referencia = trim($_POST['referencia'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');
    $usuario_acceso = trim($_POST['usuario_acceso'] ?? '');
    $password_acceso = trim($_POST['password_acceso'] ?? '');
    
    // Validaciones básicas
    if (empty($empresa)) {
        jsonError('La empresa es obligatoria');
    }
    
    if (empty($compania)) {
        jsonError('La compañía es obligatoria');
    }
    
    if (empty($cuenta_contrato)) {
        jsonError('La cuenta/contrato es obligatoria');
    }
    
    if (empty($monto) || !is_numeric($monto)) {
        jsonError('El monto debe ser un número válido');
    }
    
    if (empty($fecha_vencimiento)) {
        jsonError('La fecha de vencimiento es obligatoria');
    }
    
    if (empty($status)) {
        jsonError('El status es obligatorio');
    }
    
    // Validar formato de fecha
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_vencimiento)) {
        jsonError('Formato de fecha de vencimiento inválido');
    }
    
    // Validar fecha de pago si se proporciona
    if (!empty($fecha_pago) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_pago)) {
        jsonError('Formato de fecha de pago inválido');
    }
    
    // Validar monto
    if (floatval($monto) < 0) {
        jsonError('El monto no puede ser negativo');
    }
    
    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    
    // Verificar si ya existe un pago con la misma empresa y cuenta/contrato
    $sql_check = "SELECT COUNT(*) as total FROM sys_pagos WHERE empresa = ? AND cuenta_contrato = ? AND status != 'cancelado'";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([$empresa, $cuenta_contrato]);
    $existing = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if ($existing['total'] > 0) {
        jsonError('Ya existe un pago activo para esta empresa y cuenta/contrato');
    }
    
    // Preparar datos para insertar
    $data = [
        'empresa' => $empresa,
        'compania' => $compania,
        'cuenta_contrato' => $cuenta_contrato,
        'monto' => floatval($monto),
        'fecha_vencimiento' => $fecha_vencimiento,
        'fecha_pago' => !empty($fecha_pago) ? $fecha_pago : null,
        'metodo_pago' => $metodo_pago,
        'referencia' => $referencia,
        'status' => $status,
        'observaciones' => $observaciones,
        'usuario_acceso' => $usuario_acceso,
        'password_acceso' => $password_acceso,
        'fecha_creacion' => date('Y-m-d H:i:s'),
        'fecha_actualizacion' => date('Y-m-d H:i:s')
    ];
    
    // Insertar en la base de datos
    $crud->db_table = 'sys_pagos';
    $crud->data = $data;
    
    if ($crud->create()) {
        echo json_encode([
            'success' => true,
            'message' => 'Pago agregado correctamente',
            'id_pago' => $conn->lastInsertId()
        ]);
    } else {
        jsonError('Error al guardar el pago en la base de datos');
    }
    
} catch (Exception $e) {
    jsonError('Error interno del servidor: ' . $e->getMessage());
}
?>