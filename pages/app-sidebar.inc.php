<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <?php
    // Incluir el sistema de permisos
    include_once 'core/permisos-menu.php';
    // Generar y mostrar el CSS y JavaScript de permisos
    echo generarSistemaPermisos();
    ?>
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="localhost/app" class="brand-link">
            <!--begin::Brand Image-->
            <!--<img src="../assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />-->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light"><?php echo SYSNAME.' '.VERSION;?></span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->

    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-item" data-id-opcion="1" data-padre="0">
                    <a href="panel?pg=dashboard" class="nav-link">
                        <i class="nav-icon bi bi-house-door"></i>
                        <p>
                            Dashboard
                            <i class="#"></i>
                        </p>
                        </a>
                    <ul class="nav nav-treeview">
                    </ul>
                </li>
                <!--end::menu--> 

                <!--begin::Modulo Planeación -->
                <li class="nav-item" data-id-opcion="2" data-padre="0">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-calendar2-week"></i>
                        <p>
                            Planeación
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                                          
                        <li class="nav-item" data-id-opcion="6" data-padre="2">
                            <a href="panel?pg=proyectos-dashboard" class="nav-link">
                                <i class="nav-icon bi bi-diagram-3"></i>
                                <p>Proyectos</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="7" data-padre="2">
                            <a href="panel?pg=visualizar-Tareas" class="nav-link">
                                <i class="nav-icon bi bi-check2-square"></i>
                                <p>Tareas</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="8" data-padre="2">
                            <a href="panel?pg=minuta" class="nav-link">
                                <i class="nav-icon bi bi-person-circle"></i>
                                <p>Minutas</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="9" data-padre="2">
                            <a href="panel?pg=empleados-config" class="nav-link">
                                <i class="nav-icon bi bi-person-lines-fill"></i>
                                <p>Empleados</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="10" data-padre="2">
                            <a href="panel?pg=equipos" class="nav-link">
                                <i class="nav-icon bi bi-person-lines-fill"></i>
                                <p>Equipos</p>
                            </a>
                        </li>

                        <li class="nav-item" data-id-opcion="11" data-padre="2">
                            <a href="panel?pg=calendario-nuevo" class="nav-link">
                                <i class="nav-icon bi bi-calendar"></i>
                                <p>Calendario</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--end::Modulo Planeación -->
                <li class="nav-item" data-id-opcion="3" data-padre="0">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-archive"></i>
                        <p>
                            Expedientes
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item" data-id-opcion="12" data-padre="3">
                            <a href="panel?pg=expedientesdig" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark"></i>
                                <p>Expedientes digitales</p>
                            </a>
                        </li>
                       
                        <li class="nav-item" data-id-opcion="13" data-padre="3">
                            <a href="panel?pg=exp-notariales" class="nav-link">
                                <i class="nav-icon bi bi-archive"></i>
                                <p>Expedientes notariales</p>
                            </a>
                        </li>

                        <li class="nav-item" data-id-opcion="14" data-padre="3">
                            <a href="panel?pg=categorias" class="nav-link">
                                <i class="nav-icon bi bi-card-list"></i>
                                <p>Categorías</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="15" data-padre="3">
                            <a href="panel?pg=instituciones" class="nav-link">
                                <i class="nav-icon bi bi-building"></i>
                                <p>Instituciones</p>
                            </a>
                        </li>
                       
                    </ul>
                </li>
                <!--begin::Modulo Documentos -->
                <li class="nav-item" data-id-opcion="4" data-padre="0">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-building-gear"></i>
                        <p>
                            Administración
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!--
                        



                        -->
                        <li class="nav-item">
                            <a href="panel?pg=cargar-facturas" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-arrow-down"></i>
                                <p>Cargar facturas</p>
                            </a>
                        </li>

                        <li class="nav-item" data-id-opcion="16" data-padre="4" >
                            <a href="panel?pg=administracion-cfdis" class="nav-link">
                                <i class="nav-icon bi bi-file-binary"></i>
                                <p>Administracion de CFDI's</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="17" data-padre="4">
                            <a href="panel?pg=reporte-cfdis" class="nav-link">
                                <i class="nav-icon bi bi-file-earmark-bar-graph-fill"></i>
                                <p>Reporte de CFDI's</p>
                            </a>
                        </li>
                        <li class="nav-item"  data-padre="4">
                            <a href="panel?pg=comisiones" class="nav-link">
                                <i class="nav-icon bi bi-bank"></i>
                                <p>Comisiones</p>
                            </a>
                        </li>
                        <li class="nav-item"  data-padre="4">
                            <a href="panel?pg=catalogos-productos" class="nav-link">
                                <i class="nav-icon bi bi-dice-4"></i>
                                <p>Productos</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="18" data-padre="4">
                            <a href="panel?pg=clientes" class="nav-link">
                                <i class="nav-icon bi bi-person-circle"></i>
                                <p>Clientes</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="18" data-padre="4">
                            <a href="panel?pg=directorio-empresarial" class="nav-link">
                                <i class="nav-icon bi bi-folder"></i>
                                <p>Directorio Empresarial</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="20" data-padre="4">
                            <a href="panel?pg=contactos" class="nav-link">
                                <i class="nav-icon bi bi-person-lines-fill"></i>
                                <p>Contactos</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="21" data-padre="4">
                            <a href="panel?pg=control-pagos" class="nav-link">
                                <i class="nav-icon bi bi-person-lines-fill"></i>
                                <p>Control de pagos</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="22" data-padre="4">
                            <a href="panel?pg=archivos-directorios" class="nav-link">
                                <i class="nav-icon bi bi-folder-check"></i>
                                <p>Documentos / Archivos</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item" data-id-opcion="5" data-padre="0">
                    <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-person-badge"></i>
                        <p>
                            Usuarios
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" >
                        <li class="nav-item" data-id-opcion="23" data-padre="5">
                            <a href="panel?pg=usuarios-config" class="nav-link">
                                <i class="nav-icon bi bi-person-circle"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                        <li class="nav-item" data-id-opcion="24" data-padre="5">
                            <a href="panel?pg=mensajes" class="nav-link">
                                <i class="nav-icon bi bi-person-vcard"></i>
                                <p>Mensajes</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>    
    <!--end::Sidebar Wrapper-->
</aside>
<!-- Script para mantener activo el submenú seleccionado en el sidebar, usando clases Bootstrap -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var url = window.location.href;
    var links = document.querySelectorAll('.sidebar-menu .nav-link');
    links.forEach(function(link) {
        if (link.href && url.indexOf(link.href) !== -1) {
            link.classList.add('active', 'bg-white', 'text-dark', 'fw-bold');
            // Si está dentro de un submenú, abrir el menú padre pero sin cambiar el diseño del módulo principal
            var treeview = link.closest('.nav-treeview');
            if (treeview) {
                treeview.style.display = 'block';
            }
        }
    });
});
</script>
<!-- Fin script sidebar activo -->

