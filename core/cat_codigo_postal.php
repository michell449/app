<?php
require_once __DIR__ . '/class/db.php';
$db = new Database();
$pdo = $db->getConnection();
if (!$pdo) {
    die('No se pudo conectar a la base de datos.');
}

$sql = "CREATE TABLE IF NOT EXISTS `cat_codigo_postal` (
    `codigo_postal` VARCHAR(10) primary key COLLATE 'utf8mb4_general_ci',
    `asentamiento` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `tipo_asentamiento` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `municipio` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `estado` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `ciudad` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
    `clave_oficina` VARCHAR(10) NOT NULL COLLATE 'utf8mb4_general_ci',
    UNIQUE INDEX `codigo_postal_UNIQUE` (`codigo_postal`) USING BTREE
) COLLATE='utf8mb4_general_ci' ENGINE=InnoDB;";

$errores = [];
try {
    $pdo->exec($sql);
} catch (PDOException $e) {
    $errores[] = $e->getMessage();
}

// insertar datos de codigos postales si la tabla está vacía

try{
    $stmt = $pdo->prepare("INSERT INTO cat_codigo_postal (codigo_postal, asentamiento, tipo_asentamiento, municipio, estado, ciudad, clave_oficina) values (?,?,?,?,?,?,?)");
    $codigos = [
        //Aguascalientes
        ['20260', 'Jesús Terán Peredo', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20260', 'Ojo de Agua', 'Colonia', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20260', 'Sidusa', 'Colonia', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20260', 'Rinconada El Cedazo', 'Condominio', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Agua Clara', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Balcones de Ojocaliente', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Cielo Claro', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Lomas del Chapulín', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Ojo de Agua de Palmitas', 'Colonia', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Salto de Ojocaliente', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Solidaridad 2a Sección', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Solidaridad 3a Sección', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Tierra Buena', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Rinconada San Antonio I', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Cima del Chapulín', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Cobano de Palmitas', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'San Jorge', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'La Lomita', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Villa las Palmas', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Bajío de las Palmas', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Lomas del Gachupín', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'El Cedazo', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'San Ángel', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20263', 'Villa Taurina', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20264', 'Morelos INFONAVIT', 'Unidad habitacional', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20264', 'Vista del Sol 2a Sección', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20264', 'Vista del Sol 3a Sección', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20264', 'Vista del Sol 1a Sección', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20265', 'Ojo de Agua INFONAVIT', 'Unidad habitacional', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20266', 'Jardines del Sol', 'Fraccionamiento', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20266', 'La Cruz', 'Colonia', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293'],
        ['20266', 'Misión de Santa Fe', 'Colonia', 'Aguascalientes', 'Aguascalientes', 'Aguascalientes', '20293']
    ];
}catch (PDOException $e) {
    $errores[] = 'cat_productos: ' . $e->getMessage();
}

if (empty($errores)) {
    echo 'Tablas y catálogos creados correctamente.';
} else {
    echo 'Errores al crear tablas o insertar datos:<br>' . implode('<br>', $errores);
}
