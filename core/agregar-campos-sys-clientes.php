<?php
// Configuración de la base de datos
require_once __DIR__ . '/../core/class/db.php';

// Función para ejecutar consultas SQL con manejo de errores
function ejecutarSQL($conn, $sql, $descripcion) {
    try {
        echo "Ejecutando: $descripcion...\n";
        $result = $conn->exec($sql);
        echo "✓ Completado: $descripcion\n\n";
        return true;
    } catch (PDOException $e) {
        echo "⚠ Error en $descripcion: " . $e->getMessage() . "\n";
        // Continuar con el proceso, algunos errores son esperados (como campos que ya existen)
        return false;
    }
}

// Función para verificar si una columna existe
function columnaExiste($conn, $tabla, $columna) {
    try {
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = '$tabla' 
                AND COLUMN_NAME = '$columna'";
        $stmt = $conn->query($sql);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        echo "Error verificando columna $columna: " . $e->getMessage() . "\n";
        return false;
    }
}

try {
    // Conectar a la base de datos usando la clase Database
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "=== SCRIPT PARA AGREGAR CAMPOS FALTANTES A sys_clientes ===\n\n";
    echo "Conectado a la base de datos exitosamente\n\n";
    
    // Definir los campos que necesitamos agregar
    $camposFaltantes = [
        'comision_a' => [
            'tipo' => 'FLOAT',
            'defecto' => 'DEFAULT NULL',
            'descripcion' => 'Comisión A para el cliente'
        ],
        'comision_b' => [
            'tipo' => 'FLOAT', 
            'defecto' => 'DEFAULT NULL',
            'descripcion' => 'Comisión B para el cliente'
        ],
        'socio' => [
            'tipo' => 'VARCHAR(100)',
            'defecto' => 'DEFAULT NULL',
            'descripcion' => 'Información del socio del cliente'
        ],
        'admin_cfdis' => [
            'tipo' => 'TINYINT(1)',
            'defecto' => 'NOT NULL DEFAULT 0',
            'descripcion' => 'Permisos de administrador de CFDIs'
        ]
    ];
    
    echo "--- VERIFICANDO Y AGREGANDO CAMPOS FALTANTES ---\n";
    
    foreach ($camposFaltantes as $nombreCampo => $configuracion) {
        echo "Verificando campo: $nombreCampo...\n";
        
        if (columnaExiste($conn, 'sys_clientes', $nombreCampo)) {
            echo "  ⚪ El campo '$nombreCampo' ya existe en la tabla\n\n";
        } else {
            echo "  🔄 Agregando campo '$nombreCampo'...\n";
            
            $sql = "ALTER TABLE `sys_clientes` 
                    ADD COLUMN `$nombreCampo` {$configuracion['tipo']} {$configuracion['defecto']} 
                    COMMENT '{$configuracion['descripcion']}'";
            
            if (ejecutarSQL($conn, $sql, "Agregar campo '$nombreCampo'")) {
                echo "  ✅ Campo '$nombreCampo' agregado exitosamente\n\n";
            } else {
                echo "  ❌ Error al agregar campo '$nombreCampo'\n\n";
            }
        }
    }
    
    // Verificar la estructura final de la tabla
    echo "--- VERIFICANDO ESTRUCTURA FINAL ---\n";
    $stmt = $conn->query("DESCRIBE sys_clientes");
    $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Estructura actual de la tabla sys_clientes:\n";
    foreach ($columnas as $columna) {
        $key = $columna['Key'] ? " [{$columna['Key']}]" : "";
        $default = $columna['Default'] !== null ? " DEFAULT: {$columna['Default']}" : "";
        $extra = $columna['Extra'] ? " {$columna['Extra']}" : "";
        echo "- {$columna['Field']} ({$columna['Type']}) {$columna['Null']}$key$default$extra\n";
    }
    
    // Verificar que todos los campos esperados están presentes
    echo "\n--- VERIFICACIÓN DE CAMPOS REQUERIDOS ---\n";
    $camposRequeridos = array_keys($camposFaltantes);
    $camposPresentes = array_column($columnas, 'Field');
    
    $todosBien = true;
    foreach ($camposRequeridos as $campo) {
        if (in_array($campo, $camposPresentes)) {
            echo "✅ Campo '$campo' presente\n";
        } else {
            echo "❌ Campo '$campo' faltante\n";
            $todosBien = false;
        }
    }
    
    echo "\n=== PROCESO COMPLETADO ===\n";
    if ($todosBien) {
        echo "✅ Todos los campos requeridos están presentes en la tabla sys_clientes\n";
    } else {
        echo "⚠ Algunos campos no pudieron ser agregados. Revisa los errores anteriores.\n";
    }
    
    echo "\nCampos agregados en esta ejecución:\n";
    echo "- comision_a: Para manejar comisiones del tipo A (FLOAT, permite NULL)\n";
    echo "- comision_b: Para manejar comisiones del tipo B (FLOAT, permite NULL)\n";
    echo "- socio: Información del socio del cliente (VARCHAR(100), permite NULL)\n";
    echo "- admin_cfdis: Permisos de administrador para CFDIs (TINYINT(1), default 0)\n\n";
    
} catch (PDOException $e) {
    echo "ERROR CRÍTICO: " . $e->getMessage() . "\n";
    echo "El proceso fue interrumpido.\n";
    exit(1);
} catch (Exception $e) {
    echo "ERROR GENERAL: " . $e->getMessage() . "\n";
    echo "El proceso fue interrumpido.\n";
    exit(1);
}

echo "NOTA: Este script es seguro de ejecutar múltiples veces.\n";
echo "Los campos existentes no serán modificados.\n";
?>