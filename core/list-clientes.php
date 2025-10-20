<?php
    require_once __DIR__ . '/class/db.php';
    require_once __DIR__ . '/class/crud.php';
    $db = new Database();
    $conn = $db->getConnection();
    $crud = new Crud($conn);
    $crud->db_table = 'sys_clientes';
    $crud->read();
    $usuario = $crud->data;



    // Leer solo los clientes activos
    $sql = "SELECT id_cliente, nombre_comercial, correo, telefono, razon_social, rfc, contacto, estado, activo FROM sys_clientes WHERE activo = 1 ORDER BY id_cliente DESC";
    $result = $crud->customQuery($sql);

    // Imprime la tabla HTML
    if ($result && count($result) > 0) {
        echo '<table class="table table-bordered table-hover">';
        echo '<thead class="table-secondary">';
        echo '<tr>';
        echo '<th>Nombre Comercial</th>';
        echo '<th>Correo</th>';
        echo '<th>Teléfono</th>';
        echo '<th>Razón Social</th>';
        echo '<th>RFC</th>';
        echo '<th>Contacto</th>';
        echo '<th>Estado</th>';
        echo '<th>Acciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($result as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['nombre_comercial']) . '</td>';
            echo '<td>' . htmlspecialchars($row['correo']) . '</td>';
            echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
            echo '<td>' . htmlspecialchars($row['razon_social']) . '</td>';
            echo '<td>' . htmlspecialchars($row['rfc']) . '</td>';
            echo '<td>' . htmlspecialchars($row['contacto']) . '</td>';
        echo '<td>' . htmlspecialchars($row['estado']) . '</td>';
            echo '<td class="text-center">';
            echo '<div class="d-inline-flex">';
            // Botón Control de Cliente
            //echo '<a href="panel?pg=control-clientes&cliente_id=' . $row['id_cliente'] . '&cliente_nombre=' . urlencode($row['nombre_comercial']) . '" class="btn btn-primary btn-sm mx-1" title="Control de Cliente"><i class="fa fa-cogs"></i></a>';
            // Botón activar/desactivar
            echo '<button class="btn btn-danger btn-sm btn-desactivar-cliente mx-1" data-id="' . $row['id_cliente'] . '" title="Suspender Cliente"><i class="bi bi-clipboard-x-fill"></i></button>';
            // Botón editar (fondo amarillo, ícono lápiz)
            echo '<button class="btn btn-warning btn-sm btn-editar-cliente mx-1" data-id="' . $row['id_cliente'] . '" title="Editar"><i class="fa fa-edit"></i></button>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="alert alert-info">No hay clientes registrados.</div>';
    }
