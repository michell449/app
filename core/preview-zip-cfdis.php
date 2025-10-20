<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultados = [];
    $errores = [];
    function parseCfdiXmlString($xmlString) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlString);
        if (!$xml) return false;
        $getByLocal = function ($node, $local) {
            $res = $node->xpath("//*[local-name()='$local']");
            return ($res && count($res) > 0) ? $res[0] : null;
        };
        $comprobante = $getByLocal($xml, 'Comprobante') ?: $xml;
        $emisor      = $getByLocal($xml, 'Emisor');
        $receptor    = $getByLocal($xml, 'Receptor');
        $timbre      = $getByLocal($xml, 'TimbreFiscalDigital');
        if (!$comprobante || !$emisor || !$receptor) {
            return false;
        }
        $data = [];
        $data['uuid']         = $timbre ? (string) $timbre['UUID'] : '';
        $data['fecha_emision']= (string) $comprobante['Fecha'] ?: null;
        $data['rfc_emisor']   = (string) $emisor['Rfc'] ?: (string) $emisor['RFC'] ?: '';
        $data['nombre_emisor']= (string) $emisor['Nombre'] ?: '';
        $data['rfc_receptor'] = (string) $receptor['Rfc'] ?: (string) $receptor['RFC'] ?: '';
        $data['subtotal']     = (string) $comprobante['SubTotal'] ?: '0.00';
        $data['total']        = (string) $comprobante['Total'] ?: '0.00';
        $data['serie']        = (string) $comprobante['Serie'] ?: '';
        $data['folio']        = (string) $comprobante['Folio'] ?: '';
        $data['tipo_comprobante'] = (string) $comprobante['TipoDeComprobante'] ?: '';
        // Buscar impuestos trasladados
        $totalImpuestos = 0.00;
        foreach ($xml->xpath("//*[local-name()='Impuestos']") as $impuestosNode) {
            if (isset($impuestosNode['TotalImpuestosTrasladados'])) {
                $totalImpuestos += (float) $impuestosNode['TotalImpuestosTrasladados'];
            }
        }
        $data['total_impuestos'] = number_format($totalImpuestos, 2, '.', '');
        $data['validacion']   = ($data['uuid'] && strlen($data['uuid']) === 36) ? '<span class="badge bg-success">UUID OK</span>' : '<span class="badge bg-danger">UUID Inválido</span>';
        $data['archivo']      = '';
        return $data;
    }

    if (isset($_FILES['archivo_zip']) && $_FILES['archivo_zip']['error'] == UPLOAD_ERR_OK) {
        $zip = new ZipArchive();
        $zipPath = $_FILES['archivo_zip']['tmp_name'];
        if ($zip->open($zipPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);
                if (strtolower(pathinfo($entry, PATHINFO_EXTENSION)) !== 'xml') continue;
                $stream = $zip->getStream($entry);
                if (!$stream) {
                    $errores[] = "No se pudo leer el archivo dentro del zip: $entry";
                    continue;
                }
                $contents = stream_get_contents($stream);
                fclose($stream);
                $parsed = parseCfdiXmlString($contents);
                if (!$parsed) {
                    $errores[] = "XML dentro de ZIP no válido: $entry";
                    continue;
                }
                $parsed['filename'] = $entry;
                $resultados[] = $parsed;
            }
            $zip->close();
        } else {
            $errores[] = 'No se pudo abrir el archivo ZIP.';
        }
    } else {
        $errores[] = 'No se recibió archivo ZIP.';
    }
    echo json_encode(['resultados' => $resultados, 'errores' => $errores]);
    exit;
}
echo json_encode(['resultados' => [], 'errores' => ['Método no permitido']]);
