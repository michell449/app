<?php
require_once __DIR__ . '/../core/class/db.php';
    $db = (new Database())->getConnection();
    $instituciones = $db->query('SELECT id_institucion, nombre FROM sys_instituciones ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($instituciones as $inst) {
        echo '<option value="' . htmlspecialchars($inst['id_institucion']) . '">' . htmlspecialchars($inst['nombre']) . '</option>';
    }