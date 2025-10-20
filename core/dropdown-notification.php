<?php
// dropdown-notification.php
require_once __DIR__ . '/class/db.php';
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$database = new Database();
$db = $database->getConnection();

// Suponiendo que el id del colaborador logueado está en $_SESSION['id_colab']
$id_colab = isset($_SESSION['id_colab']) ? intval($_SESSION['id_colab']) : 0;
$notificaciones = [];
$count = 0;
if ($id_colab > 0) {
  $stmt = $db->prepare("SELECT id_notificacion, id_cita, mensaje, fecha, leido FROM sys_notificaciones WHERE id_colab = ? ORDER BY fecha DESC LIMIT 10");
  $stmt->execute([$id_colab]);
  $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $count = $db->query("SELECT COUNT(*) FROM sys_notificaciones WHERE id_colab = $id_colab AND leido = 0")->fetchColumn();
}
?>
<li class="nav-item dropdown">
  <a class="nav-link" data-bs-toggle="dropdown" href="#">
    <i class="bi bi-bell-fill"></i>
    <span class="navbar-badge badge text-bg-warning"><?php echo $count; ?></span>
  </a>
  <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
    <span class="dropdown-item dropdown-header"><?php echo $count; ?> Notificaciones</span>
    <div class="dropdown-divider"></div>
    <?php if ($notificaciones && count($notificaciones) > 0): ?>
      <?php foreach ($notificaciones as $noti): ?>
        <a href="panel?pg=ver-cita&id=<?php echo $noti['id_cita']; ?>" class="dropdown-item py-3<?php echo $noti['leido'] ? '' : ' fw-bold'; ?>" style="white-space: normal;">
          <div class="d-flex align-items-center">
            <span class="me-3" style="font-size:1.5em;color:#0d6efd;"><i class="bi bi-calendar-event"></i></span>
            <div class="flex-grow-1">
              <div class="mb-1" style="font-size:1.05em;line-height:1.2;"><span class="text-dark fw-semibold"><?php echo htmlspecialchars($noti['mensaje']); ?></span></div>
              <div class="text-muted small" style="font-size:0.95em;"><i class="bi bi-clock me-1"></i><?php echo date('d/m/Y H:i', strtotime($noti['fecha'])); ?></div>
            </div>
          </div>
        </a>
        <div class="dropdown-divider"></div>
      <?php endforeach; ?>
    <?php else: ?>
      <a href="#" class="dropdown-item">No tienes notificaciones.</a>
      <div class="dropdown-divider"></div>
    <?php endif; ?>
    <a href="#" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
  </div>
</li>
<!--end::Notifications Dropdown Menu-->