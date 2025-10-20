<?php
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    if (!isset($_FILES['xml_file']) || $_FILES['xml_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No se recibió el archivo XML o hubo un error en la subida');
    }

    $xmlFile = $_FILES['xml_file'];
    $xmlContent = file_get_contents($xmlFile['tmp_name']);
    
    if (!$xmlContent) {
        throw new Exception('No se pudo leer el contenido del archivo XML');
    }

    // Verificar que libxml esté disponible
    if (!extension_loaded('libxml')) {
        throw new Exception('Extensión libxml no está disponible');
    }

    if (!extension_loaded('SimpleXML')) {
        throw new Exception('Extensión SimpleXML no está disponible');
    }

    // Usar libxml para manejar errores de XML
    libxml_use_internal_errors(true);
    
    // Limpiar el XML de caracteres problemáticos
    $xmlContent = trim($xmlContent);
    $xmlContent = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $xmlContent);
    
    // Usar simplexml_load_string para leer el XML
    $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);
    
    if ($xml === false) {
        $errors = libxml_get_errors();
        $errorMsg = 'Error al parsear XML: ';
        foreach ($errors as $error) {
            $errorMsg .= $error->message . ' ';
        }
        throw new Exception($errorMsg);
    }

    // Inicializar array de datos
    $datos = [];
    
    // Obtener atributos del comprobante principal
    $datos['folio'] = (string)$xml['Folio'] ?: 'Sin folio';
    $datos['serie'] = (string)$xml['Serie'] ?: '';
    $datos['fecha'] = (string)$xml['Fecha'] ?: '';
    $datos['total'] = (string)$xml['Total'] ?: '0.00';
    $datos['subtotal'] = (string)$xml['SubTotal'] ?: '0.00';
    $datos['tipo_comprobante'] = (string)$xml['TipoDeComprobante'] ?: '';
    $datos['moneda'] = (string)$xml['Moneda'] ?: 'MXN';
    
    // Buscar emisor usando diferentes métodos
    $emisor = null;
    if (isset($xml->Emisor)) {
        $emisor = $xml->Emisor;
    } else {
        // Buscar con namespace
        $namespaces = $xml->getNamespaces(true);
        foreach ($namespaces as $prefix => $namespace) {
            $emisores = $xml->xpath("//{$prefix}:Emisor");
            if (!empty($emisores)) {
                $emisor = $emisores[0];
                break;
            }
        }
    }
    
    if ($emisor) {
        $datos['emisor_nombre'] = (string)$emisor['Nombre'] ?: 'Sin nombre';
        $datos['emisor_rfc'] = (string)$emisor['Rfc'] ?: 'Sin RFC';
    } else {
        $datos['emisor_nombre'] = 'No encontrado';
        $datos['emisor_rfc'] = 'No encontrado';
    }
    
    // Buscar receptor
    $receptor = null;
    if (isset($xml->Receptor)) {
        $receptor = $xml->Receptor;
    } else {
        // Buscar con namespace
        $namespaces = $xml->getNamespaces(true);
        foreach ($namespaces as $prefix => $namespace) {
            $receptores = $xml->xpath("//{$prefix}:Receptor");
            if (!empty($receptores)) {
                $receptor = $receptores[0];
                break;
            }
        }
    }
    
    if ($receptor) {
        $datos['receptor_nombre'] = (string)$receptor['Nombre'] ?: 'Sin nombre';
        $datos['receptor_rfc'] = (string)$receptor['Rfc'] ?: 'Sin RFC';
    } else {
        $datos['receptor_nombre'] = 'No encontrado';
        $datos['receptor_rfc'] = 'No encontrado';
    }
    
    // Calcular impuestos
    $total = floatval($datos['total']);
    $subtotal = floatval($datos['subtotal']);
    $impuestos_calculados = $total - $subtotal;
    
    // Buscar impuestos en el XML
    $impuestos = null;
    if (isset($xml->Impuestos)) {
        $impuestos = $xml->Impuestos;
    }
    
    if ($impuestos && isset($impuestos['TotalImpuestosTrasladados'])) {
        $datos['impuestos'] = number_format(floatval($impuestos['TotalImpuestosTrasladados']), 2);
    } else {
        $datos['impuestos'] = number_format($impuestos_calculados, 2);
    }
    
    // Obtener tipo de comprobante en texto
    $tipos = [
        'I' => 'Ingreso',
        'E' => 'Egreso', 
        'T' => 'Traslado',
        'P' => 'Pago'
    ];
    $datos['tipo_texto'] = $tipos[$datos['tipo_comprobante']] ?? $datos['tipo_comprobante'];
    
    // Formatear fecha para mostrar
    if ($datos['fecha']) {
        try {
            $fecha = new DateTime($datos['fecha']);
            $datos['fecha_formateada'] = $fecha->format('Y-m-d');
        } catch (Exception $e) {
            $datos['fecha_formateada'] = '';
        }
    } else {
        $datos['fecha_formateada'] = '';
    }
    
    // Crear folio completo
    $datos['folio_completo'] = ($datos['serie'] ? $datos['serie'] . '-' : '') . $datos['folio'];
    
    // Formatear montos con separadores de miles
    $datos['total_formateado'] = '$' . number_format(floatval($datos['total']), 2);
    $datos['impuestos_formateado'] = '$' . number_format(floatval($datos['impuestos']), 2);
    
    // Obtener ClaveProdServ del XML (de los conceptos)
    $claveProdServ = null;
    if (isset($xml->Conceptos) && isset($xml->Conceptos->Concepto)) {
        $concepto = $xml->Conceptos->Concepto;
        $claveProdServ = (string)$concepto['ClaveProdServ'] ?? null;
    } else {
        // Buscar con namespace si es necesario
        $namespaces = $xml->getNamespaces(true);
        foreach ($namespaces as $prefix => $namespace) {
            $conceptos = $xml->xpath("//{$prefix}:Concepto");
            if (!empty($conceptos)) {
                $claveProdServ = (string)$conceptos[0]['ClaveProdServ'] ?? null;
                break;
            }
        }
    }

    // Guardar en la base de datos el id_cliente y claveProdServ usando las clases
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';
    $database = new Database();
    $db = $database->getConnection();
    $crud = new crud($db);
    $crud->db_table = 'cat_productos_clientes';
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
    if ($id_cliente > 0 && $claveProdServ) {
        $crud->data = [
            'id_cliente' => $id_cliente,
            'clave' => $claveProdServ
        ];
        $crud->create();
    }

    // Debug info
    $datos['debug'] = [
        'archivo' => $xmlFile['name'],
        'tamaño' => $xmlFile['size'],
        'namespaces' => $xml->getNamespaces(true),
        'elemento_raiz' => $xml->getName(),
        'claveProdServ' => $claveProdServ,
        'id_cliente' => $id_cliente
    ];

    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'datos' => $datos,
        'mensaje' => 'XML procesado correctamente'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'datos' => null
    ]);
}
?>