<?php
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$proyectos = $db->query('SELECT id_proyecto, nombre FROM proy_proyectos ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);
foreach ($proyectos as $proyecto) {
    echo '<option value="' . $proyecto['id_proyecto'] . '">' . htmlspecialchars($proyecto['nombre']) . '</option>';
}
