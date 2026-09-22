<?php

include_once "modelos/estados.php";

$stat = new Estado();

$lista_estados = $stat->consultarVariosEstados();

?>

<div class="container-fluid py-4 px-4">

    <!-- ===================================================== -->
    <!-- ENCABEZADO -->
    <!-- ===================================================== -->

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-semibold mb-1">
                Gestión de Estados
            </h2>

            <p class="text-muted mb-0">
                Administra los estados utilizados en el sistema.
            </p>

        </div>


        <!-- BOTÓN NUEVO ESTADO -->

        <div class="mt-3 mt-md-0">

            <button
                type="button"
                class="btn btn-primary px-4"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNuevoEstado"
                aria-controls="offcanvasNuevoEstado">

                <i class="fa-solid fa-plus me-2"></i>

                Nuevo Estado

            </button>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- TARJETA PRINCIPAL -->
    <!-- ===================================================== -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">


            <!-- CABECERA DE LA TABLA -->

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

                <div>

                    <h5 class="fw-semibold mb-1">
                        Estados registrados
                    </h5>

                    <small class="text-muted">
                        Estados disponibles para utilizar en el sistema.
                    </small>

                </div>


                <!-- BUSCADOR -->

                <div class="mt-3 mt-md-0">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="fa-solid fa-magnifying-glass text-muted"></i>

                        </span>

                        <input
                            type="text"
                            id="buscarEstado"
                            class="form-control"
                            placeholder="Buscar estado...">

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- TABLA -->
            <!-- ================================================= -->

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                    id="tablaEstados">

                    <thead class="table-light">

                        <tr>

                            <th>ID</th>

                            <th>Estado</th>

                            <th>Descripción</th>

                            <th class="text-center">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php

                        if ($lista_estados) {

                            foreach ($lista_estados as $row) {

                        ?>

                                <tr>


                                    <!-- ID -->

                                    <td>

                                        <span class="text-muted">

                                            #<?= $row['id_estados'] ?>

                                        </span>

                                    </td>


                                    <!-- ESTADO -->

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>

                                                <span class="fw-semibold d-block">

                                                    <?= htmlspecialchars($row['tipo_estado']) ?>

                                                </span>

                                                <small class="text-muted">
                                                    Estado del sistema
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- DESCRIPCIÓN -->

                                    <td>

                                        <span class="text-muted">

                                            <?= htmlspecialchars($row['descripcion']) ?>

                                        </span>

                                    </td>


                                    <!-- ACCIONES -->

                                    <td>

                                        <div class="d-flex justify-content-center gap-2">


                                            <!-- EDITAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Editar estado"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal<?= $row['id_estados'] ?>">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- ELIMINAR -->

                                            <form
                                                action="controladores/estados_controlador.php"
                                                method="post"
                                                class="d-inline">

                                                <input
                                                    type="hidden"
                                                    name="id_estados"
                                                    value="<?= $row['id_estados'] ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar estado">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <!-- ================================================= -->
                                <!-- MODAL EDITAR ESTADO -->
                                <!-- ================================================= -->

                                <div
                                    class="modal fade"
                                    id="modal<?= $row['id_estados'] ?>"
                                    tabindex="-1"
                                    aria-labelledby="modalLabel<?= $row['id_estados'] ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog">

                                        <div class="modal-content">


                                            <!-- HEADER -->

                                            <div class="modal-header">

                                                <div>

                                                    <h5
                                                        class="modal-title fw-semibold"
                                                        id="modalLabel<?= $row['id_estados'] ?>">

                                                        Editar Estado

                                                    </h5>

                                                    <small class="text-muted">

                                                        Modifica la información del estado.

                                                    </small>

                                                </div>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Cerrar">
                                                </button>

                                            </div>


                                            <!-- FORMULARIO -->

                                            <form
                                                class="needs-validation"
                                                novalidate
                                                action="controladores/estados_controlador.php"
                                                method="post">


                                                <div class="modal-body">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">

                                                    <input
                                                        type="hidden"
                                                        name="id_estados"
                                                        value="<?= $row['id_estados'] ?>">


                                                    <!-- ESTADO -->

                                                    <div class="mb-3">

                                                        <label
                                                            for="tipo_estado<?= $row['id_estados'] ?>"
                                                            class="form-label fw-semibold">

                                                            Estado

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="tipo_estado<?= $row['id_estados'] ?>"
                                                            name="tipo_estado"
                                                            value="<?= htmlspecialchars($row['tipo_estado']) ?>"
                                                            required>

                                                        <div class="invalid-feedback">

                                                            Campo de nombre de estado vacío.

                                                        </div>

                                                    </div>


                                                    <!-- DESCRIPCIÓN -->

                                                    <div class="mb-3">

                                                        <label
                                                            for="descripcion<?= $row['id_estados'] ?>"
                                                            class="form-label fw-semibold">

                                                            Descripción

                                                        </label>

                                                        <textarea
                                                            class="form-control"
                                                            id="descripcion<?= $row['id_estados'] ?>"
                                                            name="descripcion"
                                                            rows="3"
                                                            required><?= htmlspecialchars($row['descripcion']) ?></textarea>

                                                        <div class="invalid-feedback">

                                                            Campo descripción vacío.

                                                        </div>

                                                    </div>

                                                </div>


                                                <!-- FOOTER -->

                                                <div class="modal-footer">

                                                    <button
                                                        type="button"
                                                        class="btn btn-light border"
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

                        <?php

                            }

                        }

                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- OFFCANVAS NUEVO ESTADO -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoEstado"
    aria-labelledby="offcanvasNuevoEstadoLabel"
    style="width: 430px;">

    <!-- HEADER -->

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoEstadoLabel">

                Nuevo Estado

            </h5>

            <small class="text-muted">

                Registra un nuevo estado para el sistema.

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
            action="controladores/estados_controlador.php">


            <!-- ACTION -->

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- ESTADO -->

            <div class="mb-4">

                <label
                    for="tipo_estado"
                    class="form-label fw-semibold">

                    Estado

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="tipo_estado"
                    name="tipo_estado"
                    placeholder="Ej. Activo"
                    required>

                <div class="invalid-feedback">

                    Campo de nombre de estado vacío.

                </div>

                <div class="form-text">

                    Introduce el nombre que identificará al estado.

                </div>

            </div>


            <!-- DESCRIPCIÓN -->

            <div class="mb-4">

                <label
                    for="descripcion"
                    class="form-label fw-semibold">

                    Descripción

                </label>

                <textarea
                    class="form-control"
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    placeholder="Describe brevemente el estado..."
                    required></textarea>

                <div class="invalid-feedback">

                    Campo descripción vacío.

                </div>

            </div>


            <!-- INFORMACIÓN -->

            <div class="alert alert-primary border-0 small">

                <i class="fa-solid fa-circle-info me-2"></i>

                El estado quedará disponible para los módulos
                que utilicen esta configuración.

            </div>


            <!-- BOTONES -->

            <div class="d-flex gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-light border flex-fill"
                    data-bs-dismiss="offcanvas">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="btn btn-primary flex-fill">

                    <i class="fa-solid fa-plus me-2"></i>

                    Crear Estado

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- BUSCADOR -->
<!-- ========================================================= -->

<script>

    const buscadorEstado =
        document.getElementById('buscarEstado');

    if (buscadorEstado) {

        buscadorEstado.addEventListener('keyup', function () {

            const texto =
                this.value.toLowerCase();

            const filas =
                document.querySelectorAll(
                    '#tablaEstados tbody tr'
                );

            filas.forEach(function (fila) {

                const contenido =
                    fila.textContent.toLowerCase();

                fila.style.display =
                    contenido.includes(texto)
                        ? ''
                        : 'none';

            });

        });

    }

</script>


<!-- ========================================================= -->
<!-- VALIDACIONES BOOTSTRAP -->
<!-- ========================================================= -->

<script src="assets/js/validaciones/validaciones_controlador.js"></script>