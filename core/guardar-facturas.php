<?php
// app-m/core/guardar-facturas.php
require_once __DIR__ . "/autoload-phpcfdi.php";
require_once __DIR__ . "/class/db.php"; // $conn (mysqli)
header('Content-Type: application/json; charset=utf-8');


use CfdiUtils\Nodes\XmlNodeUtils;
use PhpCfdi\CfdiToPdf\Converter;
use PhpCfdi\CfdiToPdf\Builders\Html2PdfBuilder;
use PhpCfdi\CfdiToPdf\CfdiData;
use PhpCfdi\CfdiToPdf\CfdiDataBuilder;

// Función para convertir XML a PDF
function convertirXmlAPdf($contenidoXml, $rutaPdfDestino)
{
    try {
        $builder = new Html2PdfBuilder();
        $converter = new Converter($builder);
        $comprobante = XmlNodeUtils::nodeFromXmlString($contenidoXml);
        $cfdiDataBuilder = new CfdiDataBuilder();
        $cfdiData = $cfdiDataBuilder->build($comprobante);

        $converter->createPdfAs($cfdiData, $rutaPdfDestino);
        return true;
    } catch (Throwable $e) {
        // Log detallado y guardar XML para depuración
        error_log("Error al convertir XML a PDF: " . $e->getMessage());
        $debugFile = __DIR__ . '/error_xml_debug_' . time() . '.xml';
        file_put_contents($debugFile, $contenidoXml);
        error_log("XML fallido guardado en: " . $debugFile);
        return false;
    }
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['items'])) {
    echo json_encode(['success' => false, 'message' => 'No hay facturas para guardar']);
    exit;
}

$uploadTmpDir = __DIR__ . "/../uploads/tmp";
$uploadXmlDir = __DIR__ . "/../uploads/xml/";
$uploadPdfDir = __DIR__ . "/../uploads/pdf/";
if (!is_dir($uploadXmlDir)) mkdir($uploadXmlDir, 0755, true);
if (!is_dir($uploadPdfDir)) mkdir($uploadPdfDir, 0755, true);


$items = $input['items'];
$inserted = [];
$errors = [];

// Instanciar la conexión correctamente usando la clase Database (patrón del controlador db.php)
$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'No hay conexión a la base de datos']);
    exit;
}

// preparar statement 
$stmt = $conn->prepare("INSERT INTO facturas 
    (uuid, version, fecha, subtotal, total, moneda, metodo_pago, forma_pago, lugar_expedicion, no_certificado, condiciones_pago, exportacion, tipo_comprobante, emisor_rfc, emisor_nombre, emisor_regimen, receptor_rfc, receptor_nombre, receptor_domicilio, receptor_regimen, receptor_uso_cfdi, no_certificado_sat, rfc_prov_certif, xml_file, pdf_file ,serie, folio)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar consulta: ' . ($conn->errorInfo()[2] ?? 'Error desconocido')]);
    exit;
}

foreach ($items as $it) {
    // esperemos que cliente envíe el _tmp_file regresado por cargar-xml.php
    if (empty($it['_tmp_file'])) {
        $errors[] = ['uuid' => $it['uuid'] ?? null, 'error' => 'Temp file faltante'];
        continue;
    }

    // revisar duplicado por uuid
    $uuid = $it['uuid'];
    $stmtCheck = $conn->prepare("SELECT uuid FROM facturas WHERE uuid = ? LIMIT 1");
    $stmtCheck->execute([$uuid]);
    if ($stmtCheck->fetch()) {
        $errors[] = ['uuid' => $uuid, 'error' => 'UUID ya existe en la base'];
        @unlink($uploadTmpDir . $it['_tmp_file']);
        continue;
    }

    $tmpname = $uploadTmpDir . $it['_tmp_file'];
    $finalXmlName = $uuid . '.xml';
    $dest = $uploadXmlDir . $finalXmlName;
    error_log('Depuración: tmpname=' . $tmpname . ' | destino=' . $dest . ' | existe_tmp=' . (file_exists($tmpname) ? 'SI' : 'NO'));
    if (!file_exists($tmpname)) {
        error_log('Archivo temporal no existe: ' . $tmpname);
    }
    if (!file_exists($tmpname) || !copy($tmpname, $dest)) {
        error_log('Error copiando archivo de ' . $tmpname . ' a ' . $dest . ' | ' . print_r(error_get_last(), true));
        $errors[] = ['uuid' => $uuid, 'error' => 'No se pudo copiar xml a carpeta final'];
        continue;
    }
    @unlink($tmpname); // Elimina el temporal solo si se copió

    // generacion de pdf 
    $finalPdfName = $uuid . '.pdf';
    $destPdf = $uploadPdfDir . $finalPdfName;
    $xmlContent = file_get_contents($dest);
    if (!convertirXmlAPdf($xmlContent, $destPdf)) {
        $errors[] = ['uuid' => $uuid, 'error' => 'Error al convertir XML a PDF'];
        @unlink($dest);
        continue;
    }
 
    $version = $it['version'] ?? '';
    $fecha = $it['fecha'] ?? null;
    $subtotal = $it['subtotal'] ?? 0.00;
    $total = $it['total'] ?? 0.00;
    $moneda = $it['moneda'] ?? '';
    $metodo_pago = $it['metodo_pago'] ?? '';
    $forma_pago = $it['forma_pago'] ?? '';
    $lugar_expedicion = $it['lugar_expedicion'] ?? '';
    $no_certificado = $it['no_certificado'] ?? '';
    $condiciones_pago = $it['condiciones_pago'] ?? '';
    $exportacion = $it['exportacion'] ?? '';
    $tipo_comprobante = $it['tipo_comprobante'] ?? '';
    $emisor_rfc = $it['emisor_rfc'] ?? '';
    $emisor_nombre = $it['emisor_nombre'] ?? '';
    $emisor_regimen = $it['emisor_regimen'] ?? '';
    $receptor_rfc = $it['receptor_rfc'] ?? '';
    $receptor_nombre = $it['receptor_nombre'] ?? '';
    $receptor_domicilio = $it['receptor_domicilio'] ?? '';
    $receptor_regimen = $it['receptor_regimen'] ?? '';
    $receptor_uso_cfdi = $it['receptor_uso_cfdi'] ?? '';
    $no_certificado_sat = $it['no_certificado_sat'] ?? '';
    $rfc_prov_certif = $it['rfc_prov_certif'] ?? '';
    $xml_file = $finalXmlName; // guarda rutas relativas
    $pdf_file = $finalPdfName; // guarda rutas relativas
    $serie = $it['serie'] ?? '';
    $folio = $it['folio'] ?? '';

    $ok = $stmt->execute([
        $uuid,
        $version,
        $fecha,
        $subtotal,
        $total,
        $moneda,
        $metodo_pago,
        $forma_pago,
        $lugar_expedicion,
        $no_certificado,
        $condiciones_pago,
        $exportacion,
        $tipo_comprobante,
        $emisor_rfc,
        $emisor_nombre,
        $emisor_regimen,
        $receptor_rfc,
        $receptor_nombre,
        $receptor_domicilio,
        $receptor_regimen,
        $receptor_uso_cfdi,
        $no_certificado_sat,
        $rfc_prov_certif,
        $xml_file,
        $pdf_file,
        $serie,
        $folio
    ]);

    if ($ok) {
        $inserted[] = $uuid;
    } else {
        $errors[] = ['uuid' => $uuid, 'error' => 'Error execute: ' . implode(' | ', $stmt->errorInfo())];
        // rollback parcial: intentar eliminar archivo movido
        @unlink($dest);
        @unlink($destPdf);
    }
}

echo json_encode(['success' => count($errors) === 0, 'inserted' => $inserted, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
exit;
