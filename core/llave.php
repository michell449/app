<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "ALTER TABLE `citas_citas` 
    ADD CONSTRAINT `citas_citas_ibfk_2` FOREIGN KEY (`id_contacto`) REFERENCES `sys_contactos` (`id_contacto`) ON UPDATE RESTRICT ON DELETE RESTRICT;";

try {
    $conn->exec($sql);
    echo "Llave foránea 'id_contacto' agregada correctamente.";
} catch (PDOException $e) {
    echo "Error al agregar la llave foránea: " . $e->getMessage();
}
$conn = null;
