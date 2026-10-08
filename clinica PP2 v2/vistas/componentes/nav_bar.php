<header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">

    <!-- LOGO -->
    <div class="col-md-3 mb-2 mb-md-0">

        <a href="index.php?page=indexo"
            class="d-inline-flex link-body-emphasis text-decoration-none">

            <img src="assets/images/logo/captura_de_pantalla_2.png"
                alt="Logo"
                style="width:250px; margin:10px;">
        </a>
    </div>


    <!-- MENU PRINCIPAL -->
    <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">

        <li>
            <a href="index.php?page=indexo"
                class="nav-link px-2 link-secondary">
                Inicio
            </a>
        </li>

        <li>
            <a href="index.php?page=noticias"
                class="nav-link px-2">
                Noticias
            </a>
        </li>

        <li>
            <a href="index.php?page=nosotros"
                class="nav-link px-2">
                Nosotros
            </a>
        </li>

        <li>
            <a href="index.php?page=biblioteca"
                class="nav-link px-2">
                Biblioteca
            </a>
        </li>


        <!-- DROPDOWN SALUD -->
        <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                Salud
            </a>

            <ul class="dropdown-menu">

                <li>
                    <a class="dropdown-item"
                        href="index.php?page=doctores">
                        Doctores
                    </a>
                </li>

                <li>
                    <a class="dropdown-item"
                        href="index.php?page=turnos">
                        Turnos
                    </a>
                </li>

            </ul>

        </li>


        <!-- TURNOS -->
        <li>
            <a class="nav-link px-2"
                href="index.php?page=turnos">
                Turnos
            </a>
        </li>

    </ul>


    <!-- PARTE DERECHA DEL NAVBAR -->
    <div class="col-md-3 text-end d-flex align-items-center justify-content-end gap-3">


        <?php if (!isset($_SESSION['id_usuario'])): ?>

            <!-- INGRESAR -->
            <a href="index.php?page=login"
                class="btn btn-outline-primary">
                Ingresar
            </a>


        <?php else: ?>

            <!-- ============================= -->
            <!-- CAMPANA DE NOTIFICACIONES -->
            <!-- ============================= -->

            <div class="dropdown">

                <button
                    type="button"
                    class="btn btn-light position-relative"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    id="btnNotificaciones">

                    <!-- ICONO CAMPANA -->
                    <i class="bi bi-bell-fill fs-5"></i>


                    <!-- BURBUJA DE NOTIFICACIONES -->
                    <span
                        id="contadorNotificaciones"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                        3

                        <span class="visually-hidden">
                            notificaciones no leídas
                        </span>

                    </span>

                </button>


                <!-- ============================= -->
                <!-- LISTA DE NOTIFICACIONES -->
                <!-- ============================= -->

                <ul
                    class="dropdown-menu dropdown-menu-end shadow"
                    style="width:350px;"
                    id="listaNotificaciones">


                    <!-- CABECERA -->
                    <li>

                        <div class="dropdown-header d-flex justify-content-between align-items-center">

                            <strong>
                                Notificaciones
                            </strong>

                            <button
                                type="button"
                                class="btn btn-sm btn-link text-decoration-none"
                                id="marcarTodasLeidas">

                                Marcar todas como leídas

                            </button>

                        </div>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <!-- NOTIFICACIÓN 1 -->
                    <li>

                        <a
                            href="#"
                            class="dropdown-item d-flex gap-3 py-3 notificacion no-leida">

                            <div>

                                <i class="bi bi-calendar-check text-primary fs-4"></i>

                            </div>

                            <div>

                                <strong>
                                    Nuevo turno
                                </strong>

                                <p class="mb-1 small text-muted">
                                    Se ha registrado un nuevo turno.
                                </p>

                                <small class="text-secondary">
                                    Hace 5 minutos
                                </small>

                            </div>

                        </a>

                    </li>


                    <!-- NOTIFICACIÓN 2 -->
                    <li>

                        <a
                            href="#"
                            class="dropdown-item d-flex gap-3 py-3 notificacion no-leida">

                            <div>

                                <i class="bi bi-person-plus text-success fs-4"></i>

                            </div>

                            <div>

                                <strong>
                                    Nuevo paciente
                                </strong>

                                <p class="mb-1 small text-muted">
                                    Se registró un nuevo paciente.
                                </p>

                                <small class="text-secondary">
                                    Hace 20 minutos
                                </small>

                            </div>

                        </a>

                    </li>


                    <!-- NOTIFICACIÓN 3 -->
                    <li>

                        <a
                            href="#"
                            class="dropdown-item d-flex gap-3 py-3 notificacion no-leida">

                            <div>

                                <i class="bi bi-info-circle text-warning fs-4"></i>

                            </div>

                            <div>

                                <strong>
                                    Recordatorio
                                </strong>

                                <p class="mb-1 small text-muted">
                                    Tienes un turno próximamente.
                                </p>

                                <small class="text-secondary">
                                    Hace 1 hora
                                </small>

                            </div>

                        </a>

                    </li>


                    <!-- SIN NOTIFICACIONES -->
                    <li id="sinNotificaciones" class="d-none">

                        <div class="text-center py-4">

                            <i class="bi bi-bell-slash fs-2 text-muted"></i>

                            <p class="mb-0 mt-2 text-muted">
                                No tienes notificaciones nuevas
                            </p>

                        </div>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <!-- VER TODAS -->
                    <li>

                        <a
                            href="#"
                            class="dropdown-item text-center text-primary">

                            Ver todas las notificaciones

                        </a>

                    </li>

                </ul>

            </div>


            <!-- ============================= -->
            <!-- PERFIL -->
            <!-- ============================= -->

            <a
                class="navbar-brand"
                href="index.php?page=mi_perfil">

                <img
                    src="assets/images/img_avatar1.png"
                    alt="Avatar"
                    style="width:40px;"
                    class="rounded-pill">

            </a>


            <!-- CERRAR SESIÓN -->
            <a
                href="vistas/paginas/salida.php"
                class="btn btn-danger">

                Cerrar Sesión

            </a>

            <script>
                window.ID_USUARIO = <?= (int) $_SESSION['id_usuario'] ?>;
            </script>
        <?php endif; ?>


    </div>

</header>
<script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
<script src="assets/js/notificaciones/pusher_notificaciones.js"></script>