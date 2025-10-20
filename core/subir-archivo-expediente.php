<?php
// core/subir-archivo-expediente.php
require_once __DIR__ . '/class/db.php';
function generar_uuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
$response = ['ok' => false, 'msg' => ''];
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['id_expediente']) &&
    isset($_POST['tipo_archivo']) &&
    isset($_POST['fecha_presentacion']) &&
    isset($_FILES['archivo'])
    // Nuevo campo para el nombre personalizado
    && isset($_POST['nombre_archivo'])
) {
    $id_expediente = intval($_POST['id_expediente']);
    $tipo_archivo = trim($_POST['tipo_archivo']);
    $fecha_presentacion = trim($_POST['fecha_presentacion']);
    $nombre_original = $_FILES['archivo']['name'];
    $nombre_personalizado = trim($_POST['nombre_archivo']);
    $tmp = $_FILES['archivo']['tmp_name'];
    $uuid = generar_uuid();
    $fecha = date('Y-m-d H:i:s');
    $timestamp = date('YmdHis'); 
    
    // Crear carpeta usando id_expediente
    $carpeta = __DIR__ . '/../uploads/Expedientes/' . $id_expediente;
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    
    // SIEMPRE usar la extensión del archivo original subido
    $info_archivo_original = pathinfo($nombre_original);
    $extension_original = isset($info_archivo_original['extension']) ? '.' . strtolower($info_archivo_original['extension']) : '';
    
    // Lista de extensiones soportadas
    $extensiones_soportadas = ['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.jpg', '.jpeg', '.png', '.gif', '.txt'];
    
    // Validar que la extensión esté soportada
    if (empty($extension_original) || !in_array($extension_original, $extensiones_soportadas)) {
        $extension_original = '.pdf,'; // Por defecto PDF si no es soportada
    } 
    
    // Para el nombre físico del archivo, usar el nombre personalizado sin extensión + extensión original
    if (!empty($nombre_personalizado)) {
        // Remover cualquier extensión del nombre personalizado para evitar duplicados
        $info_personalizado = pathinfo($nombre_personalizado);
        $nombre_sin_ext = $info_personalizado['filename'];
    } else {
        $nombre_sin_ext = $info_archivo_original['filename'];
    }
    
    // Crear nombre único con timestamp: nombre_20250925143022.ext
    // Calcular espacio disponible: 35 - 1 (guión bajo) - 14 (timestamp) - longitud_extensión
    $espacio_extension = strlen($extension_original);
    $espacio_disponible = 35 - 1 - 14 - $espacio_extension;
    
    // Truncar el nombre si es necesario
    if (strlen($nombre_sin_ext) > $espacio_disponible) {
        $nombre_sin_ext = substr($nombre_sin_ext, 0, $espacio_disponible);
    }
    
    $nombre_archivo = $nombre_sin_ext . '_' . $timestamp . $extension_original;
    $ruta_destino = $carpeta . '/' . $nombre_archivo;
    
    // Debug antes de guardar el archivo
    error_log("DEBUG ANTES DE GUARDAR:");
    error_log("nombre_sin_ext: " . $nombre_sin_ext);
    error_log("timestamp: " . $timestamp);
    error_log("extension_original: " . $extension_original);
    error_log("espacio_disponible: " . $espacio_disponible);
    error_log("nombre_archivo (longitud " . strlen($nombre_archivo) . "): " . $nombre_archivo);
    error_log("ruta_destino: " . $ruta_destino);
    
    if (move_uploaded_file($tmp, $ruta_destino)) {
        $db = new Database();
        $conn = $db->getConnection();
        if ($conn) {
            try {
                $sql = "INSERT INTO exp_documentos (uuid, id_expediente, fecha, tipo_archivo, fecha_presentacion, documento, nombre_archivo) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                // Crear nombre para mostrar con la extensión correcta
                if (!empty($nombre_personalizado)) {
                    // Usar nombre personalizado pero con la extensión del archivo original
                    $info_personalizado = pathinfo($nombre_personalizado);
                    $nombre_para_mostrar = $info_personalizado['filename'];
                    $documento_mostrar = $nombre_para_mostrar . $extension_original;
                } else {
                    // Usar nombre original completo
                    $documento_mostrar = $nombre_original;
                }
                
                // Debug antes de insertar en BD
                error_log("DEBUG ANTES DE INSERTAR EN BD:");
                error_log("documento_mostrar: " . $documento_mostrar);
                error_log("nombre_archivo: " . $nombre_archivo);
                error_log("SQL: " . $sql);
                
                $ok = $stmt->execute([
                    $uuid,
                    $id_expediente,
                    $fecha,
                    $tipo_archivo,
                    $fecha_presentacion,
                    $documento_mostrar, // nombre para mostrar (con extensión garantizada)
                    $nombre_archivo     // nombre físico del archivo (siempre con extensión y timestamp)
                ]);
                if ($ok) {
                    $response['ok'] = true;
                    $response['msg'] = 'Archivo subido correctamente.';
                    
                    // Verificar qué se insertó realmente
                    $last_id = $conn->lastInsertId();
                    error_log("Registro insertado con ID: " . $last_id);
                    
                    // Consultar lo que se guardó realmente
                    $verify_sql = "SELECT documento, nombre_archivo FROM exp_documentos WHERE id_doc = ?";
                    $verify_stmt = $conn->prepare($verify_sql);
                    $verify_stmt->execute([$last_id]);
                    $saved_data = $verify_stmt->fetch(PDO::FETCH_ASSOC);
                    error_log("Datos guardados: " . json_encode($saved_data));
                    
                    $response['debug'] = [
                        'nombre_original' => $nombre_original,
                        'nombre_personalizado' => $nombre_personalizado,
                        'extension_detectada' => $extension_original,
                        'documento_mostrar' => $documento_mostrar,
                        'nombre_archivo_fisico' => $nombre_archivo,
                        'ruta_guardado' => $ruta_destino,
                        'extensiones_soportadas' => $extensiones_soportadas,
                        'datos_guardados_en_bd' => $saved_data,
                        'last_insert_id' => $last_id
                    ];
                } else {
                    $errorInfo = $stmt->errorInfo();
                    $response['msg'] = 'Error al guardar en la base de datos: ' . ($errorInfo[2] ?? '');
                }
            } catch (Exception $e) {
                $response['msg'] = 'Excepción SQL: ' . $e->getMessage();
            }
        } else {
            $response['msg'] = 'Error de conexión a la base de datos.';
        }
    } else {
        $response['msg'] = 'Error al mover el archivo.';
    }
} else {
    $response['msg'] = 'Datos incompletos.';
}
header('Content-Type: application/json');
echo json_encode($response);
