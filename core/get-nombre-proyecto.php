<?php
$proyecto_id = $_GET['id'] ?? 1;
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$stmt = $db->prepare('SELECT nombre FROM proy_proyectos WHERE id_proyecto = ? LIMIT 1');
$stmt->execute([$proyecto_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$nombre_proyecto = $row ? $row['nombre'] : $proyecto_id;
?>
