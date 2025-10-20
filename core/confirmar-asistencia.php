
<?php
require_once __DIR__ . '/class/db.php';

$id_cita = isset($_POST['id_cita']) ? intval($_POST['id_cita']) : (isset($_GET['id_cita']) ? intval($_GET['id_cita']) : 0);
$id_contacto = isset($_POST['id_contacto']) ? intval($_POST['id_contacto']) : (isset($_GET['id_contacto']) ? intval($_GET['id_contacto']) : 0);
$error = '';
if ($id_cita <= 0 || $id_contacto <= 0) {
    $error = 'Faltan parámetros o son inválidos.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $asistira = isset($_POST['asistira']) ? intval($_POST['asistira']) : 0;
    // Debug: mostrar valores recibidos
    $debug = "<div style='background:#ffe;padding:10px;margin-bottom:10px;border:1px solid #fc0;'>";
    $debug .= "<strong>Debug:</strong><br>id_cita: $id_cita<br>id_contacto: $id_contacto<br>asistira: $asistira<br></div>";
    $db = new Database();
    $conn = $db->getConnection();
    if (!$conn) {
        $error = 'Error de conexión a la base de datos';
    } else {
        try {
            $sql = "UPDATE citas_citas SET asistira=? WHERE id_cita=? AND id_contacto=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$asistira, $id_cita, $id_contacto]);
            $debug .= "<strong>SQL:</strong> $sql<br>";
            $debug .= "<strong>RowCount:</strong> " . $stmt->rowCount() . "<br>";
            if ($stmt->rowCount() > 0) {
                $error = '';
                $mensaje = '¡Gracias! Tu respuesta ha sido registrada.';
            } else {
                $error = 'Error al registrar tu respuesta.';
            }
        } catch (Exception $e) {
            $error = 'Error: ' . htmlspecialchars($e->getMessage());
            $debug .= "<strong>Exception:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
        }
        $conn = null;
    }
    // Mostrar solo el mensaje de confirmación y debug con SweetAlert
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Confirmación</title>';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></head><body>';
    echo '<script>';
    if ($error) {
        echo 'Swal.fire({icon:"error",title:"Error",text:"' . addslashes($error) . '"}).then(() => { window.close(); });';
    } else {
        echo 'Swal.fire({icon:"success",title:"¡Gracias!",text:"' . addslashes($mensaje) . '"}).then(() => { window.close(); });';
    }
    echo '</script>';
    echo '</body></html>';
    exit;
}

// Mostrar la interfaz HTML separada
// Asegurar que las variables estén disponibles para el diseño
$vars = [
    'id_cita' => $id_cita,
    'id_contacto' => $id_contacto,
    'error' => $error
];
extract($vars);
include dirname(__DIR__) . '/pages/confirmar-asistencia.inc.php';
