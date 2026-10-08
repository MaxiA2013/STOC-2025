
<?php

include_once "modelos/calle.php";
include_once "modelos/barrio.php";
include_once "modelos/localidad.php";
include_once "modelos/provincia.php";
include_once "modelos/pais.php";


$calleModel = new Calle();
$lista_calles = $calleModel->consultarVariasCalles();


$barrioModel = new Barrio();
$lista_barrios = $barrioModel->consultarVariosBarrios();


$localidadModel = new Localidad();
$lista_localidades = $localidadModel->consultarVariasLocalidades();


$provinciaModel = new Provincia();
$lista_provincias = $provinciaModel->consultarVariasProvincias();


$paisModel = new Pais();
$lista_paises = $paisModel->consultarVariosPaises();


// =========================================================
// ARMAR ARRAY DE PAÍSES
// =========================================================

$paises = [];

if ($lista_paises) {

    while ($pais = $lista_paises->fetch_assoc()) {

        $paises[$pais['id_pais']] = $pais['descripcion'];
    }
}


// =========================================================
// ARMAR ARRAY DE PROVINCIAS
// =========================================================

$provincias = [];

if ($lista_provincias) {

    while ($provincia = $lista_provincias->fetch_assoc()) {

        $provincias[$provincia['id_provincia']] = [
            'nombre' => $provincia['nombre_provincia'],
            'pais_id' => (int) $provincia['pais_id_pais']
        ];
    }
}


// =========================================================
// ARMAR ARRAY DE LOCALIDADES
// =========================================================

$localidades = [];

if ($lista_localidades) {

    while ($localidad = $lista_localidades->fetch_assoc()) {

        $localidadId = $localidad['id_localidad'];
        $provinciaId = (int) $localidad['id_provincia'];

        $nombreProvincia = '';
        $paisId = null;
        $nombrePais = '';

        if (isset($provincias[$provinciaId])) {

            $nombreProvincia =
                $provincias[$provinciaId]['nombre'];

            $paisId =
                $provincias[$provinciaId]['pais_id'];

            if (isset($paises[$paisId])) {

                $nombrePais =
                    $paises[$paisId];
            }
        }

        $localidades[$localidadId] = [
            'nombre' => $localidad['nombre_localidad'],
            'provincia_id' => $provinciaId,
            'provincia' => $nombreProvincia,
            'pais_id' => $paisId,
            'pais' => $nombrePais
        ];
    }
}


// =========================================================
// ARMAR ARRAY DE BARRIOS
// =========================================================

$barrios = [];

if ($lista_barrios) {

    while ($barrio = $lista_barrios->fetch_assoc()) {

        $barrioId = $barrio['id_barrio'];
        $localidadId = (int) $barrio['localidad_id_localidad'];

        $nombreLocalidad = '';
        $nombreProvincia = '';
        $nombrePais = '';

        if (isset($localidades[$localidadId])) {

            $nombreLocalidad =
                $localidades[$localidadId]['nombre'];

            $nombreProvincia =
                $localidades[$localidadId]['provincia'];

            $nombrePais =
                $localidades[$localidadId]['pais'];
        }

        $barrios[$barrioId] = [
            'nombre' => $barrio['nombre_barrio'],
            'localidad_id' => $localidadId,
            'localidad' => $nombreLocalidad,
            'provincia' => $nombreProvincia,
            'pais' => $nombrePais
        ];
    }
}


$calleControllerPath = "controladores/calle_controlador.php";

?>


<div class="container-fluid py-4 px-4">


    <!-- ================================================= -->
    <!-- ENCABEZADO -->
    <!-- ================================================= -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-semibold mb-1">
                Gestión de Calles
            </h2>

            <p class="text-muted mb-0">
                Administración de calles y sus barrios.
            </p>

        </div>


        <button
            type="button"
            class="btn btn-primary px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevaCalle">

            <i class="fa-solid fa-plus me-2"></i>

            Nueva Calle

        </button>

    </div>


    <!-- ================================================= -->
    <!-- CONTENEDOR PRINCIPAL -->
    <!-- ================================================= -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">


            <!-- ================================================= -->
            <!-- TÍTULO Y BUSCADOR -->
            <!-- ================================================= -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h5 class="fw-semibold mb-1">
                        Lista de Calles
                    </h5>

                    <p class="text-muted small mb-0">
                        Calles registradas en el sistema.
                    </p>

                </div>


                <div style="width: 280px;">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="fa-solid fa-magnifying-glass text-muted"></i>

                        </span>


                        <input
                            type="text"
                            id="buscarCalle"
                            class="form-control"
                            placeholder="Buscar calle...">

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- TABLA -->
            <!-- ================================================= -->

            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                    id="tablaCalles">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 80px;">
                                ID
                            </th>

                            <th>
                                Calle
                            </th>

                            <th>
                                Altura
                            </th>

                            <th>
                                Barrio
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
                                style="width: 150px;">

                                Acciones

                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php if ($lista_calles && mysqli_num_rows($lista_calles) > 0): ?>


                            <?php while ($calle = $lista_calles->fetch_assoc()): ?>


                                <?php

                                $idCalle =
                                    $calle['id_calle'];

                                $idBarrio =
                                    (int) $calle['barrio_id_barrio'];


                                $nombreBarrio =
                                    'Sin barrio';

                                $nombreLocalidad =
                                    'Sin localidad';

                                $nombreProvincia =
                                    'Sin provincia';

                                $nombrePais =
                                    'Sin país';


                                if (isset($barrios[$idBarrio])) {

                                    $nombreBarrio =
                                        $barrios[$idBarrio]['nombre'];

                                    $nombreLocalidad =
                                        $barrios[$idBarrio]['localidad'];

                                    $nombreProvincia =
                                        $barrios[$idBarrio]['provincia'];

                                    $nombrePais =
                                        $barrios[$idBarrio]['pais'];
                                }

                                ?>


                                <tr>


                                    <td>

                                        <?= htmlspecialchars($idCalle) ?>

                                    </td>


                                    <td>

                                        <span class="fw-semibold">

                                            <?= htmlspecialchars(
                                                $calle['nombre_calle']
                                            ) ?>

                                        </span>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $calle['calle_altura']
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $nombreBarrio
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $nombreLocalidad
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $nombreProvincia
                                        ) ?>

                                    </td>


                                    <td>

                                        <?= htmlspecialchars(
                                            $nombrePais
                                        ) ?>

                                    </td>


                                    <td class="text-center">


                                        <!-- EDITAR -->

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarCalle<?= $idCalle ?>"
                                            title="Editar">

                                            <i class="fa-solid fa-pen"></i>

                                        </button>


                                        <!-- ELIMINAR -->

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger btnEliminarCalle"
                                            data-id="<?= $idCalle ?>"
                                            data-nombre="<?= htmlspecialchars(
                                                $calle['nombre_calle'],
                                                ENT_QUOTES
                                            ) ?>"
                                            title="Eliminar">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>


                                    </td>


                                </tr>


                                <!-- ================================================= -->
                                <!-- MODAL EDITAR -->
                                <!-- ================================================= -->

                                <div
                                    class="modal fade"
                                    id="modalEditarCalle<?= $idCalle ?>"
                                    tabindex="-1"
                                    aria-hidden="true">


                                    <div class="modal-dialog modal-dialog-centered">


                                        <div class="modal-content border-0 shadow rounded-4">


                                            <form
                                                method="POST"
                                                action="<?= $calleControllerPath ?>">


                                                <div class="modal-header border-0">


                                                    <h5 class="modal-title fw-semibold">

                                                        Editar Calle

                                                    </h5>


                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                    </button>


                                                </div>


                                                <div class="modal-body">


                                                    <input
                                                        type="hidden"
                                                        name="action"
                                                        value="actualizacion">


                                                    <input
                                                        type="hidden"
                                                        name="id_calle"
                                                        value="<?= $idCalle ?>">


                                                    <!-- NOMBRE -->

                                                    <div class="mb-3">

                                                        <label
                                                            class="form-label fw-semibold">

                                                            Nombre de la calle

                                                        </label>


                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            name="nombre_calle"
                                                            value="<?= htmlspecialchars(
                                                                $calle['nombre_calle']
                                                            ) ?>"
                                                            required>

                                                    </div>


                                                    <!-- ALTURA -->

                                                    <div class="mb-3">

                                                        <label
                                                            class="form-label fw-semibold">

                                                            Altura

                                                        </label>


                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            name="calle_altura"
                                                            value="<?= htmlspecialchars(
                                                                $calle['calle_altura']
                                                            ) ?>"
                                                            placeholder="Ej. 1250"
                                                            required>

                                                    </div>


                                                    <!-- BARRIO -->

                                                    <div class="mb-3">

                                                        <label
                                                            class="form-label fw-semibold">

                                                            Barrio

                                                        </label>


                                                        <select
                                                            class="form-select"
                                                            name="barrio_id_barrio"
                                                            required>


                                                            <option value="">

                                                                Seleccionar barrio

                                                            </option>


                                                            <?php foreach ($barrios as $id => $barrio): ?>


                                                                <option
                                                                    value="<?= $id ?>"
                                                                    <?= ($id == $idBarrio)
                                                                        ? 'selected'
                                                                        : '' ?>>


                                                                    <?= htmlspecialchars(
                                                                        $barrio['nombre']
                                                                    ) ?>


                                                                    <?php if ($barrio['localidad'] !== ''): ?>

                                                                        -
                                                                        <?= htmlspecialchars(
                                                                            $barrio['localidad']
                                                                        ) ?>

                                                                    <?php endif; ?>


                                                                    <?php if ($barrio['provincia'] !== ''): ?>

                                                                        -
                                                                        <?= htmlspecialchars(
                                                                            $barrio['provincia']
                                                                        ) ?>

                                                                    <?php endif; ?>


                                                                </option>


                                                            <?php endforeach; ?>


                                                        </select>

                                                    </div>


                                                </div>


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

                                                        <i class="fa-solid fa-save me-2"></i>

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

                                <td
                                    colspan="8"
                                    class="text-center text-muted py-4">

                                    No hay calles registradas.

                                </td>

                            </tr>


                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>



<!-- ===================================================== -->
<!-- OFFCANVAS NUEVA CALLE -->
<!-- ===================================================== -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevaCalle"
    aria-labelledby="offcanvasNuevaCalleLabel">


    <div class="offcanvas-header">


        <h5
            class="offcanvas-title fw-semibold"
            id="offcanvasNuevaCalleLabel">

            Nueva Calle

        </h5>


        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas">
        </button>


    </div>


    <div class="offcanvas-body">


        <form
            method="POST"
            action="<?= $calleControllerPath ?>"
            id="formNuevaCalle">


            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- NOMBRE -->

            <div class="mb-3">


                <label
                    for="nombre_calle"
                    class="form-label fw-semibold">

                    Nombre de la calle

                </label>


                <input
                    type="text"
                    class="form-control"
                    id="nombre_calle"
                    name="nombre_calle"
                    placeholder="Ingrese el nombre de la calle"
                    required>


            </div>


            <!-- ALTURA -->

            <div class="mb-3">


                <label
                    for="calle_altura"
                    class="form-label fw-semibold">

                    Altura

                </label>


                <input
                    type="text"
                    class="form-control"
                    id="calle_altura"
                    name="calle_altura"
                    placeholder="Ej. 1250"
                    required>


            </div>


            <!-- BARRIO -->

            <div class="mb-3">


                <label
                    for="barrio_id_barrio"
                    class="form-label fw-semibold">

                    Barrio

                </label>


                <select
                    class="form-select"
                    id="barrio_id_barrio"
                    name="barrio_id_barrio"
                    required>


                    <option value="">

                        Seleccionar barrio

                    </option>


                    <?php foreach ($barrios as $id => $barrio): ?>


                        <option value="<?= $id ?>">


                            <?= htmlspecialchars(
                                $barrio['nombre']
                            ) ?>


                            <?php if ($barrio['localidad'] !== ''): ?>

                                -
                                <?= htmlspecialchars(
                                    $barrio['localidad']
                                ) ?>

                            <?php endif; ?>


                            <?php if ($barrio['provincia'] !== ''): ?>

                                -
                                <?= htmlspecialchars(
                                    $barrio['provincia']
                                ) ?>

                            <?php endif; ?>


                        </option>


                    <?php endforeach; ?>


                </select>


            </div>


            <div class="d-grid mt-4">


                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="fa-solid fa-save me-2"></i>

                    Guardar Calle

                </button>


            </div>


        </form>

    </div>

</div>



<!-- ===================================================== -->
<!-- JAVASCRIPT -->
<!-- ===================================================== -->

<script>

document.addEventListener("DOMContentLoaded", function () {


    // =====================================================
    // BUSCADOR
    // =====================================================

    const buscador =
        document.getElementById("buscarCalle");


    if (buscador) {

        buscador.addEventListener("keyup", function () {

            const texto =
                this.value.toLowerCase();


            const filas =
                document.querySelectorAll(
                    "#tablaCalles tbody tr"
                );


            filas.forEach(function (fila) {

                const contenido =
                    fila.textContent.toLowerCase();


                fila.style.display =
                    contenido.includes(texto)
                        ? ""
                        : "none";

            });

        });

    }



    // =====================================================
    // ELIMINAR CALLE
    // =====================================================

    const botonesEliminar =
        document.querySelectorAll(
            ".btnEliminarCalle"
        );


    botonesEliminar.forEach(function (boton) {


        boton.addEventListener("click", function () {


            const id =
                this.dataset.id;


            const nombre =
                this.dataset.nombre;


            Swal.fire({

                title: "¿Eliminar calle?",

                text:
                    'Se eliminará la calle "' +
                    nombre +
                    '".',

                icon: "warning",

                showCancelButton: true,

                confirmButtonText:
                    "Sí, eliminar",

                cancelButtonText:
                    "Cancelar",

                reverseButtons: true

            }).then(function (resultado) {


                if (!resultado.isConfirmed) {
                    return;
                }


                fetch(
                    "<?= $calleControllerPath ?>",
                    {

                        method: "POST",

                        headers: {
                            "Content-Type":
                                "application/x-www-form-urlencoded"
                        },

                        body:
                            "action=eliminacion" +
                            "&id_calle=" +
                            encodeURIComponent(id)

                    }
                )

                .then(function (response) {

                    return response.json();

                })

                .then(function (data) {


                    if (data.success) {


                        Swal.fire({

                            icon: "success",

                            title: "Eliminado",

                            text: data.mensaje,

                            timer: 1500,

                            showConfirmButton: false

                        }).then(function () {

                            location.reload();

                        });


                    } else {


                        Swal.fire({

                            icon: "error",

                            title: "Error",

                            text:
                                data.mensaje ||
                                "No se pudo eliminar la calle."

                        });

                    }

                })

                .catch(function (error) {


                    console.error(error);


                    Swal.fire({

                        icon: "error",

                        title: "Error",

                        text:
                            "Ocurrió un error al comunicarse con el servidor."

                    });

                });

            });

        });

    }



    // =====================================================
    // VALIDACIÓN NUEVA CALLE
    // =====================================================

    const formNueva =
        document.getElementById(
            "formNuevaCalle"
        );


    if (formNueva) {


        formNueva.addEventListener(
            "submit",
            function (event) {


                const nombre =
                    document.getElementById(
                        "nombre_calle"
                    );


                const altura =
                    document.getElementById(
                        "calle_altura"
                    );


                const barrio =
                    document.getElementById(
                        "barrio_id_barrio"
                    );


                if (
                    nombre.value.trim() === ""
                ) {


                    event.preventDefault();


                    Swal.fire({

                        icon: "warning",

                        title: "Campo requerido",

                        text:
                            "Debe ingresar el nombre de la calle."

                    });


                    nombre.focus();

                    return;

                }


                if (
                    altura.value.trim() === ""
                ) {


                    event.preventDefault();


                    Swal.fire({

                        icon: "warning",

                        title: "Campo requerido",

                        text:
                            "Debe ingresar la altura de la calle."

                    });


                    altura.focus();

                    return;

                }


                if (
                    barrio.value === ""
                ) {


                    event.preventDefault();


                    Swal.fire({

                        icon: "warning",

                        title: "Campo requerido",

                        text:
                            "Debe seleccionar un barrio."

                    });


                    barrio.focus();

                }

            }
        );

    }



    // =====================================================
    // VALIDACIÓN EDICIÓN
    // =====================================================

    const formulariosEdicion =
        document.querySelectorAll(
            'form[action="<?= $calleControllerPath ?>"]'
        );


    formulariosEdicion.forEach(
        function (formulario) {


            if (
                formulario.id ===
                "formNuevaCalle"
            ) {

                return;

            }


            formulario.addEventListener(
                "submit",
                function (event) {


                    const nombre =
                        formulario.querySelector(
                            'input[name="nombre_calle"]'
                        );


                    const altura =
                        formulario.querySelector(
                            'input[name="calle_altura"]'
                        );


                    const barrio =
                        formulario.querySelector(
                            'select[name="barrio_id_barrio"]'
                        );


                    if (
                        !nombre ||
                        nombre.value.trim() === ""
                    ) {


                        event.preventDefault();


                        Swal.fire({

                            icon: "warning",

                            title: "Campo requerido",

                            text:
                                "Debe ingresar el nombre de la calle."

                        });


                        if (nombre) {
                            nombre.focus();
                        }


                        return;

                    }


                    if (
                        !altura ||
                        altura.value.trim() === ""
                    ) {


                        event.preventDefault();


                        Swal.fire({

                            icon: "warning",

                            title: "Campo requerido",

                            text:
                                "Debe ingresar la altura de la calle."

                        });


                        if (altura) {
                            altura.focus();
                        }


                        return;

                    }


                    if (
                        !barrio ||
                        barrio.value === ""
                    ) {


                        event.preventDefault();


                        Swal.fire({

                            icon: "warning",

                            title: "Campo requerido",

                            text:
                                "Debe seleccionar un barrio."

                        });


                        if (barrio) {
                            barrio.focus();
                        }

                    }

                }
            );

        }
    );

});

</script>