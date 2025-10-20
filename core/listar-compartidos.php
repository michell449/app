<?php
// core/listar-compartidos.php
// Devuelve todos los archivos y carpetas compartidos con el usuario actual
require_once __DIR__ . '/../config.php';

// Eliminar cualquier salida antes del JSON (evita Notice de session_start y HTML)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Limpiar buffer de salida para evitar HTML/errores antes del JSON
if (ob_get_length()) ob_clean();
header('Content-Type: application/json');

$usuario_id = $_SESSION['USR_ID'] ?? null;
if (!$usuario_id) {
    echo json_encode(['success' => false, 'msg' => 'No autenticado']);
    exit;
}

try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Traer todos los recursos compartidos con el usuario (archivos y carpetas)
    $sql = "SELECT ad.*, pa.ver, pa.descargar, pa.actualizar, pa.borrar
            FROM archivos_directorios ad
            INNER JOIN permisos_archivos pa ON ad.id = pa.idarchivo
            WHERE pa.idusuario = :usuario_id
            ORDER BY ad.tipo DESC, ad.nombre ASC";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    $recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'recursos' => $recursos]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
    exit;
}
