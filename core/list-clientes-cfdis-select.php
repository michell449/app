<?php
// Asegurar que se cargue la configuración correcta
if (!defined('DB_HOST')) {
    require_once dirname(__DIR__) . '/config.php';
}

require_once __DIR__ . '/class/db.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    $id_contacto = isset($_GET['id_contacto']) ? intval($_GET['id_contacto']) : null;
    $extraEmpresa = null;
    if ($id_contacto) {
      $sqlExtra = "SELECT c.cliente_empresa, cl.nombre_comercial FROM sys_contactos c LEFT JOIN sys_clientes cl ON c.cliente_empresa = cl.id_cliente WHERE c.id_contacto = ? LIMIT 1";
      $stmtExtra = $conn->prepare($sqlExtra);
      $stmtExtra->execute([$id_contacto]);
      $extraEmpresa = $stmtExtra->fetch(PDO::FETCH_ASSOC);
    }

    // Solo seleccionar clientes que tienen admin_cfdis = 1
    // Intentar primero con sys_clientes, si falla intentar con clientes
    $sqlClientes = "SELECT id_cliente, nombre_comercial FROM sys_clientes WHERE activo = 1 AND admin_cfdis = 1 ORDER BY nombre_comercial ASC";
    $stmt = $conn->prepare($sqlClientes);
    
    try {
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Si falla con sys_clientes, intentar con clientes
        try {
            $sqlClientes = "SELECT id_cliente, nombre_comercial FROM clientes WHERE activo = 1 AND admin_cfdis = 1 ORDER BY nombre_comercial ASC";
            $stmt = $conn->prepare($sqlClientes);
            $stmt->execute();
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e2) {
            throw new Exception("Error en ambas tablas (sys_clientes y clientes): " . $e2->getMessage());
        }
    }

    // Si hay un contacto específico y su empresa no está en la lista pero tiene admin_cfdis = 1, incluirla
    if ($extraEmpresa && $extraEmpresa['cliente_empresa']) {
      $alreadyExists = false;
      foreach ($clientes as $cli) {
        if ($cli['id_cliente'] == $extraEmpresa['cliente_empresa']) {
          $alreadyExists = true;
          break;
        }
      }
      if (!$alreadyExists) {
        // Verificar si la empresa tiene admin_cfdis = 1 pero está inactiva
        $sqlCheck = "SELECT admin_cfdis FROM sys_clientes WHERE id_cliente = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->execute([$extraEmpresa['cliente_empresa']]);
        $checkResult = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($checkResult && $checkResult['admin_cfdis'] == 1) {
          echo '<option value="' . htmlspecialchars($extraEmpresa['cliente_empresa']) . '">' . htmlspecialchars($extraEmpresa['nombre_comercial']) . ' (inactiva)</option>';
        }
      }
    }

    if ($clientes) {
      foreach ($clientes as $cli) {
        echo '<option value="' . htmlspecialchars($cli['id_cliente']) . '">' . htmlspecialchars($cli['nombre_comercial']) . '</option>';
      }
    } else {
      echo '<option value="">No hay clientes con administración de CFDIs disponibles</option>';
    }

} catch (Exception $e) {
    error_log("Error en list-clientes-cfdis-select.php: " . $e->getMessage());
    echo '<option value="">Error al cargar clientes: ' . htmlspecialchars($e->getMessage()) . '</option>';
}
?>