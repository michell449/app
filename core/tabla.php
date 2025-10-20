<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Crear tabla comisionistas
    $sqlComisionistas = "CREATE TABLE IF NOT EXISTS comisionistas (
        id_comision INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        tipo VARCHAR(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    $conn->exec($sqlComisionistas);

    // Crear tabla com_cliente
    $sqlComCliente = "CREATE TABLE IF NOT EXISTS com_cliente (
        id_comision INT(11) NOT NULL,
        id_cliente INT(11) NOT NULL,
        porcentaje FLOAT(5,2) NOT NULL DEFAULT 0.00,
        PRIMARY KEY (id_comision, id_cliente),
        CONSTRAINT fk_com_cliente_comisionist FOREIGN KEY (id_comision) REFERENCES comisionistas(id_comision) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT fk_com_cliente_cliente FOREIGN KEY (id_cliente) REFERENCES sys_clientes(id_cliente) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    $conn->exec($sqlComCliente);

    echo json_encode(['success' => true, 'message' => 'Tablas creadas correctamente.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
