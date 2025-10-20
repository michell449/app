<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$sql = "SELECT a.id_archivo, a.id_proyecto, a.id_tarea, a.nombre, a.descripcion, a.tamaño, a.tipo_mime, a.ruta_archivo, a.id_categoria, a.id_institucion, a.id_colab, a.compartido, a.descargable, a.password, p.nombre AS nombre_proyecto, c.nombre AS nombre_categoria, i.nombre AS nombre_institucion FROM arch_archivos a LEFT JOIN proy_proyectos p ON a.id_proyecto = p.id_proyecto LEFT JOIN arch_categorias c ON a.id_categoria = c.id_categoria LEFT JOIN sys_instituciones i ON a.id_institucion = i.id_institucion WHERE a.status = 'activo' ORDER BY a.id_archivo DESC";
$stmt = $db->prepare($sql);
$stmt->execute();
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'archivos' => $archivos]);