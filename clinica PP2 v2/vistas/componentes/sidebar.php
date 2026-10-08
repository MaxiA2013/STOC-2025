<style>
    /*SIDEBAR*/
    .sidebar {
        width: 280px;
        height: 100vh;
        min-height: 100vh;

        position: fixed;
        top: 0;
        left: 0;

        z-index: 2050;

        transition: transform 0.3s ease-in-out;

        overflow-y: auto;
        overflow-x: hidden;
    }


    /* Sidebar oculto */
    .sidebar.hidden-sidebar {
        transform: translateX(-280px);
    }


    /* BOTÓN PARA ABRIR/CERRAR*/
    #toggleSidebar {
        position: fixed;

        top: 18px;
        left: 18px;

        width: 42px;
        height: 42px;

        border: none;
        border-radius: 50%;

        background-color: #343a40;
        color: white;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 20px;

        cursor: pointer;

        z-index: 3000;

        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.30);

        transition:
            left 0.3s ease-in-out,
            background-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    /* Efecto al pasar el mouse */

    #toggleSidebar:hover {
        background-color: #495057;

        transform: scale(1.08);

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.35);
    }


    /* Cuando el sidebar está abierto */

    .sidebar:not(.hidden-sidebar)~#toggleSidebar {
        left: 250px;
    }


    /* ============================
       ELEMENTOS DEL MENÚ
    ============================ */

    .sidebar .nav-link {
        display: flex;
        align-items: center;

        gap: 8px;
    }


    .sidebar .nav-link i {
        width: 20px;

        text-align: center;

        font-size: 17px;
    }


    /* ============================
       SUBMENÚS
    ============================ */

    .submenu {
        padding-left: 15px;
    }


    .submenu .nav-link {
        font-size: 14px;

        padding-top: 7px;
        padding-bottom: 7px;
    }


    /* Icono de flecha */

    .submenu-arrow {
        width: auto !important;

        font-size: 11px !important;

        transition: transform 0.2s ease;
    }


    /* Girar flecha cuando está abierto */
    .submenu-toggle[aria-expanded="true"] .submenu-arrow {
        transform: rotate(180deg);
    }


    /*SCROLLBAR*/
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }


    .sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.25);

        border-radius: 10px;
    }


    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
</style>


<!-- SIDEBAR-->

<div id="sidebar"
    class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark">


    <!-- Encabezado -->

    <a href="index.php"
        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">

        <i class="bi bi-hospital me-2 fs-4"></i>

        <span class="fs-4">
            Sistema de Turnos
        </span>

    </a>


    <hr>

    <ul class="nav nav-pills flex-column mb-auto">
        <!-- MI PERFIL -->
        <li class="nav-item">
            <a href="index.php?page=mi_perfil"
                class="nav-link text-white">

                <i class="bi bi-person-circle"></i>

                <span>Mi Perfil</span>
            </a>
        </li>


        <!-- MIS DATOS -->
        <li>
            <a href="index.php?page=mis_datos"
                class="nav-link text-white">

                <i class="bi bi-person-vcard"></i>

                <span>Mis Datos</span>
            </a>
        </li>


        <!-- TURNOS -->
        <li>
            <a href="index.php?page=turnos"
                class="nav-link text-white">

                <i class="bi bi-calendar-check"></i>

                <span>Turnos</span>

            </a>
        </li>


        <!-- HISTORIAL -->
        <li>
            <a href="index.php?page=historial_citas"
                class="nav-link text-white">

                <i class="bi bi-clock-history"></i>

                <span>Historial de Citas</span>
            </a>
        </li>

        <!-- MI GESTION (dashboard)-->
        <li>
            <a href="index.php?page=mi_gestion"
                class="nav-link text-white">

                <i class="bi bi-clock-history"></i>

                <span>Mi Gestion</span>
            </a>
        </li>


        <?php if ($_SESSION['id_perfil'] == '1'): ?>
            <hr>


            <!-- GESTIÓN DE USUARIOS-->
            <li>
                <a href="#gestionUsuarios"
                    class="nav-link text-white submenu-toggle"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="gestionUsuarios">

                    <i class="bi bi-people"></i>

                    <span>Gestión de Usuarios</span>

                    <i class="bi bi-chevron-down ms-auto submenu-arrow"></i>
                </a>


                <div class="collapse submenu"
                    id="gestionUsuarios">


                    <ul class="nav flex-column">


                        <!-- Usuarios -->

                        <li>

                            <a href="index.php?page=lista_usuario"
                                class="nav-link text-white">

                                <i class="bi bi-person"></i>

                                <span>Usuarios</span>

                            </a>

                        </li>


                        <!-- Doctores -->

                        <li>

                            <a href="index.php?page=lista_doctor"
                                class="nav-link text-white">

                                <i class="bi bi-heart-pulse"></i>

                                <span>Doctores</span>

                            </a>

                        </li>


                        <!-- Pacientes -->

                        <li>

                            <a href="index.php?page=lista_paciente"
                                class="nav-link text-white">

                                <i class="bi bi-person-vcard"></i>

                                <span>Pacientes</span>

                            </a>

                        </li>


                        <!-- Administradores -->

                        <li>

                            <a href="index.php?page=lista_administradores"
                                class="nav-link text-white">

                                <i class="bi bi-person-gear"></i>

                                <span>Administradores</span>

                            </a>

                        </li>


                    </ul>

                </div>

            </li>


            <!-- ============================
                 GESTIÓN DE SEGURIDAD
            ============================= -->

            <li>

                <a href="#gestionSeguridad"
                    class="nav-link text-white submenu-toggle"
                    data-bs-toggle="collapse"
                    role="button"
                    aria-expanded="false"
                    aria-controls="gestionSeguridad">

                    <i class="bi bi-shield-lock"></i>

                    <span>Gestión de Seguridad</span>

                    <i class="bi bi-chevron-down ms-auto submenu-arrow"></i>

                </a>


                <div class="collapse submenu"
                    id="gestionSeguridad">


                    <ul class="nav flex-column">


                        <!-- Perfiles -->

                        <li>

                            <a href="index.php?page=perfiles"
                                class="nav-link text-white">

                                <i class="bi bi-person-badge"></i>

                                <span>Perfiles</span>

                            </a>
                        </li>


                        <!-- Permisos -->
                        <li>
                            <a href="index.php?page=permisos"
                                class="nav-link text-white">
                                <i class="bi bi-key"></i>
                                <span>Permisos</span>
                            </a>
                        </li>


                        <!-- Módulos -->
                        <li>
                            <a href="index.php?page=modulos"
                                class="nav-link text-white">
                                <i class="bi bi-grid-3x3-gap"></i>
                                <span>Módulos</span>
                            </a>
                        </li>


                        <!-- Tablas -->
                        <li>
                            <a href="index.php?page=tablas"
                                class="nav-link text-white">

                                <i class="bi bi-table"></i>

                                <span>Tablas</span>

                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>
    </ul>
</div>


<!-- BOTÓN FLOTANTE -->

<button id="toggleSidebar"
    type="button"
    aria-label="Abrir o cerrar menú"
    title="Abrir/cerrar menú">

    <i id="toggleIcon" class="bi bi-list"></i>

</button>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        const sidebar = document.getElementById("sidebar");
        const toggleBtn = document.getElementById("toggleSidebar");
        const toggleIcon = document.getElementById("toggleIcon");


        /*
         * Recuperamos el estado anterior
         * del sidebar
         */

        const sidebarOculto =
            localStorage.getItem("sidebarOculto") === "true";


        /*
         * Restauramos el estado
         */

        if (sidebarOculto) {

            sidebar.classList.add("hidden-sidebar");

            toggleIcon.classList.remove("bi-chevron-left");
            toggleIcon.classList.add("bi-list");

        } else {

            toggleIcon.classList.remove("bi-list");
            toggleIcon.classList.add("bi-chevron-left");

        }


        /*
         * Abrir / cerrar sidebar
         */

        toggleBtn.addEventListener("click", function() {

            sidebar.classList.toggle("hidden-sidebar");


            const oculto =
                sidebar.classList.contains("hidden-sidebar");


            /* Guardamos el estado */

            localStorage.setItem(
                "sidebarOculto",
                oculto
            );


            /* Cambiamos el iconon */

            if (oculto) {

                toggleIcon.classList.remove("bi-chevron-left");
                toggleIcon.classList.add("bi-list");

            } else {

                toggleIcon.classList.remove("bi-list");
                toggleIcon.classList.add("bi-chevron-left");
            }

        });

    });
</script>