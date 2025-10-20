<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$id_contacto = isset($_GET['id_contacto']) ? intval($_GET['id_contacto']) : null;
$extraEmpresa = null;
if ($id_contacto) {
  $sqlExtra = "SELECT c.cliente_empresa, cl.nombre_comercial FROM sys_contactos c LEFT JOIN sys_clientes cl ON c.cliente_empresa = cl.id_cliente WHERE c.id_contacto = ? LIMIT 1";
  $stmtExtra = $conn->prepare($sqlExtra);
  $stmtExtra->execute([$id_contacto]);
  $extraEmpresa = $stmtExtra->fetch(PDO::FETCH_ASSOC);
}

$sqlClientes = "SELECT id_cliente, nombre_comercial FROM sys_clientes WHERE activo = 1 ORDER BY nombre_comercial ASC";
$stmt = $conn->prepare($sqlClientes);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($extraEmpresa && $extraEmpresa['cliente_empresa']) {
  $alreadyExists = false;
  foreach ($clientes as $cli) {
    if ($cli['id_cliente'] == $extraEmpresa['cliente_empresa']) {
      $alreadyExists = true;
      break;
    }
  }
  if (!$alreadyExists) {
    echo '<option value="' . htmlspecialchars($extraEmpresa['cliente_empresa']) . '">' . htmlspecialchars($extraEmpresa['nombre_comercial']) . ' (inactiva)</option>';
  }
}

if ($clientes) {
  foreach ($clientes as $cli) {
    echo '<option value="' . htmlspecialchars($cli['id_cliente']) . '">' . htmlspecialchars($cli['nombre_comercial']) . '</option>';
  }
}
?>