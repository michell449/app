<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

// Crear la conexión PDO usando tu clase Database
$db = new Database();
$pdo = $db->getConnection();

class BitacoraController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function guardar($data) {
        $sql = "INSERT INTO us_bitacora (
            empresa, direccion, fecha, hora_inicio, responsable, tema_principal, objetivo_general, participantes, elaborada_por, aprobada_por, descripcion
        ) VALUES (
            :empresa, :direccion, :fecha, :hora_inicio, :responsable, :tema_principal, :objetivo_general, :participantes, :elaborada_por, :aprobada_por, :descripcion
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':empresa', $data['empresa']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':fecha', $data['fecha']);
        $stmt->bindParam(':hora_inicio', $data['hora_inicio']);
        $stmt->bindParam(':responsable', $data['responsable']);
        $stmt->bindParam(':tema_principal', $data['tema_principal']);
        $stmt->bindParam(':objetivo_general', $data['objetivo_general']);
        $stmt->bindParam(':participantes', $data['participantes']);
        $stmt->bindParam(':elaborada_por', $data['elaborada_por']);
        $stmt->bindParam(':aprobada_por', $data['aprobada_por']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        return $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // require_once 'conexion.php';
    // $pdo = new PDO(...);

    $controller = new BitacoraController($pdo);

    $participantes = isset($_POST['participantes']) ? json_encode($_POST['participantes']) : '';

    $data = [
        'empresa'          => $_POST['empresa'] ?? '',
        'direccion'        => $_POST['direccion'] ?? '',
        'fecha'            => $_POST['fecha'] ?? '',
        'hora_inicio'      => $_POST['hora_inicio'] ?? '',
        'responsable'      => $_POST['responsable'] ?? '',
        'tema_principal'   => $_POST['tema_principal'] ?? '',
        'objetivo_general' => $_POST['objetivo_general'] ?? '',
        'participantes'    => $participantes,
        'elaborada_por'    => $_POST['elaborada_por'] ?? '',
        'aprobada_por'     => $_POST['aprobada_por'] ?? '',
        'descripcion'      => $_POST['descripcion'] ?? ''
    ];

    if ($controller->guardar($data)) {
        echo "Registro guardado correctamente.";
    } else {
        echo "Error al guardar el registro.";
    }
}
?>