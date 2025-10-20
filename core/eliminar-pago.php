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
    
    // Obtener ID del pago
    $id_pago = trim($_POST['id_pago'] ?? trim($_POST['id'] ?? '')); // Buscar en ambos campos para compatibilidad
    
    if (empty($id_pago) || !is_numeric($id_pago)) {
        jsonError('ID de pago inválido');
    }
    
    // Conectar a la base de datos
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    
    // Verificar que el pago existe
    $sql_check = "SELECT id_pago, empresa, cuenta_contrato FROM sys_pagos WHERE id_pago = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([$id_pago]);
    $pago = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$pago) {
        jsonError('El pago no existe');
    }
    
    // Eliminar el pago
    $crud->db_table = 'sys_pagos';
    $crud->id_key = 'id_pago'; // Especificar la clave primaria
    $crud->id_param = $id_pago; // Usar id_param en lugar de where
    
    if ($crud->delete()) {
        echo json_encode([
            'success' => true,
            'message' => 'Pago eliminado correctamente'
        ]);
    } else {
        jsonError('Error al eliminar el pago de la base de datos');
    }
    
} catch (Exception $e) {
    jsonError('Error interno del servidor: ' . $e->getMessage());
}
?>