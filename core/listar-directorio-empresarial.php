<?php
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

// Cambiar el backend para que si no se manda id_cliente, liste todos los registros

$where = [];
$params = [];
$where = [];
$params = [];
if (!empty($_GET['id'])) {
    $where[] = 'id_directorio = ?';
    $params[] = $_GET['id'];
}
if (!empty($_GET['id_cliente'])) {
    $where[] = 'id_cliente = ?';
    $params[] = $_GET['id_cliente'];
}
if (!empty($_GET['clasificacion'])) {
    $where[] = 'clasificacion = ?';
    $params[] = $_GET['clasificacion'];
}
if (!empty($_GET['busqueda'])) {
    $busq = '%' . $_GET['busqueda'] . '%';
    $where[] = '(
        empresa LIKE ? OR
        clasificacion LIKE ? OR
        contacto LIKE ? OR
        telefono LIKE ? OR
        puesto LIKE ? OR
        referencia LIKE ?
    )';
    array_push($params, $busq, $busq, $busq, $busq, $busq, $busq);
}
$sql = 'SELECT d.*, c.nombre_comercial FROM directorio_empresarial d LEFT JOIN sys_clientes c ON d.id_cliente = c.id_cliente';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY fecha_registro ASC';
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'data' => $registros]);
