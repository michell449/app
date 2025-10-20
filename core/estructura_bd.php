<?php
// estructura_bd.php
// Muestra la estructura de la base de datos: tablas, columnas, tipos, llaves

$host = 'db5018526164.hosting-data.io';
$user = 'dbu3200166';
$pass = '@SysRJE*2025';
$db   = 'dbs14710504';
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

// Obtener todas las tablas
$tables = [];
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}

foreach ($tables as $table) {
    echo "<h2>Tabla: $table</h2>";
    echo "<table border='1' cellpadding='4'><tr><th>Columna</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Defecto</th><th>Extra</th></tr>";
    $cols = $conn->query("SHOW COLUMNS FROM `$table`");
    while ($col = $cols->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    // Mostrar llaves foráneas
    $fk = $conn->query("SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='$db' AND TABLE_NAME='$table' AND REFERENCED_TABLE_NAME IS NOT NULL");
    if ($fk->num_rows > 0) {
        echo "<b>Llaves foráneas:</b><ul>";
        while ($f = $fk->fetch_assoc()) {
            echo "<li>{$f['COLUMN_NAME']} → {$f['REFERENCED_TABLE_NAME']}({$f['REFERENCED_COLUMN_NAME']})</li>";
        }
        echo "</ul>";
    }
}
$conn->close();
?>
