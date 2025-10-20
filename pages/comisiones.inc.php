<div class="container mt-4">
	<div class="card shadow">
			<div class="card-header bg-primary text-white d-flex align-items-center" style="font-weight:600;font-size:1.15rem;">
			<span class="mb-0" style="font-size:2rem;line-height:1.1;font-weight:700;">Comisiones</span>
			</div>
		<div class="card-body">
			<div class="d-flex justify-content-start mb-3">
				<button class="btn btn-success fw-semibold" data-bs-toggle="modal" data-bs-target="#modalAgregarComision"><i class="fa fa-plus"></i> Agregar Comisión</button>
			</div>
			<div class="table-responsive">
				<div class="bg-primary text-white px-3 py-2 rounded-top" style="font-weight:600;font-size:1.05rem;">
					<span class="fw-semibold">Listado de Comisiones</span>
				</div>
				<table class="table table-bordered table-striped mb-0 align-middle" style="font-size:1.01rem;">
					<thead class="table-light">
						<tr style="font-weight:600;">
							<th>Cliente</th>
							<th>Comisionista</th>
							<th>% Comisión</th>
						</tr>
					</thead>
					<tbody id="tablaComisionesBody">
						<?php require_once __DIR__ . '/../core/list-comisiones-clientes.php'; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal para agregar comisión -->
<div class="modal fade" id="modalAgregarComision" tabindex="-1" aria-labelledby="modalAgregarComisionLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="modalAgregarComisionLabel">Agregar Comisión</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
			</div>
			<div class="modal-body">
				<form id="formAgregarComision">
					<div class="mb-3">
						<label for="cliente" class="form-label">Cliente</label>
						<select class="form-select" id="cliente" name="cliente" required>
							<option value="">Seleccionar cliente</option>
							<!-- Opciones de clientes se llenarán dinámicamente -->
						</select>
					</div>
					<div class="mb-3">
						<label for="comisionista" class="form-label">Comisionista</label>
						<select class="form-select" id="comisionista" name="comisionista" required>
							<option value="">Seleccionar comisionista</option>
							<!-- Opciones de comisionistas se llenarán dinámicamente -->
						</select>
					</div>
					<div class="mb-3">
						<label for="porcentaje" class="form-label">% Comisión</label>
						<input type="number" class="form-control" id="porcentaje" name="porcentaje" min="0" max="100" step="0.01" required>
					</div>
					<div class="d-flex justify-content-end">
						<button type="submit" class="btn btn-success">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
