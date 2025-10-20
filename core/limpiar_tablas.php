<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Eliminar registros de sys_notificaciones
    $conn->exec("DELETE FROM sys_notificaciones");
    // Reiniciar autoincrement de sys_notificaciones
    $conn->exec("ALTER TABLE sys_notificaciones AUTO_INCREMENT = 1");

    // Eliminar registros de citas_citas
    $conn->exec("DELETE FROM citas_citas");
    // Reiniciar autoincrement de citas_citas
    $conn->exec("ALTER TABLE citas_citas AUTO_INCREMENT = 1");

    echo "Registros eliminados y autoincrement reiniciado correctamente.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>