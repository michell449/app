<?php
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $objetivo = $_POST['objetivo'] ?? '';
    $idcliente = $_POST['idcliente'] ?? null;
    $idresponsable = $_POST['idresponsable'] ?? null;
    $lugar = $_POST['lugar'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    // Si la hora viene como HH:MM, convertir a HH:MM:SS
    if ($hora_inicio && strlen($hora_inicio) === 5) {
        $hora_inicio .= ':00';
    }


    // Participantes recibidos como array de IDs
    $participantes = isset($_POST['participantes']) ? $_POST['participantes'] : [];
    // Temas recibidos como array JSON
    $temas = isset($_POST['temas']) ? json_decode($_POST['temas'], true) : [];
    // Acuerdos recibidos como array JSON
    $acuerdos = isset($_POST['acuerdos']) ? json_decode($_POST['acuerdos'], true) : [];

    if ($titulo && $fecha) {
        $db = new Database();
        $conn = $db->getConnection();
        $crud = new Crud($conn);
        $crud->db_table = 'min_minutas';
        $crud->data = [
            'titulo' => $titulo,
            'objetivo' => $objetivo,
            'idcliente' => $idcliente,
            'idresponsable' => $idresponsable,
            'lugar' => $lugar,
            'fecha' => $fecha,
            'hora_inicio' => $hora_inicio
        ];
        $result = $crud->create();
        if ($result) {
            $id_minuta = $conn->lastInsertId();
            // Guardar participantes (contactos)
            if (!empty($participantes)) {
                // Obtener todos los contactos para buscar el nombre
                $crud->db_table = 'sys_contactos';
                $crud->id_key = null;
                $crud->id_param = null;
                $crud->read();
                $contactos = $crud->data;
                foreach ($participantes as $idcontacto) {
                    $nombre = '';
                    foreach ($contactos as $c) {
                        if ($c['id_contacto'] == $idcontacto) {
                            $nombre = $c['nombre'];
                            if (!empty($c['apellidos'])) {
                                $nombre .= ' ' . $c['apellidos'];
                            }
                            break;
                        }
                    }
                    $crud->db_table = 'min_participantes';
                    $crud->data = [
                        'idminuta' => $id_minuta,
                        'idcontacto' => $idcontacto,
                        'nombre_completo' => $nombre,
                        'rol' => 'Participante'
                    ];
                    $crud->create();
                }
            }
            // Guardar responsable como participante (idcolab)
            if ($idresponsable) {
                // Obtener nombre completo del responsable
                $crud->db_table = 'sys_colaboradores';
                $crud->id_key = 'id_colab';
                $crud->id_param = $idresponsable;
                $crud->read();
                $nombre = '';
                if (!empty($crud->data)) {
                    $colab = $crud->data[0];
                    $nombre = $colab['nombre'];
                    if (!empty($colab['apellidos'])) {
                        $nombre .= ' ' . $colab['apellidos'];
                    }
                }
                $crud->db_table = 'min_participantes';
                $crud->data = [
                    'idminuta' => $id_minuta,
                    'idcolab' => $idresponsable,
                    'nombre_completo' => $nombre,
                    'rol' => 'Responsable'
                ];
                $crud->create();
            }
            // Guardar temas
            foreach ($temas as $t) {
                $crud->db_table = 'min_temas';
                $crud->data = [
                    'id_minuta' => $id_minuta,
                    'titulo' => $t['titulo'],
                    'descripcion' => $t['descripcion'],
                    'observaciones' => '',
                    'orden' => 0
                ];
                $crud->create();
            }
            // Guardar acuerdos
            foreach ($acuerdos as $a) {
                $crud->db_table = 'min_acuerdos';
                $crud->data = [
                    'id_minuta' => $id_minuta,
                    'descripcion' => $a['descripcion'],
                    'idresponsable' => $a['responsable'],
                    'fecha_limite' => $a['fecha'],
                    'estado' => ucfirst($a['estatus']),
                    'fecha_cumplimiento' => null
                ];
                $crud->create();
            }
            header('Location: /app/panel?pg=minuta');
            exit;
        } else {
            echo 'Error al guardar la minuta.';
        }
    } else {
        echo 'Faltan datos obligatorios.';
    }
    exit;
}
?>
