<?php
// core/drive-controller.php
if (!defined('DB_NAME')) {
    require_once __DIR__ . '/../config.php';
}
require_once __DIR__ . '/class/db.php';

// Inicializar variables por defecto
$recursos = [];
$breadcrumbs = [];

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar que tenemos sesión activa
if (!isset($_SESSION['USR_ID'])) {
    // Debug temporal
    echo '<div style="background:yellow;padding:10px;">DEBUG: No hay sesión USR_ID activa en drive-controller.php</div>';
    return; // Salir silenciosamente si no hay sesión
}

// Obtener idpadre de forma robusta
$idpadre = isset($_GET['idpadre']) && $_GET['idpadre'] !== '' ? $_GET['idpadre'] : null;
$usuario_id = $_SESSION['USR_ID'] ?? 0;

// Los datos de sesión están correctos
try {
    $where = is_null($idpadre)
        ? '((idpadre IS NULL) AND en_papelera=0 AND (id_propietario = :usuario_id OR id IN (SELECT idarchivo FROM permisos_archivos WHERE idusuario = :usuario_id)))'
        : '((idpadre = :idpadre) AND en_papelera=0 AND (id_propietario = :usuario_id OR id IN (SELECT idarchivo FROM permisos_archivos WHERE idusuario = :usuario_id)))';
    
    $database = new Database();
    $db = $database->getConnection();
    
    $sql = "SELECT * FROM archivos_directorios WHERE $where ORDER BY tipo DESC, nombre ASC";
    $stmt = $db->prepare($sql);
    if (!is_null($idpadre)) {
        $stmt->bindParam(':idpadre', $idpadre);
    }
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->execute();
    $recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Los recursos se están cargando correctamente (confirmado por debug)
    
} catch (Exception $e) {
    // En caso de error, mantener array vacío
    $recursos = [];
    error_log('Error en drive-controller DB: ' . $e->getMessage());
    // Debug temporal
    echo '<div style="background:red;color:white;padding:10px;">DEBUG ERROR: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
// --- AGREGAR PERMISOS PERSONALIZADOS PARA RECURSOS COMPARTIDOS ---
if (isset($db) && !empty($recursos)) {
    try {
        foreach ($recursos as &$recurso) {
            if ($recurso['id_propietario'] != $usuario_id) {
                // Buscar permisos personalizados para este usuario y recurso
                $stmtPerm = $db->prepare('SELECT ver, descargar, actualizar, borrar FROM permisos_archivos WHERE idarchivo = :idarchivo AND idusuario = :idusuario LIMIT 1');
                $stmtPerm->bindParam(':idarchivo', $recurso['id']);
                $stmtPerm->bindParam(':idusuario', $usuario_id);
                $stmtPerm->execute();
                $perm = $stmtPerm->fetch(PDO::FETCH_ASSOC);
                if ($perm) {
                    $recurso['ver'] = $perm['ver'];
                    $recurso['descargar'] = $perm['descargar'];
                    $recurso['actualizar'] = $perm['actualizar'];
                    $recurso['borrar'] = $perm['borrar'];
                }
            }
        }
    } catch (Exception $e) {
        error_log('Error en permisos: ' . $e->getMessage());
    }
}

// Breadcrumbs
$breadcrumbs = [];
if (!is_null($idpadre) && isset($db)) {
    try {
        $id_actual = $idpadre;
        while ($id_actual) {
            $stmtB = $db->prepare("SELECT id, nombre, idpadre FROM archivos_directorios WHERE id = :id AND tipo = 'D'");
            $stmtB->bindParam(':id', $id_actual);
            $stmtB->execute();
            $row = $stmtB->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                array_unshift($breadcrumbs, $row);
                $id_actual = $row['idpadre'];
            } else {
                break;
            }
        }
    } catch (Exception $e) {
        error_log('Error en breadcrumbs: ' . $e->getMessage());
        $breadcrumbs = [];
    }
}
