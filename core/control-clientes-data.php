<?php
// core/control-clientes-data.php
// Maneja la lógica y datos para el control de clientes

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

class ControlClientesData {
    private $db;
    private $crud;
    
    public function __construct() {
        $this->db = new Database();
        $conn = $this->db->getConnection();
        $this->crud = new Crud($conn);
    }
    
    /**
     * Obtiene los datos del cliente seleccionado
     */
    public function obtenerDatosCliente() {
        $cliente_id = isset($_GET['cliente_id']) ? (int)$_GET['cliente_id'] : 0;
        $cliente_nombre = isset($_GET['cliente_nombre']) ? htmlspecialchars($_GET['cliente_nombre']) : '';
        
        $cliente_data = [
            'id' => $cliente_id,
            'nombre' => $cliente_nombre,
            'datos_completos' => null
        ];
        
        // Si hay un ID de cliente, obtener datos completos de la base de datos
        if ($cliente_id > 0) {
            $sql = "SELECT * FROM sys_clientes WHERE id_cliente = ? AND activo = 1";
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute([$cliente_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $cliente_data['datos_completos'] = $result;
                // Actualizar el nombre si no se pasó por parámetro
                if (empty($cliente_data['nombre'])) {
                    $cliente_data['nombre'] = $cliente_data['datos_completos']['nombre_comercial'];
                }
            }
        }
        
        return $cliente_data;
    }
    
    /**
     * Obtiene los documentos del cliente por categoría
     */
    public function obtenerDocumentosCliente($cliente_id) {
        if ($cliente_id <= 0) {
            return [];
        }
        
        // Aquí irías agregando la lógica para obtener documentos
        // Por ahora devuelvo estructura vacía
        return [
            'fiscales' => [],
            'legales' => [],
            'bancarios' => [],
            'contactos' => [],
            'logos' => []
        ];
    }
    
    /**
     * Obtiene contactos del cliente
     */
    public function obtenerContactosCliente($cliente_id) {
        if ($cliente_id <= 0) {
            return [];
        }
        
        // Aquí iría la lógica para obtener contactos de la base de datos
        // Por ahora devuelvo array vacío
        return [];
    }
    
    /**
     * Obtiene estados de cuenta del cliente
     */
    public function obtenerEstadosCuentaCliente($cliente_id) {
        if ($cliente_id <= 0) {
            return [];
        }
        
        // Aquí iría la lógica para obtener estados de cuenta
        // Por ahora devuelvo array vacío
        return [];
    }
}

// Instanciar la clase y obtener datos
$controlClientes = new ControlClientesData();
$cliente = $controlClientes->obtenerDatosCliente();
$documentos = $controlClientes->obtenerDocumentosCliente($cliente['id']);
$contactos = $controlClientes->obtenerContactosCliente($cliente['id']);
$estadosCuenta = $controlClientes->obtenerEstadosCuentaCliente($cliente['id']);

// Variables disponibles para la vista:
// $cliente - datos del cliente seleccionado
// $documentos - documentos organizados por categoría
// $contactos - contactos del cliente
// $estadosCuenta - estados de cuenta del cliente
?>