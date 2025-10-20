<?php
// Script para agregar el campo 'en_papelera' a la tabla archivos_directorios
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();

// Verificar si el campo ya existe
$existe = false;
$stmt = $db->query("SHOW COLUMNS FROM archivos_directorios LIKE 'en_papelera'");
if ($stmt->fetch()) {
    $existe = true;
}

if ($existe) {
    echo "<b>El campo 'en_papelera' ya existe en la tabla archivos_directorios.</b>";
    exit;
}

// Ejecutar el ALTER TABLE
$sql = "ALTER TABLE archivos_directorios ADD COLUMN en_papelera TINYINT(1) NOT NULL DEFAULT 0 AFTER compartido";
try {
    $db->exec($sql);
    echo "<b>Campo 'en_papelera' agregado correctamente a la tabla archivos_directorios.</b>";
} catch (PDOException $e) {
    echo "<b>Error al agregar el campo: </b>" . $e->getMessage();
}
