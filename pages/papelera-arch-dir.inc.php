
<div class="container-fluid px-2 mb-3">
    <div class="card bg-white shadow-sm mt-4 mb-3">
        <div class="card-header bg-primary text-white p-3 ">
            <h2 class="fw-bold m-0">Papelera de archivos y carpetas</h2>
        </div>
    </div>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="bi bi-trash3 me-2"></i>Papelera de archivos</h4>
            </div>
            <div class="card-body">
                <form id="formFiltroPapeleraArchDir" class="row g-3 mb-3">
                    <div class="col-md-8">
                        <input class="form-control" type="search" placeholder="Buscar en papelera..." aria-label="Buscar" id="busquedaPapelera">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary w-100" id="btnBuscarPapelera"><i class="bi bi-search"></i> Buscar</button>
                    </div>
                </form>
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb" id="breadcrumbPapelera"></ol>
                </nav>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tablaPapeleraArchDir">
                        <thead class="table-danger">
                            <tr>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="papeleraListGroup">
                            <!-- Aquí se llenan los archivos y carpetas vía JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Cargar archivos y carpetas en papelera



let idpadrePapelera = '';

function cargarPapelera(idpadre = '') {
    idpadrePapelera = idpadre;
    let url = 'core/listar-papelera.php';
    if (idpadre) url += '?idpadre=' + encodeURIComponent(idpadre);
    fetch(url)
        .then(r => r.json())
        .then(data => {
            const tbody = document.getElementById('papeleraListGroup');
            tbody.innerHTML = '';
            if (!data.length) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center">La papelera está vacía.</td></tr>';
            } else {
                data.forEach(item => {
                    const icon = item.tipo === 'D' ? 'bi-folder' : 'bi-file-earmark';
                    tbody.innerHTML += `
                        <tr>
                            <td><i class="bi ${icon}"></i> ${item.tipo === 'D' ? 'Carpeta' : 'Archivo'}</td>
                            <td>
                                ${item.tipo === 'D' ? `<a href=\"#\" class=\"enlace-carpeta-papelera\" data-id=\"${item.id}\">${item.nombre}</a>` : item.nombre}
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm me-2" onclick="restaurarDePapelera('${item.id}', '${item.nombre}')"><i class="bi bi-arrow-counterclockwise"></i> Restaurar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarDefinitivo('${item.id}', '${item.nombre}')"><i class="bi bi-trash"></i> Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
            }
            cargarBreadcrumbPapelera(idpadre);
        });
}

function cargarBreadcrumbPapelera(id) {
    const nav = document.getElementById('breadcrumbPapelera');
    if (!id) {
        nav.innerHTML = '<li class="breadcrumb-item active">Papelera</li>';
        return;
    }
    fetch('core/breadcrumb-papelera.php?id=' + encodeURIComponent(id))
        .then(r => r.json())
        .then(bc => {
            let html = '<li class="breadcrumb-item"><a href="#" data-id="">Papelera</a></li>';
            bc.forEach((item, idx) => {
                if (idx === bc.length - 1) {
                    html += `<li class="breadcrumb-item active">${item.nombre}</li>`;
                } else {
                    html += `<li class="breadcrumb-item"><a href="#" data-id="${item.id}">${item.nombre}</a></li>`;
                }
            });
            nav.innerHTML = html;
        });
}

function restaurarDePapelera(id, nombre) {
    if (!confirm('¿Restaurar "' + nombre + '"?')) return;
    fetch('core/restaurar-archivo-papelera.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_archivo=' + encodeURIComponent(id)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) cargarPapelera();
        else alert('No se pudo restaurar: ' + (res.msg || 'Error desconocido'));
    })
    .catch(() => alert('Error de conexión al restaurar.'));
}

function eliminarDefinitivo(id, nombre) {
    if (!confirm('¿Eliminar definitivamente "' + nombre + '"? Esta acción no se puede deshacer.')) return;
    fetch('core/eliminar-definitivo-papelera.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_archivo=' + encodeURIComponent(id)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) cargarPapelera();
        else alert('No se pudo eliminar: ' + (res.msg || 'Error desconocido'));
    })
    .catch(() => alert('Error de conexión al eliminar.'));
}


document.addEventListener('DOMContentLoaded', function() {
    cargarPapelera();
    // Navegación en carpetas de la papelera
    document.getElementById('papeleraListGroup').addEventListener('click', function(e) {
        const enlace = e.target.closest('.enlace-carpeta-papelera');
        if (enlace) {
            e.preventDefault();
            cargarPapelera(enlace.dataset.id);
        }
    });
    // Breadcrumb click
    document.getElementById('breadcrumbPapelera').addEventListener('click', function(e) {
        const a = e.target.closest('a[data-id]');
        if (a) {
            e.preventDefault();
            cargarPapelera(a.dataset.id);
        }
    });
    // Botón buscar (opcional, aquí solo recarga la raíz)
    document.getElementById('btnBuscarPapelera').addEventListener('click', function() {
        cargarPapelera('');
    });
});
</script>
