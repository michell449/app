<?php
require_once 'class/db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Crear tabla sys_pagos
    $sql = "CREATE TABLE IF NOT EXISTS `sys_pagos` (
        `id_pago` int(11) NOT NULL AUTO_INCREMENT,
        `empresa` varchar(255) NOT NULL,
        `compania` varchar(255) NOT NULL,
        `cuenta_contrato` varchar(100) NOT NULL,
        `monto` decimal(10,2) NOT NULL,
        `fecha_vencimiento` date NOT NULL,
        `fecha_pago` date NULL,
        `metodo_pago` varchar(100) NULL,
        `referencia` varchar(255) NULL,
        `status` enum('pendiente','pagado','vencido','cancelado') NOT NULL DEFAULT 'pendiente',
        `observaciones` text NULL,
        `usuario_acceso` varchar(100) NULL,
        `password_acceso` varchar(100) NULL,
        `fecha_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `fecha_actualizacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id_pago`),
        INDEX `idx_empresa` (`empresa`),
        INDEX `idx_status` (`status`),
        INDEX `idx_fecha_vencimiento` (`fecha_vencimiento`),
        INDEX `idx_compania` (`compania`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->exec($sql);
    
    // Insertar datos de ejemplo
    $insert_sql = "INSERT INTO `sys_pagos` (`empresa`, `compania`, `cuenta_contrato`, `monto`, `fecha_vencimiento`, `fecha_pago`, `metodo_pago`, `referencia`, `status`, `observaciones`, `usuario_acceso`, `password_acceso`) VALUES
        ('PEMEX', 'Petróleos Mexicanos', 'CONT-2024-001', 150000.00, '2024-02-15', NULL, 'Transferencia', 'REF-001-2024', 'pendiente', 'Pago mensual de servicios', 'pemex_user', 'Pass123!'),
        ('CFE', 'Comisión Federal de Electricidad', 'CFE-SRV-2024-002', 250000.00, '2024-02-20', '2024-02-18', 'Cheque', 'CHQ-002-2024', 'pagado', 'Servicios de consultoría eléctrica', 'cfe_admin', 'Secure456'),
        ('IMSS', 'Instituto Mexicano del Seguro Social', 'IMSS-CONT-2024-003', 80000.00, '2024-01-30', NULL, 'Transferencia', 'IMSS-REF-003', 'vencido', 'Servicios médicos especializados', 'imss_portal', 'Health789'),
        ('TELMEX', 'Teléfonos de México', 'TEL-2024-004', 45000.00, '2024-02-25', NULL, 'Depósito', 'DEP-004-2024', 'pendiente', 'Servicios de telecomunicaciones', 'telmex_sys', 'Connect2024'),
        ('BANORTE', 'Grupo Financiero Banorte', 'BNT-FIN-2024-005', 120000.00, '2024-03-01', NULL, 'Transferencia', 'BNT-005-2024', 'pendiente', 'Consultoría financiera', 'banorte_fin', 'Money$2024')";
    
    $conn->exec($insert_sql);
    
    echo "✅ Tabla sys_pagos creada exitosamente con datos de ejemplo\n";
    echo "📊 Se insertaron 5 registros de ejemplo\n";
    echo "🔧 Estructura completa con índices optimizados\n";
    
} catch (PDOException $e) {
    echo "❌ Error al crear la tabla: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
}
?>