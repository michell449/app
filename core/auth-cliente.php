<?php
/**
 * Sistema de autenticación específico para clientes
 * Validaciones de seguridad para el portal de clientes
 */

class AuthCliente {
    
    /**
     * Verifica si el usuario logueado es un cliente
     */
    public static function esCliente() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Verificar si es cliente por id_perfil = 4 o USR_TYPE = 'cliente'
        return (isset($_SESSION['USR_TYPE']) && 
                ($_SESSION['USR_TYPE'] === 'cliente' || $_SESSION['USR_TYPE'] == 4));
    }
    
    /**
     * Obtiene el ID del cliente logueado
     */
    public static function obtenerClienteId() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (self::esCliente()) {
            // Si ya está establecido CLIENTE_ID, usarlo
            if (isset($_SESSION['CLIENTE_ID'])) {
                return (int)$_SESSION['CLIENTE_ID'];
            }
            
            // Si no, buscar en la tabla intermedia us_usuarios_clientes
            if (isset($_SESSION['USR_ID'])) {
                try {
                    require_once __DIR__ . '/class/db.php';
                    $db = new Database();
                    $conn = $db->getConnection();
                    
                    // Buscar en la tabla intermedia us_usuarios_clientes
                    $sql = "SELECT uc.id_cliente 
                            FROM us_usuarios_clientes uc 
                            WHERE uc.id_usuario = ? AND uc.activo = 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$_SESSION['USR_ID']]);
                    $cliente_id = $stmt->fetchColumn();
                    
                    if ($cliente_id) {
                        $_SESSION['CLIENTE_ID'] = $cliente_id; // Guardarlo en sesión
                        return (int)$cliente_id;
                    }
                } catch (Exception $e) {
                    error_log("Error al obtener cliente_id: " . $e->getMessage());
                }
            }
        }
        return 0;
    }
    
    /**
     * Obtiene los datos básicos del cliente logueado
     */
    public static function obtenerDatosCliente() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!self::esCliente()) {
            return null;
        }
        
        $cliente_id = self::obtenerClienteId();
        if ($cliente_id <= 0) {
            return null;
        }
        
        // Intentar obtener datos completos del cliente usando la tabla intermedia
        try {
            require_once __DIR__ . '/class/db.php';
            $db = new Database();
            $conn = $db->getConnection();
            
            $sql = "SELECT c.nombre_comercial, c.rfc, c.correo 
                    FROM sys_clientes c
                    INNER JOIN us_usuarios_clientes uc ON c.id_cliente = uc.id_cliente
                    WHERE uc.id_usuario = ? AND uc.activo = 1 AND c.activo = 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_SESSION['USR_ID']]);
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($cliente) {
                return [
                    'id' => $cliente_id,
                    'nombre' => $cliente['nombre_comercial'],
                    'email' => $cliente['correo'] ?: $_SESSION['USR_MAIL'],
                    'rfc' => $cliente['rfc']
                ];
            }
        } catch (Exception $e) {
            error_log("Error al obtener datos del cliente: " . $e->getMessage());
        }
        
        // Fallback con datos de sesión
        return [
            'id' => $cliente_id,
            'nombre' => $_SESSION['USR_NAME'] ?? '',
            'email' => $_SESSION['USR_MAIL'] ?? '',
            'rfc' => $_SESSION['CLIENTE_RFC'] ?? ''
        ];
    }
    
    /**
     * Verifica acceso a documentos - solo permitir si es el cliente correcto
     */
    public static function verificarAccesoDocumento($cliente_id_propietario) {
        $cliente_logueado = self::obtenerClienteId();
        return $cliente_logueado > 0 && $cliente_logueado === (int)$cliente_id_propietario;
    }
    
    /**
     * Redirecciona a login si no está autorizado
     */
    public static function requerirAutenticacion() {
        if (!self::esCliente()) {
            header('Location: ' . HOMEURL . '/login?error=acceso_denegado');
            exit();
        }
    }
    
    /**
     * Inicializa sesión de cliente después del login
     */
    public static function iniciarSesionCliente($cliente_datos) {
        session_start();
        $_SESSION['USR_ID'] = $cliente_datos['id_usuario'];
        $_SESSION['USR_NAME'] = $cliente_datos['nombre_comercial'];
        $_SESSION['USR_TYPE'] = 'cliente';
        $_SESSION['USR_MAIL'] = $cliente_datos['correo'];
        $_SESSION['CLIENTE_ID'] = $cliente_datos['id_cliente'];
        $_SESSION['CLIENTE_RFC'] = $cliente_datos['rfc'];
    }
    
    /**
     * Cierra la sesión del cliente
     */
    public static function cerrarSesion() {
        session_start();
        session_destroy();
        header('Location: ' . HOMEURL . '/login');
        exit();
    }
}
?>