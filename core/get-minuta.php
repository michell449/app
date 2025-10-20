<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

$id_minuta = $_GET['id_minuta'] ?? '';
if (!$id_minuta) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}
$db = (new Database())->getConnection();
$crud = new Crud($db);

// Minuta principal
$crud->db_table = 'min_minutas';
$crud->id_key = 'id_minuta';
$crud->id_param = $id_minuta;
$crud->read();
$minuta = $crud->data[0] ?? [];

// Participantes
$crud->db_table = 'min_participantes';
$crud->id_key = 'idminuta';
$crud->id_param = $id_minuta;
$crud->read();
$minuta['participantes'] = $crud->data;

// Temas

$crud->db_table = 'min_temas';
$crud->id_key = 'id_minuta';
$crud->id_param = $id_minuta;
$crud->read();
$temas = [];
foreach ($crud->data as $row) {
    $tema = $row;
    // Asegura que el campo se llame 'id' y no 'id_tema'
    if (isset($tema['id'])) {
        $tema['id'] = $tema['id'];
    } elseif (isset($tema['id_tema'])) {
        $tema['id'] = $tema['id_tema'];
        unset($tema['id_tema']);
    }
    $temas[] = $tema;
}
$minuta['temas'] = $temas;

// Acuerdos

$crud->db_table = 'min_acuerdos';
$crud->id_key = 'id_minuta';
$crud->id_param = $id_minuta;
$crud->read();
$acuerdos = [];
foreach ($crud->data as $acuerdo) {
    // Mapeo de campos: ajusta los nombres según tu base de datos
    $acuerdo_out = [];
    $acuerdo_out['id'] = $acuerdo['id'] ?? $acuerdo['id_acuerdo'] ?? null;
    $acuerdo_out['id_tema'] = $acuerdo['id_tema'] ?? null;
    $acuerdo_out['descripcion'] = $acuerdo['descripcion'] ?? ($acuerdo['detalle'] ?? '');
    $acuerdo_out['fecha_limite'] = $acuerdo['fecha_limite'] ?? ($acuerdo['fecha'] ?? '');
    $acuerdo_out['estado'] = $acuerdo['estado'] ?? 'Pendiente';
    // Si responsable es un ID, busca el nombre
    if (!empty($acuerdo['idresponsable'])) {
        $crudColab = new Crud($db);
        $crudColab->db_table = 'sys_colaboradores';
        $crudColab->id_key = 'id_colab';
        $crudColab->id_param = $acuerdo['idresponsable'];
        $crudColab->read();
        if (!empty($crudColab->data[0]['nombre'])) {
            $nombre = $crudColab->data[0]['nombre'];
            $apellidos = $crudColab->data[0]['apellidos'] ?? '';
            $acuerdo_out['responsable'] = trim($nombre . ' ' . $apellidos);
        } else {
            $acuerdo_out['responsable'] = '';
        }
    } else {
        $acuerdo_out['responsable'] = $acuerdo['responsable'] ?? '';
    }
    $acuerdos[] = $acuerdo_out;
}
$minuta['acuerdos'] = $acuerdos;

// Obtener nombre de cliente
if (!empty($minuta['idcliente'])) {
    $crud->db_table = 'sys_clientes';
    $crud->id_key = 'id_cliente';
    $crud->id_param = $minuta['idcliente'];
    $crud->read();
    if (!empty($crud->data[0]['razon_social'])) {
        $minuta['clienteNombre'] = $crud->data[0]['razon_social'];
    }
}
// Obtener nombre de responsable
if (!empty($minuta['idresponsable'])) {
    $crud->db_table = 'sys_colaboradores';
    $crud->id_key = 'id_colab';
    $crud->id_param = $minuta['idresponsable'];
    $crud->read();
    if (!empty($crud->data[0]['nombre'])) {
        $nombre = $crud->data[0]['nombre'];
        $apellidos = $crud->data[0]['apellidos'] ?? '';
        $minuta['responsableNombre'] = trim($nombre . ' ' . $apellidos);
    }
}
// Renombrar hora_inicio si existe
if (isset($minuta['hora_inicio'])) {
    $minuta['hora'] = $minuta['hora_inicio'];
}

echo json_encode($minuta);
?>
