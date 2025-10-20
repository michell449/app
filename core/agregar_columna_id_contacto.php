<?php
require_once dirname(__DIR__,2) . '/core/class/db.php';

// Crear/alterar la tabla citas_citas para agregar la columna id_contacto
try {
    $db = new Database();
    $conn = $db->getConnection();
    $sql = "ALTER TABLE citas_citas 
        ADD COLUMN id_contacto INT(11) NULL AFTER enviar_correo,
        ADD COLUMN asistira TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0=No confirmado, 1=Sí asistirá, 2=No asistirá' AFTER id_contacto;";
    $conn->exec($sql);
    echo "Columnas id_contacto y asistira agregadas correctamente.";
} catch (PDOException $e) {
    echo "Error al agregar la columna: " . $e->getMessage();
}
