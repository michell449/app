<?php
// Controlador para la página ver-proy
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/crud.php';

// Obtener el ID del proyecto desde la URL
$proyecto_id = $_GET['id'] ?? null;

if (!$proyecto_id) {
    header("Location: panel?pg=proyectos-dashboard");
    exit;
}

// Establecer conexión a la base de datos
$db = new Database();
$conn = $db->getConnection();

// Obtener datos del proyecto
$crud = new Crud($conn);
$crud->db_table = 'proy_proyectos';
$crud->id_param = $proyecto_id;
$crud->id_key = 'id_proyecto';
$crud->read();
$proyecto = $crud->data[0] ?? null;

if (!$proyecto) {
    header("Location: panel?pg=proyectos-dashboard");
    exit;
}

// Obtener datos del supervisor
$supervisor = '';
if ($proyecto['supervisor']) {
    $crud_colab = new Crud($conn);
    $crud_colab->db_table = 'sys_colaboradores';
    $crud_colab->id_param = $proyecto['supervisor'];
    $crud_colab->id_key = 'id_colab';
    $crud_colab->read();
    $colab = $crud_colab->data[0] ?? null;
    $supervisor = $colab ? $colab['nombre'] . ' ' . $colab['apellidos'] : 'Sin asignar';
}

// Obtener nombre del proyecto para la sección de archivos
$nombre_proyecto = htmlspecialchars($proyecto['nombre']);
$get_nombre_proyecto_html = '<!-- Nombre del proyecto obtenido -->';

// Generar HTML de colaboradores del proyecto
ob_start();
include __DIR__ . '/list-colaboradores-proyecto.php';
$lista_colaboradores_html = ob_get_clean();

// Contar colaboradores
$sql_count = "SELECT COUNT(*) as total FROM proy_colabproyectos WHERE id_proyecto = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->execute([$proyecto_id]);
$colab_count = $stmt_count->fetchColumn();

// Generar HTML de colaboradores para select
$crud_colaboradores = new Crud($conn);
$crud_colaboradores->db_table = 'sys_colaboradores';
$crud_colaboradores->read();
$colaboradores_todos = $crud_colaboradores->data;

$colaboradores_select_html = '';
if (is_array($colaboradores_todos)) {
    foreach ($colaboradores_todos as $c) {
        $colaboradores_select_html .= '<option value="' . $c['id_colab'] . '">' . 
                                     htmlspecialchars($c['nombre'] . ' ' . $c['apellidos']) . '</option>';
    }
}

// Generar HTML de tareas del proyecto
ob_start();
include __DIR__ . '/list-tareas-proyecto.php';
$list_tareas_html = ob_get_clean();

// Generar HTML de archivos del proyecto
ob_start();
include __DIR__ . '/listar-archivos-proyecto.php';
$listar_archivos_html = ob_get_clean();
?>