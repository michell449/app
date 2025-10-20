<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calendario FullCalendar</title>
  <!-- FullCalendar CDN -->
  <style>
    #fullcalendar { max-width: 900px; margin: 40px auto; }
  </style>
</head>
<body>

  <div class="container-fluid mt-4">
    <div class="card bg-white shadow-sm w-100">
      <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Calendario</h2>
      </div>
      <div class="card-body">
        <div class="calendario-contenedor p-3 bg-white rounded shadow-sm">
          <div class="row g-2 align-items-center mb-3">
            <div class="col-md-2 col-6">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-date"></i></span>
                <select id="yearFilter" class="form-select">
                  <option value="2025" selected>2025</option>
                  <option value="2026">2026</option>
                  <option value="2027">2027</option>
                  <option value="2028">2028</option>
                  <option value="2029">2029</option>
                  <option value="2030">2030</option>
                </select>
              </div>
            </div>
            <div class="col-md-2 col-6">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-calendar-month"></i></span>
                <select id="monthFilter" class="form-select">
                  <option value="1">Enero</option>
                  <option value="2">Febrero</option>
                  <option value="3">Marzo</option>
                  <option value="4">Abril</option>
                  <option value="5">Mayo</option>
                  <option value="6">Junio</option>
                  <option value="7">Julio</option>
                  <option value="8">Agosto</option>
                  <option value="9">Septiembre</option>
                  <option value="10">Octubre</option>
                  <option value="11">Noviembre</option>
                  <option value="12">Diciembre</option>
                </select>
              </div>
            </div>
            <div class="col-md-3 col-12 d-flex align-items-end">
              <a href="panel?pg=listado-citas" class="btn btn-success w-100" style="min-height:40px; font-size:1.1em;">
                <i class="bi bi-list"></i> Ver listado de citas
              </a>
            </div>
          </div>
          <div id="fullcalendar"></div>
        </div>
        </div>
      </div>
    </div>

<?php include_once __DIR__ . '/../core/calendario.php'; ?>
</body>
</html>

<?php
// FullCalendar integración y ejemplo de eventos desde PHP
$eventos = [
];
?>