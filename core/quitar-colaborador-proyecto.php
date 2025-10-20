<?php
require_once __DIR__ . '/class/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proyecto = isset($_POST['id_proyecto']) ? intval($_POST['id_proyecto']) : null;
    $id_colab = isset($_POST['id_colab']) ? intval($_POST['id_colab']) : null;
    if ($id_proyecto && $id_colab) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare('DELETE FROM proy_colabproyectos WHERE id_proyecto = ? AND id_colab = ?');
        $stmt->execute([$id_proyecto, $id_colab]);
        header('Location: /app/panel?pg=ver-proy&id=' . $id_proyecto . '#custom-tabs-one-home');
        exit;
    }
}
header('Location: /app/panel?pg=ver-proy&id=' . ($id_proyecto ?? 1) . '#custom-tabs-one-home');
exit;
