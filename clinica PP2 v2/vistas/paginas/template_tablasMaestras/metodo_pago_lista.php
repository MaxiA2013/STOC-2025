<?php

include_once "modelos/metodo_pago.php";

$metodo_pago = new Metodo_pago();
$lista_metodo_pago = $metodo_pago->consultarVariosMetodosPago();

?>

<div class="container-fluid py-4 px-4">

    <!-- =========================================================
         ENCABEZADO
         ========================================================= -->

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Gestión de Métodos de Pago
            </h2>

            <p class="text-muted mb-0">
                Administra los métodos de pago disponibles en el sistema.
            </p>

        </div>


        <!-- Botón nuevo método -->
        <button
            type="button"
            class="btn btn-primary px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoMetodo">

            <i class="fa-solid fa-plus me-2"></i>
            Nuevo método

        </button>

    </div>


    <!-- =========================================================
         TARJETA PRINCIPAL
         ========================================================= -->

    <div class="card border-0 shadow-sm">

        <!-- Cabecera -->
        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Métodos de pago registrados
                    </h5>

                    <small class="text-muted">
                        Listado de métodos de pago disponibles.
                    </small>

                </div>


                <!-- Buscador -->
                <div
                    class="position-relative"
                    style="max-width: 320px; width: 100%;">

                    <i
                        class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                    </i>

                    <input
                        type="text"
                        id="buscarMetodo"
                        class="form-control ps-5"
                        placeholder="Buscar método...">

                </div>

            </div>

        </div>


        <!-- =====================================================
             CUERPO
             ===================================================== -->

        <div class="card-body px-4 pb-4">

            <div class="table-responsive">

                <table class="table align-middle mb-0" id="tablaMetodos">

                    <thead class="table-light">

                        <tr>

                            <th class="text-muted small fw-semibold">
                                ID
                            </th>

                            <th class="text-muted small fw-semibold">
                                Método de pago
                            </th>

                            <th class="text-muted small fw-semibold text-end">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($lista_metodo_pago as $row): ?>

                            <tr class="fila-metodo">

                                <!-- ID -->
                                <td>

                                    <span class="text-muted fw-semibold">
                                        #<?= htmlspecialchars($row['id_metodo_pago']) ?>
                                    </span>

                                </td>


                                <!-- Método -->
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div>

                                            <div class="fw-semibold text-dark">

                                                <?= htmlspecialchars($row['nombre_metodo']) ?>

                                            </div>

                                            <small class="text-muted">
                                                Método de pago
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- Acciones -->
                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-2">

                                        <!-- Editar -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Editar método"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal<?= $row['id_metodo_pago'] ?>">

                                            <i class="fa-solid fa-pen"></i>

                                        </button>


                                        <!-- Eliminar -->
                                        <form
                                            action="controladores/metodo_pago_controlador.php"
                                            method="post"
                                            class="d-inline">

                                            <input
                                                type="hidden"
                                                name="id_metodo_pago"
                                                value="<?= htmlspecialchars($row['id_metodo_pago']) ?>">

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="eliminacion">

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar método"
                                                onclick="return confirm('¿Estás seguro de que querés eliminar este método de pago?');">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                            <!-- =================================================
                                 MODAL EDITAR
                                 ================================================= -->

                            <div
                                class="modal fade"
                                id="modal<?= $row['id_metodo_pago'] ?>"
                                tabindex="-1"
                                aria-labelledby="modalLabel<?= $row['id_metodo_pago'] ?>"
                                aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content border-0 shadow">

                                        <!-- Cabecera -->
                                        <div class="modal-header">

                                            <div>

                                                <h5
                                                    class="modal-title fw-bold"
                                                    id="modalLabel<?= $row['id_metodo_pago'] ?>">

                                                    <i class="fa-solid fa-pen-to-square text-primary me-2"></i>

                                                    Editar método de pago

                                                </h5>

                                                <small class="text-muted">
                                                    Modificá el nombre del método de pago.
                                                </small>

                                            </div>


                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Cerrar">
                                            </button>

                                        </div>


                                        <!-- Formulario -->
                                        <form
                                            class="needs-validation"
                                            novalidate
                                            action="controladores/metodo_pago_controlador.php"
                                            method="post">

                                            <div class="modal-body">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="actualizacion">

                                                <input
                                                    type="hidden"
                                                    name="id_metodo_pago"
                                                    value="<?= htmlspecialchars($row['id_metodo_pago']) ?>">


                                                <div class="mb-3">

                                                    <label
                                                        for="nombre_metodo<?= $row['id_metodo_pago'] ?>"
                                                        class="form-label fw-semibold">

                                                        Método de pago

                                                    </label>

                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        id="nombre_metodo<?= $row['id_metodo_pago'] ?>"
                                                        name="nombre_metodo"
                                                        value="<?= htmlspecialchars($row['nombre_metodo']) ?>"
                                                        placeholder="Ej. Efectivo"
                                                        required>

                                                    <div class="invalid-feedback">
                                                        Ingresá el método de pago.
                                                    </div>

                                                </div>

                                            </div>


                                            <!-- Pie -->
                                            <div class="modal-footer border-0">

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

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


            <!-- =====================================================
                 SIN RESULTADOS
                 ===================================================== -->

            <div
                id="sinResultadosMetodo"
                class="text-center py-5 d-none">

                <div
                    class="d-flex align-items-center justify-content-center rounded-circle bg-light mx-auto mb-3"
                    style="width: 60px; height: 60px;">

                    <i class="fa-solid fa-magnifying-glass text-muted fs-4"></i>

                </div>

                <h6 class="fw-semibold">
                    No se encontraron métodos
                </h6>

                <p class="text-muted mb-0">
                    Probá con otro término de búsqueda.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- =============================================================
     OFFCANVAS - NUEVO MÉTODO DE PAGO
     ============================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoMetodo"
    aria-labelledby="offcanvasNuevoMetodoLabel">

    <!-- Cabecera -->
    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-bold"
                id="offcanvasNuevoMetodoLabel">

                <i class="fa-solid fa-credit-card text-primary me-2"></i>

                Nuevo método de pago

            </h5>

            <small class="text-muted">
                Registrá un nuevo método de pago.
            </small>

        </div>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar">
        </button>

    </div>


    <!-- Cuerpo -->
    <div class="offcanvas-body">

        <form
            class="needs-validation"
            novalidate
            method="post"
            action="controladores/metodo_pago_controlador.php">

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- Método -->
            <div class="mb-4">

                <label
                    for="nombre_metodo_nuevo"
                    class="form-label fw-semibold">

                    Método de pago

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_metodo_nuevo"
                    name="nombre_metodo"
                    placeholder="Ej. Efectivo"
                    required>

                <div class="invalid-feedback">
                    Ingresá el método de pago.
                </div>

                <div class="form-text">
                    Ejemplos: Efectivo, Tarjeta, Transferencia.
                </div>

            </div>


            <!-- Botones -->
            <div class="d-flex gap-2 mt-4">

                <button
                    type="submit"
                    class="btn btn-primary flex-grow-1">

                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    Guardar método

                </button>


                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="offcanvas">

                    Cancelar

                </button>

            </div>

        </form>

    </div>

</div>


<!-- Validaciones existentes -->
<script src="assets/js/validaciones/validaciones_controlador.js"></script>


<!-- =============================================================
     BUSCADOR
     ============================================================= -->

<script>

document
    .getElementById("buscarMetodo")
    .addEventListener("input", function () {

        let texto = this.value.toLowerCase().trim();

        let filas = document.querySelectorAll(".fila-metodo");

        let encontrados = 0;


        filas.forEach(function (fila) {

            let contenido = fila.textContent.toLowerCase();


            if (contenido.includes(texto)) {

                fila.style.display = "";

                encontrados++;

            } else {

                fila.style.display = "none";

            }

        });


        let sinResultados =
            document.getElementById("sinResultadosMetodo");


        if (encontrados === 0) {

            sinResultados.classList.remove("d-none");

        } else {

            sinResultados.classList.add("d-none");

        }

    });

</script>