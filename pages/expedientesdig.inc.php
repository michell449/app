<div class="card bg-white shadow-sm mt-4 mb-3">
    <div class="card-header bg-primary text-white p-3 ">
        <h2 class="fw-bold m-0">Listado de expedientes digitales</h2>
    </div> 
    <div class="d-flex justify-content-end mt-4 mb-4 me-3">
		<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalArchivo"><i class="bi bi-archive me-2"></i>Nuevo expediente</button>
	  </div>
	  <div class="card mb-4">
		<div class="card-body p-0">
			<div class="table-responsive">
                
				<table class="table table-hover align-middle mb-0">
                    
					<thead class="bg-primary text-white">
						<tr>
							<th class="text-center">Numero de expediente</th>
							<th class="text-center">Materia</th>
							<th class="text-center">Parte</th>
							<th class="text-center">Organo juridiccional</th>
							<th class="text-center">Tipo de asunto</th>
							<th class="text-center">Fecha de registro</th>
							<th class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>
                    <?php include __DIR__ . '/../core/expedientes-controller.php'; ?>
                    </tbody>
                </table>
				<!-- begin:: Modal Guardar Archivo -->
				<div class="modal fade" id="modalArchivo" tabindex="-1" aria-labelledby="modalArchivoLabel" aria-hidden="true">
				<div class="modal-dialog modal-md">
				<div class="modal-content">
				<!-- Header -->
				<div class="modal-header">
					<h5 class="modal-title" id="modalArchivoLabel">Nuevo expediente</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>
				<!-- Body -->          
				<div class="modal-body">
					<form id="formArchivo">
			
						<div class="row g-3">
              
              <div class="col-md-6">
								<label for="exp" class="form-label">Número de expediente</label>
								<input type="text" class="form-control" id="exp" name="exp">
							</div>
							<div class="col-md-6">
								<label for="neun" class="form-label">NEUN</label>
								<input type="text" class="form-control" id="neun" name="neun">
							</div>
							<div class="col-md-6">
								<label for="materia" class="form-label">Materia</label>
								<select class="form-select" id="materia" name="materia" required>
									<option value="">Seleccione materia</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="lugar" class="form-label">Lugar</label>
								<select class="form-select" id="lugar" name="lugar" required>
									<option value="">Seleccione lugar</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="parte" class="form-label">Parte</label>
								<input type="text" class="form-control" id="parte" name="parte">
							</div>
							<div class="col-md-6">
								<label for="tipo_organo" class="form-label">Tipo de órgano</label>
								<select class="form-select" id="tipo_organo" name="tipo_organo" required>
									<option value="">Seleccione tipo de órgano</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="organo_jur" class="form-label">Órgano jurisdiccional</label>
								<select class="form-select" id="organo_jur" name="organo_jur" required>
									<option value="">Seleccione órgano jurisdiccional</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="tipo_asunto" class="form-label">Tipo de asunto</label>
								<select class="form-select" id="tipo_asunto" name="tipo_asunto" required>
									<option value="">Seleccione tipo de asunto</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="asunto" class="form-label">Asunto</label>
								<input type="text" class="form-control" id="asunto" name="asunto">
							</div>
							<div class="col-md-6">
								<label for="fecha_registro" class="form-label">Fecha de registro</label>
								<input type="datetime-local" class="form-control" id="fecha_registro" name="fecha_registro">
							</div>
              <div class="col-md-6">
								<label for="cliente" class="form-label">Cliente</label>
								<select class="form-select" id="cliente" name="cliente" required>
									<option value="">Seleccione cliente</option>
								</select>
							</div>
              <div class="col-md-6">
								<label for="demandante" class="form-label">Demandante</label>
								<input type="text" class="form-control" id="demandante" name="demandante">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="submit" form="formArchivo" class="btn btn-primary">Guardar</button>
				</div>
				</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // --- Mostrar expedientes en la tabla ---
  function cargarExpedientes() {
    fetch('/app/core/expedientesdigitales.php')
      .then(response => response.json())
      .then(data => {
        if (!data.success) {
          console.error('Error al consultar expedientes:', data.message);
          return;
        }
        var tbody = document.querySelector('.table.table-hover.align-middle.mb-0 tbody');
        if (!tbody) return;
        tbody.innerHTML = '';
        if (!data.expedientes || data.expedientes.length === 0) {
          var tr = document.createElement('tr');
          tr.innerHTML = '<td colspan="7" class="text-center text-muted">No hay expedientes registrados.</td>';
          tbody.appendChild(tr);
          return;
        }
        // Ordenar de menor a mayor por numero_expediente
        data.expedientes.sort(function(a, b) {
          return Number(a.numero_expediente) - Number(b.numero_expediente);
        });
        data.expedientes.forEach(function(exp) {
          var tr = document.createElement('tr');
          tr.innerHTML = `
            <td class="text-center">${exp.numero_expediente}</td>
            <td class="text-center">${exp.materia || ''}</td>
            <td class="text-center">${exp.parte || ''}</td>
            <td class="text-center">${exp.organo_jur || ''}</td>
            <td class="text-center">${exp.tipo_asunto || ''}</td>
            <td class="text-center">${exp.fecha_creacion ? exp.fecha_creacion.replace('T', ' ').substring(0, 16) : ''}</td>
            <td class="text-center">
              <button class="btn btn-info btn-sm btn-ver-expediente" data-id="${exp.id_expediente}"><i class="bi bi-eye"></i> Ver</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
        // Delegar evento para el botón Ver
        tbody.addEventListener('click', function(e) {
          var btn = e.target.closest('.btn-ver-expediente');
          if (btn) {
            var id = btn.getAttribute('data-id');
            Swal.fire('Ver expediente', 'ID: ' + id, 'info');
          }
        });
      })
      .catch(error => {
        console.error('Error al cargar expedientes:', error);
      });
  }

  // Cargar expedientes al iniciar
  cargarExpedientes();
  // --- Cargar filtros al abrir el modal ---
  var modal = document.getElementById('modalArchivo');
  if (modal) {
    modal.addEventListener('show.bs.modal', function () {
      fetch('/app/core/expedientesdig-filtros-controller.php')
        .then(response => {
          if (!response.ok) throw new Error('Error en la respuesta del servidor');
          return response.json();
        })
        .then(data => {
          fillSelect('materia', data.materia, 'clave', 'nombre');
          fillSelect('lugar', data.lugar, 'clave', 'nombre');
          fillSelect('tipo_organo', data.tipo_organo, 'clave', 'nombre');
          fillSelect('tipo_asunto', data.tipos_asunto, 'clave', 'nombre');
          fillSelect('organo_jur', data.organo_jur, 'clave', 'nombre');
          // Nuevo: llenar select de clientes comerciales
          fillSelect('cliente', data.clientes, 'id_cliente', 'nombre_comercial');
        })
        .catch(error => {
          if (window.Swal) {
            Swal.fire('Error', 'No se pudieron cargar los filtros: ' + error.message, 'error');
          }
        });
    });
  }

  function fillSelect(selectId, items, valueField, textField) {
    const select = document.getElementById(selectId);
    if (!select) return;
    select.innerHTML = '<option value="">Seleccione</option>';
    if (!Array.isArray(items)) return;
    items.forEach(item => {
      if (item && item.hasOwnProperty(valueField) && item.hasOwnProperty(textField)) {
        const option = document.createElement('option');
        option.value = item[valueField];
        option.textContent = item[textField];
        select.appendChild(option);
      }
    });
  }

  // --- Guardar expediente digital ---
  var form = document.getElementById('formArchivo');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(form);
      // Tomar el valor de NEUN y enviarlo como expediente_unico
      var neunInput = document.getElementById('neun');
      if (neunInput) {
        formData.set('expediente_unico', neunInput.value);
      }
      var campos = [
        'exp',
        'materia',
        'lugar',
        'parte',
        'tipo_organo',
        'organo_jur',
        'tipo_asunto',
        'asunto',
        'fecha_registro',
        'cliente',
        'demandante'
      ];
      campos.forEach(function(campo) {
        var input = document.getElementById(campo);
        if (input && !formData.has(campo)) {
          formData.append(campo, input.value);
        }
      });
      for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
      }
      fetch('app/../core/expedientesdigitales.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log(data);
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: '¡Expediente guardado!',
            text: data.message || 'Expediente guardado correctamente.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
          }).then(() => {
            form.reset();
            var modalEl = document.getElementById('modalArchivo');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
            // Forzar eliminación del backdrop y la clase modal-open
            setTimeout(function() {
              // Eliminar cualquier backdrop que quede
              document.querySelectorAll('.modal-backdrop').forEach(function(bd) { bd.parentNode.removeChild(bd); });
              // Quitar la clase modal-open del body
              document.body.classList.remove('modal-open');
              // Quitar el padding del body si quedó
              document.body.style.paddingRight = '';
            }, 400);
            // Refrescar la tabla de expedientes sin recargar la página
            // cargarExpedientes();
            // Refrescar toda la página para limpiar selects y tabla
            window.location.reload();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message || 'Error al guardar el expediente.',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Cerrar'
          });
        }
      })
      .catch(error => {
        console.error('Fetch error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error de conexión',
          text: 'No se pudo conectar con el servidor o la respuesta no es JSON.'
        });
      });
    });
  }
});
</script>