<?php
include_once "modelos/contacto.php";

$contac = new Contacto();
$lista_contacto = $contac->consultarVariosContactos();
?>

<div class="container-fluid py-4">

    <!-- Encabezado -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="fw-semibold mb-1">
                Gestión de Tipos de Contacto
            </h2>

            <p class="text-muted mb-0">
                Administración de los tipos de contacto disponibles en el sistema.
            </p>
        </div>

        <!-- Botón Nuevo Contacto -->
        <button
            type="button"
            class="btn btn-primary rounded-3 px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoContacto"
            aria-controls="offcanvasNuevoContacto">

            <i class="fa-solid fa-plus me-2"></i>
            Nuevo Contacto

        </button>

    </div>


    <!-- Card principal -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- Cabecera de la tabla -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Tipos de Contacto Registrados
                    </h5>

                    <p class="text-muted small mb-0">
                        Listado de los tipos de contacto disponibles.
                    </p>
                </div>
            </div>


            <!-- Buscador -->
            <div class="mb-4">

                <div class="input-group">

                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control border-start-0"
                        id="buscarContacto"
                        placeholder="Buscar tipo de contacto...">

                </div>

            </div>


            <!-- Tabla -->
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0" id="tablaContactos">

                    <thead class="table-light">

                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Contacto</th>
                            <th scope="col">Descripción</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>

                    </thead>

                    <tbody id="tablaContactosBody">

                        <?php if (!empty($lista_contacto)) { ?>

                            <?php foreach ($lista_contacto as $row) { ?>

                                <tr>

                                    <!-- ID -->
                                    <td class="fw-semibold text-muted">
                                        <?php echo htmlspecialchars($row['id_contacto']); ?>
                                    </td>


                                    <!-- Tipo de contacto -->
                                    <td>

                                        <div class="d-flex align-items-center gap-2">

                                            <div
                                                class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center"
                                                style="width: 38px; height: 38px;">

                                                <i class="fa-solid fa-address-book"></i>

                                            </div>

                                            <span class="fw-semibold">
                                                <?php echo htmlspecialchars($row['tipo_contacto']); ?>
                                            </span>

                                        </div>

                                    </td>


                                    <!-- Descripción -->
                                    <td class="text-muted">

                                        <?php echo htmlspecialchars($row['descripcion']); ?>

                                    </td>


                                    <!-- Acciones -->
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-2">

                                            <!-- Editar -->
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary btn-sm rounded-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalContacto<?php echo $row['id_contacto']; ?>"
                                                title="Editar">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- Eliminar -->
                                            <form
                                                action="controladores/contacto_controlador.php"
                                                method="post"
                                                class="d-inline">

                                                <input
                                                    type="hidden"
                                                    name="id_contacto"
                                                    value="<?php echo $row['id_contacto']; ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-3"
                                                    title="Eliminar"
                                                    onclick="return confirm('¿Está seguro de eliminar este tipo de contacto?');">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>


                                        <!-- Modal Editar -->
                                        <div
                                            class="modal fade"
                                            id="modalContacto<?php echo $row['id_contacto']; ?>"
                                            tabindex="-1"
                                            aria-labelledby="modalContactoLabel<?php echo $row['id_contacto']; ?>"
                                            aria-hidden="true">

                                            <div class="modal-dialog modal-dialog-centered">

                                                <div class="modal-content border-0 shadow rounded-4">

                                                    <div class="modal-header">

                                                        <div>

                                                            <h5
                                                                class="modal-title fw-semibold"
                                                                id="modalContactoLabel<?php echo $row['id_contacto']; ?>">

                                                                Modificar Contacto

                                                            </h5>

                                                            <small class="text-muted">
                                                                Actualiza los datos del tipo de contacto.
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
                                                        action="controladores/contacto_controlador.php"
                                                        method="post">

                                                        <div class="modal-body p-4">

                                                            <input
                                                                type="hidden"
                                                                name="action"
                                                                value="actualizacion">

                                                            <input
                                                                type="hidden"
                                                                name="id_contacto"
                                                                value="<?php echo $row['id_contacto']; ?>">


                                                            <!-- Contacto -->
                                                            <div class="mb-3">

                                                                <label
                                                                    for="tipo_contacto<?php echo $row['id_contacto']; ?>"
                                                                    class="form-label fw-semibold">

                                                                    Tipo de Contacto

                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    class="form-control"
                                                                    id="tipo_contacto<?php echo $row['id_contacto']; ?>"
                                                                    name="tipo_contacto"
                                                                    value="<?php echo htmlspecialchars($row['tipo_contacto']); ?>"
                                                                    required>

                                                                <div class="invalid-feedback">
                                                                    Campo contacto vacío.
                                                                </div>

                                                            </div>


                                                            <!-- Descripción -->
                                                            <div class="mb-3">

                                                                <label
                                                                    for="descripcion<?php echo $row['id_contacto']; ?>"
                                                                    class="form-label fw-semibold">

                                                                    Descripción

                                                                </label>

                                                                <input
                                                                    type="text"
                                                                    class="form-control"
                                                                    id="descripcion<?php echo $row['id_contacto']; ?>"
                                                                    name="descripcion"
                                                                    value="<?php echo htmlspecialchars($row['descripcion']); ?>"
                                                                    required>

                                                                <div class="invalid-feedback">
                                                                    Campo descripción vacío.
                                                                </div>

                                                            </div>

                                                        </div>


                                                        <div class="modal-footer">

                                                            <button
                                                                type="button"
                                                                class="btn btn-light rounded-3"
                                                                data-bs-dismiss="modal">

                                                                Cancelar

                                                            </button>

                                                            <button
                                                                type="submit"
                                                                class="btn btn-primary rounded-3">

                                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                                Guardar cambios

                                                            </button>

                                                        </div>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>

                                <td colspan="4" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fa-solid fa-address-book fa-2x mb-3"></i>

                                        <p class="mb-0">
                                            No hay tipos de contacto registrados.
                                        </p>

                                    </div>

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
<!-- OFFCANVAS: NUEVO CONTACTO -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoContacto"
    aria-labelledby="offcanvasNuevoContactoLabel">

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoContactoLabel">

                Nuevo Tipo de Contacto

            </h5>

            <small class="text-muted">
                Completa los datos para registrar un nuevo tipo.
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
            action="controladores/contacto_controlador.php"
            method="post">

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- Tipo de contacto -->
            <div class="mb-3">

                <label
                    for="tipo_contacto"
                    class="form-label fw-semibold">

                    Tipo de Contacto

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="tipo_contacto"
                    name="tipo_contacto"
                    placeholder="Ej. Teléfono"
                    required>

                <div class="invalid-feedback">
                    Ingrese el tipo de contacto.
                </div>

            </div>


            <!-- Descripción -->
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
                    Ingrese una descripción.
                </div>

            </div>


            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-light rounded-3"
                    data-bs-dismiss="offcanvas">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="btn btn-primary rounded-3">

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

    const buscador = document.getElementById("buscarContacto");
    const filas = document.querySelectorAll("#tablaContactosBody tr");

    if (buscador) {

        buscador.addEventListener("keyup", function () {

            const texto = this.value.toLowerCase().trim();

            filas.forEach(function (fila) {

                const contenido = fila.textContent.toLowerCase();

                if (contenido.includes(texto)) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }

            });

        });

    }

});

</script>


<script src="assets/js/validaciones/validaciones_controlador.js"></script>