<div class="container mt-4">
	<div class="card">
		<div class="card-header bg-primary text-white">
			<h4>Agregar nueva minuta</h4>
		</div>
		<div class="card-body">
			<form id="formNuevaMinuta" method="post" action="core/agregar-minuta.php">
				<div class="mb-3">
					<label for="titulo" class="form-label">Título</label>
					<input type="text" class="form-control" id="titulo" name="titulo" required>
				</div>
				<div class="mb-3">
					<label for="objetivo" class="form-label">Objetivo</label>
					<textarea class="form-control" id="objetivo" name="objetivo" rows="2"></textarea>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label for="idcliente" class="form-label">Cliente</label>
						<select class="form-control" id="idcliente" name="idcliente" required>
							<option value="">Selecciona cliente</option>
						</select>
					</div>
					<div class="col-md-6 mb-3">
						<label for="idresponsable" class="form-label">Responsable</label>
						<select class="form-control" id="idresponsable" name="idresponsable" required>
							<option value="">Selecciona responsable</option>
						</select>
					</div>
	                <div class="col-md-12 mb-3">
							<label for="idparticipante" class="form-label">Agregar participante:</label>
							<div class="input-group mb-2">
								<select class="form-control" id="idparticipante">
									<option value="">Selecciona participante</option>
								</select>
								<button type="button" class="btn btn-success" id="agregarParticipante">Agregar</button>
							</div>
							<small class="form-text text-muted">Agrega los asistentes uno por uno.</small>
							<ul class="list-group" id="listaParticipantes"></ul>
	                </div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<label for="fecha" class="form-label">Fecha de reunión</label>
						<input type="date" class="form-control" id="fecha" name="fecha" required>
					</div>
					<div class="col-md-6 mb-3">
						<label for="hora_inicio" class="form-label">Hora de inicio</label>
						<input type="time" class="form-control" id="hora_inicio" name="hora_inicio">
					</div>
				</div>
				<div class="mb-3">
					<label for="lugar" class="form-label">Lugar</label>
					<input type="text" class="form-control" id="lugar" name="lugar">
				</div>
				<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Registrar Minuta</button>
			</form>
		</div>
	</div>
</div>


