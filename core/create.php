<?php
require_once __DIR__ . '/class/db.php';
$database = new Database();
$db = $database->getConnection();

try {
    // Tabla sys_mensajes
    $sql1 = "CREATE TABLE IF NOT EXISTS sys_mensajes (
        id_mensaje INT(11) AUTO_INCREMENT PRIMARY KEY,
        id_conversacion INT(11) NOT NULL,
        id_usuario INT(11) DEFAULT NULL,
        fecha_publicacion DATETIME NOT NULL,
        fecha_vencimiento DATETIME DEFAULT NULL,
        status ENUM('Enviado', 'Leído', 'Archivado', 'Eliminado') NOT NULL,
        mensaje TEXT NOT NULL,
        fecha_lectura DATETIME DEFAULT NULL,
        INDEX id_conversacion (id_conversacion),
        INDEX id_usuario (id_usuario)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $db->exec($sql1);

    // Tabla sys_conversaciones
    $sql2 = "CREATE TABLE IF NOT EXISTS sys_conversaciones (
        id_conversacion INT(11) AUTO_INCREMENT PRIMARY KEY,
        asunto VARCHAR(255) DEFAULT NULL,
        fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $db->exec($sql2);

    // Tabla sys_conversacion_usuario
    $sql3 = "CREATE TABLE IF NOT EXISTS sys_conversacion_usuario (
        id_conversacion INT(11) NOT NULL,
        id_usuario INT(11) NOT NULL,
        status VARCHAR(20) DEFAULT 'Enviado',
        PRIMARY KEY (id_conversacion, id_usuario),
        INDEX id_usuario (id_usuario)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $db->exec($sql3);

    echo json_encode(['success' => true, 'message' => 'Tablas creadas correctamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
