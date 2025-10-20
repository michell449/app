<?php
// core/get-equipo.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

if (!empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM proy_equipos WHERE id_equipo = ? LIMIT 1");
    $stmt->execute([$id]);
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($equipo) {
        echo json_encode($equipo);
    } else {
        echo '{}';
    }
} else {
    echo '{}';
}
?>
