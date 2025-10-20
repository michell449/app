<li class="nav-item dropdown user-menu">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <span class="d-none d-md-inline"><?php echo $_SESSION['USR_NAME'];?></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <!--begin::User Image-->
        <li class="user-header text-bg-primary d-flex flex-column align-items-center justify-content-center">
            <span class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center mb-2" style="width:64px; height:64px; font-size:2.5rem;">
                <i class="bi bi-person-circle"></i>
            </span>
            <p class="mb-0 fw-bold">
                <?php echo $_SESSION['USR_NAME']; ?>
            </p>
            <small><?php echo $_SESSION['USR_MAIL']; ?></small>
        </li>
        <!--end::User Image-->
        <!--begin::Menu Body-->
        <li class="user-body">
            <!--begin::Row-->
            <div class="row">
                <div class="col-4 text-center"><a href="#">Mensajes</a></div>
                <div class="col-4 text-center"><a href="#">Notas</a></div>
                <div class="col-4 text-center"><a href="#">Contacto</a></div>
            </div>
            <!--end::Row-->
        </li>
        <!--end::Menu Body-->
        <!--begin::Menu Footer-->
        <li class="user-footer">
            <a href="panel?pg=usuario-Profile" class="btn btn-default btn-flat">Perfil</a>
            <a href="core/logout.php" class="btn btn-danger btn-flat float-end">Cerrar sesión</a>
        </li>
        <!--end::Menu Footer-->
    </ul>
</li>
<!--end::User Menu Dropdown-->