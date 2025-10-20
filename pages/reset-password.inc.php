<?php
// reset-password.inc.php
?>
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h1 class="mb-0 text-center">Restablecer contraseña</h1>
    </div>
    <div class="card-body login-card-body">
      <p class="login-box-msg">Ingresa tu nueva contraseña para recuperar el acceso.</p>
      <?php
      if (!empty($_GET['msg'])) {
        $msg = $_GET['msg'];
        $alertClass = isset($_GET['alert']) ? 'alert-' . $_GET['alert'] : 'alert-danger';
        echo '<div class="alert ' . $alertClass . '">' . htmlspecialchars($msg) . '</div>';
        }
      ?>
      <form action="core/changepassword.php" method="post" autocomplete="off" id="resetForm">
        <input type="hidden" name="token" value="<?php echo isset($_GET['key']) ? htmlspecialchars($_GET['key']) : ''; ?>" />
        <div class="input-group mb-1">
          <div class="form-floating">
            <input id="password" type="password" name="password" required class="form-control" placeholder="Nueva contraseña" />
            <label for="password">Nueva contraseña</label>
          </div>
          <button type="button" class="input-group-text" onclick="togglePassword('password', this)"><span class="bi bi-eye"></span></button>
        </div>
        <div class="input-group mb-1">
          <div class="form-floating">
            <input id="confirm_password" type="password" name="confirm_password" required class="form-control" placeholder="Confirmar contraseña" />
            <label for="confirm_password">Confirmar contraseña</label>
          </div>
          <button type="button" class="input-group-text" onclick="togglePassword('confirm_password', this)"><span class="bi bi-eye"></span></button>
        </div>
        <div id="reset-msg" class="mb-2"></div>
        <div class="row">
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
          </div>
        </div>
      </form>
      <p class="mb-1"><a href="login">Iniciar sesión</a></p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
</section>
<!-- /.login-box -->
