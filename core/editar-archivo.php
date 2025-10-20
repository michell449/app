<?php
require_once '../config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();

// Acepta tanto 'id' como 'id_recurso' para compatibilidad con el frontend
$id = isset($_POST['id']) ? trim($_POST['id']) : (isset($_POST['id_recurso']) ? trim($_POST['id_recurso']) : '');
$nuevoNombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
if ($id === '' || $nuevoNombre === '') {
    echo json_encode(['success' => false, 'msg' => 'Faltan datos']);
    exit;
}
try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Buscar el recurso
    $stmt = $db->prepare('SELECT * FROM archivos_directorios WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['success' => false, 'msg' => 'No encontrado']);
        exit;
    }
    $tipo = $row['tipo'];
    $nombreAntiguo = $row['nombre'];
    // Actualizar en la BD
    $stmtUp = $db->prepare('UPDATE archivos_directorios SET nombre = ? WHERE id = ?');
    $stmtUp->execute([$nuevoNombre, $id]);
    // Renombrar en el gestor de archivos
    if ($tipo === 'A') {
        // Construir la ruta completa considerando carpetas padre
        $ruta = [];
        $idpadre = $row['idpadre'];
        while ($idpadre) {
            $stmtPadre = $db->prepare('SELECT id, nombre, idpadre, tipo FROM archivos_directorios WHERE id = ?');
            $stmtPadre->execute([$idpadre]);
            $padre = $stmtPadre->fetch(PDO::FETCH_ASSOC);
            if ($padre && $padre['tipo'] === 'D') {
                array_unshift($ruta, $padre['nombre']);
                $idpadre = $padre['idpadre'];
            } else {
                break;
            }
        }
        $rutaBase = __DIR__ . '/../uploads/archivos';
        $rutaCarpeta = $rutaBase . (count($ruta) ? '/' . implode('/', $ruta) : '');
        $rutaAntigua = $rutaCarpeta . '/' . $id . '_' . $nombreAntiguo;
        $rutaNueva = $rutaCarpeta . '/' . $id . '_' . $nuevoNombre;
        if (file_exists($rutaAntigua)) {
            rename($rutaAntigua, $rutaNueva);
        }
    } else if ($tipo === 'D') {
        // Carpeta: renombrar la carpeta física
        // Buscar la ruta completa
        $ruta = [$nombreAntiguo];
        $idpadre = $row['idpadre'];
        while ($idpadre) {
            $stmtPadre = $db->prepare('SELECT id, nombre, idpadre, tipo FROM archivos_directorios WHERE id = ?');
            $stmtPadre->execute([$idpadre]);
            $padre = $stmtPadre->fetch(PDO::FETCH_ASSOC);
            if ($padre && $padre['tipo'] === 'D') {
                array_unshift($ruta, $padre['nombre']);
                $idpadre = $padre['idpadre'];
            } else {
                break;
            }
        }
        $rutaBase = __DIR__ . '/../uploads/archivos';
        $rutaAntigua = $rutaBase . '/' . implode('/', $ruta);
        $ruta[ count($ruta)-1 ] = $nuevoNombre;
        $rutaNueva = $rutaBase . '/' . implode('/', $ruta);
        if (is_dir($rutaAntigua)) {
            rename($rutaAntigua, $rutaNueva);
        }
    }
    echo json_encode(['success' => true]);
    exit;
} catch (Exception $ex) {
    echo json_encode(['success' => false, 'msg' => $ex->getMessage()]);
    exit;
}
