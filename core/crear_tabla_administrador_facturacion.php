<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `administrador_facturacion` (
    `id_admin` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `correo_electronico` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `telefono` VARCHAR(20) NULL COLLATE 'utf8mb4_general_ci',
    `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_admin`) USING BTREE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";
try {
    $conn->exec($sql);
    echo "Tabla 'administrador_facturacion' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}