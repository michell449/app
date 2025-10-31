<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `empresas_facturacion` (
    `id_empresa` INT(11) NOT NULL AUTO_INCREMENT,
    `id_admin` INT(11) NOT NULL,
    `razon_social` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `rfc` VARCHAR(13) NOT NULL COLLATE 'utf8mb4_general_ci',
    `regimen_fiscal` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
    `num_ext` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    `num_int` VARCHAR(10) NULL COLLATE 'utf8mb4_general_ci',
    `direccion` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    `codigo_postal` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    `correo_electronico` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `estatus` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_empresa`) USING BTREE,
    INDEX `fk_empresas_facturacion_idx` (`id_admin` ASC) VISIBLE,
    CONSTRAINT `fk_empresas_facturacion`
    FOREIGN KEY (`id_admin`)
    REFERENCES `administrador_facturacion` (`id_admin`) ON DELETE CASCADE,
    ON UPDATE CASCADE,
    INDEX `fk_empresas_facturacion_codigo_postal_idx` (`codigo_postal` ASC) VISIBLE,
    CONSTRAINT `fk_empresas_facturacion_codigo_postal`
    FOREIGN KEY (`codigo_postal`)
    REFERENCES `cat_codigo_postal` (`codigo_postal`) ON DELETE CASCADE
    ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'empresas_facturacion' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
