<?php

include_once "modelos/pais.php";

$pais = new Pais();
$lista_paises = $pais->consultarVariosPaises();
?>

<div class="container-fluid py-4 px-4">
    <!-- ENCABEZADO -->

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-semibold mb-1">
                Gestión de Países
            </h2>

            <p class="text-muted mb-0">
                Administra los países utilizados en el sistema.
            </p>

        </div>


        <!-- BOTÓN NUEVO PAÍS -->

        <div class="mt-3 mt-md-0">

            <button
                type="button"
                class="btn btn-primary px-4"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNuevoPais"
                aria-controls="offcanvasNuevoPais">

                <i class="fa-solid fa-plus me-2"></i>

                Nuevo País

            </button>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- TARJETA PRINCIPAL -->
    <!-- ===================================================== -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">


            <!-- ================================================= -->
            <!-- CABECERA DE LA TABLA -->
            <!-- ================================================= -->

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

                <div>

                    <h5 class="fw-semibold mb-1">
                        Países registrados
                    </h5>

                    <small class="text-muted">
                        Países disponibles para utilizar en el sistema.
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
                            id="buscarPais"
                            class="form-control"
                            placeholder="Buscar país..."
                            autocomplete="off">

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- TABLA -->
            <!-- ================================================= -->

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                    id="tablaPaises">

                    <thead class="table-light">

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                País
                            </th>

                            <th class="text-center">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if ($lista_paises && $lista_paises->num_rows > 0): ?>

                            <?php while ($row = $lista_paises->fetch_assoc()): ?>

                                <tr>

                                    <!-- ================================================= -->
                                    <!-- ID -->
                                    <!-- ================================================= -->

                                    <td>

                                        <span class="text-muted">

                                            #<?= (int) $row['id_pais'] ?>

                                        </span>

                                    </td>


                                    <!-- ================================================= -->
                                    <!-- PAÍS -->
                                    <!-- ================================================= -->

                                    <td>

                                        <div>

                                            <span class="fw-semibold d-block">

                                                <?= htmlspecialchars(
                                                    $row['nombre_pais'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </span>

                                            <small class="text-muted">

                                                País del sistema

                                            </small>

                                        </div>

                                    </td>


                                    <!-- ================================================= -->
                                    <!-- ACCIONES -->
                                    <!-- ================================================= -->

                                    <td>

                                        <div class="d-flex justify-content-center gap-2">


                                            <!-- EDITAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Editar país"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal<?= (int) $row['id_pais'] ?>">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- ELIMINAR -->

                                            <form
                                                action="controladores/pais/pais_controlador.php"
                                                method="post"
                                                class="d-inline">

                                                <input
                                                    type="hidden"
                                                    name="id_pais"
                                                    value="<?= (int) $row['id_pais'] ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar país">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <!-- ================================================= -->
                                <!-- MODAL EDITAR PAÍS -->
                                <!-- ================================================= -->

                                <div
                                    class="modal fade"
                                    id="modal<?= (int) $row['id_pais'] ?>"
                                    tabindex="-1"
                                    aria-labelledby="modalLabel<?= (int) $row['id_pais'] ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog">

                                        <div class="modal-content">


                                            <!-- HEADER -->

                                            <div class="modal-header">

                                                <div>

                                                    <h5
                                                        class="modal-title fw-semibold"
                                                        id="modalLabel<?= (int) $row['id_pais'] ?>">

                                                        Editar País

                                                    </h5>

                                                    <small class="text-muted">

                                                        Modifica la información del país.

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
                                                action="controladores/pais/pais_controlador.php"
                                                method="post">

                                                <div class="modal-body">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">

                                                    <input
                                                        type="hidden"
                                                        name="id_pais"
                                                        value="<?= (int) $row['id_pais'] ?>">


                                                    <!-- PAÍS -->

                                                    <div class="mb-3">

                                                        <label
                                                            for="nombre_pais<?= (int) $row['id_pais'] ?>"
                                                            class="form-label fw-semibold">

                                                            País

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="nombre_pais<?= (int) $row['id_pais'] ?>"
                                                            name="nombre_pais"
                                                            value="<?= htmlspecialchars(
                                                                $row['nombre_pais'],
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?>"
                                                            required>

                                                        <div class="invalid-feedback">

                                                            Campo nombre de país vacío.

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

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="3" class="text-center py-4">

                                    <span class="text-muted">

                                        No hay países registrados.

                                    </span>

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
<!-- OFFCANVAS NUEVO PAÍS -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoPais"
    aria-labelledby="offcanvasNuevoPaisLabel"
    style="width: 430px;">

    <!-- HEADER -->

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoPaisLabel">

                Nuevo País

            </h5>

            <small class="text-muted">

                Registra un nuevo país para el sistema.

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
            action="controladores/pais/pais_controlador.php">

            <!-- ACTION -->

            <input
                type="hidden"
                name="action"
                value="insertar">
            <!-- PAÍS -->

            <div class="mb-4">

                <label
                    for="nombre_pais"
                    class="form-label fw-semibold">
                    País
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_pais"
                    name="nombre_pais"
                    placeholder="Ej. Argentina"
                    required>

                <div class="invalid-feedback">

                    Campo nombre de país vacío.

                </div>

                <div class="form-text">

                    Introduce el nombre que identificará al país.

                </div>

            </div>


            <!-- INFORMACIÓN -->

            <div class="alert alert-primary border-0 small">

                <i class="fa-solid fa-circle-info me-2"></i>

                El país quedará disponible para los módulos
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

                    Crear País

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- BUSCADOR -->
<!-- ========================================================= -->

<script>

    const buscadorPais =
        document.getElementById('buscarPais');

    if (buscadorPais) {

        buscadorPais.addEventListener('keyup', function () {

            const texto =
                this.value.toLowerCase().trim();

            const filas =
                document.querySelectorAll(
                    '#tablaPaises tbody tr'
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
<script src="assets/js/validaciones/validaciones_controlador.js"></script>