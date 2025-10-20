<?php
require_once __DIR__ . '/../config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_archivo = $_POST['id_archivo'] ?? null;
    $usuarios = $_POST['usuarios'] ?? [];
    $permiso_ver = isset($_POST['permiso_ver']) ? 1 : 0;
    $permiso_descargar = isset($_POST['permiso_descargar']) ? 1 : 0;
    $permiso_actualizar = isset($_POST['permiso_actualizar']) ? 1 : 0;
    $permiso_borrar = isset($_POST['permiso_borrar']) ? 1 : 0;
    $idpadre = $_POST['idpadre'] ?? '';
    if ($id_archivo && !empty($usuarios)) {
        try {
            $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // --- RECURSIVE PERMISSION PROPAGATION ---
            // 1. Get all descendant IDs if this is a folder
            $ids_to_share = [$id_archivo];
            $stmtTipo = $db->prepare("SELECT tipo FROM archivos_directorios WHERE id = ? LIMIT 1");
            $stmtTipo->execute([$id_archivo]);
            $rowTipo = $stmtTipo->fetch(PDO::FETCH_ASSOC);
            if ($rowTipo && $rowTipo['tipo'] === 'D') {
                // Recursive function to get all descendant IDs
                function getDescendants($db, $parent_id) {
                    $ids = [];
                    $stmt = $db->prepare("SELECT id, tipo FROM archivos_directorios WHERE idpadre = ?");
                    $stmt->execute([$parent_id]);
                    $children = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($children as $child) {
                        $ids[] = $child['id'];
                        if ($child['tipo'] === 'D') {
                            $ids = array_merge($ids, getDescendants($db, $child['id']));
                        }
                    }
                    return $ids;
                }
                $ids_to_share = array_merge($ids_to_share, getDescendants($db, $id_archivo));
            }
            // 2. Remove previous permissions for all these IDs and users
            $in = str_repeat('?,', count($usuarios) - 1) . '?';
            foreach ($ids_to_share as $id_to_share) {
                $sqlDel = "DELETE FROM permisos_archivos WHERE idarchivo = ? AND idusuario IN ($in)";
                $params = array_merge([$id_to_share], $usuarios);
                $stmtDel = $db->prepare($sqlDel);
                $stmtDel->execute($params);
                // 3. Insert new permissions for all these IDs and users
                $sqlIns = "INSERT INTO permisos_archivos (idarchivo, idusuario, ver, descargar, actualizar, borrar) VALUES (:idarchivo, :idusuario, :ver, :descargar, :actualizar, :borrar)";
                $stmtIns = $db->prepare($sqlIns);
                foreach ($usuarios as $idusuario) {
                    $stmtIns->execute([
                        ':idarchivo' => $id_to_share,
                        ':idusuario' => $idusuario,
                        ':ver' => $permiso_ver,
                        ':descargar' => $permiso_descargar,
                        ':actualizar' => $permiso_actualizar,
                        ':borrar' => $permiso_borrar
                    ]);
                }
            }
            // Redirigir con éxito
            $redir = '../panel.php?pg=archivos-directorios&msg=compartido';
            if ($idpadre) {
                $redir .= '&idpadre=' . urlencode($idpadre);
            }
            header('Location: ' . $redir);
            exit;
        } catch (PDOException $e) {
            echo '<div style="color:red; padding:1em;">Error al compartir archivo: ' . htmlspecialchars($e->getMessage()) . '</div>';
            exit;
        }
    } else {
        echo '<div style="color:red; padding:1em;">Debes seleccionar al menos un usuario para compartir.</div>';
        exit;
    }
} else {
    header('Location: ../panel.php?pg=archivos-directorios');
    exit;
}
