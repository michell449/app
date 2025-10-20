<?php
// core/update-equipo.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

if (!empty($_POST['id']) && !empty($_POST['nombre'])) {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? '';
    $privacidad = $_POST['privacidad'] ?? 'Público';
    $stmt = $conn->prepare("UPDATE proy_equipos SET nombre = ?, descripcion = ?, privacidad = ? WHERE id_equipo = ?");
    if ($stmt->execute([$nombre, $descripcion, $privacidad, $id])) {
        echo 'ok';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
