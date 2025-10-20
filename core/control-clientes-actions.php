<?php
// Iniciar output buffering para controlar la salida
ob_start();

// Limpiar cualquier output previo
ob_clean();

header('Content-Type: application/json');
require_once 'class/db.php';

// Función para generar nombre de carpeta descriptivo
function generarNombreCarpeta($cliente_id, $nombre_cliente) {
    // Limpiar el nombre del cliente para el sistema de archivos
    $nombre_limpio = preg_replace('/[^\w\s-]/', '', $nombre_cliente);
    $nombre_limpio = preg_replace('/\s+/', '_', trim($nombre_limpio));
    $nombre_limpio = substr($nombre_limpio, 0, 50); // Limitar longitud
    
    return $cliente_id . '_' . $nombre_limpio;
}

// Habilitar logging de errores para depuración (SIN display_errors para evitar interferir con JSON)
error_reporting(E_ALL);
ini_set('display_errors', 0);  // CAMBIO: Desactivar display_errors para no interferir con JSON
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/control-clientes-errors.log');

// Función personalizada para manejar errores y que no interfieran con JSON
set_error_handler(function($severity, $message, $file, $line) {
    $logDir = __DIR__ . '/../logs';
    file_put_contents($logDir . '/control-clientes-debug.log', 
        "[" . date('Y-m-d H:i:s') . "] PHP Error: $message in $file on line $line\n", 
        FILE_APPEND
    );
    return true; // No mostrar el error en pantalla
});

// Crear directorio de logs si no existe
$logDir = __DIR__ . '/../logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}

// Log de depuración
file_put_contents($logDir . '/control-clientes-debug.log', 
    "[" . date('Y-m-d H:i:s') . "] Inicio de request - POST: " . print_r($_POST, true) . "\n", 
    FILE_APPEND
);

// Función para obtener la sesión del usuario actual
function obtenerUsuarioActual() {
    session_start();
    if (!isset($_SESSION['id_usuario'])) {
        // Para desarrollo: usar un usuario por defecto y registrar en log
        $logDir = __DIR__ . '/../logs';
        file_put_contents($logDir . '/control-clientes-debug.log', 
            "[" . date('Y-m-d H:i:s') . "] ADVERTENCIA: Usuario no autenticado, usando ID por defecto para desarrollo\n", 
            FILE_APPEND
        );
        
        // Retornar un ID de usuario por defecto para desarrollo
        // NOTA: En producción, esta línea debe ser removida y debe requerir autenticación
        return 1; // Usuario por defecto para desarrollo
        
        // Para producción, descomentar esta línea:
        // throw new Exception('Usuario no autenticado');
    }
    return $_SESSION['id_usuario'];
}

// Función para crear directorio si no existe
function crearDirectorioSiNoExiste($ruta) {
    if (!file_exists($ruta)) {
        if (!mkdir($ruta, 0755, true)) {
            throw new Exception("No se pudo crear el directorio: $ruta");
        }
    }
}

// Función para generar nombre único de archivo
function generarNombreUnico($nombreOriginal, $directorio) {
    $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nombreBase = pathinfo($nombreOriginal, PATHINFO_FILENAME);
    
    // Limpiar el nombre preservando caracteres especiales importantes
    $nombreLimpio = limpiarNombreArchivo($nombreBase);
    
    $contador = 1;
    $nombreFinal = $nombreLimpio . '.' . $extension;
    
    while (file_exists($directorio . '/' . $nombreFinal)) {
        $nombreFinal = $nombreLimpio . '_' . $contador . '.' . $extension;
        $contador++;
    }
    
    return $nombreFinal;
}

// Función para limpiar nombres de archivo manteniendo caracteres importantes
function limpiarNombreArchivo($nombre) {
    // Convertir a UTF-8 si no lo está
    if (!mb_check_encoding($nombre, 'UTF-8')) {
        $nombre = utf8_encode($nombre);
    }
    
    // Reemplazar caracteres problemáticos pero mantener acentos y puntos
    $nombre = str_replace(['\\', '/', ':', '*', '?', '"', '<', '>', '|'], '_', $nombre);
    
    // Convertir múltiples espacios a uno solo
    $nombre = preg_replace('/\s+/', ' ', $nombre);
    
    // Reemplazar espacios con guiones bajos para compatibilidad web
    $nombre = str_replace(' ', '_', $nombre);
    
    // Eliminar caracteres de control pero mantener acentos
    $nombre = preg_replace('/[\x00-\x1F\x7F]/', '', $nombre);
    
    // Limitar longitud del nombre (sin extensión) a 200 caracteres
    if (mb_strlen($nombre) > 200) {
        $nombre = mb_substr($nombre, 0, 200);
    }
    
    // Si el nombre queda vacío, usar un nombre por defecto
    if (empty(trim($nombre))) {
        $nombre = 'archivo_' . date('YmdHis');
    }
    
    return $nombre;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();
    $action = $_POST['action'] ?? '';
    $cliente_id = $_POST['cliente_id'] ?? 0;
    
    if (empty($cliente_id) || $cliente_id <= 0) {
        throw new Exception('ID de cliente requerido');
    }
    
    // Verificar que el cliente existe
    $stmt = $pdo->prepare("SELECT id_cliente, nombre_comercial FROM sys_clientes WHERE id_cliente = ? AND activo = 1");
    $stmt->execute([$cliente_id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$cliente) {
        throw new Exception('Cliente no encontrado');
    }
    
    switch ($action) {
        case 'subir_documento':
            file_put_contents($logDir . '/control-clientes-debug.log', 
                "[" . date('Y-m-d H:i:s') . "] Procesando subir_documento\n", 
                FILE_APPEND
            );
            
            $seccion = $_POST['seccion'] ?? '';
            $tipo_documento = $_POST['tipo_documento'] ?? '';
            
            file_put_contents($logDir . '/control-clientes-debug.log', 
                "[" . date('Y-m-d H:i:s') . "] Parámetros - Sección: $seccion, Tipo: $tipo_documento, Cliente: $cliente_id\n", 
                FILE_APPEND
            );
            
            if (empty($seccion) || empty($tipo_documento)) {
                throw new Exception('Sección y tipo de documento requeridos');
            }
            
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir el archivo');
            }
            
            $archivo = $_FILES['archivo'];
            $nombreOriginal = $archivo['name'];
            $tamañoBytes = $archivo['size'];
            $tamañoKB = round($tamañoBytes / 1024, 2);
            $tipoMime = $archivo['type'];
            $tmpName = $archivo['tmp_name'];
            
            // Mapear secciones del frontend a categorías de la base de datos
            $mapeoSeccionCategoria = [
                'documentos' => 'fiscales',           // Documentos fiscales
                'expedientes' => 'legales',           // Documentos legales
                'estados-cuenta' => 'bancarios',      // Estados de cuenta
                'logos-empresa' => 'corporativos'     // Logos e identidad corporativa
            ];
            
            $categoriaDB = $mapeoSeccionCategoria[$seccion] ?? 'fiscales';
            
            // Validar tipo de archivo según el tipo de documento
            $stmt = $pdo->prepare("SELECT extensiones_permitidas FROM ctrl_tipos_documentos WHERE codigo = ? AND categoria = ?");
            $stmt->execute([$tipo_documento, $categoriaDB]);
            $tipoInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$tipoInfo) {
                throw new Exception('Tipo de documento no válido');
            }
            
            $extensionesPermitidas = explode(',', $tipoInfo['extensiones_permitidas']);
            $extensionArchivo = '.' . strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            
            if (!in_array($extensionArchivo, $extensionesPermitidas)) {
                throw new Exception('Tipo de archivo no permitido para este documento');
            }
            
            // Crear directorio del cliente
            $directorioBase = __DIR__ . '/../uploads/clientes';
            
            // Obtener el nombre del cliente para el directorio
            $stmt_cliente = $pdo->prepare("SELECT nombre_comercial FROM sys_clientes WHERE id_cliente = ?");
            $stmt_cliente->execute([$cliente_id]); $cliente_data = $stmt_cliente->fetch(PDO::FETCH_ASSOC);
            
            if (!$cliente_data) {
                throw new Exception("Cliente no encontrado");
            }
            
            // Generar nombre de carpeta descriptivo
            $nombreCarpetaCliente = generarNombreCarpeta($cliente_id, $cliente_data['nombre_comercial']);
            $directorioCliente = $directorioBase . '/' . $nombreCarpetaCliente;
            
            // Mapear secciones a carpetas específicas según el menú de la interfaz
            $mapeoSeccionCarpeta = [
                'documentos' => 'documentos-fiscales',      // Documentos Fiscales
                'expedientes' => 'documentos-legales',      // Documentos Legales
                'estados-cuenta' => 'documentos-bancarios', // Documentos Bancarios
                'logos-empresa' => 'identidad-corporativa', // Identidad Corporativa
                'contactos' => 'control-admin/contactos',
                'otros' => 'control-admin/otros-documentos'
            ];
            
            $carpetaDestino = $mapeoSeccionCarpeta[$seccion] ?? 'documentos';
            $directorioCategoria = $directorioCliente . '/' . $carpetaDestino;
            
            crearDirectorioSiNoExiste($directorioBase);
            crearDirectorioSiNoExiste($directorioCliente);
            crearDirectorioSiNoExiste($directorioCategoria);
            
            // Generar nombre único y mover archivo
            $nombreArchivo = generarNombreUnico($nombreOriginal, $directorioCategoria);
            $rutaCompleta = $directorioCategoria . '/' . $nombreArchivo;
            $rutaRelativa = 'uploads/clientes/' . $nombreCarpetaCliente . '/' . $carpetaDestino . '/' . $nombreArchivo;
            
            if (!move_uploaded_file($tmpName, $rutaCompleta)) {
                throw new Exception('Error al guardar el archivo');
            }
            
            // Verificar si ya existe un documento del mismo tipo para este cliente
            $stmt_existente = $pdo->prepare("SELECT id_documento, nombre_archivo, ruta_archivo FROM ctrl_documentos_clientes WHERE id_cliente = ? AND categoria = ? AND tipo_documento = ? AND activo = 1");
            $stmt_existente->execute([$cliente_id, $categoriaDB, $tipo_documento]);
            $documentoExistente = $stmt_existente->fetch(PDO::FETCH_ASSOC);
            
            // Guardar en base de datos
            $id_usuario = obtenerUsuarioActual();
            
            if ($documentoExistente) {
                // Si existe un documento del mismo tipo, actualizarlo
                $sql = "UPDATE ctrl_documentos_clientes 
                        SET nombre_archivo = ?, nombre_original = ?, ruta_archivo = ?, 
                            tamaño_kb = ?, tipo_mime = ?, fecha_actualizacion = NOW(), 
                            descripcion = 'Documento actualizado desde control de clientes',
                            id_usuario_subida = ?
                        WHERE id_documento = ?";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $nombreArchivo,
                    $nombreOriginal,
                    $rutaRelativa,
                    $tamañoKB,
                    $tipoMime,
                    $id_usuario,
                    $documentoExistente['id_documento']
                ]);
                
                // Eliminar archivo anterior del sistema de archivos
                $rutaAnterior = __DIR__ . '/../' . $documentoExistente['ruta_archivo'];
                // Normalizar la ruta para evitar problemas con separadores
                $rutaAnterior = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rutaAnterior);
                
                if (file_exists($rutaAnterior)) {
                    if (unlink($rutaAnterior)) {
                        error_log("Archivo anterior eliminado automáticamente: " . $rutaAnterior);
                    } else {
                        error_log("No se pudo eliminar el archivo anterior automáticamente: " . $rutaAnterior);
                    }
                }
                
                $mensaje = 'Documento actualizado correctamente (reemplazó al anterior)';
            } else {
                // Si no existe, crear nuevo documento
                $sql = "INSERT INTO ctrl_documentos_clientes 
                        (id_cliente, categoria, tipo_documento, nombre_archivo, nombre_original, 
                         ruta_archivo, tamaño_kb, tipo_mime, descripcion, id_usuario_subida) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $cliente_id,
                    $categoriaDB,  // Usar la categoría de la base de datos, no la sección del frontend
                    $tipo_documento,
                    $nombreArchivo,
                    $nombreOriginal,
                    $rutaRelativa,
                    $tamañoKB,
                    $tipoMime,
                    "Documento subido desde control de clientes",
                    $id_usuario
                ]);
                
                $mensaje = 'Documento subido correctamente';
            }
            
            echo json_encode([
                'success' => true,
                'message' => $mensaje,
                'archivo' => [
                    'nombre' => $nombreArchivo,
                    'tamaño_kb' => $tamañoKB,
                    'tipo' => $tipoMime
                ]
            ]);
            break;
            
        case 'actualizar_documento':
            $seccion = $_POST['seccion'] ?? '';
            $tipo_documento = $_POST['tipo_documento'] ?? '';
            $archivo_id = $_POST['archivo_id'] ?? 0;
            
            if (empty($seccion) || empty($tipo_documento) || empty($archivo_id)) {
                throw new Exception('Sección, tipo de documento y archivo ID requeridos');
            }
            
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir el archivo');
            }
            
            // Obtener información del archivo existente
            $stmt = $pdo->prepare("SELECT id_documento, nombre_archivo, ruta_archivo FROM ctrl_documentos_clientes WHERE id_documento = ? AND id_cliente = ? AND activo = 1");
            $stmt->execute([$archivo_id, $cliente_id]);
            $archivoExistente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$archivoExistente) {
                throw new Exception('Archivo a actualizar no encontrado');
            }
            
            $archivo = $_FILES['archivo'];
            $nombreOriginal = $archivo['name'];
            $tamañoBytes = $archivo['size'];
            $tamañoKB = round($tamañoBytes / 1024, 2);
            $tipoMime = $archivo['type'];
            $tmpName = $archivo['tmp_name'];
            
            // Mapear secciones del frontend a categorías de la base de datos
            $mapeoSeccionCategoria = [
                'documentos' => 'fiscales',
                'expedientes' => 'legales',
                'estados-cuenta' => 'bancarios',
                'logos-empresa' => 'corporativos'
            ];
            
            $categoriaDB = $mapeoSeccionCategoria[$seccion] ?? 'fiscales';
            
            // Validar tipo de archivo
            $stmt = $pdo->prepare("SELECT extensiones_permitidas FROM ctrl_tipos_documentos WHERE codigo = ? AND categoria = ?");
            $stmt->execute([$tipo_documento, $categoriaDB]);
            $tipoInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$tipoInfo) {
                throw new Exception('Tipo de documento no válido');
            }
            
            $extensionesPermitidas = explode(',', $tipoInfo['extensiones_permitidas']);
            $extensionArchivo = '.' . strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            
            if (!in_array($extensionArchivo, $extensionesPermitidas)) {
                throw new Exception('Tipo de archivo no permitido para este documento');
            }
            
            // Crear directorio del cliente y carpeta específica
            $directorioBase = __DIR__ . '/../uploads/clientes';
            
            // Obtener el nombre del cliente para el directorio
            $stmt_cliente = $pdo->prepare("SELECT nombre_comercial FROM sys_clientes WHERE id_cliente = ?");
            $stmt_cliente->execute([$cliente_id]); $cliente_data = $stmt_cliente->fetch(PDO::FETCH_ASSOC);
            
            if (!$cliente_data) {
                throw new Exception("Cliente no encontrado");
            }
            
            // Generar nombre de carpeta descriptivo
            $nombreCarpetaCliente = generarNombreCarpeta($cliente_id, $cliente_data['nombre_comercial']);
            $directorioCliente = $directorioBase . '/' . $nombreCarpetaCliente;
            
            $mapeoSeccionCarpeta = [
                'documentos' => 'documentos-fiscales',      // Documentos Fiscales
                'expedientes' => 'documentos-legales',      // Documentos Legales
                'estados-cuenta' => 'documentos-bancarios', // Documentos Bancarios
                'logos-empresa' => 'identidad-corporativa'  // Identidad Corporativa
            ];
            
            $carpetaDestino = $mapeoSeccionCarpeta[$seccion] ?? 'documentos';
            $directorioCategoria = $directorioCliente . '/' . $carpetaDestino;
            
            crearDirectorioSiNoExiste($directorioBase);
            crearDirectorioSiNoExiste($directorioCliente);
            crearDirectorioSiNoExiste($directorioCategoria);
            
            // Generar nombre único y mover archivo
            $nombreArchivo = generarNombreUnico($nombreOriginal, $directorioCategoria);
            $rutaCompleta = $directorioCategoria . '/' . $nombreArchivo;
            $rutaRelativa = 'uploads/clientes/' . $nombreCarpetaCliente . '/' . $carpetaDestino . '/' . $nombreArchivo;
            
            if (!move_uploaded_file($tmpName, $rutaCompleta)) {
                throw new Exception('Error al guardar el archivo');
            }
            
            // Actualizar registro en base de datos
            $id_usuario = obtenerUsuarioActual();
            
            $sql = "UPDATE ctrl_documentos_clientes 
                    SET nombre_archivo = ?, nombre_original = ?, ruta_archivo = ?, 
                        tamaño_kb = ?, tipo_mime = ?, fecha_actualizacion = NOW(), 
                        descripcion = 'Documento actualizado desde control de clientes'
                    WHERE id_documento = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $nombreArchivo,
                $nombreOriginal,
                $rutaRelativa,
                $tamañoKB,
                $tipoMime,
                $archivo_id
            ]);
            
            // Eliminar archivo anterior del sistema de archivos
            $rutaAnterior = __DIR__ . '/../' . $archivoExistente['ruta_archivo'];
            // Normalizar la ruta para evitar problemas con separadores
            $rutaAnterior = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rutaAnterior);
            
            if (file_exists($rutaAnterior)) {
                if (unlink($rutaAnterior)) {
                    error_log("Archivo anterior eliminado: " . $rutaAnterior);
                } else {
                    error_log("No se pudo eliminar el archivo anterior: " . $rutaAnterior);
                }
            } else {
                error_log("Archivo anterior no encontrado: " . $rutaAnterior);
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Documento actualizado correctamente',
                'archivo' => [
                    'id' => $archivo_id,
                    'nombre' => $nombreArchivo,
                    'tamaño_kb' => $tamañoKB,
                    'tipo' => $tipoMime
                ]
            ]);
            break;
            
        case 'agregar_contacto':
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $tipo_cuenta = $_POST['tipo_cuenta'] ?? '';
            $notas = $_POST['notas'] ?? '';
            
            if (empty($nombre) || empty($correo) || empty($tipo_cuenta)) {
                throw new Exception('Campos requeridos: nombre, correo y tipo de cuenta');
            }
            
            $sql = "INSERT INTO ctrl_contactos_clientes 
                    (id_cliente, nombre_completo, correo_electronico, password, tipo_cuenta, notas) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$cliente_id, $nombre, $correo, $password, $tipo_cuenta, $notas]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Contacto agregado correctamente',
                'contacto_id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'agregar_estado_cuenta':
            $banco = $_POST['banco'] ?? '';
            $numero_cuenta = $_POST['numero_cuenta'] ?? '';
            $periodo = $_POST['periodo'] ?? '';
            
            if (empty($banco) || empty($periodo)) {
                throw new Exception('Banco y período son requeridos');
            }
            
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir el archivo');
            }
            
            $archivo = $_FILES['archivo'];
            $nombreOriginal = $archivo['name'];
            $tamañoBytes = $archivo['size'];
            $tamañoKB = round($tamañoBytes / 1024, 2);
            $tmpName = $archivo['tmp_name'];
            $tipoMime = $archivo['type'];
            
            // Crear directorio
            $directorioBase = __DIR__ . '/../uploads/clientes';
            
            // Obtener el nombre del cliente para el directorio
            $stmt_cliente = $pdo->prepare("SELECT nombre_comercial FROM sys_clientes WHERE id_cliente = ?");
            $stmt_cliente->execute([$cliente_id]); $cliente_data = $stmt_cliente->fetch(PDO::FETCH_ASSOC);
            
            if (!$cliente_data) {
                throw new Exception("Cliente no encontrado");
            }
            
            // Generar nombre de carpeta descriptivo
            $nombreCarpetaCliente = generarNombreCarpeta($cliente_id, $cliente_data['nombre_comercial']);
            $directorioCliente = $directorioBase . '/' . $nombreCarpetaCliente;
            $directorioBancarios = $directorioCliente . '/documentos-bancarios';
            
            crearDirectorioSiNoExiste($directorioBase);
            crearDirectorioSiNoExiste($directorioCliente);
            crearDirectorioSiNoExiste($directorioBancarios);
            
            // Generar nombre único
            $nombreArchivo = generarNombreUnico($nombreOriginal, $directorioBancarios);
            $rutaCompleta = $directorioBancarios . '/' . $nombreArchivo;
            $rutaRelativa = 'uploads/clientes/' . $nombreCarpetaCliente . '/documentos-bancarios/' . $nombreArchivo;
            
            if (!move_uploaded_file($tmpName, $rutaCompleta)) {
                throw new Exception('Error al guardar el archivo');
            }
            
            // Crear descripción con información del banco y período
            $descripcion = "Banco: $banco";
            if (!empty($numero_cuenta)) {
                $descripcion .= " | Cuenta: **** $numero_cuenta";
            }
            $descripcion .= " | Período: $periodo";
            
            // Guardar en la tabla principal de documentos
            $id_usuario = obtenerUsuarioActual();
            
            $sql = "INSERT INTO ctrl_documentos_clientes 
                    (id_cliente, categoria, tipo_documento, nombre_original, nombre_archivo, ruta_archivo, 
                     tamaño_kb, tipo_mime, descripcion, id_usuario_subida) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $cliente_id,
                'bancarios',
                'estado_cuenta',
                $nombreOriginal,
                $nombreArchivo,
                $rutaRelativa,
                $tamañoKB,
                $tipoMime,
                $descripcion,
                $id_usuario
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Estado de cuenta agregado correctamente',
                'documento_id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'subir_logo':
            $tipo_logo = $_POST['tipo_logo'] ?? 'principal';
            
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Error al subir el archivo');
            }
            
            $archivo = $_FILES['archivo'];
            $nombreOriginal = $archivo['name'];
            $tamañoBytes = $archivo['size'];
            $tamañoKB = round($tamañoBytes / 1024, 2);
            $tipoMime = $archivo['type'];
            $tmpName = $archivo['tmp_name'];
            
            // Validar que sea imagen
            $extensionesImagenes = ['.png', '.jpg', '.jpeg', '.svg', '.gif'];
            $extensionArchivo = '.' . strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            
            if (!in_array($extensionArchivo, $extensionesImagenes)) {
                throw new Exception('Solo se permiten archivos de imagen (PNG, JPG, JPEG, SVG, GIF)');
            }
            
            // Crear directorio
            $directorioBase = __DIR__ . '/../uploads/clientes';
            
            // Obtener el nombre del cliente para el directorio
            $stmt_cliente = $pdo->prepare("SELECT nombre_comercial FROM sys_clientes WHERE id_cliente = ?");
            $stmt_cliente->execute([$cliente_id]); $cliente_data = $stmt_cliente->fetch(PDO::FETCH_ASSOC);
            
            if (!$cliente_data) {
                throw new Exception("Cliente no encontrado");
            }
            
            // Generar nombre de carpeta descriptivo
            $nombreCarpetaCliente = generarNombreCarpeta($cliente_id, $cliente_data['nombre_comercial']);
            $directorioCliente = $directorioBase . '/' . $nombreCarpetaCliente;
            $directorioLogos = $directorioCliente . '/identidad-corporativa';
            
            crearDirectorioSiNoExiste($directorioBase);
            crearDirectorioSiNoExiste($directorioCliente);
            crearDirectorioSiNoExiste($directorioLogos);
            
            // Generar nombre único
            $nombreArchivo = generarNombreUnico($nombreOriginal, $directorioLogos);
            $rutaCompleta = $directorioLogos . '/' . $nombreArchivo;
            $rutaRelativa = 'uploads/clientes/' . $nombreCarpetaCliente . '/identidad-corporativa/' . $nombreArchivo;
            
            if (!move_uploaded_file($tmpName, $rutaCompleta)) {
                throw new Exception('Error al guardar el archivo');
            }
            
            // Desactivar logo anterior del mismo tipo
            $stmt = $pdo->prepare("UPDATE ctrl_logos_empresas SET activo = 0 WHERE id_cliente = ? AND tipo_logo = ?");
            $stmt->execute([$cliente_id, $tipo_logo]);
            
            // Guardar nuevo logo
            $id_usuario = obtenerUsuarioActual();
            
            $sql = "INSERT INTO ctrl_logos_empresas 
                    (id_cliente, tipo_logo, nombre_archivo, ruta_archivo, tamaño_kb, tipo_mime, id_usuario_subida) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $cliente_id,
                $tipo_logo,
                $nombreArchivo,
                $rutaRelativa,
                $tamañoKB,
                $tipoMime,
                $id_usuario
            ]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Logo subido correctamente',
                'logo_id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'eliminar_documento':
            $documento_id = $_POST['documento_id'] ?? 0;
            
            if (empty($documento_id)) {
                throw new Exception('ID de documento requerido');
            }
            
            // Obtener información del documento antes de eliminarlo
            $stmt = $pdo->prepare("SELECT id_cliente, nombre_archivo, ruta_archivo FROM ctrl_documentos_clientes WHERE id_documento = ? AND activo = 1");
            $stmt->execute([$documento_id]);
            $documento = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$documento) {
                throw new Exception('Documento no encontrado');
            }
            
            // Verificar que pertenece al cliente correcto (seguridad adicional)
            if ($documento['id_cliente'] != $cliente_id) {
                throw new Exception('Documento no pertenece al cliente especificado');
            }
            
            // Marcar como inactivo en base de datos
            $stmt = $pdo->prepare("UPDATE ctrl_documentos_clientes SET activo = 0, fecha_actualizacion = NOW() WHERE id_documento = ?");
            $stmt->execute([$documento_id]);
            
            // Opcional: eliminar archivo físico (comentado por seguridad)
            // $rutaCompleta = __DIR__ . '/..' . $documento['ruta_archivo'];
            // if (file_exists($rutaCompleta)) {
            //     unlink($rutaCompleta);
            // }
            
            echo json_encode([
                'success' => true,
                'message' => 'Documento eliminado correctamente'
            ]);
            break;
            
        default:
            throw new Exception('Acción no válida');
    }
    
} catch (Exception $e) {
    $logDir = __DIR__ . '/../logs';
    file_put_contents($logDir . '/control-clientes-debug.log', 
        "[" . date('Y-m-d H:i:s') . "] Exception: " . $e->getMessage() . "\n" .
        "Trace: " . $e->getTraceAsString() . "\n", 
        FILE_APPEND
    );
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (Throwable $e) {
    $logDir = __DIR__ . '/../logs';
    file_put_contents($logDir . '/control-clientes-debug.log', 
        "[" . date('Y-m-d H:i:s') . "] Throwable: " . $e->getMessage() . "\n" .
        "Trace: " . $e->getTraceAsString() . "\n", 
        FILE_APPEND
    );
    
    echo json_encode([
        'success' => false,
        'message' => 'Error inesperado: ' . $e->getMessage()
    ]);
}

// Limpiar cualquier output adicional
ob_end_flush();
?>

