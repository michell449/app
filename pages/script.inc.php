
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script
src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
crossorigin="anonymous"
></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
crossorigin="anonymous"
></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="js/adminlte.js"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
  const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
  };
  document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
      OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
        scrollbars: {
          theme: Default.scrollbarTheme,
          autoHide: Default.scrollbarAutoHide,
          clickScroll: Default.scrollbarClickScroll,
        },
      });
    }
  });
</script>
<!--end::OverlayScrollbars Configure-->
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--end::Script-->
<!-- FullCalendar CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<!-- Script solo para catalogo de productos -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'catalogos-productos' || (isset($_GET['pg']) && $_GET['pg'] === 'catalogos-productos')): ?> 
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      // Función para limpiar el modal de categoría
      function limpiarModalCategoria() {
          document.getElementById('filtroCategoria').selectedIndex = 0;
          document.getElementById('productosPorCategoria').innerHTML = '';
      }

      // Limpiar al cerrar con la X o al ocultar el modal
      document.getElementById('modalCategoria').addEventListener('hidden.bs.modal', function() {
          limpiarModalCategoria();
      });
      // Guardar productos seleccionados para el cliente
      document.getElementById('btnGuardarProductosModal').addEventListener('click', function() {
          // Obtener id_cliente seleccionado
          const inputNombre = document.getElementById('clienteSeleccionadoExterior');
          const rfc = document.getElementById('filtroClienteExterior').value.trim();
          if (!rfc || !inputNombre.value || inputNombre.value === 'No encontrado' || inputNombre.value === 'RFC vacío' || inputNombre.value === 'Error de búsqueda') {
              Swal.fire('Error', 'Selecciona un cliente válido antes de guardar.', 'error');
              return;
          }
          // Obtener id_cliente desde la última búsqueda exitosa
          fetch('core/buscar-cliente-rfc.php?rfc=' + encodeURIComponent(rfc))
              .then(response => response.json())
              .then(data => {
                  if (!(data.success && data.id_cliente)) {
                      Swal.fire('Error', 'No se pudo identificar el cliente.', 'error');
                      return;
                  }
                  const id_cliente = data.id_cliente;
                  // Obtener productos seleccionados
                  const productos = [];
                  document.querySelectorAll('#productosPorCategoria tr').forEach(tr => {
                      const clave = tr.children[0]?.textContent.trim();
                      const facturable = tr.querySelector('.facturable-check')?.checked ? 1 : 0;
                      if (clave && tr.querySelector('.facturable-check')?.checked) {
                          productos.push({ clave, id_cliente });
                      }
                  });
                  if (productos.length === 0) {
                      Swal.fire('Atención', 'Selecciona al menos un producto facturable.', 'warning');
                      return;
                  }
                  // Enviar productos a guardar
                  fetch('core/agregar-productos-cliente.php', {
                      method: 'POST',
                      headers: { 'Content-Type': 'application/json' },
                      body: JSON.stringify({ productos })
                  })
                  .then(response => response.json())
                  .then(res => {
                      if (res.success) {
                          Swal.fire({
                              title: '¡Guardado!',
                              text: 'Productos agregados correctamente.',
                              icon: 'success',
                              timer: 1500,
                              showConfirmButton: false
                          });
                          // Cerrar modal, limpiar y recargar tabla principal
                          const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCategoria'));
                          modal.hide();
                          limpiarModalCategoria();
                          mostrarProductosCliente(id_cliente);
                      } else {
                          Swal.fire('Error', res.error || 'No se pudieron guardar los productos.', 'error');
                      }
                  })
                  .catch(() => {
                      Swal.fire('Error', 'Error de conexión al guardar.', 'error');
                  });
              });
      });
      // Ocultar el botón al cargar la página
      document.getElementById('btnAgregarNuevoProducto').disabled = true;
      // Buscar cliente por RFC y mostrar nombre comercial
      const btnAgregarNuevoProducto = document.getElementById('btnAgregarNuevoProducto');
      document.getElementById('btnBuscarClienteExterior').addEventListener('click', function() {
          const rfc = document.getElementById('filtroClienteExterior').value.trim();
          const inputNombre = document.getElementById('clienteSeleccionadoExterior');
          inputNombre.value = '';
      btnAgregarNuevoProducto.disabled = true;
          if (!rfc) {
              inputNombre.value = 'RFC vacío';
              mostrarProductosCliente(null);
              return;
          }
          fetch('core/buscar-cliente-rfc.php?rfc=' + encodeURIComponent(rfc))
              .then(response => response.json())
              .then(data => {
                  if (data.success && data.nombre_comercial) {
                      inputNombre.value = data.nombre_comercial;
                  } else {
                      inputNombre.value = 'No encontrado';
                  }
                  if (data.success && data.id_cliente) {
                      btnAgregarNuevoProducto.disabled = false;
                      mostrarProductosCliente(data.id_cliente);
                  } else {
                      btnAgregarNuevoProducto.disabled = true;
                      mostrarProductosCliente(null);
                  }
              })
              .catch(() => {
                  inputNombre.value = 'Error de búsqueda';
                  btnAgregarNuevoProducto.disabled = true;
                  mostrarProductosCliente(null);
              });
      });

      // Función para mostrar productos del cliente seleccionado
      function mostrarProductosCliente(idCliente) {
          const tbody = document.querySelector('#tablaCategorias tbody');
          tbody.innerHTML = '';
          if (!idCliente) {
              const tr = document.createElement('tr');
              tr.innerHTML = `<td colspan="3" class="text-center">No hay ningún producto registrado</td>`;
              tbody.appendChild(tr);
              return;
          }
          fetch('core/listar-productos-cliente.php?id_cliente=' + idCliente)
              .then(response => response.json())
              .then(data => {
                  if (data.success && Array.isArray(data.productos) && data.productos.length > 0) {
                      data.productos.forEach(prod => {
                          const tr = document.createElement('tr');
                          tr.innerHTML = `
                              <td>${prod.clave}</td>
                              <td>${prod.descripcion || ''}</td>
                              <td>
                                  <button class="btn btn-sm btn-danger btn-eliminar-producto" data-clave="${prod.clave}" data-id-cliente="${idCliente}" title="Eliminar">
                                      <i class="fas fa-trash-alt"></i>
                                  </button>
                              </td>
                          `;
                          tbody.appendChild(tr);
                      });
                  } else {
                      const tr = document.createElement('tr');
                      tr.innerHTML = `<td colspan="3" class="text-center">No hay ningún producto registrado</td>`;
                      tbody.appendChild(tr);
                  }
                  // Agregar evento a los botones eliminar
                  document.querySelectorAll('.btn-eliminar-producto').forEach(btn => {
                      btn.addEventListener('click', function() {
                          const clave = this.getAttribute('data-clave');
                          const idCliente = this.getAttribute('data-id-cliente');
                          // SweetAlert2 confirmación
                          Swal.fire({
                              title: '¿Eliminar producto?',
                              text: 'Esta acción no se puede deshacer.',
                              icon: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#d33',
                              cancelButtonColor: '#3085d6',
                              confirmButtonText: 'Sí, eliminar',
                              cancelButtonText: 'Cancelar'
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  fetch('core/eliminar-producto-cliente.php', {
                                      method: 'POST',
                                      headers: { 'Content-Type': 'application/json' },
                                      body: JSON.stringify({ clave, id_cliente: idCliente })
                                  })
                                  .then(response => response.json())
                                  .then(data => {
                                      if (data.success) {
                                          Swal.fire({
                                              title: 'Eliminado',
                                              text: 'El producto fue eliminado correctamente.',
                                              icon: 'success',
                                              timer: 1500,
                                              showConfirmButton: false
                                          });
                                          mostrarProductosCliente(idCliente);
                                      } else {
                                          Swal.fire('Error', data.error || 'No se pudo eliminar', 'error');
                                      }
                                  })
                                  .catch(() => {
                                      Swal.fire('Error', 'Error de conexión al eliminar', 'error');
                                  });
                              }
                          });
                      });
                  });
              })
              .catch(() => {
                  const tr = document.createElement('tr');
                  tr.innerHTML = `<td colspan="3" class="text-center">Error al cargar productos</td>`;
                  tbody.appendChild(tr);
              });
      }
      // Llenar el select de categorías desde el nuevo endpoint
      fetch('core/listar-categorias-catalogo.php')
          .then(response => response.json())
          .then(data => {
              if (data.success && Array.isArray(data.categorias)) {
                  const select = document.getElementById('filtroCategoria');
                  data.categorias.forEach(cat => {
                      const option = document.createElement('option');
                      option.value = cat.clave;
                      option.textContent = cat.grupo;
                      select.appendChild(option);
                  });
              }
          });

      // Al cambiar la categoría, cargar productos de esa categoría
      document.getElementById('filtroCategoria').addEventListener('change', function() {
          const grupo = this.value;
          const tbody = document.getElementById('productosPorCategoria');
          tbody.innerHTML = '';
          if (!grupo) return;
          fetch('core/listar-productos-por-categoria.php?grupo=' + grupo)
              .then(response => response.json())
              .then(data => {
                  if (data.success && Array.isArray(data.productos)) {
                      data.productos.forEach(prod => {
                          const tr = document.createElement('tr');
                          tr.innerHTML = `
                              <td>${prod.clave}</td>
                              <td>${prod.descripcion || ''}</td>
                              <td class="text-center">
                                  <input type="checkbox" class="form-check-input facturable-check" />
                              </td>
                          `;
                          tbody.appendChild(tr);
                      });
                  }
              });
      });

      // Botón para seleccionar todos los productos como facturables
      document.getElementById('btnSeleccionarTodos').addEventListener('click', function() {
          document.querySelectorAll('.facturable-check').forEach(chk => {
              chk.checked = true;
          });
      });
  });
  </script>
<?php endif; ?>
<!-- Script solo para comisiones -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'comisiones' || (isset($_GET['pg']) && $_GET['pg'] === 'comisiones')): ?> 
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modalAgregarComision');
    if (modal) {
      modal.addEventListener('show.bs.modal', function() {
        var selectCliente = document.getElementById('cliente');
              var selectComisionista = document.getElementById('comisionista');
        if (selectCliente) {
          selectCliente.innerHTML = '<option value="">Cargando clientes...</option>';
          fetch('/app/core/list-clientes-cfdis-select.php')
            .then(res => res.text())
            .then(html => {
              selectCliente.innerHTML = '<option value="">Seleccionar cliente</option>' + html;
            })
            .catch(() => {
              selectCliente.innerHTML = '<option value="">Error al cargar clientes</option>';
            });
        }
              if (selectComisionista) {
                  selectComisionista.innerHTML = '<option value="">Cargando comisionistas...</option>';
                  fetch('/app/core/list-comisionistas-select.php')
                      .then(res => res.text())
                      .then(html => {
                          selectComisionista.innerHTML = '<option value="">Seleccionar comisionista</option>' + html;
                      })
                      .catch(() => {
                          selectComisionista.innerHTML = '<option value="">Error al cargar comisionistas</option>';
                      });
              }
      });
    }

    // Script para guardar comisión
    var form = document.getElementById('formAgregarComision');
    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        var datos = new FormData(form);
        fetch('/app/core/add-comision.php', {
          method: 'POST',
          body: datos
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: '¡Guardado!',
              text: data.msg,
              showConfirmButton: false,
              timer: 1500
            });
            form.reset();
            var modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) modalInstance.hide();
            setTimeout(function() { location.reload(); }, 1600);
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.msg
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo guardar la comisión.'
          });
        });
      });
    }
  });
  </script>
<?php endif; ?>




<!-- Script solo para mensajes -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'mensajes' || (isset($_GET['pg']) && $_GET['pg'] === 'mensajes')): ?>
   <script>
    window.USR_ID = <?php echo json_encode($_SESSION['USR_ID'] ?? 1); ?>;
  </script>
  <script>
    function mostrarBandejaEntrada() {
    document.querySelector('.col-md-9 .card-title').textContent = 'Bandeja de Entrada';
    cargarMensajes();
    }
  </script>
  <script>
    function mostrarEnviados() {
    document.querySelector('.col-md-9 .card-title').textContent = 'Enviados';
    fetch('/app/core/list-mensajes.php?tipo=enviados')
      .then(res => res.json())
      .then(data => {
        const mensajesPorPagina = 20;
        let paginaActual = window.paginaMensajesEnviados || 1;
        const totalMensajes = data.length;
        const totalPaginas = Math.max(1, Math.ceil(totalMensajes / mensajesPorPagina));
        paginaActual = Math.min(Math.max(1, paginaActual), totalPaginas);
        window.paginaMensajesEnviados = paginaActual;
        const inicio = (paginaActual - 1) * mensajesPorPagina;
        const fin = Math.min(inicio + mensajesPorPagina, totalMensajes);
        const tbody = document.querySelector('.mailbox-messages tbody');
        tbody.innerHTML = '';
        for (let i = inicio; i < fin; i++) {
          const msg = data[i];
          tbody.innerHTML += `
            <tr>
              <td><input type='checkbox' value='${msg.id_mensaje}'></td>
              <td class='mailbox-status'><span class='badge ${msg.status === 'Leído' ? 'bg-success text-white' : (msg.status === 'Enviado' ? 'bg-primary text-white' : (msg.status === 'Archivado' ? 'bg-danger text-white' : 'bg-warning text-dark'))}' style='font-size:0.75em;padding:2px 8px;'>${msg.status}</span></td>
              <td class='mailbox-subject'><b><a href='#' onclick="mostrarConversacion(${msg.id_conversacion})">${msg.mensaje.substring(0,15)}...</a></b></td>
              <td class='mailbox-name'><span><i class='fas fa-user'></i> <span id='participantes-${msg.id_conversacion}'>Cargando...</span></span></td>
              <td class='mailbox-date'>${msg.fecha_publicacion}</td>
              <td class='mailbox-actions'>
                <button class='btn btn-sm btn-secondary' onclick="archivarConversacion(${msg.id_conversacion})"><i class='fas fa-archive'></i> Archivar</button>
              </td>
            </tr>
          `;
          // Cargar participantes para cada fila
          setTimeout(() => {
            fetch(`/app/core/list-participantes.php?id_conversacion=${msg.id_conversacion}`)
              .then(res => res.json())
              .then(participantes => {
                const nombresArr = participantes.map(p => p.nombre + (p.apellidos ? ' ' + p.apellidos : ''));
                const span = document.getElementById('participantes-' + msg.id_conversacion);
                if (span) {
                  if (nombresArr.length === 0) {
                    span.textContent = 'Sin participantes';
                  } else if (nombresArr.length === 1) {
                    span.textContent = nombresArr[0];
                  } else {
                    const extra = nombresArr.length - 1;
                    span.textContent = `${nombresArr[0]} +${extra} más`;
                    span.title = nombresArr.join(', ');
                    span.style.cursor = 'pointer';
                  }
                }
              });
          }, 0);
        }
        // Actualizar paginación en el footer
        const infoPaginacion = document.getElementById('infoPaginacion');
        if (infoPaginacion) {
          if (totalMensajes === 0) {
            infoPaginacion.textContent = '0-0/0';
          } else {
            infoPaginacion.textContent = `${inicio + 1}-${fin}/${totalMensajes}`;
          }
        }
        const btnAnterior = document.getElementById('btnAnterior');
        const btnSiguiente = document.getElementById('btnSiguiente');
        if (btnAnterior) {
          btnAnterior.disabled = paginaActual === 1;
          btnAnterior.onclick = function() {
            if (paginaActual > 1) {
              window.paginaMensajesEnviados = paginaActual - 1;
              mostrarEnviados();
            }
          };
        }
        if (btnSiguiente) {
          btnSiguiente.disabled = paginaActual === totalPaginas;
          btnSiguiente.onclick = function() {
            if (paginaActual < totalPaginas) {
              window.paginaMensajesEnviados = paginaActual + 1;
              mostrarEnviados();
            }
          };
        }
      });
    }
  </script>
  <script>
    // Contadores de mensajes por carpeta
    function actualizarContadoresCarpetas() {
    Promise.all([
      fetch('/app/core/list-mensajes.php').then(res => res.json()),
      fetch('/app/core/list-mensajes.php?tipo=enviados').then(res => res.json()),
      fetch('/app/core/list-mensajes.php?tipo=papelera').then(res => res.json())
    ]).then(([entrada, enviados, papelera]) => {
      document.getElementById('contadorEntrada').textContent = entrada.length;
      document.getElementById('contadorEnviados').textContent = enviados.length;
      document.getElementById('contadorPapelera').textContent = papelera.length;
    });
    }
    document.addEventListener('DOMContentLoaded', actualizarContadoresCarpetas);
    // Actualizar al cambiar de carpeta o tras acciones
    window.actualizarContadoresCarpetas = actualizarContadoresCarpetas;
    function minimizarCarpetas() {
    const cardBody = document.querySelector('.card .card-body');
    const icon = document.getElementById('iconMinimizarCarpetas');
    if (cardBody.style.display === 'none') {
      cardBody.style.display = '';
      icon.classList.remove('fa-plus');
      icon.classList.add('fa-minus');
    } else {
      cardBody.style.display = 'none';
      icon.classList.remove('fa-minus');
      icon.classList.add('fa-plus');
    }
    }
  </script>
  <script>
    // Cargar colaboradores en el select
    function cargarColaboradores(){
      fetch('/app/core/list-colaboradores.php')
        .then(res => res.json())
        .then(data => {
          const cont = document.getElementById('participantesCheckboxes');
          cont.innerHTML = '';
          data.forEach(colab => {
            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.style.marginRight = '12px';
            const chk = document.createElement('input');
            chk.type = 'checkbox';
            chk.className = 'form-check-input';
            chk.name = 'participantes[]';
            chk.value = colab.id_usuario;
            label.appendChild(chk);
            label.appendChild(document.createTextNode(` ${colab.nombre} ${colab.apellidos} (${colab.correo})`));
            cont.appendChild(label);
          });
        });
    }
    // Mostrar modal al hacer clic en Redactar (nuevo mensaje)
    const btnRedactar = document.getElementById('btnRedactar');
    if(btnRedactar){
      btnRedactar.onclick = function(){
        document.getElementById('modalRedactar').style.display = 'block';
        document.getElementById('id_padre').value = '';
        document.getElementById('formRedactar').reset();
        cargarColaboradores();
      };
    }

    // Función para responder a un mensaje
    function responderMensaje(id_mensaje, destinatarioId){
    // Cierra el modal de mensaje completo si está abierto
    if (document.getElementById('modalMensajeCompleto')) {
      document.getElementById('modalMensajeCompleto').remove();
    }
    document.getElementById('modalRedactar').style.display = 'block';
    document.getElementById('id_padre').value = id_mensaje;
    cargarColaboradores();
    setTimeout(function(){
      document.getElementById('destino').value = destinatarioId;
      if (document.getElementById('destino').value) {
        document.getElementById('destino').disabled = true;
      }
    }, 400);
    }

    // Cargar mensajes dinámicamente with botón Responder
    function cargarMensajes(){
    const searchValue = document.getElementById('inputBuscarCorreo') ? document.getElementById('inputBuscarCorreo').value.trim() : '';
    let url = '/app/core/list-mensajes.php';
    if (searchValue) {
      url += '?buscar=' + encodeURIComponent(searchValue);
    }
    // Paginación
    const mensajesPorPagina = 20;
    let paginaActual = window.paginaMensajes || 1;
    fetch(url)
      .then(res => res.json())
      .then(data => {
        const totalMensajes = data.length;
        const totalPaginas = Math.max(1, Math.ceil(totalMensajes / mensajesPorPagina));
        paginaActual = Math.min(Math.max(1, paginaActual), totalPaginas);
        window.paginaMensajes = paginaActual;
        const inicio = (paginaActual - 1) * mensajesPorPagina;
        const fin = Math.min(inicio + mensajesPorPagina, totalMensajes);
        const tbody = document.querySelector('.mailbox-messages tbody');
        tbody.innerHTML = '';
        for (let i = inicio; i < fin; i++) {
          const conv = data[i];
          // Escapar comillas y caracteres especiales para el mensaje
          const mensajeEscapado = (conv.mensaje || '').replace(/'/g, "\\'").replace(/\n/g, '\\n').replace(/\r/g, '');
          // Mostrar participantes (puedes obtenerlos con una petición extra si lo deseas)
          let participantesHtml = '';
          if (conv.participantes && Array.isArray(conv.participantes)) {
            const nombresArr = conv.participantes.map(p => p.nombre + (p.apellidos ? ' ' + p.apellidos : ''));
            if (nombresArr.length === 0) {
              participantesHtml = '<span class="text-muted">Sin participantes</span>';
            } else if (nombresArr.length === 1) {
              participantesHtml = `<span><i class='fas fa-user'></i> ${nombresArr[0]}</span>`;
            } else {
              const extra = nombresArr.length - 1;
              participantesHtml = `<span><i class='fas fa-user'></i> ${nombresArr[0]} +${extra} más</span>`;
            }
          }
          tbody.innerHTML += `
            <tr>
              <td><input type='checkbox' value='${conv.id_conversacion}'></td>
              <td class='mailbox-status'>
                <span class='badge ${conv.status === 'Leído' ? 'bg-success text-white' : (conv.status === 'Enviado' ? 'bg-primary text-white' : (conv.status === 'Archivado' ? 'bg-danger text-white' : 'bg-warning text-dark'))}' style='font-size:0.75em;padding:2px 8px;'>
                  ${conv.status === 'Leído' ? 'LEÍDO' : (conv.status === 'Enviado' ? 'NUEVO' : (conv.status === 'Archivado' ? 'ARCHIVADO' : conv.status.toUpperCase()))}
                </span>
              </td>
              <td class='mailbox-subject'><b><a href='#' onclick="mostrarConversacion(${conv.id_conversacion})">${conv.mensaje.substring(0,15).replace(/'/g, "&apos;")}...</a></b></td>
              <td class='mailbox-name'>${participantesHtml}</td>
              <td class='mailbox-date'>${conv.fecha_publicacion}</td>
              <td class='mailbox-actions'>
                <button class='btn btn-sm btn-secondary' onclick="archivarConversacion(${conv.id_conversacion})"><i class='fas fa-archive'></i> Archivar</button>
              </td>
            </tr>
          `;
        }
        // Actualizar paginación en el footer
        const infoPaginacion = document.getElementById('infoPaginacion');
        if (infoPaginacion) {
          if (totalMensajes === 0) {
            infoPaginacion.textContent = '0-0/0';
          } else {
            infoPaginacion.textContent = `${inicio + 1}-${fin}/${totalMensajes}`;
          }
        }
        const btnAnterior = document.getElementById('btnAnterior');
        const btnSiguiente = document.getElementById('btnSiguiente');
        if (btnAnterior) {
          btnAnterior.disabled = paginaActual === 1;
          btnAnterior.onclick = function() {
            if (paginaActual > 1) {
              window.paginaMensajes = paginaActual - 1;
              cargarMensajes();
            }
          };
        }
        if (btnSiguiente) {
          btnSiguiente.disabled = paginaActual === totalPaginas;
          btnSiguiente.onclick = function() {
            if (paginaActual < totalPaginas) {
              window.paginaMensajes = paginaActual + 1;
              cargarMensajes();
            }
          };
        }
      });
    // Evento para buscar correo
    document.addEventListener('DOMContentLoaded', function() {
    const inputBuscar = document.querySelector('input[placeholder="Buscar Correo"]');
    if (inputBuscar) {
      inputBuscar.id = 'inputBuscarCorreo';
      const btnBuscar = inputBuscar.parentElement.querySelector('.btn-primary');
      if (btnBuscar) {
        btnBuscar.addEventListener('click', function() {
          cargarMensajes();
        });
      }
      inputBuscar.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') cargarMensajes();
      });
    }
    });
    }
    cargarMensajes();

    // Marcar como leído y mostrar el modal
    function mostrarConversacion(id_conversacion) {
      // Marcar como leídos los mensajes de la conversación para el usuario actual
      fetch('/app/core/marcar-leido.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_conversacion=' + encodeURIComponent(id_conversacion)
      })
      .then(() => {
        // Detectar si estamos en la vista de enviados o papelera y refrescar correctamente
        const titulo = document.querySelector('.col-md-9 .card-title').textContent.trim();
        if (titulo === 'Enviados') {
          mostrarEnviados();
        } else if (titulo === 'Papelera') {
          mostrarPapelera();
        } else {
          cargarMensajes();
        }
        // Obtener todos los mensajes de la conversación grupal
        fetch(`/app/core/list-conversacion.php?id_conversacion=${id_conversacion}`)
          .then(res => res.json())
          .then(conversacion => {
            // Obtener participantes de la conversación
            fetch(`/app/core/list-participantes.php?id_conversacion=${id_conversacion}`)
              .then(res => res.json())
              .then(participantes => {
                let modal = document.getElementById('modalMensajeCompleto');
                if (!modal) {
                  modal = document.createElement('div');
                  modal.id = 'modalMensajeCompleto';
                  modal.className = 'modal';
                  modal.style.display = 'block';
                }
                let mensajesHtml = '';
                const usuarioActual = window.USR_ID;
                conversacion
                  .slice()
                  .sort((a, b) => new Date(a.fecha_publicacion) - new Date(b.fecha_publicacion))
                  .forEach(msg => {
                    const esPropio = msg.id_usuario == usuarioActual;
                    mensajesHtml += `<div style='display:flex;justify-content:${esPropio ? 'flex-end' : 'flex-start'};margin-bottom:8px;'>
                      <div style='max-width:70%;background:${esPropio ? '#dcf8c6' : '#fff'};border-radius:8px;padding:8px 12px;box-shadow:0 1px 2px #eee;'>
                        <div style='font-size:0.95em;'><b>${msg.nombre_autor || 'Usuario'}${esPropio ? ' (Tú)' : ''}</b></div>
                        <div style='white-space:pre-line;'>${msg.mensaje}</div>
                        <div style='text-align:right;color:#888;font-size:0.85em;'>${msg.fecha_publicacion}</div>
                      </div>
                    </div>`;
                  });
                let participantesHtml = '';
                if (Array.isArray(participantes)) {
                  participantesHtml = participantes.map(p => `<span class='badge bg-info text-dark'>${p.nombre} ${p.apellidos}</span>`).join(' ');
                }
                // Solo mostrar select y botón de agregar participante si no estamos en la papelera
                const titulo = document.querySelector('.col-md-9 .card-title').textContent.trim();
                if (titulo !== 'Papelera') {
                  // Select para invitar participantes
                  fetch('/app/core/list-colaboradores.php')
                    .then(res => res.json())
                    .then(colabs => {
                      const select = document.createElement('select');
                      select.className = 'form-control mb-2';
                      select.id = 'nuevoParticipante';
                      select.innerHTML = `<option value=''>Agregar participante...</option>`;
                      colabs.forEach(colab => {
                        // Evitar mostrar los que ya están en la conversación
                        if (!participantes.some(p => p.id_usuario == colab.id_usuario)) {
                          select.innerHTML += `<option value='${colab.id_usuario}'>${colab.nombre} ${colab.apellidos} (${colab.correo})</option>`;
                        }
                      });
                      // Botón para agregar
                      const btnAgregar = document.createElement('button');
                      btnAgregar.className = 'btn btn-success btn-sm mb-2';
                      btnAgregar.textContent = 'Agregar participante';
                      btnAgregar.onclick = function() {
                        const idNuevo = select.value;
                        if (!idNuevo) return mostrarModalAlerta('Selecciona un colaborador', 'warning', 'Advertencia');
                        btnAgregar.disabled = true;
                        fetch('/app/core/add-participante.php', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                          body: `id_conversacion=${id_conversacion}&id_usuario=${idNuevo}`
                        })
                        .then(res => res.json())
                        .then(resp => {
                          btnAgregar.disabled = false;
                          if (resp.success) {
                            mostrarModalAlerta('Participante agregado correctamente.', 'success');
                            // Esperar a que el usuario cierre el modal antes de recargar
                            const btnAceptar = document.querySelector('#modalAlertaSistema .btn-primary');
                            if (btnAceptar) {
                              btnAceptar.onclick = function() {
                                document.getElementById('modalAlertaSistema').remove();
                                mostrarConversacion(id_conversacion);
                              };
                            } else {
                              setTimeout(() => mostrarConversacion(id_conversacion), 1200);
                            }
                          } else {
                            mostrarModalAlerta(resp.error || 'No se pudo agregar el participante.', 'danger', 'Error');
                          }
                        })
                        .catch(()=>{
                          btnAgregar.disabled = false;
                          mostrarModalAlerta('Error de conexión', 'danger', 'Error');
                        });
                      };
                      // Insertar select y botón en el modal, en línea
                      const modalBody = modal.querySelector('.modal-body');
                      if (modalBody) {
                        const divRow = document.createElement('div');
                        divRow.style.display = 'flex';
                        divRow.style.gap = '8px';
                        divRow.style.alignItems = 'center';
                        divRow.style.marginBottom = '8px';
                        select.style.marginBottom = '0';
                        btnAgregar.classList.remove('mb-2');
                        select.style.flex = '2 1 0';
                        btnAgregar.style.flex = '1 1 0';
                        btnAgregar.style.width = 'auto';
                        divRow.appendChild(select);
                        divRow.appendChild(btnAgregar);
                        modalBody.insertAdjacentElement('afterbegin', divRow);
                      }
                    });
                }
                modal.innerHTML = `
                  <div class='modal-dialog' role='document' style='max-width: 600px;'>
                    <div class='modal-content' style='max-height: 600px; display: flex; flex-direction: column;'>
                      <div class='modal-header'>
                        <h5 class='modal-title'><i class='fas fa-envelope'></i> Conversación grupal</h5>
                        <button type='button' class='close' onclick="document.getElementById('modalMensajeCompleto').remove()"><span>&times;</span></button>
                      </div>
                      <div class='modal-body' style='flex: 1 1 auto; display: flex; flex-direction: column;'>
                        <div class='mb-2'><strong>Participantes:</strong> ${participantesHtml}</div>
                        <div id='areaMensajesConversacion' style='flex: 1 1 auto; overflow-y: auto; max-height: 350px; padding-right: 8px;'>
                          ${mensajesHtml}
                        </div>
                      </div>
                      <div class='modal-footer' style='flex-shrink: 0; background: #fff;'>
                        ${titulo !== 'Papelera' ? `<button type='button' class='btn btn-info' onclick=\"responderGrupo(${id_conversacion})\"><i class='fas fa-reply'></i> Responder</button>` : ''}
                        <button type='button' class='btn btn-secondary' onclick="document.getElementById('modalMensajeCompleto').remove();">Cerrar</button>
                      </div>
                    </div>
                  </div>
                `;
                document.body.appendChild(modal);

              });
          });
      });
    }
    // Marcar mensaje como leído
    function marcarLeido(id_mensaje) {
    fetch('/app/core/marcar-leido.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id_mensaje=' + encodeURIComponent(id_mensaje)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        cargarMensajes();
      }
    });
    }

    // Enviar mensaje usando AJAX
    function enviarMensaje(){
      if (window.tinymce) tinymce.triggerSave(); // Sincroniza rich-text con textarea
      const form = document.getElementById('formRedactar');
      const checkboxes = document.querySelectorAll('#participantesCheckboxes input[type="checkbox"]');
      const participantes = Array.from(checkboxes).filter(chk => chk.checked).map(chk => chk.value);
      const formData = new FormData(form);
      // Elimina el campo destino si existe
      formData.delete('destino');
      // Agrega los participantes seleccionados
      formData.delete('participantes[]');
      participantes.forEach(id => formData.append('participantes[]', id));
      fetch('/app/core/send-mensaje.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if(data.success){
          mostrarModalAlerta('¡El mensaje ha sido enviado exitosamente!', 'success');
          document.getElementById('modalRedactar').style.display = 'none';
          form.reset();
          // Esperar a que el usuario cierre el modal antes de recargar
          const btnAceptar = document.querySelector('#modalAlertaSistema .btn-primary');
          if (btnAceptar) {
            btnAceptar.onclick = function() {
              document.getElementById('modalAlertaSistema').remove();
              location.reload();
            };
          } else {
            // Fallback si el botón no existe
            setTimeout(() => location.reload(), 1200);
          }
        }else{
          mostrarModalAlerta(data.error || 'Error al enviar', 'danger', 'Error');
        }
      })
      .catch(()=>{
        mostrarModalAlerta('Error de conexión', 'danger', 'Error');
      });
    }
  </script>
  <script>
    // Responder en conversación grupal
    function responderGrupo(id_conversacion) {
      if (document.getElementById('modalMensajeCompleto')) {
        document.getElementById('modalMensajeCompleto').remove();
      }
      document.getElementById('modalRedactar').style.display = 'block';
      document.getElementById('formRedactar').reset();
      // Guardar el id_conversacion para el envío
      if (!document.getElementById('id_conversacion')) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'id_conversacion';
        input.name = 'id_conversacion';
        document.getElementById('formRedactar').appendChild(input);
      }
      document.getElementById('id_conversacion').value = id_conversacion;
      // Deshabilitar selección de participantes (se responde al grupo)
      const checks = document.querySelectorAll('#participantesCheckboxes input[type="checkbox"]');
      checks.forEach(chk => { chk.disabled = true; chk.checked = false; });
    }
  </script>
  <script>
  // Función para archivar una conversación
  function archivarConversacion(id_conversacion) {
    mostrarConfirmacion('¿Seguro que deseas archivar esta conversación?', function(acepta) {
      if (!acepta) return;
      fetch('/app/core/archivar-mensaje.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_conversacion=' + encodeURIComponent(id_conversacion)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          mostrarModalAlerta('Conversación archivada correctamente.', 'success');
          const btnAceptar = document.querySelector('#modalAlertaSistema .btn-primary');
          if (btnAceptar) {
            btnAceptar.onclick = function() {
              document.getElementById('modalAlertaSistema').remove();
              cargarMensajes();
            };
          } else {
            setTimeout(() => cargarMensajes(), 1200);
          }
        } else {
          mostrarModalAlerta(data.error || 'No se pudo archivar la conversación.', 'danger', 'Error');
        }
      })
      .catch(()=>{
        mostrarModalAlerta('Error de conexión', 'danger', 'Error');
      });
    });
  }
  </script>
  <script>
  function mostrarPapelera() {
  document.querySelector('.col-md-9 .card-title').textContent = 'Papelera';
  fetch('/app/core/list-mensajes.php?tipo=papelera')
    .then(res => res.json())
    .then(data => {
      const mensajesPorPagina = 20;
      let paginaActual = window.paginaMensajesPapelera || 1;
      const totalMensajes = data.length;
      const totalPaginas = Math.max(1, Math.ceil(totalMensajes / mensajesPorPagina));
      paginaActual = Math.min(Math.max(1, paginaActual), totalPaginas);
      window.paginaMensajesPapelera = paginaActual;
      const inicio = (paginaActual - 1) * mensajesPorPagina;
      const fin = Math.min(inicio + mensajesPorPagina, totalMensajes);
      const tbody = document.querySelector('.mailbox-messages tbody');
      tbody.innerHTML = '';
      for (let i = inicio; i < fin; i++) {
        const msg = data[i];
        let participantesHtml = '';
        if (msg.participantes && Array.isArray(msg.participantes)) {
          const nombresArr = msg.participantes.map(p => p.nombre + (p.apellidos ? ' ' + p.apellidos : ''));
          if (nombresArr.length === 0) {
            participantesHtml = '<span class="text-muted">Sin participantes</span>';
          } else if (nombresArr.length === 1) {
            participantesHtml = `<span><i class='fas fa-user'></i> ${nombresArr[0]}</span>`;
          } else {
            const extra = nombresArr.length - 1;
            participantesHtml = `<span><i class='fas fa-user'></i> ${nombresArr[0]} +${extra} más</span>`;
          }
        }
        tbody.innerHTML += `
          <tr>
            <td class='mailbox-status'>
              <span class='badge bg-danger text-white' style='font-size:0.75em;padding:2px 8px;'>ARCHIVADO</span>
            </td>
            <td class='mailbox-subject'><b><a href='#' onclick="mostrarConversacion(${msg.id_conversacion})">${msg.mensaje.substring(0,15)}...</a></b></td>
            <td class='mailbox-name'>${participantesHtml}</td>
            <td class='mailbox-date'>${msg.fecha_publicacion}</td>
            <td class='mailbox-actions'>
              <button class='btn btn-sm btn-danger' onclick="eliminarDefinitivoConversacion(${msg.id_conversacion})"><i class='fas fa-trash'></i> Eliminar</button>
            </td>
          </tr>
        `;
      }
      // Actualizar paginación en el footer
      const infoPaginacion = document.getElementById('infoPaginacion');
      if (infoPaginacion) {
        if (totalMensajes === 0) {
          infoPaginacion.textContent = '0-0/0';
        } else {
          infoPaginacion.textContent = `${inicio + 1}-${fin}/${totalMensajes}`;
        }
      }
      const btnAnterior = document.getElementById('btnAnterior');
      const btnSiguiente = document.getElementById('btnSiguiente');
      if (btnAnterior) {
        btnAnterior.disabled = paginaActual === 1;
        btnAnterior.onclick = function() {
          if (paginaActual > 1) {
            window.paginaMensajesPapelera = paginaActual - 1;
            mostrarPapelera();
          }
        };
      }
      if (btnSiguiente) {
        btnSiguiente.disabled = paginaActual === totalPaginas;
        btnSiguiente.onclick = function() {
          if (paginaActual < totalPaginas) {
            window.paginaMensajesPapelera = paginaActual + 1;
            mostrarPapelera();
          }
        };
      }
    });
  }
  </script>
  <script>
    // Eliminar definitivamente la conversación para el usuario actual
    function eliminarDefinitivoConversacion(id_conversacion) {
      mostrarConfirmacion('¿Seguro que deseas eliminar esta conversación? Esta acción no se puede deshacer.', function(acepta) {
      if (!acepta) return;
      fetch('/app/core/eliminar-definitivo-conversacion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_conversacion=' + encodeURIComponent(id_conversacion)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          mostrarModalAlerta('La conversación fue eliminada definitivamente.', 'success');
          const btnAceptar = document.querySelector('#modalAlertaSistema .btn-primary');
          if (btnAceptar) {
            btnAceptar.onclick = function() {
              document.getElementById('modalAlertaSistema').remove();
              mostrarPapelera();
            };
          } else {
            setTimeout(() => mostrarPapelera(), 1200);
          }
        } else {
          mostrarModalAlerta(data.error || 'No se pudo eliminar la conversación.', 'danger', 'Error');
        }
      })
      .catch(()=>{
        mostrarModalAlerta('Error de conexión', 'danger', 'Error');
      });
    });
    }
  </script>
  <script>
    // Seleccionar todos los mensajes
    document.addEventListener('DOMContentLoaded', function() {
    const toggleBtns = document.querySelectorAll('.checkbox-toggle');
    toggleBtns.forEach(btn => {
      btn.addEventListener('click', function() {
        const table = btn.closest('.mailbox-controls').parentElement.querySelector('table');
        const checks = table.querySelectorAll('input[type="checkbox"]');
        const checked = btn.classList.toggle('active');
        checks.forEach(chk => chk.checked = checked);
        btn.querySelector('i').classList.toggle('fa-square', !checked);
        btn.querySelector('i').classList.toggle('fa-check-square', checked);
      });
    });

    // Refrescar mensajes
    const refreshBtns = document.querySelectorAll('.fa-sync-alt');
    refreshBtns.forEach(btn => {
      btn.parentElement.addEventListener('click', function() {
        cargarMensajes();
      });
    });

    // Archivar mensajes seleccionados
    const btnArchivar = document.getElementById('btnArchivarSeleccionados');
    if (btnArchivar) {
      btnArchivar.addEventListener('click', function() {
        const table = btnArchivar.closest('.mailbox-controls').parentElement.querySelector('table');
        const checks = table.querySelectorAll('input[type="checkbox"]:checked');
        if (checks.length === 0) {
          mostrarModalAlerta('Selecciona al menos un mensaje para archivar.', 'warning', 'Advertencia');
          return;
        }
        mostrarConfirmacion('¿Seguro que deseas archivar los mensajes seleccionados?', function(acepta) {
          if (!acepta) return;
          checks.forEach(chk => {
            archivarMensaje(chk.value);
          });
        });
      });
    }
    }); 
  </script>
  <script>
    function mostrarModalAlerta(mensaje, tipo = 'success', titulo = '') {
    // Elimina cualquier modal anterior
    const modalExistente = document.getElementById('modalAlertaSistema');
    if (modalExistente) modalExistente.remove();
    // Iconos SVG para éxito y error
    const icono = tipo === 'success'
      ? '<svg width="64" height="64" fill="none"><circle cx="32" cy="32" r="32" fill="#e6f9ec"/><path d="M20 33l8 8 16-16" stroke="#4bb543" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>'
      : '<svg width="64" height="64" fill="none"><circle cx="32" cy="32" r="32" fill="#fdeaea"/><path d="M20 20l24 24M44 20L20 44" stroke="#d9534f" stroke-width="4" stroke-linecap="round"/></svg>';
    const tituloMostrar = titulo || (tipo === 'success' ? '¡Éxito!' : 'Error');
    const modal = document.createElement('div');
    modal.id = 'modalAlertaSistema';
    modal.className = 'modal fade show';
    modal.style.cssText = 'display:block; background:rgba(0,0,0,0.2); position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:99999;';
    modal.innerHTML = `
      <div class='d-flex align-items-center justify-content-center' style='height:100vh;'>
        <div class='bg-white rounded shadow p-4 text-center' style='min-width:320px; max-width:90vw;'>
          <div class='mb-3'>${icono}</div>
          <h3 class='mb-2' style='font-weight:600;'>${tituloMostrar}</h3>
          <div class='mb-3'>${mensaje}</div>
          <button class='btn btn-primary' onclick="document.getElementById('modalAlertaSistema').remove()">Aceptar</button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }
  </script>
  <script>
  function mostrarConfirmacion(mensaje, callback) {
    const modalExistente = document.getElementById('modalConfirmacionSistema');
    if (modalExistente) modalExistente.remove();
    const modal = document.createElement('div');
    modal.id = 'modalConfirmacionSistema';
    modal.className = 'modal fade show';
    modal.style.cssText = 'display:block; background:rgba(0,0,0,0.2); position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:99999;';
    modal.innerHTML = `
      <div class='d-flex align-items-center justify-content-center' style='height:100vh;'>
        <div class='bg-white rounded shadow p-4 text-center' style='min-width:320px; max-width:90vw;'>
          <div class='mb-3'><svg width="48" height="48" fill="none"><circle cx="24" cy="24" r="24" fill="#ffeeba"/><path d="M24 14v12" stroke="#856404" stroke-width="3" stroke-linecap="round"/><circle cx="24" cy="32" r="2" fill="#856404"/></svg></div>
          <h5 class='mb-2' style='font-weight:600;'>Confirmar acción</h5>
          <div class='mb-3'>${mensaje}</div>
          <button class='btn btn-primary me-2' id='btnConfirmarSistema'>Aceptar</button>
          <button class='btn btn-secondary' id='btnCancelarSistema'>Cancelar</button>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
    document.getElementById('btnConfirmarSistema').onclick = function() {
      modal.remove();
      if (typeof callback === 'function') callback(true);
    };
    document.getElementById('btnCancelarSistema').onclick = function() {
      modal.remove();
      if (typeof callback === 'function') callback(false);
    };
  }
  </script>
<?php endif; ?>




<!--begin:: for modulo calendarios-->
<?php if ((isset($_GET['pg']) && $_GET['pg'] === 'calendario-nuevo') || (basename($_SERVER['REQUEST_URI'], '.php') === 'calendario-nuevo')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var colaboradorSelect = document.getElementById('searchColaborador');
      if (colaboradorSelect) {
        fetch('core/list-colaboradores.php')
          .then(response => response.json())
          .then(data => {
            colaboradorSelect.innerHTML = '<option value="">Colaborador</option>';
            data.forEach(function(colab) {
              colaboradorSelect.innerHTML += `<option value="${colab.id_colab}">${colab.nombre}</option>`;
            });
          });
      }
        // Validación de formulario de agendar cita
        var formCita = document.getElementById('formCita');
        if (formCita) {
          formCita.addEventListener('submit', function(e) {
            var asunto = document.getElementById('asunto');
            var tipo = document.getElementById('tipo');
            var ubicacionUrl = document.getElementById('ubicacion_url');
            var ubicacionText = document.getElementById('ubicacion_text');
            var inicio = document.getElementById('inicio');
            var duracion = document.getElementById('duracion');
            var descripcion = document.getElementById('compose-textarea');
            var cliente = document.getElementById('cliente');
            var colaborador = document.getElementById('colaborador');
            var enviarCorreo = document.getElementById('enviarCorreo');
            // var todoDia = document.getElementById('todoDia'); // No bloquear por este campo
            // Validar campos obligatorios (excepto todoDia)
            if (!asunto.value.trim() || !tipo.value.trim() || !ubicacionUrl.value.trim() || !ubicacionText.value.trim() || !inicio.value.trim() || !duracion.value.trim() || !descripcion.value.trim() || !cliente.value.trim() || !colaborador.value.trim() || !enviarCorreo.value.trim()) {
              e.preventDefault();
              Swal.fire({
                icon: 'warning',
                title: 'Campos obligatorios',
                text: 'Por favor, llena todos los campos antes de guardar la cita.'
              });
              return false;
            }
            // El campo todoDia se muestra siempre en la etiqueta, no bloquea el guardado
          });
        }
        // Cargar citas en la tabla de listado
        var tablaCitas = document.getElementById('tablaCategorias');
        if (tablaCitas) {
          var tbody = tablaCitas.querySelector('tbody');
          if (tbody) {
              // Log de depuración del colaborador logueado
              console.log('Colaborador logueado:', <?php echo json_encode($_SESSION['id_colab'] ?? null); ?>);
              // Evitar caché al cargar citas
              fetch('core/list-citas-controller.php', { cache: 'no-store' })
                .then(response => response.text())
                .then(html => {
                  tbody.innerHTML = html;
                });
          }
        }
    });
  </script>
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="msgCitaGuardada" class="alert alert-success text-center" style="margin-bottom:15px;">¡Cita guardada correctamente!</div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('modalAgendarCita');
        if (form) {
          // Si es un formulario, resetea. Si es un modal, busca el form dentro y resetea.
          if (form.tagName === 'FORM') {
            form.reset();
          } else {
            var innerForm = form.querySelector('form');
            if (innerForm) innerForm.reset();
          }
        }
        setTimeout(function() {
          var msg = document.getElementById('msgCitaGuardada');
          if (msg) msg.style.display = 'none';
        }, 4000);
        // Eliminar el parámetro success de la URL para que la alerta no se repita
        if (window.history.replaceState) {
          var url = new URL(window.location);
          url.searchParams.delete('success');
          window.history.replaceState({}, document.title, url);
        }
      });
    </script>
  <?php endif; ?>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('fullcalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
          },
          buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
          },
          events: function(fetchInfo, successCallback, failureCallback) {
            var year = document.getElementById('yearFilter')?.value;
            var month = document.getElementById('monthFilter')?.value;
            var params = [];
            if (year) params.push('year=' + encodeURIComponent(year));
            if (month) params.push('month=' + encodeURIComponent(month));
            var url = 'core/listar-citas.php';
            if (params.length) url += '?' + params.join('&');
            fetch(url)
              .then(response => response.json())
              .then(events => successCallback(events))
              .catch(failureCallback);
          },
              eventDidMount: function(info) {
                // Asignar color según el status
                var status = info.event.extendedProps.status;
                var color = '';
                switch (status) {
                  case 'Programada':
                    color = '#007bff'; // azul
                    break;
                  case 'Realizada':
                    color = '#28a745'; // verde
                    break;
                  case 'Cancelada':
                    color = '#dc3545'; // rojo
                    break;
                  case 'Pospuesta':
                    color = '#ffc107'; // amarillo
                    break;
                  default:
                    color = '#6c757d'; // gris
                }
                info.el.style.backgroundColor = color;
                info.el.style.borderColor = color;
                // Mostrar solo el nombre de la cita como título, sin viñeta ni número ni "0" si no es todo el día
                var titleEl = info.el.querySelector('.fc-event-title');
                var todoDia = info.event.extendedProps.todo_dia;
                if (titleEl && info.event.title) {
                  // Si no es todo el día, elimina viñeta y número si existen
                  if (!todoDia || todoDia === '0' || todoDia === 0 || todoDia === false) {
                    titleEl.textContent = info.event.title;
                    // Elimina viñeta si existe
                    var dot = info.el.querySelector('.fc-event-dot');
                    if (dot) {
                      dot.style.display = 'none';
                      dot.remove();
                    }
                    // Elimina número si existe
                    var number = info.el.querySelector('.fc-event-time');
                    if (number) {
                      number.style.display = 'none';
                      number.remove();
                    }
                  } else {
                    // Si es todo el día, muestra normalmente
                    titleEl.textContent = info.event.title;
                  }
                }
              },
          dateClick: function(info) {
            var modal = document.getElementById('modalAgendarCita');
            if (modal) {
              var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
              var fechaInput = document.getElementById('inicio');
              var hiddenInput = document.getElementById('inicio_real');
              // Adaptar campos de ubicación
              var ubicacionUrlInput = document.getElementById('ubicacion_url');
              var ubicacionTextInput = document.getElementById('ubicacion_text');
              if (fechaInput && hiddenInput) {
                var parts = info.dateStr.split('-');
                if (parts.length === 3) {
                  var ddmmyyyy = parts[2] + '/' + parts[1] + '/' + parts[0];
                  fechaInput.value = ddmmyyyy;
                  hiddenInput.value = parts[0] + '-' + parts[1] + '-' + parts[2];
                } else {
                  fechaInput.value = info.dateStr;
                  hiddenInput.value = info.dateStr;
                }
              }
              bsModal.show();
            }
          },
          eventClick: function(info) {
            var evento = info.event;
            var idCita = evento.id;
            if (!idCita) return;
            fetch('core/calendario.php?id_cita=' + encodeURIComponent(idCita))
              .then(function(response) { return response.text(); })
              .then(function(html) {
                var tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                var modalDetalle = tempDiv.querySelector('#modalDetalleCita');
                if (modalDetalle) {
                  // Corregir color de la cabecera del modal de edición
                  var header = modalDetalle.querySelector('.modal-header');
                  if (header) {
                    header.classList.add('bg-gradient-primary');
                    var title = header.querySelector('.modal-title');
                    if (title) {
                      title.style.color = '#fff';
                    }
                  }
                  var actualModal = document.getElementById('modalDetalleCita');
                  if (actualModal) {
                    actualModal.parentNode.replaceChild(modalDetalle, actualModal);
                  } else {
                    document.body.appendChild(modalDetalle);
                  }
                  var bsModal = bootstrap.Modal.getInstance(modalDetalle) || new bootstrap.Modal(modalDetalle);
                  bsModal.show();
                  var btnGuardar = modalDetalle.querySelector('#btnGuardarCita');
                  if (btnGuardar) {
                    btnGuardar.onclick = function() {
                      var form = modalDetalle.querySelector('#formEditarCita');
                      var detallesField = modalDetalle.querySelector('#detalleDescripcion');
                      var detallesValue = detallesField ? detallesField.value : '';
                      var fechaInput = modalDetalle.querySelector('#detalleFecha');
                      var fechaValue = fechaInput ? fechaInput.value : '';
                      // Normalizar fecha a formato yyyy-mm-dd si viene como dd/mm/yyyy
                      if (fechaValue && fechaValue.indexOf('/') !== -1) {
                        var parts = fechaValue.split('/');
                        if (parts.length === 3) {
                          fechaValue = parts[2] + '-' + parts[1] + '-' + parts[0];
                        }
                      }
                      var data = {
                        id_cita: modalDetalle.querySelector('#detalleIdCita').value,
                        asunto: modalDetalle.querySelector('#detalleAsunto').value,
                        fecha_inicio: fechaValue,
                        detalles: detallesValue,
                        ubicacion: modalDetalle.querySelector('#detalleUbicacion').value,
                        duracion: modalDetalle.querySelector('#detalleDuracion').value,
                        status: modalDetalle.querySelector('#detalleStatus').value,
                        id_colab: modalDetalle.querySelector('#detalleColaborador').value,
                        todo_dia: modalDetalle.querySelector('#detalleTodoDia').value
                      };
                      if (!data.asunto || !data.fecha_inicio) {
                        Swal.fire({ icon: 'error', title: 'Campos requeridos', text: 'Asunto y fecha son obligatorios.' });
                        return;
                      }
                      fetch('core/editar-cita.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                      })
                      .then(response => response.json())
                      .then(res => {
                        if (res.success) {
                          Swal.fire({ icon: 'success', title: '¡Cita actualizada!', text: 'Los cambios se guardaron correctamente.' });
                          // Forzar recarga de eventos y mantener filtros activos
                          calendar.refetchEvents();
                          setTimeout(function() {
                            bsModal.hide();
                          }, 800);
                        } else {
                          Swal.fire({ icon: 'error', title: 'Error', text: res.error || 'No se pudo guardar.' });
                        }
                      })
                      .catch(err => {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Error de red o servidor.' });
                      });
                    };
                  }
                } else {
                  Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el detalle de la cita.' });
                }
              })
              .catch(function() {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo consultar la cita.' });
              });
          }
        });
        calendar.render();
        // Mascara para el campo de fecha en el modal agendar cita
        var fechaInput = document.getElementById('inicio');
        var hiddenInput = document.getElementById('inicio_real');
        if (fechaInput && hiddenInput) {
          fechaInput.addEventListener('input', function(e) {
            // Permitir solo números y /
            var v = fechaInput.value.replace(/[^0-9\/]/g, '');
            // Formatear automáticamente dd/mm/yyyy
            if (v.length >= 2 && v[2] !== '/') v = v.slice(0,2) + '/' + v.slice(2);
            if (v.length >= 5 && v[5] !== '/') v = v.slice(0,5) + '/' + v.slice(5);
            fechaInput.value = v.slice(0,10);
          });
          fechaInput.addEventListener('blur', function() {
            // Al perder foco, actualizar el input oculto en formato yyyy-mm-dd
            var v = fechaInput.value;
            var parts = v.split('/');
            if (parts.length === 3 && parts[0].length === 2 && parts[1].length === 2 && parts[2].length === 4) {
              hiddenInput.value = parts[2] + '-' + parts[1] + '-' + parts[0];
            }
          });
        }
        // --- REAGREGAR EVENT LISTENERS DE FILTRO DE AÑO, MES, STATUS, NOMBRE Y COLABORADOR ---
        function agregarListenersFiltrosCalendario() {
          var yearSelect = document.getElementById('yearFilter');
          var monthSelect = document.getElementById('monthFilter');
          function actualizarCalendario() {
            var year = yearSelect ? parseInt(yearSelect.value) : null;
            var month = monthSelect ? parseInt(monthSelect.value) : null;
            if (!isNaN(year) && !isNaN(month)) {
              // month es 1-12, FullCalendar espera 0-11
              calendar.gotoDate(year + '-' + (month.toString().padStart(2, '0')) + '-01');
            }
            calendar.refetchEvents();
          }
          if (yearSelect) yearSelect.addEventListener('change', actualizarCalendario);
          if (monthSelect) monthSelect.addEventListener('change', actualizarCalendario);
        }
        agregarListenersFiltrosCalendario();
          // --- FUNCIONALIDAD PARA EDITAR CITAS ---
          window.mostrarDetalleCita = function(cita) {

            console.log('DEBUG cita:', cita);
            console.log('DEBUG detalles:', cita.detalles);
            document.getElementById('detalleAsunto').value = cita.asunto;
            document.getElementById('detalleFecha').value = cita.fecha_inicio;
            var detallesField = document.getElementById('detalleDescripcion');
            if (detallesField) {
              detallesField.value = cita.detalles || '';
            }
            // Adaptar campos de ubicación en el modal de edición si existen
            var detalleUbicacionUrl = document.getElementById('detalleUbicacion_url');
            var detalleUbicacionText = document.getElementById('detalleUbicacion_text');
            if (detalleUbicacionUrl) detalleUbicacionUrl.value = cita.ubicacion_url || '';
            if (detalleUbicacionText) detalleUbicacionText.value = cita.ubicacion_text || '';
            // Si solo existe el campo clásico, mantener compatibilidad
            var detalleUbicacion = document.getElementById('detalleUbicacion');
            if (detalleUbicacion) detalleUbicacion.value = cita.ubicacion || '';
            document.getElementById('detalleDuracion').value = cita.duracion;
            document.getElementById('detalleStatus').value = cita.status;
            document.getElementById('detalleColaborador').value = cita.id_colab;
            document.getElementById('detalleIdCita').value = cita.id;
            document.getElementById('detalleTodoDia').value = cita.todo_dia;

            // Mostrar el modal
            var modal = document.getElementById('modalDetalleCita');
            var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
            bsModal.show();
          };


          // ...existing code...
          // Actualizar calendario al cambiar año o mes
          var yearSelect = document.getElementById('yearFilter');
          var monthSelect = document.getElementById('monthFilter');
          function actualizarCalendario() {
            var year = yearSelect ? parseInt(yearSelect.value) : null;
            var month = monthSelect ? parseInt(monthSelect.value) : null;
            if (!isNaN(year) && !isNaN(month)) {
              // month es 1-12, FullCalendar espera 0-11
              calendar.gotoDate(year + '-' + (month.toString().padStart(2, '0')) + '-01');
            }
          }
          if (yearSelect) {
            yearSelect.addEventListener('change', actualizarCalendario);
          }
          if (monthSelect) {
            monthSelect.addEventListener('change', actualizarCalendario);
          }
      });
    </script>
<?php endif; ?>
  <script>
    // Función global para generar el enlace de confirmación sin localhost
    function generarLinkConfirmacion(idCita, idContacto) {
      var dominio = window.location.origin;
      return dominio + '/app/core/confirmar-asistencia.php?id_cita=' + idCita + '&id_contacto=' + idContacto;
    }
  </script>
<!--end:: for modulo calendarios-->

<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'listado-citas' || (isset($_GET['pg']) && $_GET['pg'] === 'listado-citas')): ?>
  <script>
     document.addEventListener('DOMContentLoaded', function() {
                                          var tablaCitas = document.getElementById('tablaCategorias');
                                          if (tablaCitas) {
                                              var tbody = tablaCitas.querySelector('tbody');
                                              if (tbody) {
                          // función para cargar el contenido del tbody vía fetch
                          function cargarTablaCitas() {
                            fetch('core/list-citas-controller.php')
                              .then(response => response.text())
                              .then(html => {
                                // sólo reemplazar si cambió para minimizar flicker
                                if (tbody.innerHTML.trim() !== html.trim()) {
                                  tbody.innerHTML = html;
                                }
                              })
                              .catch(err => {
                                console.error('Error cargando citas:', err);
                              });
                          }

                          // carga inicial
                          cargarTablaCitas();

                          // recarga cada 10 segundos (10000 ms)
                          var intervaloCitas = setInterval(cargarTablaCitas, 10000);
                                              }
                                          }
                                      });
  </script>
<?php endif; ?>




<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'clientes' || (isset($_GET['pg']) && $_GET['pg'] === 'clientes')): ?>
  <script>
    // Buscar cliente
  document.addEventListener('DOMContentLoaded', function() {
  // Mover el foco al botón Buscar cuando se cierra el modal de edición
  var modalEditar = document.getElementById('modalEditarCliente');
  if (modalEditar) {
    modalEditar.addEventListener('hidden.bs.modal', function () {
      // Buscar el primer botón visible y habilitado para enfocar
      var btnBuscar = document.querySelector('button[type="submit"]');
      if (btnBuscar && btnBuscar.offsetParent !== null && !btnBuscar.disabled) {
        btnBuscar.focus();
        console.log('Foco movido al botón Buscar');
      } else {
        // Si el botón Buscar no está disponible, enfoca el body
        document.body.focus();
        console.log('Foco movido al body');
      }
    });
  }
    const form = document.querySelector('form.row.g-2.mb-3');
    if (!form) return; // Prevent errors if form not found
    const inputBuscar = form.querySelector('input[type="text"]');
    const tablaDiv = document.querySelector('.table-responsive');

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const nombre = inputBuscar.value.trim();
      if (!nombre) {
        tablaDiv.innerHTML = '<div class="alert alert-warning">Ingresa el nombre del cliente para buscar.</div>';
        return;
      }
      fetch('core/buscar-cliente.php?nombre=' + encodeURIComponent(nombre))
        .then(res => {
          if (!res.ok) throw new Error('Error en la petición');
          return res.json();
        })
        .then(data => {
          let html = '';
          if (Array.isArray(data) && data.length > 0) {
            html += '<table class="table table-bordered table-hover"><thead class="table-secondary"><tr>';
            html += '<th>Nombre Comercial</th><th>Correo</th><th>Teléfono</th><th>Razón Social</th><th>RFC</th><th>Contacto</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>';
            data.forEach(row => {
              html += '<tr>';
              html += `<td>${row.nombre_comercial}</td>`;
              html += `<td>${row.correo}</td>`;
              html += `<td>${row.telefono}</td>`;
              html += `<td>${row.razon_social}</td>`;
              html += `<td>${row.rfc}</td>`;
              html += `<td>${row.contacto}</td>`;
              html += `<td>${row.estado}</td>`;
              html += `<td>
                <button class="btn btn-warning btn-sm btn-editar-cliente" data-id="${row.id_cliente}">
                  <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-sm btn-eliminar-cliente" data-id="${row.id_cliente}">
                  <i class="fa fa-trash"></i>
                </button>
              </td>`;
              html += '</tr>';
            });
            html += '</tbody></table>';
          } else {
            html = '<div class="alert alert-info">No se encontró ningún cliente con ese nombre.</div>';
          }
          tablaDiv.innerHTML = html;
        })
        .catch(err => {
          tablaDiv.innerHTML = '<div class="alert alert-danger">Error al buscar clientes. Revisa la ruta y el backend.</div>';
          console.error(err);
        });
    });
  });
  </script>
  <!-- editar y desactivar-->
  <script>
  document.addEventListener('click', function(e) {
    // Editar cliente
  console.log('Handler de edición ejecutado');
  const btnEditar = e.target.closest('.btn-editar-cliente');
    if (btnEditar) {
      const id = btnEditar.getAttribute('data-id');
      fetch('/app/core/get-cliente.php?id=' + encodeURIComponent(id))
          .then(async response => {
            const text = await response.text();
            console.log('RESPUESTA RAW:', text); // <-- Depuración
            try {
              const data = JSON.parse(text);
              console.log('DATA PARSEADA:', data); // <-- Depuración
              console.log('data.success:', data.success);
              let errorMostrado = false;
              if (data.success) {
                const cliente = data.data;
                function setField(id, value, isCheckbox) {
                  const el = document.getElementById(id);
                  if (!el) {
                    console.warn('Campo no encontrado:', id);
                    return;
                  }
                  if (isCheckbox) {
                    el.checked = value;
                  } else {
                    el.value = value;
                  }
                }
                setField('edit_id_cliente', cliente.id_cliente || '');
                setField('edit_razon_social', cliente.razon_social || '');
                setField('edit_nombre_comercial', cliente.nombre_comercial || '');
                setField('edit_regimen_fiscal', cliente.regimen_fiscal || '');
                setField('edit_telefono', cliente.telefono || '');
                setField('edit_rfc', cliente.rfc || '');
                setField('edit_contacto', cliente.contacto || '');
                setField('edit_correo', cliente.correo || '');
                setField('edit_calle', cliente.calle || '');
                setField('edit_n_exterior', cliente.n_exterior || '');
                setField('edit_n_interior', cliente.n_interior || '');
                setField('edit_entre_calle', cliente.entre_calle || '');
                setField('edit_y_calle', cliente.y_calle || '');
                setField('edit_pais', cliente.pais || '');
                setField('edit_cp', cliente.cp || '');
                setField('edit_estado', cliente.estado || '');
                setField('edit_municipio', cliente.municipio || '');
                setField('edit_poblacion', cliente.poblacion || '');
                setField('edit_colonia', cliente.colonia || '');
                setField('edit_referencia', cliente.referencia || '');
                setField('edit_admin_cfdis', cliente.admin_cfdis == 1, true);
                // Limpieza previa: cerrar cualquier alerta de Swal antes de mostrar el modal
                if (window.Swal && Swal.isVisible()) {
                  Swal.close();
                }
                var modal = new bootstrap.Modal(document.getElementById('modalEditarCliente'));
                modal.show();
              } else {
                Swal.fire('Error', 'No se encontró el cliente.', 'error');
              }
            } catch (err) {
              console.error('ERROR AL PARSEAR:', err, text); // Solo depuración, no mostrar modal de error
            }
          })
        .catch(() => Swal.fire('Error', 'Error al cargar datos del cliente.', 'error'));
    }

    // Desactivar cliente (mover a papelera)
    const btnDesactivar = e.target.closest('.btn-desactivar-cliente');
    if (btnDesactivar) {
      const id = btnDesactivar.getAttribute('data-id');
      Swal.fire({
        icon: 'warning',
        title: '¿Enviar a papelera?',
        text: '¿Seguro que deseas enviar este cliente a la papelera? Podrás restaurarlo o eliminarlo definitivamente después.',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('id_cliente', id);
          fd.append('en_papelera', 1);
          fetch('/app/core/mover-cliente-papelera.php', {
            method: 'POST',
            body: fd
          })
          .then(async response => {
            const text = await response.text();
            try {
              const data = JSON.parse(text);
              Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? '¡En papelera!' : 'Error',
                text: data.message || data.error,
                showConfirmButton: true
              }).then(() => { 
                if (data.success) {
                  // Recargar la tabla en lugar de toda la página
                  fetch('/app/core/list-clientes.php')
                    .then(response => response.text())
                    .then(html => {
                      document.getElementById('tabla-clientes-resultados').innerHTML = html;
                    })
                    .catch(err => console.error('Error al recargar tabla:', err));
                }
              });
            } catch (err) {
              Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
            }
          })
          .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
        }
      });
    }

    // Activar cliente
    const btnActivar = e.target.closest('.btn-activar-cliente');
    if (btnActivar) {
      const id = btnActivar.getAttribute('data-id');
      Swal.fire({
        icon: 'warning',
        title: '¿Activar cliente?',
        text: '¿Seguro que deseas activar este cliente?',
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('id_cliente', id);
          fd.append('activo', 1);
          fetch('/app/core/modificar-cliente.php', {
            method: 'POST',
            body: fd
          })
          .then(async response => {
            const text = await response.text();
            try {
              const data = JSON.parse(text);
              Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? '¡Activado!' : 'Error',
                text: data.message || data.error,
                showConfirmButton: true
              }).then(() => { 
                if (data.success) {
                  // Recargar la tabla en lugar de toda la página
                  fetch('/app/core/list-clientes.php')
                    .then(response => response.text())
                    .then(html => {
                      document.getElementById('tabla-clientes-resultados').innerHTML = html;
                    })
                    .catch(err => console.error('Error al recargar tabla:', err));
                }
              });
            } catch (err) {
              Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
            }
          })
          .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
        }
      });
    }
  });
  </script>
  <script>
  document.getElementById('formEditarCliente').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    fetch('/app/core/modificar-cliente.php', {
      method: 'POST',
      body: fd
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        if (window.Swal) {
          Swal.fire({
            icon: 'success',
            title: '¡Guardado!',
            text: 'Los cambios se guardaron correctamente.',
            showConfirmButton: true
          }).then(() => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditarCliente')).hide();
            // Recargar la tabla de clientes
            fetch('/app/core/list-clientes.php')
              .then(response => response.text())
              .then(html => {
                document.getElementById('tabla-clientes-resultados').innerHTML = html;
              })
              .catch(err => console.error('Error al recargar tabla:', err));
          });
        } else {
          alert('Los cambios se guardaron correctamente.');
          bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditarCliente')).hide();
          // Recargar la tabla de clientes
          fetch('/app/core/list-clientes.php')
            .then(response => response.text())
            .then(html => {
              document.getElementById('tabla-clientes-resultados').innerHTML = html;
            })
            .catch(err => console.error('Error al recargar tabla:', err));
        }
      } else {
        if (window.Swal) {
          Swal.fire('Error', data.error || data.message || 'No se pudo guardar.', 'error');
        } else {
          alert(data.error || data.message || 'No se pudo guardar.');
        }
      }
    })
    .catch(() => {
      if (window.Swal) {
        Swal.fire('Error', 'Error de conexión o respuesta inesperada del servidor.', 'error');
      } else {
        alert('Error de conexión o respuesta inesperada del servidor.');
      }
    });
  });
  </script>
<?php endif; ?>








<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'clientes-delete' || (isset($_GET['pg']) && $_GET['pg'] === 'clientes-delete')): ?>
  <script>
  function cargarPapeleraClientes() {
    const buscar = document.getElementById('buscar-papelera-clientes')?.value?.trim() || '';
    fetch('/app/core/listar-clientes-papelera.php' + (buscar ? ('?q=' + encodeURIComponent(buscar)) : ''))
      .then(res => res.json())
      .then(data => {
        let html = '<table class="table table-bordered table-hover"><thead class="table-danger"><tr>';
        html += '<th>Tipo</th><th>Nombre Comercial</th><th>Correo</th><th>Teléfono</th><th>Razón Social</th><th>RFC</th><th>Contacto</th><th>Acciones</th></tr></thead><tbody>';
        if (Array.isArray(data) && data.length > 0) {
          data.forEach(row => {
            html += '<tr>';
            html += `<td><span class="badge bg-danger">Cliente</span></td>`;
            html += `<td>${row.nombre_comercial}</td>`;
            html += `<td>${row.correo}</td>`;
            html += `<td>${row.telefono}</td>`;
            html += `<td>${row.razon_social}</td>`;
            html += `<td>${row.rfc}</td>`;
            html += `<td>${row.contacto}</td>`;
            html += `<td>
              <button class="btn btn-success btn-sm btn-restaurar-cliente" data-id="${row.id_cliente}" title="Restaurar">
                <i class="fa fa-undo"></i>
              </button>
              <button class="btn btn-danger btn-sm btn-borrar-definitivo-cliente ms-1" data-id="${row.id_cliente}" title="Eliminar definitivo">
                <i class="fa fa-trash"></i>
              </button>
            </td>`;
            html += '</tr>';
          });
        } else {
          html += '<tr><td colspan="8" class="text-center text-muted">La papelera está vacía.</td></tr>';
        }
        html += '</tbody></table>';
        document.getElementById('tabla-papelera-clientes').innerHTML = html;
      })
      .catch(() => {
        document.getElementById('tabla-papelera-clientes').innerHTML = '<div class="alert alert-danger">Error al cargar la papelera.</div>';
      });
  }

  document.addEventListener('DOMContentLoaded', cargarPapeleraClientes);

  document.addEventListener('click', function(e) {
    // Restaurar cliente
    const btnRestaurar = e.target.closest('.btn-restaurar-cliente');
    if (btnRestaurar) {
      const id = btnRestaurar.getAttribute('data-id');
      Swal.fire({
        icon: 'question',
        title: '¿Restaurar cliente?',
        text: '¿Seguro que deseas restaurar este cliente? Se activará nuevamente.',
        showCancelButton: true,
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('id_cliente', id);
          fetch('/app/core/restaurar-cliente.php', {
            method: 'POST',
            body: fd
          })
          .then(async response => {
            const text = await response.text();
            try {
              const data = JSON.parse(text);
              Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? '¡Restaurado!' : 'Error',
                text: data.message || data.error,
                showConfirmButton: true
              }).then(() => { 
                if (data.success) {
                  cargarPapeleraClientes();
                }
              });
            } catch (err) {
              Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
            }
          })
          .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
        }
      });
    }

    // Borrar definitivo cliente
    const btnBorrar = e.target.closest('.btn-borrar-definitivo-cliente');
    if (btnBorrar) {
      const id = btnBorrar.getAttribute('data-id');
      Swal.fire({
        icon: 'warning',
        title: '¿Eliminar definitivamente?',
        text: 'Esta acción eliminará el cliente y todos sus datos relacionados. ¿Continuar?',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('id_cliente', id);
          fetch('/app/core/borrar-definitivo-cliente.php', {
            method: 'POST',
            body: fd
          })
          .then(async response => {
            const text = await response.text();
            try {
              const data = JSON.parse(text);
              Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? '¡Eliminado!' : 'Error',
                text: data.message || data.error,
                showConfirmButton: true
              }).then(() => { 
                if (data.success) {
                  cargarPapeleraClientes();
                }
              });
            } catch (err) {
              Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
            }
          })
          .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
        }
      });
    }
  });
  </script>

  <script>
  document.addEventListener('click', function(e) {
    // Restaurar cliente
    const btnRestaurar = e.target.closest('.btn-restaurar-cliente');
    if (btnRestaurar) {
      const id = btnRestaurar.getAttribute('data-id');
      Swal.fire({
        icon: 'question',
        title: '¿Restaurar cliente?',
        text: '¿Seguro que deseas restaurar este cliente? Se activará nuevamente.',
        showCancelButton: true,
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('id_cliente', id);
          fd.append('en_papelera', 0);
          fetch('/app/core/restaurar-cliente.php', {
            method: 'POST',
            body: fd
          })
          .then(async response => {
            const text = await response.text();
            try {
              const data = JSON.parse(text);
              Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? '¡Restaurado!' : 'Error',
                text: data.message || data.error,
                showConfirmButton: true
              }).then(() => { 
                if (data.success) {
                  location.reload();
                }
              });
            } catch (err) {
              Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
            }
          })
          .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
        }
      });
    }

    // Borrar definitivo cliente
    const btnBorrar = e.target.closest('.btn-borrar-definitivo-cliente');
    if (btnBorrar) {
      const id = btnBorrar.getAttribute('data-id');
      Swal.fire({
        icon: 'warning',
        title: '¿Eliminar definitivamente?',
        text: 'Esta acción eliminará el cliente y todos sus datos relacionados. ¿Continuar?',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
      }).then((result) => {
        if (result.isConfirmed) {
          const fd = new FormData();
          fd.append('id_cliente', id);
          fetch('/app/core/borrar-definitivo-cliente.php', {
            method: 'POST',
            body: fd
          })
          .then(async response => {
            const text = await response.text();
            try {
              const data = JSON.parse(text);
              Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? '¡Eliminado!' : 'Error',
                text: data.message || data.error,
                showConfirmButton: true
              }).then(() => { 
                if (data.success) {
                  location.reload();
                }
              });
            } catch (err) {
              Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
            }
          })
          .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
        }
      });
    }
  });
  </script>
<?php endif; ?>









<!--begin:: for modulo expedientes notariales-->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'exp-notariales' || (isset($_GET['pg']) && $_GET['pg'] === 'exp-notariales')): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      // --- FUNCION PARA RECARGAR TABLA ---
      function recargarTablaExpNotariales() {
        fetch('/app/core/exp_notariales-controller.php')
          .then(response => response.json())
          .then(data => {
            const tbody = document.querySelector('table tbody');
            if (data.success && Array.isArray(data.data)) {
              tbody.innerHTML = '';
              data.data.forEach(row => {
                const tr = document.createElement('tr');
                tr.classList.add('text-center');
                tr.innerHTML = `
                  <td>${row.empresa || ''}</td>
                  <td>${row.instrumento || ''}</td>
                  <td>${row.notario || ''}</td>
                  <td>${row.giro_empresa || ''}</td>
                  <td>${row.rl || ''}</td>
                  <td>${row.socio || ''}</td>
                  <td>${row.domicilio || ''}</td>
                  <td>${row.rfc || ''}</td>
                  <td>${row.correo || ''}</td>
                  <td>${row.institucion_bancaria || ''}</td>
                  <td>
                    <a href="panel?pg=expedientes-notariales&id_notarial=${row.id_notarial}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i> Ver</a>
                  </td>
                `;
                tbody.appendChild(tr);
              });
            } else {
              tbody.innerHTML = '<tr><td colspan="11" class="text-center">No se encontraron registros.</td></tr>';
            }
          })
          .catch(error => {
            document.querySelector('table tbody').innerHTML = '<tr><td colspan="11" class="text-center">Error al cargar los datos.</td></tr>';
            console.error('Error:', error);
          });
      }

      // Cargar la tabla al iniciar
      recargarTablaExpNotariales();

      // --- GUARDAR DATOS DEL MODAL POR AJAX ---
      const form = document.getElementById('formExpedienteNotarial');
      if (form) {
        form.addEventListener('submit', function (e) {
          e.preventDefault();
          const formData = new FormData(form);
          fetch('/app/core/agregar-expediente-notarial.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Cerrar el modal (Bootstrap 5)
              const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalExpedienteNotarial'));
              modal.hide();
              // Limpiar el formulario
              form.reset();
              // Refrescar la tabla
              recargarTablaExpNotariales();
              // Mensaje de éxito
              if (typeof Swal !== 'undefined') {
                Swal.fire({icon:'success',title:'Guardado',text:data.message, timer:1500, showConfirmButton:false});
              }
            } else {
              if (typeof Swal !== 'undefined') {
                Swal.fire({icon:'error',title:'Error',text:data.message || 'No se pudo guardar.'});
              }
            }
          })
          .catch(error => {
            if (typeof Swal !== 'undefined') {
              Swal.fire({icon:'error',title:'Error',text:'Error al guardar.'});
            }
            console.error('Error:', error);
          });
        });
      }
    });
    </script>
<?php endif; ?>
<!--end:: for modulo expedientes notariales-->
<!--begin:: for modulo papelera notariales-->
<?php
// Solo incluir el script si estamos en la página papelera-notarial
if (
  basename($_SERVER['REQUEST_URI'], '.php') === 'palera-notarial' ||
  (isset($_GET['pg']) && $_GET['pg'] === 'palera-notarial')
) : ?>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('tablaPapelera')) {
      function cargarPapeleraNotarial() {
        const idNotarial = document.getElementById('filtroExpediente').value;
        const cat = document.getElementById('filtroCategoria').value;
        fetch('core/papelera-notarial.php?id_notarial=' + encodeURIComponent(idNotarial) + '&categoria=' + encodeURIComponent(cat))
          .then(res => res.json())
          .then(data => {
            const tbody = document.querySelector('#tablaPapelera tbody');
            tbody.innerHTML = '';
            if (data.archivos && data.archivos.length > 0) {
              data.archivos.forEach(arch => {
                const tr = document.createElement('tr');
                // Usar uuid y nombre_archivo para la ruta de preview y descarga
                const rutaPreview = `core/descargar-pdf.php?id_notarial=${arch.id_notarial}&uuid=${arch.uuid || ''}&nombre_archivo=${encodeURIComponent(arch.nombre_archivo)}`;
                tr.innerHTML = `
                  <td>${arch.id_doc}</td>
                  <td>${arch.id_notarial}</td>
                  <td>${arch.categoria}</td>
                  <td>${arch.fecha}</td>
                  <td>${arch.documento}</td>
                  <td class="text-center align-middle">
                    <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                      <button class="btn btn-primary btn-sm btn-preview" data-ruta="${rutaPreview}" data-nombre="${arch.nombre_archivo}"><i class="bi bi-eye"></i> Preview</button>
                      <a class="btn btn-secondary btn-sm" href="${rutaPreview}" target="_blank" download title="Descargar"><i class="bi bi-download"></i> Descargar</a>
                      <button class="btn btn-danger btn-sm btn-restaurar-papelera" data-id="${arch.id_doc}" data-uuid="${arch.uuid}" data-categoria="${arch.categoria}" title="Restaurar"><i class="bi bi-trash"></i> Eliminar</button>
                    </div>
                  </td>
                `;
                tbody.appendChild(tr);
              });
            } else {
              tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay archivos en la papelera</td></tr>';
            }

            // Modal para preview
            if (!document.getElementById('modalPreviewArchivo')) {
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
            }

            let bsModalPreview = null;
            if (window.bootstrap) {
              bsModalPreview = new window.bootstrap.Modal(document.getElementById('modalPreviewArchivo'));
            }

            document.querySelectorAll('.btn-preview').forEach(btn => {
              btn.addEventListener('click', function() {
                const ruta = btn.getAttribute('data-ruta');
                const body = document.getElementById('previewArchivoBody');
                body.innerHTML = `<iframe src="${ruta}" style="width:100%;height:70vh;border:1px solid #ccc;"></iframe>`;
                if (window.bootstrap && bsModalPreview) {
                  bsModalPreview.show();
                } else {
                  const modal = document.getElementById('modalPreviewArchivo');
                  modal.style.display = 'block';
                  modal.classList.add('show');
                }
              });
            });

            // Evento para eliminar (mover a papelera)
            document.querySelectorAll('.btn-restaurar-papelera').forEach(btn => {
              btn.addEventListener('click', function() {
                const idDoc = btn.getAttribute('data-id');
                const uuid = btn.getAttribute('data-uuid');
                if (!confirm('¿Seguro que deseas enviar el archivo a la papelera?')) return;
                btn.disabled = true;
                fetch('core/restaurar-papelera-notarial.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({ id_doc: idDoc, uuid: uuid })
                })
                .then(res => res.json())
                .then(resp => {
                  if (resp.ok) {
                    if (window.Swal) {
                      Swal.fire({
                        icon: 'success',
                        title: '¡Enviado a papelera!',
                        text: 'Archivo movido correctamente a la papelera.'
                      });
                    } else {
                      alert('Archivo movido correctamente a la papelera.');
                    }
                    cargarPapeleraNotarial();
                  } else {
                    if (window.Swal) {
                      Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: resp.msg || 'No se pudo mover a la papelera.'
                      });
                    } else {
                      alert('Error: ' + (resp.msg || 'No se pudo mover a la papelera.'));
                    }
                    btn.disabled = false;
                  }
                })
                .catch(() => {
                  if (window.Swal) {
                    Swal.fire({
                      icon: 'error',
                      title: 'Error de conexión',
                      text: 'No se pudo conectar con el servidor.'
                    });
                  } else {
                    alert('Error de conexión al mover el archivo a la papelera.');
                  }
                  btn.disabled = false;
                });
              });
            });
          });
      }
      document.getElementById('btnBuscarPapelera').addEventListener('click', cargarPapeleraNotarial);
      document.getElementById('filtroCategoria').addEventListener('change', cargarPapeleraNotarial);
      document.getElementById('filtroExpediente').addEventListener('change', cargarPapeleraNotarial);
      cargarPapeleraNotarial();
    }
  });
  </script>
<?php endif; ?>
<!--end:: for modulo papelera notariales-->
<script>
  // Función global para abrir el modal de preview PDF
  function verArchivo(uuid) {
      // Cambia la ruta según tu backend para servir el PDF
      var url = 'uploads/' + uuid + '.pdf';
      var iframe = document.getElementById('iframePreviewPDF');
      iframe.src = url;
      var modal = new bootstrap.Modal(document.getElementById('modalPreviewPDF'));
      modal.show();
  }
</script>
<!--begin:: for modulo expedientes notariales archivos-->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'expedientes-notariales' || (isset($_GET['pg']) && $_GET['pg'] === 'expedientes-notariales')): ?>
  <script>
  // JavaScript para manejar expedientes notariales
  document.addEventListener('DOMContentLoaded', function() {
      
      // Obtener el ID del expediente notarial desde la URL o variable PHP
      function getIdNotarial() {
          const urlParams = new URLSearchParams(window.location.search);
          const idFromUrl = urlParams.get('id_notarial');
          
          // Si hay un input hidden con el id_notarial, usar ese valor
          const inputIdNotarial = document.getElementById('id_notarial');
          if (inputIdNotarial && inputIdNotarial.value) {
              return inputIdNotarial.value;
          }
          
          return idFromUrl || '';
      }
      
      // Cargar contadores de todas las categorías
      function cargarContadores() {
          const idNotarial = getIdNotarial();
          
          if (!idNotarial) {
              console.warn('No se encontró ID del expediente notarial');
              return;
          }
          
          fetch(`core/expedientes-notariales-controller.php?action=contar_categorias&id_notarial=${idNotarial}`)
              .then(response => response.json())
              .then(data => {
                  console.log('Contadores cargados:', data);
                  
                  if (data.success) {
                      // Actualizar los badges con los contadores
                      Object.keys(data.conteos).forEach(categoria => {
                          const badge = document.getElementById(`count-${categoria}`);
                          if (badge) {
                              badge.textContent = data.conteos[categoria];
                          }
                      });
                  } else {
                      console.error('Error al cargar contadores:', data.msg);
                  }
              })
              .catch(error => {
                  console.error('Error de red al cargar contadores:', error);
              });
      }
      
      // Cargar archivos de una categoría específica
      function cargarArchivosCategoria(categoria) {
          const idNotarial = getIdNotarial();
          console.log('[Depuración] cargarArchivosCategoria:', { categoria, idNotarial });
          if (!idNotarial) {
              console.warn('No se encontró ID del expediente notarial');
              return;
          }
          fetch(`core/expedientes-notariales-controller.php?action=obtener_categoria&categoria=${categoria}&id_notarial=${idNotarial}`)
              .then(response => response.json())
              .then(data => {
                  console.log(`[Depuración] Respuesta API (${categoria}, id_notarial=${idNotarial}):`, data);
                  if (data.success) {
                      const tbody = document.getElementById(`table-${categoria}`);
                      if (tbody) {
                          actualizarTablaArchivos(tbody, data.archivos, categoria);
                      }
                  } else {
                      console.error(`Error al cargar archivos de ${categoria}:`, data.msg);
                  }
              })
              .catch(error => {
                  console.error(`Error de red al cargar archivos de ${categoria}:`, error);
              });
      }
      
      // Actualizar el contenido de una tabla con los archivos
      function actualizarTablaArchivos(tbody, archivos, categoria) {
          if (archivos.length === 0) {
              tbody.innerHTML = `
                  <tr>
                      <td colspan="4" class="text-center text-muted py-4">
                          No hay documentos de ${obtenerNombreCategoria(categoria)}
                      </td>
                  </tr>
              `;
              return;
          }
          
          tbody.innerHTML = '';
          
          // Mostrar en la consola todos los datos de los archivos que se están jalando
          console.log('Archivos recibidos para categoria', categoria, archivos);
          archivos.forEach(archivo => {
              // Mostrar cada archivo individualmente
              console.log('Archivo:', archivo);
              const tr = document.createElement('tr');
              
              // Formatear fecha
              const fechaPresentacion = archivo.fecha_presentacion ? 
                  new Date(archivo.fecha_presentacion).toLocaleDateString('es-ES') : 
                  'Sin fecha';
              
              tr.innerHTML = `
                  <td class="py-3 px-4">
                      <div class="d-flex align-items-center">
                          <i class="bi bi-file-earmark-pdf text-danger me-2" style="font-size: 1.2rem;"></i>
                          <div>
                              <div class="fw-semibold">${archivo.documento || 'Sin nombre'}</div>
                              <small class="text-muted">${archivo.nombre_archivo || ''}</small>
                          </div>
                      </div>
                  </td>
                  <td class="py-3 px-4" style="width:120px; min-width:100px; max-width:140px; white-space:nowrap;">
                      <span class="badge bg-light text-dark">${fechaPresentacion}</span>
                  </td>
                  <td class="py-3 px-4" style="width:110px; min-width:90px; max-width:130px; white-space:nowrap;">
                      <span class="badge bg-success">Activo</span>
                  </td>
                  <td class="py-3 px-4" style="width:120px; min-width:100px; max-width:150px; white-space:nowrap; text-align:center;">
                      <div class="btn-group" role="group">
                          <button type="button" class="btn btn-outline-primary btn-sm" onclick="verArchivo('${archivo.id_notarial}', '${archivo.uuid}', '${archivo.nombre_archivo}')" title="Ver archivo">
                              <i class="bi bi-eye"></i>
                          </button>
                          <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarArchivo('${archivo.id_doc}', '${categoria}')" title="Eliminar">
                              <i class="bi bi-trash"></i>
                          </button>
                      </div>
                  </td>
              `;
              
              tbody.appendChild(tr);
          });
      }
      
      // Obtener nombre descriptivo de la categoría
      function obtenerNombreCategoria(categoria) {
          const nombres = {
              'compraventa': 'compraventa',
              'prestamo_hipotecario': 'préstamo hipotecario',
              'prestamo_personal': 'préstamo personal', 
              'donacion': 'donación',
              'testamento': 'testamento',
              'herencia': 'herencia',
              'declaracion_heredero_abintestado': 'declaracion de heredero abintestato',
              'capitulaciones_matrimoniales': 'capitulaciones matrimoniales',
              'bodas': 'bodas',
              'separaciones': 'separaciones',
              'divorcios': 'divorcios',
              'poder': 'poder',
              'actas': 'actas',
              'constitucion_sociedades_mercantiles': 'constitución de sociedades mercantiles',
              'poliza': 'póliza',
              'reclamacion_deudas': 'reclamación de deudas',
              'conciliacion': 'conciliación'
          };
          
          return nombres[categoria] || categoria.replace('_', ' ');
      }
      
      // Event listeners para las pestañas
      const tabs = document.querySelectorAll('#notarialTabs button[data-bs-toggle="tab"]');
      tabs.forEach(tab => {
          tab.addEventListener('shown.bs.tab', function(event) {
              const targetTab = event.target.getAttribute('data-bs-target');
              
              // Cargar archivos según la pestaña activa
              switch(targetTab) {
                  case '#patrimonio':
                      cargarArchivosCategoria('compraventa');
                      cargarArchivosCategoria('prestamo_hipotecario');
                      cargarArchivosCategoria('prestamo_personal');
                      cargarArchivosCategoria('donacion');
                      break;
                  case '#sucesiones':
                      cargarArchivosCategoria('testamento');
                      cargarArchivosCategoria('herencia');
                      cargarArchivosCategoria('declaracion_heredero_abintestado');
                      break;
                  case '#familia':
                      cargarArchivosCategoria('capitulaciones_matrimoniales');
                      cargarArchivosCategoria('bodas');
                      cargarArchivosCategoria('separaciones');
                      cargarArchivosCategoria('divorcios');
                      break;
                  case '#poderes':
                      cargarArchivosCategoria('poder');
                      cargarArchivosCategoria('actas');
                      break;
                  case '#societario':
                      cargarArchivosCategoria('constitucion_sociedades_mercantiles');
                      break;
                  case '#otros':
                      cargarArchivosCategoria('poliza');
                      cargarArchivosCategoria('reclamacion_deudas');
                      cargarArchivosCategoria('conciliacion');
                      break;
              }
          });
      });
      
      // Funciones para manejar archivos
      window.verArchivo = function(id_notarial, uuid, nombre_archivo) {
          // Usar el script PHP para servir el PDF con headers correctos
          var pdfUrl = `core/descargar-pdf.php?id_notarial=${id_notarial}&uuid=${uuid}&nombre_archivo=${encodeURIComponent(nombre_archivo)}`;
          var iframe = document.getElementById('iframePreviewPDF');
          if (iframe) {
              iframe.src = pdfUrl;
              var modal = new bootstrap.Modal(document.getElementById('modalPreviewPDF'));
              modal.show();
          } else {
              window.open(pdfUrl, '_blank');
          }
      };
      
      window.descargarArchivo = function(uuid) {
          // Implementar descarga de archivo
          const url = `core/arch-descargar.php?uuid=${uuid}`;
          window.location.href = url;
      };
      
      window.eliminarArchivo = function(idDoc, categoria) {
          if (confirm('¿Estás seguro de que deseas eliminar este archivo?')) {
              fetch('core/mover-papelera-notariales.php', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({ id_doc: Number(idDoc) })
              })
              .then(response => response.json())
              .then(data => {
                  if (data.ok) {
                      cargarArchivosCategoria(categoria);
                      cargarContadores();
                      if (window.Swal) {
                          Swal.fire({
                              icon: 'success',
                              title: 'Movido a papelera',
                              showConfirmButton: true,
                              confirmButtonColor: '#6c63ff',
                              timer: 1500
                          });
                      } else {
                          alert('Archivo movido a la papelera correctamente');
                      }
                  } else {
                      alert('Error al eliminar el archivo: ' + (data.msg || 'Error desconocido'));
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Error de conexión al eliminar el archivo');
              });
          }
      };
      
      // Manejar subida de archivos
      const formSubirArchivo = document.getElementById('formSubirArchivoCategorias');
      if (formSubirArchivo && !formSubirArchivo.dataset.listenerAttached) {
          formSubirArchivo.dataset.listenerAttached = 'true';
          let enviandoArchivo = false;
          formSubirArchivo.addEventListener('submit', function(e) {
              var archivoInput = document.getElementById('archivo_cat');
              if (archivoInput && archivoInput.files.length > 0) {
                  var archivo = archivoInput.files[0];
                  // Validar tipo
                  if (archivo.type !== 'application/pdf') {
                      alert('Solo se permiten archivos PDF.');
                      e.preventDefault();
                      return false;
                  }
                  // Validar tamaño (2MB = 2*1024*1024 bytes)
                  if (archivo.size > 2 * 1024 * 1024) {
                      alert('El archivo no debe superar los 2MB.');
                      e.preventDefault();
                      return false;
                  }
              }
              e.preventDefault();
              if (enviandoArchivo) return; // Evita doble envío
              enviandoArchivo = true;
              const formData = new FormData(formSubirArchivo);
              const categoria = formData.get('categoria_archivo');
              // Incluir SweetAlert2 si no está presente
              if (!window.Swal) {
                  const script = document.createElement('script');
                  script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                  document.head.appendChild(script);
                  script.onload = () => {
                      Swal.fire({
                          icon: 'info',
                          title: 'Cargando...',
                          text: 'Por favor, intenta de nuevo.',
                          timer: 1200,
                          showConfirmButton: false
                      });
                  };
                  enviandoArchivo = false;
                  return;
              }
              fetch('core/subir-archivo-notarial.php', {
                  method: 'POST',
                  body: formData
              })
              .then(async response => {
                  let data;
                  try {
                      data = await response.json();
                  } catch (err) {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error inesperado',
                          text: 'Respuesta inesperada del servidor.'
                      });
                      enviandoArchivo = false;
                      return null;
                  }
                  return data;
              })
              .then(data => {
                  enviandoArchivo = false;
                  if (!data) return;
                  if (data.success) {
                      Swal.fire({
                          icon: 'success',
                          title: '¡Guardado exitosamente!',
                          text: data.msg || 'El archivo se guardó correctamente.',
                          timer: 1800,
                          showConfirmButton: false
                      });
                      // Refrescar solo la tabla de la categoría activa
                      cargarArchivosCategoria(categoria);
                      // Refrescar los contadores de categorías
                      cargarContadores();
                      // Cerrar el modal
                      const modal = bootstrap.Modal.getInstance(document.getElementById('modalSubirArchivoCategorias'));
                      if (modal) modal.hide();
                      // Limpiar el formulario
                      formSubirArchivo.reset();
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: data.msg || data.message || 'Error desconocido.'
                      });
                  }
              })
              .catch(error => {
                  enviandoArchivo = false;
                  Swal.fire({
                      icon: 'error',
                      title: 'Error inesperado',
                      text: error.message || 'No se pudo conectar con el servidor.'
                  });
              });
          });
      }
      
      // Auto-rellenar nombre del archivo
      const inputArchivo = document.getElementById('archivo_cat');
      const inputNombre = document.getElementById('nombre_archivo_cat');
      
      if (inputArchivo && inputNombre) {
          inputArchivo.addEventListener('change', function() {
              if (this.files.length > 0 && !inputNombre.value) {
                  inputNombre.value = this.files[0].name;
              }
          });
      }
      
      // Inicializar: cargar contadores y archivos de la pestaña activa
      cargarContadores();
      
      // Cargar archivos de la pestaña Patrimonio (activa por defecto)
      setTimeout(() => {
          cargarArchivosCategoria('compraventa');
          cargarArchivosCategoria('prestamo_hipotecario');
          cargarArchivosCategoria('prestamo_personal');
          cargarArchivosCategoria('donacion');
      }, 500);
  });
  </script>
<?php endif; ?>
<!--end:: for modulo expedientes notariales archivos-->









<!-- Script solo para reporte-cfdis -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'reporte-cfdis' || (isset($_GET['pg']) && $_GET['pg'] === 'reporte-cfdis')): ?> 
  <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Variables globales para las gráficas
    let graficaComisionistas, graficaEstados, graficaTendencias;
    let ultimaGraficas = null;
    let filtrosActuales = { fecha_inicio: '', fecha_final: '', cliente: '' };

        // Inicializar gráficas
        document.addEventListener('DOMContentLoaded', function() {
            inicializarGraficas();
            // Cargar clientes y luego traer todo
            Promise.resolve(cargarClientes()).then(() => {
                const cli = document.getElementById('cliente');
                if (cli) cli.value = '0';
                filtrosActuales = { fecha_inicio: '', fecha_final: '', cliente: '0' };
                buscarCFDIs(true);
            });
        });

        // Función para cargar clientes en el select
        function cargarClientes() {
            return new Promise((resolve) => {
                const select = document.getElementById('cliente');
                // Agregar opción 'Todos' si no existe
                if (![...select.options].some(o => o.value === '0')) {
                    const optTodos = document.createElement('option');
                    optTodos.value = '0';
                    optTodos.textContent = 'Todos';
                    select.appendChild(optTodos);
                }
                // Cargar desde backend existente
                fetch('core/list-clientes-cfdis-select.php')
                    .then(r => r.text())
                    .then(htmlOptions => {
                        const temp = document.createElement('div');
                        temp.innerHTML = `<select>${htmlOptions}</select>`;
                        const opts = temp.querySelectorAll('option');
                        opts.forEach(opt => select.appendChild(opt));
                    })
                    .catch(() => { /* noop */ })
                    .finally(() => resolve());
            });
        }

        // Función para inicializar las gráficas
        function inicializarGraficas() {
            // Gráfica de Comisionistas (Pie Chart)
            const ctxComisionistas = document.getElementById('grafica-comisionistas').getContext('2d');
            graficaComisionistas = new Chart(ctxComisionistas, {
                type: 'pie',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfica de Estados (Bar Chart)
            const ctxEstados = document.getElementById('grafica-estados').getContext('2d');
            graficaEstados = new Chart(ctxEstados, {
                type: 'bar',
                data: {
                    labels: ['Pagados', 'Pendientes'],
                    datasets: [{
                        label: 'Cantidad',
                        data: [0, 0],
                        backgroundColor: ['#28a745', '#dc3545'],
                        borderColor: ['#1e7e34', '#c82333'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            // Gráfica de Tendencias (Line Chart)
            const ctxTendencias = document.getElementById('grafica-tendencias').getContext('2d');
            graficaTendencias = new Chart(ctxTendencias, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Pagados',
                        data: [],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Pendientes',
                        data: [],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }



        // Función para buscar CFDI's desde backend
        function buscarCFDIs(all = false) {
            const fi = document.getElementById('fecha_inicio').value;
            const ff = document.getElementById('fecha_final').value;
            const cliente = document.getElementById('cliente').value || '';
            filtrosActuales = { fecha_inicio: fi, fecha_final: ff, cliente };

            const params = new URLSearchParams(filtrosActuales);
            if (all) params.set('all', '1');
            return fetch('core/reporte-cfdis-controller.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) throw new Error(data.error || 'Error desconocido');
                actualizarTabla(data.rows);
                actualizarDashboard(data.resumen);
                actualizarGraficas(data.graficas);
            })
            .catch(err => {
                console.error(err);
                mostrarMensaje('Ocurrió un error al cargar el reporte', 'danger');
                // Limpiar vistas
                actualizarTabla([]);
                actualizarDashboard({ total_cfdis: 0, total_general: 0, comision_a: 0, comision_b: 0 });
                actualizarGraficas({ comision_a: 0, comision_b: 0, pagados: 0, pendientes: 0, fechas: [], pagados_tendencia: [], pendientes_tendencia: [] });
            });
        }

        // Eliminado: auto-búsqueda en carga inicial

        // Eliminado: generación de datos de demostración

        // Función para actualizar la tabla
        function actualizarTabla(cfdis) {
            const tbody = document.getElementById('tbody-cfdis');
            tbody.innerHTML = '';
            let totalCfdis = 0;
            let totalGeneral = 0;
            let totalComisiones = 0;
            cfdis.forEach(cfdi => {
                let comisionistas = '';
                let totalComisionCliente = 0;
                if (cfdi.comisiones) {
                    const lista = cfdi.comisiones.split('\n').filter(Boolean);
                    comisionistas = lista.map(c => `<span class='badge bg-secondary me-1'>${c}</span>`).join(' ');
                    lista.forEach(c => {
                        const match = c.match(/\((\d+(?:\.\d+)?)%\)/);
                        if (match && cfdi.total) {
                            totalComisionCliente += (parseFloat(cfdi.total) * parseFloat(match[1]) / 100);
                        }
                    });
                }
                const row = `
                    <tr>
                        <td>${cfdi.cliente || ''}</td>
                        <td>${cfdi.rfc || ''}</td>
                        <td>${cfdi.num_cfdis || 0}</td>
                        <td>$${parseFloat(cfdi.total || 0).toLocaleString('es-MX', {minimumFractionDigits: 2})}</td>
                        <td>${comisionistas}</td>
                        <td>$${totalComisionCliente.toLocaleString('es-MX', {minimumFractionDigits: 2})}</td>
                        <td>
                            <span class="badge ${
                                cfdi.estado_resumen === 'Pagado' ? 'bg-success' : (
                                cfdi.estado_resumen === 'Pendiente' ? 'bg-warning text-dark' : (
                                cfdi.estado_resumen === 'Pagado Pendiente' ? 'bg-info text-dark' : (
                                cfdi.estado_resumen === 'Cancelado' ? 'bg-danger' : 'bg-secondary'))
                            )}">${cfdi.estado_resumen}</span>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
                totalCfdis += parseInt(cfdi.num_cfdis || 0);
                totalGeneral += parseFloat(cfdi.total || 0);
                totalComisiones += totalComisionCliente;
            });
            document.getElementById('total-cfdis').textContent = totalCfdis;
            document.getElementById('total-general').textContent = '$' + totalGeneral.toLocaleString('es-MX', {minimumFractionDigits: 2});
            document.getElementById('total-comisionistas').textContent = '$' + totalComisiones.toLocaleString('es-MX', {minimumFractionDigits: 2});
        }

        // Función para actualizar el dashboard
        function actualizarDashboard(resumen) {
            document.getElementById('card-total-cfdis').textContent = resumen.total_cfdis;
            document.getElementById('card-total-comisionistas').textContent = '$' + parseFloat(resumen.total_comisionistas || 0).toLocaleString('es-MX', {minimumFractionDigits: 2});
            document.getElementById('card-total-general').textContent = '$' + parseFloat(resumen.total_general).toLocaleString('es-MX', {minimumFractionDigits: 2});
        }

        // Función para actualizar las gráficas
        function actualizarGraficas(datosGraficas) {
            // Actualizar gráfica de comisionistas
            graficaComisionistas.data.labels = datosGraficas.comisionistas || [];
            graficaComisionistas.data.datasets[0].data = datosGraficas.comisionistas_totales || [];
            graficaComisionistas.data.datasets[0].backgroundColor = generarPaletaColores((datosGraficas.comisionistas || []).length);
            graficaComisionistas.update();
            // Actualizar gráfica de estados
            graficaEstados.data.datasets[0].data = [datosGraficas.pagados || 0, datosGraficas.pendientes || 0];
            graficaEstados.update();
            // Actualizar gráfica de tendencias
            graficaTendencias.data.labels = datosGraficas.fechas || [];
            graficaTendencias.data.datasets[0].data = datosGraficas.pagados_tendencia || [];
            graficaTendencias.data.datasets[1].data = datosGraficas.pendientes_tendencia || [];
            graficaTendencias.update();
        }

        // Actualiza la gráfica de pastel dependiendo del modo seleccionado
        function actualizarGraficaPastel(g) {
            if (!g) return;
            if (modoPie === 'ab') {
                graficaComisiones.data.labels = ['Comisión A', 'Comisión B'];
                graficaComisiones.data.datasets[0].data = [g.comision_a || 0, g.comision_b || 0];
                graficaComisiones.data.datasets[0].backgroundColor = ['#28a745', '#17a2b8'];
            } else {
                const cs = g.comision_a_socios || { labels: [], data: [] };
                graficaComisiones.data.labels = cs.labels || [];
                graficaComisiones.data.datasets[0].data = (cs.data || []).map(v => Number(v || 0));
                graficaComisiones.data.datasets[0].backgroundColor = generarPaletaColores((cs.labels || []).length);
            }
            graficaComisiones.update();
        }

        function generarPaletaColores(n) {
            const base = ['#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#6610f2', '#0dcaf0', '#198754'];
            const out = [];
            for (let i = 0; i < n; i++) {
                out.push(base[i % base.length]);
            }
            return out;
        }

        // Toggle de la gráfica de pastel
        document.addEventListener('DOMContentLoaded', function() {
            const btnAB = document.getElementById('btn-pie-ab');
            const btnSoc = document.getElementById('btn-pie-socios');
            if (btnAB && btnSoc) {
                btnAB.addEventListener('click', () => {
                    modoPie = 'ab';
                    btnAB.classList.add('active');
                    btnSoc.classList.remove('active');
                    actualizarGraficaPastel(ultimaGraficas);
                });
                btnSoc.addEventListener('click', () => {
                    modoPie = 'socios';
                    btnSoc.classList.add('active');
                    btnAB.classList.remove('active');
                    actualizarGraficaPastel(ultimaGraficas);
                });
            }
        });

        // Función para ver detalle
        function verDetalle(clienteId) {
            // Redirigir a una vista existente de CFDIs filtrada por cliente (si existe)
            if (!clienteId) return;
            const url = new URL(window.location.origin + '/app/index.php');
            // Si existe una página de listados específica, ajustar aquí; por ahora llevar al buscador si existe
            url.searchParams.set('page', 'cfdis');
            window.location.href = url.toString();
        }

        // Función para exportar a Excel
        document.getElementById('exportar-excel').addEventListener('click', function() {
            const button = this;
            const originalText = button.innerHTML;
            button.innerHTML = '<div class="spinner-border spinner-border-sm me-1" role="status"></div>Exportando...';
            button.disabled = true;

            const params = new URLSearchParams(filtrosActuales);
            if (!(filtrosActuales.fecha_inicio || filtrosActuales.fecha_final)) {
                params.set('all', '1');
            }
            const url = 'core/reporte-cfdis-export.php?' + params.toString();
            // Abrir descarga
            window.open(url, '_blank');

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1200);
        });

        // Agregar efecto de carga al botón filtrar
        document.getElementById('filtros-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = document.querySelector('#filtros-form button[type="submit"]');
            const originalText = button.innerHTML;
            
            // Validación: requerir al menos un filtro (fecha inicio, fecha final o cliente != 0)
            const fiVal = document.getElementById('fecha_inicio').value;
            const ffVal = document.getElementById('fecha_final').value;
            const cliVal = document.getElementById('cliente').value;
            const hayFiltros = (fiVal && fiVal.length) || (ffVal && ffVal.length) || (cliVal && cliVal !== '0' && cliVal !== '');
            if (!hayFiltros) {
                mostrarMensaje('Selecciona al menos una fecha o un cliente para buscar.', 'warning');
                return;
            }

            button.innerHTML = '<div class="spinner-border spinner-border-sm me-1" role="status"></div>Cargando...';
            button.disabled = true;
            
            buscarCFDIs().finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });

        // Botón Limpiar: resetea filtros y limpia tabla/dashboard/gráficas
        document.getElementById('btn-limpiar').addEventListener('click', function() {
            const fi = document.getElementById('fecha_inicio');
            const ff = document.getElementById('fecha_final');
            const cli = document.getElementById('cliente');
            fi.value = '';
            ff.value = '';
            if (cli) cli.value = '0';
            filtrosActuales = { fecha_inicio: '', fecha_final: '', cliente: '0' };
            // Tras limpiar, cargar todos los datos
            buscarCFDIs(true);
        });

        function mostrarMensaje(texto, tipo = 'info') {
            // Utilitario simple: usar alert por ahora; puede reemplazarse por toasts
            console.warn(texto);
        }
  </script>
<?php endif; ?>

<!-- Script solo para administracion-cfdis -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'administracion-cfdis' || (isset($_GET['pg']) && $_GET['pg'] === 'administracion-cfdis')): ?> 
  <script>
      // Al cerrar el modal ZIP, abrir el modal principal de cargar CFDIs (solo si no fue por importar)

      // =============================
      // Handler único para guardar CFDI desde el modal XML
      // =============================
      document.addEventListener('DOMContentLoaded', function() {
          // ...existing code...
          // Abrir modal y mostrar datos extraídos al cargar XML
          const btnCargarXml = document.getElementById('btn-cargar-xml');
          if (btnCargarXml) {
              btnCargarXml.addEventListener('click', function() {
                  // ...existing code para cargar XML y abrir modal preview...
                  // Handler para guardar CFDI SOLO cuando el modal está visible
                  setTimeout(function() {
                      const btnGuardarXml = document.getElementById('btn-guardar-xml-cfdi');
                      if (btnGuardarXml) {
                          btnGuardarXml.onclick = function() {
                              const clienteModal = document.getElementById('cliente_cfdi_modal')?.value;
                              const estadoModal = document.getElementById('estado_cfdi_modal')?.value;
                              const xmlFile = window._xmlFilePreview;
                              if (!clienteModal) {
                                  Swal.fire({
                                      icon: 'error',
                                      title: 'Por favor seleccione un cliente',
                                      confirmButtonText: 'OK',
                                      allowOutsideClick: false
                                  });
                                  return;
                              }
                              if (!estadoModal) {
                                  Swal.fire({
                                      icon: 'error',
                                      title: 'Por favor seleccione un estado',
                                      confirmButtonText: 'OK',
                                      allowOutsideClick: false
                                  });
                                  return;
                              }
                              if (!xmlFile) {
                                  Swal.fire({
                                      icon: 'error',
                                      title: 'Por favor seleccione un archivo XML',
                                      confirmButtonText: 'OK',
                                      allowOutsideClick: false
                                  });
                                  return;
                              }
                              btnGuardarXml.disabled = true;
                              btnGuardarXml.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
                              const formData = new FormData();
                              formData.append('cliente_id', clienteModal);
                              formData.append('estado_cfdi', estadoModal);
                              formData.append('archivo_xml', xmlFile);
                              fetch('core/cargar-cfdis.php', {
                                  method: 'POST',
                                  body: formData
                              })
                              .then(response => response.json())
                              .then(data => {
                                  if (data.success) {
                                      Swal.fire({
                                          icon: 'success',
                                          title: data.message || 'CFDI guardado correctamente',
                                          confirmButtonText: 'OK',
                                          allowOutsideClick: false
                                      }).then(() => {
                                          document.body.style.overflow = 'auto';
                                          window.location.replace(window.location.href);
                                      });
                                  } else {
                                      Swal.fire({
                                          icon: 'error',
                                          title: data.error || 'Error al guardar CFDI',
                                          confirmButtonText: 'OK',
                                          allowOutsideClick: false
                                      });
                                  }
                              })
                              .catch(() => {
                                  Swal.fire({
                                      icon: 'error',
                                      title: 'Error de conexión al guardar CFDI',
                                      confirmButtonText: 'OK',
                                      allowOutsideClick: false
                                  });
                              })
                              .finally(() => {
                                  btnGuardarXml.disabled = false;
                                  btnGuardarXml.innerHTML = '<i class="fas fa-save"></i> Guardar CFDI';
                              });
                          }
                      }
                  }, 500); // Espera a que el modal esté visible
              });
          }
          // ...resto del código...
      });
          // Totales CFDI
          function parseMoney(str) {
              if (!str) return 0;
              return parseFloat(str.replace(/[$,%\s]/g, '').replace(/\./g, '.')) || 0;
          }
          function calcularTotalesCFDIS() {
              let totalImpuestos = 0, totalTotal = 0, totalComisiones = 0, count = 0;
              document.querySelectorAll('#cfdis-tbody tr').forEach(function(fila) {
                  const celdas = fila.querySelectorAll('td');
                  if (celdas.length < 8) return;
                  totalImpuestos += parseMoney(celdas[5].textContent);
                  totalTotal += parseMoney(celdas[6].textContent);
                  const matches = celdas[7].textContent.match(/(\d+(\.\d+)?)/g);
                  if (matches) matches.forEach(num => { totalComisiones += parseFloat(num); });
                  count++;
              });
              document.getElementById('total-impuestos').textContent = '$' + totalImpuestos.toFixed(2);
              document.getElementById('total-total').textContent = '$' + totalTotal.toFixed(2);
              document.getElementById('total-comisiones').textContent = totalComisiones.toFixed(2) + '%';
              document.getElementById('total_cfdis').textContent = count;
              document.getElementById('resumen_total_cfdis').textContent = count;
              document.getElementById('resumen_total_impuestos').textContent = '$' + totalImpuestos.toFixed(2);
              document.getElementById('resumen_total_total').textContent = '$' + totalTotal.toFixed(2);
          }
          calcularTotalesCFDIS();
          // Modal XML preview
          const btnCargarXml = document.getElementById('btn-cargar-xml');
          if (btnCargarXml) {
              btnCargarXml.addEventListener('click', function() {
                  const xmlInput = document.getElementById('archivo_xml');
                  if (xmlInput.files.length > 0) {
                      const xmlFile = xmlInput.files[0];
                      window._xmlFilePreview = xmlFile;
                      const fd = new FormData();
                      fd.append('archivo_xml', xmlFile);
                      fetch('core/preview-xml-cfdi.php', {
                          method: 'POST',
                          body: fd
                      })
                      .then(res => res.json())
                      .then(data => {
                          if (data.resultados && data.resultados.length > 0) {
                              const cfdi = data.resultados[0];
                              const folio = cfdi.folio || cfdi.Folio || '';
                              const fecha = cfdi.fecha_emision || cfdi.FechaEmision || cfdi.fecha || cfdi.Fecha || '';
                              const emisor = cfdi.emisor || cfdi.Emisor || '';
                              const rfcEmisor = cfdi.rfc || cfdi.RFC || cfdi.rfc_emisor || cfdi.RFCEmisor || '';
                              const tipoRaw = cfdi.tipo || cfdi.Tipo || cfdi.tipo_comprobante || cfdi.TipoDeComprobante || '';
                              const tipoMap = { 'I': 'Ingreso', 'E': 'Egreso', 'T': 'Traslado', 'P': 'Pago', 'N': 'Nómina' };
                              const tipoLegible = tipoMap[tipoRaw] || tipoRaw;
                              let impuestos = cfdi.impuestos || cfdi.Impuestos || cfdi.impuesto || cfdi.Impuesto || cfdi.iva || cfdi.IVA || '';
                              if (impuestos === undefined || impuestos === null || impuestos === '' || isNaN(impuestos)) impuestos = '$0.00';
                              else impuestos = `$${parseFloat(impuestos).toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
                              let total = cfdi.total || cfdi.Total || '';
                              if (total === undefined || total === null || total === '' || isNaN(total)) total = '$0.00';
                              else total = `$${parseFloat(total).toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
                              let html = `<div class='row'>
                                  <div class='col-md-6'><b>Folio:</b> ${folio}</div>
                                  <div class='col-md-6'><b>Fecha de Emisión:</b> ${fecha}</div>
                              </div>
                              <div class='row'>
                                  <div class='col-md-6'><b>Emisor:</b> ${emisor}</div>
                                  <div class='col-md-6'><b>RFC Emisor:</b> ${rfcEmisor}</div>
                              </div>
                              <div class='row'>
                                  <div class='col-md-6'><b>Tipo:</b> ${tipoLegible}</div>
                                  <div class='col-md-6'><b>Total de Impuestos:</b> ${impuestos}</div>
                              </div>
                              <div class='row'>
                                  <div class='col-md-6'><b>Total:</b> ${total}</div>
                              </div>`;
                              const xmlFieldsPreview = document.getElementById('xml-fields-preview');
                              if (xmlFieldsPreview) {
                                  xmlFieldsPreview.innerHTML = '';
                                  xmlFieldsPreview.style.background = '#fff';
                                  xmlFieldsPreview.innerHTML = html;
                              }
                              const previewZipTable = document.getElementById('preview-zip-table');
                              if (previewZipTable) previewZipTable.innerHTML = '';
                              const infoAutoFields = document.getElementById('info-auto-fields');
                              if (infoAutoFields) infoAutoFields.style.display = 'none';
                              // Abrir el modal de preview
                              const modalPrincipalEl = document.getElementById('modal-cargar-cfdis');
                              const modalPreviewEl = document.getElementById('modal-xml-preview');
                              let modalPrincipal = bootstrap.Modal.getInstance(modalPrincipalEl);
                              if (!modalPrincipal) modalPrincipal = new bootstrap.Modal(modalPrincipalEl);
                              modalPrincipal.hide();
                          }
                      });
                  }
              });
          }
          // ...existing code...

          // Guardar CFDIs desde formulario principal
          const formCargarCFDIs = document.getElementById('form-cargar-cfdis');
          if (formCargarCFDIs) {
              formCargarCFDIs.addEventListener('submit', function(e) {
                  e.preventDefault();
                  const formData = new FormData(formCargarCFDIs);
                  const cliente = document.getElementById('cliente_cfdi');
                  if (cliente) formData.append('cliente_id', cliente.value);
                  const estado = document.getElementById('estado_cfdi');
                  if (estado) formData.append('estado_cfdi', estado.value);
                  fetch('core/cargar-cfdis.php', {
                      method: 'POST',
                      body: formData
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          mostrarNotificacion(data.message || 'CFDI guardado correctamente', 'success');
                          if (window.recargarTablaCFDIs) window.recargarTablaCFDIs();
                          const modalPrincipalEl = document.getElementById('modal-cargar-cfdis');
                          if (modalPrincipalEl) bootstrap.Modal.getInstance(modalPrincipalEl)?.hide();
                          const xmlInput = document.getElementById('archivo_xml');
                          if (xmlInput) xmlInput.value = '';
                                  // Eliminar manualmente el backdrop si queda visible
                                  setTimeout(function() {
                                      document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
                                      document.body.classList.remove('modal-open');
                                  }, 300);
                      } else {
                          mostrarNotificacion(data.error || 'Error al guardar CFDI', 'error');
                      }
                  })
                  .catch(() => {
                      mostrarNotificacion('Error de conexión al guardar CFDI', 'error');
                  });
              });
          }

      // --- FIN SCRIPT REPARADO ---
                                  setTimeout(function() {
                                      document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
                                      document.body.classList.remove('modal-open');
                                  }, 300);

      if (typeof jQuery === 'undefined') {
          const script = document.createElement('script');
          script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
          script.onload = function() {
              $(document).ready(function() {
                  inicializarScriptCFDI();
              });
          };
          script.onerror = function() {
              mostrarNotificacion('Error cargando jQuery', 'error');
          };
          document.head.appendChild(script);
                              setTimeout(function() {
                                  document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
                                  document.body.classList.remove('modal-open');
                              }, 300);
      } else {
          $(document).ready(function() {
              inicializarScriptCFDI();
          });
      }

      function inicializarScriptCFDI() {
          // Log para depuración
          console.log('Inicializando script CFDI...');

          // Ya no se crea el formulario de filtros dinámicamente porque está en el HTML

          const fileInput = document.getElementById('archivos_cfdi_modal');
          const clienteSelect = document.getElementById('cliente_cfdi');
          const btnGuardar = document.getElementById('btn-cargar-cfdis');
          const modal = document.getElementById('modal-cargar-cfdis');
          
          if (fileInput) {
              fileInput.addEventListener('change', function(event) {
                  // Actualizar label del input
                  const label = this.nextElementSibling;
                  if (label && label.classList.contains('custom-file-label')) {
                      const fileNames = Array.from(this.files).map(file => file.name).join(', ');
                      label.textContent = fileNames;
                  }
                  
                  if (this.files.length > 0) {
                      const file = this.files[0];
                      
                      // Limpiar campos primero
                      limpiarCamposModal();
                      
                      // Procesar con AJAX usando jQuery para obtener preview
                      const formData = new FormData();
                      formData.append('xml_file', file);
                      
                      if (typeof $ === 'undefined') {
                          mostrarNotificacion('jQuery no está cargado', 'error');
                          return;
                      }
                      
                      $.ajax({
                          url: 'core/procesar-xml-cfdi.php',
                          type: 'POST',
                          data: formData,
                          processData: false,
                          contentType: false,
                          timeout: 10000,
                          success: function(response) {
                              try {
                                  let data;
                                  if (typeof response === 'string') {
                                      data = JSON.parse(response);
                                  } else {
                                      data = response;
                                  }
                                  
                                  if (data.success && data.datos) {
                                      const datosXML = data.datos;
                                      // Llenar campos con datos reales
                                      const campoValores = {
                                          'preview_fecha': datosXML.fecha_formateada || '',
                                          'preview_folio': datosXML.folio_completo || '', 
                                          'preview_emisor': datosXML.emisor_nombre || '',
                                          'preview_rfc': datosXML.emisor_rfc || '',
                                          'preview_tipo': datosXML.tipo_texto || '',
                                          'preview_total': datosXML.total_formateado || '',
                                          'preview_impuestos': datosXML.impuestos_formateado || ''
                                      };
                                      Object.keys(campoValores).forEach(function(campoId) {
                                          const campo = document.getElementById(campoId);
                                          if (campo) {
                                              campo.value = campoValores[campoId];
                                          }
                                      });
                                      
                                  } else {
                                      mostrarNotificacion('Error procesando XML: ' + (data.error || 'Error desconocido'), 'error');
                                  }
                                  
                              } catch (e) {
                                  mostrarNotificacion('Error parseando respuesta del servidor', 'error');
                              }
                          },
                          error: function(xhr, status, error) {
                              let errorMsg = 'Error de conexión';
                              if (xhr.status === 404) {
                                  errorMsg = 'Archivo procesar-xml-cfdi.php no encontrado';
                              } else if (xhr.status === 500) {
                                  errorMsg = 'Error interno del servidor';
                              }
                              mostrarNotificacion(errorMsg, 'error');
                          }
                      });
                      
                  } else {
                      limpiarCamposModal();
                  }
              });
          }
          
          // Función para limpiar campos
          function limpiarCamposModal() {
              const campos = ['preview_fecha', 'preview_folio', 'preview_emisor', 'preview_rfc', 'preview_tipo', 'preview_total', 'preview_impuestos'];
              campos.forEach(function(campoId) {
                  const campo = document.getElementById(campoId);
                  if (campo) {
                      campo.value = '';
                  }
              });
          }
          
          // Event listener para cerrar modal
          if (modal) {
              modal.addEventListener('hidden.bs.modal', function() {
                  const form = document.getElementById('form-cargar-cfdis');
                  if (form) form.reset();
                  limpiarCamposModal();
                  
                  // Resetear label del archivo
                  const fileInput = document.getElementById('archivos_cfdi_modal');
                  if (fileInput) {
                      const label = fileInput.nextElementSibling;
                      if (label && label.classList.contains('custom-file-label')) {
                          label.textContent = 'Seleccionar archivos XML...';
                      }
                  }
              });
          }
          
          // Event listener para botón guardar
          if (btnGuardar) {
          btnGuardar.addEventListener('click', function() {
              console.log('[DEBUG] Click en Guardar CFDI');
              const clienteSelect = document.getElementById('cliente_cfdi');
              const archivoInput = document.getElementById('archivos_cfdi_modal');
              const estadoSelect = document.getElementById('estado_cfdi');
              if (!clienteSelect || !archivoInput || !estadoSelect) {
              mostrarNotificacion('Elementos del formulario no encontrados', 'error');
              console.error('[DEBUG] Elementos del formulario no encontrados');
              return;
              }
              const cliente = clienteSelect.value;
              const archivos = archivoInput.files;
              const estado = estadoSelect.value;
              if (!cliente) {
              mostrarNotificacion('Por favor seleccione un cliente', 'error');
              console.warn('[DEBUG] Cliente no seleccionado');
              return;
              }
              if (!estado) {
              mostrarNotificacion('Por favor seleccione un estado', 'error');
              console.warn('[DEBUG] Estado no seleccionado');
              return;
              }
              if (archivos.length === 0) {
              mostrarNotificacion('Por favor seleccione al menos un archivo XML', 'error');
              console.warn('[DEBUG] No hay archivos XML seleccionados');
              return;
              }
              btnGuardar.disabled = true;
              btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
              const formData = new FormData();
              formData.append('cliente_id', cliente);
              formData.append('estado_cfdi', estado);
              for (let i = 0; i < archivos.length; i++) {
              formData.append('archivos_cfdi[]', archivos[i]);
              }
              if (typeof $ === 'undefined') {
              mostrarNotificacion('jQuery no está cargado', 'error');
              console.error('[DEBUG] jQuery no está cargado');
              btnGuardar.disabled = false;
              btnGuardar.innerHTML = '<i class="fas fa-save"></i> Guardar';
              return;
              }
              console.log('[DEBUG] Enviando AJAX a core/cargar-cfdis.php');
              $.ajax({
                  url: 'core/cargar-cfdis.php',
                  type: 'POST',
                  data: formData,
                  processData: false,
                  contentType: false,
                  timeout: 60000,
                  success: function(response) {
                  console.log('[DEBUG] Respuesta AJAX cargar-cfdis.php:', response);
                  try {
                      let data;
                      if (typeof response === 'string') {
                      data = JSON.parse(response);
                      } else {
                      data = response;
                      }
                      if (data.success) {
                                      let msg = '¡CFDI guardado correctamente!';
                                      if (data.errores > 0 && Array.isArray(data.detalles_errores)) {
                                          msg += '<br><br><b>Archivos no guardados:</b><ul style="text-align:left">';
                                          data.detalles_errores.forEach(function(err) {
                                              msg += '<li>' + err + '</li>';
                                          });
                                          msg += '</ul>';
                                      }
                                      Swal.fire({
                                          icon: 'success',
                                          title: 'CFDI guardado',
                                          html: msg,
                                          confirmButtonText: 'OK',
                                          allowOutsideClick: false
                                                      }).then(() => {
                                                          document.body.style.overflow = 'auto';
                                                          window.location.replace(window.location.href);
                                                      });
                      } else {
                      let errorMsg = data.error || 'Error desconocido';
                      let detalles = '';
                      if (data.detalles_errores && Array.isArray(data.detalles_errores) && data.detalles_errores.length > 0) {
                          detalles = '<br><b>Detalles:</b><ul style="text-align:left">';
                          data.detalles_errores.forEach(function(err) {
                          detalles += '<li>' + err + '</li>';
                          });
                          detalles += '</ul>';
                      }
                      mostrarNotificacion('Error: ' + errorMsg + detalles, 'error');
                      console.error('[DEBUG] Error en respuesta:', errorMsg, detalles);
                      }
                  } catch (e) {
                      mostrarNotificacion('Error procesando respuesta del servidor', 'error');
                      console.error('[DEBUG] Error parseando respuesta:', e);
                  }
                  },
                  error: function(xhr, status, error) {
                  let errorMsg = 'Error de conexión al guardar CFDIs';
                  if (xhr.status === 404) {
                      errorMsg = 'Archivo cargar-cfdis.php no encontrado';
                  } else if (xhr.status === 500) {
                      errorMsg = 'Error interno del servidor al guardar';
                  } else if (xhr.status === 0) {
                      errorMsg = 'Error de conexión. Verifica que el servidor esté funcionando';
                  }
                  mostrarNotificacion(errorMsg, 'error');
                  console.error('[DEBUG] Error AJAX:', errorMsg, xhr, status, error);
                  },
                  complete: function() {
                  btnGuardar.disabled = false;
                  btnGuardar.innerHTML = '<i class="fas fa-save"></i> Guardar';
                  }
              });
              });
          }
          
          // ===== FUNCIONALIDAD DE FILTROS PARA TABLA HTML =====
          // Función para recargar la tabla usando buscar-cfdis.php
          function recargarTablaCFDIs() {
              let tipo = $('#filtro_tipo').val();
              if (tipo === 'Todos') tipo = '';
              const filtros = {
                  folio: $('#filtro_folio').val() || '',
                  emisor: $('#filtro_emisor').val() || '',
                  rfc: $('#filtro_rfc').val() || '',
                  tipo: tipo,
                  fecha: $('#filtro_fecha').val() || ''
              };
              console.log('Enviando filtros:', filtros);

              // Mostrar indicador de carga
              $('#cfdis-tbody').html('<tr><td colspan="11" class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</td></tr>');

              $.ajax({
                  url: 'core/buscar-cfdis.php',
                  type: 'POST',
                  data: filtros,
                  dataType: 'html',
                  success: function(html) {
                      // Reemplazar el contenido del tbody con las nuevas filas
                      $('#cfdis-tbody').html(html);
                      // Contar las filas (excluyendo filas de "no hay datos")
                      let totalFilas = $('#cfdis-tbody tr').length;
                      if ($('#cfdis-tbody tr td[colspan]').length > 0) {
                          totalFilas = 0; // Si hay mensaje de "no hay datos", el total es 0
                      }
                      $('#total_cfdis').text(totalFilas);
                  },
                  error: function(xhr, status, error) {
                      console.error('Error en AJAX:', error);
                      $('#cfdis-tbody').html('<tr><td colspan="11" class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i> Error al buscar CFDIs</td></tr>');
                      $('#total_cfdis').text(0);
                  }
              });
          }

          // Solo buscar al dar click en el botón o presionar Enter
          $('#filtro_folio, #filtro_emisor, #filtro_rfc, #filtro_tipo, #filtro_fecha').on('keypress', function(e) {
              if (e.which === 13) {
                  e.preventDefault();
                  console.log('Enter presionado en filtro, recargando tabla...');
                  recargarTablaCFDIs();
              }
          });

          // Botón para buscar CFDIs
          $(document).on('click', '#btn-buscar-cfdis', function(e) {
              e.preventDefault();
              console.log('Click en btn-buscar-cfdis, recargando tabla...');
              recargarTablaCFDIs();
          });

          // Botón para limpiar filtros
          $(document).on('click', '#btn-limpiar-filtros', function() {
              $('#filtro_folio, #filtro_emisor, #filtro_rfc, #filtro_fecha').val('');
              $('#filtro_tipo').val('');
              // Recargar la página para restaurar el include PHP original
              location.reload();
          });

          // Guardar función global para recargar desde otros lugares
          window.recargarTablaCFDIs = recargarTablaCFDIs;

          // Prevenir submit del formulario de filtros si existe
          $("#form-filtros-cfdis").on('submit', function(e) {
              e.preventDefault();
              console.log('Submit en form-filtros-cfdis, recargando tabla...');
              recargarTablaCFDIs();
          });

          // Función para actualizar el total mostrado contando filas del tbody
          function actualizarTotalCFDIs() {
              const filas = Array.from(document.querySelectorAll('#cfdis-tbody tr'));
              const totalValidas = filas.filter(tr => !tr.querySelector('td[colspan]')).length;
              const totalEl = document.getElementById('total_cfdis');
              if (totalEl) totalEl.textContent = totalValidas;
          }

          // Calcular total inicial con las filas renderizadas por PHP
          actualizarTotalCFDIs();
      }


      // ===== MODAL VER CFDI =====
      // Evento global para abrir y llenar el modal de ver CFDI
      // Este bloque reemplaza el uso de jQuery y centraliza la lógica

      document.addEventListener('click', function(e) {
          var target = e.target;
          var btn = null;
          // Detecta el botón o ícono de ver CFDI
          if (target.classList && target.classList.contains('btn-info') && target.title === 'Ver CFDI') {
              btn = target;
          } else if (target.closest && target.closest('.btn-info[title="Ver CFDI"]')) {
              btn = target.closest('.btn-info[title="Ver CFDI"]');
          }
          if (btn) {
              e.preventDefault();
              // Obtener el idCfdi desde el atributo data-idcfdi o desde el onclick
              var idCfdi = btn.getAttribute('data-idcfdi');
              if (!idCfdi) {
                  var onclickAttr = btn.getAttribute('onclick');
                  if (onclickAttr) {
                      var match = onclickAttr.match(/verCFDI\((\d+)\)/);
                      if (match) {
                          idCfdi = match[1];
                      }
                  }
              }
              if (idCfdi) {
                  var modal = document.getElementById('modal-ver-cfdi');
                  if (modal && typeof bootstrap !== 'undefined') {
                      let bsModal = bootstrap.Modal.getInstance(modal);
                      if (!bsModal) bsModal = new bootstrap.Modal(modal);
                      bsModal.show();
                  }
                  // Lógica para llenar el modal
                  const loader = document.getElementById('ver-cfdi-loader');
                  const datos = document.getElementById('ver-cfdi-datos');
                  const btnModificar = document.getElementById('btn-modificar-cfdi');
                  const btnGuardar = document.getElementById('btn-guardar-cfdi');
                  const selectEstado = document.getElementById('ver-estado');
                  const inputId = document.getElementById('ver-id-cfdi');
                  if (datos) datos.style.display = 'none';
                  if (loader) loader.style.display = 'block';
                  if (btnGuardar) btnGuardar.classList.add('d-none');
                  if (selectEstado) selectEstado.disabled = true;
                  fetch('core/ver-cfdi.php?id=' + encodeURIComponent(idCfdi))
                      .then(r => r.json())
                      .then(res => {
                          try {
                              const comisionesInput = document.getElementById('ver-comisiones-list');
                              if (res.success && res.cfdi) {
                                  if (inputId) inputId.value = res.cfdi.id_cfdi || '';
                                  document.getElementById('ver-folio').value = res.cfdi.folio || '';
                                  document.getElementById('ver-fecha-emision').value = res.cfdi.fecha_emision || '';
                                  document.getElementById('ver-emisor').value = res.cfdi.emisor || '';
                                  document.getElementById('ver-rfc').value = res.cfdi.rfc || '';
                                  document.getElementById('ver-tipo').value = res.cfdi.tipo || '';
                                  document.getElementById('ver-importe').value = res.cfdi.importe || '';
                                  document.getElementById('ver-total').value = res.cfdi.total || '';
                                  document.getElementById('ver-cliente').value = res.cfdi.cliente_nombre || '';

                                  // Estado: si es EFO, mostrarlo y deshabilitar select
                                  if (selectEstado) {
                                      // Eliminar opción EFO si existe y el CFDI no es EFO
                                      let efoOption = selectEstado.querySelector('option[value="EFO"]');
                                      if (res.cfdi.estado === 'EFO') {
                                          if (!efoOption) {
                                              efoOption = document.createElement('option');
                                              efoOption.value = 'EFO';
                                              efoOption.textContent = 'EFO';
                                              selectEstado.appendChild(efoOption);
                                          }
                                          selectEstado.value = 'EFO';
                                          selectEstado.disabled = true;
                                          if (btnModificar) {
                                              btnModificar.disabled = true;
                                              btnModificar.classList.add('disabled');
                                          }
                                      } else {
                                          if (efoOption) efoOption.remove();
                                          selectEstado.value = res.cfdi.estado || '';
                                          selectEstado.disabled = true;
                                          if (btnModificar) {
                                              btnModificar.disabled = false;
                                              btnModificar.classList.remove('disabled');
                                          }
                                      }
                                  }

                                  // Comisiones
                                  if (comisionesInput) {
                                      if (Array.isArray(res.cfdi.comisionistas) && res.cfdi.comisionistas.length > 0) {
                                          let partes = [];
                                          res.cfdi.comisionistas.forEach(function(com) {
                                              let montoNum = parseFloat(com.monto.replace(/[^\d\.]/g, ''));
                                              partes.push(com.nombre + ': $' + montoNum.toFixed(2));
                                          });
                                          comisionesInput.value = partes.join(', ');
                                          comisionesInput.placeholder = '';
                                      } else {
                                          comisionesInput.value = '';
                                          comisionesInput.placeholder = 'No hay comisiones para este cliente.';
                                      }
                                  }
                                  if (datos) datos.style.display = 'block';
                                  if (loader) loader.style.display = 'none';
                                  if (btnModificar && res.cfdi.estado !== 'EFO') btnModificar.classList.remove('d-none');
                                  if (btnGuardar) btnGuardar.classList.add('d-none');
                                  // btnModificar solo habilitado si no es EFO
                                  btnModificar.onclick = function() {
                                      if (selectEstado && res.cfdi.estado !== 'EFO') selectEstado.disabled = false;
                                      btnModificar.classList.add('d-none');
                                      if (btnGuardar) btnGuardar.classList.remove('d-none');
                                  };
                                  if (btnGuardar) {
                                      btnGuardar.onclick = function() {
                                          guardarCambioEstadoCFDI();
                                      };
                                  }
                              } else {
                                  mostrarNotificacion(res.error || 'No se pudo consultar el CFDI', 'error');
                                  if (datos) datos.style.display = 'none';
                                  if (loader) loader.style.display = 'none';
                              }
                          } catch (e) {
                              mostrarNotificacion('Error en depuración: ' + e.message, 'error');
                          }
                      })
                      .catch(() => {
                          if (loader) loader.style.display = 'none';
                          mostrarNotificacion('Error consultando CFDI', 'error');
                      });
              }
          }
      });


      function guardarCambioEstadoCFDI() {
          const id = document.getElementById('ver-id-cfdi').value;
          const estado = document.getElementById('ver-estado').value;
          const btnGuardar = document.getElementById('btn-guardar-cfdi');
          const btnModificar = document.getElementById('btn-modificar-cfdi');
          const selectEstado = document.getElementById('ver-estado');
          if (!id || !estado) {
              mostrarNotificacion('Datos incompletos para guardar', 'error');
              return;
          }
          btnGuardar.disabled = true;
          btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
          fetch('core/modificar-cfdi.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ id: id, estado: estado })
          })
          .then(r => r.json())
          .then(res => {
              if (res.success) {
                  mostrarNotificacion('¡Estado actualizado correctamente!', 'success', function() {
                      if (window.recargarTablaCFDIs) window.recargarTablaCFDIs();
                      // Cerrar el modal de detalle CFDI si está abierto
                      var modal = document.getElementById('modal-ver-cfdi');
                      if (modal && typeof bootstrap !== 'undefined') {
                          let bsModal = bootstrap.Modal.getInstance(modal);
                          if (!bsModal) bsModal = new bootstrap.Modal(modal);
                          bsModal.hide();
                      }
                  });
                  if (selectEstado) selectEstado.disabled = true;
                  if (btnGuardar) btnGuardar.classList.add('d-none');
                  if (btnModificar) btnModificar.classList.remove('d-none');
              } else {
                  mostrarNotificacion(res.error || 'No se pudo actualizar el estado', 'error');
              }
          })
          .catch(() => {
              mostrarNotificacion('Error al guardar el estado', 'error');
          })
          .finally(() => {
              btnGuardar.disabled = false;
              btnGuardar.innerHTML = '<i class="fas fa-save"></i> Guardar';
          });
      }

      // ===== FUNCIÓN PARA ENVIAR CORREO =====
      function enviarCorreoCFDI(idCfdi) {
          if (!idCfdi) {
              mostrarNotificacion('ID de CFDI inválido', 'error');
              return;
          }

          // Primero consultamos el CFDI para obtener el correo del cliente
          fetch('core/ver-cfdi.php?id=' + encodeURIComponent(idCfdi))
              .then(r => r.json())
              .then(res => {
                  if (!res.success || !res.cfdi) {
                      throw new Error(res.error || 'No se pudo obtener el CFDI');
                  }
                  const correo = res.cfdi.cliente_correo || '';
                  const cliente = res.cfdi.cliente_nombre || 'cliente';
                  const folio = res.cfdi.folio || '';

                  if (!correo) {
                      Swal.fire({
                          icon: 'warning',
                          title: 'Sin correo registrado',
                          text: 'El cliente no tiene un correo registrado. Por favor actualícelo en el perfil del cliente.'
                      });
                      return;
                  }

                  Swal.fire({
                      icon: 'question',
                      title: '¿Enviar CFDI por correo?',
                      html: `<div class="text-start">Se enviará el CFDI <strong>${folio}</strong> a:<br><strong>${cliente}</strong><br><span class="text-primary">${correo}</span></div>`,
                      showCancelButton: true,
                      confirmButtonText: 'Sí, enviar',
                      cancelButtonText: 'Cancelar',
                      showLoaderOnConfirm: true,
                      preConfirm: () => {
                          return fetch('core/enviar-correo-cfdi.php', {
                              method: 'POST',
                              headers: { 'Content-Type': 'application/json' },
                              body: JSON.stringify({ id_cfdi: idCfdi })
                          })
                          .then(resp => resp.json())
                          .then(data => {
                              if (!data.success) {
                                  throw new Error(data.error || 'Error al enviar el correo');
                              }
                              return data;
                          })
                          .catch(err => {
                              Swal.showValidationMessage('Error: ' + err.message);
                          });
                      },
                      allowOutsideClick: () => !Swal.isLoading()
                  }).then((result) => {
                      if (result.isConfirmed) {
                          mostrarNotificacion('¡Correo enviado exitosamente!', 'success');
                      }
                  });
              })
              .catch(err => {
                  mostrarNotificacion(err.message || 'Error al preparar el envío de correo', 'error');
              });
      }
  </script>
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          
          const btnCargarXml = document.getElementById('btn-cargar-xml');
          if (btnCargarXml) {
              btnCargarXml.addEventListener('click', function() {
                  const xmlInput = document.getElementById('archivo_xml');
                  if (xmlInput.files.length > 0) {
                      const xmlFile = xmlInput.files[0];
                      const fd = new FormData();
                      fd.append('archivo_xml', xmlFile);
                      fetch('core/preview-xml-cfdi.php', {
                          method: 'POST',
                          body: fd
                      })
                      .then(res => res.json())
                      .then(data => {
                          if (data.resultados && data.resultados.length > 0) {
                              const cfdi = data.resultados[0];
                              // Leer variantes de nombre para cada campo
                              const folio = cfdi.folio || cfdi.Folio || '';
                              const fecha = cfdi.fecha_emision || cfdi.FechaEmision || cfdi.fecha || cfdi.Fecha || '';
                              const emisor = cfdi.emisor || cfdi.Emisor || '';
                              const rfcEmisor = cfdi.rfc || cfdi.RFC || cfdi.rfc_emisor || cfdi.RFCEmisor || '';
                              const tipoRaw = cfdi.tipo || cfdi.Tipo || cfdi.tipo_comprobante || cfdi.TipoDeComprobante || '';
                              const tipoMap = { 'I': 'Ingreso', 'E': 'Egreso', 'T': 'Traslado', 'P': 'Pago', 'N': 'Nómina' };
                              const tipoLegible = tipoMap[tipoRaw] || tipoRaw;
                              let impuestos = cfdi.impuestos || cfdi.Impuestos || cfdi.impuesto || cfdi.Impuesto || cfdi.iva || cfdi.IVA || '';
                              if (impuestos === undefined || impuestos === null || impuestos === '' || isNaN(impuestos)) impuestos = '$0.00';
                              else impuestos = `$${parseFloat(impuestos).toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
                              let total = cfdi.total || cfdi.Total || '';
                              if (total === undefined || total === null || total === '' || isNaN(total)) total = '$0.00';
                              else total = `$${parseFloat(total).toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
                              let html = `<div class='row'>
                                  <div class='col-md-6'><b>Folio:</b> ${folio}</div>
                                  <div class='col-md-6'><b>Fecha de Emisión:</b> ${fecha}</div>
                              </div>
                              <div class='row'>
                                  <div class='col-md-6'><b>Emisor:</b> ${emisor}</div>
                                  <div class='col-md-6'><b>RFC Emisor:</b> ${rfcEmisor}</div>
                              </div>
                              <div class='row'>
                                  <div class='col-md-6'><b>Tipo:</b> ${tipoLegible}</div>
                                  <div class='col-md-6'><b>Total de Impuestos:</b> ${impuestos}</div>
                              </div>
                              <div class='row'>
                                  <div class='col-md-6'><b>Total:</b> ${total}</div>
                              </div>`;
                              document.getElementById('xml-fields-preview').innerHTML = html;
                              // Cerrar el modal principal y abrir el de preview solo cuando termine de ocultarse
                              const modalPrincipalEl = document.getElementById('modal-cargar-cfdis');
                              const modalPreviewEl = document.getElementById('modal-xml-preview');
                              let modalPrincipal = bootstrap.Modal.getInstance(modalPrincipalEl);
                              if (!modalPrincipal) {
                                  modalPrincipal = new bootstrap.Modal(modalPrincipalEl);
                              }
                              // Cerrar el modal principal y abrir el de preview XML solo cuando termine de ocultarse
                              function abrirPreviewXML() {
                                  const modalPreviewEl = document.getElementById('modal-xml-preview');
                                  if (modalPreviewEl) {
                                      const modalPreview = new bootstrap.Modal(modalPreviewEl);
                                      modalPreview.show();
                                  } else {
                                      console.error('No se encontró el modal de preview XML en el DOM');
                                  }
                                  modalPrincipalEl.removeEventListener('hidden.bs.modal', abrirPreviewXML);
                              }
                              modalPrincipalEl.addEventListener('hidden.bs.modal', abrirPreviewXML);
                              modalPrincipal.hide();
                          } else {
                              alert('No se pudo extraer información del XML.');
                          }
                      })
                      .catch(() => {
                          alert('Error al procesar el XML.');
                      });
                  } else {
                      alert('Selecciona un archivo XML primero.');
                  }
              });
          }
      });
  </script>
  <!-- Scripts al final del HTML -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery debe ir antes de Bootstrap y antes de cualquier script personalizado -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
      // Limpiar campos y tabla al cancelar
      function showModalCargarCFDI() {
          const modalCargar = document.getElementById('modal-cargar-cfdis');
          if (modalCargar && typeof bootstrap !== 'undefined') {
              let bsModalCargar = bootstrap.Modal.getInstance(modalCargar);
              if (!bsModalCargar) bsModalCargar = new bootstrap.Modal(modalCargar);
              bsModalCargar.show();
          }
      }

      const btnCancelarXml = document.querySelector('#modal-xml-preview .btn-secondary[data-bs-dismiss="modal"]');
      if (btnCancelarXml) {
          btnCancelarXml.addEventListener('click', function() {
              // Limpiar inputs de archivo
              const xmlInput = document.getElementById('archivo_xml');
              const zipInput = document.getElementById('archivo_zip');
              if (xmlInput) xmlInput.value = '';
              if (zipInput) zipInput.value = '';
              // Limpiar tabla de revisión
              const previewZipTable = document.getElementById('preview-zip-table');
              if (previewZipTable) previewZipTable.innerHTML = '';
              // Limpiar campos automáticos
              const infoAutoFields = document.getElementById('info-auto-fields');
              if (infoAutoFields) infoAutoFields.innerHTML = '';
              // Limpiar div de preview
              const previewDiv = document.getElementById('preview-zip-cfdis');
              if (previewDiv) previewDiv.innerHTML = '';

              // Cerrar el modal XML y mostrar el de cargar CFDIs
              const modalXml = document.getElementById('modal-xml-preview');
              if (modalXml && typeof bootstrap !== 'undefined') {
                  let bsModalXml = bootstrap.Modal.getInstance(modalXml);
                  if (!bsModalXml) bsModalXml = new bootstrap.Modal(modalXml);
                  bsModalXml.hide();
                  setTimeout(showModalCargarCFDI, 400);
              } else {
                  showModalCargarCFDI();
              }
              // Recargar la tabla principal si existe la función
              if (window.recargarTablaCFDIs) window.recargarTablaCFDIs();
          });
      }

      const btnCancelarZip = document.querySelector('#modal-zip-preview .btn-secondary[data-bs-dismiss="modal"]');
      if (btnCancelarZip) {
          btnCancelarZip.addEventListener('click', function() {
              // Limpiar inputs de archivo
              const xmlInput = document.getElementById('archivo_xml');
              const zipInput = document.getElementById('archivo_zip');
              if (xmlInput) xmlInput.value = '';
              if (zipInput) zipInput.value = '';
              // Limpiar tabla de revisión
              const previewZipTable = document.getElementById('preview-zip-table');
              if (previewZipTable) previewZipTable.innerHTML = '';
              // Limpiar campos automáticos
              const infoAutoFields = document.getElementById('info-auto-fields');
              if (infoAutoFields) infoAutoFields.innerHTML = '';
              // Limpiar div de preview
              const previewDiv = document.getElementById('preview-zip-cfdis');
              if (previewDiv) previewDiv.innerHTML = '';

              // Cerrar el modal ZIP y mostrar el de cargar CFDIs
              const modalZip = document.getElementById('modal-zip-preview');
              if (modalZip && typeof bootstrap !== 'undefined') {
                  let bsModalZip = bootstrap.Modal.getInstance(modalZip);
                  if (!bsModalZip) bsModalZip = new bootstrap.Modal(modalZip);
                  bsModalZip.hide();
                  setTimeout(showModalCargarCFDI, 400);
              } else {
                  showModalCargarCFDI();
              }
              // Recargar la tabla principal si existe la función
              if (window.recargarTablaCFDIs) window.recargarTablaCFDIs();
          });
      }

      document.addEventListener('DOMContentLoaded', function() {
          const form = document.getElementById('form-cargar-cfdis');
          const zipInput = document.getElementById('archivo_zip');
          const btnCargarZip = document.getElementById('btn-cargar-zip');
          const xmlInput = document.getElementById('archivo_xml');

          // Si se selecciona XML, limpiar la vista previa ZIP
          if (xmlInput) {
              xmlInput.addEventListener('change', function() {
                  const infoAutoFields = document.getElementById('info-auto-fields');
                  if (infoAutoFields) infoAutoFields.style.display = 'none';
              });
          }

          // Evento para cargar ZIP y preparar el modal de preview (cierra el modal principal y abre preview al ocultarse)
          if (btnCargarZip && zipInput) {
              btnCargarZip.addEventListener('click', function(e) {
                  e.preventDefault();
                  if (zipInput.files.length === 0) {
                      alert('Selecciona un archivo ZIP primero.');
                      return;
                  }
                  const zipFile = zipInput.files[0];
                  window._zipFilePreview = zipFile; // Guardar ZIP en variable global
                  const fd = new FormData();
                  fd.append('archivo_zip', zipFile);
                  fetch('core/preview-zip-cfdis.php', {
                      method: 'POST',
                      body: fd
                  })
                  .then(res => res.json())
                  .then(data => {
                      // data.resultados debe contener los CFDIs
                      let html = '';
                      if (data.resultados && data.resultados.length > 0) {
                          html += `<table class="table table-bordered table-striped table-hover"><thead><tr>
                              <th></th>
                              <th>Folio</th>
                              <th>Emisor</th>
                              <th>RFC</th>
                              <th>Tipo</th>
                              <th>Impuesto</th>
                              <th>Total</th>
                          </tr></thead><tbody>`;
                          const tipoMap = { 'I': 'Ingreso', 'E': 'Egreso', 'T': 'Traslado', 'P': 'Pago', 'N': 'Nómina' };
                          data.resultados.forEach((cfdi, idx) => {
                              // Usar los campos planos del backend
                              let emisorNombre = cfdi.nombre_emisor || '';
                              let rfcEmisor = cfdi.rfc_emisor || '';
                              let tipoRaw = cfdi.tipo_comprobante || '';
                              let tipoLegible = tipoMap[tipoRaw] || tipoRaw;
                              let impuestosNum = parseFloat(cfdi.total_impuestos || 0) || 0;
                              let totalNum = parseFloat(cfdi.total || 0) || 0;
                              let impuestosFmt = `$${impuestosNum.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
                              let totalFmt = `$${totalNum.toLocaleString('es-MX', {minimumFractionDigits:2, maximumFractionDigits:2})}`;
                              html += `<tr data-file='${cfdi.filename || ''}'>
                                  <td><input type='checkbox' class='cfdi-checkbox' checked data-idx='${idx}'></td>
                                  <td>${cfdi.folio || ''}</td>
                                  <td>${emisorNombre}</td>
                                  <td>${rfcEmisor}</td>
                                  <td>${tipoLegible}</td>
                                  <td>${impuestosFmt}</td>
                                  <td>${totalFmt}</td>
                              </tr>`;
                          });
                          html += '</tbody></table>';
                      } else {
                          html = '<div class="alert alert-warning">No se encontraron CFDIs válidos en el ZIP.</div>';
                      }
                      if (data.errores && data.errores.length > 0) {
                          html += '<div class="alert alert-danger">Errores:<br>' + data.errores.join('<br>') + '</div>';
                      }
                      const container = document.getElementById('zip-preview-table-container');
                      if (container) container.innerHTML = html;

                      // Cerrar modal principal y abrir preview al ocultarse (igual que XML)
                      const modalPrincipalEl = document.getElementById('modal-cargar-cfdis');
                      function abrirPreviewZIP() {
                          const zipPreviewEl = document.getElementById('modal-zip-preview');
                          if (zipPreviewEl) {
                              const zipModal = new bootstrap.Modal(zipPreviewEl);
                              zipModal.show();
                          } else {
                              console.error('No se encontró el modal de preview ZIP en el DOM');
                          }
                          modalPrincipalEl && modalPrincipalEl.removeEventListener('hidden.bs.modal', abrirPreviewZIP);
                      }
                      let modalPrincipal = bootstrap.Modal.getInstance(modalPrincipalEl);
                      if (!modalPrincipal) modalPrincipal = new bootstrap.Modal(modalPrincipalEl);
                      modalPrincipalEl.addEventListener('hidden.bs.modal', abrirPreviewZIP);
                      modalPrincipal.hide();
                  })
                  .catch(() => {
                      const container = document.getElementById('zip-preview-table-container');
                      if (container) container.innerHTML = '<div class="alert alert-danger">Error al procesar el ZIP.</div>';
                      const modal = new bootstrap.Modal(document.getElementById('modal-zip-preview'));
                      modal.show();
                  });
              });
          }

          // Handler para botón "Importar seleccionados" dentro del modal ZIP
          const btnImportarZip = document.getElementById('btn-importar-zip');
          if (btnImportarZip) {
              btnImportarZip.addEventListener('click', function() {
                  // Selecciona todos los checkboxes marcados dentro del modal ZIP
                  const checkboxes = Array.from(document.querySelectorAll('#modal-zip-preview .cfdi-checkbox:checked'));
                  // Obtener los nombres de los archivos seleccionados
                  const seleccionados = checkboxes.map(cb => {
                      const fila = cb.closest('tr');
                      return fila ? fila.getAttribute('data-file') : '';
                  }).filter(n => n);
                  if (seleccionados.length === 0) {
                      alert('Selecciona al menos un CFDI para importar.');
                      return;
                  }
                  // Usar el ZIP guardado en variable global
                  const zipFile = window._zipFilePreview;
                  const clienteSelect = document.getElementById('cliente_zip_modal');
                  const estadoSelect = document.getElementById('estado_zip_modal');
                  if (!zipFile) {
                      alert('No se encontró el archivo ZIP original para subir. Vuelve a seleccionar el ZIP.');
                      return;
                  }
                  if (!clienteSelect || !clienteSelect.value) {
                      alert('Selecciona un cliente antes de importar.');
                      return;
                  }
                  if (!estadoSelect || !estadoSelect.value) {
                      alert('Selecciona un estado antes de importar.');
                      return;
                  }
                  const fd = new FormData();
                  fd.append('archivo_zip', zipFile);
                  fd.append('archivos_seleccionados', JSON.stringify(seleccionados));
                  fd.append('desde_preview_zip', '1');
                  fd.append('cliente_id', clienteSelect.value);
                  fd.append('estado_cfdi', estadoSelect.value);

                  btnImportarZip.disabled = true;

                  fetch('core/cargar-cfdis.php', {
                      method: 'POST',
                      body: fd
                  })
                  .then(r => r.json())
                  .then(res => {
                      if (res.success) {
                          // Usar SweetAlert2 para mostrar el mensaje y recargar la página al dar OK
                          Swal.fire({
                              icon: 'success',
                              title: res.message || 'CFDIs importados correctamente',
                              confirmButtonText: 'OK',
                              allowOutsideClick: false
                          }).then(() => {
                              document.body.style.overflow = 'auto';
                              window.location.replace(window.location.href);
                          });
                      } else {
                          mostrarNotificacion(res.error || 'Error al importar CFDIs', 'error');
                      }
                  })
                  .catch(err => {
                      console.error('Error importando desde preview ZIP:', err);
                      mostrarNotificacion('Error al importar CFDIs', 'error');
                  })
                  .finally(() => {
                      btnImportarZip.disabled = false;
                  });
              });
          }
      });
  </script>
  <script>
      // Exportar funciones al scope global si no existen
      if (typeof window.verCFDI === 'undefined') {
          window.verCFDI = function(idCfdi) {
              // Buscar el botón por data-idcfdi
              var btn = document.querySelector('.btn-info[data-idcfdi="' + idCfdi + '"]');
              if (btn) {
                  btn.click();
              } else {
                  // Alternativamente, dispara el evento global para abrir el modal
                  var event = new CustomEvent('abrirCFDI', { detail: { idCfdi: idCfdi } });
                  document.dispatchEvent(event);
              }
          };
      }

      if (typeof window.mostrarNotificacion === 'undefined') {
          window.mostrarNotificacion = function(mensaje, tipo, callback) {
              // Usa SweetAlert2 si está disponible
              if (typeof Swal !== 'undefined') {
                  Swal.fire({
                      icon: tipo === 'success' ? 'success' : (tipo === 'error' ? 'error' : 'info'),
                      title: mensaje,
                      confirmButtonText: 'OK',
                      allowOutsideClick: false
                  }).then(function() {
                      if (typeof callback === 'function') callback();
                  });
              } else {
                  alert(mensaje);
                  if (typeof callback === 'function') callback();
              }
          };
      }
  </script>
<?php endif; ?>

<!-- script de control-clientes-->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'control-clientes' || (isset($_GET['pg']) && $_GET['pg'] === 'control-clientes')): ?>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      // Obtener parámetros de la URL
      const urlParams = new URLSearchParams(window.location.search);
      const clienteId = urlParams.get('cliente_id') || 0;
      const clienteNombre = urlParams.get('cliente_nombre') || '';
      
      // Verificar que se haya seleccionado un cliente
      if (clienteId > 0) {
          // Actualizar badge con nombre del cliente
          const badgeCliente = document.getElementById('clienteSeleccionado');
          const nombreCliente = document.getElementById('cliente-nombre');
          nombreCliente.textContent = decodeURIComponent(clienteNombre);
          badgeCliente.style.display = 'block';
          
          // La sección de control ya está visible por defecto
          // Cargar datos del cliente
          cargarDocumentosCliente(clienteId);
      } else {
          // Redirigir a la lista de clientes si no hay cliente seleccionado
          alert('Debe seleccionar un cliente desde la administración de clientes.');
          window.location.href = 'panel?pg=clientes';
          return;
      }
      
      // Función para cargar documentos del cliente
      function cargarDocumentosCliente(clienteId) {
          // Esta función se puede expandir en el futuro para cargar datos específicos
          console.log('Cargando documentos para cliente ID:', clienteId);
          // Por ahora, el sistema ya carga los documentos cuando se seleccionan las pestañas
      }
      
      // Funcionalidad para los formularios de nuevo contacto y estado de cuenta
      const formNuevoContacto = document.getElementById('formNuevoContacto');
      const formNuevoEstadoCuenta = document.getElementById('formNuevoEstadoCuenta');
      
      if (formNuevoContacto) {
          formNuevoContacto.addEventListener('submit', function(e) {
              e.preventDefault();
              
              const formData = new FormData(formNuevoContacto);
              formData.append('action', 'agregar_contacto');
              formData.append('cliente_id', clienteId);
              
              fetch('core/control-clientes-actions.php', {
                  method: 'POST',
                  body: formData
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Contacto agregado correctamente');
                      formNuevoContacto.reset();
                      // Cerrar modal
                      const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoContacto'));
                      modal.hide();
                  } else {
                      alert('Error: ' + data.message);
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Error al procesar la solicitud');
              });
          });
      }
      
      if (formNuevoEstadoCuenta) {
          formNuevoEstadoCuenta.addEventListener('submit', function(e) {
              e.preventDefault();
              
              const formData = new FormData(formNuevoEstadoCuenta);
              formData.append('action', 'agregar_estado_cuenta');
              formData.append('cliente_id', clienteId);
              
              fetch('core/control-clientes-actions.php', {
                  method: 'POST',
                  body: formData
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Estado de cuenta agregado correctamente');
                      formNuevoEstadoCuenta.reset();
                      // Cerrar modal
                      const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoEstadoCuenta'));
                      modal.hide();
                      
                      // Recargar la lista de estados de cuenta
                      const contenedorEstados = document.querySelector('.archivos-existentes[data-categoria="bancarios"]');
                      if (contenedorEstados) {
                          cargarArchivosExistentes(clienteId, 'bancarios', 'estado_cuenta', contenedorEstados);
                      }
                  } else {
                      alert('Error: ' + data.message);
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Error al procesar la solicitud');
              });
          });
      }
      
      // Vista previa de logos
      const seccionLogos = document.querySelector('#identidad-corporativa');
      if (seccionLogos) {
          const logoInputs = seccionLogos.querySelectorAll('input[type="file"]');
          const logoPreview = seccionLogos.querySelector('.logo-preview');
          
          logoInputs.forEach(input => {
              input.addEventListener('change', function(e) {
                  const file = e.target.files[0];
                  if (file && file.type.startsWith('image/')) {
                      const reader = new FileReader();
                      reader.onload = function(e) {
                          logoPreview.innerHTML = `
                              <img src="${e.target.result}" 
                                  alt="Vista previa del logo" 
                                  style="max-width: 100%; max-height: 70px; object-fit: contain;">
                          `;
                      };
                      reader.readAsDataURL(file);
                  } else if (!file) {
                      // Limpiar vista previa si no hay archivo
                      logoPreview.innerHTML = '<span class="text-muted small">Vista previa</span>';
                  }
              });
          });
      }
      
      // Funcionalidad para subir documentos
      const botonesSubir = document.querySelectorAll('.btn-subir-documento');
      botonesSubir.forEach(boton => {
          boton.addEventListener('click', function() {
              const seccion = this.getAttribute('data-seccion');
              const tipoDocumento = this.getAttribute('data-tipo');
              const modo = this.dataset.modo || 'subir';
              const archivoId = this.dataset.archivoId || null;
              const inputFile = this.parentElement.parentElement.querySelector('input[type="file"]');
              
              if (!inputFile.files.length) {
                  alert('Por favor seleccione un archivo');
                  return;
              }
              
              // Confirmación para actualizar
              if (modo === 'actualizar') {
                  if (!confirm('¿Está seguro de que desea reemplazar el archivo existente?')) {
                      return;
                  }
              }
              
              const formData = new FormData();
              formData.append('action', modo === 'actualizar' ? 'actualizar_documento' : 'subir_documento');
              formData.append('cliente_id', clienteId);
              formData.append('seccion', seccion);
              formData.append('tipo_documento', tipoDocumento);
              formData.append('archivo', inputFile.files[0]);
              
              // Si es actualización, agregar ID del archivo a reemplazar
              if (modo === 'actualizar' && archivoId) {
                  formData.append('archivo_id', archivoId);
              }
              
              // Debug: verificar que clienteId no sea 0
              if (clienteId == 0) {
                  // Mostrar modal de error más informativo
                  const modalHtml = `
                      <div class="modal fade" id="modalErrorCliente" tabindex="-1">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header bg-warning text-dark">
                                      <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Cliente No Seleccionado</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                  </div>
                                  <div class="modal-body">
                                      <p><strong>Debe seleccionar un cliente antes de subir documentos.</strong></p>
                                      <p>Para acceder al control de un cliente:</p>
                                      <ol>
                                          <li>Vaya a la <strong>Administración de Clientes</strong></li>
                                          <li>Busque el cliente deseado</li>
                                          <li>Haga clic en el botón "Control de Documentos"</li>
                                      </ol>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.href='panel?pg=clientes'">
                                          <i class="bi bi-list"></i> Ir a Administración de Clientes
                                      </button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  `;
                  
                  // Agregar modal al DOM si no existe
                  if (!document.getElementById('modalErrorCliente')) {
                      document.body.insertAdjacentHTML('beforeend', modalHtml);
                  }
                  
                  // Mostrar modal
                  const modal = new bootstrap.Modal(document.getElementById('modalErrorCliente'));
                  modal.show();
                  
                  return;
              }
              
              console.log('Subiendo documento:', {
                  cliente_id: clienteId,
                  seccion: seccion,
                  tipo_documento: tipoDocumento,
                  archivo: inputFile.files[0].name
              });
              
              // Mostrar loading en el botón
              const textoOriginal = this.innerHTML;
              const textoLoading = modo === 'actualizar' ? 
                  '<i class="bi bi-arrow-repeat"></i> Actualizando...' : 
                  '<i class="bi bi-upload"></i> Subiendo...';
              this.innerHTML = textoLoading;
              this.disabled = true;
              
              fetch('core/control-clientes-actions.php', {
                  method: 'POST',
                  body: formData
              })
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const mensaje = modo === 'actualizar' ? 
                          'Documento actualizado correctamente' : 
                          'Documento subido correctamente';
                      alert(mensaje);
                      inputFile.value = ''; // Limpiar el input
                      
                      // Recargar la lista de archivos para esta tarjeta
                      const tarjeta = this.closest('.card');
                      const archivosDiv = tarjeta.querySelector('.archivos-existentes');
                      if (archivosDiv) {
                          cargarArchivosExistentes(clienteId, archivosDiv.dataset.categoria, archivosDiv.dataset.tipo, archivosDiv);
                      }
                  } else {
                      alert('Error: ' + data.message);
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert('Error al subir el documento');
              })
              .finally(() => {
                  // Restaurar botón
                  this.innerHTML = textoOriginal;
                  this.disabled = false;
              });
          });
      });
      
      // Función para cargar archivos existentes
      function cargarArchivosExistentes(clienteId, categoria, tipoDocumento, contenedor) {
          if (clienteId == 0) return;
          
          const url = `core/listar-archivos-cliente.php?cliente_id=${clienteId}&categoria=${categoria}&tipo_documento=${tipoDocumento}`;
          
          fetch(url)
              .then(response => response.json())
              .then(data => {
                  if (data.success) {
                      mostrarArchivos(data.archivos, contenedor);
                  } else {
                      contenedor.innerHTML = '<div class="text-center text-muted small">Error al cargar archivos</div>';
                  }
              })
              .catch(error => {
                  console.error('Error al cargar archivos:', error);
                  contenedor.innerHTML = '<div class="text-center text-muted small">Error al cargar archivos</div>';
              });
      }
      
      // Función para mostrar archivos en la interfaz
      function mostrarArchivos(archivos, contenedor) {
          // Actualizar el estado del botón de subir según si hay archivos
          const tarjeta = contenedor.closest('.card');
          const botonSubir = tarjeta.querySelector('.btn-subir-documento');
          
          if (archivos.length === 0) {
              contenedor.innerHTML = '<div class="text-center text-muted small"><i class="bi bi-file-x"></i> Sin archivos</div>';
              
              // Cambiar botón a "Subir"
              if (botonSubir) {
                  botonSubir.innerHTML = '<i class="bi bi-upload"></i> Subir';
                  botonSubir.dataset.modo = 'subir';
                  botonSubir.classList.remove('btn-warning');
                  botonSubir.classList.add('btn-success');
              }
              return;
          }
          
          // Solo mostrar el primer archivo (documento único)
          const archivo = archivos[0];
          let html = `
              <div class="border rounded p-2 bg-light">
                  <div class="d-flex justify-content-between align-items-start flex-wrap">
                      <div class="flex-grow-1 me-2 archivo-info">
                          <h6 class="mb-1 small archivo-nombre">${archivo.nombre_original}</h6>
                          <small class="text-muted archivo-meta">
                              <i class="bi bi-calendar3"></i> ${archivo.fecha_formato} | 
                              <i class="bi bi-hdd"></i> ${archivo.tamaño_formato}
                          </small>
                      </div>
                      <div class="d-flex gap-1 flex-shrink-0">
                          <button class="btn btn-outline-primary btn-sm" onclick="descargarArchivo('${archivo.ruta_archivo}', '${archivo.nombre_original}')" title="Descargar">
                              <i class="bi bi-download"></i>
                          </button>
                          <button class="btn btn-outline-info btn-sm" onclick="verArchivo('${archivo.ruta_archivo}', '${archivo.nombre_original}')" title="Ver">
                              <i class="bi bi-eye"></i>
                          </button>
                      </div>
                  </div>
              </div>
          `;
          
          // Mostrar mensaje si hay más archivos (para transición)
          if (archivos.length > 1) {
              html += `<small class="text-muted"><i class="bi bi-info-circle"></i> Se muestran documentos únicos por tipo</small>`;
          }
          
          contenedor.innerHTML = html;
          
          // Cambiar botón a "Actualizar"
          if (botonSubir) {
              botonSubir.innerHTML = '<i class="bi bi-arrow-repeat"></i> Actualizar';
              botonSubir.dataset.modo = 'actualizar';
              botonSubir.dataset.archivoId = archivo.id;
              botonSubir.classList.remove('btn-success');
              botonSubir.classList.add('btn-warning');
          }
      }
      
      // Cargar archivos existentes al inicializar la página
      if (clienteId > 0) {
          document.querySelectorAll('.archivos-existentes').forEach(contenedor => {
              cargarArchivosExistentes(clienteId, contenedor.dataset.categoria, contenedor.dataset.tipo, contenedor);
          });
      }
  });

  // Funciones globales para los botones de archivo (fuera del DOMContentLoaded)
  function descargarArchivo(rutaArchivo, nombreOriginal) {
      console.log('Descargando archivo:', rutaArchivo);
      // Crear enlace de descarga forzada
      const downloadUrl = `core/arch-preview.php?carpeta=${encodeURIComponent(extraerCarpetaDeRuta(rutaArchivo))}&file=${encodeURIComponent(extraerArchivoDeRuta(rutaArchivo))}&download=1`;
      window.open(downloadUrl, '_blank');
  }

  function verArchivo(rutaArchivo, nombreOriginal) {
      console.log('Viendo archivo:', rutaArchivo);
      const carpeta = extraerCarpetaDeRuta(rutaArchivo);
      const archivo = extraerArchivoDeRuta(rutaArchivo);
      
      if (carpeta && archivo) {
          const previewUrl = `core/arch-preview.php?carpeta=${encodeURIComponent(carpeta)}&file=${encodeURIComponent(archivo)}`;
          window.open(previewUrl, '_blank');
      } else {
          console.error('Error al procesar la ruta del archivo:', rutaArchivo);
          alert('Error al abrir el archivo');
      }
  }

  function extraerCarpetaDeRuta(rutaCompleta) {
      // Ejemplo: /uploads/clientes/2/documentos/archivo.pdf -> clientes/2/documentos
      const ruta = rutaCompleta.startsWith('/') ? rutaCompleta.substring(1) : rutaCompleta;
      const parts = ruta.split('/');
      
      if (parts.length >= 4 && parts[0] === 'uploads') {
          // Remover 'uploads' al inicio y el nombre del archivo al final
          return parts.slice(1, -1).join('/');
      }
      return null;
  }

  function extraerArchivoDeRuta(rutaCompleta) {
      return rutaCompleta.split('/').pop();
  }
  </script>

  <style>
  /* Archivos styling */
  .archivos-existentes .border {
    transition: box-shadow 0.2s ease;
  }

  .archivos-existentes .border:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  /* Archivo nombre responsivo */
  .archivo-info {
    min-width: 0; /* Permite que el contenedor se contraiga */
  }

  .archivo-nombre {
    word-wrap: break-word;
    word-break: break-all;
    overflow-wrap: break-word;
    line-height: 1.3;
    max-width: 100%;
  }

  .archivo-meta {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    /* Mejoras para archivos en móvil */
    .archivos-existentes .d-flex {
      flex-wrap: wrap;
    }
    
    .archivos-existentes .flex-grow-1 {
      width: 100%;
      margin-bottom: 8px;
    }
    
    .archivos-existentes .d-flex.gap-1 {
      width: 100%;
      justify-content: center;
    }
    
    /* Nombres de archivo en tablet */
    .archivo-nombre {
      font-size: 0.8rem;
      line-height: 1.2;
    }
    
    .archivo-meta {
      font-size: 0.75rem;
      white-space: normal; /* Permite wrap en tablets */
    }
  }

  @media (max-width: 576px) {
    /* En pantallas muy pequeñas, hacer botones más grandes */
    .archivos-existentes .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }
    
    /* Reducir padding en las cajas de archivos */
    .archivos-existentes .border {
      padding: 0.75rem !important;
    }
    
    /* Ajustar columnas en móvil */
    .col-md-4 {
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }
    
    /* Nombres de archivo en móvil */
    .archivo-nombre {
      font-size: 0.75rem;
      line-height: 1.1;
      margin-bottom: 0.25rem !important;
    }
    
    .archivo-meta {
      font-size: 0.7rem;
      line-height: 1.2;
    }
    
    /* Hacer que el contenido del archivo sea más compacto */
    .archivo-info {
      padding-right: 0.5rem;
    }
  }

  @media (max-width: 400px) {
    /* Para pantallas extra pequeñas */
    .archivo-nombre {
      font-size: 0.7rem;
      max-height: 2.2rem; /* Limitar a 2 líneas aprox */
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }
    
    .archivo-meta {
      font-size: 0.65rem;
    }
    
    /* Hacer las tarjetas aún más compactas */
    .card-body {
      padding: 0.75rem !important;
    }
  }
  </style>
<?php endif; ?>

<!-- script de mis-documentos-->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'mis-documentos' || (isset($_GET['pg']) && $_GET['pg'] === 'mis-documentos')): ?>

  <script>
  // Configuración de tipos de documentos con sus iconos y colores
  const tiposDocumentos = {
    fiscales: {
      'firma_electronica': { icono: 'bi-pen', color: 'info', descripcion: 'Certificados digitales y firmas electrónicas' },
      'constancia_situacion_fiscal': { icono: 'bi-file-earmark-text', color: 'success', descripcion: 'Constancia actualizada del SAT' },
      'buzon_tributario': { icono: 'bi-mailbox', color: 'warning', descripcion: 'Documentos del buzón tributario' },
      'opinion_cumplimiento': { icono: 'bi-check-circle', color: 'secondary', descripcion: 'Opinión de cumplimiento fiscal' },
      'infonavit': { icono: 'bi-house', color: 'primary', descripcion: 'Documentos del INFONAVIT' },
      'imss': { icono: 'bi-heart-pulse', color: 'danger', descripcion: 'Documentos del IMSS' }
    },
    legales: {
      'identificaciones': { icono: 'bi-person-badge', color: 'info', descripcion: 'Identificaciones de representantes legales' },
      'actas_constitutivas': { icono: 'bi-file-earmark-text', color: 'success', descripcion: 'Acta constitutiva y modificaciones' },
      'caratulas': { icono: 'bi-folder', color: 'warning', descripcion: 'Carátulas de expedientes' },
      'poderes_notariales': { icono: 'bi-file-earmark-person', color: 'primary', descripcion: 'Poderes notariales' }
    }
  };

  // Cargar datos del cliente al cargar la página
  document.addEventListener('DOMContentLoaded', function() {
      cargarDatosCliente();
  });

  /**
   * Carga los datos del cliente y documentos
   */
  async function cargarDatosCliente() {
      try {
          // Primero hacer debug de la sesión
          console.log('Iniciando carga de datos del cliente...');
          
          const debugResponse = await fetch('core/debug-sesion-cliente.php');
          const debugData = await debugResponse.json();
          console.log('Debug de sesión:', debugData);
          
          // Ahora intentar cargar los documentos
          const response = await fetch('core/listar-documentos-cliente.php');
          console.log('Response status:', response.status);
          
          if (!response.ok) {
              const errorText = await response.text();
              console.error('Error response:', errorText);
              mostrarError(`Error ${response.status}: ${errorText}`);
              return;
          }
          
          const data = await response.json();
          console.log('Datos recibidos:', data);
          
          if (data.success) {
              // Actualizar información del cliente en el header
              document.getElementById('cliente-nombre').textContent = data.cliente.cliente.nombre || 'Cliente';
              document.getElementById('cliente-rfc').textContent = data.cliente.cliente.rfc || 'Sin RFC';
              
              // Cargar documentos por categoría
              cargarDocumentosFiscales(data.documentos.fiscales);
              cargarDocumentosLegales(data.documentos.legales);
              cargarEstadosCuenta(data.documentos.bancarios);
              cargarRecursosCorporativos(data.documentos.corporativos);
          } else {
              console.error('Error en respuesta:', data.error);
              mostrarError('Error: ' + (data.error || 'Respuesta inválida'));
          }
      } catch (error) {
          console.error('Error de conexión:', error);
          mostrarError('Error de conexión: ' + error.message);
      }
  }

  /**
   * Carga documentos fiscales
   */
  function cargarDocumentosFiscales(documentos) {
      console.log('Cargando documentos fiscales:', documentos);
      const container = document.getElementById('documentos-fiscales');
      container.innerHTML = '';
      
      if (!documentos || documentos.length === 0) {
          container.innerHTML = '<div class="col-12 text-center py-4"><p class="text-muted">No hay documentos fiscales disponibles.</p></div>';
          return;
      }
      
      documentos.forEach((doc, index) => {
          console.log(`Documento ${index}:`, doc);
          console.log('Archivos del documento:', doc.archivos);
          
          const tipoConfig = tiposDocumentos.fiscales[doc.tipo] || { 
              icono: 'bi-file-earmark', 
              color: 'secondary', 
              descripcion: 'Documento' 
          };
          
          const tipoNombre = formatearNombreTipo(doc.tipo);
          const fechaFormateada = formatearFecha(doc.ultima_actualizacion);
          
          const cardHtml = `
              <div class="col-md-4 mb-3">
                  <div class="card h-100">
                      <div class="card-header bg-${tipoConfig.color} text-white">
                          <h6 class="mb-0"><i class="${tipoConfig.icono}"></i> ${tipoNombre}</h6>
                      </div>
                      <div class="card-body p-3">
                          <p class="text-muted small">${tipoConfig.descripcion}</p>
                          <div class="d-flex justify-content-between align-items-center mb-3">
                              <span class="badge bg-${doc.cantidad > 0 ? 'success' : 'secondary'}">
                                  ${doc.cantidad} archivo${doc.cantidad !== 1 ? 's' : ''}
                              </span>
                              <small class="text-muted">${fechaFormateada}</small>
                          </div>
                          <div class="archivos-contenido">
                              ${crearBotonDescarga(doc)}
                          </div>
                      </div>
                  </div>
              </div>
          `;
          
          container.innerHTML += cardHtml;
      });
  }

  /**
   * Carga documentos legales
   */
  function cargarDocumentosLegales(documentos) {
      const container = document.getElementById('documentos-legales');
      container.innerHTML = '';
      
      if (!documentos || documentos.length === 0) {
          container.innerHTML = '<div class="col-12 text-center py-4"><p class="text-muted">No hay documentos legales disponibles.</p></div>';
          return;
      }
      
      documentos.forEach(doc => {
          const tipoConfig = tiposDocumentos.legales[doc.tipo] || { 
              icono: 'bi-file-earmark', 
              color: 'secondary', 
              descripcion: 'Documento legal' 
          };
          
          const tipoNombre = formatearNombreTipo(doc.tipo);
          const fechaFormateada = formatearFecha(doc.ultima_actualizacion);
          
          const cardHtml = `
              <div class="col-md-4 mb-3">
                  <div class="card h-100">
                      <div class="card-header bg-${tipoConfig.color} text-white">
                          <h6 class="mb-0"><i class="${tipoConfig.icono}"></i> ${tipoNombre}</h6>
                      </div>
                      <div class="card-body p-3">
                          <p class="text-muted small">${tipoConfig.descripcion}</p>
                          <div class="d-flex justify-content-between align-items-center mb-3">
                              <span class="badge bg-${doc.cantidad > 0 ? 'success' : 'secondary'}">
                                  ${doc.cantidad} archivo${doc.cantidad !== 1 ? 's' : ''}
                              </span>
                              <small class="text-muted">${fechaFormateada}</small>
                          </div>
                          <div class="archivos-contenido">
                              ${crearBotonDescarga(doc)}
                          </div>
                      </div>
                  </div>
              </div>
          `;
          
          container.innerHTML += cardHtml;
      });
  }

  /**
   * Carga estados de cuenta bancarios
   */
  function cargarEstadosCuenta(estados) {
      const tbody = document.getElementById('estados-cuenta-tbody');
      tbody.innerHTML = '';
      
      if (!estados || estados.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><p class="text-muted mb-0">No hay estados de cuenta disponibles.</p></td></tr>';
          return;
      }
      
      estados.forEach(estado => {
          const fechaFormateada = formatearFecha(estado.fecha_subida);
          const cuentaOculta = estado.numero_cuenta ? `****${estado.numero_cuenta.slice(-4)}` : 'Sin número';
          
          const tr = document.createElement('tr');
          tr.innerHTML = `
              <td>
                  <div class="d-flex align-items-center">
                      <i class="bi bi-bank text-primary me-2"></i>
                      <strong>${estado.banco}</strong>
                  </div>
              </td>
              <td><code>${cuentaOculta}</code></td>
              <td><span class="badge bg-info">${estado.periodo}</span></td>
              <td><small class="text-muted">${fechaFormateada}</small></td>
              <td>
                  <button class="btn btn-primary btn-sm" onclick="descargarArchivo('${estado.ruta_archivo}')">
                      <i class="bi bi-download"></i> Descargar
                  </button>
              </td>
          `;
          
          tbody.appendChild(tr);
      });
  }

  /**
   * Carga recursos corporativos
   */
  function cargarRecursosCorporativos(recursos) {
      const container = document.getElementById('recursos-corporativos');
      container.innerHTML = '';
      
      if (!recursos || recursos.length === 0) {
          container.innerHTML = '<div class="col-12 text-center py-4"><p class="text-muted">No hay recursos corporativos disponibles.</p></div>';
          return;
      }
      
      // Separar logos y otros recursos
      const logos = recursos.filter(r => r.tipo === 'principal' || r.tipo === 'alternativo');
      const otrosRecursos = recursos.filter(r => r.tipo !== 'principal' && r.tipo !== 'alternativo');
      
      let htmlContent = '';
      
      // Sección de logos
      if (logos.length > 0) {
          htmlContent += `
              <div class="col-md-12 mb-3">
                  <div class="card">
                      <div class="card-header bg-info text-white">
                          <h5 class="mb-0"><i class="bi bi-image"></i> Logo de la Empresa</h5>
                      </div>
                      <div class="card-body">
                          <div class="row">
          `;
          
          logos.forEach(logo => {
              const tipoLabel = logo.tipo === 'principal' ? 'Logo Principal' : 'Logo Alternativo';
              htmlContent += `
                  <div class="col-md-6 mb-3">
                      <div class="border rounded p-3 bg-light text-center">
                          <div class="logo-preview bg-white border-2 border-dashed rounded d-flex align-items-center justify-content-center" style="height: 150px;">
                              <img src="${logo.ruta_archivo}" alt="${tipoLabel}" style="max-width: 100%; max-height: 140px; object-fit: contain;">
                          </div>
                          <h6 class="mt-2 mb-2">${tipoLabel}</h6>
                          <small class="text-muted">${logo.nombre_archivo}</small>
                          <div class="d-flex gap-2 mt-2 justify-content-center">
                              <button class="btn btn-primary btn-sm" onclick="descargarArchivo('${logo.ruta_archivo}')">
                                  <i class="bi bi-download"></i> Descargar
                              </button>
                              <button class="btn btn-info btn-sm" onclick="previsualizarArchivo('${logo.ruta_archivo}')">
                                  <i class="bi bi-eye"></i> Ver
                              </button>
                          </div>
                      </div>
                  </div>
              `;
          });
          
          htmlContent += `
                          </div>
                      </div>
                  </div>
              </div>
          `;
      }
      
      container.innerHTML = htmlContent;
  }

  /**
   * Crea el botón de descarga según los archivos disponibles
   */
  function crearBotonDescarga(doc) {
      if (doc.cantidad === 0) {
          return `
              <div class="text-center text-muted small py-2">
                  <i class="bi bi-file-x"></i> Sin documentos disponibles
              </div>
          `;
      } else if (doc.cantidad === 1) {
          const archivo = doc.archivos[0];
          return `
              <div class="border rounded p-2 bg-light">
                  <div class="d-flex justify-content-between align-items-start flex-wrap">
                      <div class="flex-grow-1 me-2 archivo-info">
                          <h6 class="mb-1 small archivo-nombre">${archivo.nombre}</h6>
                          <small class="text-muted archivo-meta">
                              <i class="bi bi-calendar3"></i> ${formatearFecha(archivo.fecha_subida)} | 
                              <i class="bi bi-hdd"></i> ${archivo.tamaño_formato}
                          </small>
                      </div>
                      <div class="d-flex gap-1 flex-shrink-0">
                          <button class="btn btn-outline-primary btn-sm" onclick="descargarArchivo('${archivo.ruta}')" title="Descargar">
                              <i class="bi bi-download"></i>
                          </button>
                          <button class="btn btn-outline-info btn-sm" onclick="previsualizarArchivo('${archivo.ruta}')" title="Ver">
                              <i class="bi bi-eye"></i>
                          </button>
                      </div>
                  </div>
              </div>
          `;
      } else {
          // Para múltiples archivos, mostrar lista expandible
          let html = '<div class="archivos-multiples">';
          
          doc.archivos.forEach((archivo, index) => {
              html += `
                  <div class="border rounded p-2 bg-light mb-2">
                      <div class="d-flex justify-content-between align-items-start flex-wrap">
                          <div class="flex-grow-1 me-2 archivo-info">
                              <h6 class="mb-1 small archivo-nombre">${archivo.nombre}</h6>
                              <small class="text-muted archivo-meta">
                                  <i class="bi bi-calendar3"></i> ${formatearFecha(archivo.fecha_subida)} | 
                                  <i class="bi bi-hdd"></i> ${archivo.tamaño_formato}
                              </small>
                          </div>
                          <div class="d-flex gap-1 flex-shrink-0">
                              <button class="btn btn-outline-primary btn-sm" onclick="descargarArchivo('${archivo.ruta}')" title="Descargar">
                                  <i class="bi bi-download"></i>
                              </button>
                              <button class="btn btn-outline-info btn-sm" onclick="previsualizarArchivo('${archivo.ruta}')" title="Ver">
                                  <i class="bi bi-eye"></i>
                              </button>
                          </div>
                      </div>
                  </div>
              `;
          });
          
          html += '</div>';
          return html;
      }
  }

  /**
   * Descarga un archivo mediante el sistema seguro
   */
  async function descargarArchivo(rutaArchivo) {
      try {
          const formData = new FormData();
          formData.append('ruta_archivo', rutaArchivo);
          
          const response = await fetch('core/descargar-archivo-cliente.php', {
              method: 'POST',
              body: formData
          });
          
          if (response.ok) {
              const blob = await response.blob();
              const url = window.URL.createObjectURL(blob);
              const a = document.createElement('a');
              a.style.display = 'none';
              a.href = url;
              
              // Obtener el nombre del archivo del header Content-Disposition si está disponible
              const contentDisposition = response.headers.get('Content-Disposition');
              let filename = 'documento.pdf';
              if (contentDisposition) {
                  const matches = /filename="?([^"]+)"?/.exec(contentDisposition);
                  if (matches) {
                      filename = matches[1];
                  }
              }
              
              a.download = filename;
              document.body.appendChild(a);
              a.click();
              window.URL.revokeObjectURL(url);
              document.body.removeChild(a);
          } else {
              const errorText = await response.text();
              mostrarError('Error al descargar el archivo: ' + errorText);
          }
      } catch (error) {
          console.error('Error en descarga:', error);
          mostrarError('Error al descargar el archivo. Intenta nuevamente.');
      }
  }

  /**
   * Previsualiza un archivo en una nueva ventana
   */
  function previsualizarArchivo(rutaArchivo) {
      console.log('Previsualizando archivo:', rutaArchivo);
      const url = `core/arch-preview.php?ruta=${encodeURIComponent(rutaArchivo)}`;
      console.log('URL generada:', url);
      window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
  }

  /**
   * Función placeholder para previsualizar múltiples archivos
   */
  function previsualizarMultiples(tipo) {
      mostrarInfo('Función de previsualización múltiple en desarrollo');
  }

  /**
   * Utilitarios
   */
  function formatearNombreTipo(tipo) {
      return tipo.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  }

  function formatearFecha(fecha) {
      if (!fecha) return '-';
      const date = new Date(fecha);
      return date.toLocaleDateString('es-ES', { 
          day: '2-digit', 
          month: '2-digit', 
          year: 'numeric' 
      });
  }

  function obtenerIconoRecurso(tipo) {
      const iconos = {
          'manual_identidad': 'bi-file-pdf text-danger',
          'paleta_colores': 'bi-palette text-success',
          'plantillas': 'bi-file-text text-info'
      };
      return iconos[tipo] || 'bi-file-earmark text-secondary';
  }

  function mostrarError(mensaje) {
      // Crear un toast o alert para mostrar errores
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed';
      alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
      alertDiv.innerHTML = `
          ${mensaje}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      document.body.appendChild(alertDiv);
      
      // Remover automáticamente después de 5 segundos
      setTimeout(() => {
          if (alertDiv.parentNode) {
              alertDiv.parentNode.removeChild(alertDiv);
          }
      }, 5000);
  }

  function mostrarInfo(mensaje) {
      // Crear un toast o alert para mostrar información
      const alertDiv = document.createElement('div');
      alertDiv.className = 'alert alert-info alert-dismissible fade show position-fixed';
      alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
      alertDiv.innerHTML = `
          <i class="bi bi-info-circle me-2"></i>${mensaje}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      document.body.appendChild(alertDiv);
      
      // Remover automáticamente después de 3 segundos
      setTimeout(() => {
          if (alertDiv.parentNode) {
              alertDiv.parentNode.removeChild(alertDiv);
          }
      }, 3000);
  }
  </script>

  <style>
  /* Eliminar estilos del header gradiente y usar estilo simple como control-clientes */
  .card-header {
    font-weight: 600;
  }

  .card {
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }

  /* Buttons */
  .btn {
    transition: all 0.3s ease;
    border-radius: 6px;
  }

  .btn:hover {
    transform: translateY(-1px);
  }

  /* Tables */
  .table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9rem;
    background-color: #f8f9fa;
  }

  /* Badge improvements */
  .badge {
    font-weight: 500;
    padding: 0.4rem 0.6rem;
  }

  /* Logo placeholder */
  .logo-preview {
    border-style: dashed !important;
  }

  /* Navigation pills - igual que control-clientes */
  .nav-pills .nav-link {
    border-radius: 8px;
    margin: 0 2px;
    padding: 10px 16px;
    transition: all 0.3s ease;
  }

  .nav-pills .nav-link.active {
    background-color: #0d6efd;
  }

  .nav-pills .nav-link:hover:not(.active) {
    background-color: rgba(0, 123, 255, 0.1);
  }

  /* Container adjustments */
  .container-fluid {
    max-width: 100%;
  }

  /* Archivos styling */
  .archivos-contenido .border {
    transition: box-shadow 0.2s ease;
  }

  .archivos-contenido .border:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  /* Archivo nombre responsivo */
  .archivo-info {
    min-width: 0; /* Permite que el contenedor se contraiga */
  }

  .archivo-nombre {
    word-wrap: break-word;
    word-break: break-all;
    overflow-wrap: break-word;
    line-height: 1.3;
    max-width: 100%;
  }

  .archivo-meta {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .btn-group {
      flex-direction: column;
    }
    
    .btn-group .btn {
      border-radius: 6px !important;
      margin-bottom: 2px;
    }
    
    /* Mejoras para archivos en móvil */
    .archivos-contenido .d-flex {
      flex-wrap: wrap;
    }
    
    .archivos-contenido .flex-grow-1 {
      width: 100%;
      margin-bottom: 8px;
    }
    
    .archivos-contenido .d-flex.gap-1 {
      width: 100%;
      justify-content: center;
    }
    
    /* Nombres de archivo en tablet */
    .archivo-nombre {
      font-size: 0.8rem;
      line-height: 1.2;
    }
    
    .archivo-meta {
      font-size: 0.75rem;
      white-space: normal; /* Permite wrap en tablets */
    }
  }

  @media (max-width: 576px) {
    /* En pantallas muy pequeñas, hacer botones más grandes */
    .archivos-contenido .btn-sm {
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
    }
    
    /* Reducir padding en las cajas de archivos */
    .archivos-contenido .border {
      padding: 0.75rem !important;
    }
    
    /* Ajustar columnas en móvil */
    .col-md-4 {
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }
    
    /* Nombres de archivo en móvil */
    .archivo-nombre {
      font-size: 0.75rem;
      line-height: 1.1;
      margin-bottom: 0.25rem !important;
    }
    
    .archivo-meta {
      font-size: 0.7rem;
      line-height: 1.2;
    }
    
    /* Hacer que el contenido del archivo sea más compacto */
    .archivo-info {
      padding-right: 0.5rem;
    }
  }

  @media (max-width: 400px) {
    /* Para pantallas extra pequeñas */
    .archivo-nombre {
      font-size: 0.7rem;
      max-height: 2.2rem; /* Limitar a 2 líneas aprox */
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }
    
    .archivo-meta {
      font-size: 0.65rem;
    }
    
    /* Hacer las tarjetas aún más compactas */
    .card-body {
      padding: 0.75rem !important;
    }
  }
  </style>
<?php endif; ?>


<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'ver-expediente' || (isset($_GET['pg']) && $_GET['pg'] === 'ver-expediente')): ?>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      // --- Auto-llenar nombre del archivo cuando se selecciona ---
      var archivoInput = document.getElementById('archivo');
      var nombreArchivoInput = document.getElementById('nombre_archivo');
      
      if (archivoInput && nombreArchivoInput) {
          archivoInput.addEventListener('change', function(e) {
              if (e.target.files && e.target.files.length > 0) {
                  var fileName = e.target.files[0].name;
                  var fileInfo = {
                      name: fileName,
                      size: e.target.files[0].size,
                      type: e.target.files[0].type
                  };
                  
                  // Extraer solo el nombre sin extensión para que el usuario pueda personalizarlo
                  var nameWithoutExt = fileName.substring(0, fileName.lastIndexOf('.')) || fileName;
                  nombreArchivoInput.value = nameWithoutExt;
                  
                  console.log('Archivo seleccionado:', fileInfo);
                  console.log('Nombre sin extensión para personalizar:', nameWithoutExt);
                  console.log('La extensión original se preservará automáticamente');
              }
          });
      }

      // --- Manejar subida de archivo con AJAX y mostrar mensaje con SweetAlert ---
      var form = document.getElementById('formSubirArchivo');
      if (form) {
          form.addEventListener('submit', function(e) {
              e.preventDefault();
              var formData = new FormData(form);
              var btn = form.querySelector('button[type="submit"]');
              btn.disabled = true;
              fetch('core/subir-archivo-expediente.php', {
                  method: 'POST',
                  body: formData
              })
              .then(res => res.json())
              .then(data => {
                  btn.disabled = false;
                  console.log('=== RESPUESTA DEL SERVIDOR AL SUBIR ARCHIVO ===');
                  console.log('Datos completos:', data);
                  if (data.debug) {
                      console.log('Debug información:', data.debug);
                  }
                  
                  var modal = document.getElementById('modalSubirArchivo');
                  if (data.ok) {
                      Swal.fire({
                          icon: 'success',
                          title: '¡Éxito!',
                          html: data.msg,
                          confirmButtonColor: '#3085d6',
                          confirmButtonText: 'Aceptar'
                      }).then(() => {
                          form.reset();
                          if (window.bootstrap && modal) {
                              var bsModal = window.bootstrap.Modal.getOrCreateInstance(modal);
                              bsModal.hide();
                          } else {
                              modal.classList.remove('show');
                              modal.style.display = 'none';
                          }
                          // Esperar un poco antes de recargar para asegurar que el servidor procesó el archivo
                          setTimeout(() => {
                              console.log('Recargando archivos del expediente después de subir archivo...');
                              cargarArchivosExpediente();
                          }, 500);
                      });
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          html: data.msg,
                          confirmButtonColor: '#d33',
                          confirmButtonText: 'Cerrar'
                      });
                  }
              })
              .catch((e) => {
                  btn.disabled = false;
                  Swal.fire({
                      icon: 'error',
                      title: 'Error de red',
                      text: 'No se pudo conectar con el servidor. ' + e
                  });
              });
          });
      }
      // --- Mostrar archivos por categoría en el sidebar ---
      function cargarArchivosExpediente() {
          var idExpediente = <?php echo json_encode($expedienteDatos['id_expediente']); ?>;
          
          fetch('core/listar-archivos-expediente.php?id_expediente=' + idExpediente)
              .then(res => res.json())
              .then(data => {
                  console.log('Respuesta completa del servidor:', data);
                  console.log('Archivos debug:', data.debug_archivos);
                  console.log('Categorías recibidas:', data.categorias);
                  console.log('Carpeta expediente:', data.carpeta_expediente);
                  console.log('Archivos existentes:', data.archivos_existentes);
                  console.log('Archivos faltantes:', data.archivos_faltantes);
                  
                  if (!data.success) {
                      console.error('Error al cargar archivos:', data.msg);
                      return;
                  }
                  
                  // Limpiar contadores
                  ['caratula', 'acuerdo', 'promocion', 'constancia', 'juicio', 'audiencia'].forEach(function(cat) {
                      var countEl = document.getElementById('count-' + cat);
                      var listEl = document.getElementById('list-' + cat);
                      if (countEl) countEl.textContent = '0';
                      if (listEl) listEl.innerHTML = '';
                  });
                  
                  // Actualizar contadores y listas
                  if (data.counts) {
                      console.log('Contadores recibidos:', data.counts);
                      Object.keys(data.counts).forEach(function(cat) {
                          var countEl = document.getElementById('count-' + cat);
                          if (countEl) {
                              console.log('Actualizando contador ' + cat + ' a ' + data.counts[cat]);
                              countEl.textContent = data.counts[cat];
                          } else {
                              console.log('No se encontró elemento count-' + cat);
                          }
                      });
                  } else {
                      console.log('No se recibieron contadores');
                  }
                  
                  if (data.categorias) {
                      Object.keys(data.categorias).forEach(function(cat) {
                          var ul = document.getElementById('list-' + cat);
                          if (ul && data.categorias[cat].length > 0) {
                              ul.innerHTML = '';
                              
                              data.categorias[cat].forEach(function(doc) {
                                  var li = document.createElement('li');
                                  li.className = 'list-group-item bg-dark text-white p-2 d-flex align-items-center justify-content-between';
                                  // Contenedor de info y botones
                                  var group = document.createElement('div');
                                  group.className = 'd-flex align-items-center flex-grow-1';
                                  // Link archivo
                                  var link = document.createElement('a');
                                  link.href = '#';
                                  link.className = 'text-info text-decoration-none d-block text-truncate flex-grow-1';
                                  link.title = doc.documento;
                                  link.style.wordWrap = 'break-word';
                                  link.style.whiteSpace = 'nowrap';
                                  link.style.overflow = 'hidden';
                                  link.style.textOverflow = 'ellipsis';
                                  link.innerHTML = '<i class="bi bi-file-earmark-arrow-down me-1"></i> ' + doc.documento;
                                  link.addEventListener('click', function(e) {
                                      e.preventDefault();
                                      // ... preview code igual que antes ...
                                      var visor = document.getElementById('visor-archivo-expediente');
                                      visor.innerHTML = '';
                                      var nombreDiv = document.createElement('div');
                                      nombreDiv.className = 'fw-bold mb-3 text-center';
                                      nombreDiv.textContent = doc.documento;
                                      visor.appendChild(nombreDiv);
                                      var extension = '';
                                      if (doc.nombre_archivo) {
                                          var parts = doc.nombre_archivo.toLowerCase().split('.');
                                          extension = parts.length > 1 ? parts.pop() : '';
                                      }
                                      if (!extension) extension = 'pdf';
                                      // ... preview switch igual que antes ...
                                      if (['pdf'].includes(extension)) {
                                          var iframe = document.createElement('iframe');
                                          iframe.src = doc.ruta_fisica;
                                          iframe.style = 'width:100%;height:700px;border:1px solid #ccc;border-radius:4px;';
                                          visor.appendChild(iframe);
                                      } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'].includes(extension)) {
                                          var img = document.createElement('img');
                                          img.src = doc.ruta_fisica;
                                          img.style = 'max-width:100%;max-height:700px;border:1px solid #ccc;border-radius:4px;object-fit:contain;';
                                          visor.appendChild(img);
                                      } else if (['txt', 'csv', 'log', 'md', 'json', 'xml', 'html', 'css', 'js', 'php', 'py', 'sql'].includes(extension)) {
                                          var pre = document.createElement('pre');
                                          pre.style = 'background:#f8f9fa;padding:20px;border:1px solid #ccc;border-radius:4px;max-height:700px;overflow:auto;font-family:monospace;';
                                          pre.textContent = 'Cargando contenido...';
                                          visor.appendChild(pre);
                                          fetch(doc.ruta_fisica)
                                              .then(response => response.text())
                                              .then(text => { pre.textContent = text; })
                                              .catch(error => { pre.textContent = 'Error al cargar el archivo de texto'; });
                                      } else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(extension)) {
                                          var iframe = document.createElement('iframe');
                                          iframe.src = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(window.location.origin + '/' + doc.ruta_fisica)}`;
                                          iframe.style = 'width:100%;height:700px;border:1px solid #ccc;border-radius:4px;';
                                          visor.appendChild(iframe);
                                      } else if (['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'].includes(extension)) {
                                          var video = document.createElement('video');
                                          video.src = doc.ruta_fisica;
                                          video.controls = true;
                                          video.style = 'width:100%;max-height:700px;border:1px solid #ccc;border-radius:4px;';
                                          visor.appendChild(video);
                                      } else if (['mp3', 'wav', 'ogg', 'aac', 'flac', 'm4a'].includes(extension)) {
                                          var audio = document.createElement('audio');
                                          audio.src = doc.ruta_fisica;
                                          audio.controls = true;
                                          audio.style = 'width:100%;margin:20px 0;';
                                          visor.appendChild(audio);
                                      } else {
                                          var iframe = document.createElement('iframe');
                                          iframe.src = doc.ruta_fisica;
                                          iframe.style = 'width:100%;height:700px;border:1px solid #ccc;border-radius:4px;';
                                          visor.appendChild(iframe);
                                      }
                                  });
                                  group.appendChild(link);
                                  // Botón mover a papelera
                                  var btnPapelera = document.createElement('button');
                                  btnPapelera.className = 'btn btn-sm btn-danger ms-2';
                                  btnPapelera.title = 'Mover a papelera';
                                  btnPapelera.innerHTML = '<i class="bi bi-trash"></i>';
                                  btnPapelera.addEventListener('click', function(e) {
                                      e.stopPropagation();
                                      e.preventDefault();
                                      // Lógica para mover a papelera (AJAX)
                                      console.log('doc:', doc);
                                      console.log('doc.id_doc:', doc.id_doc);
                                      fetch('core/mover-a-papelera.php', {
                                          method: 'POST',
                                          headers: { 'Content-Type': 'application/json' },
                                          body: JSON.stringify({ id_archivo: doc.id_doc, categoria: doc.categoria })
                                      })
                                      .then(res => res.json())
                                      .then(data => {
                                          if (data.ok) {
                                              li.style.display = 'none';
                                              Swal.fire('Movido a papelera', '', 'success');
                                              // Refrescar categorías inmediatamente
                                              cargarArchivosExpediente();
                                          } else {
                                              Swal.fire('Error', data.msg || 'No se pudo mover a papelera', 'error');
                                          }
                                      })
                                      .catch(() => Swal.fire('Error', 'No se pudo conectar con el servidor', 'error'));
                                  });
                                  group.appendChild(btnPapelera);
                                  li.appendChild(group);
                                  ul.appendChild(li);
                              });
                          }
                      });
                  }
              })
              .catch(error => {
                  console.error('Error al cargar archivos del expediente:', error);
              });
      }
      cargarArchivosExpediente();
      // Exponer la función globalmente para que pueda ser llamada desde otras páginas (como la papelera)
      window.cargarArchivosExpediente = cargarArchivosExpediente;

      // Mostrar/ocultar lista al hacer clic en la categoría
      document.querySelectorAll('.nav-link[data-cat]').forEach(function(link) {
          link.addEventListener('click', function(e) {
              e.preventDefault();
              var cat = link.getAttribute('data-cat');
              var ul = document.getElementById('list-' + cat);
              if (ul) {
                  ul.style.display = ul.style.display === 'none' ? 'block' : 'none';
              }
          });
      });

      // --- Funcionalidad de sidebar colapsable con JavaScript puro ---
      const sidebar = document.getElementById('sidebar-expediente');
      const expandBtn = document.getElementById('expand-sidebar-btn');
      let isExpanded = true;

      function toggleSidebar() {
          const sidebarContent = document.getElementById('sidebar-content');
          const expandIcon = expandBtn.querySelector('i');
          
          if (isExpanded) {
              // Colapsar sidebar
              if (sidebarContent) {
                  sidebarContent.style.display = 'none';
              }
              
              // Ajustar el sidebar a un ancho mínimo
              sidebar.style.minWidth = '50px';
              sidebar.style.maxWidth = '50px';
              sidebar.style.width = '50px';
              sidebar.style.padding = '0';
              sidebar.style.overflowX = 'hidden';
              sidebar.style.overflowY = 'hidden';
              
              // Ajustar posición del botón cuando está colapsado (más pegado)
              expandBtn.style.right = '-15px';
              expandBtn.style.boxShadow = '2px 2px 10px rgba(0,0,0,0.3)';
              
              // Cambiar icono a expandir
              expandIcon.className = 'bi bi-chevron-right text-white';
              expandBtn.title = 'Expandir sidebar';
              
              isExpanded = false;
          } else {
              // Expandir sidebar
              if (sidebarContent) {
                  sidebarContent.style.display = '';
              }
              
              // Restaurar estilos del sidebar
              sidebar.style.minWidth = '450px';
              sidebar.style.maxWidth = '600px';
              sidebar.style.width = '';
              sidebar.style.padding = '1.5rem 0';
              sidebar.style.overflowX = 'hidden';
              sidebar.style.overflowY = 'auto';
              
              // Ajustar posición del botón cuando está expandido (por fuera del sidebar)
              expandBtn.style.right = '-50px';
              expandBtn.style.boxShadow = '4px 4px 20px rgba(0,0,0,0.5)';
              
              // Cambiar icono a colapsar
              expandIcon.className = 'bi bi-chevron-left text-white';
              expandBtn.title = 'Colapsar sidebar';
              
              isExpanded = true;
          }
      }

      // Evento del botón central único
      if (expandBtn) {
          expandBtn.addEventListener('click', toggleSidebar);
      }

      // Responsive: en pantallas pequeñas, ocultar completamente
      function handleResize() {
          if (window.innerWidth <= 768 && !isExpanded) {
              sidebar.style.display = 'none';
          } else if (window.innerWidth > 768 && !isExpanded) {
              sidebar.style.display = 'flex';
          }
      }

      window.addEventListener('resize', handleResize);
  });
  </script>

<?php endif; ?>




<!-- Script de agregar-cliente.php -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'agregar-cliente' || (isset($_GET['pg']) && $_GET['pg'] === 'agregar-cliente')): ?>
    <script>
    document.getElementById('formAgregarCliente').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = e.target;
      const fd = new FormData(form);
      fetch('/app/core/agregar-cliente.php', {
        method: 'POST',
        body: fd
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          if (window.Swal) {
            Swal.fire({
              icon: 'success',
              title: '¡Guardado!',
              text: data.message,
              showConfirmButton: true
            }).then(() => {
              form.reset();
              window.location.href = '/app/panel.php?pg=clientes';
            });
          } else {
            alert(data.message);
            form.reset();
          }
        } else {
          if (window.Swal) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message || 'No se pudo guardar.',
              showConfirmButton: true
            });
          } else {
            alert(data.message || 'No se pudo guardar.');
          }
        }
      })
      .catch(() => {
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error de conexión o respuesta inesperada del servidor.',
            showConfirmButton: true
          });
        } else {
          alert('Error de conexión o respuesta inesperada del servidor.');
        }
      });
    });
    </script> 
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        fetch('core/listar-regimenes.php')
          .then(res => res.json())
          .then(data => {
            const select = document.getElementById('regimen_fiscal');
            select.innerHTML = '';
            if (data.success && Array.isArray(data.data)) {
              select.innerHTML = '<option value="">Seleccionar</option>' +
                data.data.map(r => `<option value="${r.clave}">${r.descripcion}</option>`).join('');
            } else {
              select.innerHTML = '<option value="">No disponible</option>';
            }
          })
          .catch(() => {
            const select = document.getElementById('regimen_fiscal');
            select.innerHTML = '<option value="">Error al cargar</option>';
          });
      });
    </script>
<?php endif; ?>

<!-- script de contactos-->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'contactos' || (isset($_GET['pg']) && $_GET['pg'] === 'contactos')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var form = document.getElementById('formAgregarContacto');
      if (form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          var formData = new FormData(form);
          fetch('/app/core/add-contacto.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              if (window.Swal) {
                Swal.fire({
                  icon: 'success',
                  title: '¡Guardado!',
                  text: data.message,
                  showConfirmButton: true,
                  timer: undefined
                }).then(() => { location.reload(); });
              } else {
                alert('✔ ' + data.message);
                location.reload();
              }
              var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAgregarContacto'));
              setTimeout(function(){
                modal.hide();
                location.reload();
              }, 3000); // Espera a que el toast termine
            } else {
              if (window.Swal) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: data.message,
                  showConfirmButton: true,
                  timer: undefined
                });
              } else {
                alert(data.message);
              }
            }
          })
          .catch(() => {
            alert('Error de conexión o servidor.');
          });
        });
      }

      // Lógica para editar contacto
      var formEditar = document.getElementById('formEditarContacto');
      if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
          e.preventDefault();
          var formData = new FormData(formEditar);
          fetch('/app/core/editar-contacto.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              if (window.Swal) {
                Swal.fire({
                  icon: 'success',
                  title: '¡Actualizado!',
                  text: data.message,
                  showConfirmButton: true,
                  timer: undefined
                }).then(() => { location.reload(); });
              } else {
                alert('✔ ' + data.message);
                location.reload();
              }
              var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditarContacto'));
              setTimeout(function(){
                modal.hide();
                location.reload();
              }, 3500);
            } else {
              if (window.Swal) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: data.message,
                  showConfirmButton: true,
                  timer: undefined
                });
              } else {
                alert(data.message);
              }
            }
          })
          .catch(() => {
            alert('Error de conexión o servidor.');
          });
        });
      }

        // Evento para mostrar modal de edición al hacer clic en el botón editar
        document.querySelectorAll('.btn-editar-contacto').forEach(function(btn) {
          btn.addEventListener('click', function() {
            var id = btn.getAttribute('data-id');
            if (id) {
              cargarDatosContacto(id);
            }
          });
        });
    });

    // Función para cargar datos en el modal de edición
    function cargarDatosContacto(id) {
    fetch('/app/core/get-contacto.php?id_contacto=' + id)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            document.getElementById('edit_id_contacto').value = data.contacto.id_contacto;
            document.getElementById('edit_nombre').value = data.contacto.nombre;
            document.getElementById('edit_telefono').value = data.contacto.telefono;
            document.getElementById('edit_whatsapp').value = data.contacto.whatsapp;
            document.getElementById('edit_correo').value = data.contacto.correo;
            // Depuración: mostrar valor y opciones
            console.log('Valor cliente_empresa:', data.contacto.cliente_empresa);
            const select = document.getElementById('edit_cliente_empresa');
            Array.from(select.options).forEach(opt => {
              console.log('Option:', opt.value, opt.text);
            });
            select.value = data.contacto.cliente_empresa;
            document.getElementById('edit_puesto').value = data.contacto.puesto;
            document.getElementById('edit_departamento').value = data.contacto.departamento;
            document.getElementById('edit_direccion').value = data.contacto.direccion;
            var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditarContacto'));
            modal.show();
          } else {
            alert('No se pudo cargar el contacto.');
          }
        })
        .catch(() => {
          alert('Error al cargar los datos del contacto.');
        });
    }
    window.cargarDatosContacto = cargarDatosContacto;
    </script>
    <script>
    document.addEventListener('click', function(e) {
      // Desactivar contacto
      const btnDesactivar = e.target.closest('.btn-desactivar-contacto');
      if (btnDesactivar) {
        const id = btnDesactivar.getAttribute('data-id');
        if (window.Swal) {
          Swal.fire({
            icon: 'warning',
            title: '¿Desactivar contacto?',
            text: '¿Seguro que deseas desactivar este contacto?',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
          }).then((result) => {
            if (result.isConfirmed) {
              fetch('core/desactivar-contacto.php', {
                method: 'POST',
                body: new URLSearchParams({id_contacto: id})
              })
              .then(response => response.json())
              .then(data => {
                Swal.fire({
                  icon: data.success ? 'success' : 'error',
                  title: data.success ? '¡Desactivado!' : 'Error',
                  text: data.message,
                  showConfirmButton: true,
                  timer: undefined
                }).then(() => { if (data.success) location.reload(); });
              })
              .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
            }
          });
        } else {
          if (confirm('¿Seguro que deseas desactivar este contacto?')) {
            fetch('core/desactivar-contacto.php', {
              method: 'POST',
              body: new URLSearchParams({id_contacto: id})
            })
            .then(response => response.json())
            .then(data => {
              alert(data.message);
              if (data.success) location.reload();
            })
            .catch(() => alert('Error de conexión.'));
          }
        }
      }

      // Activar contacto
      const btnActivar = e.target.closest('.btn-activar-contacto');
      if (btnActivar) {
        const id = btnActivar.getAttribute('data-id');
        if (window.Swal) {
          Swal.fire({
            icon: 'warning',
            title: '¿Activar contacto?',
            text: '¿Seguro que deseas activar este contacto?',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
          }).then((result) => {
            if (result.isConfirmed) {
              fetch('core/activar-contacto.php', {
                method: 'POST',
                body: new URLSearchParams({id_contacto: id})
              })
              .then(response => response.json())
              .then(data => {
                Swal.fire({
                  icon: data.success ? 'success' : 'error',
                  title: data.success ? '¡Activado!' : 'Error',
                  text: data.message,
                  showConfirmButton: true,
                  timer: undefined
                }).then(() => { if (data.success) location.reload(); });
              })
              .catch(() => Swal.fire('Error', 'Error de conexión.', 'error'));
            }
          });
        } else {
          if (confirm('¿Seguro que deseas activar este contacto?')) {
            fetch('core/activar-contacto.php', {
              method: 'POST',
              body: new URLSearchParams({id_contacto: id})
            })
            .then(response => response.json())
            .then(data => {
              alert(data.message);
              if (data.success) location.reload();
            })
            .catch(() => alert('Error de conexión.'));
          }
        }
      }
    });
  </script>
<?php endif; ?>



<!-- Scripts de empleados solo para empleados-config -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'empleados-config' || (isset($_GET['pg']) && $_GET['pg'] === 'empleados-config')): ?>
  <script>
    window.cargarDatosEmpleado = function(id, nombre, apellidos, correo, telefono, departamento, area) {
      if(document.getElementById('empleadoId')) document.getElementById('empleadoId').value = id || '';
      if(document.getElementById('empleadoNombre')) document.getElementById('empleadoNombre').value = nombre || '';
      if(document.getElementById('empleadoApellidos')) document.getElementById('empleadoApellidos').value = apellidos || '';
      if(document.getElementById('empleadoCorreo')) document.getElementById('empleadoCorreo').value = correo || '';
      if(document.getElementById('empleadoTelefono')) document.getElementById('empleadoTelefono').value = telefono || '';
      if(document.getElementById('empleadoDepartamento')) document.getElementById('empleadoDepartamento').value = departamento || '';
      if(document.getElementById('empleadoArea')) document.getElementById('empleadoArea').value = area || '';
      var btnEditar = document.getElementById('btnEditarEmpleado');
      var btnGuardar = document.getElementById('btnGuardarEmpleado');
      if(btnEditar) btnEditar.style.display = '';
      if(btnGuardar) btnGuardar.style.display = 'none';
            // Actualizar la URL con el id del empleado seleccionado
      var url = new URL(window.location.href);
      url.searchParams.set('id', id);
      window.history.pushState({}, '', url);
    }
    document.addEventListener('DOMContentLoaded', function() {
      var btnVolver = document.getElementById('btnVolverEmpleado');
      if(btnVolver) {
        btnVolver.addEventListener('click', function() {
          var modal = document.getElementById('modalEmpleado');
          if(modal) {
            var modalInstance = bootstrap.Modal.getInstance(modal);
            if(modalInstance) {
              modalInstance.hide();
            }
          }
                // Quitar el parámetro id de la URL al volver
          var url = new URL(window.location.href);
          url.searchParams.delete('id');
          window.history.pushState({}, '', url);
        });
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      var modal = document.getElementById('modalEmpleado');
      if(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
          var btnEditar = document.getElementById('btnEditarEmpleado');
          var btnGuardar = document.getElementById('btnGuardarEmpleado');
          if(btnEditar) btnEditar.style.display = 'none';
          if(btnGuardar) btnGuardar.style.display = 'none';
        });

        var btnEditar = document.getElementById('btnEditarEmpleado');
        var btnGuardar = document.getElementById('btnGuardarEmpleado');
        var btnEliminar = document.getElementById('btnEliminarEmpleado');
        if(btnEditar) btnEditar.style.display = '';
        if(btnEliminar) btnEliminar.style.display = '';

        if(btnEditar && btnGuardar) {
          if(btnEliminar) {
            btnEliminar.addEventListener('click', function() {
              var id = document.getElementById('empleadoId').value;
              if(!id) return;
              Swal.fire({
                title: '¿Eliminar empleado?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.isConfirmed) {
                  var formData = new FormData();
                  formData.append('id', id);
                  fetch('/app/core/eliminar-empleado.php', {
                    method: 'POST',
                    body: formData
                  })
                  .then(response => response.json())
                  .then(data => {
                    if(data && data.success) {
                      Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        html: data.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                      }).then(() => location.reload());
                    } else if(data && data.message) {
                      Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: data.message,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                      });
                    }
                  })
                  .catch(() => {
                    Swal.fire({
                      icon: 'error',
                      title: 'Error de red',
                      text: 'No se pudo conectar con el servidor.'
                    });
                  });
                }
              });
            });
          }

          btnEditar.addEventListener('click', function() {
            ['empleadoNombre','empleadoApellidos','empleadoCorreo','empleadoTelefono','empleadoDepartamento','empleadoArea'].forEach(function(id){
              var el = document.getElementById(id);
              if(el) el.readOnly = false;
            });
            btnEditar.style.display = 'none';
            btnGuardar.style.display = '';
          });

          btnGuardar.addEventListener('click', function() {
            var id = document.getElementById('empleadoId').value;
            var nombre = document.getElementById('empleadoNombre').value;
            var apellidos = document.getElementById('empleadoApellidos').value;
            var correo = document.getElementById('empleadoCorreo').value;
            var telefono = document.getElementById('empleadoTelefono').value;
            var departamento = document.getElementById('empleadoDepartamento').value;
            var area = document.getElementById('empleadoArea').value;
            var formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);
            formData.append('apellidos', apellidos);
            formData.append('correo', correo);
            formData.append('telefono', telefono);
            formData.append('departamento', departamento);
            formData.append('area', area);
            fetch('/app/core/editar-empleado.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              if(data && data.success) {
                Swal.fire({
                  icon: 'success',
                  title: '¡Éxito!',
                  html: data.message,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                }).then(() => location.reload());
              } else if(data && data.message) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  html: data.message,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'Cerrar'
                });
              }
            })
            .catch((e) => {
              console.error('Error al procesar respuesta:', e);
              Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo conectar con el servidor. ' + e
              });
            });
          });
        }
      }
    });
          // Script para agregar empleado desde el modal
  var formAgregar = document.getElementById('formAgregarEmpleado');
  if(formAgregar) {
    formAgregar.addEventListener('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(formAgregar);
      fetch('/app/core/agregar-empleado.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if(data && data.success) {
          Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            html: data.message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
          }).then(() => location.reload());
        } else if(data && data.message) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            html: data.message,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Cerrar'
          });
        }
      })
      .catch((e) => {
        Swal.fire({
          icon: 'error',
          title: 'Error de red',
          text: 'No se pudo conectar con el servidor. ' + e
        });
      });
    });
  }
  </script>
<?php endif; ?>

<!--scripts de usuarios solo para usuarios-config -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'usuarios-config' || (isset($_GET['pg']) && $_GET['pg'] === 'usuarios-config')): ?>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('nuevoPassword');
    const togglePassword = document.getElementById('togglePassword');
    const iconPassword = document.getElementById('iconPassword');
    if (togglePassword && passwordInput && iconPassword) {
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        iconPassword.classList.toggle('fa-eye');
        iconPassword.classList.toggle('fa-eye-slash');
      });
    }
  });
  </script>
  <script>
        window.cargarDatosUsuario = function(id, nombre, apellido, correo, telefono, status, perfil) {
          if(document.getElementById('usuarioId')) document.getElementById('usuarioId').value = id || '';
          if(document.getElementById('usuarioNombre')) document.getElementById('usuarioNombre').value = nombre || '';
          if(document.getElementById('usuarioApellido')) document.getElementById('usuarioApellido').value = apellido || '';
          if(document.getElementById('usuarioCorreo')) document.getElementById('usuarioCorreo').value = correo || '';
          if(document.getElementById('usuarioTelefono')) document.getElementById('usuarioTelefono').value = telefono || '';
          var statusSelect = document.getElementById('usuarioStatus');
          if(statusSelect) statusSelect.value = status || '1';
          if(statusSelect) statusSelect.disabled = true;
          var perfilSelect = document.getElementById('usuarioPerfil');
          if(perfilSelect) perfilSelect.value = perfil || 'perfil'; // '3' es ejemplo para Usuario
          if(perfilSelect) perfilSelect.disabled = true;
          var btnEditar = document.getElementById('btnEditarUsuario');
          var btnGuardar = document.getElementById('btnGuardarUsuario');
          if(btnEditar) btnEditar.style.display = '';
          if(btnGuardar) btnGuardar.style.display = 'none';
          var url = new URL(window.location.href);
          url.searchParams.set('id', id);
          window.history.pushState({}, '', url);
        }
        document.addEventListener('DOMContentLoaded', function() {
          var btnVolver = document.getElementById('btnVolverUsuario');
          if(btnVolver) {
            btnVolver.addEventListener('click', function() {
              var modal = document.getElementById('modalUsuario');
              if(modal) {
                var modalInstance = bootstrap.Modal.getInstance(modal);
                if(modalInstance) {
                  modalInstance.hide();
                }
              }
              var url = new URL(window.location.href);
              url.searchParams.delete('id');
              window.history.pushState({}, '', url);
            });
          }
        });

        document.addEventListener('DOMContentLoaded', function() {
          var modal = document.getElementById('modalUsuario');
          if(modal) {
            modal.addEventListener('hidden.bs.modal', function() {
              var btnEditar = document.getElementById('btnEditarUsuario');
              var btnGuardar = document.getElementById('btnGuardarUsuario');
              if(btnEditar) btnEditar.style.display = 'none';
              if(btnGuardar) btnGuardar.style.display = 'none';
              var statusSelect = document.getElementById('usuarioStatus');
              if(statusSelect) statusSelect.disabled = true;
            });

            var btnEditar = document.getElementById('btnEditarUsuario');
            var btnGuardar = document.getElementById('btnGuardarUsuario');
            if(btnEditar) btnEditar.style.display = '';

            if(btnEditar && btnGuardar) {
              btnEditar.addEventListener('click', function() {
                ['usuarioNombre','usuarioApellido','usuarioCorreo','usuarioTelefono'].forEach(function(id){
                  var el = document.getElementById(id);
                  if(el) el.readOnly = false;
                });
                var statusSelect = document.getElementById('usuarioStatus');
                if(statusSelect) statusSelect.disabled = false;
                var perfilSelect = document.getElementById('usuarioPerfil');
                if(perfilSelect) perfilSelect.disabled = false;
                btnEditar.style.display = 'none';
                btnGuardar.style.display = '';
              });

              btnGuardar.addEventListener('click', function() {
                var id = document.getElementById('usuarioId').value;
                var nombre = document.getElementById('usuarioNombre').value;
                var apellido = document.getElementById('usuarioApellido').value;
                var correo = document.getElementById('usuarioCorreo').value;
                var telefono = document.getElementById('usuarioTelefono').value;
                var status = document.getElementById('usuarioStatus').value;
                var perfil = document.getElementById('usuarioPerfil').value;
                var formData = new FormData();
                formData.append('id', id);
                formData.append('nombre', nombre);
                formData.append('apellido', apellido);
                formData.append('correo', correo);
                formData.append('telefono', telefono);
                formData.append('status', status);
                formData.append('id_perfil', perfil);
                fetch('/app/core/editar-usuario.php', {
                  method: 'POST',
                  body: formData
                })
                .then(response => response.json())
                .then(data => {
                  if(data && data.success) {
                    Swal.fire({
                      icon: 'success',
                      title: '¡Éxito!',
                      html: data.message,
                      confirmButtonColor: '#3085d6',
                      confirmButtonText: 'Aceptar'
                    }).then(() => location.reload());
                  } else if(data && data.message) {
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      html: data.message,
                      confirmButtonColor: '#d33',
                      confirmButtonText: 'Cerrar'
                    });
                  }
                })
                .catch((e) => {
                  console.error('Error al procesar respuesta:', e);
                  Swal.fire({
                    icon: 'error',
                    title: 'Error de red',
                    text: 'No se pudo conectar con el servidor. ' + e
                  });
                });
              });
            }
          }
        });
            // Script para agregar usuario y colaborador desde el modal
        var formAgregar = document.getElementById('formAgregarUsuario');
        if(formAgregar) {
          formAgregar.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(formAgregar);
            fetch('/app/core/agregar-usuario.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              if(data && data.success) {
                Swal.fire({
                  icon: 'success',
                  title: '¡Éxito!',
                  html: data.message,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                }).then(() => location.reload());
              } else if(data && data.message) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  html: data.message,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'Cerrar'
                });
              }
            })
            .catch((e) => {
              Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo conectar con el servidor. ' + e
              });
            });
          });
        }
      </script>
<?php endif; ?>









<!--____________________________________________________________________________________________________________________________________________-->

<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'ver-proy' || (isset($_GET['pg']) && $_GET['pg'] === 'ver-proy')): ?>
  <script>
    // Función para filtrar archivos (ya existente)
    function filtrarArchivos() {
        const input = document.getElementById('busquedaArchivos');
        const filtro = input.value.toLowerCase();
        const tabla = document.getElementById('tablaArchivos');
        const filas = tabla.getElementsByTagName('tr');

        for (let i = 1; i < filas.length; i++) {
            const fila = filas[i];
            const texto = fila.textContent || fila.innerText;
            
            if (texto.toLowerCase().indexOf(filtro) > -1) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        }
    }

      // Cargar categorías e instituciones cuando se abre el modal
      document.getElementById('modalAgregarArchivo').addEventListener('show.bs.modal', function() {
          cargarCategorias();
          cargarInstituciones();
      });

      // Función para cargar categorías
      function cargarCategorias() {
          fetch('core/list-add-categorias.php')
              .then(response => response.json())
              .then(data => {
                  const select = document.getElementById('id_categoria');
                  select.innerHTML = '<option value="">Selecciona categoría</option>';
                  
                  if (data.success && data.categorias) {
                      data.categorias.forEach(categoria => {
                          const option = document.createElement('option');
                          option.value = categoria.id_categoria;
                          option.textContent = categoria.nombre;
                          select.appendChild(option);
                      });
                  }
              })
              .catch(error => {
                  console.error('Error al cargar categorías:', error);
              });
      }

      // Función para cargar instituciones
      function cargarInstituciones() {
          fetch('core/list-add-instituciones.php')
              .then(response => response.json())
              .then(data => {
                  const select = document.getElementById('id_institucion');
                  select.innerHTML = '<option value="">Selecciona institución</option>';
                  
                  if (data.success && data.instituciones) {
                      data.instituciones.forEach(institucion => {
                          const option = document.createElement('option');
                          option.value = institucion.id_institucion;
                          option.textContent = institucion.nombre;
                          select.appendChild(option);
                      });
                  }
              })
              .catch(error => {
                  console.error('Error al cargar instituciones:', error);
              });
      }

      // Mostrar nombre del archivo seleccionado
      document.getElementById('addArchivo').addEventListener('change', function(e) {
          const file = e.target.files[0];
          const helpText = this.nextElementSibling;
          
          if (file) {
              helpText.textContent = `Archivo seleccionado: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
              helpText.classList.add('text-success');
              helpText.classList.remove('text-muted');
          } else {
              helpText.textContent = 'Ningún archivo seleccionado';
              helpText.classList.remove('text-success');
              helpText.classList.add('text-muted');
          }
      });

      // Manejar envío del formulario
      document.getElementById('formAgregarArchivo').addEventListener('submit', function(e) {
          e.preventDefault();
          
          const formData = new FormData(this);
          const submitBtn = this.querySelector('button[type="submit"]');
          const spinner = submitBtn.querySelector('.spinner-border');
          
          // Agregar el nombre del archivo desde el input file
          const fileInput = document.getElementById('addArchivo');
          if (fileInput.files[0]) {
              formData.append('nombre', fileInput.files[0].name);
          }
          
          // Mostrar spinner
          spinner.classList.remove('d-none');
          submitBtn.disabled = true;
          
          fetch('core/add-archivo-proyecto.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Cerrar modal
                  const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarArchivo'));
                  modal.hide();
                  
                  // Limpiar formulario
                  this.reset();
                  document.getElementById('addArchivo').dispatchEvent(new Event('change'));
                  
                  // Mostrar mensaje de éxito
                  mostrarAlerta('Archivo subido correctamente', 'success');
                  
                  // Recargar la lista de archivos
                  recargarListaArchivos();
              } else {
                  mostrarAlerta(data.msg || 'Error al subir el archivo', 'danger');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              mostrarAlerta('Error al subir el archivo', 'danger');
          })
          .finally(() => {
              // Ocultar spinner
              spinner.classList.add('d-none');
              submitBtn.disabled = false;
          });
      });

      // Función para mostrar alertas
      function mostrarAlerta(mensaje, tipo) {
          const alertContainer = document.createElement('div');
          alertContainer.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
          alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
          alertContainer.innerHTML = `
              ${mensaje}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          `;
          
          document.body.appendChild(alertContainer);
          
          // Auto remover después de 5 segundos
          setTimeout(() => {
              if (alertContainer.parentNode) {
                  alertContainer.parentNode.removeChild(alertContainer);
              }
          }, 5000);
      }

      // Función para recargar la lista de archivos
      function recargarListaArchivos() {
          const projectId = <?= $proyecto_id ?>;
          
          fetch(`core/listar-archivos-proyecto-carpetas.php?id_proyecto=${projectId}`)
              .then(response => response.text())
              .then(html => {
                  const tbody = document.querySelector('#tablaArchivos tbody');
                  if (tbody) {
                      tbody.innerHTML = html;
                  }
              })
              .catch(error => {
                  console.error('Error al recargar archivos:', error);
              });
      }
  </script>
  <script>
      var formEditarProyecto = document.getElementById('formEditarProyecto');
    if(formEditarProyecto) {
      formEditarProyecto.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(formEditarProyecto);
        fetch('core/editar-proyecto.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: '¡Proyecto actualizado!',
              html: data.message,
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Aceptar'
            }).then(() => location.reload());
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarProyecto'));
            if(modal) modal.hide();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              html: data.message,
              confirmButtonColor: '#d33',
              confirmButtonText: 'Cerrar'
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Error de red',
            text: 'No se pudo conectar con el servidor.'
          });
        });
      });
    }



      var formAgregarTarea = document.getElementById('formAgregarTarea');
      if(formAgregarTarea) {
        formAgregarTarea.addEventListener('submit', function(e) {
          e.preventDefault();
          var formData = new FormData(formAgregarTarea);
          fetch('/app/core/agregar-tarea.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if(data && data.success) {
              Swal.fire({
                icon: 'success',
                title: '¡Tarea agregada!',
                html: data.message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
              }).then(() => location.reload());
            } else if(data && data.message) {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                html: data.message,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Cerrar'
              });
            }
          })
          .catch((e) => {
            Swal.fire({
              icon: 'error',
              title: 'Error de red',
              text: 'No se pudo conectar con el servidor. ' + e
            });
          });
        });
      }
  </script>
  <script>
  // Script específico para ver-proy - modal de archivos
  document.addEventListener('DOMContentLoaded', function() {
      const modalAgregarArchivo = document.getElementById('modalAgregarArchivo');
      
      // Cargar categorías e instituciones cuando se abre el modal
      modalAgregarArchivo.addEventListener('show.bs.modal', function () {
          cargarCategorias();
          cargarInstituciones();
      });
      
      function cargarCategorias() {
          fetch('core/list-categorias.php')
              .then(response => response.json())
              .then(data => {
                  const select = document.getElementById('id_categoria');
                  select.innerHTML = '<option value="">Selecciona categoría</option>';
                  
                  if (data.success && data.categorias) {
                      data.categorias.forEach(categoria => {
                          const option = document.createElement('option');
                          option.value = categoria.id_categoria;
                          option.textContent = categoria.nombre;
                          select.appendChild(option);
                      });
                  }
              })
              .catch(error => {
                  console.error('Error al cargar categorías:', error);
              });
      }
      
      function cargarInstituciones() {
          fetch('core/list-instituciones.php')
              .then(response => response.json())
              .then(data => {
                  const select = document.getElementById('id_institucion');
                  select.innerHTML = '<option value="">Selecciona institución</option>';
                  
                  if (data.success && data.instituciones) {
                      data.instituciones.forEach(institucion => {
                          const option = document.createElement('option');
                          option.value = institucion.id_institucion;
                          option.textContent = institucion.nombre;
                          select.appendChild(option);
                      });
                  }
              })
              .catch(error => {
                  console.error('Error al cargar instituciones:', error);
              });
      }
      
      // ...existing code...
      
      // Mostrar nombre del archivo seleccionado
      document.getElementById('addArchivo').addEventListener('change', function() {
          const fileText = this.parentElement.querySelector('.form-text');
          if (this.files.length > 0) {
              fileText.textContent = this.files[0].name;
          } else {
              fileText.textContent = 'Ningún archivo seleccionado';
          }
      });
  });

  // Función para filtrar archivos en la tabla
  function filtrarArchivos() {
      const input = document.getElementById('busquedaArchivos');
      const filter = input.value.toLowerCase();
      const table = document.getElementById('tablaArchivos');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
      
      for (let i = 0; i < rows.length; i++) {
          const row = rows[i];
          const cells = row.getElementsByTagName('td');
          let found = false;
          
          // Buscar en todas las celdas de la fila
          for (let j = 0; j < cells.length - 1; j++) { // -1 para excluir la columna de acciones
              const cellText = cells[j].textContent || cells[j].innerText;
              if (cellText.toLowerCase().indexOf(filter) > -1) {
                  found = true;
                  break;
              }
          }
          
          row.style.display = found ? '' : 'none';
      }
  }
  </script>
  <script>
    // Función para filtrar archivos
    function filtrarArchivos() {
      var input = document.getElementById('busquedaArchivos');
      var filtro = input.value.toLowerCase();
      var tabla = document.getElementById('tablaArchivos');
      var filas = tabla.getElementsByTagName('tr');
      for (var i = 1; i < filas.length; i++) { // Empieza en 1 para saltar el header
        var fila = filas[i];
        var texto = fila.textContent.toLowerCase();
        fila.style.display = texto.indexOf(filtro) > -1 ? '' : 'none';
      }
    }
  </script>
<?php endif; ?>

<!--____________________________________________________________________________________________________________________________________________-->




<!-- Scripts de proyectos solo para proyectos-casos -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'proyectos-casos' || (isset($_GET['pg']) && $_GET['pg'] === 'proyectos-casos')): ?>
    <?php include_once __DIR__ . '/../core/list-proyectos.php'; ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
            // Poblar selects dinámicos desde PHP
        var equipoSelect = document.getElementById('id_equipo');
        var supervisorSelect = document.getElementById('supervisorProyecto');
        if(equipoSelect) {
          equipoSelect.innerHTML = '<option value="">Seleccionar equipo</option>';
          <?php if (isset($equipos) && is_array($equipos)): ?>
          <?php foreach ($equipos as $equipo): ?>
            equipoSelect.innerHTML += `<option value="<?php echo $equipo['id_equipo']; ?>"><?php echo addslashes($equipo['nombre']); ?></option>`;
          <?php endforeach; ?>
        <?php endif; ?>
      }
      if(supervisorSelect) {
        supervisorSelect.innerHTML = '<option value="">Seleccionar supervisor</option>';
        <?php if (isset($colaboradores) && is_array($colaboradores)): ?>
        <?php foreach ($colaboradores as $colab): ?>
          supervisorSelect.innerHTML += `<option value="<?php echo $colab['id_colab']; ?>"><?php echo addslashes($colab['nombre'] . ' ' . $colab['apellidos']); ?></option>`;
        <?php endforeach; ?>
      <?php endif; ?>
    }

                // Script para agregar proyecto
    var formProyecto = document.getElementById('formCrearProyecto');
    if(formProyecto) {
      formProyecto.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(formProyecto);
        fetch('core/agregar-proyecto.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            if(window.Swal){
              Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                html: data.message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
              }).then(() => location.reload());
            } else {
              alert(data.message);
              location.reload();
            }
            formProyecto.reset();
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearProyecto'));
            if(modal) modal.hide();
          } else {
            if(window.Swal){
              Swal.fire({
                icon: 'error',
                title: 'Error',
                html: data.message,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Cerrar'
              });
            } else {
              alert(data.message);
            }
          }
        })
        .catch(() => {
          if(window.Swal){
            Swal.fire({
              icon: 'error',
              title: 'Error de red',
              text: 'No se pudo conectar con el servidor.'
            });
          } else {
            alert('Error de conexión.');
          }
        });
      });
    }
    });
    </script>
<?php endif; ?>



<!-- Script solo para archivos-config: subir archivo a carpeta -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'archivos-config' || (isset($_GET['pg']) && $_GET['pg'] === 'archivos-config')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.form-upload-carpeta').forEach(function(form) {
        var input = form.querySelector('input[type="file"]');
        input.addEventListener('change', function() {
          var archivo = input.files[0];
          if (!archivo) return;
          var carpeta = form.getAttribute('data-carpeta');
          var formData = new FormData();
          formData.append('archivo', archivo);
          formData.append('carpeta', carpeta);
          fetch('core/upload-carpeta.php', {
            method: 'POST',
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if(window.Swal){Swal.fire(data.msg,'',data.success?'success':'error');}else{alert(data.msg);}
            if (data.success) location.reload();
          })
          .catch(() => {
            if(window.Swal){Swal.fire('Error de red','','error');}else{alert('Error de red');}
          });
        });
      });
    });
  </script>
<?php endif; ?>




<!--Script solo para page visualizar-Tarea -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'visualizar-Tareas' || (isset($_GET['pg']) && $_GET['pg'] === 'visualizar-Tareas')): ?>
  <script>
    window.cargarDatosTarea = function(
      id_tarea, asunto, fecha_inicio, fecha_ejecucion, fecha_vencimiento, status, prioridad, detalles, archivo
      ) {
      if(document.getElementById('tareaId')) document.getElementById('tareaId').value = id_tarea || '';
      if(document.getElementById('verTareaAsunto')) document.getElementById('verTareaAsunto').value = asunto || '';
      if(document.getElementById('verTareaFechaInicio')) document.getElementById('verTareaFechaInicio').value = fecha_inicio || '';
      if(document.getElementById('verTareaFechaEjecucion')) document.getElementById('verTareaFechaEjecucion').value = fecha_ejecucion || '';
      if(document.getElementById('verTareaFechaVencimiento')) document.getElementById('verTareaFechaVencimiento').value = fecha_vencimiento || '';
      if(document.getElementById('verTareaEstado')) document.getElementById('verTareaEstado').value = status || '';
      if(document.getElementById('verTareaPrioridad')) document.getElementById('verTareaPrioridad').value = prioridad || '';
      if(document.getElementById('verTareaDetalles')) document.getElementById('verTareaDetalles').value = detalles || '';
      if(document.getElementById('verTareaArchivo')) document.getElementById('verTareaArchivo').value = archivo || '';
      var btnEditar = document.getElementById('btnEditarTarea');
      var btnGuardar = document.getElementById('btnGuardarTarea');
      if(btnEditar) btnEditar.style.display = '';
      if(btnGuardar) btnGuardar.style.display = 'none';
      // Dejar todos los campos readonly
      ['verTareaAsunto','verTareaFechaInicio','verTareaFechaEjecucion','verTareaFechaVencimiento','verTareaEstado','verTareaPrioridad','verTareaDetalles','verTareaArchivo'].forEach(function(id){
        var el = document.getElementById(id);
        if(el) el.readOnly = true;
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      var btnVolver = document.getElementById('btnVolverTarea');
      if(btnVolver) {
        btnVolver.addEventListener('click', function() {
          var modal = document.getElementById('modalTarea');
          if(modal) {
            var modalInstance = bootstrap.Modal.getInstance(modal);
            if(modalInstance) {
              modalInstance.hide();
            }
          }
        });
      }

      var modal = document.getElementById('modalTarea');
      if(modal) {
        modal.addEventListener('hidden.bs.modal', function() {
          var btnEditar = document.getElementById('btnEditarTarea');
          var btnGuardar = document.getElementById('btnGuardarTarea');
          if(btnEditar) btnEditar.style.display = 'none';
          if(btnGuardar) btnGuardar.style.display = 'none';
          // Dejar todos los campos readonly
          ['verTareaAsunto','verTareaFechaInicio','verTareaFechaEjecucion','verTareaFechaVencimiento','verTareaEstado','verTareaPrioridad','verTareaDetalles','verTareaArchivo'].forEach(function(id){
            var el = document.getElementById(id);
            if(el) el.readOnly = true;
          });
        });

        var btnEditar = document.getElementById('btnEditarTarea');
        var btnGuardar = document.getElementById('btnGuardarTarea');
        var btnEliminar = document.getElementById('btnEliminarTarea');
        if(btnEditar) btnEditar.style.display = '';
        if(btnEliminar) btnEliminar.style.display = '';

        if(btnEditar && btnGuardar) {
          btnEditar.addEventListener('click', function() {
            ['verTareaAsunto','verTareaFechaInicio','verTareaFechaEjecucion','verTareaFechaVencimiento','verTareaDetalles'].forEach(function(id){
              var el = document.getElementById(id);
              if(el) el.readOnly = false;
            });
            ['verTareaEstado','verTareaPrioridad'].forEach(function(id){
              var el = document.getElementById(id);
              if(el) el.disabled = false;
            });
            btnEditar.style.display = 'none';
            btnGuardar.style.display = '';
          });

          btnGuardar.addEventListener('click', function() {
            var id = document.getElementById('tareaId').value;
            var asunto = document.getElementById('verTareaAsunto').value;
            var fecha_inicio = document.getElementById('verTareaFechaInicio').value;
            var fecha_ejecucion = document.getElementById('verTareaFechaEjecucion').value;
            var fecha_vencimiento = document.getElementById('verTareaFechaVencimiento').value;
            var status = document.getElementById('verTareaEstado').value;
            var prioridad = document.getElementById('verTareaPrioridad').value;
            var detalles = document.getElementById('verTareaDetalles').value;
            var archivo = document.getElementById('verTareaArchivo').value;
            var formData = new FormData();
            formData.append('id_tarea', id);
            formData.append('asunto', asunto);
            formData.append('fecha_inicio', fecha_inicio);
            formData.append('fecha_ejecucion', fecha_ejecucion);
            formData.append('fecha_vencimiento', fecha_vencimiento);
            formData.append('status', status);
            formData.append('prioridad', prioridad);
            formData.append('detalles', detalles);
            formData.append('archivo', archivo);
            fetch('/app/core/editar-tarea.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              if(data && data.success) {
                Swal.fire({
                  icon: 'success',
                  title: '¡Éxito!',
                  html: data.message,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                }).then(() => location.reload());
              } else if(data && data.message) {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  html: data.message,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'Cerrar'
                });
              }
            })
            .catch((e) => {
              Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo conectar con el servidor. ' + e
              });
            });
          });

          if(btnEliminar) {
            btnEliminar.addEventListener('click', function() {
              var id = document.getElementById('tareaId').value;
              if(!id) return;
              Swal.fire({
                title: '¿Eliminar tarea?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.isConfirmed) {
                  var formData = new FormData();
                  formData.append('id_tarea', id);
                  fetch('/app/core/eliminar-tarea.php', {
                    method: 'POST',
                    body: formData
                  })
                  .then(response => response.json())
                  .then(data => {
                    if(data && data.success) {
                      Swal.fire({
                        icon: 'success',
                        title: 'Eliminada',
                        html: data.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                      }).then(() => location.reload());
                    } else if(data && data.message) {
                      Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: data.message,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Cerrar'
                      });
                    }
                  })
                  .catch(() => {
                    Swal.fire({
                      icon: 'error',
                      title: 'Error de red',
                      text: 'No se pudo conectar con el servidor.'
                    });
                  });
                }
              });
            });
          }
        }
      }
    });
  </script>
  <script>
  // Manejar botones de Iniciar y Finalizar tareas
  document.addEventListener('DOMContentLoaded', function() {
    // Manejar click en botón "Iniciar"
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-iniciar-tarea')) {
        e.preventDefault();
        const idTarea = e.target.getAttribute('data-id');
        
        if (!idTarea) {
          console.error('No se encontró el ID de la tarea');
          return;
        }
        
        // Confirmar acción
        if (window.Swal) {
          Swal.fire({
            title: '¿Iniciar tarea?',
            text: 'La tarea se marcará como "En proceso"',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, iniciar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              iniciarTarea(idTarea);
            }
          });
        } else {
          if (confirm('¿Seguro que quieres iniciar esta tarea?')) {
            iniciarTarea(idTarea);
          }
        }
      }
      
      // Manejar click en botón "Finalizar"
      if (e.target.classList.contains('btn-finalizar-tarea')) {
        e.preventDefault();
        const idTarea = e.target.getAttribute('data-id');
        
        if (!idTarea) {
          console.error('No se encontró el ID de la tarea');
          return;
        }
        
        // Confirmar acción
        if (window.Swal) {
          Swal.fire({
            title: '¿Finalizar tarea?',
            text: 'La tarea se marcará como "Completada"',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, finalizar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              finalizarTarea(idTarea);
            }
          });
        } else {
          if (confirm('¿Seguro que quieres finalizar esta tarea?')) {
            finalizarTarea(idTarea);
          }
        }
      }
    });
    
    // Función para iniciar una tarea
    function iniciarTarea(idTarea) {
      const data = {
        id_tarea: idTarea,
        status: 'en proceso',
        fecha_ejecucion: new Date().toISOString().slice(0, 19).replace('T', ' ')
      };
      
      fetch('/app/core/actualizar-tarea-status.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          if (window.Swal) {
            Swal.fire({
              icon: 'success',
              title: '¡Tarea iniciada!',
              text: 'La tarea se ha marcado como "En proceso"',
              confirmButtonColor: '#28a745',
              confirmButtonText: 'Aceptar'
            }).then(() => {
              location.reload();
            });
          } else {
            alert('Tarea iniciada exitosamente');
            location.reload();
          }
        } else {
          if (window.Swal) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'No se pudo iniciar la tarea',
              confirmButtonColor: '#dc3545',
              confirmButtonText: 'Cerrar'
            });
          } else {
            alert('Error: ' + (result.message || 'No se pudo iniciar la tarea'));
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Cerrar'
          });
        } else {
          alert('Error de conexión: No se pudo conectar con el servidor');
        }
      });
    }
    
    // Función para finalizar una tarea
    function finalizarTarea(idTarea) {
      const data = {
        id_tarea: idTarea,
        status: 'completada'
      };
      
      fetch('/app/core/actualizar-tarea-status.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          if (window.Swal) {
            Swal.fire({
              icon: 'success',
              title: '¡Tarea completada!',
              text: 'La tarea se ha marcado como "Completada"',
              confirmButtonColor: '#28a745',
              confirmButtonText: 'Aceptar'
            }).then(() => {
              location.reload();
            });
          } else {
            alert('Tarea completada exitosamente');
            location.reload();
          }
        } else {
          if (window.Swal) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: result.message || 'No se pudo completar la tarea',
              confirmButtonColor: '#dc3545',
              confirmButtonText: 'Cerrar'
            });
          } else {
            alert('Error: ' + (result.message || 'No se pudo completar la tarea'));
          }
        }
      })
      .catch(error => {
        console.error('Error:', error);
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Cerrar'
          });
        } else {
          alert('Error de conexión: No se pudo conectar con el servidor');
        }
      });
    }
  });
  </script>
  <script>
  // Enviar el formulario de agregar tarea por AJAX
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formAgregarTarea');
    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        fetch(form.action, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: '¡Éxito!',
              text: data.message || 'Tarea agregada correctamente.',
              confirmButtonText: 'OK',
              allowOutsideClick: false
            }).then(() => {
              // Cierra el modal solo después de que el usuario presione OK
              const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAgregarTarea'));
              modal.hide();
              form.reset();
              // Recarga la página para actualizar la lista de tareas
              window.location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message || 'Ocurrió un error al agregar la tarea.'
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo conectar con el servidor.'
          });
        });
      });
    }
  });
  </script>
<?php endif; ?>



<!-- Script solo para categorias -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'categorias' || (isset($_GET['pg']) && $_GET['pg'] === 'categorias')): ?>
      <!-- Los scripts de inicialización y lógica van en script.inc.php -->
      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

      <script>
      // Inicializar DataTable después de cargar datos
      cargarCategorias = function() {
        var $tabla = $('#tablaCategorias');
        var tablaDom = $tabla[0];
        // Destruir DataTable si ya está inicializado
        if ($.fn.dataTable.isDataTable(tablaDom)) {
          $tabla.DataTable().clear().destroy();
          $tabla.find('tbody').empty();
        }
        fetch('/app/core/list-categorias.php')
        .then(r => r.json())
        .then(data => {
          var tbody = $tabla.find('tbody');
          tbody.empty();
          if (data.success && data.categorias.length) {
            data.categorias.forEach(cat => {
              tbody.append(`<tr>
                <td>${cat.id_categoria}</td>
                <td>${cat.nombre}</td>
                <td>${cat.descripcion}</td>
                <td>
                  <button class='btn btn-info btn-sm' onclick='editarCategoria(${cat.id_categoria}, "${cat.nombre}", "${cat.descripcion}")'><i class='fas fa-edit'></i></button>
                  <button class='btn btn-danger btn-sm' onclick='eliminarCategoria(${cat.id_categoria})'><i class='fas fa-trash'></i></button>
                </td>
              </tr>`);
            });
          } else {
            tbody.append('<tr><td colspan=4>No hay categorías</td></tr>');
          }
          $tabla.DataTable({
            "lengthMenu": [5, 10, 25],
            "language": {
              "lengthMenu": "Mostrar _MENU_ registros por página",
              "search": "Buscar:",
              "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
              },
              "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
              "infoEmpty": "No hay registros disponibles",
              "zeroRecords": "No se encontraron resultados"
            }
          });
        });
      }
      cargarCategorias();

      // Agregar categoría
      document.querySelector('#modalCategoria .btn-primary').onclick = function() {
        const nombre = document.getElementById('nombreCategoria').value.trim();
        const descripcion = document.getElementById('descripcionCategoria').value.trim();
        fetch('/app/core/add-categoria.php', {
          method: 'POST',
          body: new URLSearchParams({nombre, descripcion})
        }).then(r => r.json()).then(data => {
          if (data.success) {
            document.getElementById('nombreCategoria').value = '';
            document.getElementById('descripcionCategoria').value = '';
            var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCategoria'));
            modal.hide();
            cargarCategorias();
          } else {
            alert(data.msg || 'Error al agregar');
          }
        });
      };

      // Eliminar categoría
      window.eliminarCategoria = function(id) {
        if (window.Swal) {
          Swal.fire({
            title: '¿Eliminar categoría?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              fetch('/app/core/delete-categoria.php', {
                method: 'POST',
                body: new URLSearchParams({id})
              }).then(r => r.json()).then(data => {
                if (data.success) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: 'La categoría ha sido eliminada.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                  });
                  cargarCategorias();
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msg || 'Error al eliminar',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                  });
                }
              });
            }
          });
        } else {
          if (confirm('¿Eliminar categoría?')) {
            fetch('/app/core/delete-categoria.php', {
              method: 'POST',
              body: new URLSearchParams({id})
            }).then(r => r.json()).then(data => {
              if (data.success) cargarCategorias();
              else alert(data.msg || 'Error al eliminar');
            });
          }
        }
      };

      // Editar categoría
      window.editarCategoria = function(id, nombre, descripcion) {
        document.getElementById('nombreCategoria').value = nombre;
        document.getElementById('descripcionCategoria').value = descripcion;
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCategoria'));
        modal.show();
        // Cambiar el botón para actualizar
        const btn = document.querySelector('#modalCategoria .btn-primary');
        btn.textContent = 'Actualizar';
        btn.onclick = function() {
          const nuevoNombre = document.getElementById('nombreCategoria').value.trim();
          const nuevaDesc = document.getElementById('descripcionCategoria').value.trim();
          fetch('/app/core/update-categoria.php', {
            method: 'POST',
            body: new URLSearchParams({id, nombre: nuevoNombre, descripcion: nuevaDesc})
          }).then(r => r.json()).then(data => {
            if (data.success) {
              modal.hide();
              cargarCategorias();
              btn.textContent = 'Guardar';
              btn.onclick = document.querySelector('#modalCategoria .btn-primary').onclick;
            } else {
              alert(data.msg || 'Error al actualizar');
            }
          });
        };
      };

      // Reset modal al abrir para agregar
      document.querySelector('[data-bs-target="#modalCategoria"]').onclick = function() {
        document.getElementById('nombreCategoria').value = '';
        document.getElementById('descripcionCategoria').value = '';
        const btn = document.querySelector('#modalCategoria .btn-primary');
        btn.textContent = 'Guardar';
        btn.onclick = document.querySelector('#modalCategoria .btn-primary').onclick;
      };
      </script>
<?php endif; ?>

<!-- Script solo para instituciones -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'instituciones' || (isset($_GET['pg']) && $_GET['pg'] === 'instituciones')): ?>
    <!-- Integración de DataTables para instituciones -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script>
    document.addEventListener('DOMContentLoaded', function() {
      function cargarInstituciones() {
        var $tabla = $('#tablaInstituciones');
        var tablaDom = $tabla[0];
        // Destruir DataTable si ya está inicializado
        if ($.fn.dataTable.isDataTable(tablaDom)) {
          $tabla.DataTable().clear().destroy();
          $tabla.find('tbody').empty();
        }
        fetch('/app/core/list-instituciones.php')
          .then(r => r.json())
          .then(data => {
            var tbody = $tabla.find('tbody');
            tbody.empty();
            if (data.success && data.instituciones.length) {
              data.instituciones.forEach(inst => {
                tbody.append(`<tr>
                  <td>${inst.id_institucion}</td>
                  <td>${inst.nombre}</td>
                  <td>${inst.tipo}</td>
                  <td>${inst.telefono || ''}</td>
                  <td>${inst.correo}</td>
                  <td>
                    <button class='btn btn-sm btn-primary' onclick='verInstitucionModal(${JSON.stringify(inst)})'><i class='fas fa-eye'></i></button>
                    <button class='btn btn-sm btn-warning' onclick='editarInstitucion(
                      ${inst.id_institucion},
                      "${inst.nombre}",
                      "${inst.tipo}",
                      "${inst.correo}",
                      "${inst.descripcion || ''}",
                      "${inst.direccion || ''}",
                      "${inst.telefono || ''}",
                      "${inst.web || ''}",
                      "${inst.ubicacion_url || ''}"
                    )'><i class='fas fa-edit'></i></button>
                    <button class='btn btn-sm btn-danger' onclick='eliminarInstitucion(${inst.id_institucion})'><i class='fas fa-trash-alt'></i></button>
                  </td>
                </tr>`);
              });
            } else {
              tbody.append('<tr><td colspan=6>No hay instituciones</td></tr>');
            }
            $tabla.DataTable({
              "lengthMenu": [5, 10, 25],
              "language": {
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "search": "Buscar:",
                "paginate": {
                  "first": "Primero",
                  "last": "Último",
                  "next": "Siguiente",
                  "previous": "Anterior"
                },
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "No hay registros disponibles",
                "zeroRecords": "No se encontraron resultados"
              }
            });
          });
      }
      cargarInstituciones();

      // Agregar/Actualizar institución
      document.getElementById('formInstitucion').onsubmit = function(e) {
        e.preventDefault();
        const id = document.getElementById('institucionId').value.trim();
        const nombre = document.getElementById('nombreInstitucion').value.trim();
        const tipo = document.getElementById('tipoInstitucion').value.trim();
        const correo = document.getElementById('correoInstitucion').value.trim();
        const telefono = document.getElementById('telefonoInstitucion').value.trim();
        const direccion = document.getElementById('direccionInstitucion').value.trim();
        const descripcion = document.getElementById('descripcionInstitucion').value.trim();
        const web = document.getElementById('webInstitucion').value.trim();
        const ubicacion_url = document.getElementById('ubicacionUrlInstitucion').value.trim();
        let url = '/app/core/add-institucion.php';
        let body = new URLSearchParams({nombre, tipo, correo, telefono, direccion, descripcion, web, ubicacion_url});
        if (id) {
          url = '/app/core/update-institucion.php';
          body = new URLSearchParams({id, nombre, tipo, correo, telefono, direccion, descripcion, web, ubicacion_url});
        }
        fetch(url, {
          method: 'POST',
          body
        }).then(r => r.json()).then(data => {
          if (data.success) {
            var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInstitucion'));
            modal.hide();
            cargarInstituciones();
            limpiarFormularioInstitucion();
          } else {
            alert(data.msg || 'Error al guardar');
          }
        });
      };

      // Eliminar institución
      window.eliminarInstitucion = function(id) {
        if (window.Swal) {
          Swal.fire({
            title: '¿Eliminar institución?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              fetch('/app/core/delete-institucion.php', {
                method: 'POST',
                body: new URLSearchParams({id})
              }).then(r => r.json()).then(data => {
                if (data.success) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: 'La institución ha sido eliminada.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                  });
                  cargarInstituciones();
                } else {
                  Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msg || 'Error al eliminar',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                  });
                }
              });
            }
          });
        } else {
          if (confirm('¿Eliminar institución?')) {
            fetch('/app/core/delete-institucion.php', {
              method: 'POST',
              body: new URLSearchParams({id})
            }).then(r => r.json()).then(data => {
              if (data.success) cargarInstituciones();
              else alert(data.msg || 'Error al eliminar');
            });
          }
        }
      };

      // Editar institución
      window.editarInstitucion = function(id, nombre, tipo, correo, descripcion = '', direccion = '', telefono = '', web = '', ubicacion_url = '') {
        document.getElementById('institucionId').value = id;
        document.getElementById('nombreInstitucion').value = nombre;
        // Seleccionar el valor correcto en el select, ignorando mayúsculas/minúsculas
        var tipoSelect = document.getElementById('tipoInstitucion');
        for (var i = 0; i < tipoSelect.options.length; i++) {
          if (tipoSelect.options[i].value.toLowerCase() === (tipo || '').toLowerCase()) {
            tipoSelect.selectedIndex = i;
            break;
          }
        }
        document.getElementById('correoInstitucion').value = correo;
        if(document.getElementById('descripcionInstitucion')) document.getElementById('descripcionInstitucion').value = descripcion;
        if(document.getElementById('direccionInstitucion')) document.getElementById('direccionInstitucion').value = direccion;
        if(document.getElementById('telefonoInstitucion')) document.getElementById('telefonoInstitucion').value = telefono;
        if(document.getElementById('webInstitucion')) document.getElementById('webInstitucion').value = web;
        if(document.getElementById('ubicacionUrlInstitucion')) document.getElementById('ubicacionUrlInstitucion').value = ubicacion_url;
        document.getElementById('modalInstitucionLabel').textContent = 'Editar Institución';
        var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInstitucion'));
        modal.show();
      };

      // Ver institución profesional
      window.verInstitucionModal = function(inst) {
        const modal = document.getElementById('modalVerInstitucion');
        modal.querySelector('.modal-title').textContent = 'Detalles de la Institución';
        modal.querySelector('.modal-body').innerHTML = `
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="fw-bold">ID:</label>
              <div class="text-muted">${inst.id_institucion}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Nombre:</label>
              <div class="text-muted">${inst.nombre}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Tipo:</label>
              <div class="text-muted">${inst.tipo}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Teléfono:</label>
              <div class="text-muted">${inst.telefono || ''}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Correo:</label>
              <div class="text-muted">${inst.correo}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Descripción:</label>
              <div class="text-muted">${inst.descripcion || ''}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Dirección:</label>
              <div class="text-muted">${inst.direccion || ''}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Web:</label>
              <div class="text-muted">${inst.web || ''}</div>
            </div>
            <div class="col-12 col-md-6">
              <label class="fw-bold">Ubicación URL:</label>
              <div class="text-muted">${inst.ubicacion_url || ''}</div>
            </div>
          </div>
        `;
        bootstrap.Modal.getOrCreateInstance(modal).show();
      };

      // Limpiar formulario
      window.limpiarFormularioInstitucion = function() {
        document.getElementById('institucionId').value = '';
        document.getElementById('nombreInstitucion').value = '';
        document.getElementById('tipoInstitucion').value = '';
        document.getElementById('correoInstitucion').value = '';
        if(document.getElementById('telefonoInstitucion')) document.getElementById('telefonoInstitucion').value = '';
        if(document.getElementById('direccionInstitucion')) document.getElementById('direccionInstitucion').value = '';
        if(document.getElementById('descripcionInstitucion')) document.getElementById('descripcionInstitucion').value = '';
        if(document.getElementById('webInstitucion')) document.getElementById('webInstitucion').value = '';
        if(document.getElementById('ubicacionUrlInstitucion')) document.getElementById('ubicacionUrlInstitucion').value = '';
        document.getElementById('modalInstitucionLabel').textContent = 'Registro de Nueva Institución';
      };
      // Limpiar el formulario al cerrar el modal de institución
      document.getElementById('modalInstitucion').addEventListener('hidden.bs.modal', function () {
        limpiarFormularioInstitucion();
      });
    });
    </script>
<?php endif; ?>



<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'usuario-Profile' || (isset($_GET['pg']) && $_GET['pg'] === 'usuario-Profile')): ?>
    <script>
    window._perfilEditando = false;
    window.alternarEdicionPerfil = function() {
        var campos = ["perfil_nombre", "perfil_apellidos", "perfil_correo", "perfil_telefono", "perfil_departamento", "perfil_area", "perfil_contrasena"];
        var passWrap = document.getElementById("perfil_contrasena_wrap");
        var btnVer = document.getElementById("btnVerContrasena");
        var btnEditar = document.getElementById("btnEditarPerfil");
        var txtEditar = document.getElementById("txtEditarPerfil");
        var btnGuardar = document.getElementById("btnGuardarPerfil");
        var inputPass = document.getElementById("perfil_contrasena");
        if (!window._perfilEditando) {
            campos.forEach(function(id) { var campo = document.getElementById(id); if(campo) campo.disabled = false; });
            btnGuardar.disabled = false;
            if(passWrap) passWrap.style.display = "";
            if(btnVer) btnVer.style.display = "";
            if(txtEditar) txtEditar.textContent = "Cancelar";
            window._perfilEditando = true;
        } else {
            campos.forEach(function(id) { var campo = document.getElementById(id); if(campo) campo.disabled = true; });
            btnGuardar.disabled = true;
            if(btnVer) btnVer.style.display = "none";
            if(txtEditar) txtEditar.textContent = "Editar";
            if(inputPass) inputPass.type = "password";
            window._perfilEditando = false;
        }
    }
    window.togglePasswordPerfil = function() {
        var input = document.getElementById("perfil_contrasena");
        var btn = document.getElementById("btnVerContrasena");
        if (!input || !btn) return;
        if (input.type === "password") {
            input.type = "text";
            btn.innerHTML = '<span class="bi bi-eye-slash"></span>';
        } else {
            input.type = "password";
            btn.innerHTML = '<span class="bi bi-eye"></span>';
        }
    };
    </script>
    <script>
    // Script para guardar perfil desde el formulario
    var formPerfil = document.getElementById('formEditarPerfil');
    if(formPerfil) {
      formPerfil.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(formPerfil);
        fetch('/app/core/editar-perfil.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if(data && data.success) {
            Swal.fire({
              icon: 'success',
              title: '¡Éxito!',
              html: data.message || 'Perfil actualizado correctamente.',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Aceptar'
            }).then(() => location.reload());
          } else if(data && data.message) {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              html: data.message,
              confirmButtonColor: '#d33',
              confirmButtonText: 'Cerrar'
            });
          }
        })
        .catch((e) => {
          Swal.fire({
            icon: 'error',
            title: 'Error de red',
            text: 'No se pudo conectar con el servidor. ' + e
          });
        });  
      });
    }

    window._perfilEditando = false;
    window.alternarEdicionPerfil = function() {
        var campos = ["perfil_nombre", "perfil_apellidos", "perfil_correo", "perfil_telefono", "perfil_departamento", "perfil_area", "perfil_contrasena"];
        var btnVer = document.getElementById("btnVerContrasena");
        var btnEditar = document.getElementById("btnEditarPerfil");
        var txtEditar = document.getElementById("txtEditarPerfil");
        var btnGuardar = document.getElementById("btnGuardarPerfil");
        var inputPass = document.getElementById("perfil_contrasena");
        if (!window._perfilEditando) {
            campos.forEach(function(id) { var campo = document.getElementById(id); if(campo) campo.disabled = false; });
            if(btnGuardar) { btnGuardar.style.display = ''; btnGuardar.disabled = false; }
            if(btnVer) btnVer.style.display = '';
            if(txtEditar) txtEditar.textContent = "Cancelar";
            window._perfilEditando = true;
        } else {
            campos.forEach(function(id) { var campo = document.getElementById(id); if(campo) campo.disabled = true; });
            if(btnGuardar) { btnGuardar.style.display = 'none'; btnGuardar.disabled = true; }
            if(btnVer) btnVer.style.display = 'none';
            if(txtEditar) txtEditar.textContent = "Editar";
            if(inputPass) inputPass.type = "password";
            window._perfilEditando = false;
        }
    }
    </script>
<?php endif; ?>

<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'bitacora' || (isset($_GET['pg']) && $_GET['pg'] === 'bitacora')): ?>
    <script>
    // filepath: c:\xampp\htdocs\app\pages\bitacora.inc.php
    // ...existing code...
    document.addEventListener('DOMContentLoaded', function() {
      const addBtn = document.getElementById('addParticipante');
      if (!addBtn) return;

      // Busca el tbody de la tabla de participantes
      const table = addBtn.closest('.col-md-6').querySelector('table');
      const tableBody = table.querySelector('tbody');

      addBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
          <td><input type="text" class="form-control" name="nombre[]"></td>
          <td><input type="text" class="form-control" name="apellido[]"></td>
          <td><input type="text" class="form-control" name="cargo[]"></td>
          <td><input type="email" class="form-control" name="correo[]"></td>
          <td><button type="button" class="btn btn-danger btn-sm remove-participante">Eliminar</button></td>
        `;
        tableBody.appendChild(newRow);
      });

      tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-participante')) {
          e.preventDefault();
          e.target.closest('tr').remove();
        }
      });
    });
    // ...existing code...
    </script>
<?php endif; ?>



<?php if ((isset($pg) && $pg === 'dashboard') || (basename($_SERVER['REQUEST_URI'], '.php') === 'dashboard') || (isset($_GET['pg']) && $_GET['pg'] === 'dashboard')): ?>
  <!--begin::Script chart-->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!--end::Script chart-->
  <script>
    const proyectosLabels = <?php echo json_encode($labelsProy); ?>;
    const proyectosData = <?php echo json_encode($valuesProy); ?>;
    const tareasLabels = <?php echo json_encode($labelsTareas); ?>;
    const tareasData = <?php echo json_encode($valuesTareas); ?>;
    const modernColors = [
        "#17a2b8", "#007bff", "#6c757d","#ffc107", "#28a745", "#fd7e14", "#dc3545"
    ];

    // Proyectos - Barras Horizontales
    new Chart(document.getElementById("chart0"), {
        type: "bar",
        data: {
            labels: proyectosLabels,
            datasets: [{
                label: "Proyectos por estado",
                data: proyectosData,
                backgroundColor: modernColors,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            indexAxis: "y",
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });

    // Proyectos - Barras Verticales
    new Chart(document.getElementById("chart1"), {
        type: "bar",
        data: {
            labels: proyectosLabels,
            datasets: [{
                label: "Proyectos por estado",
                data: proyectosData,
                backgroundColor: modernColors,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            indexAxis: "x",
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Proyectos - Pastel
    new Chart(document.getElementById("chart2"), {
        type: "pie",
        data: {
            labels: proyectosLabels,
            datasets: [{
                label: "Proyectos por estado",
                data: proyectosData,
                backgroundColor: modernColors,
                borderWidth: 2,
                borderColor: "#fff"
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: "bottom" } }
        }
    });

    // Tareas - Barras Horizontales
    new Chart(document.getElementById("chart3"), {
        type: "bar",
        data: {
            labels: tareasLabels,
            datasets: [{
                label: "Tareas por estado",
                data: tareasData,
                backgroundColor: modernColors,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            indexAxis: "y",
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
  </script>
<?php endif; ?>

<!-- Scripts de proyectos solo para proyectos-dashboard -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'proyectos-dashboard' || (isset($_GET['pg']) && $_GET['pg'] === 'proyectos-dashboard')): ?>
  <?php include_once __DIR__ . '/../core/list-proyecto-dashboard.php'; ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Poblar selects dinámicos desde PHP
      var equipoSelect = document.getElementById('id_equipo');
      var supervisorSelect = document.getElementById('supervisorProyecto');
      if(equipoSelect) {
        equipoSelect.innerHTML = '<option value="">Seleccionar equipo</option>';
        <?php if (isset($equipos) && is_array($equipos)): ?>
          <?php foreach ($equipos as $equipo): ?>
            equipoSelect.innerHTML += `<option value="<?php echo $equipo['id_equipo']; ?>"><?php echo addslashes($equipo['nombre']); ?></option>`;
          <?php endforeach; ?>
        <?php endif; ?>
      }
      if(supervisorSelect) {
        supervisorSelect.innerHTML = '<option value="">Seleccionar supervisor</option>';
        <?php if (isset($colaboradores) && is_array($colaboradores)): ?>
          <?php foreach ($colaboradores as $colab): ?>
            supervisorSelect.innerHTML += `<option value="<?php echo $colab['id_colab']; ?>"><?php echo addslashes($colab['nombre'] . ' ' . $colab['apellidos']); ?></option>`;
          <?php endforeach; ?>
        <?php endif; ?>
      }

      // Script para agregar proyecto
      var formProyecto = document.getElementById('formCrearProyecto');
      if(formProyecto) {
        formProyecto.addEventListener('submit', function(e) {
          e.preventDefault();
          var formData = new FormData(formProyecto);
          fetch('core/agregar-proyecto.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              if(window.Swal){
                Swal.fire({
                  icon: 'success',
                  title: '¡Éxito!',
                  html: data.message,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                }).then(() => location.reload());
              } else {
                alert(data.message);
                location.reload();
              }
              formProyecto.reset();
              var modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearProyecto'));
              if(modal) modal.hide();
            } else {
              if(window.Swal){
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  html: data.message,
                  confirmButtonColor: '#d33',
                  confirmButtonText: 'Cerrar'
                });
              } else {
                alert(data.message);
              }
            }
          })
          .catch(() => {
            if(window.Swal){
              Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo conectar con el servidor.'
              });
            } else {
              alert('Error de conexión.');
            }
          });
        });
      }
    });
  </script>
<?php endif; ?>







  <!-- _______________________________________________________________________________________________________________________________________________________________________________________________________________ SUPERIOR-->
<!-- Script solo para archivos-directorios -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'archivos-directorios' || (isset($_GET['pg']) && $_GET['pg'] === 'archivos-directorios')): ?>

  // Inicializar variables por defecto
  $recursos = [];
  $breadcrumbs = [];

  // TEMPORAL: Permitir salida para debugging
  try {
      require_once __DIR__ . '/../core/drive-controller.php';
      
      // DEBUG: Verificar qué se cargó
      error_log("Drive Controller - Recursos cargados: " . count($recursos));
      error_log("Drive Controller - Breadcrumbs: " . count($breadcrumbs));
      if (isset($_SESSION['USR_ID'])) {
          error_log("Drive Controller - Usuario ID: " . $_SESSION['USR_ID']);
      } else {
          error_log("Drive Controller - No hay sesión de usuario");
      }
      
      // Los datos se cargan correctamente desde drive-controller.php
      
  } catch (Exception $e) {
      // Si hay error, usar valores por defecto
      error_log('Error en drive-controller: ' . $e->getMessage());
      echo '<div style="background:red;color:white;padding:10px;margin:10px;">❌ ERROR EN SCRIPT.INC.PHP: ' . htmlspecialchars($e->getMessage()) . '</div>';
  }

  // Asegurar que las variables están definidas
  if (!isset($recursos)) $recursos = [];
  if (!isset($breadcrumbs)) $breadcrumbs = [];
  ?>
  <!-- Animación visual al navegar entre carpetas -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <script>
  // --- RENDER DATA FROM PHP ARRAYS ---
  window.recursos = <?php echo json_encode($recursos ?: [], JSON_UNESCAPED_UNICODE); ?>;
  window.breadcrumbs = <?php echo json_encode($breadcrumbs ?: [], JSON_UNESCAPED_UNICODE); ?>;
  // Obtener el ID de usuario actual desde PHP
  const currentUserId = <?php echo isset($_SESSION['USR_ID']) ? (int)$_SESSION['USR_ID'] : 'null'; ?>;

  // DEBUG: Verificar datos cargados
  console.log('Debug archivos-directorios:');
  console.log('- Recursos cargados:', window.recursos);
  console.log('- Breadcrumbs:', window.breadcrumbs);
  console.log('- Usuario ID:', currentUserId);
  // Obtener el id de la carpeta actual desde la URL
  function getParam(name) {
    const url = new URL(window.location.href);
    return url.searchParams.get(name) || '';
  }
  window.carpetaActualId = getParam('idpadre') || '';

  // --- FILTRO EN VIVO ---
  function filtrarDriveList() {
      const q = document.getElementById('busquedaDrive').value.trim().toLowerCase();
      const items = document.querySelectorAll('#driveListGroup .drive-item');
      let hayResultados = false;
      // Limpiar mensajes vacíos previos
      const emptySearchMsg = document.getElementById('driveEmptySearchMsg');
      if (emptySearchMsg) emptySearchMsg.remove();
      // Ocultar mensaje de lista vacía si hay búsqueda
      const emptyListMsg = document.getElementById('driveEmptyMsg');
      if (emptyListMsg) emptyListMsg.style.display = q ? 'none' : (items.length === 0 ? '' : 'none');
      // Filtrar elementos
      items.forEach(item => {
          const nombre = item.getAttribute('data-nombre') || '';
          const texto = item.textContent.toLowerCase();
          if (!q || nombre.includes(q) || texto.includes(q)) {
              item.style.display = '';
              hayResultados = true;
          } else {
              item.style.display = 'none';
          }
      });
      // Mensaje vacío de búsqueda
      if (!hayResultados && items.length > 0 && q) {
          const div = document.createElement('div');
          div.className = 'list-group-item text-center py-5 bg-light border-0';
          div.id = 'driveEmptySearchMsg';
          div.innerHTML = '<i class="bi bi-search" style="font-size: 3rem; color:rgb(0,0,0,0.7);"></i><div class="fw-bold text-muted mb-2 mt-3">No hay resultados para la búsqueda</div>';
          document.getElementById('driveListGroup').appendChild(div);
      }
  }
  // Prevenir submit del form de búsqueda
  const formBusqueda = document.querySelector('form .form-control#busquedaDrive')?.closest('form');
  if (formBusqueda) {
      formBusqueda.addEventListener('submit', function(e) { e.preventDefault(); });
  }

  // Render breadcrumbs
  function renderBreadcrumbs() {
      try {
          const nav = document.getElementById('breadcrumbNav');
          if (!nav) {
              console.warn('Elemento breadcrumbNav no encontrado');
              return;
          }
          
          if (!window.breadcrumbs || !window.breadcrumbs.length) { 
              nav.innerHTML = ''; 
              return; 
          }
          
          let html = '<ol class="breadcrumb">';
          html += '<li class="breadcrumb-item"><a href="?pg=archivos-directorios">Raíz</a></li>';
          window.breadcrumbs.forEach((bc, i) => {
              if (i === window.breadcrumbs.length - 1) {
                  html += `<li class="breadcrumb-item active" aria-current="page"><i class="bi bi-folder-fill text-warning me-1"></i> ${bc.nombre}</li>`;
              } else {
                  html += `<li class="breadcrumb-item"><a href="?pg=archivos-directorios&idpadre=${bc.id}"><i class="bi bi-folder-fill text-warning me-1"></i> ${bc.nombre}</a></li>`;
              }
          });
          html += '</ol>';
          nav.innerHTML = html;
          console.log('Breadcrumbs renderizados correctamente');
      } catch (error) {
          console.error('Error en renderBreadcrumbs:', error);
      }
  }
  // Render archivos y carpetas
  function renderDriveList() {
      try {
          const list = document.getElementById('driveListGroup');
          if (!list) {
              console.warn('Elemento driveListGroup no encontrado');
              return;
          }
          
          const busquedaInput = document.getElementById('busquedaDrive');
          const q = busquedaInput ? busquedaInput.value.trim().toLowerCase() : '';
          
          // Asegurarse de usar SIEMPRE window.recursos
          const recursos = window.recursos || [];
          console.log('Renderizando lista con', recursos.length, 'recursos');
          
          if (!recursos.length) {
          list.innerHTML = `<div class="list-group-item text-center py-5 bg-light border-0" id="driveEmptyMsg">
              <i class="bi bi-archive-fill" style="font-size: 3rem; color:rgb(0, 0, 0); opacity: 0.7;"></i>
              <div class="fw-bold text-muted mb-2 mt-3">No hay archivos ni carpetas en esta ubicación</div>
              <div class="text-muted small mb-3">¡Sube tu primer archivo o crea una carpeta para comenzar!</div>
              <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalNuevaCarpeta"><i class="bi bi-folder-plus me-2"></i>Nueva carpeta</button>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSubirArchivo"><i class="bi bi-upload me-2"></i>Subir archivo</button>
          </div>`;
          return;
      }
      let html = '';
      let hayResultados = false;
      recursos.forEach(recurso => {
          // Si el usuario actual es el propietario, forzar todos los permisos a 1
          if (recurso.id_propietario == currentUserId) {
              recurso.ver = 1;
              recurso.descargar = 1;
              recurso.actualizar = 1;
              recurso.borrar = 1;
          }
          const nombre = recurso.nombre.toLowerCase();
          // Escapar nombre para JS seguro
          const nombreSafe = recurso.nombre.replace(/\\/g, "\\\\").replace(/'/g, "\\'").replace(/\"/g, '\\"');
          const idSafe = String(recurso.id).replace(/\\/g, "\\\\").replace(/'/g, "\\'").replace(/\"/g, '\\"');
          if (!q || nombre.includes(q)) {
              hayResultados = true;
              if (recurso.tipo === 'D') {
                  html += `<div class="list-group-item d-flex align-items-center drive-item position-relative" data-nombre="${nombre}">
                      <a href="?pg=archivos-directorios&idpadre=${recurso.id}" class="flex-grow-1 d-flex align-items-center text-decoration-none folder-link">
                          <i class="bi bi-folder-fill text-warning me-3" style="font-size:1.7rem;"></i>
                          <div>
                              <div class="fw-bold text-truncate">${recurso.nombre}</div>
                              <div class="text-muted small">Carpeta</div>
                          </div>
                      </a>
                      <div class="dropdown ms-2">
                          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownOpciones_${recurso.id}" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bi bi-three-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownOpciones_${recurso.id}">
                              <li><a class="dropdown-item text-primary" href="#" onclick="compartirCarpeta('${idSafe}', '${nombreSafe}'); return false;"><i class="bi bi-share me-2"></i>Compartir</a></li>
                              ${recurso.actualizar == 1 ? `<li><a class="dropdown-item text-warning" href="#" onclick="editarCarpeta('${idSafe}', '${nombreSafe}'); return false;"><i class="bi bi-pencil-square me-2"></i>Editar</a></li>` : ''}
                              ${recurso.borrar == 1 ? `<li><a class="dropdown-item text-danger" href="#" onclick="eliminarCarpeta('${idSafe}', '${nombreSafe}'); return false;"><i class="bi bi-trash me-2"></i>Quitar</a></li>` : ''}
                          </ul>
                      </div>
                  </div>`;
              } else {
                  let ext = recurso.nombre.split('.').pop().toLowerCase();
                  let icon = 'bi-file-earmark text-secondary';
                  if (['pdf'].includes(ext)) icon = 'bi-file-earmark-pdf text-danger';
                  else if (['doc','docx'].includes(ext)) icon = 'bi-file-earmark-word text-primary';
                  else if (['xls','xlsx'].includes(ext)) icon = 'bi-file-earmark-excel text-success';
                  else if (['ppt','pptx'].includes(ext)) icon = 'bi-file-earmark-ppt text-warning';
                  else if (['jpg','jpeg','png','gif'].includes(ext)) icon = 'bi-file-earmark-image text-info';
                  else if (['zip','rar'].includes(ext)) icon = 'bi-file-earmark-zip text-warning';
                  else if (['txt'].includes(ext)) icon = 'bi-file-earmark-text text-secondary';
                  html += `<div class="list-group-item d-flex align-items-center drive-item position-relative" data-nombre="${nombre}">
                      <i class="bi ${icon} me-3" style="font-size:1.7rem;"></i>
                      <div class="flex-grow-1">
                          <div class="fw-bold text-truncate mb-1">
                              ${recurso.nombre}
                              ${recurso.compartido == 1 ? '<i class="bi bi-people-fill ms-2 text-secondary" title="Compartido"></i>' : ''}
                          </div>
                          <div class="text-muted small">
                              Archivo &bull; ${Number(recurso.tamano_kb).toLocaleString()} KB
                              ${recurso.fecha ? `&bull; ${new Date(recurso.fecha).toLocaleDateString('es-ES', {day:'2-digit', month:'short', year:'numeric'})}` : ''}
                          </div>
                      </div>
                      <div class="dropdown ms-2">
                          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownOpciones_${recurso.id}" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="bi bi-three-dots-vertical"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownOpciones_${recurso.id}">
                              <li><a class="dropdown-item text-primary" href="#" onclick="compartirArchivo('${idSafe}', '${nombreSafe}'); return false;"><i class="bi bi-share me-2"></i>Compartir</a></li>
                              ${recurso.ver == 1 ? `<li><a class="dropdown-item text-info" href="core/preview-controler.php?file=${encodeURIComponent(recurso.id + '_' + recurso.nombre)}" target="_blank"><i class="bi bi-eye me-2"></i>Ver</a></li>` : ''}
                              ${recurso.descargar == 1 ? `<li><a class="dropdown-item" href="core/arch-descargar.php?id=${recurso.id}"><i class="bi bi-download me-2"></i>Descargar</a></li>` : ''}
                              ${recurso.actualizar == 1 ? `<li><a class="dropdown-item text-warning" href="#" onclick="editarArchivo('${idSafe}', '${nombreSafe}'); return false;"><i class="bi bi-pencil-square me-2"></i>Editar</a></li>` : ''}
                              ${recurso.borrar == 1 ? `<li><a class="dropdown-item text-danger" href="#" onclick="eliminarArchivo('${idSafe}', '${nombreSafe}'); return false;"><i class="bi bi-trash me-2"></i>Quitar</a></li>` : ''}
                          </ul>
                      </div>
                  </div>`;
              }
          }
      });
          if (!hayResultados) {
              html = `<div class="list-group-item text-center py-5 bg-light border-0" id="driveEmptySearchMsg">
                  <i class="bi bi-search" style="font-size: 3rem; color:rgb(0,0,0,0.7);"></i><div class="fw-bold text-muted mb-2 mt-3">No hay resultados para la búsqueda</div>
              </div>`;
          }
          list.innerHTML = html;
          console.log('Lista renderizada correctamente con', recursos.length, 'recursos');
      } catch (error) {
          console.error('Error en renderDriveList:', error);
          const list = document.getElementById('driveListGroup');
          if (list) {
              list.innerHTML = `<div class="alert alert-danger">Error al cargar archivos: ${error.message}</div>`;
          }
      }
  }
  document.addEventListener('DOMContentLoaded', function() {
      try {
          console.log('Iniciando carga de archivos-directorios...');
          
          // Verificar elementos del DOM
          const breadcrumbNav = document.getElementById('breadcrumbNav');
          const driveListGroup = document.getElementById('driveListGroup');
          const totalArchivos = document.getElementById('totalArchivos');
          const totalCarpetas = document.getElementById('totalCarpetas');
          const espacioUsado = document.getElementById('espacioUsado');
          
          console.log('Elementos DOM encontrados:', {
              breadcrumbNav: !!breadcrumbNav,
              driveListGroup: !!driveListGroup,
              totalArchivos: !!totalArchivos,
              totalCarpetas: !!totalCarpetas,
              espacioUsado: !!espacioUsado
          });
          
          renderBreadcrumbs();
          renderDriveList();
          
          // Estadísticas de la carpeta actual
          let archivos = 0, carpetas = 0, espacio = 0;
          if (window.recursos && Array.isArray(window.recursos)) {
              window.recursos.forEach(r => {
                  if (r.tipo === 'D') carpetas++;
                  else { archivos++; espacio += Number(r.tamano_kb) || 0; }
              });
          }
          
          if (totalArchivos) totalArchivos.textContent = archivos;
          if (totalCarpetas) totalCarpetas.textContent = carpetas;
          if (espacioUsado) espacioUsado.textContent = espacio > 0 ? (espacio/1024).toFixed(2) + ' MB' : '0 MB';
          
          console.log('Estadísticas calculadas:', { archivos, carpetas, espacio });
          console.log('Carga de archivos-directorios completada exitosamente');
      } catch (error) {
          console.error('Error en DOMContentLoaded archivos-directorios:', error);
          // Mostrar error en la interfaz también
          const driveListGroup = document.getElementById('driveListGroup');
          if (driveListGroup) {
              driveListGroup.innerHTML = `<div class="alert alert-danger">
                  <h5>Error al inicializar archivos-directorios</h5>
                  <p>${error.message}</p>
                  <small>Revisa la consola para más detalles</small>
              </div>`;
          }
      }

      // Restaurar: Detectar tamaño y tipo MIME al seleccionar archivo
      const archivoInput = document.getElementById('archivo');
      if (archivoInput) {
        archivoInput.addEventListener('change', function() {
          const file = this.files[0];
          if (file) {
            document.getElementById('tamanoArchivo').value = (file.size / 1024).toFixed(2);
            document.getElementById('tipoArchivo').value = file.type;
          } else {
            document.getElementById('tamanoArchivo').value = '';
            document.getElementById('tipoArchivo').value = '';
          }
        });
      }
      // Al abrir el modal de nueva carpeta, setear id_propietario y idpadre
      const modalNuevaCarpeta = document.getElementById('modalNuevaCarpeta');
      if (modalNuevaCarpeta) {
        modalNuevaCarpeta.addEventListener('show.bs.modal', function() {
          document.querySelector('#modalNuevaCarpeta input[name="id_propietario"]').value = currentUserId || '';
          // --- AQUI: setear idpadre correctamente ---
          const inputIdPadre = document.querySelector('#modalNuevaCarpeta input[name="idpadre"]');
          if (inputIdPadre) {
            // Si no hay carpeta actual, dejar vacío para raíz
            inputIdPadre.value = window.carpetaActualId ? window.carpetaActualId : '';
          }
        });
      }
      // Al abrir el modal de subir archivo, setear id_propietario y idpadre
      const modalSubirArchivo = document.getElementById('modalSubirArchivo');
      if (modalSubirArchivo) {
        modalSubirArchivo.addEventListener('show.bs.modal', function(e) {
          // Solo restaurar si NO es compartir
          if (!e.relatedTarget || !e.relatedTarget.classList.contains('btn-compartir')) {
            document.getElementById('modalSubirArchivoLabel').textContent = 'Subir archivo';
            const btn = document.querySelector('#modalSubirArchivo button[type="submit"]');
            if (btn) btn.textContent = 'Subir';
            const form = document.querySelector('#modalSubirArchivo form');
            if (form) form.action = 'core/upload.php';
            document.getElementById('archivo').closest('.mb-3').style.display = '';
            document.getElementById('tamanoArchivo').closest('.mb-3').style.display = '';
            document.getElementById('tipoArchivo').closest('.mb-3').style.display = '';
            document.querySelector('#modalSubirArchivo input[name="id_archivo"]')?.remove();
            // --- AQUI: setear idpadre correctamente ---
            const inputIdPadre = document.querySelector('#modalSubirArchivo input[name="idpadre"]');
            if (inputIdPadre) inputIdPadre.value = window.carpetaActualId || '';
          }
          document.querySelector('#modalSubirArchivo input[name="id_propietario"]').value = currentUserId || '';
        });
      }
      // --- BUSQUEDA/FILTRO EN VIVO ---
      const busquedaDrive = document.getElementById('busquedaDrive');
      if (busquedaDrive) {
          busquedaDrive.addEventListener('input', renderDriveList);
          console.log('Event listener de búsqueda agregado');
      } else {
          console.warn('Elemento busquedaDrive no encontrado');
      }
      // Eliminar msg=compartido de la URL al cargar la página
      if (window.location.search.includes('msg=compartido')) {
          const url = new URL(window.location.href);
          url.searchParams.delete('msg');
          window.history.replaceState({}, document.title, url.pathname + url.search);
      }
      // ...existing code for animaciones, filtros, etc...
  });

  // --- ACCIONES DE LOS 3 PUNTITOS ---
  function compartirArchivo(id, nombre) {
      if (!window.bootstrap || !window.bootstrap.Modal) {
          alert('Bootstrap JS no está cargado. Asegúrate de incluir bootstrap.bundle.min.js');
          return;
      }
      const modalEl = document.getElementById('modalCompartirArchivo');
      if (!modalEl) {
          alert('No se encontró el modal con id modalCompartirArchivo en el DOM.');
          return;
      }
      // Título y id
      document.getElementById('modalCompartirArchivoLabel').textContent = 'Compartir archivo: ' + nombre;
      document.getElementById('compartir_id_archivo').value = id;
      // Setear idpadre actual en el modal compartir
      var idPadre = window.carpetaActualId || '';
      var inputIdPadre = document.getElementById('compartir_idpadre');
      if (inputIdPadre) inputIdPadre.value = idPadre;
      // Limpiar y cargar usuarios
      const container = document.getElementById('usuariosCompartirChecks');
      container.innerHTML = '<div class="text-muted small">Cargando usuarios...</div>';
      fetch('core/usuarios-json.php')
        .then(r => r.json())
        .then(usuarios => {
          container.innerHTML = '';
          usuarios.forEach(u => {
            const div = document.createElement('div');
            div.className = 'form-check';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input';
            input.name = 'usuarios[]';
            input.value = u.id_usuario;
            input.id = 'compartir_u' + u.id_usuario;
            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = input.id;
            label.textContent = u.nombre + ' ' + u.apellido + ' (' + u.email + ')';
            div.appendChild(input);
            div.appendChild(label);
            container.appendChild(div);
          });
        });
      // Mostrar modal
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
  }

  function compartirCarpeta(id, nombre) {
      if (!window.bootstrap || !window.bootstrap.Modal) {
          alert('Bootstrap JS no está cargado. Asegúrate de incluir bootstrap.bundle.min.js');
          return;
      }
      const modalEl = document.getElementById('modalCompartirArchivo');
      if (!modalEl) {
          alert('No se encontró el modal con id modalCompartirArchivo en el DOM.');
          return;
      }
      document.getElementById('modalCompartirArchivoLabel').textContent = 'Compartir carpeta: ' + nombre;
      document.getElementById('compartir_id_archivo').value = id;
      // Limpiar y cargar usuarios
      const container = document.getElementById('usuariosCompartirChecks');
      container.innerHTML = '<div class="text-muted small">Cargando usuarios...</div>';
      fetch('core/usuarios-json.php')
        .then(r => r.json())
        .then(usuarios => {
          container.innerHTML = '';
          usuarios.forEach(u => {
            const div = document.createElement('div');
            div.className = 'form-check';
            const input = document.createElement('input');
            input.type = 'checkbox';
            input.className = 'form-check-input';
            input.name = 'usuarios[]';
            input.value = u.id_usuario;
            input.id = 'compartir_u' + u.id_usuario;
            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = input.id;
            label.textContent = u.nombre + ' ' + u.apellido + ' (' + u.email + ')';
            div.appendChild(input);
            div.appendChild(label);
            container.appendChild(div);
          });
        });
      // Ocultar el permiso de descargar y poner el valor en 0
      document.getElementById('compartirPermisoDescargar').closest('.form-check').style.display = 'none';
      document.getElementById('compartirPermisoDescargar').checked = false;
      document.getElementById('compartirPermisoDescargar').value = '0';
      // Mostrar modal
      const modal = new bootstrap.Modal(modalEl);
      modal.show();
  }

  function eliminarArchivo(id, nombre) {
      if (!confirm('¿Seguro que deseas quitar/eliminar el archivo "' + nombre + '"?')) return;
      // AJAX para eliminar
      fetch('core/delete-archivo.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id_archivo=' + encodeURIComponent(id)
      })
      .then(r => r.json())
      .then(res => {
          if (res.success) {
              location.reload(); // Recargar la página automáticamente
          } else {
              alert('No se pudo eliminar: ' + (res.msg || 'Error desconocido'));
          }
      })
      .catch(() => alert('Error de conexión al eliminar.'));
  }

  function eliminarCarpeta(id, nombre) {
      if (!confirm('¿Seguro que deseas quitar/eliminar la carpeta "' + nombre + '"?')) return;
      // AJAX para eliminar (reutiliza delete-archivo.php, ya que borra por ID y tipo)
      fetch('core/delete-archivo.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id_archivo=' + encodeURIComponent(id)
      })
      .then(r => r.json())
      .then(res => {
          if (res.success) {
              location.reload(); // Recargar la página automáticamente
          } else {
              alert('No se pudo eliminar: ' + (res.msg || 'Error desconocido'));
          }
      })
      .catch(() => alert('Error de conexión al eliminar.'));
  }

  function editarArchivo(id, nombre) {
      document.getElementById('modalEditarRecursoLabel').textContent = 'Renombrar archivo';
      document.getElementById('editar_id_recurso').value = id;
      document.getElementById('editar_tipo_recurso').value = 'A';
      document.getElementById('editar_nombre_recurso').value = nombre;
      const modal = new bootstrap.Modal(document.getElementById('modalEditarRecurso'));
      modal.show();
  }

  function editarCarpeta(id, nombre) {
      document.getElementById('modalEditarRecursoLabel').textContent = 'Renombrar carpeta';
      document.getElementById('editar_id_recurso').value = id;
      document.getElementById('editar_tipo_recurso').value = 'D';
      document.getElementById('editar_nombre_recurso').value = nombre;
      const modal = new bootstrap.Modal(document.getElementById('modalEditarRecurso'));
      modal.show();
  }

  document.getElementById('formEditarRecurso').addEventListener('submit', function(e) {
      e.preventDefault();
      const id = document.getElementById('editar_id_recurso').value;
      const tipo = document.getElementById('editar_tipo_recurso').value;
      const nombre = document.getElementById('editar_nombre_recurso').value.trim();
      if (!id || !nombre) return;
      fetch('core/editar-archivo.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id_recurso=' + encodeURIComponent(id) + '&tipo_recurso=' + encodeURIComponent(tipo) + '&nombre=' + encodeURIComponent(nombre)
      })
      .then(r => r.json())
      .then(res => {
          if (res.success) {
              // Actualizar el nombre en la vista sin recargar
              let recurso = window.recursos.find(r => String(r.id) === String(id));
              if (recurso) {
                  recurso.nombre = nombre;
                  renderDriveList();
              }
              bootstrap.Modal.getInstance(document.getElementById('modalEditarRecurso')).hide();
          } else {
              alert('No se pudo renombrar: ' + (res.msg || 'Error desconocido'));
          }
      })
      .catch(() => alert('Error de conexión al renombrar.'));
  });

  // Botón para mostrar compartidos
  const btnVerCompartidos = document.getElementById('btnVerCompartidos');
  let mostrandoCompartidos = false;
  let recursosOriginal = null;
  if (btnVerCompartidos) {
      btnVerCompartidos.addEventListener('click', function() {
          const list = document.getElementById('driveListGroup');
          if (!mostrandoCompartidos) {
              if (!recursosOriginal) recursosOriginal = JSON.parse(JSON.stringify(window.recursos));
              btnVerCompartidos.disabled = true;
              btnVerCompartidos.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cargando...';
              fetch('core/listar-compartidos.php')
                  .then(r => r.json())
                  .then(data => {
                      btnVerCompartidos.disabled = false;
                      // Debug: log the raw response
                      console.log('Respuesta listar-compartidos:', data);
                      if (data && data.recursos) {
                          console.log('Recursos compartidos:', data.recursos);
                      } else {
                          console.warn('No se recibió el campo recursos:', data);
                      }
                      // Normalize fields for frontend compatibility
                      if (data.success && Array.isArray(data.recursos)) {
                          data.recursos = data.recursos.map(r => ({
                              ...r,
                              id: r.id,
                              nombre: r.nombre,
                              tipo: r.tipo === 'A' || r.tipo === 'D' ? r.tipo : (r.tipo === 'archivo' ? 'A' : (r.tipo === 'carpeta' ? 'D' : r.tipo)),
                              tamano_kb: r.tamano_kb !== undefined ? Number(r.tamano_kb) : (Number(r.tamano) || Number(r.tamaño) || 0),
                              compartido: 1,
                              fecha: r.fecha || r.fecha_creacion || ''
                          }));
                      }
                      if (!data.success || !data.recursos.length) {
                          window.recursos = [];
                          renderDriveList();
                          // Mostrar mensaje vacío SOLO si no hay compartidos
                          list.innerHTML = `<div class='list-group-item text-center text-muted py-5'>No tienes archivos ni carpetas compartidos.</div>`;
                          // Limpiar estadísticas
                          document.getElementById('totalArchivos').textContent = '0';
                          document.getElementById('totalCarpetas').textContent = '0';
                          document.getElementById('espacioUsado').textContent = '0 MB';
                          // Limpiar breadcrumbs
                          document.getElementById('breadcrumbNav').innerHTML = '';
                          // Restaurar botón y estado
                          btnVerCompartidos.innerHTML = '<i class="bi bi-people"></i> Compartidos conmigo';
                          mostrandoCompartidos = false;
                          // Limpiar búsqueda y mensajes
                          document.getElementById('busquedaDrive').value = '';
                          const emptySearchMsg = document.getElementById('driveEmptySearchMsg');
                          if (emptySearchMsg) emptySearchMsg.remove();
                          return;
                      }
                      btnVerCompartidos.innerHTML = '<i class="bi bi-arrow-left"></i> Volver a mis archivos';
                      mostrandoCompartidos = true;
                      window.recursos = data.recursos;
                      renderDriveList();
                      // Calcular estadísticas
                      let archivos = 0, carpetas = 0, espacio = 0;
                      data.recursos.forEach(r => {
                          if (r.tipo === 'D') carpetas++;
                          else { archivos++; espacio += Number(r.tamano_kb) || 0; }
                      });
                      document.getElementById('totalArchivos').textContent = archivos;
                      document.getElementById('totalCarpetas').textContent = carpetas;
                      document.getElementById('espacioUsado').textContent = espacio > 0 ? (espacio/1024).toFixed(2) + ' MB' : '0 MB';
                      document.getElementById('breadcrumbNav').innerHTML = '';
                      // Limpiar búsqueda y mensajes
                      // (No limpiar la lista aquí, ya se actualizó con renderDriveList)
                      document.getElementById('busquedaDrive').value = '';
                      const emptySearchMsg = document.getElementById('driveEmptySearchMsg');
                      if (emptySearchMsg) emptySearchMsg.remove();
                  })
                  .catch(() => {
                      btnVerCompartidos.disabled = false;
                      btnVerCompartidos.innerHTML = '<i class="bi bi-people"></i> Compartidos conmigo';
                      mostrandoCompartidos = false;
                  });
          } else {
              window.recursos = JSON.parse(JSON.stringify(recursosOriginal));
              renderDriveList();
              btnVerCompartidos.innerHTML = '<i class="bi bi-people"></i> Compartidos conmigo';
              // Limpiar parámetro msg de la URL si existe
              if (window.location.search.includes('msg=compartido')) {
                  const url = new URL(window.location.href);
                  url.searchParams.delete('msg');
                  window.history.replaceState({}, document.title, url.pathname + url.search);
              }
              // Restaurar estadísticas y breadcrumbs
              let archivos = 0, carpetas = 0, espacio = 0;
              window.recursos.forEach(r => {
                  if (r.tipo === 'D') carpetas++;
                  else { archivos++; espacio += Number(r.tamano_kb) || 0; }
              });
              document.getElementById('totalArchivos').textContent = archivos;
              document.getElementById('totalCarpetas').textContent = carpetas;
              document.getElementById('espacioUsado').textContent = espacio > 0 ? (espacio/1024).toFixed(2) + ' MB' : '0 MB';
              renderBreadcrumbs();
              mostrandoCompartidos = false;
              // Limpiar búsqueda y mensajes
              document.getElementById('busquedaDrive').value = '';
              const emptySearchMsg = document.getElementById('driveEmptySearchMsg');
              if (emptySearchMsg) emptySearchMsg.remove();
          }
      });
  }
  // ...existing code...
  </script>
  <script>
  // Abrir la papelera en una nueva pestaña o sección
  document.getElementById('btnAbrirPapelera').addEventListener('click', function() {
    window.location.href = 'panel.php?pg=papelera-arch-dir';
  });
  </script>
  <script>
  // AJAX para subir archivo y mostrar errores en el modal
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalSubirArchivo');
    const form = modal.querySelector('form[action="core/upload.php"]');
    const inputArchivo = form.querySelector('input[name="archivo"]');
    let errorDiv = form.querySelector('.upload-error-msg');
    if (!errorDiv) {
      errorDiv = document.createElement('div');
      errorDiv.className = 'upload-error-msg text-danger mb-2';
      errorDiv.style.display = 'none';
      form.querySelector('.modal-body').appendChild(errorDiv);
    }

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      errorDiv.style.display = 'none';
      errorDiv.textContent = '';
      const formData = new FormData(form);
      fetch('core/upload.php', {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Cerrar modal y recargar lista
          const bsModal = bootstrap.Modal.getInstance(modal);
          bsModal.hide();
          // Opcional: recargar lista de archivos sin refrescar toda la página
          location.reload();
        } else {
          errorDiv.textContent = data.msg || 'Error al subir archivo.';
          errorDiv.style.display = 'block';
        }
      })
      .catch(() => {
        errorDiv.textContent = 'Error de red o formato inesperado.';
        errorDiv.style.display = 'block';
      });
    });

    // Limpiar error al abrir modal
    modal.addEventListener('show.bs.modal', function() {
      errorDiv.style.display = 'none';
      errorDiv.textContent = '';
      form.reset();
    });
  });
  </script>
  <style>
  #driveEmptyMsg img { opacity:0.5; }
  #driveEmptyMsg { border-radius: 12px; }
  </style>

<?php endif; ?>



<!-- Script solo para papelera -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'papelera-arch-dir' || (isset($_GET['pg']) && $_GET['pg'] === 'papelera-arch-dir')): ?>
  
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

<?php endif; ?>



  <!-- _______________________________________________________________________________________________________________________________________________________________________________________________________________INFERIOR -->








<!-- Script solo para equipos -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'equipos' || (isset($_GET['pg']) && $_GET['pg'] === 'equipos')): ?>
  <script>
    // Agregar colaborador al equipo
    window.idEquipoActual = null;
    document.addEventListener('DOMContentLoaded', function() {
      // ...existing code...
      // Quitar el click general de la tarjeta
      // Agregar función global para abrir el modal de colaboradores
      window.abrirModalColaboradores = function(idEquipo) {
        window.idEquipoActual = idEquipo;
        // Cargar lista de colaboradores del equipo
        fetch('core/list-colaboradores-equipo.php?id_equipo=' + encodeURIComponent(idEquipo))
          .then(r => r.text())
          .then(html => {
            document.getElementById('colaboradores-equipo-list').innerHTML = html;
            // Cargar select de colaboradores disponibles
            fetch('core/list-colaboradores.php')
              .then(r => r.json())
              .then(colabs => {
                var select = document.getElementById('edit-colaborador');
                select.innerHTML = '<option value="">Selecciona colaborador...</option>';
                colabs.forEach(function(colab) {
                  var opt = document.createElement('option');
                  opt.value = colab.id_colab;
                  opt.textContent = colab.nombre + ' ' + colab.apellidos;
                  select.appendChild(opt);
                });
              });
            var modal = new bootstrap.Modal(document.getElementById('modalColaboradoresEquipo'));
            modal.show();
          });
      }

      // Agregar colaborador
      var btnAgregar = document.getElementById('btnAgregarColaborador');
      if (btnAgregar) {
        btnAgregar.addEventListener('click', function() {
          var id_colab = document.getElementById('edit-colaborador').value;
          var rol = document.getElementById('edit-rol').value;
          if (!id_colab || !rol) {
            alert('Selecciona colaborador y escribe el rol.');
            return;
          }
          fetch('core/agregar-colaborador-equipo.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_equipo=' + encodeURIComponent(window.idEquipoActual) + '&id_colab=' + encodeURIComponent(id_colab) + '&rol=' + encodeURIComponent(rol)
          })
          .then(r => r.text())
          .then(resp => {
            if (resp.trim() === 'ok') {
              alert('Colaborador agregado correctamente.');
              // Limpiar campos
              document.getElementById('edit-colaborador').value = '';
              document.getElementById('edit-rol').value = '';
              // Recargar lista de colaboradores
              fetch('core/list-colaboradores-equipo.php?id_equipo=' + encodeURIComponent(window.idEquipoActual))
                .then(r => r.text())
                .then(html => {
                  document.getElementById('colaboradores-equipo-list').innerHTML = html;
                });
            } else {
              alert(resp);
            }
          });
        });
      }
    });

    // Quitar colaborador del equipo
    window.quitarColaborador = function(id_equipo, id_colab) {
      if (confirm('¿Seguro que deseas quitar este colaborador del equipo?')) {
        fetch('core/quitar-colaborador-equipo.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id_equipo=' + encodeURIComponent(id_equipo) + '&id_colab=' + encodeURIComponent(id_colab)
        })
        .then(r => r.text())
        .then(resp => {
          if (resp.trim() === 'ok') {
            // Recargar lista de colaboradores
            fetch('core/list-colaboradores-equipo.php?id_equipo=' + encodeURIComponent(id_equipo))
              .then(r => r.text())
              .then(html => {
                document.getElementById('colaboradores-equipo-list').innerHTML = html;
              });
          } else {
            alert('Error al quitar colaborador.');
          }
        });
      }
    }
    // Abrir modal al hacer clic en el botón
    document.getElementById('btnAbrirModalEquipo').addEventListener('click', function() {
      var modal = new bootstrap.Modal(document.getElementById('modalEquipo'));
      modal.show();
    });

    // Enviar formulario por AJAX (crear)
    document.getElementById('form-equipo').addEventListener('submit', function(e) {
      e.preventDefault();
      var form = e.target;
      var datos = new FormData(form);
      fetch('core/agregar-equipo.php', {
        method: 'POST',
        body: datos
      })
      .then(r => r.text())
      .then(resp => {
        var msg = document.getElementById('msg-equipo');
        if (resp.trim() === 'ok') {
          msg.innerHTML = '<span class="text-success">Equipo creado correctamente.</span>';
          form.reset();
          setTimeout(function(){
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalEquipo'));
            modal.hide();
            msg.innerHTML = '';
            location.reload();
          }, 1200);
        } else {
          msg.innerHTML = '<span class="text-danger">Error al crear el equipo.</span>';
        }
      });
    });

    // Función para eliminar equipo
    window.eliminarEquipo = function(id) {
      if (confirm('¿Seguro que deseas eliminar este equipo?')) {
        fetch('core/delete-equipo.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(r => r.text())
        .then(resp => {
          if (resp.trim() === 'ok') {
            location.reload();
          } else {
            alert('Error al eliminar el equipo.');
          }
        });
      }
    }

    // Función para abrir modal de edición
    window.editarEquipo = function(id) {
      fetch('core/get-equipo.php?id=' + encodeURIComponent(id))
      .then(r => r.json())
      .then(equipo => {
        if (equipo && equipo.id_equipo) {
          window.idEquipoActual = equipo.id_equipo;
          document.getElementById('edit-id').value = equipo.id_equipo;
          document.getElementById('edit-nombre').value = equipo.nombre;
          document.getElementById('edit-descripcion').value = equipo.descripcion;
          document.getElementById('edit-privacidad').value = equipo.privacidad;
          // Llenar select de colaboradores
          fetch('core/list-colaboradores.php')
            .then(r => r.json())
            .then(colabs => {
              var select = document.getElementById('edit-colaborador');
              select.innerHTML = '<option value="">Selecciona colaborador...</option>';
              colabs.forEach(function(colab) {
                var opt = document.createElement('option');
                opt.value = colab.id_colab;
                opt.textContent = colab.nombre + ' ' + colab.apellidos;
                select.appendChild(opt);
              });
            });
          var modal = new bootstrap.Modal(document.getElementById('modalEditEquipo'));
          modal.show();
        }
      });
    }

    // Enviar formulario de edición por AJAX
    document.getElementById('form-edit-equipo').addEventListener('submit', function(e) {
      e.preventDefault();
      var form = e.target;
      var datos = new FormData(form);
      fetch('core/update-equipo.php', {
        method: 'POST',
        body: datos
      })
      .then(r => r.text())
      .then(resp => {
        var msg = document.getElementById('msg-edit-equipo');
        if (resp.trim() === 'ok') {
          msg.innerHTML = '<span class="text-success">Equipo actualizado correctamente.</span>';
          setTimeout(function(){
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalEditEquipo'));
            modal.hide();
            msg.innerHTML = '';
            location.reload();
          }, 1200);
        } else {
          msg.innerHTML = '<span class="text-danger">Error al actualizar el equipo.</span>';
        }
      });
    });
  </script>
<?php endif; ?>


<!-- Script solo para nueva minuta -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'nueva-minuta' || (isset($_GET['pg']) && $_GET['pg'] === 'nueva-minuta')): ?>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    fetch('core/minuta-selects.php')
      .then(res => res.json())
      .then(data => {
        // Clientes
        const clienteSelect = document.getElementById('idcliente');
        if (data.clientes) {
          data.clientes.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id_cliente;
            opt.textContent = c.razon_social;
            clienteSelect.appendChild(opt);
          });
        }
        // Responsables
        const responsableSelect = document.getElementById('idresponsable');
        if (data.colaboradores) {
          data.colaboradores.forEach(col => {
            const opt = document.createElement('option');
            opt.value = col.id_colab;
            opt.textContent = col.nombre + ' ' + col.apellidos;
            responsableSelect.appendChild(opt);
          });
        }
        // Participantes (sys_contactos)
        const participantesSelect = document.getElementById('idparticipante');
        if (data.contactos) {
          data.contactos.forEach(cont => {
            const opt = document.createElement('option');
            opt.value = cont.id_contacto;
            opt.textContent = cont.nombre + (cont.apellidos ? ' ' + cont.apellidos : '');
            participantesSelect.appendChild(opt);
          });
        }
      })
      .catch(err => {
        console.error('Error cargando selects:', err);
      });
    // Participantes agregados
    let participantesAgregados = [];
    document.getElementById('agregarParticipante').addEventListener('click', function() {
      const select = document.getElementById('idparticipante');
      const id = select.value;
      if (!id) return;
      // Evitar duplicados
      if (participantesAgregados.includes(id)) {
        alert('Este participante ya fue agregado.');
        return;
      }
      participantesAgregados.push(id);
      renderParticipantes();
      select.value = '';
    });

    function renderParticipantes() {
      const lista = document.getElementById('listaParticipantes');
      lista.innerHTML = '';
      participantesAgregados.forEach(id => {
        const opt = document.querySelector('#idparticipante option[value="' + id + '"]');
        if (!opt) return;
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.textContent = opt.textContent;
        const btnQuitar = document.createElement('button');
        btnQuitar.className = 'btn btn-sm btn-danger';
        btnQuitar.textContent = 'Quitar';
        btnQuitar.onclick = function() {
          participantesAgregados = participantesAgregados.filter(pid => pid !== id);
          renderParticipantes();
        };
        li.appendChild(btnQuitar);
        lista.appendChild(li);
      });
    }

    // Al enviar el formulario, agregar los participantes como campos ocultos
    document.getElementById('formNuevaMinuta').addEventListener('submit', function(e) {
      // Eliminar campos ocultos previos
      document.querySelectorAll('.participante-hidden').forEach(el => el.remove());
      participantesAgregados.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'participantes[]';
        input.value = id;
        input.className = 'participante-hidden';
        this.appendChild(input);
      });
    });
  });
  </script>

<?php endif; ?>

  <!-- Script solo para minuta -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'minuta' || (isset($_GET['pg']) && $_GET['pg'] === 'minuta')): ?> 

  <script>
    // Funcionalidad de botón detalle eliminada, ahora es un enlace
  document.addEventListener('DOMContentLoaded', function() {
    function renderMinutasTabla(minutas) {
      const tbody = document.getElementById('tablaMinutasBody');
      if (!tbody) return;
      tbody.innerHTML = '';
      minutas.forEach(minuta => {
        const tr = document.createElement('tr');
        tr.setAttribute('data-id', minuta.id_minuta);
        tr.innerHTML = `
          <td>${minuta.titulo || ''}</td>
          <td>${minuta.fecha || ''}</td>
          <td>${minuta.hora_inicio || ''}</td>
          <td>${minuta.lugar || ''}</td>
          <td><a href="panel?pg=ver-minuta&id_minuta=${minuta.id_minuta}" class="btn btn-sm btn-secondary" title="Ver detalles"><i class="fas fa-eye"></i></a></td>
        `;
        tbody.appendChild(tr);
      });
    }

    function cargarMinutas() {
      fetch('core/minuta-list.php')
        .then(res => res.json())
        .then(data => {
          if (Array.isArray(data)) {
            renderMinutasTabla(data);
          } else if (Array.isArray(data.minutas)) {
            renderMinutasTabla(data.minutas);
          }
        })
        .catch(err => {
          console.error('Error al cargar minutas:', err);
        });
    }

    cargarMinutas();
  });
  </script>

<?php endif; ?>

  <!-- Script solo para ver-minuta -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'ver-minuta' || (isset($_GET['pg']) && $_GET['pg'] === 'ver-minuta')): ?> 
  <!-- JS dinámico al final -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      let acuerdoIdParaEstado = null;
      // Event listener para abrir el modal y capturar el id del acuerdo
      var modalEditarEstado = document.getElementById('modalEditarEstadoAcuerdo');
      if (modalEditarEstado) {
          modalEditarEstado.addEventListener('show.bs.modal', function (event) {
              const button = event.relatedTarget;
              if (button && button.getAttribute) {
                  acuerdoIdParaEstado = button.getAttribute('data-id-acuerdo');
                  console.log('Modal abierto para acuerdo:', acuerdoIdParaEstado);
              } else {
                  acuerdoIdParaEstado = null;
                  console.warn('No se pudo obtener el id del acuerdo');
              }
          });
      }
      // Event listener para guardar estado de acuerdo
      var btnGuardarEstado = document.getElementById('btn-guardar-estado-acuerdo');
      if (btnGuardarEstado) {
          btnGuardarEstado.addEventListener('click', function() {
              const estadoNuevo = document.getElementById('editar-estado-acuerdo').value;
              console.log('Guardar estado acuerdo:', acuerdoIdParaEstado, estadoNuevo);
              if (!acuerdoIdParaEstado || !estadoNuevo) {
                  alert('No se pudo obtener el id del acuerdo o el estado');
                  return;
              }
              fetch('/app/core/update-acuerdo-estado.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                  body: `id_acuerdo=${encodeURIComponent(acuerdoIdParaEstado)}&estado=${encodeURIComponent(estadoNuevo)}`
              })
              .then(res => res.json())
              .then(resp => {
                  if (resp.success) {
                      var bsModal = bootstrap.Modal.getInstance(modalEditarEstado) || new bootstrap.Modal(modalEditarEstado);
                      bsModal.hide();
                      cargarMinuta();
                  } else {
                      alert('Error al actualizar estado: ' + (resp.error || ''));
                  }
              })
              .catch(() => alert('Error al actualizar estado'));
          });
      }
      // Botón Editar Minuta: abrir modal y llenar datos
      document.getElementById('btn-editar-minuta').addEventListener('click', function() {
          const id = getMinutaId();
          fetch(`/app/core/get-minuta.php?id_minuta=${id}`)
              .then(res => res.json())
              .then(data => {
                  document.getElementById('edit-minuta-lugar').value = data.lugar || data.lugar_reunion || '';
                  document.getElementById('edit-minuta-fecha').value = (data.fecha || data.fecha_reunion || '').split(' ')[0];
                  document.getElementById('edit-minuta-hora').value = (data.hora || data.hora_inicio || '').substring(0,5);
                  // Cargar responsables y clientes en los selects
                  fetch('/app/core/minuta-selects.php')
                      .then(res => res.json())
                      .then(selectsData => {
                          // Responsables
                          const selectResp = document.getElementById('edit-minuta-responsable');
                          selectResp.innerHTML = '<option value="">Selecciona responsable</option>';
                          if (selectsData.colaboradores) {
                              selectsData.colaboradores.forEach(col => {
                                  const opt = document.createElement('option');
                                  opt.value = col.id_colab;
                                  opt.textContent = col.nombre + ' ' + col.apellidos;
                                  if (col.id_colab == data.idresponsable) opt.selected = true;
                                  selectResp.appendChild(opt);
                              });
                          }
                          // Clientes
                          const selectCliente = document.getElementById('edit-minuta-cliente');
                          selectCliente.innerHTML = '<option value="">Selecciona cliente</option>';
                          if (selectsData.clientes) {
                              selectsData.clientes.forEach(cli => {
                                  const opt = document.createElement('option');
                                  opt.value = cli.id_cliente;
                                  opt.textContent = cli.razon_social;
                                  if (cli.id_cliente == data.idcliente) opt.selected = true;
                                  selectCliente.appendChild(opt);
                              });
                          }
                      });
                  document.getElementById('edit-minuta-asunto').value = data.objetivo || data.asunto || data.tema || '';
                  var modal = document.getElementById('modalEditarMinuta');
                  var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                  bsModal.show();
              });
      });

      // Guardar cambios de la minuta
      document.getElementById('btn-guardar-editar-minuta').addEventListener('click', function() {
          const id = getMinutaId();
          const form = document.getElementById('form-editar-minuta');
          const data = {
              id_minuta: id,
              lugar: form.lugar.value,
              fecha: form.fecha.value,
              hora: form.hora.value,
              idresponsable: form.responsable.value,
              cliente: form.cliente.value, // ahora es el ID
              asunto: form.asunto.value
          };
          fetch('/app/core/update-minuta.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify(data)
          })
          .then(res => res.json())
          .then(resp => {
              if (resp.success) {
                  var modal = document.getElementById('modalEditarMinuta');
                  var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                  bsModal.hide();
                  cargarMinuta();
              } else {
                  alert('Error al guardar cambios: ' + (resp.error || ''));
              }
          })
          .catch(() => alert('Error al guardar cambios'));
      });
      // Estilo global para asegurar que los detalles de acuerdos se muestren correctamente
      const style = document.createElement('style');
      style.innerHTML = '.acuerdo-detalle-row { display: none; } .acuerdo-detalle-row.mostrar { display: table-row !important; }';
      document.head.appendChild(style);
      // Botón cancelar tema
      document.querySelector('.btn-cancelar-tema').addEventListener('click', function() {
          var modal = document.getElementById('modalAgregarTema');
          var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
          bsModal.hide();
      });
      // Botón cancelar acuerdo
      document.querySelector('.btn-cancelar-acuerdo').addEventListener('click', function() {
          var modalA = document.getElementById('modalAgregarAcuerdo');
          var bsModalA = bootstrap.Modal.getInstance(modalA) || new bootstrap.Modal(modalA);
          bsModalA.hide();
          // Limpiar el temaId del formulario al cerrar el modal
          document.getElementById('form-agregar-acuerdo').removeAttribute('data-tema-id');
      });
      // Cargar responsables en el select de acuerdo
      function cargarResponsables() {
          fetch('/app/core/minuta-selects.php')
              .then(res => res.json())
              .then(data => {
                  const select = document.getElementById('acuerdo-responsable');
                  if (!select) return;
                  select.innerHTML = '<option value="">Selecciona responsable</option>';
                  if (data.colaboradores) {
                      data.colaboradores.forEach(col => {
                          const opt = document.createElement('option');
                          opt.value = col.id_colab;
                          opt.textContent = col.nombre + ' ' + col.apellidos;
                          select.appendChild(opt);
                      });
                  }
              })
              .catch(err => {
                  console.error('Error cargando responsables:', err);
              });
      }
      cargarResponsables();
      function getMinutaId() {
          const params = new URLSearchParams(window.location.search);
          return params.get('id_minuta');
      }

      function renderMinuta(data) {
          document.getElementById('minuta-lugar').textContent = data.lugar || data.lugar_reunion || '';
          document.getElementById('minuta-fecha').textContent = data.fecha || data.fecha_reunion || '';
          document.getElementById('minuta-responsable').textContent = data.responsableNombre || '';
          document.getElementById('minuta-hora').textContent = data.hora || data.hora_inicio || '';
          document.getElementById('minuta-cliente').textContent = data.clienteNombre || '';
          document.getElementById('minuta-asunto').textContent = data.objetivo || data.asunto || data.tema || '';
      }

      function renderTemas(temas, acuerdosPorTema) {
          const tbody = document.querySelector('#tabla-temas tbody');
          tbody.innerHTML = '';
          if (Array.isArray(temas)) {
              temas.forEach((tema, idx) => {
                  // Log para depuración: mostrar el tema y su id_tema
                  console.log('Renderizando tema:', tema);
                  const tr = document.createElement('tr');
                  tr.classList.add('tema-row');
                  tr.setAttribute('data-tema-idx', idx);
                  tr.setAttribute('data-tema-id', tema.id);
                  tr.innerHTML = `
                      <td>${tema.titulo || tema.tema || ''}</td>
                      <td>${tema.descripcion || ''}</td>
                      <td>${tema.observaciones || ''}</td>
                  `;
                  tbody.appendChild(tr);

                  // Acuerdos expandibles
                  const acuerdos = acuerdosPorTema && acuerdosPorTema[tema.id] ? acuerdosPorTema[tema.id] : [];
                  const trAcuerdos = document.createElement('tr');
                  trAcuerdos.classList.add('expandable-body');
                  trAcuerdos.style.display = 'none';
                  // Usar tema.id para el botón y elementos relacionados
                  let temaIdBtn = tema.id ? tema.id : '';
                  if (!temaIdBtn) {
                      console.warn('El tema no tiene id:', tema);
                  }
                  let acuerdosHtml = `<td colspan="3">
                      <div style="margin-bottom:10px;">
                          <strong>Acuerdos:</strong>
                          <div class="acuerdos-list" id="acuerdos-list-${temaIdBtn}">
                              ${acuerdos.map((acuerdo, i) => {
                                  const descripcion = acuerdo.descripcion && acuerdo.descripcion.trim() ? acuerdo.descripcion : 'Sin datos';
                                  const previewLength = 40; // Cambia este valor para mostrar más o menos caracteres
                                  const resumen = descripcion.length > previewLength ? descripcion.substring(0, previewLength) + '...' : descripcion;
                                  const responsable = acuerdo.responsable && acuerdo.responsable.trim() ? acuerdo.responsable : 'Sin datos';
                                  const fechaLimite = acuerdo.fecha_limite && acuerdo.fecha_limite.trim() ? acuerdo.fecha_limite : 'Sin datos';
                                  const detalleId = `acuerdo-detalle-${temaIdBtn}-${i}`;
                                  return `
                                      <div class="card mb-2 acuerdo-card">
                                          <div class="card-header d-flex justify-content-between align-items-center">
                                              <span>
                                                  <a href="#" class="acuerdo-toggle" data-bs-toggle="collapse" data-bs-target="#${detalleId}" style="font-weight:bold; text-decoration:none;">${resumen}</a>
                                              </span>
                                              <span class="badge ${getEstadoBadgeClass(acuerdo.estado)} ms-2">${acuerdo.estado || 'Pendiente'}</span>
                                              <button class="btn btn-sm btn-outline-secondary ms-2 btn-editar-estado" data-id-acuerdo="${acuerdo.id}" data-bs-toggle="modal" data-bs-target="#modalEditarEstadoAcuerdo"><i class="fas fa-edit"></i></button>
                                          </div>
                                          <div id="${detalleId}" class="collapse card-body bg-light">
                                              <strong>Descripción completa:</strong> ${descripcion}<br>
                                              <strong>Responsable:</strong> <span class="badge bg-info text-dark ms-2">${responsable}</span><br>
                                              <strong>Fecha límite:</strong> <span class='badge bg-warning text-dark'>${fechaLimite}</span><br>
                                              <strong>Estado:</strong> <span class="badge ${getEstadoBadgeClass(acuerdo.estado)} ms-2">${acuerdo.estado || 'Pendiente'}</span>
                                              <button class="btn btn-sm btn-outline-secondary ms-2 btn-editar-estado" data-id-acuerdo="${acuerdo.id}" data-bs-toggle="modal" data-bs-target="#modalEditarEstadoAcuerdo"><i class="fas fa-edit"></i></button><br>
                                          </div>
                                      </div>
                                  `;


  let acuerdoIdParaEstado = null;
  document.getElementById('modalEditarEstadoAcuerdo').addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      acuerdoIdParaEstado = button.getAttribute('data-id-acuerdo');
  });
  document.getElementById('btn-guardar-estado-acuerdo').addEventListener('click', function() {
      const estadoNuevo = document.getElementById('editar-estado-acuerdo').value;
      console.log('Guardar estado acuerdo:', acuerdoIdParaEstado, estadoNuevo);
      if (!acuerdoIdParaEstado || !estadoNuevo) return;
      fetch('/app/core/update-acuerdo-estado.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id_acuerdo=${encodeURIComponent(acuerdoIdParaEstado)}&estado=${encodeURIComponent(estadoNuevo)}`
      })
          .then(res => res.json())
          .then(resp => {
                  if (resp.success) {
                          var modal = document.getElementById('modalEditarEstadoAcuerdo');
                          var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                          bsModal.hide();
                          cargarMinuta();
                  } else {
                          alert('Error al actualizar estado: ' + (resp.error || ''));
                  }
          })
          .catch(() => alert('Error al actualizar estado'));
  });
      // Función para asignar clase de color según estado
  function getEstadoBadgeClass(estado) {
          switch (estado) {
              case 'Pendiente': return 'bg-secondary';
              case 'En proceso': return 'bg-primary';
              case 'Concluido': return 'bg-success';
              case 'Vencido': return 'bg-danger';
              case 'Cancelado': return 'bg-dark';
              default: return 'bg-secondary';
          }
      }
                              }).join('')}
                          </div>
                          <button class="btn btn-primary btn-sm btn-agregar-acuerdo" data-tema-id="${temaIdBtn}" style="margin-top:10px;"><i class="fas fa-plus"></i> Agregar acuerdo</button>
                      </div>
                  </td>`;
                  trAcuerdos.innerHTML = acuerdosHtml;
                  tbody.appendChild(trAcuerdos);
              });
          }
          // No registrar el evento aquí, se registra globalmente al cargar el DOM
      // Evento para mostrar/ocultar detalles de acuerdo tipo acordeón (delegación sobre #tabla-temas)
  document.getElementById('tabla-temas').addEventListener('click', function(e) {
          const toggle = e.target.closest('.acuerdo-toggle');
          if (toggle) {
              e.stopPropagation();
              const tr = toggle.closest('tr.acuerdo-row');
              if (tr && tr.nextElementSibling && tr.nextElementSibling.classList.contains('acuerdo-detalle-row')) {
                  const detalleTr = tr.nextElementSibling;
                  detalleTr.classList.toggle('mostrar');
              }
          }
      });
      }

      // Mostrar/ocultar acuerdos al hacer clic en tema
      document.querySelector('#tabla-temas').addEventListener('click', function(e) {
          const tr = e.target.closest('tr.tema-row');
          if (tr) {
              const nextTr = tr.nextElementSibling;
              if (nextTr && nextTr.classList.contains('expandable-body')) {
                  nextTr.style.display = nextTr.style.display === 'none' ? '' : 'none';
              }
          }
          // Botón agregar acuerdo: usar closest para obtener el botón aunque el click sea en el ícono o texto
          const btn = e.target.closest('.btn-agregar-acuerdo');
          if (btn) {
              // Validar que el botón tenga el atributo data-tema-id
              const temaId = btn.getAttribute('data-tema-id');
              if (!temaId) {
                  console.warn('El botón Agregar acuerdo no tiene data-tema-id:', btn);
                  alert('Error: No se pudo obtener el tema para el acuerdo.');
                  return;
              }
              var modalA = document.getElementById('modalAgregarAcuerdo');
              var bsModalA = bootstrap.Modal.getInstance(modalA) || new bootstrap.Modal(modalA);
              bsModalA.show();
              console.log('Asignando temaId al modal:', temaId);
              document.getElementById('form-agregar-acuerdo').setAttribute('data-tema-id', temaId);
          }
      });

      // Abrir modal agregar tema
      document.getElementById('btn-agregar-tema').addEventListener('click', function() {
          var modal = document.getElementById('modalAgregarTema');
          var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
          bsModal.show();
      });

      // Guardar tema en la BD
      document.getElementById('btn-guardar-tema').addEventListener('click', function() {
          const id_minuta = getMinutaId();
          const titulo = document.getElementById('tema-titulo').value.trim();
          const descripcion = document.getElementById('tema-descripcion').value.trim();
          const observaciones = document.getElementById('tema-observaciones').value.trim();
          if (!id_minuta || !titulo || !descripcion) {
              alert('Completa los campos obligatorios');
              return;
          }
          fetch('/app/core/agregar-tema.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `id_minuta=${encodeURIComponent(id_minuta)}&titulo=${encodeURIComponent(titulo)}&descripcion=${encodeURIComponent(descripcion)}&observaciones=${encodeURIComponent(observaciones)}`
          })
          .then(res => res.json())
          .then(resp => {
              if (resp.success) {
                  var modal = document.getElementById('modalAgregarTema');
                  var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                  bsModal.hide();
                  document.getElementById('tema-titulo').value = '';
                  document.getElementById('tema-descripcion').value = '';
                  document.getElementById('tema-observaciones').value = '';
                  // Recargar los datos de la minuta para mostrar el nuevo tema
                  cargarMinuta();
              } else {
                  alert('Error al guardar tema: ' + (resp.error || '')); 
              }
          })
          .catch(err => {
              alert('Error al guardar tema');
          });
      });

      // Guardar acuerdo en la BD
      document.getElementById('btn-guardar-acuerdo').addEventListener('click', function() {
          const btnGuardar = this;
          btnGuardar.disabled = true;
      const id_minuta = getMinutaId();
      const descripcion = document.getElementById('acuerdo-descripcion').value.trim();
      const idresponsable = document.getElementById('acuerdo-responsable').value.trim();
      const fecha_limite = document.getElementById('acuerdo-fecha').value;
      const estado = document.getElementById('acuerdo-estado').value;
      const temaId = document.getElementById('form-agregar-acuerdo').getAttribute('data-tema-id');
          if (!temaId) {
              alert('Error: No se ha seleccionado un tema para el acuerdo.');
              btnGuardar.disabled = false;
              return;
          }
          if (!id_minuta || !descripcion) {
              alert('Completa los campos obligatorios');
              btnGuardar.disabled = false;
              return;
          }
          fetch('/app/core/agregar-acuerdo.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `id_minuta=${encodeURIComponent(id_minuta)}&id_tema=${encodeURIComponent(temaId)}&descripcion=${encodeURIComponent(descripcion)}&idresponsable=${encodeURIComponent(idresponsable)}&fecha_limite=${encodeURIComponent(fecha_limite)}&estado=${encodeURIComponent(estado)}`
          })
          .then(res => res.json())
          .then(resp => {
              btnGuardar.disabled = false;
              if (resp.success) {
                  var modalA = document.getElementById('modalAgregarAcuerdo');
                  var bsModalA = bootstrap.Modal.getInstance(modalA) || new bootstrap.Modal(modalA);
                  bsModalA.hide();
                  document.getElementById('acuerdo-descripcion').value = '';
                  document.getElementById('acuerdo-responsable').value = '';
                  document.getElementById('acuerdo-fecha').value = '';
                  cargarMinuta();
              } else {
                  alert('Error al guardar acuerdo: ' + (resp.error || ''));
              }
          })
          .catch(err => {
              btnGuardar.disabled = false;
              alert('Error al guardar acuerdo');
          });
      });

      // Recargar datos de la minuta (para actualizar temas/acuerdos)
      function cargarMinuta() {
          const id = getMinutaId();
          if (id) {
              fetch(`/app/core/get-minuta.php?id_minuta=${id}`)
                  .then(res => res.json())
                  .then((data) => {
                      renderMinuta(data);
                      // Agrupar acuerdos por tema
                      const acuerdosPorTema = {};
                      if (Array.isArray(data.acuerdos)) {
                          data.acuerdos.forEach(acuerdo => {
                              if (!acuerdosPorTema[acuerdo.id_tema]) acuerdosPorTema[acuerdo.id_tema] = [];
                              acuerdosPorTema[acuerdo.id_tema].push(acuerdo);
                          });
                      }
                      renderTemas(data.temas || [], acuerdosPorTema);
                  })
                  .catch(err => {
                      console.error('Error al cargar la minuta:', err);
                  });
          }
      }

      // Inicialmente cargar la minuta
      cargarMinuta();

      // Cargar datos de la minuta
      const id = getMinutaId();
      console.log('ID de minuta:', id); // Debug: ver si el id se obtiene
      if (id) {
          fetch(`/app/core/get-minuta.php?id_minuta=${id}`)
              .then(res => res.json())
              .then((data) => {
                  console.log('Respuesta get-minuta:', data); // Debug
                  renderMinuta(data);
                  // Agrupar acuerdos por tema
                  const acuerdosPorTema = {};
                  if (Array.isArray(data.acuerdos)) {
                      data.acuerdos.forEach(acuerdo => {
                          if (!acuerdosPorTema[acuerdo.id_tema]) acuerdosPorTema[acuerdo.id_tema] = [];
                          acuerdosPorTema[acuerdo.id_tema].push(acuerdo);
                      });
                  }
                  renderTemas(data.temas || [], acuerdosPorTema);
              })
              .catch(err => {
                  console.error('Error al cargar la minuta:', err);
              });
      } else {
          console.warn('No se encontró el parámetro id en la URL');
      }
  });
  </script>

<?php endif; ?>



<script>
  // Validación para el formulario de reset-password
  document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('resetForm');
    if (form) {
      form.addEventListener('submit', function(e) {
        var password = document.getElementById('password').value;
        var confirm = document.getElementById('confirm_password').value;
        var msgDiv = document.getElementById('reset-msg');
        msgDiv.innerHTML = '';
        // Validación avanzada de requisitos
        var requisitos = [];
        if (password.length < 8) requisitos.push('mínimo 8 caracteres');
        if (!/[A-Z]/.test(password)) requisitos.push('una mayúscula');
        if (!/[a-z]/.test(password)) requisitos.push('una minúscula');
        if (!/[0-9]/.test(password)) requisitos.push('un número');
        if (!/[;:\.,!¡\?¿@#\$%\^&\-_+=\(\)\[\]\{\}]/.test(password)) requisitos.push('un carácter especial');
        if (requisitos.length > 0) {
          e.preventDefault();
          msgDiv.innerHTML = '<div class="alert alert-danger">La contraseña no cumple con los requisitos: ' + requisitos.join(', ') + '.</div>';
          return;
        }
        if (password !== confirm) {
          e.preventDefault();
          msgDiv.innerHTML = '<div class="alert alert-danger">Las contraseñas no coinciden.</div>';
          return;
        }
      });
    }
  });

  // Función global para mostrar/ocultar contraseña
  window.togglePassword = function(id, btn) {
    var input = document.getElementById(id);
    if (!input) return;
    if (input.type === 'password') {
      input.type = 'text';
      btn.innerHTML = '<span class="bi bi-eye-slash"></span>';
    } else {
      input.type = 'password';
      btn.innerHTML = '<span class="bi bi-eye"></span>';
    }
  }
</script>





<!-- Script para cargar-cfdis, mostrar tabla, convertir a pdf y descarga masiva sat-->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'cargar-facturas' || (isset($_GET['pg']) && $_GET['pg'] === 'cargar-facturas')): ?>
  <script>
    const xmlInput = document.getElementById('xmlFile');
    const zipInput = document.getElementById('zipFile');
    const cfdiModalEl = document.getElementById('cfdiModal');
    let cfdiModal = null;
    if (cfdiModalEl) {
      cfdiModal = new bootstrap.Modal(cfdiModalEl);
    }
    const cfdiReviewBody = document.getElementById('cfdiReviewBody');
    const cfdiParseErrors = document.getElementById('cfdiParseErrors');

    async function enviarArchivosParse() {
      const fd = new FormData();
      if (xmlInput && xmlInput.files.length > 0) {
        fd.append('xmlFile', xmlInput.files[0]);
      }
      if (zipInput && zipInput.files.length > 0) {
        fd.append('zipFile', zipInput.files[0]);
      }
      if (!fd.has('xmlFile') && !fd.has('zipFile')) {
        alert('Selecciona un XML o un ZIP primero.');
        return;
      }

      try {
        const res = await fetch('core/cargar-xml.php', {
          method: 'POST',
          body: fd
        });

        const text = await res.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error("Respuesta cruda del servidor:", text);
          alert("El servidor devolvió un error. Revisa la consola (F12).");
          return;
        }

        if (!data.success && (!data.parsed || data.parsed.length === 0)) {
          cfdiParseErrors.innerText = data.message || JSON.stringify(data.errors || []);
          return;
        }

        // limpiar tabla
        cfdiReviewBody.innerHTML = '';
        cfdiParseErrors.innerText = '';

        // llenar filas
        data.parsed.forEach((item, idx) => {
          const tr = document.createElement('tr');
          const chk = document.createElement('input');
          chk.type = 'checkbox';
          chk.checked = true;
          chk.dataset.tmp = item._tmp_file;
          chk.dataset.index = idx;

          tr.innerHTML = `
        <td></td>
        <td>${idx+1}</td>
        <td>${item.uuid || ''}</td>
        <td>${item.fecha || ''}</td>
        <td>${item.emisor_rfc || ''}</td>
        <td>${item.receptor_rfc || ''}</td>
        <td>${item.subtotal || ''}</td>
        <td>${item.total || ''}</td>
        <td>${item.serie || ''}</td>
        <td>${item.folio || ''}</td>
        <td>${item.uuid ? '<span class="badge bg-success">UUID OK</span>' : '<span class="badge bg-warning">UUID faltante</span>'}</td>
      `;
          tr.children[0].appendChild(chk);
          tr.dataset.item = JSON.stringify(item);
          cfdiReviewBody.appendChild(tr);
        });

        if (data.errors && data.errors.length) {
          cfdiParseErrors.innerText = data.errors.join(' | ');
        }

        if (cfdiModal) {
          cfdiModal.show();
        }

      } catch (err) {
        console.error(err);
        alert('Error al enviar archivos: ' + err.message);
      }
    }

    const btnOpenModal = document.querySelector('button[data-bs-target="#cfdiModal"]');
    if (btnOpenModal) {
      btnOpenModal.addEventListener('click', (e) => {
        e.preventDefault();
        enviarArchivosParse();
      });
    }

    const btnUploadZip = document.querySelector('form#form-manual button[data-bs-target="#cfdiModal"]');
    if (btnUploadZip) {
      btnUploadZip.addEventListener('click', (e) => {
        e.preventDefault();
        enviarArchivosParse();
      });
    }

    // Confirmar guardado
    const btnConfirm = document.createElement('button');
    btnConfirm.className = 'btn btn-primary';
    btnConfirm.innerText = 'Registrar facturas';
    btnConfirm.addEventListener('click', async () => {
      const rows = Array.from(cfdiReviewBody.querySelectorAll('tr'));
      const items = [];
      for (const r of rows) {
        const chk = r.querySelector('input[type=checkbox]');
        if (chk && chk.checked) {
          const obj = JSON.parse(r.dataset.item);
          obj._tmp_file = chk.dataset.tmp;
          items.push(obj);
        }
      }

      if (items.length === 0) {
        alert('Selecciona al menos una factura para registrar.');
        return;
      }

      const uuidRegex = /^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/;
      for (const it of items) {
        if (!it.uuid || !uuidRegex.test(it.uuid)) {
          if (!confirm(`La factura con UUID "${it.uuid || '(vacío)'}" no tiene formato válido. ¿Deseas continuar?`)) {
            return;
          } else {
            break;
          }
        }
      }

      try {
        const res = await fetch('core/guardar-facturas.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            items
          })
        });

        const text = await res.text();
        let data;
        try {
          data = JSON.parse(text);
        } catch (e) {
          console.error(" Respuesta cruda del servidor (guardar):", text);
          alert("El servidor devolvió un error al guardar. Revisa la consola.");
          return;
        }

        if (data.success) {
          alert('Facturas guardadas: ' + (data.inserted || []).length);
          location.reload();
        } else {
          alert('Error guardando: ' + JSON.stringify(data.errors || data.message));
        }
      } catch (err) {
        console.error(err);
        alert('Error al guardar: ' + err.message);
      }
    });

    const modalFooter = cfdiModalEl.querySelector('.modal-footer');
    if (modalFooter) modalFooter.appendChild(btnConfirm);

    document.addEventListener('DOMContentLoaded', function() {
      const viewFilesModal = document.getElementById('viewFilesModal');
      if (viewFilesModal) {
        viewFilesModal.addEventListener('show.bs.modal', function(event) {
          const row = event.relatedTarget;

          // Extraer información de los atributos data
          const pdfPath = row.getAttribute('data-pdf-path');
          const xmlPath = row.getAttribute('data-xml-path');
          const uuid = row.getAttribute('data-uuid');

          const modalTitle = viewFilesModal.querySelector('.modal-title');
          modalTitle.textContent = 'Archivos de Factura: ' + uuid;

          const pdfViewer = document.getElementById('pdf-viewer');
          pdfViewer.src = pdfPath;

          // Cargar y mostrar el contenido del XML
          const xmlViewer = document.getElementById('xml-viewer');
          xmlViewer.textContent = 'Cargando XML...';

          fetch(xmlPath)
            .then(response => {
              if (!response.ok) {
                throw new Error('No se pudo cargar el archivo XML. Código de estado: ' + response.status);
              }
              return response.text();
            })
            .then(data => {
              const escapedXml = data.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
              xmlViewer.innerHTML = `<code class="language-xml">${escapedXml}</code>`;
            })
            .catch(error => {
              xmlViewer.textContent = 'Error al cargar el archivo XML. Verifique que la ruta sea correcta: ' + xmlPath;
              console.error('Error en fetch:', error);
            });

          const pdfTab = document.querySelector('#pdf-tab');
          if (pdfTab) {
            const tab = new bootstrap.Tab(pdfTab);
            tab.show();
          }
        });

        viewFilesModal.addEventListener('hidden.bs.modal', function() {
          const pdfViewer = document.getElementById('pdf-viewer');
          pdfViewer.src = 'about:blank';
        });
      }
    });

    //------------------------------------------------------------------------------------------------------
    // descargar facturas desde sat 
    document.addEventListener('DOMContentLoaded', () => {
      const modalSat = new bootstrap.Modal(document.getElementById('modalSAT'));
      const modalDescarga = new bootstrap.Modal(document.getElementById('modalDescarga'));
      const formAutenticacion = document.getElementById('form-autenticacion-efirma');
      const formDescarga = document.getElementById('form-descarga-sat');
      const rfcInput = formDescarga.querySelector('input[name="rfc"]');
      let autenticadoRFC = null;

      // ---- AUTENTICAR EFIRMA ----
      formAutenticacion.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(formAutenticacion);

        Swal.fire({
          title: 'Autenticando...',
          text: 'Por favor, espere mientras validamos su e.firma.',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });

        try {
          const response = await fetch('core/autenticar-sat.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();
          if (!result.success) {
            throw new Error(result.message);
          }

          autenticadoRFC = result.rfc;
          rfcInput.value = autenticadoRFC; // Asignamos el RFC al campo del formulario de descarga

          Swal.fire('¡Éxito!', `Autenticado correctamente para el RFC: ${autenticadoRFC}`, 'success');

          modalSat.hide();
          modalDescarga.show();
        } catch (error) {
          Swal.fire('Error', error.message, 'error');
        }
      });

      formDescarga.addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = {
          tipo_descarga: formDescarga.querySelector('select[name="tipo_descarga"]').value.trim(),
          rfc: formDescarga.querySelector('input[name="rfc"]').value.trim(),
          fecha_inicio: formDescarga.querySelector('input[name="fecha_inicio"]').value,
          fecha_fin: formDescarga.querySelector('input[name="fecha_fin"]').value
        };

        // Validaciones de fechas
        if (!data.fecha_inicio || !data.fecha_fin) {
          Swal.fire('Atención', 'Debe seleccionar una fecha de inicio y fin.', 'warning');
          return;
        }

        const fechaInicio = new Date(data.fecha_inicio);
        const fechaFin = new Date(data.fecha_fin);

        if (fechaInicio >= fechaFin) {
          Swal.fire('Rango inválido', 'La fecha de inicio debe ser menor que la fecha final.', 'warning');
          return;
        }

        const diffTime = Math.abs(fechaFin - fechaInicio);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 31) {
          Swal.fire({
            icon: 'warning',
            title: 'Rango muy amplio',
            html: `El rango de <strong>${diffDays} días</strong> es demasiado amplio.<br>Use rangos de máximo 31 días.`,
            confirmButtonText: 'Entendido'
          });
          return;
        }
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0); 
        if (fechaInicio > hoy || fechaFin > hoy) {
          Swal.fire('Fecha inválida', 'Las fechas no pueden ser futuras.', 'warning');
          return;
        }

        try {
          Swal.fire({
            title: 'Enviando Solicitud...',
            html: `Conectando con el SAT...<br><small>Rango: ${data.fecha_inicio} al ${data.fecha_fin}</small>`,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });

          console.log('Enviando los siguientes datos:', data); // Para depuración

          const response = await fetch('core/solicitar-descarga.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
          });

          const result = await response.json();

          if (!result.success) {
            throw new Error(result.message);
          }

          Swal.fire({
            icon: 'success',
            title: 'Solicitud enviada correctamente',
            html: `
                <p>El SAT ha recibido la solicitud.</p>
                <p><b>El SAT puede tardar en procesarla.</b></p>
                <p>La solicitud se registró en la lista y se actualizará automáticamente cuando haya cambios.</p>
                `,
            confirmButtonText: 'Entendido'
          }).then(() => {
            modalDescarga.hide();
            if (typeof cargarSolicitudes === 'function') {
              cargarSolicitudes();
            } else {
              location.reload();
            }
          });

        } catch (error) {
          Swal.fire('Error', error.message, 'error');
        }
      });
    });

    //contador de dias
    document.addEventListener('DOMContentLoaded', function() {
      const fechaInicio = document.getElementById('fecha_inicio');
      const fechaFin = document.getElementById('fecha_fin');
      const diasRango = document.getElementById('dias-rango');

      function actualizarDiasRango() {
        const inicio = fechaInicio.value;
        const fin = fechaFin.value;
        if (inicio && fin) {
          const dateInicio = new Date(inicio);
          const dateFin = new Date(fin);
          if (dateFin >= dateInicio) {
            const diffDays = Math.ceil((dateFin - dateInicio) / (1000 * 60 * 60 * 24)) + 1;
            diasRango.textContent = `${diffDays} día${diffDays === 1 ? '' : 's'} seleccionados`;
          } else {
            diasRango.textContent = 'Rango inválido';
          }
        } else {
          diasRango.textContent = '0 días seleccionados';
        }
      }

      fechaInicio.addEventListener('change', actualizarDiasRango);
      fechaFin.addEventListener('change', actualizarDiasRango);
    });
    //------------------------------------------------------------------------------------------------------
    //paginacion
    document.addEventListener("DOMContentLoaded", () => {
      cargarFacturas(1);
      document.getElementById("facturas-cargadas").addEventListener("click", (e) => {
        if (e.target.matches(".page-link")) {
          e.preventDefault();
          const pagina = e.target.dataset.page;
          if (pagina) cargarFacturas(pagina);
        }
      });
    });

    function cargarFacturas(pagina) {
      fetch(`core/listar-facturas.php?pagina=${pagina}`)
        .then((res) => res.text())
        .then((html) => {
          document.getElementById("facturas-cargadas").innerHTML = html;
        })
        .catch((err) => {
          console.error("Error cargando facturas:", err);
        });
    }
  </script>
<?php endif; ?>

<!-- Script para ver-peticiones, verificar, descargar y procesar paquetes SAT -->
<?php if (basename($_SERVER['REQUEST_URI'], '.php') === 'ver-peticiones' || (isset($_GET['pg']) && $_GET['pg'] === 'ver-peticiones')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      console.log(' Inicializando sistema de peticiones v3.2 (Unificado y Corregido)...');

      const tablaPeticionesBody = document.querySelector('#tbody-solicitudes');

      if (!tablaPeticionesBody) {
        console.error(' No se encontró el cuerpo de la tabla (tbody) con id="tbody-solicitudes". Los botones no funcionarán.');
        return;
      }

      tablaPeticionesBody.addEventListener('click', async function(event) {

        const botonClickeado = event.target.closest('a.btn, button.btn');

        if (!botonClickeado || !botonClickeado.dataset.id) {
        }

        event.preventDefault();
        const idSolicitud = botonClickeado.dataset.id;

        if (botonClickeado.disabled) {
          return;
        }

        if (botonClickeado.classList.contains('btn-verificar-individual')) {
          const iconoOriginal = botonClickeado.innerHTML;

          botonClickeado.disabled = true;
          botonClickeado.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

          try {
            const response = await fetch('core/verificar-descarga.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                id_solicitud: idSolicitud
              })
            });
            const text = await response.text();
            console.log('Respuesta cruda:', text);
            let result;
            try {
              result = JSON.parse(text);
            } catch (e) {
              console.error('Respuesta no es JSON válido');
              Swal.fire('Error', 'Respuesta no válida del servidor (ver consola).', 'error');
              return;
            }

            if (!response.ok || !result.success) {
              throw new Error(result.message || 'Error al iniciar verificación');
            }

            Swal.fire({
              icon: 'info',
              title: 'Verificación Iniciada',
              text: result.message + ' El estado se actualizará automáticamente.',
              timer: 4000
            });

            botonClickeado.disabled = false;
            botonClickeado.innerHTML = iconoOriginal;

            // Recarga la tabla para ver el estado actualizado
            setTimeout(() => location.reload(), 2000);

          } catch (error) {
            Swal.fire('Error', 'No se pudo iniciar la verificación: ' + error.message, 'error');
            botonClickeado.disabled = false;
            botonClickeado.innerHTML = iconoOriginal;
          }
          return;
        }

        // --- Lógica para DESCARGAR ---
        if (botonClickeado.classList.contains('btn-descargar-paquetes')) {
          botonClickeado.disabled = true;
          botonClickeado.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

          Swal.fire({
            title: 'Descargando Paquetes',
            text: 'Conectando con el SAT, esto puede tardar un momento...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });

          try {
            const response = await fetch('core/descargar-paquete-sat.php', { 
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                id_solicitud: idSolicitud
              })
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Error en el servidor');

            Swal.fire('¡Éxito!', result.message, 'success').then(() => location.reload());

          } catch (error) {
            Swal.fire('Error', 'No se pudieron descargar los paquetes: ' + error.message, 'error');
            botonClickeado.disabled = false;
            botonClickeado.innerHTML = '<i class="fas fa-download"></i>';
          }
          return;
        }

        // --- Lógica para PROCESAR ---
        if (botonClickeado.classList.contains('btn-procesar-paquetes')) {
          botonClickeado.disabled = true;
          botonClickeado.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

          Swal.fire({
            title: 'Procesando Facturas',
            text: 'Extrayendo, guardando y generando PDFs. Por favor, espere...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
          });

          try {
            const response = await fetch('core/procesar_paquetes.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                id_solicitud: idSolicitud
              })
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Error desconocido durante el procesamiento.');

            Swal.fire({
              icon: 'success',
              title: '¡Proceso Completado!',
              text: result.message,
            }).then(() => {
              location.reload();
            });

          } catch (error) {
            Swal.fire('Error', 'No se pudieron procesar los paquetes: ' + error.message, 'error');
            botonClickeado.disabled = false;
            botonClickeado.innerHTML = '<i class="fas fa-cogs"></i>';
          }
          return;
        }
      });

      console.log(' Sistema de peticiones v3.2 listo y unificado.');
    });
    $(document).ready(function() {
      function updateDisplayIds() {
        $('#tbody-solicitudes tr').each(function(index) {
          $(this).find('td:first').text(index + 1);
        });
      }

      $('#tbody-solicitudes').on('click', '.btn-eliminar-solicitud', function(e) {
        e.preventDefault();
        const idSolicitud = $(this).data('id');
        const row = $(this).closest('tr');

        if (confirm('¿Estás seguro de que deseas eliminar la solicitud Rechazada con ID ' + idSolicitud + '? Esta acción es irreversible y eliminará los archivos de paquete asociados.')) {
          $.ajax({
            url: 'core/eliminar-solicitud.php',
            type: 'POST',
            dataType: 'json',
            data: {
              id: idSolicitud
            },
            beforeSend: function() {
              row.find('button, a').prop('disabled', true).addClass('disabled');
              row.css('opacity', 0.5);
            },
            success: function(response) {
              if (response.success) {
                alert('Éxito: ' + response.message);

                row.remove();

                // Reordenar los id de visualización
                updateDisplayIds();

              } else {
                alert('Error al eliminar: ' + response.message);
              }
            },
            error: function(xhr, status, error) {
              alert('Error de comunicación con el servidor. Por favor, revisa la consola (F12) para más detalles.');
              console.error('Error AJAX:', status, error, xhr.responseText);
            },
            complete: function() {
              if (row.length && row.parent().length) {
                row.find('button, a').prop('disabled', false).removeClass('disabled');
                row.css('opacity', 1);
              }
            }
          });
        }
      });
    });
  </script>
<?php endif; ?>




</body>
<!--end::Body-->
</html>
