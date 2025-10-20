<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$db = new Database();
$conn = $db->getConnection();

$crud_colab = new Crud($conn);
$crud_colab->db_table = 'sys_colaboradores';
$crud_colab->read();
$colaboradores = $crud_colab->data;
$supervisores = [];
if (is_array($colaboradores)) {
    foreach ($colaboradores as $c) {
        $supervisores[$c['id_colab']] = $c['nombre'] . ' ' . $c['apellidos'];
    }
}
foreach ($supervisores as $id => $nombre) {
    echo '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($nombre) . '</option>';
}
?>
