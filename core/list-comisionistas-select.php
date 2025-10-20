<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT id_comision, nombre, tipo FROM comisionistas ORDER BY nombre ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$comisionistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($comisionistas) {
    foreach ($comisionistas as $com) {
        $tipo = $com['tipo'] ? ' (' . htmlspecialchars($com['tipo']) . ')' : '';
        echo '<option value="' . htmlspecialchars($com['id_comision']) . '">' . htmlspecialchars($com['nombre']) . $tipo . '</option>';
    }
}
?>