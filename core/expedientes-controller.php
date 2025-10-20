<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();
$expedientes = [];
if ($conn) {
    $sql = "SELECT id_expediente, numero_expediente, materia, parte, organo_jur, tipo_asunto, fecha_creacion FROM exp_expedientes";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $expedientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
if (!empty($expedientes)) {
    foreach ($expedientes as $exp) {
        echo '<tr>';
        // Mostrar el número de expediente como está en la base de datos (formato id/año)
        echo '<td class="text-center">' . htmlspecialchars($exp['numero_expediente']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($exp['materia']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($exp['parte']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($exp['organo_jur']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($exp['tipo_asunto']) . '</td>';
        echo '<td class="text-center">' . htmlspecialchars($exp['fecha_creacion']) . '</td>';
        echo '<td class="text-center">'
            . '<a href="panel?pg=ver-expediente&expediente=' . urlencode($exp['id_expediente']) . '" class="btn btn-sm btn-primary ver-expediente" title="Ver">'
            . '<i class="bi bi-eye"></i> Ver</a>'
            . '</td>';
        echo '</tr>';
    }
} else { 
    echo '<tr><td colspan="7" class="text-center">No hay expedientes registrados.</td></tr>';
}
?>
