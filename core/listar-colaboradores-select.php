<?php
// core/listar-colaboradores-select.php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
$db = new Database();
$conn = $db->getConnection();
$crud = new Crud($conn);
$crud->db_table = 'sys_colaboradores';
$crud->read();
if (is_array($crud->data)) {
  foreach ($crud->data as $colab) {
    echo '<option value="' . htmlspecialchars($colab['id_colab']) . '">' . htmlspecialchars($colab['nombre'] . ' ' . $colab['apellidos']) . '</option>';
  }
}
?>
