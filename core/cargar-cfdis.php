<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Verificar conexión
    if (!$conn) {
        throw new Exception('No se pudo conectar a la base de datos');
    }
    
    $crud = new Crud($conn);
    $crud->db_table = 'cf_cfdis';

    try {
        // Verificar que se envíe el cliente_id
        if (!isset($_POST['cliente_id']) || empty($_POST['cliente_id'])) {
            throw new Exception('Debe seleccionar un cliente');
        }

        $cliente_id = intval($_POST['cliente_id']);

        // Verificar que el cliente existe
        $stmt = $conn->prepare("SELECT id_cliente FROM sys_clientes WHERE id_cliente = ? AND activo = 1");
        $stmt->execute([$cliente_id]);
        $cliente = $stmt->fetch();
        
        if (!$cliente) {
            throw new Exception('Cliente no encontrado');
        }

        $archivos = [
            'tmp_name' => [],
            'name' => [],
            'error' => [],
            'size' => []
        ];
        $procesados = 0;
        $errores = [];
        $exitosos = [];

        // Procesar archivos XML individuales
        if (isset($_FILES['archivo_xml']) && $_FILES['archivo_xml']['error'] == UPLOAD_ERR_OK) {
            $archivos['tmp_name'][] = $_FILES['archivo_xml']['tmp_name'];
            $archivos['name'][] = $_FILES['archivo_xml']['name'];
            $archivos['error'][] = $_FILES['archivo_xml']['error'];
            $archivos['size'][] = $_FILES['archivo_xml']['size'];
        }

        // Procesar archivos ZIP
        if (isset($_FILES['archivo_zip']) && $_FILES['archivo_zip']['error'] == UPLOAD_ERR_OK) {
            $zip = new ZipArchive();
            $zipPath = $_FILES['archivo_zip']['tmp_name'];
            $archivos_seleccionados = [];
            if (isset($_POST['archivos_seleccionados'])) {
                $archivos_seleccionados = is_array($_POST['archivos_seleccionados']) ? $_POST['archivos_seleccionados'] : json_decode($_POST['archivos_seleccionados'], true);
            }
            if ($zip->open($zipPath) === TRUE) {
                $extractPath = sys_get_temp_dir() . '/cfdis_zip_' . uniqid();
                mkdir($extractPath);
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $entry = $zip->getNameIndex($i);
                    if (strtolower(pathinfo($entry, PATHINFO_EXTENSION)) !== 'xml') continue;
                    // Solo procesar si está en la lista de seleccionados (si no se envió, procesa todos)
                    if (!empty($archivos_seleccionados) && !in_array($entry, $archivos_seleccionados)) continue;
                    $stream = $zip->getStream($entry);
                    if (!$stream) continue;
                    $xmlFile = $extractPath . '/' . basename($entry);
                    file_put_contents($xmlFile, stream_get_contents($stream));
                    fclose($stream);
                    $archivos['tmp_name'][] = $xmlFile;
                    $archivos['name'][] = basename($xmlFile);
                    $archivos['error'][] = UPLOAD_ERR_OK;
                    $archivos['size'][] = filesize($xmlFile);
                }
                $zip->close();
            } else {
                $errores[] = 'No se pudo abrir el archivo ZIP.';
            }
        }

        // Procesar archivos XML múltiples (de input múltiple)
        if (isset($_FILES['archivos_cfdi']) && !empty($_FILES['archivos_cfdi']['tmp_name'])) {
            if (is_array($_FILES['archivos_cfdi']['tmp_name'])) {
                foreach ($_FILES['archivos_cfdi']['tmp_name'] as $idx => $tmpFile) {
                    if ($_FILES['archivos_cfdi']['error'][$idx] == UPLOAD_ERR_OK) {
                        $archivos['tmp_name'][] = $tmpFile;
                        $archivos['name'][] = $_FILES['archivos_cfdi']['name'][$idx];
                        $archivos['error'][] = $_FILES['archivos_cfdi']['error'][$idx];
                        $archivos['size'][] = $_FILES['archivos_cfdi']['size'][$idx];
                    }
                }
            } else {
                // Solo uno
                if ($_FILES['archivos_cfdi']['error'] == UPLOAD_ERR_OK) {
                    $archivos['tmp_name'][] = $_FILES['archivos_cfdi']['tmp_name'];
                    $archivos['name'][] = $_FILES['archivos_cfdi']['name'];
                    $archivos['error'][] = $_FILES['archivos_cfdi']['error'];
                    $archivos['size'][] = $_FILES['archivos_cfdi']['size'];
                }
            }
        }

        // Validar que haya archivos
        if (count($archivos['tmp_name']) == 0) {
            throw new Exception('No se recibieron archivos XML ni ZIP válidos');
        }

        // Procesar cada archivo
        for ($i = 0; $i < count($archivos['tmp_name']); $i++) {
            $tmpFile = $archivos['tmp_name'][$i];
            $fileName = $archivos['name'][$i];
            $fileError = $archivos['error'][$i];

            // Verificar errores de subida
            if ($fileError !== UPLOAD_ERR_OK) {
                $errores[] = "Error al subir $fileName: Código de error $fileError";
                continue;
            }

            // Verificar que sea un archivo XML
            if (pathinfo($fileName, PATHINFO_EXTENSION) !== 'xml') {
                $errores[] = "$fileName no es un archivo XML válido";
                continue;
            }

            try {
                // Leer el contenido del archivo XML
                $xmlContent = file_get_contents($tmpFile);
                if (!$xmlContent) {
                    $errores[] = "$fileName: No se pudo leer el contenido del archivo XML";
                    continue;
                }
                
                // Usar libxml para manejar errores de XML (igual que procesar-xml-cfdi.php)
                libxml_use_internal_errors(true);
                
                // Limpiar el XML de caracteres problemáticos
                $xmlContent = trim($xmlContent);
                $xmlContent = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $xmlContent);
                
                // Usar simplexml_load_string para leer el XML (igual que procesar-xml-cfdi.php)
                $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);
                
                if ($xml === false) {
                    $errors = libxml_get_errors();
                    $errorMsg = 'Error al parsear XML: ';
                    foreach ($errors as $error) {
                        $errorMsg .= $error->message . ' ';
                    }
                    $errores[] = "$fileName: $errorMsg";
                    continue;
                }
                
                // Extraer datos principales usando el método exitoso de procesar-xml-cfdi.php
                // Normalizar folio: quitar espacios y convertir a mayúsculas
                $folio = isset($xml['Folio']) ? strtoupper(trim((string)$xml['Folio'])) : '';
                if ($folio === '' || $folio === 'SIN-FOLIO') {
                    $errores[] = "$fileName: El folio está vacío o no es válido. No se puede guardar el CFDI.";
                    continue;
                }
                $fecha_emision = (string)$xml['Fecha'] ?: '';
                $tipo = (string)$xml['TipoDeComprobante'] ?: 'I';
                $total = (float)$xml['Total'] ?: 0.0;
                $subtotal = (float)$xml['SubTotal'] ?: 0.0;
                // Extraer impuestos del XML CFDI y guardar en importe
                $importe = 0.0;
                if (isset($xml['TotalImpuestosTrasladados']) && $xml['TotalImpuestosTrasladados'] !== '') {
                    $importe = (float)$xml['TotalImpuestosTrasladados'];
                } else {
                    $impuestosNode = $xml->xpath("//*[local-name()='Impuestos']");
                    if ($impuestosNode && count($impuestosNode) > 0 && isset($impuestosNode[0]['TotalImpuestosTrasladados'])) {
                        $importe = (float)$impuestosNode[0]['TotalImpuestosTrasladados'];
                    }
                }
                
                // Debug: Log de datos extraídos
                error_log("CFDI Debug - Datos extraídos del XML:");
                error_log("  Folio: " . $folio);
                error_log("  Fecha: " . $fecha_emision);
                error_log("  Tipo: " . $tipo);
                error_log("  Total: " . $total);
                error_log("  SubTotal: " . $subtotal);
                
                // Generar UUID único para el archivo
                $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );
                
                // Extraer datos del emisor usando el método exitoso de procesar-xml-cfdi.php
                $emisor_nombre = '';
                $emisor_rfc = '';
                
                // Buscar emisor usando diferentes métodos (igual que procesar-xml-cfdi.php)
                $emisor = null;
                if (isset($xml->Emisor)) {
                    $emisor = $xml->Emisor;
                    error_log("CFDI Debug - Emisor encontrado directamente");
                } else {
                    // Buscar con namespace
                    $namespaces = $xml->getNamespaces(true);
                    error_log("CFDI Debug - Buscando emisor con namespaces: " . json_encode($namespaces));
                    foreach ($namespaces as $prefix => $namespace) {
                        $emisores = $xml->xpath("//{$prefix}:Emisor");
                        if (!empty($emisores)) {
                            $emisor = $emisores[0];
                            error_log("CFDI Debug - Emisor encontrado con namespace prefix: " . $prefix);
                            break;
                        }
                    }
                }
                
                if ($emisor) {
                    $emisor_nombre = (string)$emisor['Nombre'] ?: '';
                    $emisor_rfc = (string)$emisor['Rfc'] ?: '';
                    
                    error_log("CFDI Debug - Datos del emisor extraídos:");
                    error_log("  Nombre: " . $emisor_nombre);
                    error_log("  RFC: " . $emisor_rfc);
                } else {
                    error_log("CFDI Debug - NO se pudo encontrar el nodo Emisor");
                }
                
                // Obtener estado del formulario (viene del POST)
                $estado = isset($_POST['estado_cfdi']) ? $_POST['estado_cfdi'] : 'pendiente';
                
                // Verificar si ya existe un CFDI con el mismo folio y cliente (duplicados por folio)
                // Validar duplicado con folio normalizado
                $stmt = $conn->prepare("SELECT id_cfdi FROM cf_cfdis WHERE UPPER(TRIM(folio)) = ? AND id_cliente = ? LIMIT 1");
                $stmt->execute([$folio, $cliente_id]);
                $existe = $stmt->fetch();
                if ($existe) {
                    $errores[] = "El folio '$folio' ya existe para este cliente. No se puede guardar el CFDI ($fileName).";
                    continue;
                }
                
                // Crear directorio si no existe - usar ruta absoluta
                $uploadDir = __DIR__ . '/../uploads/cfdis/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Usar UUID como nombre del archivo
                $nuevoNombre = $uuid . '.xml';
                $rutaArchivo = $uploadDir . $nuevoNombre;
                
                // Debug log
                error_log("CFDI: Guardando $fileName como $nuevoNombre con UUID: $uuid");
                
                // Mover archivo
                $moveOk = false;
                // Si es un upload directo
                if (is_uploaded_file($tmpFile)) {
                    $moveOk = move_uploaded_file($tmpFile, $rutaArchivo);
                } else {
                    // Si es un archivo temporal extraído del ZIP
                    $moveOk = rename($tmpFile, $rutaArchivo);
                    if (!$moveOk) {
                        // Si rename falla, intenta copiar y luego borrar
                        $moveOk = copy($tmpFile, $rutaArchivo);
                        if ($moveOk) @unlink($tmpFile);
                    }
                }
                if (!$moveOk) {
                    $errores[] = "Error al guardar archivo $fileName";
                    continue;
                }
                
                // Preparar datos para el CRUD usando los valores extraídos correctamente
                // Validar productos autorizados para el cliente
                $conceptos = $xml->xpath("//*[local-name()='Concepto']");
                $claves_no_autorizadas = [];
                if ($conceptos && count($conceptos) > 0) {
                    foreach ($conceptos as $concepto) {
                        $clave = (string)$concepto['ClaveProdServ'];
                        if ($clave) {
                            // Verificar si la clave está autorizada para el cliente
                            $stmtCheck = $conn->prepare('SELECT COUNT(*) FROM cat_productos_clientes WHERE id_cliente = ? AND clave = ?');
                            $stmtCheck->execute([$cliente_id, $clave]);
                            $autorizado = $stmtCheck->fetchColumn();
                            if (!$autorizado) {
                                $claves_no_autorizadas[] = $clave;
                            }
                        }
                    }
                }
                if (count($claves_no_autorizadas) > 0) {
                    $errores[] = "$fileName: Producto(s) no autorizado(s) para el cliente: " . implode(', ', $claves_no_autorizadas);
                    continue;
                }
                // Preparar datos para el CRUD usando los valores extraídos correctamente
                // Validar si el RFC del emisor está en la lista negra EFOS
                $efos_alert = 0;
                if (!empty($emisor_rfc)) {
                    $stmtEfos = $conn->prepare('SELECT rfc FROM efos_rfc WHERE rfc = ? LIMIT 1');
                    $stmtEfos->execute([$emisor_rfc]);
                    if ($stmtEfos->fetch()) {
                        $efos_alert = 1;
                        // Si es EFO, forzar estado a 'EFO'
                        $estado = 'EFO';
                    }
                }
                $crud->data = [
                    'folio' => $folio,
                    'fecha_emision' => !empty($fecha_emision) ? $fecha_emision : date('Y-m-d H:i:s'),
                    'tipo' => !empty($tipo) ? $tipo : 'I',
                    'importe' => $importe, // Guardar impuestos en importe
                    'total' => $total > 0 ? $total : 0,         // Usar total para total (con impuestos)
                    'emisor' => !empty($emisor_nombre) ? $emisor_nombre : 'SIN-EMISOR',
                    'rfc' => !empty($emisor_rfc) ? $emisor_rfc : 'SIN-RFC',
                    'estado' => !empty($estado) ? $estado : 'pendiente', // Si es EFO, ya se forzó arriba
                    'uuid' => $uuid,
                    'archivo_xml' => $nuevoNombre,
                    'id_cliente' => $cliente_id,
                    'efos_alert' => $efos_alert
                ];
                

                // Debug: Log de datos antes de insertar
                error_log("CFDI Debug - Datos finales a insertar:");
                error_log("  Folio: " . $crud->data['folio']);
                error_log("  Tipo: " . $crud->data['tipo']);
                error_log("  Importe (SubTotal): " . $crud->data['importe']);
                error_log("  Total: " . $crud->data['total']);
                error_log("  Emisor: " . $crud->data['emisor']);
                error_log("  RFC: " . $crud->data['rfc']);
                error_log("  Estado: " . $crud->data['estado']);
                
                try {
                    $result = $crud->create();
                    if ($result) {
                        $exitosos[] = $fileName;
                        $procesados++;
                        error_log("CFDI Success - Insertado: $fileName con UUID: $uuid");
                    } else {
                        $errores[] = "$fileName: Error al guardar en la base de datos - CRUD create retornó false";
                        error_log("CFDI Error - CRUD create failed para: $fileName");
                    }
                } catch (PDOException $pdoE) {
                    $errores[] = "$fileName: Error PDO - " . $pdoE->getMessage();
                    error_log("CFDI PDO Error - " . $pdoE->getMessage() . " | Archivo: $fileName");
                } catch (Exception $crudE) {
                    $errores[] = "$fileName: Error CRUD - " . $crudE->getMessage();
                    error_log("CFDI CRUD Error - " . $crudE->getMessage() . " | Archivo: $fileName");
                }
                
            } catch (PDOException $pdoE) {
                $errores[] = "$fileName: Error PDO - " . $pdoE->getMessage();
                error_log("CFDI PDO Exception - " . $pdoE->getMessage() . " | Archivo: $fileName | SQL State: " . $pdoE->getCode());
            } catch (Exception $e) {
                $errores[] = "$fileName: " . $e->getMessage();
                error_log("CFDI General Exception - " . $e->getMessage() . " | Archivo: $fileName");
            }
        }
        
        // Respuesta JSON mejorada
        $totalArchivos = count($archivos['tmp_name']);
        $hayErrores = count($errores) > 0;
        $hayExitos = $procesados > 0;
        $respuesta = [
            'success' => $hayExitos,
            'procesados' => $totalArchivos,
            'exitosos' => $procesados,
            'errores' => count($errores),
            'detalles_errores' => $errores,
            'archivos_exitosos' => $exitosos
        ];
        if ($hayErrores) {
            if (!$hayExitos) {
                // Si todos fallaron, poner error principal
                $respuesta['success'] = false;
                $respuesta['error'] = $errores[0];
            } else {
                // Si hay errores parciales, poner mensaje general
                $respuesta['error'] = 'Algunos archivos no se guardaron. Revisa los detalles.';
            }
        }
        echo json_encode($respuesta);
        
    } catch (PDOException $pdoE) {
        error_log("Error PDO en cargar-cfdis.php: " . $pdoE->getMessage() . " | Code: " . $pdoE->getCode());
        echo json_encode([
            'success' => false,
            'error' => 'Error de base de datos: ' . $pdoE->getMessage(),
            'error_code' => $pdoE->getCode(),
            'procesados' => 0,
            'exitosos' => 0,
            'errores' => 1
        ]);
    } catch (Exception $e) {
        error_log("Error general en cargar-cfdis.php: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage(),
            'procesados' => 0,
            'exitosos' => 0,
            'errores' => 1
        ]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
?>