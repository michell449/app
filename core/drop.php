<?php
// core/drop.php - Crear tabla citas_citas

// Incluir la clase Database
require_once 'class/db.php';

// Crear instancia de la base de datos
$database = new Database();
$conn = $database->getConnection();

// Verificar conexión
if (!$conn) {
    die("Error: No se pudo establecer conexión con la base de datos");
}

// SQL para crear la tabla citas_citas con relación a citas_invitados
$sql = "CREATE TABLE IF NOT EXISTS `citas_citas` (
    `id_cita` INT(11) NOT NULL AUTO_INCREMENT,
    `id_colab` INT(11) NOT NULL,
    `asunto` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_general_ci',
    `ubicacion` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_general_ci',
    `fecha_inicio` DATETIME NOT NULL,
    `todo_dia` TINYINT(1) NOT NULL,
    `detalles` TEXT NOT NULL COLLATE 'utf8mb4_general_ci',
    `programar` DATETIME NOT NULL,
    `status` ENUM('Programada','Realizada','Cancelada','Pospuesta') NOT NULL COLLATE 'utf8mb4_general_ci',
    `duracion` TIME NULL DEFAULT NULL,
    `enviar_correo` TINYINT(1) NULL DEFAULT '0',
    PRIMARY KEY (`id_cita`) USING BTREE,
    INDEX `citas_citas_ibfk_1` (`id_colab`) USING BTREE,
    CONSTRAINT `citas_citas_ibfk_1` FOREIGN KEY (`id_colab`) REFERENCES `sys_colaboradores` (`id_colab`) ON UPDATE RESTRICT ON DELETE RESTRICT
) COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=28;";

// SQL para agregar la relación con citas_invitados (tabla intermedia)
$sql_relacion = "ALTER TABLE `citas_invitados` 
    ADD CONSTRAINT `citas_invitados_ibfk_1` 
    FOREIGN KEY (`id_cita`) REFERENCES `citas_citas` (`id_cita`) 
    ON UPDATE CASCADE ON DELETE CASCADE;";

// Ejecutar la consulta para crear la tabla
try {
    // Crear tabla citas_citas
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    echo "✅ Tabla 'citas_citas' creada correctamente.<br>";
    
    // Agregar relación con citas_invitados
    $stmt_relacion = $conn->prepare($sql_relacion);
    $stmt_relacion->execute();
    
    echo "🔗 Relación con tabla 'citas_invitados' establecida correctamente.<br>";
    echo "📋 Estructura de la tabla:<br>";
    echo "- ID de cita (clave primaria)<br>";
    echo "- ID de colaborador (referencia a sys_colaboradores)<br>";
    echo "- Asunto, ubicación y detalles<br>";
    echo "- Fechas de inicio y programación<br>";
    echo "- Estado (Programada, Realizada, Cancelada, Pospuesta)<br>";
    echo "- Duración y configuración de correo<br>";
    echo "🔗 Relaciones establecidas:<br>";
    echo "- citas_citas.id_colab → sys_colaboradores.id_colab<br>";
    echo "- citas_invitados.id_cita → citas_citas.id_cita<br>";
    
    // Verificar si la tabla fue creada usando el método de la clase Database
    if ($database->chktable('citas_citas')) {
        echo "🔍 Verificación: La tabla 'citas_citas' existe en la base de datos.<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error al crear la tabla o relación: " . $e->getMessage() . "<br>";
    
    // Mostrar información adicional del error
    if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
        echo "💡 Posible causa: Una de las tablas de referencia no existe o no tiene las columnas necesarias.<br>";
        echo "   Verifica que existan las tablas 'sys_colaboradores' y 'citas_invitados'.<br>";
    }
}

// La conexión PDO se cierra automáticamente

echo "<br>🔗 <a href='../index.php'>Volver al inicio</a>";
?>
