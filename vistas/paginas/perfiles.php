<?php
require_once "modelos/perfil.php";

$perfil = new Perfil();
$lista_perfiles = $perfil->traer_perfiles();

?>

<div class="container-fluid py-4 px-4">

    <!-- ENCABEZADO -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-person-badge me-2 text-primary"></i>
                Gestión de Perfiles
            </h2>

            <p class="text-muted mb-0">
                Administra los perfiles de acceso del sistema.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary rounded-3 px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoPerfil">

            <i class="bi bi-plus-lg me-2"></i>
            Nuevo Perfil

        </button>

    </div>


    <!-- CARD PRINCIPAL -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- CABECERA DE LISTADO -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Perfiles Registrados
                    </h5>

                    <p class="text-muted small mb-0">
                        Lista de perfiles disponibles para asignar permisos.
                    </p>
                </div>

                <span
                    id="contadorPerfiles"
                    class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">

                    <?= is_array($lista_perfiles) ? count($lista_perfiles) : 0 ?>
                    perfiles

                </span>

            </div>


            <!-- BUSCADOR -->
            <div class="row mb-4">

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="input-group">

                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>

                        <input
                            type="text"
                            id="buscarPerfil"
                            class="form-control border-start-0"
                            placeholder="Buscar perfil...">

                    </div>

                </div>

            </div>


            <!-- TABLA -->
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="fw-semibold">
                                ID
                            </th>

                            <th class="fw-semibold">
                                Perfil
                            </th>

                            <th class="fw-semibold">
                                Descripción
                            </th>

                            <th class="fw-semibold text-end">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody id="tablaPerfiles">

                        <?php if (!empty($lista_perfiles)): ?>

                            <?php foreach ($lista_perfiles as $p): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($p['id_perfil']) ?>
                                    </td>

                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div
                                                class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle"
                                                style="width: 36px; height: 36px;">

                                                <i class="bi bi-person-badge"></i>

                                            </div>

                                            <span class="fw-semibold">
                                                <?= htmlspecialchars($p['descripcion']) ?>
                                            </span>

                                        </div>

                                    </td>

                                    <td class="text-muted">
                                        Perfil de acceso al sistema
                                    </td>

                                    <td class="text-end">

                                        <div class="btn-group" role="group">

                                            <!-- EDITAR -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Editar perfil"
                                                onclick='editarPerfil(
                                                    <?= json_encode($p['id_perfil']) ?>,
                                                    <?= json_encode($p['descripcion']) ?>
                                                )'>

                                                <i class="bi bi-pencil"></i>

                                            </button>


                                            <!-- ELIMINAR -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar perfil"
                                                onclick='eliminarPerfil(
                                                    <?= json_encode($p['id_perfil']) ?>,
                                                    <?= json_encode($p['descripcion']) ?>
                                                )'>

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="4" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-person-badge fs-1 d-block mb-3"></i>

                                        <p class="mb-0">
                                            No hay perfiles registrados.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>



<!-- ========================================================= -->
<!-- OFFCANVAS - NUEVO PERFIL -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoPerfil"
    aria-labelledby="offcanvasNuevoPerfilLabel">

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoPerfilLabel">

                <i class="bi bi-person-plus me-2 text-primary"></i>
                Nuevo Perfil

            </h5>

            <small class="text-muted">
                Registra un nuevo perfil de acceso.
            </small>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar">
        </button>

    </div>


    <div class="offcanvas-body">

        <form
            id="formNuevoPerfil"
            method="POST"
            action="controladores/perfil_controlador.php">

            <input
                type="hidden"
                name="action"
                value="insertar">


            <div class="mb-3">

                <label
                    for="descripcion_nuevo"
                    class="form-label fw-semibold">

                    Nombre del perfil

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="descripcion_nuevo"
                    name="descripcion"
                    placeholder="Ej. Administrador"
                    required>

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="offcanvas">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-check-lg me-1"></i>
                    Guardar Perfil

                </button>

            </div>

        </form>

    </div>

</div>



<!-- ========================================================= -->
<!-- MODAL - EDITAR PERFIL -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalEditarPerfil"
    tabindex="-1"
    aria-labelledby="modalEditarPerfilLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header border-0 px-4 pt-4">

                <div>

                    <h5
                        class="modal-title fw-semibold"
                        id="modalEditarPerfilLabel">

                        <i class="bi bi-pencil-square me-2 text-primary"></i>
                        Editar Perfil

                    </h5>

                    <small class="text-muted">
                        Modifica la información del perfil.
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>

            </div>


            <div class="modal-body px-4">

                <form id="formEditarPerfil">

                    <input
                        type="hidden"
                        id="id_perfil"
                        name="id_perfil">

                    <input
                        type="hidden"
                        name="action"
                        value="actualizacion">


                    <div class="mb-3">

                        <label
                            for="descripcion"
                            class="form-label fw-semibold">

                            Nombre del perfil

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="descripcion"
                            name="descripcion"
                            required>

                    </div>

                </form>

            </div>


            <div class="modal-footer border-0 px-4 pb-4">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal">

                    Cancelar

                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnActualizarPerfil">

                    <i class="bi bi-check-lg me-1"></i>
                    Guardar Cambios

                </button>

            </div>

        </div>

    </div>

</div>



<script>

$(document).ready(function () {

    /*
     * =========================================================
     * BUSCADOR
     * =========================================================
     */

    $("#buscarPerfil").on("keyup", function () {

        const valor = $(this).val().toLowerCase();

        $("#tablaPerfiles tr").each(function () {

            const texto = $(this).text().toLowerCase();

            $(this).toggle(texto.includes(valor));

        });

    });


    /*
     * =========================================================
     * ACTUALIZAR PERFIL
     * =========================================================
     */

    $("#btnActualizarPerfil").on("click", function () {

        const id = $("#id_perfil").val();
        const descripcion = $("#descripcion").val().trim();

        if (descripcion === "") {

            Swal.fire({
                icon: "warning",
                title: "Campo obligatorio",
                text: "Debe ingresar el nombre del perfil."
            });

            return;
        }


        $.ajax({

            url: "controladores/perfil_controlador.php",

            type: "POST",

            data: {

                action: "actualizacion",
                id_perfil: id,
                descripcion: descripcion

            },

            success: function (respuesta) {

                Swal.fire({
                    icon: "success",
                    title: "Perfil actualizado",
                    text: "Los cambios se guardaron correctamente.",
                    timer: 1800,
                    showConfirmButton: false
                }).then(function () {

                    location.reload();

                });

            },

            error: function () {

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No fue posible actualizar el perfil."
                });

            }

        });

    });

});


/*
 * =========================================================
 * ABRIR MODAL DE EDICIÓN
 * =========================================================
 */

function editarPerfil(id, descripcion) {

    $("#id_perfil").val(id);
    $("#descripcion").val(descripcion);

    const modal = new bootstrap.Modal(
        document.getElementById("modalEditarPerfil")
    );

    modal.show();

}


/*
 * =========================================================
 * ELIMINAR PERFIL
 * =========================================================
 */

function eliminarPerfil(id, descripcion) {

    Swal.fire({

        title: "¿Eliminar perfil?",

        html:
            'Se eliminará el perfil <strong>' +
            escapeHtml(descripcion) +
            '</strong>.',

        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Sí, eliminar",

        cancelButtonText: "Cancelar",

        reverseButtons: true

    }).then(function (result) {

        if (!result.isConfirmed) {
            return;
        }


        $.ajax({

            url: "controladores/perfil_controlador.php",

            type: "POST",

            data: {

                action: "eliminacion",
                id_perfil: id

            },

            success: function (respuesta) {

                Swal.fire({

                    icon: "success",

                    title: "Perfil eliminado",

                    text: "El perfil fue eliminado correctamente.",

                    timer: 1800,

                    showConfirmButton: false

                }).then(function () {

                    location.reload();

                });

            },

            error: function () {

                Swal.fire({

                    icon: "error",

                    title: "Error",

                    text: "No fue posible eliminar el perfil."

                });

            }

        });

    });

}


/*
 * =========================================================
 * ESCAPAR HTML
 * =========================================================
 */

function escapeHtml(text) {

    return $("<div>")
        .text(text)
        .html();

}

</script>
