<?php
require_once __DIR__ . '/class/db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Obtener parámetros de filtrado
    $folio = isset($_GET['folio']) ? trim($_GET['folio']) : '';
    $emisor = isset($_GET['emisor']) ? trim($_GET['emisor']) : '';
    $rfc = isset($_GET['rfc']) ? trim($_GET['rfc']) : '';
    $tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';
    $fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
    
    // Consulta base sin GROUP_CONCAT, para obtener los datos y luego procesar comisionistas por CFDI
    $sql = "
        SELECT 
            c.id_cfdi,
            c.folio,
            c.fecha_emision,
            c.emisor AS emisor,
            c.rfc AS rfc,
            c.efos_alert AS efos_alert,
            c.tipo,
            c.importe,
            c.total,
            c.estado,
            c.comprobante AS comprobante,
            cl.nombre_comercial AS cliente_nombre,
            cm.nombre AS comisionista_nombre,
            cc.porcentaje AS comisionista_porcentaje
        FROM cf_cfdis c
        LEFT JOIN sys_clientes cl ON c.id_cliente = cl.id_cliente
        LEFT JOIN com_cliente cc ON c.id_cliente = cc.id_cliente
        LEFT JOIN comisionistas cm ON cc.id_comision = cm.id_comision
        WHERE 1=1
    ";
    
    $params = [];
    
    // Agregar filtros dinámicamente
    if (!empty($folio)) {
        $sql .= " AND c.folio LIKE :folio";
        $params[':folio'] = "%{$folio}%";
    }
    
    if (!empty($emisor)) {
        $sql .= " AND c.emisor LIKE :emisor";
        $params[':emisor'] = "%{$emisor}%";
    }
    
    if (!empty($rfc)) {
        $sql .= " AND c.rfc LIKE :rfc";
        $params[':rfc'] = "%{$rfc}%";
    }
    
    if (!empty($tipo)) {
        $sql .= " AND c.tipo = :tipo";
        $params[':tipo'] = $tipo;
    }
    
    if (!empty($fecha)) {
        $sql .= " AND DATE(c.fecha_emision) = :fecha";
        $params[':fecha'] = $fecha;
    }
    
    $sql .= " ORDER BY c.fecha_emision DESC, c.id_cfdi DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Agrupar los CFDIs y sus comisionistas
    $cfdiMap = [];
    foreach ($rows as $row) {
        $id = $row['id_cfdi'];
        if (!isset($cfdiMap[$id])) {
            $cfdiMap[$id] = [
                'id_cfdi' => $row['id_cfdi'],
                'folio' => $row['folio'],
                'serie' => isset($row['serie']) ? $row['serie'] : '',
                'fecha_emision' => $row['fecha_emision'],
                'emisor' => $row['emisor'],
                'rfc' => $row['rfc'],
                'tipo' => $row['tipo'],
                'importe' => $row['importe'],
                'total' => $row['total'],
                'estado' => $row['estado'],
                'cliente_nombre' => $row['cliente_nombre'],
                'comprobante' => isset($row['comprobante']) ? $row['comprobante'] : '',
                    'efos_alert' => isset($row['efos_alert']) ? $row['efos_alert'] : 0,
                'comisionistas' => []
            ];
        }
        if (!empty($row['comisionista_nombre'])) {
            $cfdiMap[$id]['comisionistas'][] = [
                'nombre' => $row['comisionista_nombre'],
                'porcentaje' => $row['comisionista_porcentaje']
            ];
        }
    }
    $cfdis = array_values($cfdiMap);
    
    // Generar filas HTML
    if (count($cfdis) > 0) {
        foreach ($cfdis as $cfdi) {
            $trClass = '';
            // Determinar badge del tipo
            $tipoBadge = '';
            switch ($cfdi['tipo']) {
                case 'I':
                    $tipoBadge = '<span class="badge bg-success text-white">Ingreso</span>';
                    break;
                case 'E':
                    $tipoBadge = '<span class="badge bg-danger text-white">Egreso</span>';
                    break;
                case 'T':
                    $tipoBadge = '<span class="badge bg-secondary text-white">Traslado</span>';
                    break;
                case 'P':
                    $tipoBadge = '<span class="badge bg-info text-white">Pago</span>';
                    break;
                case 'N':
                    $tipoBadge = '<span class="badge bg-primary text-white">Nómina</span>';
                    break;
                default:
                    $tipoBadge = '<span class="badge bg-light text-dark">' . htmlspecialchars($cfdi['tipo']) . '</span>';
            }

            // Determinar badge del estado, mostrar 'EFO' si tiene alerta EFOS
            if (!empty($cfdi['efos_alert']) || strtolower($cfdi['estado']) === 'efo') {
                $estadoBadge = '<span class="badge bg-danger text-white">EFO</span>';
                $trClass = ' class="table-danger"';
            } else {
                switch (strtolower($cfdi['estado'])) {
                    case 'timbrado':
                    case 'pagado':
                        $estadoBadge = '<span class="badge bg-success text-white">Pagado</span>';
                        break;
                    case 'pendiente':
                        $estadoBadge = '<span class="badge bg-warning text-dark">Pendiente</span>';
                        break;
                    case 'cancelado':
                        $estadoBadge = '<span class="badge bg-danger text-white">Cancelado</span>';
                        break;
                    default:
                        $estadoBadge = '<span class="badge bg-secondary text-white">' . htmlspecialchars($cfdi['estado']) . '</span>';
                }
            }

            // Folio completo
            $folioCompleto = !empty($cfdi['serie']) ? $cfdi['serie'] . $cfdi['folio'] : $cfdi['folio'];

            // Formatear montos
            $totalImpuestos = isset($cfdi['importe']) ? '$' . number_format((float)$cfdi['importe'], 2) : '$0.00';
            $total = isset($cfdi['total']) ? '$' . number_format((float)$cfdi['total'], 2) : '$0.00';

            // Botones de acciones
            $acciones = '<div class="btn-group" role="group">';
            // Botón para ver CFDI, asegurando el evento onclick correcto
            $acciones .= '<button class="btn btn-sm btn-info" title="Ver CFDI" onclick="verCFDI(' . $cfdi['id_cfdi'] . ')"><i class="fas fa-eye"></i></button>';
            $acciones .= '<button class="btn btn-sm btn-warning" title="Enviar por correo" onclick="enviarCorreoCFDI(' . $cfdi['id_cfdi'] . ')"><i class="fas fa-envelope"></i></button>';
            if (empty($cfdi['comprobante'])) {
                // Botón subir comprobante solo si no existe comprobante, verde y en Acciones
                $acciones .= '<button class="btn btn-sm btn-success subir-comprobante-btn" title="Subir comprobante de pago" data-folio="' . htmlspecialchars($cfdi['folio']) . '" data-idcfdi="' . $cfdi['id_cfdi'] . '" data-bs-toggle="modal" data-bs-target="#modal-cargar-pagos"><i class="fas fa-upload"></i></button>';
            } else {
                // Botón visualizar comprobante en modal si existe
                $acciones .= '<button class="btn btn-sm btn-primary ver-comprobante-btn" title="Ver comprobante PDF" data-pdf="uploads/comprobantes/' . htmlspecialchars($cfdi['comprobante']) . '"><i class="fas fa-file-pdf"></i></button>';
            }
            $acciones .= '</div>';

            // Generar fila HTML
            // $trClass ya se define arriba para EFO, si no es EFO, se mantiene como estaba
            echo '<tr' . $trClass . '>';
            echo '<td>' . date('Y-m-d', strtotime($cfdi['fecha_emision'])) . '</td>';
            echo '<td><strong>' . htmlspecialchars($folioCompleto) . '</strong></td>';
            echo '<td>' . htmlspecialchars($cfdi['emisor']) . '</td>';
            echo '<td>' . htmlspecialchars($cfdi['rfc']) . '</td>';
            echo '<td>' . $tipoBadge . '</td>';
            echo '<td>' . $totalImpuestos . '</td>';
            echo '<td><strong>' . $total . '</strong></td>';

            // Comisiones en una sola columna
            if (!empty($cfdi['comisionistas'])) {
                $comisiones = array_map(function($comi) {
                    return htmlspecialchars($comi['nombre']) . ' (' . number_format((float)$comi['porcentaje'], 2) . '%)';
                }, $cfdi['comisionistas']);
                echo '<td>' . implode('<br>', $comisiones) . '</td>';
            } else {
                echo '<td><span class="text-muted">Sin comisión</span></td>';
            }

            // ...eliminar columna de alerta EFOS...

            echo '<td>' . $estadoBadge . '</td>';
            echo '<td>' . $acciones . '</td>';
            echo '</tr>';
        }
    } else {
        // No hay CFDIs
        echo '<tr>';
    echo '<td colspan="12" class="text-center text-muted">';
        echo '<i class="fas fa-info-circle"></i><br>';
        echo 'No hay CFDIs cargados';
        echo '</td>';
        echo '</tr>';
    }
    
} catch (Exception $e) {
    // Error
    echo '<tr>';
    echo '<td colspan="12" class="text-center text-danger">';
    echo '<i class="fas fa-exclamation-triangle"></i><br>';
    echo 'Error al cargar CFDIs: ' . htmlspecialchars($e->getMessage());
    echo '</td>';
    echo '</tr>';
}
?>