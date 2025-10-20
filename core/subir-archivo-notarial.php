<?php
    // core/subir-archivo-notarial.php
    header('Content-Type: application/json');
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
    $response = ['success' => false, 'msg' => ''];
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['id_notarial']) &&
        isset($_POST['categoria_archivo']) &&
        isset($_POST['fecha_presentacion_cat']) &&
        isset($_FILES['archivo_cat']) &&
        isset($_POST['nombre_archivo_cat'])
    ) {
        $id_notarial = intval($_POST['id_notarial']);
        $categoria = trim($_POST['categoria_archivo']);
        $fecha_presentacion = trim($_POST['fecha_presentacion_cat']);
        $nombre_original = $_FILES['archivo_cat']['name'];
        $nombre_personalizado = trim($_POST['nombre_archivo_cat']);
        $tmp = $_FILES['archivo_cat']['tmp_name'];
        $uuid = generar_uuid();
        $fecha = date('Y-m-d H:i:s');
        $timestamp = date('YmdHis'); 

        // Crear carpeta usando id_notarial
        $carpeta = __DIR__ . '/../uploads/Notariales/' . $id_notarial;
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
            $extension_original = '.pdf'; // Por defecto PDF si no es soportada
        } 

        // Guardar el archivo físico usando el UUID como nombre, manteniendo la extensión original
        $nombre_archivo = $uuid . $extension_original;
        $ruta_destino = $carpeta . '/' . $nombre_archivo;

        if (move_uploaded_file($tmp, $ruta_destino)) {
            $db = new Database();
            $conn = $db->getConnection();
            if ($conn) {
                try {
                    $sql = "INSERT INTO exp_archivos_notariales (uuid, id_notarial, fecha, categoria, fecha_presentacion, documento, nombre_archivo) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    // Crear nombre para mostrar con la extensión correcta
                    if (!empty($nombre_personalizado)) {
                        $info_personalizado = pathinfo($nombre_personalizado);
                        $nombre_para_mostrar = $info_personalizado['filename'];
                        $documento_mostrar = $nombre_para_mostrar . $extension_original;
                    } else {
                        $documento_mostrar = $nombre_original;
                    }
                    // Guardar en nombre_archivo solo el nombre para mostrar (sin UUID ni timestamp)
                    $nombre_archivo_db = $documento_mostrar;
                    $ok = $stmt->execute([
                        $uuid,
                        $id_notarial,
                        $fecha,
                        $categoria,
                        $fecha_presentacion,
                        $documento_mostrar,
                        $nombre_archivo_db
                    ]);
                    if ($ok) {
                        $response['success'] = true;
                        $response['msg'] = 'Archivo subido correctamente.';
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
    echo json_encode($response);
    exit;
?>