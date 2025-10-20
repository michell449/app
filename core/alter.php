<?php
// core/alter-tabla-conversacion-usuario.php
// Corrige el nombre de la tabla a sys_conversacion_usuarios si fue creada como sys_conversacion_usuario
require_once __DIR__ . '/class/db.php';
$database = new Database();
$db = $database->getConnection();

try {
    // Renombrar la tabla si existe con el nombre incorrecto
    $sql = "RENAME TABLE sys_conversacion_usuario TO sys_conversacion_usuarios";
    $db->exec($sql);
    echo json_encode(['success' => true, 'message' => 'Tabla renombrada correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
