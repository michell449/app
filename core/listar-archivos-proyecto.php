<?php
require_once __DIR__ . '/class/db.php';

$proyecto_id = $_GET['id'] ?? 1;
$db = new Database();
$conn = $db->getConnection();

// Obtener archivos con información de categoría e institución
$sql = "SELECT 
    a.*,
    c.nombre as categoria_nombre,
    i.nombre as institucion_nombre
FROM arch_archivos a
LEFT JOIN arch_categorias c ON a.id_categoria = c.id_categoria
LEFT JOIN sys_instituciones i ON a.id_institucion = i.id_institucion
WHERE a.id_proyecto = ?
ORDER BY a.nombre ASC";

$stmt = $conn->prepare($sql);
$stmt->execute([$proyecto_id]);
$archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
if (is_array($archivos) && count($archivos) > 0):
  foreach ($archivos as $archivo):
    echo '<tr>';
    echo '<td>' . htmlspecialchars($archivo['nombre']) . '</td>';
    echo '<td>' . htmlspecialchars($archivo['descripcion'] ?? 'Sin descripción') . '</td>';
    echo '<td>' . htmlspecialchars($archivo['categoria_nombre'] ?? 'Sin categoría') . '</td>';
    echo '<td>' . htmlspecialchars($archivo['tipo_mime']) . '</td>';
    echo '<td>' . htmlspecialchars($archivo['institucion_nombre'] ?? 'Sin institución') . '</td>';
    echo '<td>';
    echo '<a href="core/arch-preview-proyecto.php?ruta=' . urlencode($archivo['ruta_archivo']) . '" class="btn btn-sm btn-info" target="_blank" title="Ver archivo">Ver</a> ';
    echo '<a href="core/arch-descargar-proyecto.php?ruta=' . urlencode($archivo['ruta_archivo']) . '&nombre=' . urlencode($archivo['nombre']) . '" class="btn btn-sm btn-primary" title="Descargar archivo">Descargar</a>';
    echo '</td>';
    echo '</tr>';
  endforeach;
else:
  echo '<tr><td colspan="6" class="text-center text-muted">No hay archivos para este proyecto.</td></tr>';
endif;
?>
