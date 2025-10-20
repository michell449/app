<?php
require_once __DIR__ . '/class/db.php';


if (!function_exists('ls_html_escape')) {
  function ls_html_escape(string $v): string
  {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
  }
}

try {
  $db = (new Database())->getConnection();

  $cols = [];
  try {
    $cols = $db->query('SHOW COLUMNS FROM facturas')->fetchAll(PDO::FETCH_COLUMN);
  } catch (Throwable $e) {
    error_log("Error obteniendo columnas: " . $e->getMessage());
  }


  // PAGINACIÓN 
  $porPagina = 20;
  $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
  $offset = ($pagina - 1) * $porPagina;

  // Total de facturas
  $totalFacturas = (int)$db->query('SELECT COUNT(*) FROM facturas')->fetchColumn();
  $totalPaginas = max(1, ceil($totalFacturas / $porPagina));

  $sql = "SELECT 
                uuid,
                serie,
                folio,
                fecha,
                emisor_rfc,
                emisor_nombre,
                receptor_rfc,
                receptor_nombre,
                receptor_uso_cfdi,
                subtotal,
                total,
                forma_pago,
                metodo_pago,
                xml_file,
                pdf_file
            FROM facturas
            ORDER BY fecha DESC
            LIMIT {$porPagina} OFFSET {$offset}";

  $st = $db->prepare($sql);
  $st->execute();
  $rows = $st->fetchAll(PDO::FETCH_ASSOC);

  if (!$rows) {
    echo '<tr><td colspan="14" class="text-muted">Sin facturas registradas</td></tr>';
  } else {
    foreach ($rows as $f) {
      $xmlFile = $f['xml_file'] ?? '';
      $pdfFile = $f['pdf_file'] ?? '';
      $xmlPath = '/../../app-m/uploads/xml/' . ls_html_escape($xmlFile);
      $pdfPath = '/../../app-m/uploads/pdf/' . ls_html_escape($pdfFile);
      echo '<tr>';
      echo '<td><small>' . ls_html_escape($f['uuid'] ?? '') . '</small></td>';
      echo '<td>' . ls_html_escape($f['serie'] ?? '') . '</td>';
      echo '<td>' . ls_html_escape($f['folio'] ?? '') . '</td>';
      echo '<td><small>' . ls_html_escape($f['fecha'] ?? '') . '</small></td>';
      echo '<td><code>' . ls_html_escape($f['emisor_rfc'] ?? '') . '</code></td>';
      echo '<td><small>' . ls_html_escape($f['emisor_nombre'] ?? '') . '</small></td>';
      echo '<td><code>' . ls_html_escape($f['receptor_rfc'] ?? '') . '</code></td>';
      echo '<td><small>' . ls_html_escape($f['receptor_nombre'] ?? '') . '</small></td>';
      echo '<td>' . ls_html_escape($f['receptor_uso_cfdi'] ?? '') . '</td>';
      echo '<td>$' . number_format((float)($f['subtotal'] ?? 0), 2) . '</td>';
      echo '<td>$' . number_format((float)($f['total'] ?? 0), 2) . '</td>';
      echo '<td>' . ls_html_escape($f['forma_pago'] ?? '') . '</td>';
      echo '<td>' . ls_html_escape($f['metodo_pago'] ?? '') . '</td>';
      echo '<td>
                <a href="' . $pdfPath . '" class="text-danger" download title="Descargar PDF"> <i class="fas fa-file-pdf fa-lg"></i></a>
                <a href="' . $xmlPath . '" class="text-primary ms-2" download title="Descargar XML"> <i class="fas fa-file-code fa-lg"></i></a>
                </td>';
      echo '</tr>';
    }
  }

  // Navegación de páginas
  if ($totalPaginas > 1) {
    echo '<tr><td colspan="14" class="text-center">';
    echo '<nav><ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPaginas; $i++) {
      $active = ($i == $pagina) ? ' active' : '';
      echo '<li class="page-item' . $active . '"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
    }
    echo '</ul></nav>';
    echo '</td></tr>';
  }

} catch (Throwable $e) {
  echo '<tr><td colspan="14" class="text-danger">Error: ' . ls_html_escape($e->getMessage()) . '</td></tr>';
}
