<?php

include_once "modelos/modulos.php";
include_once "modelos/tablas.php";

$modul = new Modulos("", "");

$lista_modulos = $modul->traer_modulos_con_tablas();

$tables = new tablas();

$lista_tablas_result = $tables->traerTablas();

/*
 * Convertimos el resultado de tablas a un array
 * para poder reutilizarlo en el Offcanvas y en el Modal.
 */
$tablasArray = [];

if ($lista_tablas_result) {

    while ($r = $lista_tablas_result->fetch_assoc()) {

        $tablasArray[] = $r;

    }

}

?>

<div class="container-fluid py-4 px-4">

    <!-- ===================================================== -->
    <!-- ENCABEZADO -->
    <!-- ===================================================== -->

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="bi bi-grid-1x2 me-2 text-primary"></i>

                Gestión de Módulos

            </h2>

            <p class="text-muted mb-0">

                Administra los módulos del sistema y las tablas asociadas.

            </p>

        </div>


        <button
            type="button"
            class="btn btn-primary rounded-3 px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoModulo">

            <i class="bi bi-plus-lg me-2"></i>

            Nuevo Módulo

        </button>

    </div>



    <!-- ===================================================== -->
    <!-- CARD PRINCIPAL -->
    <!-- ===================================================== -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">


            <!-- CABECERA DEL LISTADO -->

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div>

                    <h5 class="fw-semibold mb-1">

                        Módulos Registrados

                    </h5>

                    <p class="text-muted small mb-0">

                        Consulta y administra los módulos y sus tablas asociadas.

                    </p>

                </div>


                <span
                    id="contadorModulos"
                    class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">

                    <?php

                    if ($lista_modulos instanceof mysqli_result) {

                        echo $lista_modulos->num_rows;

                    } else {

                        echo "0";

                    }

                    ?>

                    módulos

                </span>

            </div>



            <!-- ================================================= -->
            <!-- BUSCADOR -->
            <!-- ================================================= -->

            <div class="row mb-4">

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="input-group">

                        <span class="input-group-text bg-white border-end-0">

                            <i class="bi bi-search text-muted"></i>

                        </span>


                        <input
                            type="text"
                            id="buscarModulo"
                            class="form-control border-start-0"
                            placeholder="Buscar módulo...">

                    </div>

                </div>

            </div>



            <!-- ================================================= -->
            <!-- TABLA -->
            <!-- ================================================= -->

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="fw-semibold">
                                ID
                            </th>

                            <th class="fw-semibold">
                                Módulo
                            </th>

                            <th class="fw-semibold">
                                Tablas asociadas
                            </th>

                            <th class="fw-semibold text-end">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody id="tablaModulos">

                        <?php if ($lista_modulos instanceof mysqli_result && $lista_modulos->num_rows > 0): ?>


                            <?php while ($row = $lista_modulos->fetch_assoc()): ?>

                                <?php

                                /*
                                 * Obtener los IDs de las tablas
                                 * asignadas al módulo.
                                 */
                                $tablas_ids = $modul->traer_tablas_ids_por_modulo(
                                    $row['id_modulos']
                                );

                                ?>


                                <tr>


                                    <!-- ID -->

                                    <td>

                                        <?= htmlspecialchars($row['id_modulos']) ?>

                                    </td>



                                    <!-- MÓDULO -->

                                    <td>

                                        <div class="d-flex align-items-center gap-3">


                                            <div
                                                class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle"
                                                style="width: 36px; height: 36px;">

                                                <i class="bi bi-grid-1x2"></i>

                                            </div>


                                            <span class="fw-semibold">

                                                <?= htmlspecialchars($row['nombre']) ?>

                                            </span>


                                        </div>

                                    </td>



                                    <!-- TABLAS -->

                                    <td>

                                        <?php if (!empty($row['tablas'])): ?>

                                            <div class="d-flex flex-wrap gap-2">

                                                <?php

                                                $tablasTexto = explode(",", $row['tablas']);

                                                foreach ($tablasTexto as $tabla):

                                                ?>

                                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">

                                                        <i class="bi bi-table me-1 text-primary"></i>

                                                        <?= htmlspecialchars(trim($tabla)) ?>

                                                    </span>

                                                <?php endforeach; ?>

                                            </div>

                                        <?php else: ?>

                                            <span class="text-muted small">

                                                Sin tablas asignadas

                                            </span>

                                        <?php endif; ?>

                                    </td>



                                    <!-- ACCIONES -->

                                    <td class="text-end">

                                        <div class="btn-group" role="group">


                                            <!-- EDITAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Editar módulo"
                                                onclick='editarModulo(
                                                    <?= json_encode($row['id_modulos']) ?>,
                                                    <?= json_encode($row['nombre']) ?>,
                                                    <?= json_encode($tablas_ids) ?>
                                                )'>

                                                <i class="bi bi-pencil"></i>

                                            </button>



                                            <!-- ELIMINAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar módulo"
                                                onclick='eliminarModulo(
                                                    <?= json_encode($row['id_modulos']) ?>,
                                                    <?= json_encode($row['nombre']) ?>
                                                )'>

                                                <i class="bi bi-trash"></i>

                                            </button>


                                        </div>

                                    </td>


                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>


                            <tr>

                                <td colspan="4" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-grid-1x2 fs-1 d-block mb-3"></i>

                                        <p class="mb-0">

                                            No hay módulos registrados.

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
<!-- OFFCANVAS - NUEVO MÓDULO -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoModulo"
    aria-labelledby="offcanvasNuevoModuloLabel">


    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoModuloLabel">

                <i class="bi bi-grid-1x2 me-2 text-primary"></i>

                Nuevo Módulo

            </h5>

            <small class="text-muted">

                Registra un nuevo módulo y asigna sus tablas.

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
            method="POST"
            action="controladores/modulos/modulo_controlador.php"
            id="formNuevoModulo">


            <input
                type="hidden"
                name="action"
                value="insertar">



            <!-- NOMBRE -->

            <div class="mb-4">

                <label
                    for="nombre_modulo"
                    class="form-label fw-semibold">

                    Nombre del módulo

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_modulo"
                    name="nombre_modulo"
                    placeholder="Ej. Pacientes"
                    required>

            </div>



            <!-- TABLAS -->

            <div class="mb-4">

                <div class="d-flex justify-content-between align-items-center mb-2">

                    <label class="form-label fw-semibold mb-0">

                        Tablas asociadas

                    </label>

                    <span class="badge bg-primary-subtle text-primary rounded-pill">

                        <?= count($tablasArray) ?> disponibles

                    </span>

                </div>


                <p class="text-muted small mb-3">

                    Selecciona las tablas que pertenecerán a este módulo.

                </p>



                <?php if (!empty($tablasArray)): ?>


                    <div class="border rounded-3 p-3">

                        <?php foreach ($tablasArray as $tabla): ?>


                            <div class="form-check py-2">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="tablas[]"
                                    value="<?= htmlspecialchars($tabla['id_tablas']) ?>"
                                    id="tabla_nueva_<?= htmlspecialchars($tabla['id_tablas']) ?>">


                                <label
                                    class="form-check-label"
                                    for="tabla_nueva_<?= htmlspecialchars($tabla['id_tablas']) ?>">

                                    <i class="bi bi-table me-2 text-primary"></i>

                                    <?= htmlspecialchars($tabla['nombre_tabla']) ?>

                                </label>

                            </div>


                        <?php endforeach; ?>

                    </div>


                <?php else: ?>


                    <div class="alert alert-light border small">

                        <i class="bi bi-info-circle me-2"></i>

                        No hay tablas disponibles para asignar.

                    </div>


                <?php endif; ?>


            </div>



            <!-- BOTONES -->

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

                    Guardar Módulo

                </button>

            </div>


        </form>

    </div>

</div>



<!-- ========================================================= -->
<!-- MODAL - EDITAR MÓDULO -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalModulo"
    tabindex="-1"
    aria-labelledby="modalModuloLabel"
    aria-hidden="true">


    <div class="modal-dialog modal-dialog-centered modal-lg">


        <div class="modal-content border-0 shadow rounded-4">


            <form
                method="POST"
                action="controladores/modulos/modulo_controlador.php">


                <!-- HEADER -->

                <div class="modal-header border-0 px-4 pt-4">


                    <div>

                        <h5
                            class="modal-title fw-semibold"
                            id="modalModuloLabel">

                            <i class="bi bi-pencil-square me-2 text-primary"></i>

                            Editar Módulo

                        </h5>


                        <small class="text-muted">

                            Modifica el módulo y sus tablas asociadas.

                        </small>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                    </button>


                </div>



                <!-- BODY -->

                <div class="modal-body px-4">


                    <input
                        type="hidden"
                        name="id_modulos"
                        id="id_modulos">


                    <input
                        type="hidden"
                        name="action"
                        id="action">



                    <!-- NOMBRE -->

                    <div class="mb-4">

                        <label
                            for="nombre_modulo_modal"
                            class="form-label fw-semibold">

                            Nombre del módulo

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            id="nombre_modulo_modal"
                            name="nombre_modulo"
                            required>

                    </div>



                    <!-- TABLAS -->

                    <div>

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <label class="form-label fw-semibold mb-0">

                                Tablas asociadas

                            </label>


                            <span
                                id="contadorTablasSeleccionadas"
                                class="badge bg-primary-subtle text-primary rounded-pill">

                                0 seleccionadas

                            </span>

                        </div>


                        <p class="text-muted small mb-3">

                            Selecciona las tablas que pertenecerán a este módulo.

                        </p>



                        <?php if (!empty($tablasArray)): ?>


                            <div
                                class="border rounded-3 p-3"
                                style="max-height: 300px; overflow-y: auto;">


                                <?php foreach ($tablasArray as $tabla): ?>


                                    <div class="form-check py-2">


                                        <input
                                            class="form-check-input checkbox-tabla-modulo"
                                            type="checkbox"
                                            name="tablas[]"
                                            value="<?= htmlspecialchars($tabla['id_tablas']) ?>"
                                            id="tabla_<?= htmlspecialchars($tabla['id_tablas']) ?>">


                                        <label
                                            class="form-check-label"
                                            for="tabla_<?= htmlspecialchars($tabla['id_tablas']) ?>">

                                            <i class="bi bi-table me-2 text-primary"></i>

                                            <?= htmlspecialchars($tabla['nombre_tabla']) ?>

                                        </label>


                                    </div>


                                <?php endforeach; ?>


                            </div>


                        <?php else: ?>


                            <div class="alert alert-light border small">

                                <i class="bi bi-info-circle me-2"></i>

                                No hay tablas disponibles para asignar.

                            </div>


                        <?php endif; ?>


                    </div>


                </div>



                <!-- FOOTER -->

                <div class="modal-footer border-0 px-4 pb-4">


                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-check-lg me-1"></i>

                        Guardar Cambios

                    </button>


                </div>


            </form>

        </div>

    </div>

</div>



<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script>

$(document).ready(function () {


    /*
     * =========================================================
     * BUSCADOR
     * =========================================================
     */

    $("#buscarModulo").on("keyup", function () {

        const valor = $(this).val().toLowerCase();

        $("#tablaModulos tr").each(function () {

            const texto = $(this).text().toLowerCase();

            $(this).toggle(texto.includes(valor));

        });

    });



    /*
     * =========================================================
     * CONTADOR DE TABLAS SELECCIONADAS
     * =========================================================
     */

    $(document).on("change", ".checkbox-tabla-modulo", function () {

        actualizarContadorTablas();

    });


});


/*
 * =========================================================
 * EDITAR MÓDULO
 * =========================================================
 */

function editarModulo(id, nombre, tablasAsignadas) {


    /*
     * Cargar información básica
     */

    document.getElementById("id_modulos").value = id;

    document.getElementById("nombre_modulo_modal").value = nombre;

    document.getElementById("action").value = "actualizacion";


    /*
     * Desmarcar todas las tablas
     */

    document
        .querySelectorAll(".checkbox-tabla-modulo")
        .forEach(function (checkbox) {

            checkbox.checked = false;

        });


    /*
     * Marcar las tablas pertenecientes
     * al módulo seleccionado.
     */

    if (Array.isArray(tablasAsignadas)) {

        tablasAsignadas.forEach(function (id_tabla) {

            const checkbox = document.getElementById(
                "tabla_" + id_tabla
            );

            if (checkbox) {

                checkbox.checked = true;

            }

        });

    }


    /*
     * Actualizar contador
     */

    actualizarContadorTablas();


    /*
     * Abrir modal
     */

    const modal = new bootstrap.Modal(
        document.getElementById("modalModulo")
    );

    modal.show();

}


/*
 * =========================================================
 * CONTADOR DE TABLAS
 * =========================================================
 */

function actualizarContadorTablas() {


    const cantidad = document.querySelectorAll(
        ".checkbox-tabla-modulo:checked"
    ).length;


    const contador = document.getElementById(
        "contadorTablasSeleccionadas"
    );


    if (contador) {

        contador.textContent =
            cantidad + (cantidad === 1
                ? " seleccionada"
                : " seleccionadas");

    }

}


/*
 * =========================================================
 * ELIMINAR MÓDULO
 * =========================================================
 */

function eliminarModulo(id, nombre) {


    Swal.fire({

        title: "¿Eliminar módulo?",

        html:
            'Se eliminará el módulo <strong>' +
            escapeHtml(nombre) +
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


        /*
         * Creamos un formulario dinámicamente
         * para conservar exactamente el método
         * POST utilizado por el controlador actual.
         */

        const form = $("<form>", {

            method: "POST",

            action: "controladores/modulos/modulo_controlador.php"

        });


        form.append(
            $("<input>", {
                type: "hidden",
                name: "id_modulos",
                value: id
            })
        );


        form.append(
            $("<input>", {
                type: "hidden",
                name: "action",
                value: "eliminacion"
            })
        );


        $("body").append(form);

        form.submit();

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
