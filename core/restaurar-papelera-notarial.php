<?php
    // core/restaurar-papelera-notarial.php
    // Controlador para restaurar un archivo notarial desde la papelera y moverlo a la ruta física

    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/class/db.php';

    header('Content-Type: application/json');
    $debug = [];


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $debug['error'] = 'Método no permitido';
        echo json_encode(['ok' => false, 'msg' => 'Método no permitido', 'debug' => $debug]);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $id_doc = isset($data['id_doc']) ? intval($data['id_doc']) : 0;


    if ($id_doc <= 0) {
        $debug['error'] = 'ID de documento inválido';
        $debug['id_doc'] = $id_doc;
        echo json_encode(['ok' => false, 'msg' => 'ID de documento inválido', 'debug' => $debug]);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        $debug['error'] = 'Error de conexión a la base de datos';
        echo json_encode(['ok' => false, 'msg' => 'Error de conexión a la base de datos', 'debug' => $debug]);
        exit;
    }



    // Obtener información del archivo SOLO si está en la papelera lógica y física
    $sql = "SELECT id_doc, id_notarial, categoria, nombre_archivo, documento, uuid FROM exp_archivos_notariales WHERE id_doc = :id_doc AND en_papelera = 1 AND en_papelera_fisica = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_doc', $id_doc, PDO::PARAM_INT);
    $stmt->execute();
    $archivo = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$archivo) {
        $debug['error'] = 'Archivo no encontrado en la papelera';
        $debug['id_doc'] = $id_doc;
        echo json_encode(['ok' => false, 'msg' => 'Archivo no encontrado en la papelera', 'debug' => $debug]);
        exit;
    }


    $categoria = $archivo['categoria'];
    $uuid = $archivo['uuid'];
    $id_notarial = $archivo['id_notarial'];

    // Consultar el número de id_notarial en la tabla exp_notariales
    $sql_notarial = "SELECT id_notarial FROM exp_notariales WHERE id_notarial = :id_notarial";
    $stmt_notarial = $conn->prepare($sql_notarial);
    $stmt_notarial->bindParam(':id_notarial', $id_notarial, PDO::PARAM_INT);
    $stmt_notarial->execute();
    $row_notarial = $stmt_notarial->fetch(PDO::FETCH_ASSOC);

    if (!$row_notarial) {
        $debug['error'] = 'No se encontró el expediente notarial';
        $debug['id_notarial'] = $id_notarial;
        echo json_encode(['ok' => false, 'msg' => 'No se encontró el expediente notarial', 'debug' => $debug]);
        exit;
    }

    // Ruta de destino: .../Papelera/Notariales/<id_notarial>/<categoria>/


    // Ruta de destino: .../Papelera/Notariales/<id_notarial>/<categoria>/
    $ruta_base = __DIR__ . '/../uploads/Papelera/Notariales/';
    $ruta_id_notarial = $ruta_base . $id_notarial;
    if (!is_dir($ruta_id_notarial)) {
        mkdir($ruta_id_notarial, 0777, true);
    }
    $ruta_categoria = $ruta_id_notarial . '/' . $categoria;
    if (!is_dir($ruta_categoria)) {
        mkdir($ruta_categoria, 0777, true);
    }




    // Buscar el archivo por UUID en la carpeta Notariales/<id_notarial> o en la subcarpeta de categoría, sin importar la extensión
    $ruta_base_origen = __DIR__ . '/../uploads/Notariales/' . $id_notarial;
    $ruta_origen = '';
    $archivo_encontrado = '';


    // Buscar el archivo por UUID+.pdf primero, luego por UUID con cualquier extensión, luego por nombre_archivo
    $archivo_encontrado = '';
    // 1. Buscar por UUID+.pdf en la raíz
    $ruta_uuid_pdf = $ruta_base_origen . '/' . $uuid . '.pdf';
    if (file_exists($ruta_uuid_pdf)) {
        $archivo_encontrado = $ruta_uuid_pdf;
    }
    // 2. Buscar por UUID+.pdf en la subcarpeta de categoría
    if (!$archivo_encontrado && is_dir($ruta_base_origen . '/' . $categoria)) {
        $ruta_uuid_pdf_cat = $ruta_base_origen . '/' . $categoria . '/' . $uuid . '.pdf';
        if (file_exists($ruta_uuid_pdf_cat)) {
            $archivo_encontrado = $ruta_uuid_pdf_cat;
        }
    }
    // 3. Buscar por UUID en la raíz (cualquier extensión)
    if (!$archivo_encontrado) {
        $archivos_uuid = glob($ruta_base_origen . '/' . $uuid . '.*');
        if (!empty($archivos_uuid)) {
            $archivo_encontrado = $archivos_uuid[0];
        }
    }
    // 4. Buscar por UUID en la subcarpeta de categoría (cualquier extensión)
    if (!$archivo_encontrado && is_dir($ruta_base_origen . '/' . $categoria)) {
        $archivos_uuid_cat = glob($ruta_base_origen . '/' . $categoria . '/' . $uuid . '.*');
        if (!empty($archivos_uuid_cat)) {
            $archivo_encontrado = $archivos_uuid_cat[0];
        }
    }
    // 5. Buscar por nombre_archivo en la raíz
    if (!$archivo_encontrado && !empty($archivo['nombre_archivo'])) {
        $ruta_nombre_archivo = $ruta_base_origen . '/' . $archivo['nombre_archivo'];
        if (file_exists($ruta_nombre_archivo)) {
            $archivo_encontrado = $ruta_nombre_archivo;
        }
    }
    // 6. Buscar por nombre_archivo en la subcarpeta de categoría
    if (!$archivo_encontrado && !empty($archivo['nombre_archivo']) && is_dir($ruta_base_origen . '/' . $categoria)) {
        $ruta_nombre_archivo_cat = $ruta_base_origen . '/' . $categoria . '/' . $archivo['nombre_archivo'];
        if (file_exists($ruta_nombre_archivo_cat)) {
            $archivo_encontrado = $ruta_nombre_archivo_cat;
        }
    }


    if (!$archivo_encontrado || !file_exists($archivo_encontrado)) {
        $debug['error'] = 'El archivo físico no existe en la ruta original';
        $debug['uuid'] = $uuid;
        $debug['categoria'] = $categoria;
        $debug['ruta_base_origen'] = $ruta_base_origen;
        $debug['nombre_archivo'] = $archivo['nombre_archivo'];
        $debug['intentos'] = [
            $ruta_base_origen . '/' . $uuid . '.pdf',
            $ruta_base_origen . '/' . $categoria . '/' . $uuid . '.pdf',
            $ruta_base_origen . '/' . $uuid . '.*',
            $ruta_base_origen . '/' . $categoria . '/' . $uuid . '.*',
            $ruta_base_origen . '/' . $archivo['nombre_archivo'],
            $ruta_base_origen . '/' . $categoria . '/' . $archivo['nombre_archivo']
        ];
        echo json_encode(['ok' => false, 'msg' => 'El archivo físico no existe en la ruta original', 'debug' => $debug]);
        exit;
    }


    // Obtener la extensión original
    $extension = pathinfo($archivo_encontrado, PATHINFO_EXTENSION);
    $nombre_archivo_destino = $archivo['nombre_archivo'];
    // Si el nombre_archivo no tiene extensión, se la agregamos
    if (strtolower(substr($nombre_archivo_destino, -strlen($extension)-1)) !== '.' . strtolower($extension)) {
        $nombre_archivo_destino .= '.' . $extension;
    }

    $ruta_destino = $ruta_categoria . '/' . $nombre_archivo_destino;


    if (!file_exists($archivo_encontrado)) {
        $debug['error'] = 'El archivo físico no existe en la ruta original (al mover)';
        $debug['ruta_origen'] = $archivo_encontrado;
        echo json_encode(['ok' => false, 'msg' => 'El archivo físico no existe en la ruta original (al mover)', 'debug' => $debug]);
        exit;
    }

    // Mover el archivo de Notariales a la papelera

    if (!rename($archivo_encontrado, $ruta_destino)) {
        $debug['error'] = 'No se pudo mover el archivo físico a la papelera';
        $debug['ruta_origen'] = $archivo_encontrado;
        $debug['ruta_destino'] = $ruta_destino;
        echo json_encode(['ok' => false, 'msg' => 'No se pudo mover el archivo físico a la papelera', 'debug' => $debug]);
        exit;
    }



    // Actualizar el registro para marcarlo como restaurado (en_papelera = 0, en_papelera_fisica = 1)
    try {
        $sql_update = "UPDATE exp_archivos_notariales SET en_papelera = 0, en_papelera_fisica = 1 WHERE id_doc = :id_doc";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':id_doc', $id_doc, PDO::PARAM_INT);
        $stmt_update->execute();

        if ($stmt_update->rowCount() > 0) {
            echo json_encode(['ok' => true, 'msg' => 'Archivo restaurado y movido a la ruta física', 'debug' => $debug]);
        } else {
            $debug['error'] = 'No se pudo actualizar el registro en la base de datos';
            echo json_encode(['ok' => false, 'msg' => 'No se pudo actualizar el registro en la base de datos', 'debug' => $debug]);
        }
    } catch (PDOException $e) {
        $debug['error'] = 'Error SQL: ' . $e->getMessage();
        echo json_encode(['ok' => false, 'msg' => 'Error al actualizar el registro en la base de datos', 'debug' => $debug]);
    }
?>