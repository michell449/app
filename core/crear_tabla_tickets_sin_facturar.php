<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `tickets_sin_facturar` (
    `id_ticket` INT(11) NOT NULL AUTO_INCREMENT,
    `id_empresa` INT(11) NOT NULL,
    `id_sucursal` INT(11) NOT NULL,
    `folio_ticket` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
    `fecha_venta` DATETIME NOT NULL,
    `importe_total` DECIMAL(10,2) NOT NULL,
    `estatus` TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id_ticket`) USING BTREE,
    INDEX `fk_tickets_sin_facturar_empresa_idx` (`id_empresa` ASC) VISIBLE,
    INDEX `fk_tickets_sin_facturar_sucursal_idx` (`id_sucursal` ASC) VISIBLE,
    CONSTRAINT `fk_tickets_sin_facturar_empresa`
    FOREIGN KEY (`id_empresa`)
    REFERENCES `empresas_facturacion` (`id_empresa`) ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT `fk_tickets_sin_facturar_sucursal`
    FOREIGN KEY (`id_sucursal`)
    REFERENCES `sucursales` (`id_sucursal`) ON DELETE CASCADE
    ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'tickets_sin_facturar' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}