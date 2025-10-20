<?php
require_once __DIR__ . '/../core/class/db.php';
header('Content-Type: application/json');

$db = new Database();
$conn = $db->getConnection();

$id_colab = $_POST['id_colab'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$departamento = $_POST['departamento'] ?? '';
$area = $_POST['area'] ?? '';

if ($id_colab) {
    try {
        $sql1 = "UPDATE sys_colaboradores SET nombre=?, apellidos=?, correo=?, telefono=?, departamento=?, area=? WHERE id_colab=?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute([$nombre, $apellidos, $correo, $telefono, $departamento, $area, $id_colab]);

        $sql2 = "SELECT id_usuario FROM sys_colaboradores WHERE id_colab=?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$id_colab]);
        $row = $stmt2->fetch(PDO::FETCH_ASSOC);
        if ($row && isset($row['id_usuario'])) {
            $id_usuario = $row['id_usuario'];
            $sql3 = "UPDATE us_usuarios SET nombre=?, apellido=?, email=?, password=?, telefono=? WHERE id_usuario=?";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->execute([$nombre, $apellidos, $correo, $contrasena, $telefono, $id_usuario]);
        }
        echo json_encode([
            'success' => true,
            'message' => 'Perfil actualizado correctamente.'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error:ID de colaborador no recibido.'
    ]);
}
?>