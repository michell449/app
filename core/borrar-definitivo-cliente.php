<?php
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';

    $id = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : (isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0);

    if ($id > 0) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'sys_clientes';

        // Tablas y campos a revisar dependencias (excepto us_usuarios_clientes)
        $tablas_dependencias = [
            ['tabla' => 'ctrl_contactos_clientes', 'campo' => 'id_cliente'],
            ['tabla' => 'ctrl_documentos_clientes', 'campo' => 'id_cliente'],
            ['tabla' => 'ctrl_estados_cuenta', 'campo' => 'id_cliente'],
            ['tabla' => 'ctrl_logos_empresas', 'campo' => 'id_cliente'],
            ['tabla' => 'exp_expedientes', 'campo' => 'cliente'],
            ['tabla' => 'cf_cfdis', 'campo' => 'id_cliente'],
            ['tabla' => 'com_cliente', 'campo' => 'id_cliente'],
            ['tabla' => 'cat_productos_clientes', 'campo' => 'id_cliente'],
        ];
        // Nombres amigables para las tablas
        $nombres_amigables = [
            'ctrl_contactos_clientes' => 'Contactos del cliente',
            'ctrl_documentos_clientes' => 'Documentos del cliente',
            'ctrl_estados_cuenta' => 'Estados de cuenta',
            'ctrl_logos_empresas' => 'Logos/Identidad corporativa',
            'exp_expedientes' => 'Expedientes',
            'cf_cfdis' => 'Facturas/CFDIs',
            'com_cliente' => 'Comisiones',
            'cat_productos_clientes' => 'Productos asignados',
        ];
        $tablas_con_dependencias = [];
        $tablas_con_dependencias_amigable = [];
        foreach ($tablas_dependencias as $dep) {
            $sql = "SELECT COUNT(*) FROM {$dep['tabla']} WHERE {$dep['campo']} = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                $tablas_con_dependencias[] = $dep['tabla'];
                $tablas_con_dependencias_amigable[] = $nombres_amigables[$dep['tabla']] ?? $dep['tabla'];
            }
        }
        if (count($tablas_con_dependencias) > 0) {
            $msg = 'El cliente no se puede eliminar porque tiene dependencias en: ' . implode(', ', $tablas_con_dependencias_amigable) . '. Elimine primero esas relaciones.';
            echo json_encode(['success' => false, 'error' => $msg, 'tablas_dependientes' => $tablas_con_dependencias_amigable]);
            exit;
        }

        // Eliminar usuarios relacionados a este cliente
        // 1. Obtener los id_usuario relacionados con el cliente
        $sql = "SELECT id_usuario FROM us_usuarios_clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
        unset($stmt);
        // 2. Eliminar los usuarios de us_usuarios
        if ($usuarios && count($usuarios) > 0) {
            $in = implode(',', array_fill(0, count($usuarios), '?'));
            $sql = "DELETE FROM us_usuarios WHERE id_usuario IN ($in)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($usuarios);
            unset($stmt);
        }
        // 3. Eliminar las relaciones en us_usuarios_clientes
        $sql = "DELETE FROM us_usuarios_clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        unset($stmt);

        // Obtener el nombre comercial antes de borrar
        $sql = "SELECT nombre_comercial FROM sys_clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $nombre = '';
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre = $row['nombre_comercial'];
        }
        unset($stmt);

        // Eliminar el cliente
        $sql = "DELETE FROM sys_clientes WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$id]);
        unset($stmt);

        // Eliminar carpeta física del cliente
        function eliminarDirectorio($dir) {
            if (!file_exists($dir)) return true;
            if (!is_dir($dir)) return unlink($dir);
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') continue;
                if (!eliminarDirectorio($dir . DIRECTORY_SEPARATOR . $item)) return false;
            }
            return rmdir($dir);
        }
        if ($nombre) {
            $nombre_folder = $id . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $nombre));
            $ruta = $_SERVER['DOCUMENT_ROOT'] . '/app/uploads/clientes/' . $nombre_folder;
            eliminarDirectorio($ruta);
        }

        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Cliente eliminado definitivamente.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el cliente.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID inválido.']);
    }
?>