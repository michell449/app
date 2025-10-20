<?php
header('Content-Type: application/json');
require_once 'class/db.php';
require_once 'class/crud.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);

    // Verificar si se está filtrando
    $filtro_empresa = isset($_GET['empresa']) ? trim($_GET['empresa']) : '';
    $filtro_status = isset($_GET['status']) ? trim($_GET['status']) : '';
    
    // Construir la consulta con filtros
    $sql = "SELECT 
                p.id_pago,
                p.empresa,
                p.compania,
                p.cuenta_contrato,
                p.monto,
                p.fecha_vencimiento,
                p.fecha_pago,
                p.metodo_pago,
                p.referencia,
                p.status,
                p.observaciones,
                p.usuario_acceso,
                p.password_acceso,
                p.fecha_creacion,
                p.fecha_actualizacion
            FROM sys_pagos p 
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($filtro_empresa)) {
        $sql .= " AND p.empresa LIKE ?";
        $params[] = '%' . $filtro_empresa . '%';
    }
    
    if (!empty($filtro_status)) {
        $sql .= " AND p.status = ?";
        $params[] = $filtro_status;
    }
    
    $sql .= " ORDER BY p.fecha_creacion DESC";
    
    // Ejecutar consulta con parámetros
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $result = $crud->customQuery($sql);
    }
    
    // Formatear datos para la respuesta
    $pagos = [];
    if ($result && count($result) > 0) {
        foreach ($result as $row) {
            $pagos[] = [
                'id_pago' => $row['id_pago'],
                'empresa' => $row['empresa'],
                'compania' => $row['compania'],
                'cuenta_contrato' => $row['cuenta_contrato'],
                'monto' => number_format($row['monto'], 2),
                'monto_raw' => $row['monto'],
                'fecha_vencimiento' => $row['fecha_vencimiento'],
                'fecha_pago' => $row['fecha_pago'],
                'metodo_pago' => $row['metodo_pago'],
                'referencia' => $row['referencia'],
                'status' => $row['status'],
                'observaciones' => $row['observaciones'],
                'usuario_acceso' => $row['usuario_acceso'],
                'password_acceso' => $row['password_acceso'],
                'fecha_creacion' => $row['fecha_creacion'],
                'fecha_actualizacion' => $row['fecha_actualizacion'],
                'fecha_vencimiento_formato' => date('d/m/Y', strtotime($row['fecha_vencimiento'])),
                'fecha_pago_formato' => $row['fecha_pago'] ? date('d/m/Y', strtotime($row['fecha_pago'])) : '',
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'pagos' => $pagos,
        'total' => count($pagos),
        'filtros' => [
            'empresa' => $filtro_empresa,
            'status' => $filtro_status
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al cargar pagos: ' . $e->getMessage(),
        'pagos' => [],
        'total' => 0
    ]);
}
?>