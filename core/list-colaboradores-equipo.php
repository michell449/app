<?php
// core/list-colaboradores-equipo.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$id_equipo = isset($_GET['id_equipo']) ? intval($_GET['id_equipo']) : 0;
if ($id_equipo <= 0) {
    echo '<div class="text-danger">Equipo no válido.</div>';
    exit;
}

$stmt = $conn->prepare("SELECT c.id_colab, c.nombre, c.apellidos, ec.rol FROM proy_equiposcolab ec INNER JOIN sys_colaboradores c ON ec.id_colab = c.id_colab WHERE ec.id_equipo = ?");
$stmt->execute([$id_equipo]);
$colabs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($colabs) === 0) {
    echo '<div class="text-muted">No hay colaboradores asignados a este equipo.</div>';
} else {
    echo '<ul class="list-group">';
    foreach ($colabs as $colab) {
        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
        echo htmlspecialchars($colab['nombre'] . ' ' . $colab['apellidos']) . ' <span class="badge bg-info">' . htmlspecialchars($colab['rol']) . '</span>';
        echo '<button class="btn btn-sm btn-danger ms-2" onclick="quitarColaborador(' . $id_equipo . ',' . $colab['id_colab'] . ')"><i class="fas fa-trash"></i></button>';
        echo '</li>';
    }
    echo '</ul>';
}
?>
