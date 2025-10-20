<?php
// core/eliminar-columna-programar.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/class/db.php';

$database = new Database();
$db = $database->getConnection();

try {
    $sql = "ALTER TABLE citas_citas DROP COLUMN programar";
    $db->exec($sql);
    echo "Columna 'programar' eliminada correctamente.";
} catch (PDOException $e) {
    echo "Error al eliminar la columna: " . $e->getMessage();
}
?>
