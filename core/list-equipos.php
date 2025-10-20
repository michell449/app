<?php
// core/list-equipos.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM proy_equipos ORDER BY id_equipo DESC");
$stmt->execute();
$equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($equipos as $equipo) {
    echo '<div class="card mb-3 mx-2 d-inline-block position-relative equipo-card" data-equipo="' . $equipo['id_equipo'] . '" style="width: 270px; vertical-align:top; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 10px;">';
    echo '  <div class="card-body text-center" style="position: relative; padding-bottom: 60px;">';
    // Icono de personas centrado
    echo '    <i class="fas fa-users" style="font-size: 40px; color: #007bff; margin-top: 10px;"></i>';
    echo '    <h5 class="mt-2 mb-1 fw-bold">' . htmlspecialchars($equipo['nombre']) . '</h5>';
    echo '    <div class="mb-2" style="color:#6c757d;font-size:15px;">' . htmlspecialchars($equipo['descripcion']) . '</div>';
    echo '    <span class="badge ' . ($equipo['privacidad'] == 'Privado' ? 'bg-danger' : 'bg-success') . '" style="font-size:13px;">' . $equipo['privacidad'] . '</span>';
    // Botones en la parte inferior
    echo '    <div style="position: absolute; left: 0; right: 0; bottom: 10px; display: flex; justify-content: center; gap: 10px;">';
    echo '      <button class="btn btn-sm btn-warning" title="Editar" onclick="editarEquipo(' . $equipo['id_equipo'] . ')"><i class="fas fa-edit"></i></button>';
    echo '      <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarEquipo(' . $equipo['id_equipo'] . ')"><i class="fas fa-trash"></i></button>';
    echo '      <button class="btn btn-sm btn-primary" title="Colaboradores" onclick="abrirModalColaboradores(' . $equipo['id_equipo'] . ')"><i class="fas fa-user-plus"></i></button>';
    echo '    </div>';
    echo '  </div>';
    echo '</div>';
}
?>
