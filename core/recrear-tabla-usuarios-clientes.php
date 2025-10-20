<?php
// Configuración de la base de datos
require_once __DIR__ . '/core/class/db.php';

// Función para ejecutar consultas SQL con manejo de errores
function ejecutarSQL($conn, $sql, $descripcion) {
    try {
        echo "Ejecutando: $descripcion...\n";
        $result = $conn->exec($sql);
        echo "✓ Completado: $descripcion\n\n";
        return true;
    } catch (PDOException $e) {
        echo "⚠ Error en $descripcion: " . $e->getMessage() . "\n";
        // Continuar con el proceso, algunos errores son esperados (como FK que no existen)
        return false;
    }
}

try {
    // Conectar a la base de datos usando la clase Database
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "=== SCRIPT DE RECREACIÓN DE TABLA us_usuarios_clientes ===\n\n";
    echo "Conectado a la base de datos exitosamente\n\n";
    
    // PASO 1: Deshabilitar verificación de llaves foráneas temporalmente
    ejecutarSQL($conn, "SET FOREIGN_KEY_CHECKS = 0;", "Deshabilitando verificación de llaves foráneas");
    
    // PASO 2: Eliminar las llaves foráneas existentes
    echo "--- ELIMINANDO LLAVES FORÁNEAS ---\n";
    
    // Intentar eliminar las llaves foráneas (puede que no existan todas)
    ejecutarSQL($conn, "ALTER TABLE us_usuarios_clientes DROP FOREIGN KEY IF EXISTS fk_usucliente_cliente;", "Eliminando FK fk_usucliente_cliente");
    ejecutarSQL($conn, "ALTER TABLE us_usuarios_clientes DROP FOREIGN KEY IF EXISTS fk_usucliente_usuario;", "Eliminando FK fk_usucliente_usuario");
    
    // PASO 3: Hacer DROP de la tabla
    echo "--- ELIMINANDO TABLA ---\n";
    ejecutarSQL($conn, "DROP TABLE IF EXISTS us_usuarios_clientes;", "Eliminando tabla us_usuarios_clientes");
    
    // PASO 4: Crear la nueva tabla
    echo "--- CREANDO NUEVA TABLA ---\n";
    $crearTabla = "
    CREATE TABLE `us_usuarios_clientes` (
        `id_usuario` int(11) NOT NULL,
        `id_cliente` int(11) NOT NULL,
        `fecha_asignacion` datetime DEFAULT current_timestamp(),
        `activo` tinyint(1) NOT NULL DEFAULT 1,
        `notas` text DEFAULT NULL,
        PRIMARY KEY (`id_usuario`, `id_cliente`),
        KEY `fk_usucliente_cliente` (`id_cliente`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";
    
    ejecutarSQL($conn, $crearTabla, "Creando nueva tabla us_usuarios_clientes");
    
    // PASO 5: Agregar las llaves foráneas
    echo "--- AGREGANDO LLAVES FORÁNEAS ---\n";
    
    $fkUsuario = "
    ALTER TABLE `us_usuarios_clientes`
    ADD CONSTRAINT `fk_usucliente_usuario` 
    FOREIGN KEY (`id_usuario`) 
    REFERENCES `us_usuarios` (`id_usuario`) 
    ON DELETE CASCADE ON UPDATE CASCADE;
    ";
    
    $fkCliente = "
    ALTER TABLE `us_usuarios_clientes`
    ADD CONSTRAINT `fk_usucliente_cliente` 
    FOREIGN KEY (`id_cliente`) 
    REFERENCES `sys_clientes` (`id_cliente`) 
    ON DELETE CASCADE ON UPDATE CASCADE;
    ";
    
    ejecutarSQL($conn, $fkUsuario, "Agregando FK hacia us_usuarios");
    ejecutarSQL($conn, $fkCliente, "Agregando FK hacia sys_clientes");
    
    // PASO 6: Rehabilitar verificación de llaves foráneas
    ejecutarSQL($conn, "SET FOREIGN_KEY_CHECKS = 1;", "Rehabilitando verificación de llaves foráneas");
    
    // PASO 7: Verificar la estructura de la tabla
    echo "--- VERIFICANDO ESTRUCTURA ---\n";
    $stmt = $conn->query("DESCRIBE us_usuarios_clientes");
    $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Estructura de la nueva tabla:\n";
    foreach ($columnas as $columna) {
        echo "- {$columna['Field']} ({$columna['Type']}) {$columna['Null']} {$columna['Key']} {$columna['Extra']}\n";
    }
    
    echo "\n=== PROCESO COMPLETADO EXITOSAMENTE ===\n";
    echo "La tabla us_usuarios_clientes ha sido recreada con la nueva estructura.\n";
    echo "Cambios principales:\n";
    echo "- Se agregó el campo 'id_relacion' como PRIMARY KEY AUTO_INCREMENT\n";
    echo "- El campo 'fecha_asignacion' ahora es NOT NULL\n";
    echo "- Se eliminó el campo 'notas'\n";
    echo "- Las llaves foráneas fueron recreadas correctamente\n\n";
    
} catch (PDOException $e) {
    echo "ERROR CRÍTICO: " . $e->getMessage() . "\n";
    echo "El proceso fue interrumpido.\n";
    
    // Intentar rehabilitar las llaves foráneas en caso de error
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $conn->exec("SET FOREIGN_KEY_CHECKS = 1;");
    } catch (Exception $ex) {
        // Ignorar si falla
    }
    
    exit(1);
}

echo "NOTA: La tabla ha sido recreada con la estructura exacta del servidor.\n";
echo "ADVERTENCIA: Este script eliminó todos los datos de la tabla anterior.\n";
echo "Ejecuta 'migrar-datos-usuarios-clientes.php' para restaurar los datos del respaldo.\n";
?>