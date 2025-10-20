<?php
require_once 'class/db.php';
require_once 'class/crud.php';
$db = new Database();
$conn = $db->getConnection();
$crud = new Crud($conn);
$crud->db_table = 'sys_contactos';
$crud->read();
$contactos = $crud->data;

// Leer todos los contactos activos con nombre comercial del cliente
$sql = "SELECT c.id_contacto, c.nombre, c.telefono, c.whatsapp, c.correo, c.puesto, c.departamento, c.activo, cl.nombre_comercial
	FROM sys_contactos c
	LEFT JOIN sys_clientes cl ON c.cliente_empresa = cl.id_cliente
	ORDER BY c.id_contacto DESC";
$result = $crud->customQuery($sql);

if ($result && count($result) > 0) {
	echo '<table class="table table-bordered table-hover">';
	echo '<thead class="table-secondary">';
	echo '<tr>';
	echo '<th>Nombre</th>';
	echo '<th>Teléfono(s)</th>';
	echo '<th>WhatsApp</th>';
	echo '<th>Correo</th>';
	echo '<th>Cliente/Empresa</th>';
	echo '<th>Puesto/Cargo</th>';
	echo '<th>Departamento</th>';
	echo '<th>Acciones</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach ($result as $row) {
		echo '<tr>';
		echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
		echo '<td>' . htmlspecialchars($row['telefono']) . '</td>';
		echo '<td>' . htmlspecialchars($row['whatsapp']) . '</td>';
		echo '<td>' . htmlspecialchars($row['correo']) . '</td>';
		echo '<td>' . htmlspecialchars($row['nombre_comercial']) . '</td>';
		echo '<td>' . htmlspecialchars($row['puesto']) . '</td>';
		echo '<td>' . htmlspecialchars($row['departamento']) . '</td>';
		echo '<td>';
		// Mostrar botón para desactivar si está activo, y para activar si está inactivo
		if ($row['activo']) {
			echo '<button class="btn btn-danger btn-sm btn-desactivar-contacto" data-id="' . htmlspecialchars($row['id_contacto']) . '" title="Desactivar"><i class="fa fa-user-times"></i></button> ';
		} else {
			echo '<button class="btn btn-success btn-sm btn-activar-contacto" data-id="' . htmlspecialchars($row['id_contacto']) . '" title="Activar"><i class="fa fa-user-check"></i></button> ';
		}
		echo '<button class="btn btn-warning btn-sm btn-editar-contacto" data-id="' . htmlspecialchars($row['id_contacto']) . '"><i class="fa fa-edit"></i></button> ';
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
} else {
	echo '<div class="alert alert-info">No hay contactos registrados.</div>';
}
