<?php
require_once __DIR__ . '/class/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proyecto = isset($_POST['id_proyecto']) ? intval($_POST['id_proyecto']) : null;
    $id_colaborador = isset($_POST['id_colaborador']) ? intval($_POST['id_colaborador']) : null;
    if ($id_proyecto && $id_colaborador) {
        $db = (new Database())->getConnection();
        // Verificar que no esté ya asignado
        $stmt = $db->prepare('SELECT COUNT(*) FROM proy_colabproyectos WHERE id_proyecto = ? AND id_colab = ?');
        $stmt->execute([$id_proyecto, $id_colaborador]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $db->prepare('INSERT INTO proy_colabproyectos (id_proyecto, id_colab) VALUES (?, ?)');
            $stmt->execute([$id_proyecto, $id_colaborador]);
        }
        header('Location: /app/panel?pg=ver-proy&id=' . $id_proyecto . '#custom-tabs-one-home');
        exit;
    }
}
header('Location: /app/panel?pg=ver-proy&id=' . ($id_proyecto ?? 1) . '#custom-tabs-one-home');
exit;
