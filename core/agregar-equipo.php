<?php
// agregar-equipo.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $privacidad = $_POST['privacidad'] ?? 'Público';

    if ($nombre !== '') {
        $stmt = $conn->prepare("INSERT INTO proy_equipos (nombre, descripcion, privacidad) VALUES (?, ?, ?)");
        $ok = $stmt->execute([$nombre, $descripcion, $privacidad]);
        if ($ok) {
            echo 'ok';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
}
?>
