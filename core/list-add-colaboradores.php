<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$stmt = $db->query('SELECT id_colab, nombre, apellidos FROM sys_colaboradores ORDER BY nombre ASC, apellidos ASC');
$colaboradores = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'colaboradores' => $colaboradores]);
