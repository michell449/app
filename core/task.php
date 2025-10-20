<?php
$nMessage = 5;
//Incluir rutina para recuperar los mensajes de la base de de datos

if ($nMessage > 0) {

  echo '

  <div class="card card-white shadow-sm mb-4">
  <div class="card-header bg-primary border-bottom d-flex justify-content-between align-items-center">
  <h4 class="card-title mb-0 text-white">Lista de Usuarios</h4>
  </div>
  <div class="card-body">
  <div class="mb-3">
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">
  <i class="fas fa-plus me-1"></i> Agregar Usuario
  </button>
  </div>
  <div class="table-responsive">
  <table class="table table-striped table-bordered table-hover align-middle">
  <thead class="table-light text-center">
  <tr>
  <th>Número</th>
  <th>ID</th>
  <th>Nombre</th>
  <th>Correo</th>
  <th>Número de Teléfono</th>
  <th>Rol</th>
  <th>Acciones</th>
  </tr>
  </thead>
  <tbody class="text-center"> 
  ';


  for ($i = 1; $i <= $nMessage; $i++) {


    echo '
    <tr class="hover-effect">
    <td>1</td>
    <td>1</td>
    <td>Juan Pérez</td>
    <td>juan.perez@example.com</td>
    <td>7661008741</td>
    <td><span class="badge bg-primary">Administrador</span></td>
    <td>
    <div class="d-flex justify-content-center gap-2 flex-wrap">
    <button class="btn fw-bold text-white me-1" style="min-width:90px;height:38px;background:#17c9f7;border-radius:16px;font-size:1rem;" data-bs-toggle="modal" data-bs-target="#modalUsuario" onclick="cargarDatosUsuario("1","Juan Pérez","juan.perez@example.com","+1234567890","Administrador")" title="Ver">Ver</button>
    <button class="btn fw-bold text-dark me-1" style="min-width:90px;height:38px;background:#ffc107;border-radius:16px;font-size:1rem;" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario" onclick="cargarDatosEditarUsuario("1","Juan Pérez","juan.perez@example.com","+1234567890","Administrador")" title="Editar">Editar</button>
    <button class="btn fw-bold text-white" style="min-width:90px;height:38px;background:#dc3545;border-radius:16px;font-size:1rem;" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario" onclick="document.getElementById("eliminarUsuarioId").value="1"" title="Eliminar">Eliminar</button>
    </div>
    </td>
    </tr>';

  }


  echo'
  </tbody>
  </table>
  </div>
  </div>
  </div>';

}