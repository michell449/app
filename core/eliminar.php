<?php
require_once __DIR__ . '/class/db.php';

$db = new Database();
$conn = $db->getConnection();

// Eliminar registros donde la columna 'programar' no sea NULL
$sqlDelete = "DELETE FROM citas_citas WHERE programar IS NOT NULL";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->execute();
echo "<p>Registros con 'programar' eliminados de citas_citas.</p>";

