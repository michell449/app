<div class="container-fluid px-2 mb-3">
    <div class="card bg-white shadow-sm mt-4 mb-3">
        <div class="card-header bg-primary text-white p-3 ">
            <h2 class="fw-bold m-0">Papelera de archivos de expedientes</h2>
        </div>
    </div>
    <div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0"><i class="bi bi-trash3 me-2"></i>Papelera de archivos</h4>
        </div>
        <div class="card-body">
            <form id="formFiltroPapelera" class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="filtroCategoria" class="form-label">Categoría</label>
                    <select id="filtroCategoria" class="form-select">
                        <option value="">Todas</option>
                        <option value="acuerdo">Acuerdo</option>
                        <option value="promocion">Promoción</option>
                        <option value="constancia">Constancia</option>
                        <option value="juicio">Juicio</option>
                        <option value="audiencia">Audiencia</option>
                        <option value="caratula">Carátula</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filtroExpediente" class="form-label">ID Expediente</label>
                    <input type="number" id="filtroExpediente" class="form-control" placeholder="ID expediente">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" class="btn btn-secondary w-100" id="btnBuscarPapelera"><i class="bi bi-search"></i> Buscar</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="tablaPapelera">
                    <thead class="table-danger">
                        <tr>
                            <th>ID doc</th>
                            <th>ID Expediente</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Documento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí se llenan los archivos vía JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>


<script>
function cargarPapelera() {
    const idExp = document.getElementById('filtroExpediente').value;
    const cat = document.getElementById('filtroCategoria').value;
    fetch('core/papelera.php?id_expediente=' + encodeURIComponent(idExp) + '&categoria=' + encodeURIComponent(cat))
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#tablaPapelera tbody');
            tbody.innerHTML = '';
            if (data.archivos && data.archivos.length > 0) {
                data.archivos.forEach(arch => {
                    const tr = document.createElement('tr');
                    const rutaFisica = `uploads/Expedientes/${arch.id_expediente}/${arch.nombre_archivo}`;
                    tr.innerHTML = `
                        <td>${arch.id_doc}</td>
                        <td>${arch.id_expediente}</td>
                        <td>${arch.tipo_archivo}</td>
                        <td>${arch.fecha}</td>
                        <td>${arch.documento}</td>
                        <td>
                            <button class="btn btn-primary btn-sm me-1 btn-preview" data-ruta="${rutaFisica}" data-nombre="${arch.nombre_archivo}"><i class="bi bi-eye"></i> Preview</button>
                            <a class="btn btn-secondary btn-sm" href="${rutaFisica}" download title="Descargar"><i class="bi bi-download"></i> Descargar</a>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay archivos en la papelera</td></tr>';
            }
// Modal para preview
const modalPreview = document.createElement('div');
modalPreview.id = 'modalPreviewArchivo';
modalPreview.className = 'modal fade';
modalPreview.tabIndex = -1;
modalPreview.innerHTML = `
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Vista previa de archivo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="previewArchivoBody" style="min-height:400px;max-height:80vh;overflow:auto;"></div>
    </div>
  </div>
`;
document.body.appendChild(modalPreview);

let bsModalPreview = null;
if (window.bootstrap) {
    bsModalPreview = new window.bootstrap.Modal(modalPreview);
}

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-preview');
    if (btn) {
        const ruta = btn.getAttribute('data-ruta');
        const nombre = btn.getAttribute('data-nombre');
        const ext = nombre.split('.').pop().toLowerCase();
        const body = document.getElementById('previewArchivoBody');
        body.innerHTML = '';
        let content = '';
        if (["pdf"].includes(ext)) {
            content = `<iframe src="${ruta}" style="width:100%;height:70vh;border:1px solid #ccc;"></iframe>`;
        } else if (["jpg","jpeg","png","gif","bmp","svg","webp"].includes(ext)) {
            content = `<img src="${ruta}" style="max-width:100%;max-height:70vh;border:1px solid #ccc;object-fit:contain;" />`;
        } else if (["txt","csv","log","md","json","xml","html","css","js","php","py","sql"].includes(ext)) {
            content = `<pre style="background:#f8f9fa;padding:20px;border:1px solid #ccc;border-radius:4px;max-height:70vh;overflow:auto;font-family:monospace;">Cargando contenido...</pre>`;
            fetch(ruta).then(r=>r.text()).then(text=>{body.querySelector('pre').textContent=text;});
        } else if (["doc","docx","xls","xlsx","ppt","pptx"].includes(ext)) {
            content = `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(window.location.origin + '/' + ruta)}" style="width:100%;height:70vh;border:1px solid #ccc;"></iframe>`;
        } else if (["mp4","avi","mov","wmv","flv","webm","mkv"].includes(ext)) {
            content = `<video src="${ruta}" controls style="width:100%;max-height:70vh;border:1px solid #ccc;"></video>`;
        } else if (["mp3","wav","ogg","aac","flac","m4a"].includes(ext)) {
            content = `<audio src="${ruta}" controls style="width:100%;margin:20px 0;"></audio>`;
        } else {
            content = `<iframe src="${ruta}" style="width:100%;height:70vh;border:1px solid #ccc;"></iframe>`;
        }
        body.innerHTML = content;
        if (window.bootstrap && bsModalPreview) {
            bsModalPreview.show();
        } else {
            modalPreview.style.display = 'block';
            modalPreview.classList.add('show');
        }
    }
});
        });
}

document.getElementById('btnBuscarPapelera').addEventListener('click', cargarPapelera);
document.getElementById('filtroCategoria').addEventListener('change', cargarPapelera);
document.getElementById('filtroExpediente').addEventListener('change', cargarPapelera);

document.addEventListener('DOMContentLoaded', function() {
    cargarPapelera();
    document.querySelector('#tablaPapelera').addEventListener('click', function(e) {
        if (e.target.closest('.btn-restaurar')) {
            const id = e.target.closest('.btn-restaurar').dataset.id;
            if (!id) return;
            if (!confirm('¿Seguro que deseas restaurar este archivo?')) return;
            fetch('core/mover-a-papelera.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_archivo: Number(id) })
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    alert('Archivo restaurado correctamente.');
                    cargarPapelera();
                    // Refrescar categorías del expediente si la función existe
                    if (typeof window.cargarArchivosExpediente === 'function') {
                        window.cargarArchivosExpediente();
                    }
                } else {
                    alert('Error al restaurar: ' + (data.msg || 'Error desconocido'));
                }
            })
            .catch(() => {
                alert('Error de red al intentar restaurar.');
            });
        }
    });
});
</script>