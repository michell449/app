<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$stmt = $db->query('SELECT id_proyecto, nombre FROM proy_proyectos ORDER BY nombre ASC');
$proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'proyectos' => $proyectos]);
