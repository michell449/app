<?php
// core/agregar-colaborador-equipo.php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();

$id_equipo = isset($_POST['id_equipo']) ? intval($_POST['id_equipo']) : 0;
$id_colab = isset($_POST['id_colab']) ? intval($_POST['id_colab']) : 0;
$rol = isset($_POST['rol']) ? trim($_POST['rol']) : '';

if ($id_equipo > 0 && $id_colab > 0 && $rol !== '') {
    try {
        $stmt = $conn->prepare("INSERT INTO proy_equiposcolab (id_equipo, id_colab, rol) VALUES (?, ?, ?)");
        if ($stmt->execute([$id_equipo, $id_colab, $rol])) {
            echo 'ok';
        } else {
            $errorInfo = $stmt->errorInfo();
            echo 'error: ' . json_encode($errorInfo) . ' | datos: ' . json_encode(['id_equipo'=>$id_equipo,'id_colab'=>$id_colab,'rol'=>$rol]);
        }
    } catch (PDOException $e) {
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'error: datos incompletos: ' . json_encode(['id_equipo'=>$id_equipo,'id_colab'=>$id_colab,'rol'=>$rol]);
}
?>
