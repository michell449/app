<?php
// core/crear_tablas_drive.php

// Configura tus datos de conexión para el servidor remoto
$host = 'db5018526164.hosting-data.io';
$user = 'dbu3200166';
$pass = '@SysRJE*2025';
$db   = 'dbs14710504'; // Cambia por el nombre de tu base de datos en el servidor
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// SQL para archivos_directorios con todos los campos y relaciones
$sql1 = "CREATE TABLE IF NOT EXISTS archivos_directorios (
    id CHAR(36) NOT NULL PRIMARY KEY,
    idpadre CHAR(36) DEFAULT NULL,
    tipo ENUM('A','D') NOT NULL DEFAULT 'A',
    nombre VARCHAR(200) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_propietario INT(11) NOT NULL,
    tipo_archivo VARCHAR(20) DEFAULT NULL,
    tamano_kb INT(11) DEFAULT NULL,
    compartido TINYINT(1) NOT NULL DEFAULT 0,
    KEY idpadre (idpadre),
    KEY id_propietario (id_propietario),
    FOREIGN KEY (idpadre) REFERENCES archivos_directorios(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_propietario) REFERENCES us_usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// SQL para permisos_archivos sin llave primaria compuesta, solo índices
$sql2 = "CREATE TABLE IF NOT EXISTS permisos_archivos (
    idarchivo CHAR(36) NOT NULL,
    idusuario INT(11) NOT NULL,
    ver TINYINT(1) NOT NULL DEFAULT 0,
    descargar TINYINT(1) NOT NULL DEFAULT 0,
    actualizar TINYINT(1) NOT NULL DEFAULT 0,
    borrar TINYINT(1) NOT NULL DEFAULT 0,
    KEY idarchivo (idarchivo),
    KEY idusuario (idusuario),
    FOREIGN KEY (idarchivo) REFERENCES archivos_directorios(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (idusuario) REFERENCES us_usuarios(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Ejecutar
if ($conn->query($sql1) === TRUE) {
    echo "Tabla archivos_directorios creada correctamente.<br>";
} else {
    echo "Error creando archivos_directorios: " . $conn->error . "<br>";
}

if ($conn->query($sql2) === TRUE) {
    echo "Tabla permisos_archivos creada correctamente.<br>";
} else {
    echo "Error creando permisos_archivos: " . $conn->error . "<br>";
}

$conn->close();
?>