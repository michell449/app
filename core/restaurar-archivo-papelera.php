<?php
// Controlador para restaurar archivos/carpetas desde la papelera
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
$db = (new Database())->getConnection();
$id_archivo = trim($_POST['id_archivo'] ?? '');
if ($id_archivo === '') {
    echo json_encode(['success' => false, 'msg' => 'Falta el ID']);
    exit;
}

// Función para restaurar toda la jerarquía de carpetas madre si alguna sigue en papelera
function restaurarJerarquiaPadres($db, $idpadre, $rutaBaseOrigen, $rutaBaseDestino) {
    $padres = [];
    $tmpId = $idpadre;
    while ($tmpId) {
        $stmt = $db->prepare("SELECT * FROM archivos_directorios WHERE id=?");
        $stmt->execute([$tmpId]);
        $padre = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($padre && $padre['tipo'] === 'D') {
            array_unshift($padres, $padre);
            $tmpId = $padre['idpadre'];
        } else {
            break;
        }
    }
    foreach ($padres as $padre) {
        if ($padre['en_papelera']) {
            restaurarDePapelera($db, $padre, $rutaBaseOrigen, $rutaBaseDestino);
        }
    }
}

$stmt = $db->prepare("SELECT * FROM archivos_directorios WHERE id=? LIMIT 1");
$stmt->execute([$id_archivo]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    echo json_encode(['success' => false, 'msg' => 'No encontrado']);
    exit;
}

function restaurarDePapelera($db, $recurso, $rutaBaseOrigen, $rutaBaseDestino) {
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
        $origen = $rutaBaseOrigen . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rutaRelativa);
        $destino = $rutaBaseDestino . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rutaRelativa);
        $dirDestino = dirname($destino);
        if (!is_dir($dirDestino)) {
            mkdir($dirDestino, 0777, true);
        }
        if (file_exists($origen)) {
            @rename($origen, $destino);
        }
        $db->prepare("UPDATE archivos_directorios SET en_papelera=0 WHERE id=?")->execute([$id]);
    } else if ($tipo === 'D') {
        $db->prepare("UPDATE archivos_directorios SET en_papelera=0 WHERE id=?")->execute([$id]);
        $stmtHijos = $db->prepare("SELECT * FROM archivos_directorios WHERE idpadre=?");
        $stmtHijos->execute([$id]);
        $hijos = $stmtHijos->fetchAll(PDO::FETCH_ASSOC);
        foreach ($hijos as $hijo) {
            restaurarDePapelera($db, $hijo, $rutaBaseOrigen, $rutaBaseDestino);
        }
        $rutaRelativa[] = $nombre;
        $origen = $rutaBaseOrigen . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rutaRelativa);
        $destino = $rutaBaseDestino . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $rutaRelativa);
        if (is_dir($origen)) {
            if (!is_dir(dirname($destino))) {
                mkdir(dirname($destino), 0777, true);
            }
            @rename($origen, $destino);
        }
        // Eliminar la carpeta vacía que quedó en la papelera
        if (is_dir($origen)) {
            @rmdir($origen);
        }
    }
}


$rutaBaseOrigen = realpath(__DIR__ . '/../uploads/papelera/archivos');
$rutaBaseDestino = realpath(__DIR__ . '/../uploads/archivos');
if ($rutaBaseDestino === false) {
    $rutaBaseDestino = __DIR__ . '/../uploads/archivos';
    if (!is_dir($rutaBaseDestino)) {
        mkdir($rutaBaseDestino, 0777, true);
    }
    $rutaBaseDestino = realpath($rutaBaseDestino);
}
// Restaurar jerarquía de padres si es necesario
if ($row['tipo'] === 'A' && $row['idpadre']) {
    restaurarJerarquiaPadres($db, $row['idpadre'], $rutaBaseOrigen, $rutaBaseDestino);
}
restaurarDePapelera($db, $row, $rutaBaseOrigen, $rutaBaseDestino);
echo json_encode(['success' => true]);
