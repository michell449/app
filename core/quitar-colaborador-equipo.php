<?php
// core/quitar-colaborador-equipo.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$id_equipo = isset($_POST['id_equipo']) ? intval($_POST['id_equipo']) : 0;
$id_colab = isset($_POST['id_colab']) ? intval($_POST['id_colab']) : 0;

if ($id_equipo > 0 && $id_colab > 0) {
    $stmt = $conn->prepare("DELETE FROM proy_equiposcolab WHERE id_equipo = ? AND id_colab = ?");
    if ($stmt->execute([$id_equipo, $id_colab])) {
        echo 'ok';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
