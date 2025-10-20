<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$proyecto_id = $_GET['id_proyecto'] ?? $_GET['id'] ?? 1;
$db = new Database();
$conn = $db->getConnection();

// Obtener archivos del proyecto
$stmt = $conn->prepare("
    SELECT a.*, i.nombre as nombre_institucion, c.nombre as nombre_categoria 
    FROM arch_archivos a 
    LEFT JOIN sys_instituciones i ON a.id_institucion = i.id_institucion
    LEFT JOIN arch_categorias c ON a.id_categoria = c.id_categoria
    WHERE a.id_proyecto = ?
    ORDER BY a.id_archivo DESC
");
$stmt->execute([$proyecto_id]);
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (is_array($archivos) && count($archivos) > 0):
  foreach ($archivos as $archivo):
    $nombre_institucion = $archivo['nombre_institucion'] ?? 'N/A';
    $nombre_categoria = $archivo['nombre_categoria'] ?? 'N/A';
    
    echo '<tr>';
    echo '<td>' . htmlspecialchars($archivo['nombre']) . '</td>';
    echo '<td>' . htmlspecialchars($archivo['descripcion'] ?? '') . '</td>';
    echo '<td>' . htmlspecialchars($nombre_categoria) . '</td>';
    echo '<td>' . htmlspecialchars($archivo['tipo_mime']) . '</td>';
    echo '<td>' . htmlspecialchars($nombre_institucion) . '</td>';
    echo '<td>';
    
    // Usar la ruta completa almacenada en la base de datos
    $ruta_archivo = $archivo['ruta_archivo'];
    
    echo '<a href="core/arch-preview-proyecto.php?ruta=' . urlencode($ruta_archivo) . '" class="btn btn-sm btn-info" target="_blank">Ver</a> ';
    echo '<a href="core/arch-descargar-proyecto.php?ruta=' . urlencode($ruta_archivo) . '&nombre=' . urlencode($archivo['nombre']) . '" class="btn btn-sm btn-primary" target="_blank">Descargar</a>';
    echo '</td>';
    echo '</tr>';
  endforeach;
else:
  echo '<tr><td colspan="6" class="text-muted">No hay archivos para este proyecto.</td></tr>';
endif;
?>