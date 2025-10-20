<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_xml'])) {
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
        $data['folio']        = (string) $comprobante['Folio'] ?: '';
        $data['fecha_emision']= (string) $comprobante['Fecha'] ?: null;
        $data['tipo']         = (string) $comprobante['TipoDeComprobante'] ?: '';
        $data['total']        = (string) $comprobante['Total'] ?: '0.00';
        $data['subtotal']     = (string) $comprobante['SubTotal'] ?: '0.00';
        $data['emisor']       = (string) $emisor['Nombre'] ?: '';
        $data['rfc']          = (string) $emisor['Rfc'] ?: (string) $emisor['RFC'] ?: '';
        $data['tipo']         = (string) $comprobante['TipoDeComprobante'] ?: '';
        // Extraer impuestos correctamente
        $impuestos = '';
        if (isset($comprobante['TotalImpuestosTrasladados']) && $comprobante['TotalImpuestosTrasladados'] !== '') {
            $impuestos = (string) $comprobante['TotalImpuestosTrasladados'];
        } else {
            // Buscar nodos Traslado y sumar los importes
            $impuestosSum = 0;
            $traslados = $xml->xpath("//*[local-name()='Traslado']");
            if ($traslados && count($traslados) > 0) {
                foreach ($traslados as $traslado) {
                    if (isset($traslado['Importe'])) {
                        $impuestosSum += (float) $traslado['Importe'];
                    }
                }
            }
            $impuestos = number_format($impuestosSum, 2, '.', '');
        }
        $data['impuestos'] = $impuestos;
        return $data;
    }
    $xmlFile = $_FILES['archivo_xml']['tmp_name'];
    $xmlContent = file_get_contents($xmlFile);
    $parsed = parseCfdiXmlString($xmlContent);
    if ($parsed) {
        $resultados[] = $parsed;
    } else {
        $errores[] = 'No se pudo procesar el XML.';
    }
    echo json_encode(['resultados' => $resultados, 'errores' => $errores]);
    exit;
}
echo json_encode(['resultados' => [], 'errores' => ['Método no permitido']]);
