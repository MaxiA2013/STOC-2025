<?php
include_once "modelos/familiar.php";

$fam = new Familiar();
$lista_fam = $fam->consultarVariosFamiliar();
?>

<div class="container-fluid py-4">
<!-- ENCABEZADO -->
<div class="mb-4">

    <h2 class="fw-bold mb-1">
        Gestión de Familiares
    </h2>

    <p class="text-muted mb-0">
        Administración de los tipos de relación familiar disponibles en el sistema.
    </p>

</div>


<!-- LISTADO -->
<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <!-- CABECERA DE LA TABLA -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">

            <div>

                <h5 class="mb-1 fw-semibold">
                    Tipos de Familiares
                </h5>

                <small class="text-muted">
                    Consulta y administración de las relaciones familiares.
                </small>

            </div>


            <!-- BOTÓN NUEVO -->
            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNuevoFamiliar"
                aria-controls="offcanvasNuevoFamiliar">

                <i class="fa-solid fa-plus me-2"></i>
                Nuevo Familiar

            </button>

        </div>


        <!-- TABLA -->
        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>ID</th>

                        <th>Relación</th>

                        <th>Descripción</th>

                        <th class="text-center">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if ($lista_fam) { ?>

                        <?php foreach ($lista_fam as $row) { ?>

                            <tr>

                                <!-- ID -->
                                <td>

                                    <span class="text-muted">
                                        #<?php echo $row['id_familiar']; ?>
                                    </span>

                                </td>


                                <!-- RELACIÓN -->
                                <td>

                                    <span class="fw-semibold">
                                        <?php echo $row['relacion']; ?>
                                    </span>

                                </td>


                                <!-- DESCRIPCIÓN -->
                                <td>

                                    <span class="text-muted">
                                        <?php echo $row['descripcion']; ?>
                                    </span>

                                </td>


                                <!-- ACCIONES -->
                                <td>

                                    <div class="d-flex justify-content-center gap-2">

                                        <!-- EDITAR -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Editar"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal<?php echo $row['id_familiar']; ?>">

                                            <i class="fa-solid fa-pen"></i>

                                        </button>


                                        <!-- ELIMINAR -->
                                        <form
                                            action="controladores/familiar_controlador.php"
                                            method="post"
                                            class="d-inline">

                                            <input
                                                type="hidden"
                                                name="id_familiar"
                                                value="<?php echo $row['id_familiar']; ?>">

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="eliminacion">

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            <!-- MODAL EDITAR -->
                            <div
                                class="modal fade"
                                id="modal<?php echo $row['id_familiar']; ?>"
                                tabindex="-1"
                                aria-labelledby="modalLabel<?php echo $row['id_familiar']; ?>"
                                aria-hidden="true">

                                <div class="modal-dialog">

                                    <div class="modal-content border-0 shadow rounded-4">

                                        <div class="modal-header">

                                            <div>

                                                <h5
                                                    class="modal-title fw-semibold"
                                                    id="modalLabel<?php echo $row['id_familiar']; ?>">

                                                    Modificar Familiar

                                                </h5>

                                                <small class="text-muted">
                                                    Modifica los datos de la relación familiar.
                                                </small>

                                            </div>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Cerrar">
                                            </button>

                                        </div>


                                        <form
                                            class="needs-validation"
                                            novalidate
                                            action="controladores/familiar_controlador.php"
                                            method="post">

                                            <div class="modal-body">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="actualizacion">

                                                <input
                                                    type="hidden"
                                                    name="id_familiar"
                                                    value="<?php echo $row['id_familiar']; ?>">


                                                <!-- RELACIÓN -->
                                                <div class="mb-3">

                                                    <label
                                                        for="relacion<?php echo $row['id_familiar']; ?>"
                                                        class="form-label">

                                                        Relación

                                                    </label>

                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="relacion<?php echo $row['id_familiar']; ?>"
                                                        name="relacion"
                                                        value="<?php echo $row['relacion']; ?>"
                                                        placeholder="Ej. Padre, Madre, Hermano..."
                                                        required>

                                                    <div class="invalid-feedback">
                                                        Campo nombre de relación vacío.
                                                    </div>

                                                </div>


                                                <!-- DESCRIPCIÓN -->
                                                <div class="mb-3">

                                                    <label
                                                        for="descripcion<?php echo $row['id_familiar']; ?>"
                                                        class="form-label">

                                                        Descripción

                                                    </label>

                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="descripcion<?php echo $row['id_familiar']; ?>"
                                                        name="descripcion"
                                                        value="<?php echo $row['descripcion']; ?>"
                                                        placeholder="Ingrese una descripción"
                                                        required>

                                                    <div class="invalid-feedback">
                                                        Campo descripción vacío.
                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-light"
                                                    data-bs-dismiss="modal">

                                                    Cancelar

                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary">

                                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                                    Guardar cambios

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td
                                colspan="4"
                                class="text-center text-muted py-4">

                                <i class="fa-solid fa-circle-info me-2"></i>
                                No hay relaciones familiares registradas.

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</div>

<!-- ========================================================= -->

<!-- OFFCANVAS NUEVO FAMILIAR -->

<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoFamiliar"
    aria-labelledby="offcanvasNuevoFamiliarLabel">
<!-- CABECERA -->
<div class="offcanvas-header border-bottom">

    <div>

        <h5
            class="offcanvas-title fw-semibold"
            id="offcanvasNuevoFamiliarLabel">

            Nuevo Familiar

        </h5>

        <small class="text-muted">
            Registra un nuevo tipo de relación familiar.
        </small>

    </div>


    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="offcanvas"
        aria-label="Cerrar">
    </button>

</div>


<!-- CUERPO -->
<div class="offcanvas-body">

    <form
        class="needs-validation"
        novalidate
        method="post"
        action="controladores/familiar_controlador.php">


        <input
            type="hidden"
            name="action"
            value="insertar">


        <!-- RELACIÓN -->
        <div class="mb-3">

            <label
                for="relacion"
                class="form-label">

                Relación

            </label>

            <input
                type="text"
                class="form-control"
                id="relacion"
                name="relacion"
                placeholder="Ej. Padre, Madre, Hermano..."
                required>

            <div class="invalid-feedback">
                Campo nombre de la relación vacío.
            </div>

        </div>


        <!-- DESCRIPCIÓN -->
        <div class="mb-4">

            <label
                for="descripcion"
                class="form-label">

                Descripción

            </label>

            <input
                type="text"
                class="form-control"
                id="descripcion"
                name="descripcion"
                placeholder="Ingrese una descripción"
                required>

            <div class="invalid-feedback">
                Campo descripción vacío.
            </div>

        </div>


        <!-- BOTÓN -->
        <div class="d-grid">

            <button
                type="submit"
                class="btn btn-primary">

                <i class="fa-solid fa-floppy-disk me-2"></i>
                Guardar Familiar

            </button>

        </div>

    </form>

</div>
</div>

<!-- VALIDACIONES -->

<script src="assets/js/validaciones/validaciones_controlador.js"></script>
