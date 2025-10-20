
<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Agregar índice al campo rfc
    $sqlIndex = "ALTER TABLE cf_cfdis ADD INDEX idx_rfc (rfc)";
    $conn->exec($sqlIndex);
    echo "Índice agregado al campo rfc.\n";
} catch (Exception $e) {
    echo "Error al agregar índice: " . $e->getMessage() . "\n";
}

try {
    // 2. Agregar campo comprobante
    $sqlComprobante = "ALTER TABLE cf_cfdis ADD COLUMN comprobante VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL AFTER total";
    $conn->exec($sqlComprobante);
    echo "Campo comprobante agregado.\n";
} catch (Exception $e) {
    echo "Error al agregar comprobante: " . $e->getMessage() . "\n";
}

try {
    // 3. Agregar campo efos_alert
    $sqlEfosAlert = "ALTER TABLE cf_cfdis ADD COLUMN efos_alert TINYINT(1) NOT NULL DEFAULT 0 AFTER comprobante";
    $conn->exec($sqlEfosAlert);
    echo "Campo efos_alert agregado.\n";
} catch (Exception $e) {
    echo "Error al agregar efos_alert: " . $e->getMessage() . "\n";
}

try {
    // 4. Agregar campo efos_checked_at
    $sqlEfosCheckedAt = "ALTER TABLE cf_cfdis ADD COLUMN efos_checked_at DATETIME NULL AFTER efos_alert";
    $conn->exec($sqlEfosCheckedAt);
    echo "Campo efos_checked_at agregado.\n";
} catch (Exception $e) {
    echo "Error al agregar efos_checked_at: " . $e->getMessage() . "\n";
}

try {
    // 5. Modificar campo estado para agregar 'EFO' al enum
    $sqlEstado = "ALTER TABLE cf_cfdis MODIFY COLUMN estado ENUM('pendiente', 'pagado', 'cancelado', 'EFO') NOT NULL DEFAULT 'pendiente'";
    $conn->exec($sqlEstado);
    echo "Campo estado modificado para incluir 'EFO'.\n";
} catch (Exception $e) {
    echo "Error al modificar campo estado: " . $e->getMessage() . "\n";
}
?>
