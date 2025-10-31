<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `sucursales` (
    `id_sucursal` INT(11) NOT NULL AUTO_INCREMENT,
    `id_empresa` INT(11) NOT NULL,
    `nombre_sucursal` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `clave_sucursal` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
    `direccion` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    `codigo_postal` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    `num_ext` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    `num_int` VARCHAR(10) NULL COLLATE 'utf8mb4_general_ci',
    `telefono` VARCHAR(20) NULL COLLATE 'utf8mb4_general_ci',
    `estatus` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_sucursal`) USING BTREE,
    INDEX `fk_sucursales_idx` (`id_empresa` ASC) VISIBLE,
    CONSTRAINT `fk_sucursales`  
    FOREIGN KEY (`id_empresa`)
    REFERENCES `empresas_facturacion` (`id_empresa`) ON DELETE CASCADE
    ON UPDATE CASCADE,
    INDEX `fk_sucursales_codigo_postal_idx` (`codigo_postal` ASC) VISIBLE,
    CONSTRAINT `fk_sucursales_codigo_postal`
    FOREIGN KEY (`codigo_postal`)
    REFERENCES `cat_codigo_postal` (`codigo_postal`) ON DELETE CASCADE
    ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'sucursales' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}