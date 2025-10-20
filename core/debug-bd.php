<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';
session_start();

echo '<h2>Debug Base de Datos del Servidor</h2>';
echo '<style>
.debug { background: #f0f8ff; border: 1px solid #0066cc; padding: 10px; margin: 10px 0; }
.error { background: #ffe6e6; border: 1px solid #cc0000; padding: 10px; margin: 10px 0; }
.success { background: #e6ffe6; border: 1px solid #00cc00; padding: 10px; margin: 10px 0; }
</style>';

try {
    // 1. Verificar conexión
    echo '<div class="debug"><strong>1. Conexión a BD:</strong><br>';
    $database = new Database();
    $db = $database->getConnection();
    echo 'Conexión exitosa ✅</div>';
    
    // 2. Verificar sesión
    echo '<div class="debug"><strong>2. Sesión:</strong><br>';
    echo 'USR_ID: ' . ($_SESSION['USR_ID'] ?? 'NO DEFINIDO') . '<br>';
    echo 'id_usuario: ' . ($_SESSION['id_usuario'] ?? 'NO DEFINIDO') . '</div>';
    
    // 3. Verificar tabla archivos_directorios existe
    echo '<div class="debug"><strong>3. Tabla archivos_directorios:</strong><br>';
    $stmt = $db->prepare("SHOW TABLES LIKE 'archivos_directorios'");
    $stmt->execute();
    $tablaExiste = $stmt->rowCount() > 0;
    echo $tablaExiste ? 'Existe ✅' : 'NO EXISTE ❌';
    echo '</div>';
    
    // 4. Ver estructura de la tabla
    if ($tablaExiste) {
        echo '<div class="debug"><strong>4. Estructura tabla:</strong><br>';
        $stmt = $db->prepare("DESCRIBE archivos_directorios");
        $stmt->execute();
        $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columnas as $col) {
            echo $col['Field'] . ' (' . $col['Type'] . ')<br>';
        }
        echo '</div>';
    }
    
    // 5. Contar registros totales
    echo '<div class="debug"><strong>5. Registros totales:</strong><br>';
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM archivos_directorios");
    $stmt->execute();
    $total = $stmt->fetchColumn();
    echo "Total registros: $total</div>";
    
    // 6. Ver todos los registros (máximo 10)
    echo '<div class="debug"><strong>6. Últimos registros:</strong><br>';
    $stmt = $db->prepare("SELECT * FROM archivos_directorios ORDER BY fecha DESC LIMIT 10");
    $stmt->execute();
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($registros)) {
        echo 'No hay registros en la tabla ❌';
    } else {
        echo '<table border="1" style="width:100%; font-size:12px;">';
        echo '<tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>ID Propietario</th><th>ID Padre</th><th>Fecha</th></tr>';
        foreach ($registros as $reg) {
            echo '<tr>';
            echo '<td>' . substr($reg['id'], 0, 8) . '...</td>';
            echo '<td>' . htmlspecialchars($reg['nombre']) . '</td>';
            echo '<td>' . $reg['tipo'] . '</td>';
            echo '<td>' . $reg['id_propietario'] . '</td>';
            echo '<td>' . ($reg['idpadre'] ?? 'NULL') . '</td>';
            echo '<td>' . $reg['fecha'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    echo '</div>';
    
    // 7. Verificar registros del usuario actual
    if (isset($_SESSION['USR_ID'])) {
        $usuario_id = $_SESSION['USR_ID'];
        echo '<div class="debug"><strong>7. Registros del usuario actual (USR_ID: ' . $usuario_id . '):</strong><br>';
        $stmt = $db->prepare("SELECT * FROM archivos_directorios WHERE id_propietario = ? ORDER BY fecha DESC");
        $stmt->execute([$usuario_id]);
        $misRegistros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo 'Encontrados: ' . count($misRegistros) . ' registros<br>';
        if (!empty($misRegistros)) {
            foreach ($misRegistros as $reg) {
                echo '- ' . $reg['nombre'] . ' (' . $reg['tipo'] . ') - ' . $reg['fecha'] . '<br>';
            }
        }
        echo '</div>';
    }
    
    // 8. Verificar tabla us_usuarios
    echo '<div class="debug"><strong>8. Verificar tabla us_usuarios:</strong><br>';
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM us_usuarios");
    $stmt->execute();
    $totalUsuarios = $stmt->fetchColumn();
    echo "Total usuarios: $totalUsuarios<br>";
    
    if (isset($_SESSION['USR_ID'])) {
        $stmt = $db->prepare("SELECT * FROM us_usuarios WHERE id_usuario = ?");
        $stmt->execute([$_SESSION['USR_ID']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuario) {
            echo 'Usuario encontrado: ' . $usuario['nombre'] . ' ✅';
        } else {
            echo 'Usuario NO encontrado en us_usuarios ❌';
        }
    }
    echo '</div>';
    
} catch (Exception $e) {
    echo '<div class="error"><strong>ERROR:</strong><br>' . htmlspecialchars($e->getMessage()) . '</div>';
}

echo '<br><a href="javascript:history.back()" style="background:#007cba;color:white;padding:10px;text-decoration:none;">← Volver</a>';
?>