<?php
require_once __DIR__ . '/../core/class/db.php';
$db = (new Database())->getConnection();
$sql = "ALTER TABLE citas_citas ADD COLUMN duracion TIME DEFAULT NULL";
try {
    $db->exec($sql);
    echo "Columna 'duracion' agregada correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>