<?php
include_once "modelos/especialidad.php";

$especialidad = new Especialidad();
$lista_especialidad = $especialidad->consultarVariasEspecialidades();
?>

<div class="container-fluid py-4 px-4">

    <!-- Encabezado -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">Gestión de Especialidades</h2>
            <p class="text-muted mb-0">
                Administra las especialidades médicas disponibles en el sistema.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevaEspecialidad">

            <i class="fa-solid fa-plus me-2"></i>
            Nueva Especialidad

        </button>

    </div>


    <!-- Contenedor principal -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- Cabecera del listado -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Especialidades registradas
                    </h5>

                    <p class="text-muted small mb-0">
                        Consulta y administra las especialidades médicas.
                    </p>
                </div>


                <!-- Buscador -->
                <div class="input-group" style="max-width: 320px;">

                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control border-start-0"
                        id="buscarEspecialidad"
                        placeholder="Buscar especialidad...">

                </div>

            </div>


            <!-- Tabla -->
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th class="px-3 py-3" style="width: 100px;">
                                ID
                            </th>

                            <th class="py-3">
                                Especialidad
                            </th>

                            <th class="text-end px-3 py-3" style="width: 150px;">
                                Acciones
                            </th>
                        </tr>

                    </thead>

                    <tbody id="tablaEspecialidades">

                        <?php if ($lista_especialidad): ?>

                            <?php foreach ($lista_especialidad as $row): ?>

                                <tr class="fila-especialidad">

                                    <!-- ID -->
                                    <td class="px-3">

                                        <span class="text-muted fw-semibold">
                                            #<?= htmlspecialchars($row['id_especialidad']) ?>
                                        </span>

                                    </td>


                                    <!-- Especialidad -->
                                    <td>

                                        <div class="d-flex align-items-center gap-3">
                                            <div>

                                                <span class="fw-semibold d-block">
                                                    <?= htmlspecialchars($row['nombre_especialidad']) ?>
                                                </span>

                                                <small class="text-muted">
                                                    Especialidad médica
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    <!-- Acciones -->
                                    <td class="text-end px-3">

                                        <div class="d-flex justify-content-end gap-2">

                                            <!-- Editar -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal<?= $row['id_especialidad'] ?>"
                                                title="Editar especialidad">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- Eliminar -->
                                            <form
                                                action="controladores/especialidad_controlador.php"
                                                method="post"
                                                class="m-0">

                                                <input
                                                    type="hidden"
                                                    name="id_especialidad"
                                                    value="<?= $row['id_especialidad'] ?>">

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="eliminacion">

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar especialidad">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                                <!-- Modal de edición -->
                                <div
                                    class="modal fade"
                                    id="modal<?= $row['id_especialidad'] ?>"
                                    tabindex="-1"
                                    aria-labelledby="modalLabel<?= $row['id_especialidad'] ?>"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 shadow rounded-4">

                                            <div class="modal-header border-0 px-4 pt-4">

                                                <div>

                                                    <h5
                                                        class="modal-title fw-bold"
                                                        id="modalLabel<?= $row['id_especialidad'] ?>">

                                                        Modificar Especialidad

                                                    </h5>

                                                    <p class="text-muted small mb-0">
                                                        Actualiza la información de la especialidad.
                                                    </p>

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
                                                action="controladores/especialidad_controlador.php"
                                                method="post">

                                                <div class="modal-body px-4">

                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">

                                                    <input
                                                        type="hidden"
                                                        name="id_especialidad"
                                                        value="<?= $row['id_especialidad'] ?>">


                                                    <div class="mb-3">

                                                        <label
                                                            for="nombre_especialidad<?= $row['id_especialidad'] ?>"
                                                            class="form-label fw-semibold">

                                                            Especialidad

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="nombre_especialidad<?= $row['id_especialidad'] ?>"
                                                            name="nombre_especialidad"
                                                            value="<?= htmlspecialchars($row['nombre_especialidad']) ?>"
                                                            placeholder="Ingrese la especialidad"
                                                            required>

                                                        <div class="invalid-feedback">
                                                            Campo vacío.
                                                        </div>

                                                    </div>

                                                </div>


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

                            <tr id="sinEspecialidades">

                                <td colspan="3" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fa-solid fa-stethoscope fa-2x mb-3"></i>

                                        <p class="mb-0">
                                            No hay especialidades registradas.
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
<!-- OFFCANVAS - NUEVA ESPECIALIDAD -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevaEspecialidad"
    aria-labelledby="offcanvasNuevaEspecialidadLabel">

    <div class="offcanvas-header border-bottom px-4">

        <div>

            <h5
                class="offcanvas-title fw-bold"
                id="offcanvasNuevaEspecialidadLabel">

                Nueva Especialidad

            </h5>

            <p class="text-muted small mb-0">
                Registra una nueva especialidad médica.
            </p>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
            aria-label="Cerrar">
        </button>

    </div>


    <div class="offcanvas-body px-4">

        <form
            class="needs-validation"
            novalidate
            method="post"
            action="controladores/especialidad_controlador.php">

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- Nombre -->
            <div class="mb-4">

                <label
                    for="nombre_especialidad_nueva"
                    class="form-label fw-semibold">

                    Especialidad

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_especialidad_nueva"
                    name="nombre_especialidad"
                    placeholder="Ej. Cardiología"
                    required>

                <div class="invalid-feedback">
                    Campo vacío.
                </div>

                <div class="form-text">
                    Ingresa el nombre de la especialidad médica.
                </div>

            </div>


            <!-- Botones -->
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

    const buscador = document.getElementById("buscarEspecialidad");

    if (!buscador) {
        return;
    }

    buscador.addEventListener("keyup", function () {

        const texto = this.value.toLowerCase().trim();

        const filas = document.querySelectorAll(".fila-especialidad");

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


<!-- Validaciones existentes -->
<script src="assets/js/validaciones/validaciones_controlador.js"></script>