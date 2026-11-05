<?php
include_once "modelos/dias.php";

$dias = new Dias();
$lista_dias = $dias->consultarVariosDias();
?>

<div class="container-fluid py-4">

    <!-- ENCABEZADO -->
    <div class="mb-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h2 class="fw-bold mb-1">
                    Días
                </h2>

                <p class="text-muted mb-0">
                    Administración de los días disponibles para la gestión de turnos.
                </p>
            </div>

            <!-- BOTÓN NUEVO DÍA -->
            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNuevoDia"
                aria-controls="offcanvasNuevoDia">

                <i class="fa-solid fa-plus me-2"></i>
                Nuevo Día

            </button>

        </div>

    </div>


    <!-- TABLA -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <!-- CABECERA DE TABLA -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                <div>

                    <h5 class="fw-semibold mb-0">
                        Días Registrados
                    </h5>

                    <small class="text-muted">
                        Consulta y administración de los días disponibles.
                    </small>

                </div>

            </div>


            <!-- TABLA RESPONSIVE -->
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3" style="width: 80px;">
                                ID
                            </th>

                            <th>
                                Día
                            </th>

                            <th class="text-center" style="width: 150px;">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($lista_dias): ?>

                            <?php foreach ($lista_dias as $row): ?>

                                <tr>

                                    <!-- ID -->
                                    <td class="ps-3 text-muted">
                                        <?= htmlspecialchars($row['id_dias']) ?>
                                    </td>


                                    <!-- DÍA -->
                                    <td>

                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-semibold">
                                                    <?= htmlspecialchars($row['descripcion']) ?>
                                                </div>

                                                <small class="text-muted">
                                                    Día disponible
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- ACCIONES -->
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-2">

                                            <!-- EDITAR -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal<?= $row['id_dias'] ?>"
                                                title="Editar">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- ELIMINAR -->
                                            <form
                                                action="controladores/dias_controlador.php"
                                                method="post"
                                                class="d-inline">

                                                <input
                                                    type="hidden"
                                                    name="id_dias"
                                                    value="<?= htmlspecialchars($row['id_dias']) ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Está seguro de eliminar este día?');">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <!-- ================================================= -->
                                <!-- MODAL EDITAR DÍA -->
                                <!-- ================================================= -->

                                <div
                                    class="modal fade"
                                    id="modal<?= $row['id_dias'] ?>"
                                    tabindex="-1"
                                    aria-labelledby="modalLabel<?= $row['id_dias'] ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 shadow rounded-4">

                                            <form
                                                class="needs-validation"
                                                novalidate
                                                action="controladores/dias_controlador.php"
                                                method="post">


                                                <!-- HEADER -->
                                                <div class="modal-header border-bottom">

                                                    <div>

                                                        <h5
                                                            class="modal-title fw-semibold"
                                                            id="modalLabel<?= $row['id_dias'] ?>">

                                                            Modificar Día

                                                        </h5>

                                                        <small class="text-muted">
                                                            Actualice el nombre del día.
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
                                                <div class="modal-body">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">

                                                    <input
                                                        type="hidden"
                                                        name="id_dias"
                                                        value="<?= htmlspecialchars($row['id_dias']) ?>">


                                                    <!-- DÍA -->
                                                    <div class="mb-3">

                                                        <label
                                                            for="descripcion<?= $row['id_dias'] ?>"
                                                            class="form-label fw-semibold">

                                                            Día

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="descripcion<?= $row['id_dias'] ?>"
                                                            name="descripcion"
                                                            value="<?= htmlspecialchars($row['descripcion']) ?>"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Ingrese el día.
                                                        </div>

                                                    </div>

                                                </div>


                                                <!-- FOOTER -->
                                                <div class="modal-footer border-top">

                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">

                                                        Cancelar

                                                    </button>

                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary">

                                                        <i class="fa-solid fa-floppy-disk me-2"></i>
                                                        Guardar Cambios

                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <!-- SIN REGISTROS -->
                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fa-solid fa-calendar-days fs-2 mb-3"></i>

                                        <p class="mb-1 fw-semibold">
                                            No hay días registrados
                                        </p>

                                        <small>
                                            Utilice el botón "Nuevo Día" para agregar uno.
                                        </small>

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
<!-- OFFCANVAS NUEVO DÍA -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoDia"
    aria-labelledby="offcanvasNuevoDiaLabel">


    <!-- HEADER -->
    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoDiaLabel">

                Nuevo Día

            </h5>

            <small class="text-muted">
                Registre un nuevo día en el sistema.
            </small>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar">
        </button>

    </div>


    <!-- BODY -->
    <div class="offcanvas-body">

        <form
            class="needs-validation"
            novalidate
            method="post"
            action="controladores/dias_controlador.php">

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- DÍA -->
            <div class="mb-4">

                <label
                    for="descripcion"
                    class="form-label fw-semibold">

                    Día

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="descripcion"
                    placeholder="Ingrese el día"
                    name="descripcion"
                    required>

                <div class="invalid-feedback">
                    Ingrese el día.
                </div>

            </div>


            <!-- INFORMACIÓN -->
            <div class="alert alert-light border rounded-3">

                <div class="d-flex">

                    <i class="fa-solid fa-circle-info text-primary mt-1 me-2"></i>

                    <small class="text-muted">
                        Los días registrados estarán disponibles para
                        configurar las agendas de los profesionales.
                    </small>

                </div>

            </div>


            <!-- BOTÓN -->
            <div class="d-grid mt-4">

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    Registrar Día

                </button>

            </div>

        </form>

    </div>

</div>


<!-- VALIDACIONES -->
<script src="assets/js/validaciones/validaciones_controlador.js"></script>