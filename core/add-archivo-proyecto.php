<?php
  header('Content-Type: application/json');
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  require_once __DIR__ . '/class/db.php';
  require_once __DIR__ . '/class/crud.php';
  $db = (new Database())->getConnection();

  function nullIfEmpty($v) {
    return (isset($v) && trim($v) !== '') ? trim($v) : null;
  }

  // Función para limpiar nombres de carpeta
  function limpiarNombreCarpeta($nombre) {
    // Remover caracteres especiales y espacios, reemplazar con guiones
    $nombre = preg_replace('/[^\w\s-]/', '', $nombre);
    $nombre = preg_replace('/[\s_]+/', '-', $nombre);
    $nombre = trim($nombre, '-');
    return $nombre;
  }

  // Función para obtener nombre del proyecto
  function obtenerNombreProyecto($db, $id_proyecto) {
    if (!$id_proyecto) return null;
    
    $stmt = $db->prepare("SELECT nombre FROM proy_proyectos WHERE id_proyecto = ?");
    $stmt->execute([$id_proyecto]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $resultado ? $resultado['nombre'] : null;
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

  // Asignar valores por defecto para campos que quitamos del modal
  if ($compartido === null) {
      $compartido = 0; // No compartido por defecto
  }
  if ($descargable === null) {
      $descargable = 1; // Descargable por defecto
  }

  // Procesar archivo
  $ruta_archivo = '';
  if (isset($_FILES['addArchivo']) && $_FILES['addArchivo']['error'] === UPLOAD_ERR_OK) {
      $archivo = $_FILES['addArchivo'];
      $nombre_archivo = basename($archivo['name']);
      
      // Obtener nombre del proyecto para crear la carpeta
      $nombre_proyecto = obtenerNombreProyecto($db, $id_proyecto);
      if (!$nombre_proyecto) {
          echo json_encode(['success' => false, 'msg' => 'No se pudo obtener el nombre del proyecto']);
          exit;
      }
      
      // Limpiar nombre del proyecto para usar como nombre de carpeta
      $carpeta_proyecto = limpiarNombreCarpeta($nombre_proyecto);
      
      // Crear la ruta de la carpeta del proyecto
      $ruta_carpeta_proyecto = __DIR__ . '/../uploads/' . $carpeta_proyecto;
      
      // Crear la carpeta si no existe
      if (!is_dir($ruta_carpeta_proyecto)) {
          if (!mkdir($ruta_carpeta_proyecto, 0755, true)) {
              echo json_encode(['success' => false, 'msg' => 'No se pudo crear la carpeta del proyecto']);
              exit;
          }
      }
      
      // Ruta completa del archivo
      $ruta_destino = $ruta_carpeta_proyecto . '/' . $nombre_archivo;
      
      // Verificar si el archivo ya existe y agregar sufijo si es necesario
      $contador = 1;
      $nombre_base = pathinfo($nombre_archivo, PATHINFO_FILENAME);
      $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
      
      while (file_exists($ruta_destino)) {
          $nuevo_nombre = $nombre_base . '_' . $contador . '.' . $extension;
          $ruta_destino = $ruta_carpeta_proyecto . '/' . $nuevo_nombre;
          $nombre_archivo = $nuevo_nombre;
          $contador++;
      }
      
      if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
          $ruta_archivo = '/uploads/' . $carpeta_proyecto . '/' . $nombre_archivo;
          $tamano = $archivo['size'];
          $tipo_mime = $archivo['type'];
          $nombre = $nombre_archivo; // Usar el nombre final del archivo
      } else {
          echo json_encode(['success' => false, 'msg' => 'Error al mover el archivo']);
          exit;
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
        'msg' => $ok ? 'Archivo subido correctamente' : 'Error al guardar en la base de datos',
        'archivo_info' => [
          'nombre' => $nombre,
          'ruta' => $ruta_archivo,
          'carpeta_proyecto' => $carpeta_proyecto
        ]
      ]);
  } catch (Exception $ex) {
      http_response_code(500);
      echo json_encode(['success' => false, 'msg' => 'Error en el servidor: ' . $ex->getMessage()]);
  }
?>