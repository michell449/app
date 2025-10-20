<?php
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$categorias = $db->query('SELECT id_categoria, nombre, descripcion FROM arch_categorias ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);
foreach ($categorias as $cat) {
    echo '<option value="' . htmlspecialchars($cat['id_categoria']) . '">' . htmlspecialchars($cat['nombre']) . ' - ' . htmlspecialchars($cat['descripcion']) . '</option>';
}
