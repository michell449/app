<?php
// Controlador para eliminar las columnas comision_a, comision_b y socio de la tabla sys_clientes
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    die('Error de conexión a la base de datos.');
}

try {
    $sql = "ALTER TABLE sys_clientes 
        DROP COLUMN comision_a,
        DROP COLUMN comision_b,
        DROP COLUMN socio";
    $conn->exec($sql);
    echo 'Columnas eliminadas correctamente.';
} catch (PDOException $e) {
    echo 'Error al eliminar columnas: ' . $e->getMessage();
}
?>