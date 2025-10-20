<?php
// Crea la tabla cf_cfdis y su FK a sys_clientes, siguiendo el estilo de 1.php
require_once __DIR__ . '/class/db.php';

header('Content-Type: text/html; charset=utf-8');

try {
    $db = (new Database())->getConnection();

    // Definición de tabla con los campos vistos en tu captura y usados por el sistema
    $createTable = "
        CREATE TABLE IF NOT EXISTS cf_cfdis (
            id_cfdi INT(11) NOT NULL AUTO_INCREMENT,
            uuid VARCHAR(100) NOT NULL,
            folio VARCHAR(50) DEFAULT NULL,
            fecha_emision DATETIME NOT NULL,
            importe DECIMAL(15,4) NOT NULL DEFAULT 0.0000,
            emisor VARCHAR(100) NOT NULL,
            tipo VARCHAR(100) NOT NULL,
            estado ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pendiente',
            archivo_xml VARCHAR(256) DEFAULT NULL,
            id_cliente INT(11) DEFAULT NULL,
            rfc VARCHAR(100) DEFAULT NULL,
            total FLOAT NOT NULL DEFAULT 0,
            PRIMARY KEY (id_cfdi),
            UNIQUE KEY uk_cfdis_uuid (uuid),
            KEY idx_cfdis_cliente (id_cliente),
            KEY idx_cfdis_fecha (fecha_emision)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    $db->exec($createTable);
    echo "✅ Tabla cf_cfdis creada (o ya existía).<br>\n";

    // Intentar crear la FK hacia sys_clientes
    $addFk = "
        ALTER TABLE cf_cfdis
        ADD CONSTRAINT fk_cfdis_cliente
        FOREIGN KEY (id_cliente) REFERENCES sys_clientes(id_cliente)
        ON DELETE SET NULL
        ON UPDATE CASCADE;
    ";

    try {
        $db->exec($addFk);
        echo "✅ FK fk_cfdis_cliente creada correctamente.<br>\n";
    } catch (PDOException $e) {
        // Si ya existe o falla por duplicado, informamos y seguimos
        if (strpos($e->getMessage(), 'Duplicate') !== false || strpos(strtolower($e->getMessage()), 'exists') !== false) {
            echo "ℹ️  La FK fk_cfdis_cliente ya existe.<br>\n";
        } else if (strpos(strtolower($e->getMessage()), 'errno: 150') !== false) {
            echo "❌ No se pudo crear la FK (revisa que sys_clientes exista y sea InnoDB). Detalle: " . $e->getMessage() . "<br>\n";
        } else {
            echo "❌ Error creando FK: " . $e->getMessage() . "<br>\n";
        }
    }

    echo "<br>🎉 Proceso terminado.";
} catch (Exception $e) {
    http_response_code(500);
    echo "❌ Error general: " . $e->getMessage();
}
?>
