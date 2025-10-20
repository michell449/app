<?php
require_once __DIR__ . '/../config.php';

// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadsBase = __DIR__ . '/../uploads/archivos';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    try {
        $idpadre = (isset($_POST['idpadre']) && $_POST['idpadre'] !== '' && $_POST['idpadre'] !== '0') ? $_POST['idpadre'] : null;
        $id = bin2hex(random_bytes(18)); // 36 chars
        $id_propietario = $_POST['id_propietario'] ?? ($_SESSION['USR_ID'] ?? null);
        // Validar datos básicos
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al recibir el archivo: ' . ($_FILES['archivo']['error'] ?? 'No se recibió archivo'));
        }

        if (!$id_propietario) {
            throw new Exception('Error de sesión: usuario no válido (USR_ID: ' . ($_SESSION['USR_ID'] ?? 'NO DEFINIDO') . ')');
        }

        // Conectar usando la clase Database
        require_once 'class/db.php';
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception('Error de conexión a la base de datos');
        }
        
        // Validar que el id_propietario existe en us_usuarios
        $stmtCheck = $db->prepare('SELECT COUNT(*) FROM us_usuarios WHERE id_usuario = :id');
        $stmtCheck->bindParam(':id', $id_propietario);
        $stmtCheck->execute();
        $existeUsuario = $stmtCheck->fetchColumn() > 0;
        
        if (!$existeUsuario) {
            throw new Exception('Usuario propietario no válido (ID: ' . $id_propietario . ')');
        }
        $file = $_FILES['archivo'];
        $nombre = $file['name'];
        $tmp = $file['tmp_name'];
        $tamano_kb = round($file['size'] / 1024);
        $tipo_archivo = $file['type'];
        $extension = pathinfo($nombre, PATHINFO_EXTENSION);

        // Validaciones adicionales
        if (empty($nombre)) {
            throw new Exception('El nombre del archivo está vacío');
        }

        if ($file['size'] > 2 * 1024 * 1024) { // 2MB máximo
            // Si es AJAX, devolver JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'msg' => 'El archivo es demasiado grande (máximo 2MB)']);
                exit;
            }
            throw new Exception('El archivo es demasiado grande (máximo 2MB)');
        }

        // Asegurar que el directorio base existe
        if (!is_dir($uploadsBase)) {
            if (!mkdir($uploadsBase, 0755, true)) {
                throw new Exception('No se pudo crear el directorio base de uploads');
            }
        }
        // --- Carpeta destino física ---
        $carpetaDestino = $uploadsBase;
        
        if ($idpadre) {
            // Buscar la ruta física de la carpeta padre
            $stmt = $db->prepare('SELECT id, nombre, idpadre FROM archivos_directorios WHERE id = :idpadre AND tipo = "D"');
            $stmt->bindParam(':idpadre', $idpadre);
            $stmt->execute();
            $padre = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($padre) {
                // Construir ruta recursiva
                $ruta = [$padre['nombre']];
                $id_actual = $padre['idpadre'];
                while ($id_actual) {
                    $stmt2 = $db->prepare('SELECT id, nombre, idpadre FROM archivos_directorios WHERE id = :id AND tipo = "D"');
                    $stmt2->bindParam(':id', $id_actual);
                    $stmt2->execute();
                    $p = $stmt2->fetch(PDO::FETCH_ASSOC);
                    if ($p) {
                        array_unshift($ruta, $p['nombre']);
                        $id_actual = $p['idpadre'];
                    } else {
                        break;
                    }
                }
                $carpetaDestino .= '/' . implode('/', $ruta);
            }
        }
        
        // Crear directorio si no existe
        if (!is_dir($carpetaDestino)) {
            if (!mkdir($carpetaDestino, 0755, true)) {
                throw new Exception('No se pudo crear el directorio destino: ' . $carpetaDestino);
            }
        }
        $destino = $carpetaDestino . '/' . $id . '_' . $nombre;
        
        // Mover archivo
        if (!move_uploaded_file($tmp, $destino)) {
            throw new Exception('Error al mover el archivo al directorio destino: ' . $destino);
        }

        // Guardar en la base de datos
        $sql = "INSERT INTO archivos_directorios (id, idpadre, tipo, nombre, id_propietario, tipo_archivo, tamano_kb, fecha) VALUES (:id, :idpadre, 'A', :nombre, :id_propietario, :tipo_archivo, :tamano_kb, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($idpadre === null) {
            $stmt->bindValue(':idpadre', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':idpadre', $idpadre);
        }
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id_propietario', $id_propietario);
        $stmt->bindParam(':tipo_archivo', $tipo_archivo);
        $stmt->bindParam(':tamano_kb', $tamano_kb);
        $stmt->execute();

        // --- Permisos por usuario ---
        if (isset($_POST['compartido']) && $_POST['compartido'] && isset($_POST['usuarios'])) {
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

        // Éxito
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        // Éxito - redirigir
        header('Location: ../panel.php?pg=archivos-directorios' . ($idpadre ? '&idpadre=' . urlencode($idpadre) : ''));
        exit;

    } catch (Exception $e) {
        // Log del error para debugging
        error_log('Error en upload.php: ' . $e->getMessage());
        error_log('Stack trace: ' . $e->getTraceAsString());
        // Si es AJAX, devolver JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'msg' => $e->getMessage()]);
            exit;
        }
        // Mostrar error detallado para debugging
        echo '<div style="background:#ffebee;border:1px solid #f44336;padding:20px;margin:20px;border-radius:5px;">';
        echo '<h3 style="color:#d32f2f;">Error al subir archivo</h3>';
        echo '<p><strong>Mensaje:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>Archivo:</strong> ' . $e->getFile() . '</p>';
        echo '<p><strong>Línea:</strong> ' . $e->getLine() . '</p>';
        echo '<p><strong>Usuario ID:</strong> ' . ($id_propietario ?? 'NO DEFINIDO') . '</p>';
        echo '<p><strong>Archivo subido:</strong> ' . ($_FILES['archivo']['name'] ?? 'NO DEFINIDO') . '</p>';
        echo '<p><strong>Tamaño:</strong> ' . ($_FILES['archivo']['size'] ?? 'NO DEFINIDO') . ' bytes</p>';
        echo '<p><strong>Sesión USR_ID:</strong> ' . ($_SESSION['USR_ID'] ?? 'NO DEFINIDA') . '</p>';
        echo '<p><strong>Directorio destino:</strong> ' . ($carpetaDestino ?? 'NO DEFINIDO') . '</p>';
        echo '<hr><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '<p><a href="javascript:history.back()" style="background:#2196f3;color:white;padding:10px 20px;text-decoration:none;border-radius:3px;">Volver</a></p>';
        echo '</div>';
        exit;
    }
}

// Si no es POST con archivo, redirigir
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: ../panel.php?pg=archivos-directorios');
}
exit;
