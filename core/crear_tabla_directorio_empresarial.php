<?php
// Script para crear la tabla directorio_empresarial
require_once __DIR__ . '/class/db.php';
$db = new Database();
$pdo = $db->getConnection();
if (!$pdo) {
    die('No se pudo conectar a la base de datos.');
}
$sql = "CREATE TABLE IF NOT EXISTS directorio_empresarial (
    id_directorio INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    empresa VARCHAR(150) NOT NULL,
    clasificacion VARCHAR(100) DEFAULT NULL,
    contacto VARCHAR(100) DEFAULT NULL,
    telefono VARCHAR(50) DEFAULT NULL,
    puesto VARCHAR(100) DEFAULT NULL,
    referencia VARCHAR(150) DEFAULT NULL,
    notas TEXT DEFAULT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES sys_clientes(id_cliente) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
try {
    $pdo->exec($sql);
    echo "Tabla directorio_empresarial creada correctamente.";
} catch (Exception $e) {
    echo "Error al crear la tabla: " . $e->getMessage();
}
