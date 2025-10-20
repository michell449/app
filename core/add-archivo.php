<?php
header('Content-Type: application/json');
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/class/Crud.php';
$db = (new Database())->getConnection();



function nullIfEmpty($v) {
  return (isset($v) && trim($v) !== '') ? trim($v) : null;
}

$nombre = nullIfEmpty($_POST['nombre'] ?? '');
$descripcion = nullIfEmpty($_POST['descripcion'] ?? '');
$tipo_mime = nullIfEmpty($_POST['tipo_mime'] ?? '');
$tamano = nullIfEmpty($_POST['tamaño'] ?? '');
// Forzar id_categoria a null si no es numérico
$id_categoria = nullIfEmpty($_POST['id_categoria'] ?? '');
if (!is_null($id_categoria) && !is_numeric($id_categoria)) {
  $id_categoria = null;
}
// Forzar id_institucion a null si no es numérico
$id_institucion = nullIfEmpty($_POST['id_institucion'] ?? '');
if (!is_null($id_institucion) && !is_numeric($id_institucion)) {
  $id_institucion = null;
}
$id_proyecto = nullIfEmpty($_POST['id_proyecto'] ?? '');
if (!is_null($id_proyecto) && !is_numeric($id_proyecto)) {
  $id_proyecto = null;
}
// Forzar id_tarea a null si no es numérico
$id_tarea = nullIfEmpty($_POST['id_tarea'] ?? '');
if (!is_null($id_tarea) && !is_numeric($id_tarea)) {
  $id_tarea = null;
}
// Forzar id_colab a null si no es numérico
$id_colab = nullIfEmpty($_POST['id_colab'] ?? '');
if (!is_null($id_colab) && !is_numeric($id_colab)) {
  $id_colab = null;
}
$compartido = nullIfEmpty($_POST['compartido'] ?? '');
$descargable = nullIfEmpty($_POST['descargable'] ?? '');
$password = nullIfEmpty($_POST['password'] ?? '');

// Obtener todos las categorias para mapear id_categoria => nombre
if (!isset($categorias) || !isset($nombre_categorias)) {
  $crud_categorias = new Crud($db);
  $crud_categorias->db_table = 'arch_categorias';
  $crud_categorias->read();
  $categorias = $crud_categorias->data;
  $nombre_categorias = [];
  if (is_array($categorias)) {
    foreach ($categorias as $c) {
      $nombre_categorias[$c['id_categoria']] = $c['nombre'];
    }
  }
}
// Obtener todas las instituciones para mapear id_institucion => nombre
if (!isset($instituciones) || !isset($nombre_instituciones)) {
  $crud_instituciones = new Crud($db);
  $crud_instituciones->db_table = 'sys_instituciones';
  $crud_instituciones->read();
  $instituciones = $crud_instituciones->data;
  $nombre_instituciones = [];
  if (is_array($instituciones)) {
    foreach ($instituciones as $i) {
      $nombre_instituciones[$i['id_institucion']] = $i['nombre'];
    }
  }
}
// Obtener todos los proyectos para mapear id_proyecto => nombre
if (!isset($proyectos) || !isset($nombre_proyectos)) {
  $crud_proyectos = new Crud($db);
  $crud_proyectos->db_table = 'proy_proyectos';
  $crud_proyectos->read();
  $proyectos = $crud_proyectos->data;
  $nombre_proyectos = [];
  if (is_array($proyectos)) {
    foreach ($proyectos as $p) {
      $nombre_proyectos[$p['id_proyecto']] = $p['nombre'];
    }
  }
}

// Procesar archivo
$ruta_archivo = '';
if (isset($_FILES['addArchivo']) && $_FILES['addArchivo']['error'] === UPLOAD_ERR_OK) {
    $archivo = $_FILES['addArchivo'];
    $nombre_archivo = basename($archivo['name']);
    $ruta_destino = __DIR__ . '/../uploads/' . $nombre_archivo;
    if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        $ruta_archivo = '/uploads/' . $nombre_archivo;
        $tamano = $archivo['size'];
        $tipo_mime = $archivo['type'];
    }
}

if ($nombre === '' || $ruta_archivo === '') {
    echo json_encode(['success' => false, 'msg' => 'Faltan datos obligatorios']);
    exit;
}




try {
    $stmt = $db->prepare("INSERT INTO arch_archivos (nombre, descripcion, tipo_mime, tamaño, ruta_archivo, id_categoria, id_institucion, id_proyecto, id_tarea, id_colab, compartido, descargable, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $nombre);
    $stmt->bindValue(2, $descripcion);
    $stmt->bindValue(3, $tipo_mime);
    $stmt->bindValue(4, $tamano);
    $stmt->bindValue(5, $ruta_archivo);
    $stmt->bindValue(6, $id_categoria !== null ? $id_categoria : null, $id_categoria !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(7, $id_institucion !== null ? $id_institucion : null, $id_institucion !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(8, $id_proyecto !== null ? $id_proyecto : null, $id_proyecto !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(9, $id_tarea !== null ? $id_tarea : null, $id_tarea !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(10, $id_colab !== null ? $id_colab : null, $id_colab !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(11, $compartido !== null ? $compartido : null, $compartido !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(12, $descargable !== null ? $descargable : null, $descargable !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
    $stmt->bindValue(13, $password);
    $ok = $stmt->execute();
    echo json_encode([
      'success' => $ok,
      'debug' => [
        'id_proyecto' => $id_proyecto,
        'id_categoria' => $id_categoria,
        'id_institucion' => $id_institucion,
        'id_tarea' => $id_tarea,
        'id_colab' => $id_colab
      ]
    ]);
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => 'Error en el servidor: ' . $ex->getMessage()]);
}
