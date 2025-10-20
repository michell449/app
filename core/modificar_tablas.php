<?php
require_once __DIR__ . '/../core/class/db.php';
$db = (new Database())->getConnection();

// Agregar nuevas columnas a la tabla sys_contactos

$sql1 = "ALTER TABLE sys_contactos ADD COLUMN correo VARCHAR(150)";
$sql2 = "ALTER TABLE sys_contactos ADD COLUMN direccion TEXT";
$sql3 = "ALTER TABLE sys_contactos ADD COLUMN puesto VARCHAR(100)";
$sql4 = "ALTER TABLE sys_contactos ADD COLUMN departamento VARCHAR(100)";
$sql5 = "ALTER TABLE sys_contactos ADD COLUMN fecha_registro DATETIME";
$sql6 = "ALTER TABLE sys_contactos ADD COLUMN activo TINYINT(1) DEFAULT 1";

// Array con las consultas SQL y sus descripciones
$consultas = [
    ['sql' => $sql1, 'descripcion' => 'Columna correo agregada correctamente'],
    ['sql' => $sql2, 'descripcion' => 'Columna direccion agregada correctamente'],
    ['sql' => $sql3, 'descripcion' => 'Columna puesto agregada correctamente'],
    ['sql' => $sql4, 'descripcion' => 'Columna departamento agregada correctamente'],
    ['sql' => $sql5, 'descripcion' => 'Columna fecha_registro agregada correctamente'],
    ['sql' => $sql6, 'descripcion' => 'Columna activo agregada correctamente']
];

// Ejecutar cada consulta individualmente
foreach ($consultas as $consulta) {
    try {
        $db->exec($consulta['sql']);
        echo "✅ " . $consulta['descripcion'] . "<br>";
    } catch (PDOException $e) {
        echo "❌ Error al ejecutar: " . $consulta['descripcion'] . " - " . $e->getMessage() . "<br>";
    }
}

echo "<br>🎉 Proceso de modificación de tablas completado.";
?>