<?php

// Detectar si la petición es AJAX/JSON o HTML
$isJson = (
    (isset($_GET['format']) && $_GET['format'] === 'json') ||
    (isset($_POST['format']) && $_POST['format'] === 'json')
);

if ($isJson) {
    header('Content-Type: application/json');
}

include_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conexion = $db->getConnection();

try {
    // Obtener filtros del POST o GET
    $input = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
    $folio = isset($input['folio']) ? trim($input['folio']) : '';
    $emisor = isset($input['emisor']) ? trim($input['emisor']) : '';
    $rfc = isset($input['rfc']) ? trim($input['rfc']) : '';
    $tipo = isset($input['tipo']) ? trim($input['tipo']) : '';
    $fecha = isset($input['fecha']) ? trim($input['fecha']) : '';
    $cliente_id = isset($input['cliente_id']) ? intval($input['cliente_id']) : 0;
    $estado = isset($input['estado']) ? trim($input['estado']) : '';

    // Mapeo de descripciones a códigos de tipo
    $tipo_map = [
        'Ingreso' => 'I',
        'Egreso' => 'E',
        'Traslado' => 'T',
        'Pago' => 'P',
        'Nómina' => 'N',
        'Nomina' => 'N',
    ];
    if (isset($tipo_map[$tipo])) {
        $tipo = $tipo_map[$tipo];
    }
    
    // Consulta: obtener CFDIs y solo las comisiones del cliente, agrupadas por CFDI
    $sql = "SELECT 
        c.id_cfdi,
        c.folio,
        c.fecha_emision,
        c.emisor,
        c.rfc,
        c.tipo,
        c.importe,
        c.total,
        c.estado,
        cl.nombre_comercial as cliente_nombre,
        (
            SELECT GROUP_CONCAT(CONCAT(cm.nombre, ' (', cc.porcentaje, '%)') SEPARATOR '\n')
            FROM com_cliente cc
            LEFT JOIN comisionistas cm ON cc.id_comision = cm.id_comision
            WHERE cc.id_cliente = c.id_cliente
        ) as comisiones
    FROM cf_cfdis c
    LEFT JOIN sys_clientes cl ON c.id_cliente = cl.id_cliente
    WHERE 1=1";

    $params = array();
    
    if (!empty($folio)) {
        $sql .= " AND c.folio LIKE ?";
        $params[] = "%$folio%";
    }
    if (!empty($emisor)) {
        $sql .= " AND c.emisor LIKE ?";
        $params[] = "%$emisor%";
    }
    if (!empty($rfc)) {
        $sql .= " AND c.rfc LIKE ?";
        $params[] = "%$rfc%";
    }
    if (!empty($tipo) && $tipo !== 'Todos') {
        $sql .= " AND c.tipo = ?";
        $params[] = $tipo;
    }
    if (!empty($fecha)) {
        $sql .= " AND DATE(c.fecha_emision) = ?";
        $params[] = $fecha;
    }
    if ($cliente_id > 0) {
        $sql .= " AND c.id_cliente = ?";
        $params[] = $cliente_id;
    }

    // Log temporal para depuración
    file_put_contents(__DIR__ . '/log_buscar_cfdis.txt',
        date('Y-m-d H:i:s') . "\nSQL: " . $sql . "\nPARAMS: " . print_r($params, true) . "\n\n",
        FILE_APPEND
    );
    
    if (!empty($estado)) {
        $sql .= " AND c.estado = ?";
        $params[] = $estado;
    }
    $sql .= " ORDER BY c.fecha_emision DESC, c.id_cfdi DESC";
    
    $stmt = $conexion->prepare($sql);
    if (!empty($params)) {
        $stmt->execute($params);
    } else {
        $stmt->execute();
    }
    $cfdis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mapeo de tipo a descripción
    $tipo_map_inv = [
        'I' => 'Ingreso',
        'E' => 'Egreso',
        'T' => 'Traslado',
        'P' => 'Pago',
        'N' => 'Nómina',
    ];

    if ($isJson) {
        foreach ($cfdis as &$cfdi) {
            $cfdi['tipo_descripcion'] = isset($tipo_map_inv[$cfdi['tipo']]) ? $tipo_map_inv[$cfdi['tipo']] : $cfdi['tipo'];
            $cfdi['fecha_emision_formateada'] = date('Y-m-d', strtotime($cfdi['fecha_emision']));
            $cfdi['total_formateado'] = number_format(floatval($cfdi['total'] ?? 0), 2);
            $cfdi['impuestos_formateado'] = number_format(floatval($cfdi['importe'] ?? 0), 2);
        }
        unset($cfdi);
        $respuesta = [
            'success' => true,
            'cfdis' => $cfdis,
            'total_registros' => count($cfdis),
        ];
        echo json_encode($respuesta);
        exit;
    } else {
        if (count($cfdis) > 0) {
            foreach ($cfdis as $cfdi) {
                $tipoBadge = '';
                switch ($cfdi['tipo']) {
                    case 'I': $tipoBadge = '<span class="badge bg-success text-white">Ingreso</span>'; break;
                    case 'E': $tipoBadge = '<span class="badge bg-danger text-white">Egreso</span>'; break;
                    case 'T': $tipoBadge = '<span class="badge bg-secondary text-white">Traslado</span>'; break;
                    case 'P': $tipoBadge = '<span class="badge bg-info text-white">Pago</span>'; break;
                    case 'N': $tipoBadge = '<span class="badge bg-primary text-white">Nómina</span>'; break;
                    default: $tipoBadge = '<span class="badge bg-light text-dark">' . htmlspecialchars($cfdi['tipo']) . '</span>';
                }
                $trClass = '';
                // Pintar rojo si estado es EFO o efos_alert=1
                $isEFO = (isset($cfdi['efos_alert']) && $cfdi['efos_alert'] == 1) || strtolower($cfdi['estado']) === 'efo';
                if ($isEFO) {
                    $estadoBadge = '<span class="badge bg-danger text-white">EFO</span>';
                    $trClass = ' class="table-danger"';
                } else {
                    switch (strtolower($cfdi['estado'])) {
                        case 'timbrado':
                        case 'pagado': $estadoBadge = '<span class="badge bg-success text-white">Pagado</span>'; break;
                        case 'pendiente': $estadoBadge = '<span class="badge bg-warning text-dark">Pendiente</span>'; break;
                        case 'cancelado': $estadoBadge = '<span class="badge bg-danger text-white">Cancelado</span>'; break;
                        default: $estadoBadge = '<span class="badge bg-secondary text-white">' . htmlspecialchars($cfdi['estado']) . '</span>';
                    }
                }
                $folioCompleto = $cfdi['folio'];
                $totalImpuestos = isset($cfdi['importe']) ? '$' . number_format((float)$cfdi['importe'], 2) : '$0.00';
                $total = isset($cfdi['total']) ? '$' . number_format((float)$cfdi['total'], 2) : '$0.00';
                $acciones = '<div class="btn-group" role="group">';
                // Botón para ver CFDI, asegurando el evento onclick correcto
                $acciones .= '<button class="btn btn-sm btn-info" title="Ver CFDI" onclick="verCFDI(' . $cfdi['id_cfdi'] . ')"><i class="fas fa-eye"></i></button>';
                $acciones .= '<button class="btn btn-sm btn-warning" title="Enviar por correo" onclick="enviarCorreoCFDI(' . $cfdi['id_cfdi'] . ')"><i class="fas fa-envelope"></i></button>';
                // Botón subir comprobante solo si no existe comprobante
                if (empty($cfdi['comprobante'])) {
                    $acciones .= '<button class="btn btn-sm btn-success subir-comprobante-btn" title="Subir comprobante de pago" data-folio="' . htmlspecialchars($cfdi['folio']) . '" data-idcfdi="' . $cfdi['id_cfdi'] . '" data-bs-toggle="modal" data-bs-target="#modal-cargar-pagos"><i class="fas fa-upload"></i></button>';
                } else {
                    // Botón visualizar comprobante en modal si existe
                    $acciones .= '<button class="btn btn-sm btn-primary ver-comprobante-btn" title="Ver comprobante PDF" data-pdf="uploads/comprobantes/' . htmlspecialchars($cfdi['comprobante']) . '"><i class="fas fa-file-pdf"></i></button>';
                }
                $acciones .= '</div>';
                echo '<tr' . $trClass . '>';
                echo '<td>' . date('Y-m-d', strtotime($cfdi['fecha_emision'])) . '</td>';
                echo '<td><strong>' . htmlspecialchars($folioCompleto) . '</strong></td>';
                echo '<td>' . htmlspecialchars($cfdi['emisor']) . '</td>';
                echo '<td>' . htmlspecialchars($cfdi['rfc']) . '</td>';
                echo '<td>' . $tipoBadge . '</td>';
                echo '<td>' . $totalImpuestos . '</td>';
                echo '<td><strong>' . $total . '</strong></td>';
                // Comisiones en una sola columna
                if (!empty($cfdi['comisiones'])) {
                    echo '<td>' . nl2br(htmlspecialchars($cfdi['comisiones'])) . '</td>';
                } else {
                    echo '<td><span class="text-muted">Sin comisión</span></td>';
                }
                echo '<td>' . $estadoBadge . '</td>';
                echo '<td>' . $acciones . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td colspan="11" class="text-center text-muted">';
            echo '<i class="fas fa-info-circle"></i><br>';
            echo 'No hay CFDIs cargados';
            echo '</td>';
            echo '</tr>';
        }
        exit;
    }
    
} catch (Exception $e) {
    error_log("Error en buscar-cfdis.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Error al buscar CFDI\'s: ' . $e->getMessage(),
        'cfdis' => [],
        'total_registros' => 0
    ]);
}
?>