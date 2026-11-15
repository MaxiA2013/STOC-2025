<?php
include_once "modelos/condicion.php";

$condicion = new Condicion();
$lista_condicion = $condicion->consultarVariasCondiciones();
?>

<div class="container-fluid py-4 px-4">

    <!-- Encabezado -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="fw-semibold mb-0">Gestión de Condiciones</h2>
            </div>

            <p class="text-muted mb-0">
                Administra las condiciones registradas en el sistema.
            </p>
        </div>

        <!-- Botón nueva condición -->
        <button
            type="button"
            class="btn btn-primary d-flex align-items-center gap-2 px-3"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevaCondicion"
            aria-controls="offcanvasNuevaCondicion">

            <i class="fa-solid fa-plus"></i>
            Nueva condición

        </button>

    </div>


    <!-- Contenedor principal -->
    <div class="card border-0 shadow-sm">

        <!-- Cabecera de la tabla -->
        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Condiciones registradas
                    </h5>

                    <small class="text-muted">
                        Consulta y administra las condiciones disponibles.
                    </small>
                </div>

                <!-- Buscador -->
                <div class="input-group" style="max-width: 320px;">

                    <span class="input-group-text bg-white">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control"
                        id="buscarCondicion"
                        placeholder="Buscar condición..."
                        autocomplete="off">

                </div>

            </div>

        </div>


        <!-- Tabla -->
        <div class="card-body pt-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0" id="tablaCondiciones">

                    <thead class="table-light">

                        <tr>
                            <th scope="col" class="text-muted small">ID</th>
                            <th scope="col" class="text-muted small">Condición</th>
                            <th scope="col" class="text-muted small">Detalle</th>
                            <th scope="col" class="text-muted small text-center">Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if (!empty($lista_condicion)) { ?>

                            <?php foreach ($lista_condicion as $row) { ?>

                                <tr class="fila-condicion">

                                    <!-- ID -->
                                    <td class="fw-semibold text-muted">
                                        <?php echo htmlspecialchars($row['id_condicion']); ?>
                                    </td>


                                    <!-- Condición -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-semibold">
                                                <?php echo htmlspecialchars($row['nombre_condicion']); ?>
                                            </span>

                                        </div>

                                    </td>


                                    <!-- Detalle -->
                                    <td class="text-muted">
                                        <?php echo htmlspecialchars($row['detalle']); ?>
                                    </td>


                                    <!-- Acciones -->
                                    <td>

                                        <div class="d-flex justify-content-center gap-2">

                                            <!-- Editar -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal<?php echo $row['id_condicion']; ?>"
                                                title="Editar condición">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- Eliminar -->
                                            <form
                                                action="controladores/condicion_controlador.php"
                                                method="post"
                                                class="d-inline"
                                                onsubmit="return confirm('¿Está seguro de eliminar esta condición?');">

                                                <input
                                                    type="hidden"
                                                    name="id_condicion"
                                                    value="<?php echo htmlspecialchars($row['id_condicion']); ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar condición">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <!-- Modal editar -->
                                <div
                                    class="modal fade"
                                    id="modal<?php echo $row['id_condicion']; ?>"
                                    tabindex="-1"
                                    aria-labelledby="modalLabel<?php echo $row['id_condicion']; ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 shadow">

                                            <div class="modal-header">

                                                <div>

                                                    <h5
                                                        class="modal-title fw-semibold"
                                                        id="modalLabel<?php echo $row['id_condicion']; ?>">

                                                        Modificar condición

                                                    </h5>

                                                    <small class="text-muted">
                                                        Actualiza la información de la condición.
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
                                                action="controladores/condicion_controlador.php"
                                                method="post">

                                                <div class="modal-body">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">

                                                    <input
                                                        type="hidden"
                                                        name="id_condicions"
                                                        value="<?php echo htmlspecialchars($row['id_condicion']); ?>">


                                                    <!-- Condición -->
                                                    <div class="mb-3">

                                                        <label
                                                            for="nombre_condicion<?php echo $row['id_condicion']; ?>"
                                                            class="form-label fw-semibold">

                                                            Condición

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="nombre_condicion<?php echo $row['id_condicion']; ?>"
                                                            name="nombre_condicion"
                                                            value="<?php echo htmlspecialchars($row['nombre_condicion']); ?>"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Campo Condición vacío.
                                                        </div>

                                                    </div>


                                                    <!-- Detalle -->
                                                    <div class="mb-3">

                                                        <label
                                                            for="detalle<?php echo $row['id_condicion']; ?>"
                                                            class="form-label fw-semibold">

                                                            Detalle

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="detalle<?php echo $row['id_condicion']; ?>"
                                                            name="detalle"
                                                            value="<?php echo htmlspecialchars($row['detalle']); ?>"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Campo Detalle vacío.
                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="modal-footer">

                                                    <button
                                                        type="button"
                                                        class="btn btn-light"
                                                        data-bs-dismiss="modal">

                                                        Cerrar

                                                    </button>

                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary d-flex align-items-center gap-2">

                                                        <i class="fa-solid fa-floppy-disk"></i>
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

                                    <i class="fa-solid fa-clipboard-list fs-4 mb-2 d-block"></i>

                                    No hay condiciones registradas.

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>


            <!-- Mensaje de búsqueda sin resultados -->
            <div
                id="sinResultadosCondicion"
                class="text-center text-muted py-4 d-none">

                <i class="fa-solid fa-magnifying-glass fs-4 mb-2 d-block"></i>

                No se encontraron condiciones que coincidan con la búsqueda.

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     OFFCANVAS - NUEVA CONDICIÓN
========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevaCondicion"
    aria-labelledby="offcanvasNuevaCondicionLabel">

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevaCondicionLabel">

                Nueva condición

            </h5>

            <small class="text-muted">
                Registra una nueva condición en el sistema.
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
            class="needs-validation"
            novalidate
            method="post"
            action="controladores/condicion_controlador.php">

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- Condición -->
            <div class="mb-3">

                <label
                    for="nombre_condicion"
                    class="form-label fw-semibold">

                    Condición

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_condicion"
                    placeholder="Ingrese la condición"
                    name="nombre_condicion"
                    required>

                <div class="invalid-feedback">
                    Campo Condición vacío.
                </div>

            </div>


            <!-- Detalle -->
            <div class="mb-3">

                <label
                    for="detalle"
                    class="form-label fw-semibold">

                    Detalle

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="detalle"
                    placeholder="Ingrese el detalle"
                    name="detalle"
                    required>

                <div class="invalid-feedback">
                    Campo Detalle vacío.
                </div>

            </div>


            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="offcanvas">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="btn btn-primary d-flex align-items-center gap-2">

                    <i class="fa-solid fa-plus"></i>
                    Agregar condición

                </button>

            </div>

        </form>

    </div>

</div>


<!-- =========================================================
     VALIDACIONES
========================================================= -->

<script src="assets/js/validaciones/validaciones_controlador.js"></script>


<!-- =========================================================
     BUSCADOR
========================================================= -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const buscador = document.getElementById("buscarCondicion");
    const filas = document.querySelectorAll(".fila-condicion");
    const mensajeSinResultados = document.getElementById("sinResultadosCondicion");

    if (!buscador) {
        return;
    }

    buscador.addEventListener("keyup", function () {

        const texto = this.value.toLowerCase().trim();

        let encontrados = 0;

        filas.forEach(function (fila) {

            const contenido = fila.textContent.toLowerCase();

            if (contenido.includes(texto)) {

                fila.style.display = "";
                encontrados++;

            } else {

                fila.style.display = "none";

            }

        });

        if (mensajeSinResultados) {

            if (encontrados === 0 && texto !== "") {

                mensajeSinResultados.classList.remove("d-none");

            } else {

                mensajeSinResultados.classList.add("d-none");

            }

        }

    });

});

</script>