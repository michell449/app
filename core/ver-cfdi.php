<?php
require_once __DIR__ . '/class/db.php';
header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'ID de CFDI inválido']);
    exit;
}

$id_cfdi = intval($_GET['id']);

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Nueva consulta: obtener CFDI y sus comisionistas
    $sql = "
        SELECT 
            c.id_cfdi,
            c.folio,
            c.fecha_emision,
            c.emisor AS emisor,
            c.rfc AS rfc,
            c.tipo AS tipo,
            c.importe,
            c.total,
            c.estado,
            cl.nombre_comercial AS cliente_nombre,
            cl.correo AS cliente_correo,
            cm.nombre AS comisionista_nombre,
            cc.porcentaje AS comisionista_porcentaje
        FROM cf_cfdis c
        LEFT JOIN sys_clientes cl ON c.id_cliente = cl.id_cliente
        LEFT JOIN com_cliente cc ON c.id_cliente = cc.id_cliente
        LEFT JOIN comisionistas cm ON cc.id_comision = cm.id_comision
        WHERE c.id_cfdi = :id_cfdi
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id_cfdi' => $id_cfdi]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows && count($rows) > 0) {
        // Tomar datos generales del CFDI
        $row = $rows[0];
        $total = floatval($row['total']);
        $importe = floatval($row['importe']);
        $comisionistas = [];
        foreach ($rows as $r) {
            if (!empty($r['comisionista_nombre'])) {
                $porcentaje = floatval($r['comisionista_porcentaje']);
                $monto = ($total * $porcentaje) / 100;
                $comisionistas[] = [
                    'nombre' => $r['comisionista_nombre'],
                    'porcentaje' => number_format($porcentaje, 2),
                    'monto' => '$' . number_format($monto, 2)
                ];
            }
        }

        // Formatear importe y total como moneda
        $cfdi_formateado = [
            'id_cfdi' => $row['id_cfdi'],
            'folio' => $row['folio'],
            'fecha_emision' => $row['fecha_emision'],
            'emisor' => $row['emisor'],
            'rfc' => $row['rfc'],
            'tipo' => $row['tipo'],
            'importe' => '$' . number_format($importe, 2),
            'total' => '$' . number_format($total, 2),
            'estado' => $row['estado'],
            'cliente_nombre' => $row['cliente_nombre'],
            'cliente_correo' => $row['cliente_correo'] ?? '',
            'comisionistas' => $comisionistas
        ];
        echo json_encode(['success' => true, 'cfdi' => $cfdi_formateado]);
    } else {
        echo json_encode(['success' => false, 'error' => 'CFDI no encontrado']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error al consultar CFDI: ' . $e->getMessage()]);
}
?>