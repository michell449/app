<?php
// DEPURACIÓN: Mostrar errores PHP para encontrar problemas de respuesta
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once 'class/db.php';
require_once 'class/crud.php';

function jsonError($msg, $extra = []) {
    echo json_encode(array_merge(['success' => false, 'message' => $msg], $extra));
    exit;
}

// Función para generar nombre de carpeta descriptivo (igual que en agregar-cliente.php)
function generarNombreCarpeta($cliente_id, $nombre_cliente) {
    // Limpiar el nombre del cliente para el sistema de archivos
    $nombre_limpio = preg_replace('/[^\w\s-]/', '', $nombre_cliente);
    $nombre_limpio = preg_replace('/\s+/', '_', trim($nombre_limpio));
    $nombre_limpio = substr($nombre_limpio, 0, 50); // Limitar longitud
    
    return $cliente_id . '_' . $nombre_limpio;
}

// Función para renombrar carpeta de cliente y actualizar rutas en BD
function renombrarCarpetaCliente($pdo, $cliente_id, $nombre_anterior, $nombre_nuevo) {
    $directorioBase = __DIR__ . '/../uploads/clientes';
    
    $carpetaAnterior = generarNombreCarpeta($cliente_id, $nombre_anterior);
    $carpetaNueva = generarNombreCarpeta($cliente_id, $nombre_nuevo);
    
    $rutaAnterior = $directorioBase . '/' . $carpetaAnterior;
    $rutaNueva = $directorioBase . '/' . $carpetaNueva;
    
    // Solo proceder si las carpetas son diferentes
    if ($carpetaAnterior === $carpetaNueva) {
        return true; // No hay cambio necesario
    }
    
    // Verificar si existe la carpeta anterior
    if (!is_dir($rutaAnterior)) {
        error_log("Carpeta anterior no existe: " . $rutaAnterior);
        return true; // No hay carpeta que renombrar, continuar
    }
    
    // Verificar que la carpeta nueva no exista
    if (is_dir($rutaNueva)) {
        throw new Exception("Ya existe una carpeta con el nuevo nombre: " . $carpetaNueva);
    }
    
    // Renombrar la carpeta
    if (!rename($rutaAnterior, $rutaNueva)) {
        throw new Exception("No se pudo renombrar la carpeta de " . $carpetaAnterior . " a " . $carpetaNueva);
    }
    
    // Actualizar todas las rutas en la base de datos
    $sql = "UPDATE ctrl_documentos_clientes 
            SET ruta_archivo = REPLACE(ruta_archivo, ?, ?) 
            WHERE id_cliente = ? AND activo = 1";
    
    $rutaAnteriorDB = 'uploads/clientes/' . $carpetaAnterior . '/';
    $rutaNuevaDB = 'uploads/clientes/' . $carpetaNueva . '/';
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$rutaAnteriorDB, $rutaNuevaDB, $cliente_id]);
    
    $archivosActualizados = $stmt->rowCount();
    error_log("Carpeta renombrada de '$carpetaAnterior' a '$carpetaNueva'. Archivos actualizados: $archivosActualizados");
    
    return true;
}

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $id = $_POST['id_cliente'] ?? '';
    if ($id == '') {
        jsonError('ID requerido');
    }

    $soloActivo = (
        isset($_POST['activo']) &&
        count($_POST) === 2 &&
        isset($_POST['id_cliente'])
    );

    if ($soloActivo) {
        $activo = is_numeric($_POST['activo']) ? intval($_POST['activo']) : 1;
        $sql = "UPDATE sys_clientes SET activo=? WHERE id_cliente=?";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute([$activo, $id]);
        if ($ok) {
            echo json_encode(['success' => true, 'message' => $activo ? 'Cliente activado correctamente' : 'Cliente desactivado correctamente']);
        } else {
            jsonError('Error al modificar cliente');
        }
    } else {
        // Obtener datos actuales del cliente para comparar nombre comercial
        $stmt = $pdo->prepare("SELECT nombre_comercial FROM sys_clientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        $clienteActual = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$clienteActual) {
            jsonError('Cliente no encontrado');
        }
        
        $nombreAnterior = $clienteActual['nombre_comercial'];
        $nombreNuevo = $_POST['nombre_comercial'] ?? '';
        
        // Iniciar transacción para asegurar consistencia
        $pdo->beginTransaction();
        
        try {
            $crud = new crud($pdo);
            $crud->db_table = 'sys_clientes';
            $crud->id_key = 'id_cliente';
            $crud->id_param = $id;
            $crud->data = [
                'razon_social'    => $_POST['razon_social'] ?? '',
                'nombre_comercial' => $nombreNuevo,
                'regimen_fiscal'  => $_POST['regimen_fiscal'] ?? '',
                'telefono'        => $_POST['telefono'] ?? '',
                'rfc'             => $_POST['rfc'] ?? '',
                'contacto'        => $_POST['contacto'] ?? '',
                'correo'          => $_POST['correo'] ?? '',
                'calle'           => $_POST['calle'] ?? '',
                'n_exterior'      => $_POST['n_exterior'] ?? '',
                'n_interior'      => $_POST['n_interior'] ?? '',
                'entre_calle'     => $_POST['entre_calle'] ?? '',
                'y_calle'         => $_POST['y_calle'] ?? '',
                'pais'            => $_POST['pais'] ?? '',
                'cp'              => $_POST['cp'] ?? '',
                'estado'          => $_POST['estado'] ?? '',
                'municipio'       => $_POST['municipio'] ?? '',
                'poblacion'       => $_POST['poblacion'] ?? '',
                'colonia'         => $_POST['colonia'] ?? '',
                'referencia'      => $_POST['referencia'] ?? '',
                // Campos comentados temporalmente
                // 'descuento'       => $_POST['descuento'] ?? '',
                // 'limite_credito'  => $_POST['limite_credito'] ?? '',
                // 'dias_credito'    => $_POST['dias_credito'] ?? '',
                
                // Nuevos campos agregados
                //'comision_a'      => $_POST['comision_a'] ?? null,
                //'comision_b'      => $_POST['comision_b'] ?? null,
                //'socio'           => $_POST['socio'] ?? '',
                'admin_cfdis'     => isset($_POST['admin_cfdis']) ? 1 : 0
            ];
            
            // Solo actualizar 'activo' si se envía explícitamente
            if (isset($_POST['activo'])) {
                $crud->data['activo'] = is_numeric($_POST['activo']) ? intval($_POST['activo']) : 1;
            }
            
            if ($crud->update()) {
                // Si cambió el nombre comercial, renombrar carpeta y actualizar rutas
                if ($nombreAnterior !== $nombreNuevo && !empty($nombreNuevo)) {
                    renombrarCarpetaCliente($pdo, $id, $nombreAnterior, $nombreNuevo);
                }
                
                // Confirmar transacción
                $pdo->commit();
                
                $mensaje = 'Cliente modificado correctamente';
                if ($nombreAnterior !== $nombreNuevo && !empty($nombreNuevo)) {
                    $mensaje .= '';
                }
                
                echo json_encode(['success' => true, 'message' => $mensaje]);
            } else {
                $pdo->rollBack();
                jsonError('Error al modificar cliente');
            }
            
        } catch (Exception $e) {
            $pdo->rollBack();
            jsonError('Error al procesar modificación: ' . $e->getMessage());
        }
    }
} catch (Throwable $e) {
    jsonError('Error inesperado: ' . $e->getMessage());
}