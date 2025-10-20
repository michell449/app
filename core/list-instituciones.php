<?php
// core/list-instituciones.php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$sql = "SELECT id_institucion, nombre, descripcion, tipo, direccion, telefono, correo, ubicación_url, web FROM sys_instituciones ORDER BY id_institucion ASC";
$stmt = $db->prepare($sql);
$stmt->execute();
$instituciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'instituciones' => $instituciones]);
