<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$stmt = $db->query('SELECT id_institucion, nombre FROM sys_instituciones ORDER BY nombre ASC');
$instituciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'instituciones' => $instituciones]);
