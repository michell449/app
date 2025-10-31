<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();
$sql = "CREATE TABLE IF NOT EXISTS `ticket_metodo_pago` (
    `id_metodo_pago` INT(11) NOT NULL AUTO_INCREMENT,
    `id_ticket` INT(11) NOT NULL,
    `metodo_pago` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `forma_pago` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `monto` DECIMAL(10,2) NOT NULL,
    `ref_pago` VARCHAR(100) NULL COLLATE 'utf8mb4_general_ci',
    PRIMARY KEY (`id_metodo_pago`) USING BTREE,
    INDEX `fk_ticket_metodo_pago_ticket_idx` (`id_ticket` ASC) VISIBLE,
    CONSTRAINT `fk_ticket_metodo_pago_ticket`
    FOREIGN KEY (`id_ticket`)
    REFERENCES `tickets_sin_facturar` (`id_ticket`) ON DELETE CASCADE
    ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'ticket_metodo_pago' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}