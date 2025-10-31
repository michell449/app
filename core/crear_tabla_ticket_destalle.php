<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `ticket_detalle` (
    `id_detalle` INT(11) NOT NULL AUTO_INCREMENT,
    `id_ticket` INT(11) NOT NULL,
    `descripcion` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `cantidad` INT(11) NOT NULL,
    `precio_unitario` DECIMAL(10,2) NOT NULL,
    `importe_total` DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (`id_detalle`) USING BTREE,
    INDEX `fk_ticket_detalle_ticket_idx` (`id_ticket` ASC) VISIBLE,
    CONSTRAINT `fk_ticket_detalle_ticket`
    FOREIGN KEY (`id_ticket`)
    REFERENCES `tickets_sin_facturar` (`id_ticket`) ON DELETE CASCADE
    ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'ticket_detalle' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}