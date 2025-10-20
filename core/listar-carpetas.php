
<?php
// core/listar-carpetas.php
$carpetas = [];
$dir = dirname(__DIR__) . '/uploads';
if (is_dir($dir)) {
  foreach (scandir($dir) as $item) {
    if ($item === '.' || $item === '..') continue;
    $fullPath = $dir . '/' . $item;
    if (is_dir($fullPath)) {
      $numArchivos = 0;
      foreach (scandir($fullPath) as $f) {
        if ($f !== '.' && $f !== '..' && is_file($fullPath . '/' . $f)) $numArchivos++;
      }
      $carpetas[] = [ 'nombre' => $item, 'archivos' => $numArchivos ];
    }
  }
}
?>

<?php if (count($carpetas) > 0): ?>
  <div class="row">
  <?php foreach ($carpetas as $carpeta): ?>
    <div class="col-md-4">
      <div class="card text-center carpeta-card" data-carpeta="<?php echo htmlspecialchars($carpeta['nombre']); ?>" style="cursor:pointer;">
        <div class="card-body d-flex flex-column align-items-center">
          <i class="fas fa-folder fa-3x text-primary mb-3"></i>
          <h6 class="card-title mb-1"><?php echo htmlspecialchars($carpeta['nombre']); ?></h6>
          <p class="card-text text-muted mb-3"><?php echo $carpeta['archivos']; ?> archivos</p>
          <!-- Botón personalizado para subir archivo -->
          <form class="form-upload-carpeta" data-carpeta="<?php echo htmlspecialchars($carpeta['nombre']); ?>">
            <input type="file" class="d-none" id="fileInput_<?php echo htmlspecialchars($carpeta['nombre']); ?>" name="archivo">
            <label for="fileInput_<?php echo htmlspecialchars($carpeta['nombre']); ?>" class="btn btn-sm btn-outline-secondary mb-0" onclick="event.stopPropagation();">
              <i class="fas fa-plus fa-fw"></i>
            </label>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Redirección al hacer clic en la tarjeta de carpeta (excepto en el botón de subir archivo)
      document.querySelectorAll('.carpeta-card').forEach(function(card) {
        card.addEventListener('click', function(e) {
          // Si el click fue en el formulario o en el input file, no redirigir
          if (e.target.closest('form.form-upload-carpeta') || e.target.closest('label')) return;
          var carpeta = card.getAttribute('data-carpeta');
          if (carpeta) {
            window.location.href = 'panel?pg=archivos-carpeta&carpeta=' + encodeURIComponent(carpeta);
          }
        });
      });

      // Subida de archivo al seleccionar archivo en el input
      document.querySelectorAll('.form-upload-carpeta input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function(e) {
          var form = input.closest('form.form-upload-carpeta');
          var archivo = input.files[0];
          if (!archivo || !form) return;
          var carpeta = form.getAttribute('data-carpeta');
          var formData = new FormData();
          formData.append('archivo', archivo);
          formData.append('carpeta', carpeta);
          fetch('core/upload-carpeta.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if(window.Swal){Swal.fire(data.msg,'',data.success?'success':'error');}else{alert(data.msg);}
            if (data.success) location.reload();
          })
          .catch(() => {
            if(window.Swal){Swal.fire('Error de red','','error');}else{alert('Error de red');}
          });
        });
      });
    });
  </script>
<?php else: ?>
  <div class="alert alert-info">No hay carpetas creadas.</div>
<?php endif; ?>
