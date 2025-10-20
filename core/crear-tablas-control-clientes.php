<?php
// core/crear-tablas-control-clientes.php
// Script para crear las tablas del sistema de control de clientes

require_once __DIR__ . '/class/db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "<!DOCTYPE html>";
    echo "<html lang='es'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Crear Tablas - Control de Clientes</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
    echo "</head>";
    echo "<body class='bg-light'>";
    echo "<div class='container mt-5'>";
    echo "<div class='row justify-content-center'>";
    echo "<div class='col-md-8'>";
    echo "<div class='card shadow'>";
    echo "<div class='card-header bg-primary text-white'>";
    echo "<h3 class='mb-0'>Crear Tablas del Sistema de Control de Clientes</h3>";
    echo "</div>";
    echo "<div class='card-body'>";
    
    $tablas_creadas = 0;
    $errores = 0;
    
    // TABLA CRÍTICA FALTANTE: us_usuarios_clientes
    echo "<h5>0. Creando tabla CRÍTICA: us_usuarios_clientes</h5>";
    $sql_usuarios_clientes = "CREATE TABLE IF NOT EXISTS `us_usuarios_clientes` (
        `id_relacion` int(11) NOT NULL AUTO_INCREMENT,
        `id_usuario` int(11) NOT NULL,
        `id_cliente` int(11) NOT NULL,
        `fecha_asignacion` datetime DEFAULT current_timestamp(),
        `activo` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_relacion`),
        UNIQUE KEY `unique_usuario_cliente` (`id_usuario`, `id_cliente`),
        KEY `fk_usuarios_clientes_usuario` (`id_usuario`),
        KEY `fk_usuarios_clientes_cliente` (`id_cliente`),
        CONSTRAINT `fk_usuarios_clientes_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `us_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `fk_usuarios_clientes_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `sys_clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->exec($sql_usuarios_clientes) !== false) {
        echo "<div class='alert alert-success'>✅ Tabla us_usuarios_clientes creada exitosamente (CRÍTICA)</div>";
        $tablas_creadas++;
    } else {
        echo "<div class='alert alert-danger'>❌ Error al crear tabla us_usuarios_clientes (CRÍTICA)</div>";
        $errores++;
    }
    
    echo "<hr><h4>TABLAS ESPECÍFICAS DEL CONTROL DE CLIENTES</h4>";
    
    // 1. Tabla ctrl_tipos_documentos
    echo "<h5>1. Creando tabla: ctrl_tipos_documentos</h5>";
    $sql_tipos = "CREATE TABLE IF NOT EXISTS `ctrl_tipos_documentos` (
        `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
        `categoria` enum('fiscales','legales','bancarios','corporativos') NOT NULL,
        `codigo` varchar(50) NOT NULL,
        `nombre` varchar(150) NOT NULL,
        `descripcion` text DEFAULT NULL,
        `extensiones_permitidas` varchar(255) DEFAULT '.pdf,.jpg,.png,.jpeg',
        `icono` varchar(50) DEFAULT NULL,
        `activo` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_tipo`),
        UNIQUE KEY `unique_codigo` (`codigo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->exec($sql_tipos) !== false) {
        echo "<div class='alert alert-success'>✅ Tabla ctrl_tipos_documentos creada exitosamente</div>";
        $tablas_creadas++;
    } else {
        echo "<div class='alert alert-danger'>❌ Error al crear tabla ctrl_tipos_documentos</div>";
        $errores++;
    }
    
    // 2. Tabla ctrl_documentos_clientes
    echo "<h5>2. Creando tabla: ctrl_documentos_clientes</h5>";
    $sql_documentos = "CREATE TABLE IF NOT EXISTS `ctrl_documentos_clientes` (
        `id_documento` int(11) NOT NULL AUTO_INCREMENT,
        `id_cliente` int(11) NOT NULL,
        `categoria` enum('fiscales','legales','bancarios','corporativos') NOT NULL,
        `tipo_documento` varchar(50) NOT NULL,
        `nombre_archivo` varchar(255) NOT NULL,
        `nombre_original` varchar(255) NOT NULL,
        `ruta_archivo` varchar(500) NOT NULL,
        `tamaño_kb` int(11) DEFAULT NULL,
        `tipo_mime` varchar(100) DEFAULT NULL,
        `descripcion` text DEFAULT NULL,
        `fecha_subida` datetime DEFAULT current_timestamp(),
        `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `id_usuario_subida` int(11) NOT NULL,
        `activo` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_documento`),
        KEY `fk_documentos_cliente` (`id_cliente`),
        KEY `fk_documentos_usuario` (`id_usuario_subida`),
        CONSTRAINT `fk_documentos_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `sys_clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `fk_documentos_usuario` FOREIGN KEY (`id_usuario_subida`) REFERENCES `us_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->exec($sql_documentos) !== false) {
        echo "<div class='alert alert-success'>✅ Tabla ctrl_documentos_clientes creada exitosamente</div>";
        $tablas_creadas++;
    } else {
        echo "<div class='alert alert-danger'>❌ Error al crear tabla ctrl_documentos_clientes</div>";
        $errores++;
    }
    
    // 3. Tabla ctrl_contactos_clientes
    echo "<h5>3. Creando tabla: ctrl_contactos_clientes</h5>";
    $sql_contactos = "CREATE TABLE IF NOT EXISTS `ctrl_contactos_clientes` (
        `id_contacto_ctrl` int(11) NOT NULL AUTO_INCREMENT,
        `id_cliente` int(11) NOT NULL,
        `nombre_completo` varchar(150) NOT NULL,
        `correo_electronico` varchar(150) NOT NULL,
        `password` varchar(255) DEFAULT NULL,
        `tipo_cuenta` enum('empresarial','personal','sat','infonavit','imss','bancario','otro') NOT NULL,
        `notas` text DEFAULT NULL,
        `fecha_registro` datetime DEFAULT current_timestamp(),
        `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        `activo` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_contacto_ctrl`),
        KEY `fk_contactos_ctrl_cliente` (`id_cliente`),
        CONSTRAINT `fk_contactos_ctrl_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `sys_clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->exec($sql_contactos) !== false) {
        echo "<div class='alert alert-success'>✅ Tabla ctrl_contactos_clientes creada exitosamente</div>";
        $tablas_creadas++;
    } else {
        echo "<div class='alert alert-danger'>❌ Error al crear tabla ctrl_contactos_clientes</div>";
        $errores++;
    }
    
    // 4. Tabla ctrl_estados_cuenta
    echo "<h5>4. Creando tabla: ctrl_estados_cuenta</h5>";
    $sql_estados = "CREATE TABLE IF NOT EXISTS `ctrl_estados_cuenta` (
        `id_estado_cuenta` int(11) NOT NULL AUTO_INCREMENT,
        `id_cliente` int(11) NOT NULL,
        `banco` varchar(100) NOT NULL,
        `numero_cuenta` varchar(20) DEFAULT NULL,
        `periodo` varchar(7) NOT NULL,
        `nombre_archivo` varchar(255) NOT NULL,
        `ruta_archivo` varchar(500) NOT NULL,
        `tamaño_kb` int(11) DEFAULT NULL,
        `fecha_subida` datetime DEFAULT current_timestamp(),
        `id_usuario_subida` int(11) NOT NULL,
        `activo` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_estado_cuenta`),
        KEY `fk_estados_cuenta_cliente` (`id_cliente`),
        KEY `fk_estados_cuenta_usuario` (`id_usuario_subida`),
        CONSTRAINT `fk_estados_cuenta_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `sys_clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `fk_estados_cuenta_usuario` FOREIGN KEY (`id_usuario_subida`) REFERENCES `us_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->exec($sql_estados) !== false) {
        echo "<div class='alert alert-success'>✅ Tabla ctrl_estados_cuenta creada exitosamente</div>";
        $tablas_creadas++;
    } else {
        echo "<div class='alert alert-danger'>❌ Error al crear tabla ctrl_estados_cuenta</div>";
        $errores++;
    }
    
    // 5. Tabla ctrl_logos_empresas
    echo "<h5>5. Creando tabla: ctrl_logos_empresas</h5>";
    $sql_logos = "CREATE TABLE IF NOT EXISTS `ctrl_logos_empresas` (
        `id_logo` int(11) NOT NULL AUTO_INCREMENT,
        `id_cliente` int(11) NOT NULL,
        `tipo_logo` enum('principal','alternativo') NOT NULL DEFAULT 'principal',
        `nombre_archivo` varchar(255) NOT NULL,
        `ruta_archivo` varchar(500) NOT NULL,
        `tamaño_kb` int(11) DEFAULT NULL,
        `tipo_mime` varchar(100) DEFAULT NULL,
        `fecha_subida` datetime DEFAULT current_timestamp(),
        `id_usuario_subida` int(11) NOT NULL,
        `activo` tinyint(1) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id_logo`),
        KEY `fk_logos_cliente` (`id_cliente`),
        KEY `fk_logos_usuario` (`id_usuario_subida`),
        CONSTRAINT `fk_logos_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `sys_clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `fk_logos_usuario` FOREIGN KEY (`id_usuario_subida`) REFERENCES `us_usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->exec($sql_logos) !== false) {
        echo "<div class='alert alert-success'>✅ Tabla ctrl_logos_empresas creada exitosamente</div>";
        $tablas_creadas++;
    } else {
        echo "<div class='alert alert-danger'>❌ Error al crear tabla ctrl_logos_empresas</div>";
        $errores++;
    }
    
    // Insertar datos iniciales en ctrl_tipos_documentos
    echo "<h5>6. Insertando datos iniciales en ctrl_tipos_documentos</h5>";
    
    $datos_iniciales = [
        // Documentos Fiscales
        ['fiscales', 'firma_electronica', 'Firma Electrónica', 'Certificados digitales y firmas electrónicas', '.cer,.key,.p12,.pfx', 'bi-pen'],
        ['fiscales', 'constancia_fiscal', 'Constancia de Situación Fiscal', 'Constancia de situación fiscal del SAT', '.pdf,.jpg,.png', 'bi-file-earmark-text'],
        ['fiscales', 'buzon_tributario', 'Buzón Tributario', 'Documentos del buzón tributario del SAT', '.pdf,.xml', 'bi-mailbox'],
        ['fiscales', 'opinion_cumplimiento', 'Opinión de Cumplimiento', 'Opinión del cumplimiento de obligaciones fiscales', '.pdf', 'bi-check-circle'],
        ['fiscales', 'infonavit', 'INFONAVIT', 'Documentos y constancias del INFONAVIT', '.pdf,.jpg,.png', 'bi-house'],
        ['fiscales', 'imss', 'IMSS', 'Documentos del Instituto Mexicano del Seguro Social', '.pdf,.jpg,.png', 'bi-heart-pulse'],
        
        // Documentos Legales
        ['legales', 'identificacion_representante', 'Identificaciones de Representantes', 'Identificaciones oficiales de representantes legales', '.pdf,.jpg,.png', 'bi-person-badge'],
        ['legales', 'acta_constitutiva', 'Actas Constitutivas', 'Acta constitutiva y modificaciones de la empresa', '.pdf', 'bi-file-earmark-text'],
        ['legales', 'caratula', 'Carátulas', 'Carátulas de expedientes y documentos importantes', '.pdf,.jpg,.png', 'bi-folder'],
        
        // Documentos Bancarios
        ['bancarios', 'estado_cuenta', 'Estados de Cuenta Bancarios', 'Estados de cuenta bancarios mensuales', '.pdf,.jpg,.png', 'bi-bank2'],
        
        // Documentos Corporativos
        ['corporativos', 'logo_principal', 'Logo Principal', 'Logo principal de la empresa', '.png,.jpg,.jpeg,.svg,.gif', 'bi-image'],
        ['corporativos', 'logo_alternativo', 'Logo Alternativo', 'Logo alternativo o monocromático', '.png,.jpg,.jpeg,.svg,.gif', 'bi-image']
    ];
    
    $stmt = $conn->prepare("INSERT IGNORE INTO ctrl_tipos_documentos (categoria, codigo, nombre, descripcion, extensiones_permitidas, icono) VALUES (?, ?, ?, ?, ?, ?)");
    
    $datos_insertados = 0;
    foreach ($datos_iniciales as $dato) {
        if ($stmt->execute($dato)) {
            $datos_insertados++;
        }
    }
    
    echo "<div class='alert alert-info'>📝 Se insertaron {$datos_insertados} tipos de documentos</div>";
    
    // Crear directorio uploads si no existe
    echo "<h5>7. Creando directorio de uploads</h5>";
    $upload_dir = __DIR__ . '/../uploads/control-clientes';
    if (!file_exists($upload_dir)) {
        if (mkdir($upload_dir, 0755, true)) {
            echo "<div class='alert alert-success'>✅ Directorio de uploads creado: {$upload_dir}</div>";
        } else {
            echo "<div class='alert alert-warning'>⚠️ No se pudo crear el directorio de uploads</div>";
        }
    } else {
        echo "<div class='alert alert-info'>ℹ️ Directorio de uploads ya existe</div>";
    }
    
    // Resumen final
    echo "<hr>";
    echo "<div class='row'>";
    echo "<div class='col-md-6'>";
    echo "<div class='card bg-success text-white'>";
    echo "<div class='card-body text-center'>";
    echo "<h4>{$tablas_creadas}</h4>";
    echo "<p>Tablas creadas exitosamente</p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "<div class='col-md-6'>";
    echo "<div class='card bg-danger text-white'>";
    echo "<div class='card-body text-center'>";
    echo "<h4>{$errores}</h4>";
    echo "<p>Errores encontrados</p>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    
    if ($tablas_creadas >= 6 && $errores == 0) {
        echo "<div class='alert alert-success mt-3'>";
        echo "<h4>🎉 ¡Instalación COMPLETA exitosa!</h4>";
        echo "<p>Todas las tablas del sistema han sido creadas:</p>";
        echo "<ul>";
        echo "<li>✅ <strong>us_usuarios_clientes</strong> - Relación usuario-cliente (CRÍTICA FALTANTE)</li>";
        echo "<li>✅ <strong>ctrl_tipos_documentos</strong> - Catálogo de tipos</li>";
        echo "<li>✅ <strong>ctrl_documentos_clientes</strong> - Documentos principales</li>";
        echo "<li>✅ <strong>ctrl_contactos_clientes</strong> - Contactos de clientes</li>";
        echo "<li>✅ <strong>ctrl_estados_cuenta</strong> - Estados bancarios</li>";
        echo "<li>✅ <strong>ctrl_logos_empresas</strong> - Logos corporativos</li>";
        echo "</ul>";
        echo "<p><strong>🚀 El sistema está LISTO para migrar a otro servidor</strong></p>";
        echo "<p><strong>📝 NOTA:</strong> Las tablas base (sys_clientes, us_usuarios, us_bitacora) ya existían.</p>";
        echo "<a href='../panel?pg=control-clientes' class='btn btn-primary'>Ir al Control de Clientes</a>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-warning mt-3'>";
        echo "<h4>⚠️ Instalación completada con advertencias</h4>";
        echo "<p>Algunas tablas pueden haber tenido problemas. Revisa los mensajes anteriores.</p>";
        echo "<p><strong>Tablas creadas:</strong> {$tablas_creadas} | <strong>Errores:</strong> {$errores}</p>";
        echo "</div>";
    }
    
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</body>";
    echo "</html>";
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ Error de conexión a la base de datos</h4>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h4>❌ Error general</h4>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>