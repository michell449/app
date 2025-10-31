<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `datos_fiscales_usuario` (
    `id_datos_fiscales_usuario` INT(11) NOT NULL AUTO_INCREMENT,
    `id_usuario` INT(11) NOT NULL,
    `rfc` VARCHAR(13) NOT NULL COLLATE 'utf8mb4_general_ci',
    `razon_social` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `regimen_fiscal` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `direccion` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    `codigo_postal` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    `correo_facturacion` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `constancia_situacion_fiscal` VARCHAR(255) NULL COLLATE 'utf8mb4_general_ci',
    PRIMARY KEY (`id_datos_fiscales_usuario`) USING BTREE,
    INDEX `fk_datos_fiscales_usuario_idx` (`id_usuario` ASC) VISIBLE,
    CONSTRAINT `fk_datos_fiscales_usuario`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `usuarios_facturacion` (`id_usuario`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    INDEX `fk_datos_fiscales_usuario_usuario_idx` (`codigo_postal` ASC) VISIBLE
    CONSTRAINT `fk_datos_fiscales_usuario_usuario`
    FOREIGN KEY (`codigo_postal`)
    REFERENCES `cat_codigo_postal` (`codigo_postal`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";

try {
    $conn->exec($sql);
    echo "Tabla 'datos_fiscales_usuario' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
$conn = null;
