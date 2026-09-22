<?php
include_once "modelos/documento.php";

$docu = new Documento();
$lista_documentos = $docu->consultarVariosDocumento();
?>

<div class="container-fluid py-4 px-4">

    <!-- ENCABEZADO -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Gestión de Documentos
            </h2>

            <p class="text-muted mb-0">
                Administra los tipos de documentos solicitados por el sistema.
            </p>

        </div>


        <!-- BOTÓN NUEVO -->
        <button
            type="button"
            class="btn btn-primary px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoDocumento"
            aria-controls="offcanvasNuevoDocumento">

            <i class="fa-solid fa-plus me-2"></i>
            Nuevo Documento

        </button>

    </div>


    <!-- TARJETA PRINCIPAL -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- CABECERA DEL LISTADO -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div>

                    <h5 class="fw-semibold mb-1">
                        Documentos registrados
                    </h5>

                    <p class="text-muted small mb-0">
                        Consulta y administra los tipos de documentos disponibles.
                    </p>

                </div>


                <!-- BUSCADOR -->
                <div
                    class="input-group"
                    style="max-width: 320px;">

                    <span class="input-group-text bg-white border-end-0">

                        <i class="fa-solid fa-magnifying-glass text-muted"></i>

                    </span>

                    <input
                        type="text"
                        class="form-control border-start-0"
                        id="buscarDocumento"
                        placeholder="Buscar documento...">

                </div>

            </div>


            <!-- TABLA -->
            <div class="table-responsive">

                <table class="table align-middle table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th
                                class="px-3 py-3"
                                style="width: 90px;">

                                ID

                            </th>

                            <th class="py-3">
                                Documento
                            </th>

                            <th class="py-3">
                                Descripción
                            </th>

                            <th
                                class="py-3 text-center"
                                style="width: 140px;">

                                Obligatorio

                            </th>

                            <th
                                class="py-3 text-end px-3"
                                style="width: 150px;">

                                Acciones

                            </th>

                        </tr>

                    </thead>


                    <tbody id="tablaDocumentos">

                        <?php if ($lista_documentos): ?>

                            <?php foreach ($lista_documentos as $row): ?>

                                <tr class="fila-documento">

                                    <!-- ID -->
                                    <td class="px-3">

                                        <span class="text-muted fw-semibold">

                                            #<?= htmlspecialchars($row['id_documento']) ?>

                                        </span>

                                    </td>


                                    <!-- DOCUMENTO -->
                                    <td>

                                        <div class="d-flex align-items-center gap-3">
                                            <div>
                                                <span class="fw-semibold d-block">

                                                    <?= htmlspecialchars($row['tipo_documento']) ?>

                                                </span>

                                                <small class="text-muted">
                                                    Tipo de documento
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


                                    <!-- OBLIGATORIO -->
                                    <td class="text-center">

                                        <?php if ($row['obligatorio']): ?>

                                            <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2">

                                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                Sí

                                            </span>

                                        <?php else: ?>

                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">

                                                <i class="fa-solid fa-minus me-1"></i>
                                                No

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- ACCIONES -->
                                    <td class="text-end px-3">

                                        <div class="d-flex justify-content-end gap-2">

                                            <!-- EDITAR -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal<?= $row['id_documento'] ?>"
                                                title="Editar documento">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- ELIMINAR -->
                                            <form
                                                action="controladores/documento_controlador.php"
                                                method="post"
                                                class="m-0">

                                                <input
                                                    type="hidden"
                                                    name="id_documento"
                                                    value="<?= $row['id_documento'] ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar documento">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <!-- ================================================= -->
                                <!-- MODAL EDITAR DOCUMENTO -->
                                <!-- ================================================= -->

                                <div
                                    class="modal fade"
                                    id="modal<?= $row['id_documento'] ?>"
                                    tabindex="-1"
                                    aria-labelledby="modalLabel<?= $row['id_documento'] ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 shadow rounded-4">

                                            <!-- CABECERA -->
                                            <div class="modal-header border-0 px-4 pt-4">

                                                <div>

                                                    <h5
                                                        class="modal-title fw-bold"
                                                        id="modalLabel<?= $row['id_documento'] ?>">

                                                        Modificar Documento

                                                    </h5>

                                                    <p class="text-muted small mb-0">

                                                        Actualiza la información del documento.

                                                    </p>

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
                                                action="controladores/documento_controlador.php"
                                                method="post">

                                                <div class="modal-body px-4">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">

                                                    <input
                                                        type="hidden"
                                                        name="id_documento"
                                                        value="<?= $row['id_documento'] ?>">


                                                    <!-- DOCUMENTO -->
                                                    <div class="mb-3">

                                                        <label
                                                            for="tipo_documento<?= $row['id_documento'] ?>"
                                                            class="form-label fw-semibold">

                                                            Documento

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="tipo_documento<?= $row['id_documento'] ?>"
                                                            name="tipo_documento"
                                                            value="<?= htmlspecialchars($row['tipo_documento']) ?>"
                                                            placeholder="Ingrese el tipo de documento"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Campo Documento vacío.
                                                        </div>

                                                    </div>


                                                    <!-- DESCRIPCIÓN -->
                                                    <div class="mb-3">

                                                        <label
                                                            for="descripcion<?= $row['id_documento'] ?>"
                                                            class="form-label fw-semibold">

                                                            Descripción

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="descripcion<?= $row['id_documento'] ?>"
                                                            name="descripcion"
                                                            value="<?= htmlspecialchars($row['descripcion']) ?>"
                                                            placeholder="Ingrese una descripción"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Campo Descripción vacío.
                                                        </div>

                                                    </div>


                                                    <!-- OBLIGATORIO -->
                                                    <div class="mb-3">

                                                        <label class="form-label fw-semibold d-block">

                                                            ¿Es obligatorio?

                                                        </label>


                                                        <div class="d-flex gap-3">

                                                            <!-- NO -->
                                                            <div class="form-check">

                                                                <input
                                                                    class="form-check-input"
                                                                    type="radio"
                                                                    name="obligatorio"
                                                                    id="obligatorio_no<?= $row['id_documento'] ?>"
                                                                    value="0"
                                                                    <?= $row['obligatorio'] == 0 ? 'checked' : '' ?>>

                                                                <label
                                                                    class="form-check-label"
                                                                    for="obligatorio_no<?= $row['id_documento'] ?>">

                                                                    No

                                                                </label>

                                                            </div>


                                                            <!-- SÍ -->
                                                            <div class="form-check">

                                                                <input
                                                                    class="form-check-input"
                                                                    type="radio"
                                                                    name="obligatorio"
                                                                    id="obligatorio_si<?= $row['id_documento'] ?>"
                                                                    value="1"
                                                                    <?= $row['obligatorio'] == 1 ? 'checked' : '' ?>>

                                                                <label
                                                                    class="form-check-label"
                                                                    for="obligatorio_si<?= $row['id_documento'] ?>">

                                                                    Sí

                                                                </label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                                <!-- PIE -->
                                                <div class="modal-footer border-0 px-4 pb-4">

                                                    <button
                                                        type="button"
                                                        class="btn btn-light px-4"
                                                        data-bs-dismiss="modal">

                                                        Cancelar

                                                    </button>


                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary px-4">

                                                        <i class="fa-solid fa-floppy-disk me-2"></i>
                                                        Guardar cambios

                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fa-solid fa-file-circle-xmark fa-2x mb-3"></i>

                                        <p class="mb-0">
                                            No hay documentos registrados.
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
<!-- OFFCANVAS NUEVO DOCUMENTO -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoDocumento"
    aria-labelledby="offcanvasNuevoDocumentoLabel">


    <!-- CABECERA -->
    <div class="offcanvas-header border-bottom px-4">

        <div>

            <h5
                class="offcanvas-title fw-bold"
                id="offcanvasNuevoDocumentoLabel">

                Nuevo Documento

            </h5>

            <p class="text-muted small mb-0">

                Registra un nuevo tipo de documento.

            </p>

        </div>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar">
        </button>

    </div>


    <!-- CUERPO -->
    <div class="offcanvas-body px-4">

        <form
            class="needs-validation"
            novalidate
            method="post"
            action="controladores/documento_controlador.php">


            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- DOCUMENTO -->
            <div class="mb-3">

                <label
                    for="tipo_documento"
                    class="form-label fw-semibold">

                    Documento

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="tipo_documento"
                    name="tipo_documento"
                    placeholder="Ej. DNI, Pasaporte..."
                    required>

                <div class="invalid-feedback">
                    Campo Documento vacío.
                </div>

            </div>


            <!-- DESCRIPCIÓN -->
            <div class="mb-3">

                <label
                    for="descripcion"
                    class="form-label fw-semibold">

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
                    Campo Descripción vacío.
                </div>

            </div>


            <!-- OBLIGATORIO -->
            <div class="mb-4">

                <label class="form-label fw-semibold d-block">

                    ¿Es obligatorio?

                </label>


                <div class="d-flex gap-4">

                    <!-- NO -->
                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="obligatorio"
                            id="obligatorio_no"
                            value="0"
                            checked>

                        <label
                            class="form-check-label"
                            for="obligatorio_no">

                            No

                        </label>

                    </div>


                    <!-- SÍ -->
                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="obligatorio"
                            id="obligatorio_si"
                            value="1">

                        <label
                            class="form-check-label"
                            for="obligatorio_si">

                            Sí

                        </label>

                    </div>

                </div>

                <div class="form-text">

                    Indica si el documento será requerido obligatoriamente.

                </div>

            </div>


            <!-- BOTONES -->
            <div class="d-flex gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-light flex-fill"
                    data-bs-dismiss="offcanvas">

                    Cancelar

                </button>


                <button
                    type="submit"
                    class="btn btn-primary flex-fill">

                    <i class="fa-solid fa-plus me-2"></i>
                    Agregar

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- BUSCADOR -->
<!-- ========================================================= -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const buscador = document.getElementById("buscarDocumento");

    if (!buscador) {
        return;
    }


    buscador.addEventListener("keyup", function () {

        const texto = this.value.toLowerCase().trim();

        const filas = document.querySelectorAll(".fila-documento");


        filas.forEach(function (fila) {

            const contenido = fila.textContent.toLowerCase();

            if (contenido.includes(texto)) {

                fila.style.display = "";

            } else {

                fila.style.display = "none";

            }

        });

    });

});

</script>


<!-- VALIDACIONES EXISTENTES -->
<script src="assets/js/validaciones/validaciones_controlador.js"></script>