<?php
// core/delete-equipo.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

if (!empty($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM proy_equipos WHERE id_equipo = ?");
    if ($stmt->execute([$id])) {
        echo 'ok';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
