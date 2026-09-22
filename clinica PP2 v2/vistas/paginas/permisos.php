<?php

require_once "modelos/perfil.php";
require_once "modelos/modulos.php";

$perfil = new Perfil();
$modulos = new Modulos();

$lista_perfiles = $perfil->traer_perfiles();
$lista_modulos = $modulos->traer_Modulos();

?>

<style>

    /* =========================================================
       PERFILES - PERMISOS
       ========================================================= */

    .seguridad-header {
        background: linear-gradient(
            135deg,
            #000967 0%,
            #024296 55%,
            #007DC6 100%
        );
        border-radius: 20px;
        padding: 30px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 9, 103, .15);
        margin-bottom: 24px;
    }

    .seguridad-header h2 {
        font-weight: 700;
        margin-bottom: 6px;
    }

    .seguridad-header p {
        margin: 0;
        opacity: .85;
    }

    .seguridad-header-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: rgba(255,255,255,.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(8px);
    }

    /* =========================================================
       ESTADÍSTICAS
       ========================================================= */

    .seguridad-stat {
        background: #fff;
        border: 1px solid #e9eef5;
        border-radius: 18px;
        padding: 20px;
        height: 100%;
        transition: all .25s ease;
    }

    .seguridad-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,.07);
    }

    .seguridad-stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 13px;
        background: rgba(0, 125, 198, .10);
        color: #007DC6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .seguridad-stat-title {
        font-size: 13px;
        color: #8999AE;
        margin-bottom: 2px;
    }

    .seguridad-stat-value {
        font-size: 26px;
        font-weight: 700;
        color: #000967;
    }

    /* =========================================================
       PANEL PRINCIPAL
       ========================================================= */

    .seguridad-panel {
        background: #fff;
        border: 1px solid #e9eef5;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,.04);
    }

    /* =========================================================
       PERFIL LIST
       ========================================================= */

    .perfiles-panel {
        border-right: 1px solid #edf1f6;
        min-height: 650px;
        background: #fbfcfe;
    }

    .perfiles-panel-header {
        padding: 22px;
        border-bottom: 1px solid #edf1f6;
    }

    .perfiles-panel-header h5 {
        color: #000967;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .perfiles-panel-header p {
        color: #8999AE;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .perfil-item {
        margin: 8px 12px;
        padding: 14px;
        border-radius: 14px;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all .2s ease;
        background: transparent;
    }

    .perfil-item:hover {
        background: #fff;
        border-color: #e3eaf2;
    }

    .perfil-item.active {
        background: #fff;
        border-color: rgba(0,125,198,.25);
        box-shadow: 0 5px 15px rgba(0,0,0,.05);
    }

    .perfil-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(
            135deg,
            #000967,
            #007DC6
        );
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .perfil-nombre {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }

    .perfil-descripcion {
        color: #8999AE;
        font-size: 12px;
        margin-top: 2px;
    }

    /* =========================================================
       CONFIGURACIÓN
       ========================================================= */

    .permisos-panel {
        padding: 28px;
    }

    .perfil-config-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 25px;
    }

    .perfil-config-title {
        color: #000967;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .perfil-config-description {
        color: #8999AE;
        margin: 0;
        font-size: 14px;
    }

    .nivel-badge {
        background: rgba(0,125,198,.10);
        color: #007DC6;
        border-radius: 30px;
        padding: 7px 13px;
        font-size: 12px;
        font-weight: 600;
    }

    /* =========================================================
       TABS
       ========================================================= */

    .seguridad-tabs {
        border-bottom: 1px solid #e9eef5;
        margin-bottom: 22px;
    }

    .seguridad-tabs .nav-link {
        border: none;
        color: #8999AE;
        font-weight: 600;
        padding: 12px 18px;
        position: relative;
    }

    .seguridad-tabs .nav-link:hover {
        color: #007DC6;
    }

    .seguridad-tabs .nav-link.active {
        color: #000967;
        background: transparent;
    }

    .seguridad-tabs .nav-link.active::after {
        content: "";
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: -1px;
        height: 3px;
        border-radius: 5px 5px 0 0;
        background: #007DC6;
    }

    /* =========================================================
       MODULOS
       ========================================================= */

    .modulo-permiso {
        border: 1px solid #e7edf4;
        border-radius: 16px;
        margin-bottom: 14px;
        overflow: hidden;
        background: #fff;
    }

    .modulo-header {
        padding: 17px 18px;
        background: #fbfcfe;
        border-bottom: 1px solid #edf1f6;
    }

    .modulo-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: rgba(0,125,198,.10);
        color: #007DC6;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modulo-nombre {
        font-weight: 700;
        color: #1f2937;
        font-size: 14px;
    }

    .modulo-subtitulo {
        color: #8999AE;
        font-size: 12px;
    }

    .modulo-body {
        padding: 16px 18px;
    }

    .permiso-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f3f7;
    }

    .permiso-row:last-child {
        border-bottom: none;
    }

    .permiso-nombre {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .permiso-descripcion {
        font-size: 11px;
        color: #8999AE;
    }

    /* =========================================================
       SWITCH
       ========================================================= */

    .form-check-input {
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #007DC6;
        border-color: #007DC6;
    }

    /* =========================================================
       ACCIONES
       ========================================================= */

    .seguridad-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #edf1f6;
    }

    .btn-seguridad {
        border-radius: 10px;
        font-weight: 600;
        padding: 9px 17px;
    }

    /* =========================================================
       ESTADO SUPER ADMIN
       ========================================================= */

    .superadmin-alert {
        border: 1px solid rgba(0,125,198,.15);
        background: rgba(0,125,198,.05);
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 20px;
        color: #024296;
        font-size: 13px;
    }

</style>


<div class="container-fluid px-4 py-4">

    <!-- =====================================================
         ENCABEZADO
         ===================================================== -->

    <div class="seguridad-header">

        <div class="d-flex align-items-center gap-3">

            <div class="seguridad-header-icon">
                <i class="bi bi-shield-lock"></i>
            </div>

            <div>

                <h2>Perfiles y Permisos</h2>

                <p>
                    Administra los módulos y permisos de acceso
                    de los perfiles del sistema.
                </p>

            </div>

        </div>

    </div>


    <!-- =====================================================
         ESTADÍSTICAS
         ===================================================== -->

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="seguridad-stat">

                <div class="seguridad-stat-icon">
                    <i class="bi bi-person-badge"></i>
                </div>

                <div class="seguridad-stat-title">
                    Perfiles registrados
                </div>

                <div class="seguridad-stat-value">
                    <?php
                    echo $lista_perfiles ? mysqli_num_rows($lista_perfiles) : 0;
                    ?>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="seguridad-stat">

                <div class="seguridad-stat-icon">
                    <i class="bi bi-grid"></i>
                </div>

                <div class="seguridad-stat-title">
                    Módulos disponibles
                </div>

                <div class="seguridad-stat-value">
                    <?php
                    echo $lista_modulos ? mysqli_num_rows($lista_modulos) : 0;
                    ?>
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="seguridad-stat">

                <div class="seguridad-stat-icon">
                    <i class="bi bi-key"></i>
                </div>

                <div class="seguridad-stat-title">
                    Tipos de permisos
                </div>

                <div class="seguridad-stat-value">
                    4
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="seguridad-stat">

                <div class="seguridad-stat-icon">
                    <i class="bi bi-person-gear"></i>
                </div>

                <div class="seguridad-stat-title">
                    Permisos personalizados
                </div>

                <div class="seguridad-stat-value">
                    0
                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         PANEL PRINCIPAL
         ===================================================== -->

    <div class="seguridad-panel">

        <div class="row g-0">

            <!-- =================================================
                 LISTADO DE PERFILES
                 ================================================= -->

            <div class="col-lg-4 col-xl-3 perfiles-panel">

                <div class="perfiles-panel-header">

                    <h5>
                        Perfiles
                    </h5>

                    <p>
                        Selecciona un perfil para configurar sus accesos.
                    </p>

                    <div class="input-group">

                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>

                        <input
                            type="text"
                            id="buscarPerfil"
                            class="form-control border-start-0"
                            placeholder="Buscar perfil..."
                        >

                    </div>

                </div>


                <div id="listaPerfiles">

                    <?php

                    if ($lista_perfiles && mysqli_num_rows($lista_perfiles) > 0) {

                        $primero = true;

                        while ($fila = $lista_perfiles->fetch_assoc()) {

                    ?>

                            <div
                                class="perfil-item <?php echo $primero ? 'active' : ''; ?>"
                                data-perfil-id="<?php echo $fila['id_perfil']; ?>"
                            >

                                <div class="d-flex align-items-center gap-3">

                                    <div class="perfil-avatar">

                                        <i class="bi bi-person-badge"></i>

                                    </div>

                                    <div class="flex-grow-1">

                                        <div class="perfil-nombre">

                                            <?php
                                            echo htmlspecialchars($fila['nombre_perfil']);
                                            ?>

                                        </div>

                                        <div class="perfil-descripcion">

                                            <?php
                                            echo htmlspecialchars(
                                                $fila['descripcion'] ?: 'Sin descripción'
                                            );
                                            ?>

                                        </div>

                                    </div>

                                    <i class="bi bi-chevron-right text-muted"></i>

                                </div>

                            </div>

                    <?php

                            $primero = false;

                        }

                    } else {

                    ?>

                        <div class="text-center text-muted p-4">

                            <i class="bi bi-person-x fs-2"></i>

                            <p class="mt-2 mb-0">
                                No hay perfiles registrados.
                            </p>

                        </div>

                    <?php

                    }

                    ?>

                </div>

            </div>


            <!-- =================================================
                 CONFIGURACIÓN DE PERMISOS
                 ================================================= -->

            <div class="col-lg-8 col-xl-9">

                <div class="permisos-panel">


                    <!-- HEADER -->

                    <div class="perfil-config-header">

                        <div>

                            <h4 class="perfil-config-title">
                                Configuración del perfil
                            </h4>

                            <p class="perfil-config-description">
                                Define qué módulos y acciones puede utilizar este perfil.
                            </p>

                        </div>

                        <div>

                            <span class="nivel-badge">
                                <i class="bi bi-shield-check me-1"></i>
                                Perfil administrativo
                            </span>

                        </div>

                    </div>


                    <!-- TABS -->

                    <ul class="nav seguridad-tabs">

                        <li class="nav-item">

                            <button
                                class="nav-link active"
                                type="button"
                                data-bs-toggle="tab"
                                data-bs-target="#tabModulos"
                            >

                                <i class="bi bi-grid me-1"></i>

                                Módulos y permisos

                            </button>

                        </li>

                        <li class="nav-item">

                            <button
                                class="nav-link"
                                type="button"
                                data-bs-toggle="tab"
                                data-bs-target="#tabUsuarios"
                            >

                                <i class="bi bi-person-gear me-1"></i>

                                Permisos individuales

                            </button>

                        </li>

                    </ul>


                    <div class="tab-content">


                        <!-- =====================================
                             TAB MÓDULOS
                             ===================================== -->

                        <div
                            class="tab-pane fade show active"
                            id="tabModulos"
                        >


                            <div class="superadmin-alert">

                                <i class="bi bi-info-circle me-2"></i>

                                Los permisos se aplican al perfil seleccionado.
                                Los usuarios podrán recibir posteriormente
                                permisos personalizados.

                            </div>


                            <?php

                            if ($lista_modulos && mysqli_num_rows($lista_modulos) > 0) {

                                while ($modulo = $lista_modulos->fetch_assoc()) {

                            ?>

                                    <div class="modulo-permiso">


                                        <!-- HEADER MÓDULO -->

                                        <div class="modulo-header">

                                            <div class="d-flex align-items-center">

                                                <div class="modulo-icon me-3">

                                                    <i class="bi bi-grid-3x3-gap"></i>

                                                </div>

                                                <div class="flex-grow-1">

                                                    <div class="modulo-nombre">

                                                        <?php
                                                        echo htmlspecialchars(
                                                            $modulo['nombre']
                                                        );
                                                        ?>

                                                    </div>

                                                    <div class="modulo-subtitulo">

                                                        Acceso al módulo

                                                    </div>

                                                </div>


                                                <!-- SWITCH MÓDULO -->

                                                <div class="form-check form-switch">

                                                    <input
                                                        class="form-check-input modulo-switch"
                                                        type="checkbox"
                                                        role="switch"
                                                        data-modulo-id="<?php
                                                        echo $modulo['id_modulos'];
                                                        ?>"
                                                    >

                                                </div>

                                            </div>

                                        </div>


                                        <!-- PERMISOS -->

                                        <div class="modulo-body">


                                            <div class="permiso-row">

                                                <div>

                                                    <div class="permiso-nombre">
                                                        Ver
                                                    </div>

                                                    <div class="permiso-descripcion">
                                                        Permite consultar información
                                                    </div>

                                                </div>

                                                <div class="form-check">

                                                    <input
                                                        class="form-check-input permiso-check"
                                                        type="checkbox"
                                                        data-permiso="ver"
                                                    >

                                                </div>

                                            </div>


                                            <div class="permiso-row">

                                                <div>

                                                    <div class="permiso-nombre">
                                                        Crear
                                                    </div>

                                                    <div class="permiso-descripcion">
                                                        Permite registrar nuevos datos
                                                    </div>

                                                </div>

                                                <div class="form-check">

                                                    <input
                                                        class="form-check-input permiso-check"
                                                        type="checkbox"
                                                        data-permiso="crear"
                                                    >

                                                </div>

                                            </div>


                                            <div class="permiso-row">

                                                <div>

                                                    <div class="permiso-nombre">
                                                        Editar
                                                    </div>

                                                    <div class="permiso-descripcion">
                                                        Permite modificar información
                                                    </div>

                                                </div>

                                                <div class="form-check">

                                                    <input
                                                        class="form-check-input permiso-check"
                                                        type="checkbox"
                                                        data-permiso="editar"
                                                    >

                                                </div>

                                            </div>


                                            <div class="permiso-row">

                                                <div>

                                                    <div class="permiso-nombre">
                                                        Eliminar
                                                    </div>

                                                    <div class="permiso-descripcion">
                                                        Permite eliminar información
                                                    </div>

                                                </div>

                                                <div class="form-check">

                                                    <input
                                                        class="form-check-input permiso-check"
                                                        type="checkbox"
                                                        data-permiso="eliminar"
                                                    >

                                                </div>

                                            </div>


                                        </div>

                                    </div>

                            <?php

                                }

                            }

                            ?>


                            <!-- ACCIONES -->

                            <div class="seguridad-actions">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary btn-seguridad"
                                >

                                    <i class="bi bi-arrow-counterclockwise me-1"></i>

                                    Restablecer

                                </button>


                                <button
                                    type="button"
                                    class="btn btn-primary btn-seguridad"
                                    id="btnGuardarPermisos"
                                >

                                    <i class="bi bi-check2-circle me-1"></i>

                                    Guardar cambios

                                </button>

                            </div>


                        </div>


                        <!-- =====================================
                             TAB PERMISOS INDIVIDUALES
                             ===================================== -->

                        <div
                            class="tab-pane fade"
                            id="tabUsuarios"
                        >

                            <div class="text-center py-5">

                                <div class="seguridad-stat-icon mx-auto mb-3">

                                    <i class="bi bi-person-gear"></i>

                                </div>

                                <h5 class="fw-bold text-dark">

                                    Permisos individuales

                                </h5>

                                <p class="text-muted mb-3">

                                    Gestiona excepciones de permisos para
                                    usuarios específicos.

                                </p>

                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-seguridad"
                                >

                                    <i class="bi bi-person-plus me-1"></i>

                                    Gestionar usuarios

                                </button>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================================
       BUSCADOR DE PERFILES
       ===================================================== */

    const buscador = document.getElementById("buscarPerfil");

    if (buscador) {

        buscador.addEventListener("input", function () {

            const texto = this.value.toLowerCase();

            document
                .querySelectorAll(".perfil-item")
                .forEach(function (perfil) {

                    const nombre =
                        perfil
                            .querySelector(".perfil-nombre")
                            .textContent
                            .toLowerCase();

                    perfil.style.display =
                        nombre.includes(texto)
                            ? ""
                            : "none";

                });

        });

    }


    /* =====================================================
       SELECCIONAR PERFIL
       ===================================================== */

    document
        .querySelectorAll(".perfil-item")
        .forEach(function (perfil) {

            perfil.addEventListener("click", function () {

                document
                    .querySelectorAll(".perfil-item")
                    .forEach(function (item) {

                        item.classList.remove("active");

                    });

                this.classList.add("active");

                /*
                 * Posteriormente:
                 *
                 * cargarPermisosPerfil(
                 *     this.dataset.perfilId
                 * );
                 */

            });

        });


    /* =====================================================
       SWITCH DE MÓDULO
       ===================================================== */

    document
        .querySelectorAll(".modulo-switch")
        .forEach(function (switchModulo) {

            switchModulo.addEventListener("change", function () {

                const modulo =
                    this.closest(".modulo-permiso");

                const permisos =
                    modulo.querySelectorAll(".permiso-check");

                permisos.forEach(function (permiso) {

                    permiso.checked =
                        switchModulo.checked;

                    permiso.disabled =
                        !switchModulo.checked;

                });

            });

        });


    /* =====================================================
       GUARDAR
       ===================================================== */

    const btnGuardar =
        document.getElementById("btnGuardarPermisos");

    if (btnGuardar) {

        btnGuardar.addEventListener("click", function () {

            /*
             * En la siguiente etapa:
             *
             * 1. obtener perfil seleccionado
             * 2. obtener módulos
             * 3. obtener permisos
             * 4. enviar AJAX
             * 5. guardar en MySQL
             */

            console.log("Preparado para guardar permisos.");

        });

    }

});

</script>