<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS `sys_notificaciones` (
    `id_notificacion` INT(11) NOT NULL AUTO_INCREMENT,
    `id_colab` INT(11) NOT NULL,
    `id_cita` INT(11) NOT NULL,
    `mensaje` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `tipo` VARCHAR(50) NULL DEFAULT 'cita' COLLATE 'utf8mb4_general_ci',
    `fecha` DATETIME NULL DEFAULT current_timestamp(),
    `leido` TINYINT(1) NULL DEFAULT '0',
    PRIMARY KEY (`id_notificacion`) USING BTREE,
    INDEX `id_colab` (`id_colab`) USING BTREE,
    INDEX `id_cita` (`id_cita`) USING BTREE,
    CONSTRAINT `sys_notificaciones_ibfk_1` FOREIGN KEY (`id_colab`) REFERENCES `sys_colaboradores` (`id_colab`) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT `sys_notificaciones_ibfk_2` FOREIGN KEY (`id_cita`) REFERENCES `citas_citas` (`id_cita`) ON UPDATE RESTRICT ON DELETE RESTRICT
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";

try {
    $conn->exec($sql);
    echo "Tabla 'sys_notificaciones' creada correctamente.";
} catch (PDOException $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
$conn = null;
