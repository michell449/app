<?php
// drop_tablas_catalogos.php
// Elimina las tablas de catálogo para poder recrearlas con datos correctos

require_once __DIR__ . '/class/db.php';

$db = new Database();
$pdo = $db->getConnection();
if (!$pdo) {
    die('No se pudo conectar a la base de datos.');
}

$errores = [];
$tablas = [
    'cat_productos_clientes',
    'cat_productos',
    'cat_grupos'
];

foreach ($tablas as $tabla) {
    try {
        $pdo->exec("DROP TABLE IF EXISTS $tabla;");
        echo "Tabla $tabla eliminada.<br>";
    } catch (PDOException $e) {
        $errores[] = "Error al eliminar $tabla: " . $e->getMessage();
    }
}

if (empty($errores)) {
    echo 'Todas las tablas de catálogo fueron eliminadas correctamente.';
} else {
    echo 'Errores al eliminar tablas:<br>' . implode('<br>', $errores);
}
?>
