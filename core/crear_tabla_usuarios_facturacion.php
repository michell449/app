<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `usuarios_facturacion` (
    `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
    `correo_electronico` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `contrasena` VARCHAR(45) NOT NULL COLLATE 'utf8mb4_general_ci',
    `tipo_usuario` ENUM('invitado', 'registrado') NOT NULL COLLATE 'utf8mb4_general_ci',
    `verificacion` TINYINT(1) NOT NULL DEFAULT 0,
    `token_verificacion` VARCHAR(100) NULL COLLATE 'utf8mb4_general_ci',
    `fecha_registro` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_usuario`) USING BTREE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";

try {
    $conn->exec($sql);
    echo "Tabla 'usuarios_facturacion' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
$conn = null;