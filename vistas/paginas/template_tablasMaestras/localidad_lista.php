<?php

include_once "modelos/localidad.php";
include_once "modelos/provincia.php";
include_once "modelos/pais.php";


// =============================================================
// OBTENER LOCALIDADES
// =============================================================

$localidadModel = new Localidad();

$lista_localidades =
    $localidadModel->consultarVariasLocalidades();


// =============================================================
// OBTENER PROVINCIAS
// =============================================================

$provinciaModel = new Provincia();

$lista_provincias =
    $provinciaModel->consultarVariasProvincias();


// =============================================================
// OBTENER PAÍSES
// =============================================================

$paisModel = new Pais();

$lista_paises =
    $paisModel->consultarVariosPaises();


// =============================================================
// CONVERTIR PAÍSES EN ARRAY
// =============================================================

$paises = [];

if ($lista_paises) {

    while ($pais = $lista_paises->fetch_assoc()) {

        $paises[$pais['id_pais']] =
            $pais['descripcion'];

    }

}


// =============================================================
// CONVERTIR PROVINCIAS EN ARRAY
// =============================================================

$provincias = [];

if ($lista_provincias) {

    while ($provincia = $lista_provincias->fetch_assoc()) {

        $provincias[$provincia['id_provincia']] = [

            'nombre' =>
                $provincia['nombre_provincia'],

            'pais_id' =>
                (int) $provincia['pais_id_pais']

        ];

    }

}


// =============================================================
// RUTA DEL CONTROLADOR
// =============================================================

$localidadControllerPath =
    "controladores/localidad_controlador.php";

?>


<div class="container-fluid py-4 px-4">

    <!-- ===================================================== -->
    <!-- ENCABEZADO -->
    <!-- ===================================================== -->

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-semibold mb-1">
                Gestión de Localidades
            </h2>

            <p class="text-muted mb-0">
                Administra las localidades utilizadas en el sistema.
            </p>

        </div>


        <!-- BOTÓN NUEVA LOCALIDAD -->

        <div class="mt-3 mt-md-0">

            <button
                type="button"
                class="btn btn-primary px-4"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNuevaLocalidad"
                aria-controls="offcanvasNuevaLocalidad">

                <i class="fa-solid fa-plus me-2"></i>

                Nueva Localidad

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
                        Localidades registradas
                    </h5>

                    <small class="text-muted">
                        Localidades disponibles para utilizar en el sistema.
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
                            id="buscarLocalidad"
                            class="form-control"
                            placeholder="Buscar localidad..."
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
                    id="tablaLocalidades">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 100px;">
                                ID
                            </th>

                            <th>
                                Localidad
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

                        <?php if (
                            $lista_localidades &&
                            $lista_localidades->num_rows > 0
                        ): ?>

                            <?php while (
                                $row =
                                $lista_localidades->fetch_assoc()
                            ): ?>

                                <?php

                                $idLocalidad =
                                    (int) $row['id_localidad'];

                                $nombreLocalidad =
                                    $row['nombre_localidad'];

                                $idProvincia =
                                    (int) $row['id_provincia'];


                                $nombreProvincia =
                                    $provincias[$idProvincia]['nombre']
                                    ?? 'Provincia no encontrada';


                                $idPais =
                                    $provincias[$idProvincia]['pais_id']
                                    ?? 0;


                                $nombrePais =
                                    $paises[$idPais]
                                    ?? 'País no encontrado';

                                ?>


                                <tr data-localidad-row>


                                    <!-- ================================================= -->
                                    <!-- ID -->
                                    <!-- ================================================= -->

                                    <td>

                                        <span class="text-muted">

                                            #<?= $idLocalidad ?>

                                        </span>

                                    </td>


                                    <!-- ================================================= -->
                                    <!-- LOCALIDAD -->
                                    <!-- ================================================= -->

                                    <td>

                                        <div>

                                            <span class="fw-semibold d-block">

                                                <?= htmlspecialchars(
                                                    $nombreLocalidad,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                            </span>

                                            <small class="text-muted">

                                                Localidad del sistema

                                            </small>

                                        </div>

                                    </td>


                                    <!-- ================================================= -->
                                    <!-- PROVINCIA -->
                                    <!-- ================================================= -->

                                    <td>

                                        <span class="text-muted">

                                            <?= htmlspecialchars(
                                                $nombreProvincia,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </span>

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
                                                class="btn btn-sm btn-outline-primary btn-editar-localidad"
                                                title="Editar localidad"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarLocalidad"
                                                data-id="<?= $idLocalidad ?>"
                                                data-nombre="<?= htmlspecialchars(
                                                    $nombreLocalidad,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                data-provincia="<?= $idProvincia ?>">

                                                <i class="fa-solid fa-pen"></i>

                                            </button>


                                            <!-- ELIMINAR -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger btn-eliminar-localidad"
                                                title="Eliminar localidad"
                                                data-id="<?= $idLocalidad ?>"
                                                data-nombre="<?= htmlspecialchars(
                                                    $nombreLocalidad,
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


                            <tr id="filaSinLocalidades">

                                <td
                                    colspan="5"
                                    class="text-center py-4">

                                    <span class="text-muted">

                                        No hay localidades registradas.

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
<!-- OFFCANVAS NUEVA LOCALIDAD -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevaLocalidad"
    aria-labelledby="offcanvasNuevaLocalidadLabel"
    style="width: 430px;">

    <!-- HEADER -->

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevaLocalidadLabel">

                Nueva Localidad

            </h5>

            <small class="text-muted">

                Registra una nueva localidad para el sistema.

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
            action="<?= $localidadControllerPath ?>">


            <!-- ACTION -->

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- ================================================= -->
            <!-- LOCALIDAD -->
            <!-- ================================================= -->

            <div class="mb-4">

                <label
                    for="nombreLocalidad"
                    class="form-label fw-semibold">

                    Localidad

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombreLocalidad"
                    name="nombre_localidad"
                    placeholder="Ej. Formosa"
                    maxlength="100"
                    autocomplete="off"
                    required>

                <div class="invalid-feedback">

                    Campo nombre de localidad vacío.

                </div>

                <div class="form-text">

                    Introduce el nombre que identificará a la localidad.

                </div>

            </div>


            <!-- ================================================= -->
            <!-- PROVINCIA -->
            <!-- ================================================= -->

            <div class="mb-4">

                <label
                    for="provinciaLocalidad"
                    class="form-label fw-semibold">

                    Provincia

                </label>

                <select
                    class="form-select"
                    id="provinciaLocalidad"
                    name="id_provincia"
                    required>

                    <option
                        value=""
                        selected
                        disabled>

                        Seleccione una provincia

                    </option>


                    <?php foreach (
                        $provincias as
                        $idProvincia =>
                        $provincia
                    ): ?>

                        <option
                            value="<?= (int) $idProvincia ?>">

                            <?= htmlspecialchars(
                                $provincia['nombre'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                            <?php

                            $paisProvincia =
                                $paises[
                                    $provincia['pais_id']
                                ] ?? '';

                            if ($paisProvincia) {

                                echo ' - ' .
                                    htmlspecialchars(
                                        $paisProvincia,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );

                            }

                            ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <div class="invalid-feedback">

                    Seleccione la provincia de la localidad.

                </div>


                <div class="form-text">

                    Seleccione la provincia a la que pertenece la localidad.

                </div>

            </div>


            <!-- ================================================= -->
            <!-- INFORMACIÓN -->
            <!-- ================================================= -->

            <div class="alert alert-primary border-0 small">

                <i class="fa-solid fa-circle-info me-2"></i>

                La localidad quedará asociada a la provincia seleccionada.

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

                    Crear Localidad

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ========================================================= -->
<!-- MODAL EDITAR LOCALIDAD -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalEditarLocalidad"
    tabindex="-1"
    aria-labelledby="modalEditarLocalidadLabel"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-semibold"
                        id="modalEditarLocalidadLabel">

                        Editar Localidad

                    </h5>

                    <small class="text-muted">

                        Modifica la información de la localidad.

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
                action="<?= $localidadControllerPath ?>"
                method="post">


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="action"
                        value="actualizacion">

                    <input
                        type="hidden"
                        name="id_localidad"
                        id="editarIdLocalidad">


                    <!-- ================================================= -->
                    <!-- LOCALIDAD -->
                    <!-- ================================================= -->

                    <div class="mb-3">

                        <label
                            for="editarNombreLocalidad"
                            class="form-label fw-semibold">

                            Localidad

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="editarNombreLocalidad"
                            name="nombre_localidad"
                            maxlength="100"
                            required>

                        <div class="invalid-feedback">

                            Campo nombre de localidad vacío.

                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- PROVINCIA -->
                    <!-- ================================================= -->

                    <div class="mb-3">

                        <label
                            for="editarProvinciaLocalidad"
                            class="form-label fw-semibold">

                            Provincia

                        </label>

                        <select
                            class="form-select"
                            id="editarProvinciaLocalidad"
                            name="id_provincia"
                            required>

                            <option
                                value=""
                                disabled>

                                Seleccione una provincia

                            </option>


                            <?php foreach (
                                $provincias as
                                $idProvincia =>
                                $provincia
                            ): ?>

                                <option
                                    value="<?= (int) $idProvincia ?>">

                                    <?= htmlspecialchars(
                                        $provincia['nombre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                    <?php

                                    $paisProvincia =
                                        $paises[
                                            $provincia['pais_id']
                                        ] ?? '';

                                    if ($paisProvincia) {

                                        echo ' - ' .
                                            htmlspecialchars(
                                                $paisProvincia,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );

                                    }

                                    ?>

                                </option>

                            <?php endforeach; ?>

                        </select>


                        <div class="invalid-feedback">

                            Seleccione la provincia de la localidad.

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- FOOTER -->
                <!-- ================================================= -->

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

    const buscadorLocalidad =
        document.getElementById('buscarLocalidad');


    if (buscadorLocalidad) {

        buscadorLocalidad.addEventListener('keyup', function () {

            const texto =
                this.value
                    .toLowerCase()
                    .trim();


            const filas =
                document.querySelectorAll(
                    '#tablaLocalidades tbody tr[data-localidad-row]'
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
        .querySelectorAll('.btn-editar-localidad')
        .forEach(function (boton) {


            boton.addEventListener('click', function () {


                const id =
                    this.dataset.id;


                const nombre =
                    this.dataset.nombre;


                const provincia =
                    this.dataset.provincia;


                document.getElementById(
                    'editarIdLocalidad'
                ).value = id;


                document.getElementById(
                    'editarNombreLocalidad'
                ).value = nombre;


                document.getElementById(
                    'editarProvinciaLocalidad'
                ).value = provincia;

            });

        });


    /* =========================================================
       ELIMINAR LOCALIDAD
    ========================================================= */

    document
        .querySelectorAll('.btn-eliminar-localidad')
        .forEach(function (boton) {


            boton.addEventListener('click', function () {


                const id =
                    this.dataset.id;


                const nombre =
                    this.dataset.nombre;


                Swal.fire({

                    title: '¿Eliminar localidad?',

                    html:
                        'Se eliminará la localidad <strong>' +
                        escapeHtmlLocalidad(nombre) +
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
                        'id_localidad',
                        id
                    );


                    fetch(
                        '<?= $localidadControllerPath ?>',
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

                                title: 'Localidad eliminada',

                                text:
                                    data.mensaje ||
                                    'La localidad fue eliminada correctamente.',

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
                                    'Ocurrió un error al eliminar la localidad.'

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
       VALIDACIÓN - NUEVA LOCALIDAD
    ========================================================= */

    const formNuevaLocalidad =
        document.querySelector(
            '#offcanvasNuevaLocalidad form'
        );


    if (formNuevaLocalidad) {


        formNuevaLocalidad.addEventListener(
            'submit',
            function (e) {


                const nombre =
                    document
                        .getElementById('nombreLocalidad')
                        .value
                        .trim();


                const provincia =
                    document
                        .getElementById('provinciaLocalidad')
                        .value;


                if (!nombre || !provincia) {

                    e.preventDefault();


                    Swal.fire({

                        icon: 'warning',

                        title: 'Datos incompletos',

                        text:
                            'Ingrese el nombre de la localidad y seleccione una provincia.'

                    });

                }

            }
        );

    }


    /* =========================================================
       VALIDACIÓN - EDITAR LOCALIDAD
    ========================================================= */

    const formEditarLocalidad =
        document.querySelector(
            '#modalEditarLocalidad form'
        );


    if (formEditarLocalidad) {


        formEditarLocalidad.addEventListener(
            'submit',
            function (e) {


                const nombre =
                    document
                        .getElementById(
                            'editarNombreLocalidad'
                        )
                        .value
                        .trim();


                const provincia =
                    document
                        .getElementById(
                            'editarProvinciaLocalidad'
                        )
                        .value;


                if (!nombre || !provincia) {

                    e.preventDefault();


                    Swal.fire({

                        icon: 'warning',

                        title: 'Datos incompletos',

                        text:
                            'Ingrese el nombre de la localidad y seleccione una provincia.'

                    });

                }

            }
        );

    }


    /* =========================================================
       ESCAPE HTML
    ========================================================= */

    function escapeHtmlLocalidad(texto) {

        const div =
            document.createElement('div');


        div.textContent =
            texto;


        return div.innerHTML;

    }

});

</script>


<!-- ========================================================= -->
<!-- VALIDACIONES GENERALES -->
<!-- ========================================================= -->

<script src="assets/js/validaciones/validaciones_controlador.js"></script>