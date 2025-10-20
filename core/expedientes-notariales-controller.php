<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json');

// Conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    echo json_encode(['success' => false, 'msg' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener parámetros para las tablas
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_notarial = isset($_GET['id_notarial']) ? intval($_GET['id_notarial']) : 0;

switch ($action) {
    case 'contar_categorias':
        contarCategorias($conn, $id_notarial);
        break;
    case 'obtener_archivos':
        obtenerArchivosPorCategoria($conn, $id_notarial);
        break;
    case 'obtener_categoria':
        $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
        obtenerArchivosDeCategoria($conn, $id_notarial, $categoria);
        break;
    default:
        echo json_encode(['success' => false, 'msg' => 'Acción no especificada']);
        break;
}

/**
 * Cuenta los archivos por cada categoría
 **/
function contarCategorias($conn, $id_notarial = 0) {
    try {
        // Definir todas las categorías posibles
        $categorias = [
            // Patrimonio
            'compraventa',
            'prestamo_hipotecario', 
            'prestamo_personal',
            'donacion',
            // Sucesiones
            'testamento',
            'herencia',
            'declaracion_heredero_abintestado',
            // Familia
            'capitulaciones_matrimoniales',
            'bodas',
            'separaciones',
            'divorcios',
            // Poderes y Actas
            'poder',
            'actas',
            // Societario
            'constitucion_sociedades_mercantiles',
            // Otros
            'poliza',
            'reclamacion_deudas',
            'conciliacion'
        ];

        $conteos = [];
        
        foreach ($categorias as $categoria) {
            $sql = "SELECT COUNT(*) as total FROM exp_archivos_notariales WHERE categoria = ? AND en_papelera = 0 AND en_papelera_fisica = 0";
            $params = [$categoria];
            
            
            if ($id_notarial > 0) {
                $sql .= " AND id_notarial = ?";
                $params[] = $id_notarial;
            }
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $conteos[$categoria] = intval($resultado['total']);
        }

        echo json_encode([
            'success' => true,
            'conteos' => $conteos,
            'id_notarial' => $id_notarial
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'msg' => 'Error al contar categorías: ' . $e->getMessage()
        ]);
    }
}

/**
 * Obtiene todos los archivos organizados por categoría
 **/
function obtenerArchivosPorCategoria($conn, $id_notarial = 0) {
    try {
    $sql = "SELECT id_doc, uuid, categoria, fecha, fecha_presentacion, documento, nombre_archivo 
        FROM exp_archivos_notariales 
        WHERE en_papelera = 0 AND en_papelera_fisica = 0";
        $params = [];
        
        if ($id_notarial > 0) {
            $sql .= " AND id_notarial = ?";
            $params[] = $id_notarial;
        }
        
        $sql .= " ORDER BY categoria, fecha DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organizar archivos por categoría en cada tabla
        $archivosPorCategoria = [];
        foreach ($archivos as $archivo) {
            $categoria = $archivo['categoria'];
            if (!isset($archivosPorCategoria[$categoria])) {
                $archivosPorCategoria[$categoria] = [];
            }
            $archivosPorCategoria[$categoria][] = $archivo;
        }

        echo json_encode([
            'success' => true,
            'archivos_por_categoria' => $archivosPorCategoria,
            'total_archivos' => count($archivos),
            'id_notarial' => $id_notarial
        ]);

    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'msg' => 'Error al obtener archivos: ' . $e->getMessage()
        ]);
    }
}

/**
 * Obtiene archivos de una categoría específica
 */
    function obtenerArchivosDeCategoria($conn, $id_notarial, $categoria) {
        try {
            if (empty($categoria)) {
                echo json_encode(['success' => false, 'msg' => 'Categoría no especificada']);
                return;
            }

        $sql = "SELECT id_doc, uuid, categoria, fecha, fecha_presentacion, documento, nombre_archivo 
            FROM exp_archivos_notariales 
            WHERE categoria = ? AND en_papelera = 0 AND en_papelera_fisica = 0";
            $params = [$categoria];
            if ($id_notarial > 0) {
                $sql .= " AND id_notarial = ?";
                $params[] = $id_notarial;
            }
            $sql .= " ORDER BY fecha DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Agregar ruta física y asegurar que cada archivo tenga id_notarial
            foreach ($archivos as &$archivo) {
                $ext = pathinfo($archivo['nombre_archivo'], PATHINFO_EXTENSION);
                $archivo['ruta_fisica'] = 'uploads/Notariales/' . $id_notarial . '/' . $archivo['uuid'] . '.' . $ext;
                $archivo['id_notarial'] = $id_notarial;
            }
            unset($archivo);

            // Respuesta de depuración
            echo json_encode([
                'success' => true,
                'categoria' => $categoria,
                'archivos' => $archivos,
                'total' => count($archivos),
                'id_notarial' => $id_notarial,
                'debug_sql' => $sql,
                'debug_params' => $params
            ]);

        } catch (PDOException $e) {
            echo json_encode([
                'success' => false,
                'msg' => 'Error al obtener archivos de la categoría: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Función auxiliar para obtener el nombre descriptivo de una categoría
     */
    function obtenerNombreCategoria($categoria) {
        $nombres = [
            'compraventa' => 'Compraventa',
            'prestamo_hipotecario' => 'Préstamo Hipotecario',
            'prestamo_personal' => 'Préstamo Personal', 
            'donacion' => 'Donación',
            'testamento' => 'Testamento',
            'herencia' => 'Herencia',
            'declaracion_heredero_abintestato' => 'Declaración de Heredero Abintestato',
            'capitulaciones_matrimoniales' => 'Capitulaciones Matrimoniales',
            'bodas' => 'Bodas',
            'separaciones' => 'Separaciones',
            'divorcios' => 'Divorcios',
            'poder' => 'Poder',
            'actas' => 'Actas',
            'constitucion_sociedades_mercantiles' => 'Constitución de Sociedades Mercantiles',
            'poliza' => 'Póliza',
            'reclamacion_deudas' => 'Reclamación de Deudas',
            'conciliacion' => 'Conciliación'
        ];
        
        return isset($nombres[$categoria]) ? $nombres[$categoria] : ucfirst(str_replace('_', ' ', $categoria));
    }
?>