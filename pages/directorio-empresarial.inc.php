<!-- Directorio Empresarial - Maquetado Bootstrap/DataTables -->
<div class="container-fluid mt-4">
    <div class="card bg-primary text-white shadow mb-3">
        <div class="card-body">
            <h2 class="mb-0">Directorio Empresarial</h2>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center mb-2 gap-2 justify-content-between">
                <form class="row g-2 flex-grow-1 align-items-center" id="form-buscar-directorio" autocomplete="off" style="min-width:0;">
                    <div class="col-md-2">
                        <select class="form-select" name="id_cliente">
                            <option value="">Cliente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="clasificacion">
                            <option value="">Clasificación</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="busqueda" placeholder="Buscar">
                    </div>
                    <div class="col-md-2 d-flex align-items-start">
                        <button type="submit" class="btn btn-secondary w-100">Buscar</button>
                    </div>
                </form>
                <button class="btn btn-success" id="btn-nuevo-directorio" data-bs-toggle="modal" data-bs-target="#modalAgregarDirectorio">
                    + Nuevo registro
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tablaDirectorio">
                    <thead class="table-dark">
                        <tr>
                            <th>Cliente</th>
                            <th>Empresa</th>
                            <th>Clasificación</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Puesto</th>
                            <th>Referencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Registros dinámicos -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar/Editar Directorio -->
<div class="modal fade" id="modalAgregarDirectorio" tabindex="-1" aria-labelledby="modalAgregarDirectorioLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:680px">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAgregarDirectorioLabel">Agregar al Directorio</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formAgregarDirectorio">
                <div class="modal-body">
                    <input type="hidden" name="id_directorio" id="directorio_id">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Cliente</label>
                            <select class="form-select" name="id_cliente" id="selectClienteDirectorio" required>
                                <option value="" selected>Seleccione un cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" name="empresa" maxlength="150" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Clasificación</label>
                            <select class="form-select" name="clasificacion">
                                <option value="">Seleccione clasificación</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contacto</label>
                            <input type="text" class="form-control" name="contacto" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Puesto</label>
                            <input type="text" class="form-control" name="puesto" maxlength="100">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Referencia</label>
                            <input type="text" class="form-control" name="referencia" maxlength="150">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detalle Notas Directorio -->
<div class="modal fade" id="modalNotasDirectorio" tabindex="-1" aria-labelledby="modalNotasDirectorioLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:680px">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNotasDirectorioLabel">Notas del Contacto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formNotasDirectorio">
                <div class="modal-body">
                    <input type="hidden" name="id_directorio" id="notas_id_directorio">
                    <textarea class="form-control" name="notas" id="notas_textarea" rows="10" style="resize:vertical;min-height:200px;"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Notas</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var clientesDict = {};
// Cargar clientes para el select y para el diccionario
function cargarClientesSelect(callback) {
    fetch('core/list-clientes-select.php')
        .then(r => r.text())
        .then(html => {
            // Extraer opciones y armar diccionario
            const temp = document.createElement('select');
            temp.innerHTML = html;
            clientesDict = {};
            Array.from(temp.options).forEach(opt => {
                if (opt.value) clientesDict[opt.value] = opt.text;
            });
            const select = document.getElementById('selectClienteDirectorio');
            select.innerHTML = '<option value="" selected>Seleccione un cliente</option>' + html;
            select.value = "";
            if (typeof callback === 'function') callback();
        });
}

// Llenar el select de clientes en el buscador avanzado
function cargarClientesBuscador() {
    fetch('core/list-clientes-select.php')
        .then(r => r.text())
        .then(html => {
            document.querySelector('form#form-buscar-directorio select[name="id_cliente"]').innerHTML = '<option value="">Cliente</option>' + html;
        });
}

function cargarDirectorio(filtros = {}) {
    const params = new URLSearchParams(filtros).toString();
    fetch('core/listar-directorio-empresarial.php?' + params)
        .then(r => r.json())
        .then(data => {
            const tbody = document.querySelector('#tablaDirectorio tbody');
            tbody.innerHTML = '';
            // Soportar error del backend
            if (data.success === false) {
                tbody.innerHTML = `<tr><td colspan="11" class="text-center">${data.error || 'Sin registros'}</td></tr>`;
                return;
            }
            // Soportar respuesta {success: true, data: [...]} o un array directo
            const registros = Array.isArray(data) ? data : (Array.isArray(data.data) ? data.data : []);
            if (registros.length === 0) {
                tbody.innerHTML = '<tr><td colspan="11" class="text-center">Sin registros</td></tr>';
                return;
            }
            registros.forEach(row => {
                const nombreCliente = clientesDict[row.id_cliente] || row.id_cliente;
                tbody.innerHTML += `<tr>
                    <td>${nombreCliente}</td>
                    <td>${row.empresa}</td>
                    <td>${row.clasificacion || ''}</td>
                    <td>${row.contacto || ''}</td>
                    <td>${row.telefono || ''}</td>
                    <td>${row.puesto || ''}</td>
                    <td>${row.referencia || ''}</td>
                    <td>
                        <button class='btn btn-sm btn-info me-1 btn-notas' title='Detalle' data-id='${row.id_directorio}'><i class="bi bi-card-text"></i></button>
                        <button class='btn btn-sm btn-primary me-1 btn-editar' data-id='${row.id_directorio}'><i class="bi bi-pencil"></i></button>
                        <button class='btn btn-sm btn-danger btn-eliminar' data-id='${row.id_directorio}'><i class="bi bi-trash"></i></button>
                    </td>
                </tr>`;
            });
        });
}


// Buscador avanzado automático
const formBuscar = document.getElementById('form-buscar-directorio');
function getFiltrosFormBuscar() {
    const filtros = {};
    Array.from(formBuscar.elements).forEach(el => {
        if (el.name && el.value) filtros[el.name] = el.value;
    });
    return filtros;
}
formBuscar.addEventListener('submit', function(e) {
    e.preventDefault();
    cargarDirectorio(getFiltrosFormBuscar());
});
// Disparar búsqueda automática al cambiar cliente, clasificación o texto
formBuscar.querySelector('select[name="id_cliente"]').addEventListener('change', function() {
    cargarDirectorio(getFiltrosFormBuscar());
});
formBuscar.querySelector('select[name="clasificacion"]').addEventListener('change', function() {
    cargarDirectorio(getFiltrosFormBuscar());
});
formBuscar.querySelector('input[name="busqueda"]').addEventListener('input', function() {
    cargarDirectorio(getFiltrosFormBuscar());
});

// Mensajes con SweetAlert2
function mostrarMensaje(tipo, titulo, texto) {
    Swal.fire({
        icon: tipo,
        title: titulo,
        text: texto,
        confirmButtonColor: '#6c63ff',
        customClass: { confirmButton: 'btn btn-primary' }
    });
}

// Modal agregar/editar
const formAgregar = document.getElementById('formAgregarDirectorio');
formAgregar.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(formAgregar);
    const id = formData.get('id_directorio');
    let url, dataToSend, d;
    if (id) {
        url = 'core/editar-directorio-empresarial.php';
        dataToSend = formData;
    } else {
        url = 'core/agregar-directorio-empresarial.php';
        formData.delete('id_directorio');
        dataToSend = formData;
    }
    fetch(url, { method: 'POST', body: dataToSend })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                cargarDirectorio();
                formAgregar.reset();
                document.getElementById('directorio_id').value = '';
                // Restaurar título y habilitar selects
                document.getElementById('modalAgregarDirectorioLabel').textContent = 'Agregar al Directorio';
                document.getElementById('selectClienteDirectorio').removeAttribute('disabled');
                formAgregar.clasificacion.removeAttribute('disabled');
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAgregarDirectorio')).hide();
                mostrarMensaje('success', '¡Guardado!', resp.message || 'Registro guardado correctamente');
            } else {
                mostrarMensaje('error', 'Error', resp.error || 'No se pudo guardar el registro');
            }
        });
});

// Delegación de eventos para acciones de la tabla
document.querySelector('#tablaDirectorio tbody').addEventListener('click', function(e) {
    const btn = e.target.closest('button');
    if (!btn) return;
    const id = btn.getAttribute('data-id');
    if (btn.classList.contains('btn-editar')) {
        // Editar registro
        fetch('core/listar-directorio-empresarial.php?id=' + id)
            .then(r => r.json())
            .then(data => {
                if (data && data.data && data.data.length) {
                    const d = data.data[0];
                    formAgregar.id_directorio.value = d.id_directorio;
                    document.getElementById('modalAgregarDirectorioLabel').textContent = 'Editar registro del Directorio';
                    // Llenar selects antes de asignar valor y deshabilitar
                    cargarClientesSelect(function() {
                        const clienteSelect = document.getElementById('selectClienteDirectorio');
                        let found = false;
                        for (let i = 0; i < clienteSelect.options.length; i++) {
                            if (String(clienteSelect.options[i].value) === String(d.id_cliente)) {
                                clienteSelect.selectedIndex = i;
                                found = true;
                                break;
                            }
                        }
                        if (!found && d.id_cliente && d.nombre_comercial) {
                            // Agregar opción especial si no existe
                            const opt = document.createElement('option');
                            opt.value = d.id_cliente;
                            opt.textContent = d.nombre_comercial + ' (inactivo)';
                            clienteSelect.appendChild(opt);
                            clienteSelect.value = d.id_cliente;
                        }
                        clienteSelect.setAttribute('disabled', 'disabled');
                    });
                    // Clasificación (si es dinámico, usar función; si no, directo)
                    setTimeout(function() {
                        const clasificacionSelect = formAgregar.clasificacion;
                        for (let i = 0; i < clasificacionSelect.options.length; i++) {
                            if (clasificacionSelect.options[i].value == d.clasificacion) {
                                clasificacionSelect.selectedIndex = i;
                                break;
                            }
                        }
                        clasificacionSelect.setAttribute('disabled', 'disabled');
                    }, 200);
                    formAgregar.empresa.value = d.empresa;
                    formAgregar.contacto.value = d.contacto || '';
                    formAgregar.telefono.value = d.telefono || '';
                    formAgregar.puesto.value = d.puesto || '';
                    formAgregar.referencia.value = d.referencia || '';
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAgregarDirectorio')).show();
                }
            });
    } else if (btn.classList.contains('btn-notas')) {
        // Notas
        abrirNotasDirectorio(id);
    } else if (btn.classList.contains('btn-eliminar')) {
        // Eliminar
        eliminarDirectorio(id);
    }
});

// Eliminar registro
window.eliminarDirectorio = function(id) {
    if (!confirm('¿Seguro que deseas eliminar este registro?')) return;
    fetch('core/eliminar-directorio-empresarial.php', {
        method: 'POST',
        body: new URLSearchParams({ id })
    })
    .then (r => r.json())
    .then(resp => {
        if (resp.success) {
            cargarDirectorio();
            mostrarMensaje('success', '¡Eliminado!', 'Registro eliminado correctamente');
        } else {
            mostrarMensaje('error', 'Error', resp.error || 'No se pudo eliminar el registro');
        }
    });
}

// Botón de detalle y modal de notas
window.abrirNotasDirectorio = function(id) {
    console.log('abrirNotasDirectorio llamado con id:', id);
    alert('abrirNotasDirectorio llamado con id: ' + id);
    // Obtener notas actuales
    fetch('core/listar-directorio-empresarial.php?id=' + id)
        .then(r => r.json())
        .then(data => {
            let d = Array.isArray(data) ? data[0] : (data.data && data.data[0] ? data.data[0] : null);
            if (d) {
                document.getElementById('notas_id_directorio').value = d.id_directorio;
                document.getElementById('notas_textarea').value = d.notas || '';
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalNotasDirectorio')).show();
            }
        });
}

// Guardar notas
const formNotas = document.getElementById('formNotasDirectorio');
formNotas.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(formNotas);
    fetch('core/editar-directorio-empresarial.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalNotasDirectorio')).hide();
                cargarDirectorio();
                mostrarMensaje('success', '¡Guardado!', 'Notas guardadas correctamente');
            } else {
                mostrarMensaje('error', 'Error', resp.error || 'No se pudo guardar las notas');
            }
        });
});

// Llenar clientes cada vez que se abre el modal para asegurar que siempre estén listados
const modalAgregarDirectorio = document.getElementById('modalAgregarDirectorio');
modalAgregarDirectorio.addEventListener('show.bs.modal', function () {
    setTimeout(cargarClientesSelect, 100);
});

// Inicializar tabla y select del buscador al cargar
cargarClientesSelect(cargarDirectorio);
cargarClientesBuscador();
</script>
