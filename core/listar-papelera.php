<?php
// Listar archivos y carpetas en papelera (solo raíz o hijos de una carpeta en papelera)
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();

$idpadre = isset($_GET['idpadre']) ? trim($_GET['idpadre']) : '';
if ($idpadre === '') {
	// Mostrar solo elementos raíz: en_papelera=1 y (idpadre IS NULL o el padre no está en papelera)
	$sql = "SELECT a.id, a.nombre, a.tipo FROM archivos_directorios a
			LEFT JOIN archivos_directorios b ON a.idpadre = b.id
			WHERE a.en_papelera=1 AND (a.idpadre IS NULL OR b.en_papelera=0)
			ORDER BY a.tipo DESC, a.nombre ASC";
	$stmt = $db->query($sql);
	echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
	// Mostrar hijos directos de la carpeta en papelera
	$sql = "SELECT id, nombre, tipo FROM archivos_directorios WHERE en_papelera=1 AND idpadre=? ORDER BY tipo DESC, nombre ASC";
	$stmt = $db->prepare($sql);
	$stmt->execute([$idpadre]);
	echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
