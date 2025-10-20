<!--ya esta corregida-->
</head>
<body>
  <section class="content">
  <!-- PHP eliminado, solo diseño visual -->
    <div class="container-fluid">
      <div class="row">
        <!-- Columna principal -->
        <div class="col-md-12">
          <div class="card card-primary card-outline mt-4">
            <!-- Encabezado de la tarjeta -->
            <div class="card-header d-flex justify-content-between align-items-center ">
              <h3 class="card-title mb-0">Agregar actividad</h3>
            </div>
            
            <!-- Cuerpo de la tarjeta -->
            <div class="card-body">



                <form id="formTarea" action="#" method="post">
                <!-- NOTA: El textarea debe tener name="descripcion" para que se guarde -->
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="task-name" class="form-label">Nombre de actividad</label>
                    <input type="text" id="task-name" name="asunto" class="form-control" placeholder="Ej. Crear módulo de usuarios" required aria-describedby="task-name-help">
                    <small id="task-name-help" class="form-text text-muted">Ingresa el nombre de la actividad.</small>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="proyecto" class="form-label">Proyecto al cual se agregara la tarea: </label>
                    <select id="proyecto" name="id_proyecto" class="form-select" required>
                      <option value="">Seleccionar opción</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="priority" class="form-label">Prioridad</label>
                    <select id="priority" name="prioridad" class="form-select" required>
                      <option value="">Seleccionar opción</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="responsable" class="form-label">Responsable</label>
                    <select id="responsable" name="propietario" class="form-select" required>
                      <option value="">Seleccionar opción</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="status" class="form-label">Estado</label>
                    <select id="status" name="status" class="form-select" required>
                      <option value="">Seleccionar opción</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="fecha_ejecucion" class="form-label">Fecha Ejecución</label>
                    <input type="datetime-local" id="fecha_ejecucion" name="fecha_ejecucion" class="form-control">
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="fecha_vencimiento" class="form-label">Fecha Límite</label>
                    <input type="date" id="fecha_vencimiento" name="fecha_vencimiento" class="form-control" required>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="porcentaje" class="form-label">Porcentaje</label>
                    <select id="porcentaje" name="porcentaje" class="form-select" required>
                      <?php
                        for ($i = 0; $i <= 100; $i++) {
                          echo '<option value="' . $i . '"' . ($i === 0 ? ' selected' : '') . '>' . $i . '%</option>';
                        }
                      ?>
                      <option value="" disabled>Seleccionar opción</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="aviso" class="form-label">Aviso</label>
                    <select id="aviso" name="aviso" class="form-select" required>
                      <option value="1">Sí</option>
                      <option value="0" selected>No</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="programar" class="form-label">Programar</label>
                    <input type="date" id="programar" name="programar" class="form-control">
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="tipo_repeticion" class="form-label">Tipo de Repetición</label>
                    <select id="tipo_repeticion" name="tipo_repeticion" class="form-select" required>
                      <option value="" selected disabled>Seleccionar opción</option>
                      <option value="unica">Única</option>
                      <option value="semanal">Semanal</option>
                      <option value="mensual">Mensual</option>
                      <option value="semestral">Semestral</option>
                      <option value="eventual">Eventual</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="accion" class="form-label">Acción</label>
                    <select id="accion" name="accion" class="form-select" required>
                      <option value="" selected disabled>Seleccionar opción</option>
                      <option value="Llamar">Llamar</option>
                      <option value="Enviar correo">Enviar correo</option>
                      <option value="Reunión">Reunión</option>
                      <option value="Enviar documento">Enviar documento</option>
                    </select>
                  </div>
                </div>
                <div class="mb-3">
               
                </div>
                <div class="mb-3">
                  <div class="card p-3 border border-secondary" style="border-color: #dee2e6 !important; box-shadow: none;">
                    <label for="compose-textarea" class="form-label">Descripción</label>
                    <textarea id="compose-textarea" name="descripcion" class="form-control" style="height: 300px;"></textarea>
                  </div>
                </div>


          <!-- Contenedor de botones fuera del .card-footer -->
          <div class="d-flex justify-content-end flex-wrap gap-3 mt-4 px-3">

            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-edit" style="margin-right: 0.5rem;"></i> Guardar
          </button>
          <a href="javascript:history.back()" style="text-decoration: none; padding: 0.5rem 1rem; border: 1px solid #6c757d; color: #6c757d; border-radius: 0.25rem;">
            <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Volver
          </a>
        </div>

          </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  </body>