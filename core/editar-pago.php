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
    $id_pago = trim($_POST['id'] ?? ''); // Corregido: ahora busca 'id' en lugar de 'id_pago'
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
    if (empty($id_pago) || !is_numeric($id_pago)) {
        jsonError('ID de pago inválido');
    }
    
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
    
    // Verificar que el pago existe
    $sql_check = "SELECT id_pago FROM sys_pagos WHERE id_pago = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([$id_pago]);
    $existing = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$existing) {
        jsonError('El pago no existe');
    }
    
    // Verificar duplicados (excluyendo el registro actual)
    $sql_dup = "SELECT COUNT(*) as total FROM sys_pagos WHERE empresa = ? AND cuenta_contrato = ? AND id_pago != ? AND status != 'cancelado'";
    $stmt_dup = $conn->prepare($sql_dup);
    $stmt_dup->execute([$empresa, $cuenta_contrato, $id_pago]);
    $duplicate = $stmt_dup->fetch(PDO::FETCH_ASSOC);
    
    if ($duplicate['total'] > 0) {
        jsonError('Ya existe otro pago activo para esta empresa y cuenta/contrato');
    }
    
    // Preparar datos para actualizar
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
        'fecha_actualizacion' => date('Y-m-d H:i:s')
    ];
    
    // Actualizar en la base de datos
    $crud->db_table = 'sys_pagos';
    $crud->id_key = 'id_pago'; // Especificar la clave primaria
    $crud->data = $data;
    $crud->id_param = $id_pago; // Usar id_param en lugar de where
    
    if ($crud->update()) {
        echo json_encode([
            'success' => true,
            'message' => 'Pago actualizado correctamente'
        ]);
    } else {
        jsonError('Error al actualizar el pago en la base de datos');
    }
    
} catch (Exception $e) {
    jsonError('Error interno del servidor: ' . $e->getMessage());
}
?>