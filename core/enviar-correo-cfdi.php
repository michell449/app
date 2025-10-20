<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/mail/sendmail.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

$id_cfdi = isset($input['id_cfdi']) ? intval($input['id_cfdi']) : 0;
$email_override = isset($input['email']) ? trim($input['email']) : '';

if ($id_cfdi <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de CFDI inválido']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "
        SELECT 
            c.id_cfdi,
            c.folio,
            c.fecha_emision,
            c.emisor,
            c.rfc,
            c.total,
            c.tipo,
            c.archivo_xml,
            cl.id_cliente,
            cl.nombre_comercial,
            cl.correo AS cliente_correo
        FROM cf_cfdis c
        LEFT JOIN sys_clientes cl ON cl.id_cliente = c.id_cliente
        WHERE c.id_cfdi = :id
        LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id_cfdi]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'CFDI no encontrado']);
        exit;
    }

    $email = $email_override !== '' ? $email_override : ($row['cliente_correo'] ?? '');
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'El cliente no tiene un correo válido registrado']);
        exit;
    }

    $folio = $row['folio'] ?: 'SIN-FOLIO';
    $emisor = $row['emisor'] ?: '';
    $total = number_format((float)($row['total'] ?? 0), 2);
    $fecha = $row['fecha_emision'] ?: '';
    $tipo = $row['tipo'] ?: '';
    // Mapear tipo a descripción amigable (I/E/T/P/N)
    $tipoCompleto = $tipo;
    switch (strtoupper($tipo)) {
        case 'I': $tipoCompleto = 'I - Ingreso'; break;
        case 'E': $tipoCompleto = 'E - Egreso'; break;
        case 'T': $tipoCompleto = 'T - Traslado'; break;
        case 'P': $tipoCompleto = 'P - Pago'; break;
        case 'N': $tipoCompleto = 'N - Nómina'; break;
        default: $tipoCompleto = $tipo; break;
    }
    $clienteNombre = $row['nombre_comercial'] ?: 'Cliente';

    $subject = "CFDI folio {$folio}";
    // Texto amigable que se inyecta en la plantilla
    $msg = "Estimado(a) {$clienteNombre},<br><br>"
        . "Adjuntamos su CFDI solicitado. A continuación, se presenta un resumen del comprobante:<br>"
        . "<strong>Folio:</strong> {$folio}<br>"
        . "<strong>Emisor:</strong> {$emisor}<br>"
        . "<strong>RFC:</strong> {$row['rfc']}<br>"
        . "<strong>Fecha de emisión:</strong> {$fecha}<br>"
        . "<strong>Tipo:</strong> {$tipoCompleto}<br>"
        . "<strong>Total:</strong> $ {$total}<br><br>"
        . "Para su resguardo, se adjunta el archivo XML correspondiente al CFDI.";

    // Intentar adjuntar el XML si existe en disco
    $attachments = [];
    $xmlFile = $row['archivo_xml'] ? (dirname(__DIR__) . '/uploads/cfdis/' . $row['archivo_xml']) : '';
    if ($xmlFile && file_exists($xmlFile)) {
        $attachments[] = [
            'path' => $xmlFile,
            'name' => 'CFDI-' . preg_replace('/[^A-Za-z0-9_-]/', '_', $folio) . '.xml'
        ];
    }

    // Usar la plantilla por defecto definida en enviacorreo (plantilla1.html)
    $ok = enviacorreo($clienteNombre, $email, $subject, $msg, 'plantilla1.html', $attachments);

    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Correo enviado correctamente']);
    } else {
        $err = isset($_SESSION['ERROR_MSG']) ? $_SESSION['ERROR_MSG'] : 'No se pudo enviar el correo.';
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $err]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al enviar correo: ' . $e->getMessage()]);
}

?>
