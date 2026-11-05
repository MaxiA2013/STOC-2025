<?php
include_once "modelos/provincia.php";
include_once "modelos/pais.php";

$provincia = new Provincia();
$lista_provincias = $provincia->consultarVariasProvincias();


$paisModel = new Pais();
$lista_paises = $paisModel->consultarVariosPaises();

// CONVERTIR LOS PAÍSES EN UN ARRAY PARA BUSCARLOS POR ID
$paises = [];
if ($lista_paises) {

    while ($pais = $lista_paises->fetch_assoc()) {

        $paises[$pais['id_pais']] = $pais['nombre_pais'];

    }

}

// Ruta del controlador
$provinciaControllerPath = "controladores/provincia_controlador.php";
?>

<div class="container-fluid py-4 px-4">
    <!-- ENCABEZADO -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-semibold mb-1">
                Gestión de Provincias
            </h2>

            <p class="text-muted mb-0">
                Administra las provincias utilizadas en el sistema.
            </p>

        </div>


        <!-- BOTÓN NUEVA PROVINCIA -->

        <div class="mt-3 mt-md-0">

            <button
                type="button"
                class="btn btn-primary px-4"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNuevaProvincia"
                aria-controls="offcanvasNuevaProvincia">

                <i class="fa-solid fa-plus me-2"></i>

                Nueva Provincia

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
                        Provincias registradas
                    </h5>

                    <small class="text-muted">
                        Provincias disponibles para utilizar en el sistema.
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
                            id="buscarProvincia"
                            class="form-control"
                            placeholder="Buscar provincia..."
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
                    id="tablaProvincias">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 100px;">
                                ID
                            </th>

                            <th>
                                Provincia
                            </th>

                            <th>
                                País
                            </th>

                            <th
                                class="text-center"
                                style="width: 160px;">

                                Acciones

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if ($lista_provincias && $lista_provincias->num_rows > 0): ?>

                            <?php while ($row = $lista_provincias->fetch_assoc()): ?>

                                <?php

                                $idProvincia = (int) $row['id_provincia'];

                                $nombreProvincia = $row['nombre_provincia'];

                                $idPais = (int) $row['pais_id_pais'];

                                $nombrePais = $paises[$idPais] ?? 'País no encontrado';

                                ?>


                                <tr data-provincia-row>


                                    <!-- ================================================= -->
                                    <!-- ID -->
                                    <!-- ================================================= -->

                                    <td>

                                        <span class="text-muted">

                                            #<?= $idProvincia ?>

                                        </span>

                                    </td>


                                    <!-- ================================================= -->
                                    <!-- PROVINCIA -->
                                    <!-- ================================================= -->

                                    <td>

                                        <div>

                                            <span class="fw-semibold d-block">

                                                <?= htmlspecialchars(
                                                    $nombreProvincia,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </span>

                                            <small class="text-muted">

                                                Provincia del sistema

                                            </small>

                                        </div>

                                    </td>


                                    <!-- ================================================= -->
                                    <!-- PAÍS -->
                                    <!-- ================================================= -->

                                    <td>

                                        <span class="text-muted">

                                            <?= htmlspecialchars(
                                                $nombrePais,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </span>

                                    </td>


                                    <!-- ================================================= -->
                                    <!-- ACCIONES -->
                                    <!-- ================================================= -->

                                    <td>

                                        <div class="d-flex justify-content-center gap-2">


                                            <!-- EDITAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary btn-editar-provincia"
                                                title="Editar provincia"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarProvincia"
                                                data-id="<?= $idProvincia ?>"
                                                data-nombre="<?= htmlspecialchars(
                                                    $nombreProvincia,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                data-pais="<?= $idPais ?>">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- ELIMINAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger btn-eliminar-provincia"
                                                title="Eliminar provincia"
                                                data-id="<?= $idProvincia ?>"
                                                data-nombre="<?= htmlspecialchars(
                                                    $nombreProvincia,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>">

                                                <i class="fa-solid fa-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr id="filaSinProvincias">

                                <td
                                    colspan="4"
                                    class="text-center py-4">

                                    <span class="text-muted">

                                        No hay provincias registradas.

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
<!-- OFFCANVAS NUEVA PROVINCIA -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevaProvincia"
    aria-labelledby="offcanvasNuevaProvinciaLabel"
    style="width: 430px;">

    <!-- HEADER -->

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevaProvinciaLabel">

                Nueva Provincia

            </h5>

            <small class="text-muted">

                Registra una nueva provincia para el sistema.

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
            action="<?= $provinciaControllerPath ?>">


            <!-- ACTION -->

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- ================================================= -->
            <!-- PROVINCIA -->
            <!-- ================================================= -->

            <div class="mb-4">

                <label
                    for="nombreProvincia"
                    class="form-label fw-semibold">

                    Provincia

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombreProvincia"
                    name="nombre_provincia"
                    placeholder="Ej. Formosa"
                    maxlength="100"
                    autocomplete="off"
                    required>

                <div class="invalid-feedback">

                    Campo nombre de provincia vacío.

                </div>

                <div class="form-text">

                    Introduce el nombre que identificará a la provincia.

                </div>

            </div>


            <!-- ================================================= -->
            <!-- PAÍS -->
            <!-- ================================================= -->

            <div class="mb-4">

                <label
                    for="paisProvincia"
                    class="form-label fw-semibold">

                    País

                </label>

                <select
                    class="form-select"
                    id="paisProvincia"
                    name="pais_id_pais"
                    required>

                    <option value="" selected disabled>
                        Seleccione un país
                    </option>

                    <?php

                    foreach ($paises as $idPais => $nombrePais):

                    ?>

                        <option value="<?= (int) $idPais ?>">

                            <?= htmlspecialchars(
                                $nombrePais,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <div class="invalid-feedback">

                    Seleccione el país de la provincia.

                </div>

                <div class="form-text">

                    Seleccione el país al que pertenece la provincia.

                </div>

            </div>


            <!-- ================================================= -->
            <!-- INFORMACIÓN -->
            <!-- ================================================= -->

            <div class="alert alert-primary border-0 small">

                <i class="fa-solid fa-circle-info me-2"></i>

                La provincia quedará asociada al país seleccionado.

            </div>


            <!-- ================================================= -->
            <!-- BOTONES -->
            <!-- ================================================= -->

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

                    Crear Provincia

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- MODAL EDITAR PROVINCIA -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalEditarProvincia"
    tabindex="-1"
    aria-labelledby="modalEditarProvinciaLabel"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-semibold"
                        id="modalEditarProvinciaLabel">

                        Editar Provincia

                    </h5>

                    <small class="text-muted">

                        Modifica la información de la provincia.

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
                action="<?= $provinciaControllerPath ?>"
                method="post">


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="action"
                        value="actualizacion">

                    <input
                        type="hidden"
                        name="id_provincia"
                        id="editarIdProvincia">


                    <!-- PROVINCIA -->

                    <div class="mb-3">

                        <label
                            for="editarNombreProvincia"
                            class="form-label fw-semibold">

                            Provincia

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="editarNombreProvincia"
                            name="nombre_provincia"
                            maxlength="100"
                            required>

                        <div class="invalid-feedback">

                            Campo nombre de provincia vacío.

                        </div>

                    </div>


                    <!-- PAÍS -->

                    <div class="mb-3">

                        <label
                            for="editarPaisProvincia"
                            class="form-label fw-semibold">

                            País

                        </label>

                        <select
                            class="form-select"
                            id="editarPaisProvincia"
                            name="pais_id_pais"
                            required>

                            <option value="" disabled>

                                Seleccione un país

                            </option>

                            <?php

                            foreach ($paises as $idPais => $nombrePais):

                            ?>

                                <option value="<?= (int) $idPais ?>">

                                    <?= htmlspecialchars(
                                        $nombrePais,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                        <div class="invalid-feedback">

                            Seleccione el país de la provincia.

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


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       BUSCADOR
    ========================================================= */

    const buscadorProvincia =
        document.getElementById('buscarProvincia');


    if (buscadorProvincia) {

        buscadorProvincia.addEventListener('keyup', function () {

            const texto =
                this.value
                    .toLowerCase()
                    .trim();


            const filas =
                document.querySelectorAll(
                    '#tablaProvincias tbody tr[data-provincia-row]'
                );


            filas.forEach(function (fila) {

                const contenido =
                    fila.textContent
                        .toLowerCase();


                fila.style.display =
                    contenido.includes(texto)
                        ? ''
                        : 'none';

            });

        });

    }


    /* =========================================================
       CARGAR DATOS EN MODAL DE EDICIÓN
    ========================================================= */

    document
        .querySelectorAll('.btn-editar-provincia')
        .forEach(function (boton) {


            boton.addEventListener('click', function () {


                const id =
                    this.dataset.id;


                const nombre =
                    this.dataset.nombre;


                const pais =
                    this.dataset.pais;


                document.getElementById(
                    'editarIdProvincia'
                ).value = id;


                document.getElementById(
                    'editarNombreProvincia'
                ).value = nombre;


                document.getElementById(
                    'editarPaisProvincia'
                ).value = pais;


            });

        });


    /* =========================================================
       ELIMINAR PROVINCIA
    ========================================================= */

    document
        .querySelectorAll('.btn-eliminar-provincia')
        .forEach(function (boton) {


            boton.addEventListener('click', function () {


                const id =
                    this.dataset.id;


                const nombre =
                    this.dataset.nombre;


                Swal.fire({

                    title: '¿Eliminar provincia?',

                    html:
                        'Se eliminará la provincia <strong>' +
                        escapeHtmlProvincia(nombre) +
                        '</strong>.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText:
                        '<i class="fa-solid fa-trash me-1"></i> Eliminar',

                    cancelButtonText:
                        'Cancelar',

                    confirmButtonColor:
                        '#dc3545',

                    reverseButtons:
                        true

                }).then(function (resultado) {


                    if (!resultado.isConfirmed) {

                        return;

                    }


                    const formData =
                        new FormData();


                    formData.append(
                        'action',
                        'eliminacion'
                    );


                    formData.append(
                        'id_provincia',
                        id
                    );


                    fetch(
                        '<?= $provinciaControllerPath ?>',
                        {
                            method: 'POST',
                            body: formData
                        }
                    )
                    .then(function (response) {

                        return response.json();

                    })
                    .then(function (data) {


                        if (data.success) {


                            Swal.fire({

                                icon: 'success',

                                title: 'Provincia eliminada',

                                text:
                                    data.mensaje ||
                                    'La provincia fue eliminada correctamente.',

                                timer: 1800,

                                showConfirmButton:
                                    false

                            }).then(function () {

                                location.reload();

                            });


                        } else {


                            Swal.fire({

                                icon: 'error',

                                title: 'No se pudo eliminar',

                                text:
                                    data.mensaje ||
                                    'Ocurrió un error al eliminar la provincia.'

                            });

                        }

                    })
                    .catch(function () {


                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text:
                                'No se pudo comunicar con el servidor.'

                        });

                    });

                });

            });

        });


    /* =========================================================
       VALIDACIÓN - NUEVA PROVINCIA
    ========================================================= */

    const formNuevaProvincia =
        document.querySelector(
            '#offcanvasNuevaProvincia form'
        );


    if (formNuevaProvincia) {


        formNuevaProvincia.addEventListener(
            'submit',
            function (e) {


                const nombre =
                    document
                        .getElementById('nombreProvincia')
                        .value
                        .trim();


                const pais =
                    document
                        .getElementById('paisProvincia')
                        .value;


                if (!nombre || !pais) {

                    e.preventDefault();


                    Swal.fire({

                        icon: 'warning',

                        title: 'Datos incompletos',

                        text:
                            'Ingrese el nombre de la provincia y seleccione un país.'

                    });

                }

            }
        );

    }


    /* =========================================================
       VALIDACIÓN - EDITAR PROVINCIA
    ========================================================= */

    const formEditarProvincia =
        document.querySelector(
            '#modalEditarProvincia form'
        );


    if (formEditarProvincia) {


        formEditarProvincia.addEventListener(
            'submit',
            function (e) {


                const nombre =
                    document
                        .getElementById(
                            'editarNombreProvincia'
                        )
                        .value
                        .trim();


                const pais =
                    document
                        .getElementById(
                            'editarPaisProvincia'
                        )
                        .value;


                if (!nombre || !pais) {

                    e.preventDefault();


                    Swal.fire({

                        icon: 'warning',

                        title: 'Datos incompletos',

                        text:
                            'Ingrese el nombre de la provincia y seleccione un país.'

                    });

                }

            }
        );

    }


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function escapeHtmlProvincia(texto) {

        const div =
            document.createElement('div');


        div.textContent =
            texto;


        return div.innerHTML;

    }

});

</script>

<script src="assets/js/validaciones/validaciones_controlador.js"></script>
