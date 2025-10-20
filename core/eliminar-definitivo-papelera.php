<?php
// Controlador para eliminar definitivamente archivos/carpetas de la papelera
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$id_archivo = trim($_POST['id_archivo'] ?? '');
if ($id_archivo === '') {
    echo json_encode(['success' => false, 'msg' => 'Falta el ID']);
    exit;
}
$stmt = $db->prepare("SELECT * FROM archivos_directorios WHERE id=? LIMIT 1");
$stmt->execute([$id_archivo]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    echo json_encode(['success' => false, 'msg' => 'No encontrado']);
    exit;
}

function eliminarDefinitivo($db, $recurso, $rutaBase) {
    $id = $recurso['id'];
    $tipo = $recurso['tipo'];
    $nombre = $recurso['nombre'];
    $idpadre = $recurso['idpadre'];
    $rutaRelativa = [];
    $tmpIdPadre = $idpadre;
    while ($tmpIdPadre) {
        $stmt2 = $db->prepare("SELECT id, nombre, idpadre, tipo FROM archivos_directorios WHERE id=?");
        $stmt2->execute([$tmpIdPadre]);
        $padre = $stmt2->fetch(PDO::FETCH_ASSOC);
        if ($padre && $padre['tipo'] === 'D') {
            array_unshift($rutaRelativa, $padre['nombre']);
            $tmpIdPadre = $padre['idpadre'];
        } else {
            break;
        }
    }
    if ($tipo === 'A') {
        $nombreFisico = $id . '_' . $nombre;
        $rutaRelativa[] = $nombreFisico;
        $rutaFisica = $rutaBase . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rutaRelativa);
        if (file_exists($rutaFisica)) {
            @unlink($rutaFisica);
        }
        $db->prepare("DELETE FROM archivos_directorios WHERE id=?")->execute([$id]);
    } else if ($tipo === 'D') {
        // Eliminar hijos recursivamente
        $stmtHijos = $db->prepare("SELECT * FROM archivos_directorios WHERE idpadre=?");
        $stmtHijos->execute([$id]);
        $hijos = $stmtHijos->fetchAll(PDO::FETCH_ASSOC);
        foreach ($hijos as $hijo) {
            eliminarDefinitivo($db, $hijo, $rutaBase);
        }
        // Eliminar carpeta física
        $rutaRelativa[] = $nombre;
        $rutaFisica = $rutaBase . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rutaRelativa);
        if (is_dir($rutaFisica)) {
            function eliminarCarpetaFisica($dir) {
                $items = scandir($dir);
                foreach ($items as $item) {
                    if ($item === '.' || $item === '..') continue;
                    $path = $dir . DIRECTORY_SEPARATOR . $item;
                    if (is_dir($path)) {
                        eliminarCarpetaFisica($path);
                    } else {
                        @unlink($path);
                    }
                }
                @rmdir($dir);
            }
            eliminarCarpetaFisica($rutaFisica);
        }
        $db->prepare("DELETE FROM archivos_directorios WHERE id=?")->execute([$id]);
    }
}

$rutaBase = realpath(__DIR__ . '/../uploads/papelera/archivos');
eliminarDefinitivo($db, $row, $rutaBase);
echo json_encode(['success' => true]);
