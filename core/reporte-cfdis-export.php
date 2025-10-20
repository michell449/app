<?php
// Exporta el reporte filtrado en formato CSV (compatible con Excel)
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $input = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
    $fecha_inicio = isset($input['fecha_inicio']) ? trim($input['fecha_inicio']) : '';
    $fecha_final  = isset($input['fecha_final']) ? trim($input['fecha_final']) : '';
    $cliente_id   = isset($input['cliente']) ? (int)$input['cliente'] : 0;

    $all = isset($input['all']) && (string)$input['all'] === '1';
    $fi = $fecha_inicio !== '' ? date('Y-m-d', strtotime($fecha_inicio)) : '';
    $ff = $fecha_final !== '' ? date('Y-m-d', strtotime($fecha_final)) : '';
    if ($fi !== '' && $ff !== '' && $fi > $ff) { [$fi, $ff] = [$ff, $fi]; }

    $where = 'WHERE 1=1';
    $params = [];
    if (!$all && $fi !== '') {
        $where .= ' AND c.fecha_emision >= :fi_dt';
        $params[':fi_dt'] = $fi . ' 00:00:00';
    }
    if (!$all && $ff !== '') {
        $where .= ' AND c.fecha_emision <= :ff_dt';
        $params[':ff_dt'] = $ff . ' 23:59:59';
    }
    if ($cliente_id > 0) {
        $where .= ' AND c.id_cliente = :cliente_id';
        $params[':cliente_id'] = $cliente_id;
    }

    $sql = "
        SELECT 
            cl.nombre_comercial AS cliente,
            MAX(c.rfc) AS rfc,
            COUNT(*) AS num_cfdis,
            SUM(COALESCE(c.total,0)) AS total,
            GROUP_CONCAT(DISTINCT CONCAT(cm.nombre, ' (', cc.porcentaje, '%)') SEPARATOR '\n') AS comisiones,
            SUM(CASE WHEN LOWER(c.estado) IN ('timbrado','pagado') THEN 1 ELSE 0 END) AS pagados,
            SUM(CASE WHEN LOWER(c.estado) = 'pendiente' THEN 1 ELSE 0 END) AS pendientes
        FROM cf_cfdis c
        LEFT JOIN sys_clientes cl ON cl.id_cliente = c.id_cliente
        LEFT JOIN com_cliente cc ON c.id_cliente = cc.id_cliente
        LEFT JOIN comisionistas cm ON cc.id_comision = cm.id_comision
        $where
        GROUP BY cl.nombre_comercial
        ORDER BY cl.nombre_comercial ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $filename = 'reporte_cfdis_' . $fi . '_a_' . $ff . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);

    $output = fopen('php://output', 'w');
    // Encabezados
    fputcsv($output, ['Cliente','RFC','No. CFDIs','Total','Comisiones','Pagados','Pendientes']);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['cliente'] ?? 'Sin cliente',
            $row['rfc'] ?? '',
            (int)$row['num_cfdis'],
            number_format((float)$row['total'], 2, '.', ''),
            $row['comisiones'],
            (int)$row['pagados'],
            (int)$row['pendientes'],
        ]);
    }
    fclose($output);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error al exportar: ' . $e->getMessage();
}
?>
