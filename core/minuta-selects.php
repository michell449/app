<?php
ini_set('display_errors', 0);
error_reporting(0);
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
header('Content-Type: application/json');

$db = (new Database())->getConnection();
$crud = new Crud($db);

// Clientes
$crud->db_table = 'sys_clientes';
$crud->id_key = null;
$crud->id_param = null;
$crud->read();
$clientes = $crud->data;

// Colaboradores
$crud->db_table = 'sys_colaboradores';
$crud->id_key = null;
$crud->id_param = null;
$crud->read();
$colaboradores = $crud->data;

// Contactos
$crud->db_table = 'sys_contactos';
$crud->id_key = null;
$crud->id_param = null;
$crud->read();
$contactos = $crud->data;

echo json_encode([
  'clientes' => $clientes,
  'colaboradores' => $colaboradores,
  'contactos' => $contactos
]);
?>
