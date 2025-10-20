<?php
  include_once dirname(__DIR__, 1) . '/config.php';
  require_once __DIR__ . '/class/db.php';
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  $id_usuario = $_SESSION['USR_ID'] ?? 1;
  $db = new Database();
  $conn = $db->getConnection();
  // Detectar si estamos en la página de mensajes
  $nMessage = 0;
  $mensajes = [];
  $isMensajesPage = false;
  if (isset($_GET['pg']) && $_GET['pg'] === 'mensajes') {
    $isMensajesPage = true;
  }
  if ($conn) {
    // Obtener las conversaciones donde el usuario participa
    $sqlConv = "SELECT cu.id_conversacion FROM sys_conversacion_usuarios cu WHERE cu.id_usuario = :id_usuario";
    $stmtConv = $conn->prepare($sqlConv);
    $stmtConv->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmtConv->execute();
    $convs = $stmtConv->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($convs)) {
      $convList = implode(',', array_map('intval', $convs));
      // Contar mensajes nuevos (no leídos) en todas las conversaciones
      if (!$isMensajesPage) {
        $sqlCount = "SELECT COUNT(*) FROM sys_mensajes WHERE id_conversacion IN ($convList) AND status = 'Enviado' AND id_usuario != :id_usuario";
        $stmtCount = $conn->prepare($sqlCount);
        $stmtCount->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtCount->execute();
        $nMessage = $stmtCount->fetchColumn();
      }
      // Obtener los últimos mensajes de cada conversación
      $sql = "SELECT m.*, c.nombre, c.apellidos FROM sys_mensajes m LEFT JOIN sys_colaboradores c ON m.id_usuario = c.id_usuario WHERE m.id_conversacion IN ($convList) AND m.status IN ('Enviado','Leído') AND m.fecha_publicacion = (SELECT MAX(m2.fecha_publicacion) FROM sys_mensajes m2 WHERE m2.id_conversacion = m.id_conversacion) ORDER BY m.fecha_publicacion DESC LIMIT 5";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  }
  //Desplegando menu de mensajes desplegable
  echo '<li class="nav-item">';
  echo '<a class="nav-link position-relative" href="panel?pg=mensajes">';
  echo '<i class="bi bi-chat-text"></i>';
  if ($nMessage > 0) {
    echo '<span class="navbar-badge badge text-bg-danger">' . $nMessage . '</span>';
  }
  echo '</a>';
  echo '</li>';
