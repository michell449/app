<?php
require_once __DIR__ . '/../config.php';

// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadsBase = __DIR__ . '/../uploads/archivos';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = trim($_POST['nombre'] ?? '');
        $idpadre = isset($_POST['idpadre']) && $_POST['idpadre'] !== '' ? $_POST['idpadre'] : null;
        $id = bin2hex(random_bytes(18)); // 36 chars
        $id_propietario = $_POST['id_propietario'] ?? ($_SESSION['USR_ID'] ?? null);
        // Validar datos básicos
        if (empty($nombre)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'msg' => 'El nombre de la carpeta es requerido']);
            exit;
        }

        if (!$id_propietario) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'msg' => 'Error de sesión: usuario no válido']);
            exit;
        }

        // Conectar usando la clase Database
        require_once 'class/db.php';
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'msg' => 'Error de conexión a la base de datos']);
            exit;
        }
        
        // Validar que el id_propietario existe en us_usuarios
        $stmtCheck = $db->prepare('SELECT COUNT(*) FROM us_usuarios WHERE id_usuario = :id');
        $stmtCheck->bindParam(':id', $id_propietario);
        $stmtCheck->execute();
        $existeUsuario = $stmtCheck->fetchColumn() > 0;
        
        if (!$existeUsuario) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'msg' => 'Usuario propietario no válido']);
            exit;
        }
        $compartido = isset($_POST['compartido']) ? intval($_POST['compartido']) : 0;
        $tipo = $_POST['tipo'] ?? 'D';
        $fecha = date('Y-m-d H:i:s');
        // --- Carpeta destino física ---
        $carpetaDestino = $uploadsBase;
        
        // Asegurar que el directorio base existe
        if (!is_dir($uploadsBase)) {
            mkdir($uploadsBase, 0755, true);
        }
        
        if ($idpadre) {
            // Buscar la ruta física de la carpeta padre
            $stmt = $db->prepare('SELECT id, nombre, idpadre FROM archivos_directorios WHERE id = :idpadre AND tipo = "D"');
            $stmt->bindParam(':idpadre', $idpadre);
            $stmt->execute();
            $padre = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($padre) {
                // Construir ruta física recursiva
                $ruta = [];
                $actual = $padre;
                while ($actual) {
                    $ruta[] = $actual['nombre'];
                    if ($actual['idpadre']) {
                        $stmt2 = $db->prepare('SELECT id, nombre, idpadre FROM archivos_directorios WHERE id = :id AND tipo = "D"');
                        $stmt2->bindParam(':id', $actual['idpadre']);
                        $stmt2->execute();
                        $actual = $stmt2->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $actual = null;
                    }
                }
                $ruta = array_reverse($ruta);
                $carpetaDestino .= '/' . implode('/', $ruta);
            }
        }
        
        $carpetaDestino .= '/' . $nombre;
        
        // Crear carpeta física
        if (!is_dir($carpetaDestino)) {
            if (!mkdir($carpetaDestino, 0755, true)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'msg' => 'Error al crear la carpeta física']);
                exit;
            }
        }
        // --- Guardar en la base de datos ---
        $sql = "INSERT INTO archivos_directorios (id, idpadre, tipo, nombre, id_propietario, fecha, compartido) VALUES (:id, :idpadre, :tipo, :nombre, :id_propietario, :fecha, :compartido)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':idpadre', $idpadre);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id_propietario', $id_propietario);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':compartido', $compartido);
        $stmt->execute();
        // --- Permisos por usuario ---
        if ($compartido && isset($_POST['usuarios'])) {
            $usuarios = $_POST['usuarios'];
            $permiso_ver = isset($_POST['permiso_ver']) ? 1 : 0;
            $permiso_descargar = isset($_POST['permiso_descargar']) ? 1 : 0;
            $permiso_actualizar = isset($_POST['permiso_actualizar']) ? 1 : 0;
            $permiso_borrar = isset($_POST['permiso_borrar']) ? 1 : 0;
            
            foreach ($usuarios as $idusuario) {
                $stmtPerm = $db->prepare("INSERT INTO permisos_archivos (idarchivo, idusuario, ver, descargar, actualizar, borrar) VALUES (:idarchivo, :idusuario, :ver, :descargar, :actualizar, :borrar)");
                $stmtPerm->bindParam(':idarchivo', $id);
                $stmtPerm->bindParam(':idusuario', $idusuario);
                $stmtPerm->bindParam(':ver', $permiso_ver);
                $stmtPerm->bindParam(':descargar', $permiso_descargar);
                $stmtPerm->bindParam(':actualizar', $permiso_actualizar);
                $stmtPerm->bindParam(':borrar', $permiso_borrar);
                $stmtPerm->execute();
            }
        }
        
        // Redirigir de vuelta
        header('Location: ../panel.php?pg=archivos-directorios' . ($idpadre ? '&idpadre=' . urlencode($idpadre) : ''));
        exit;
        
    } catch (Exception $e) {
        // Log del error para debugging
        error_log('Error en crear-carpeta.php: ' . $e->getMessage());
        error_log('Stack trace: ' . $e->getTraceAsString());
        
        // Mostrar error detallado para debugging
        echo '<div style="background:#ffebee;border:1px solid #f44336;padding:20px;margin:20px;border-radius:5px;">';
        echo '<h3 style="color:#d32f2f;">Error al crear carpeta</h3>';
        echo '<p><strong>Mensaje:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>Archivo:</strong> ' . $e->getFile() . '</p>';
        echo '<p><strong>Línea:</strong> ' . $e->getLine() . '</p>';
        echo '<p><strong>Usuario ID:</strong> ' . ($id_propietario ?? 'NO DEFINIDO') . '</p>';
        echo '<p><strong>Nombre carpeta:</strong> ' . htmlspecialchars($nombre ?? 'NO DEFINIDO') . '</p>';
        echo '<p><strong>Sesión USR_ID:</strong> ' . ($_SESSION['USR_ID'] ?? 'NO DEFINIDA') . '</p>';
        echo '<hr><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '<p><a href="javascript:history.back()" style="background:#2196f3;color:white;padding:10px 20px;text-decoration:none;border-radius:3px;">Volver</a></p>';
        echo '</div>';
        exit;
    }
}

// Si no es POST, redirigir
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: ../panel.php?pg=archivos-directorios');
}
exit;
