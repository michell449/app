<?php
header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/class/db.php';

    $db = new Database();
    $conn = $db->getConnection();

    // Leer filtros (POST preferido, fallback a GET)
    $input = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
    $fecha_inicio = isset($input['fecha_inicio']) ? trim($input['fecha_inicio']) : '';
    $fecha_final  = isset($input['fecha_final']) ? trim($input['fecha_final']) : '';
    $all          = isset($input['all']) && (string)$input['all'] === '1';
    $cliente_id   = isset($input['cliente']) ? (int)$input['cliente'] : 0; // name="cliente" en el formulario

    // Normalizar fechas a formato Y-m-d
        // Normalizar fechas si existen
        $fi = $fecha_inicio !== '' ? date('Y-m-d', strtotime($fecha_inicio)) : '';
        $ff = $fecha_final  !== '' ? date('Y-m-d', strtotime($fecha_final)) : '';

        // Si ambas fechas existen y están invertidas, intercambiar
        if ($fi !== '' && $ff !== '' && $fi > $ff) { [$fi, $ff] = [$ff, $fi]; }

        // Construir filtro base; fechas opcionales
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

    // Filtro por cliente
    if ($cliente_id > 0) {
        $where .= ' AND c.id_cliente = :cliente_id';
        $params[':cliente_id'] = $cliente_id;
    }

    // 1) Agregación por cliente para la tabla
    $sqlClientes = "
        SELECT 
            cl.id_cliente,
            COALESCE(cl.nombre_comercial, 'Sin cliente') AS cliente,
            MAX(c.rfc) AS rfc,
            COUNT(DISTINCT c.id_cfdi) AS num_cfdis,
            SUM(COALESCE(c.total,0)) AS total,
            GROUP_CONCAT(DISTINCT CONCAT(cm.nombre, ' (', cc.porcentaje, '%)') SEPARATOR '\n') AS comisiones,
            SUM(CASE WHEN LOWER(c.estado) IN ('timbrado','pagado') THEN 1 ELSE 0 END) AS pagados,
            SUM(CASE WHEN LOWER(c.estado) = 'pendiente' THEN 1 ELSE 0 END) AS pendientes,
            SUM(CASE WHEN LOWER(c.estado) = 'cancelado' THEN 1 ELSE 0 END) AS cancelados
        FROM cf_cfdis c
        LEFT JOIN sys_clientes cl ON cl.id_cliente = c.id_cliente
        LEFT JOIN com_cliente cc ON c.id_cliente = cc.id_cliente
        LEFT JOIN comisionistas cm ON cc.id_comision = cm.id_comision
        $where
        GROUP BY cl.id_cliente, cl.nombre_comercial
        ORDER BY cl.nombre_comercial ASC
    ";

    $stmt = $conn->prepare($sqlClientes);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    // Preparar datos para el frontend
    $tabla = [];
    $totales = [
        'total_cfdis' => 0,
        'total_general' => 0.0,
        'pagados' => 0,
        'pendientes' => 0,
    ];

    // Calcular totales de comisión por comisionista
    $comisionistasTotales = [];
    $totalComisionistasGlobal = 0.0;
    foreach ($rows as $r) {
        $pag = (int)$r['pagados'];
        $pen = (int)$r['pendientes'];
        $can = (int)$r['cancelados'];
        $estado_resumen = 'Mixto';
        if ($pag > 0 && $pen === 0 && $can === 0) {
            $estado_resumen = 'Pagado';
        } elseif ($pen > 0 && $pag === 0 && $can === 0) {
            $estado_resumen = 'Pendiente';
        } elseif ($pag > 0 && $pen > 0 && $can === 0) {
            $estado_resumen = 'Pagado Pendiente';
        } elseif ($can > 0 && $pag === 0 && $pen === 0) {
            $estado_resumen = 'Cancelado';
        }

        // Calcular comisión por comisionista para este cliente
        $comisiones = $r['comisiones'];
        $totalCliente = (float)$r['total'];
        if ($comisiones) {
            $lista = explode("\n", $comisiones);
            foreach ($lista as $comi) {
                if (preg_match('/^(.*?) \((\d+(?:\.\d+)?)%\)$/', $comi, $m)) {
                    $nombre = $m[1];
                    $porcentaje = (float)$m[2];
                    $comisionMonto = $totalCliente * $porcentaje / 100;
                    if (!isset($comisionistasTotales[$nombre])) $comisionistasTotales[$nombre] = 0.0;
                    $comisionistasTotales[$nombre] += $comisionMonto;
                    $totalComisionistasGlobal += $comisionMonto;
                }
            }
        }

        $tabla[] = [
            'cliente_id' => (int)$r['id_cliente'],
            'cliente' => $r['cliente'],
            'rfc' => $r['rfc'],
            'num_cfdis' => (int)$r['num_cfdis'],
            'total' => $totalCliente,
            'comisiones' => $comisiones,
            'estado_resumen' => $estado_resumen,
            'pagados' => $pag,
            'pendientes' => $pen,
            'cancelados' => $can,
        ];

        $totales['total_cfdis'] += (int)$r['num_cfdis'];
        $totales['total_general'] += $totalCliente;
        $totales['pagados'] += $pag;
        $totales['pendientes'] += $pen;
    }

    // 2) Series para gráficas de tendencias por día
    $sqlSeries = "
        SELECT 
            DATE(c.fecha_emision) AS dia,
            SUM(CASE WHEN LOWER(c.estado) IN ('timbrado','pagado') THEN 1 ELSE 0 END) AS pagados,
            SUM(CASE WHEN LOWER(c.estado) = 'pendiente' THEN 1 ELSE 0 END) AS pendientes
        FROM cf_cfdis c
        $where
        GROUP BY DATE(c.fecha_emision)
        ORDER BY DATE(c.fecha_emision) ASC
    ";

    $stmt2 = $conn->prepare($sqlSeries);
    $stmt2->execute($params);
    $series = $stmt2->fetchAll(PDO::FETCH_ASSOC) ?: [];

    $labels = [];
    $seriePagados = [];
    $seriePendientes = [];
    foreach ($series as $s) {
        $labels[] = $s['dia'];
        $seriePagados[] = (int)$s['pagados'];
        $seriePendientes[] = (int)$s['pendientes'];
    }

    // 3) Comisiones por socio (eliminado, solo mostrar comisionistas)
    // Si se requiere mostrar la lista de comisionistas globales, se puede agregar aquí:
    $sqlComisionistas = "
        SELECT cm.id_comision, cm.nombre
        FROM comisionistas cm
        ORDER BY cm.nombre ASC
    ";
    $stmtCom = $conn->query($sqlComisionistas);
    $comisionistasRows = $stmtCom->fetchAll(PDO::FETCH_ASSOC) ?: [];
    $comisionistasLabels = [];
    $comisionistasTotalesArray = [];
    foreach ($comisionistasRows as $cm) {
        $comisionistasLabels[] = $cm['nombre'];
        $comisionistasTotalesArray[] = isset($comisionistasTotales[$cm['nombre']]) ? round($comisionistasTotales[$cm['nombre']],2) : 0.0;
    }

    $respuesta = [
        'success' => true,
        'filtros' => [
              'fecha_inicio' => $fi,
              'fecha_final' => $ff,
            'cliente' => $cliente_id,
        ],
        'rows' => $tabla,
        'resumen' => [
            'total_cfdis' => $totales['total_cfdis'],
            'total_general' => $totales['total_general'],
            'total_comisionistas' => round($totalComisionistasGlobal,2),
        ],
        'graficas' => [
            'pagados' => $totales['pagados'],
            'pendientes' => $totales['pendientes'],
            'fechas' => $labels,
            'pagados_tendencia' => $seriePagados,
            'pendientes_tendencia' => $seriePendientes,
            'comisionistas' => $comisionistasLabels,
            'comisionistas_totales' => $comisionistasTotalesArray,
        ],
    ];

    echo json_encode($respuesta);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error en reporte-cfdis-controller: ' . $e->getMessage(),
    ]);
}
?>
