<?php
$nMessage = 2;
//Incluir rutina para recuperar los mensajes de la base de de datos

if ($nMessage > 0) {
  //Ciclo para mostrar los mensajes recuperados 
  echo '<div class="row">';
  echo '<div class="col-lg-12 connectedSortable">';
  echo '<div class="card bg-white shadow p-3">';
  echo '<div class="card bg-white shadow p-4 mb-4">';
  echo '<div class="bg-primary text-white p-3 mb-3 rounded">';
  echo '<h5 class="mb-0">Lista de proyectos</h5>';
  echo '</div>';
  echo '<div class="card-body p-0">';
  echo '<div class="table-responsive">';
  echo '<table id="clientsTable" class="table table-bordered table-striped">';
  echo '<thead>';
  echo '<tr>';
  echo '<th>No.</th>';
  echo '<th>Proyecto</th>';
  echo '<th>Progreso</th>';
  echo '<th>Estado</th>';
  echo '<th></th>';
  echo '</tr>';
  echo '</thead>';
  echo '<tbody>';

  for ($i = 1; $i <= $nMessage; $i++) {
    echo '<tr>';
    echo '<td>1</td>';
    echo '<td>Demanda civil</td>';
    echo '<td>';
    echo '<div class="progress progress-xs">';
    echo '<div class="progress-bar text-bg-primary" style="width: 70%"></div>';
    echo '</div>';
    echo '<span class="badge text-bg-primary">70%</span>';
    echo '</td>';
    echo '<td><span class="badge bg-primary">En progreso</span></td>';
    echo '<td align="center">';
    echo '<a href="?pg=proyectos-casos" class="btn btn-info btn-sm">';
    echo '<i class="bi bi-archive-fill px-2"></i>Ver';
    echo '</a>';
    echo '</td>';
    echo '</tr>';
  }
  echo '</tbody> </table> </div> </div> </div> </div> </div> </div>';

}
