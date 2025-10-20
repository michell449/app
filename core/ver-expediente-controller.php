<?php
// core/ver-expediente-controller.php
// Obtener el id_expediente desde la URL
$id_expediente = 0;
if (isset($_GET['expediente'])) {
    $id_expediente = intval($_GET['expediente']);
} elseif (isset($_GET['id_expediente'])) {
    $id_expediente = intval($_GET['id_expediente']);
}
require_once __DIR__ . '/class/db.php';
$db = new Database();
$conn = $db->getConnection();
$expediente = null;
$expedienteDatos = [
    'tipo_asunto' => '-',
    'numero_expediente' => '-',
    'materia' => '-',
    'parte' => '-',
    'organo_jur' => '-',
    'fecha_creacion' => '-',
    'cliente' => '-',
    'nombre_comercial' => '-',
    'demandante' => '-',
    'expediente_unico' => '-', // Cambiar de 'neun' a 'expediente_unico'
];
if ($conn && $id_expediente) {
    $sql = "SELECT e.*, c.nombre_comercial FROM exp_expedientes e LEFT JOIN sys_clientes c ON e.cliente = c.id_cliente WHERE e.id_expediente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_expediente]);
    $expediente = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($expediente) {
        foreach ($expedienteDatos as $campo => $def) {
            $expedienteDatos[$campo] = htmlspecialchars($expediente[$campo] ?? '-', ENT_QUOTES, 'UTF-8');
        }
        // Agregar id_expediente para el formulario y scripts
        $expedienteDatos['id_expediente'] = isset($expediente['id_expediente']) ? intval($expediente['id_expediente']) : 0;
    }
}
