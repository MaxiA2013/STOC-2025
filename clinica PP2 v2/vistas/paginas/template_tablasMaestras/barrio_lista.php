<?php

include_once "modelos/barrio.php";
include_once "modelos/localidad.php";
include_once "modelos/provincia.php";
include_once "modelos/pais.php";

$barrioModel = new Barrio();
$lista_barrios = $barrioModel->consultarVariosBarrios();

$localidadModel = new Localidad();
$lista_localidades = $localidadModel->consultarVariasLocalidades();

$provinciaModel = new Provincia();
$lista_provincias = $provinciaModel->consultarVariasProvincias();

$paisModel = new Pais();
$lista_paises = $paisModel->consultarVariosPaises();


// ---------------------------------------------------------
// ARMAR ARRAY DE PAÍSES
// ---------------------------------------------------------

$paises = [];

if ($lista_paises) {
    while ($pais = $lista_paises->fetch_assoc()) {

        $paises[$pais['id_pais']] = $pais['descripcion'];
    }
}


// ---------------------------------------------------------
// ARMAR ARRAY DE PROVINCIAS
// ---------------------------------------------------------

$provincias = [];

if ($lista_provincias) {
    while ($provincia = $lista_provincias->fetch_assoc()) {

        $provincias[$provincia['id_provincia']] = [
            'nombre' => $provincia['nombre_provincia'],
            'pais_id' => (int) $provincia['pais_id_pais']
        ];
    }
}


// ---------------------------------------------------------
// ARMAR ARRAY DE LOCALIDADES
// ---------------------------------------------------------

$localidades = [];

if ($lista_localidades) {
    while ($localidad = $lista_localidades->fetch_assoc()) {

        $localidadId = $localidad['id_localidad'];
        $provinciaId = (int) $localidad['id_provincia'];

        $nombreProvincia = '';
        $paisId = null;
        $nombrePais = '';

        if (isset($provincias[$provinciaId])) {

            $nombreProvincia = $provincias[$provinciaId]['nombre'];
            $paisId = $provincias[$provinciaId]['pais_id'];

            if (isset($paises[$paisId])) {
                $nombrePais = $paises[$paisId];
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


$barrioControllerPath = "controladores/barrio_controlador.php";

?>

<div class="container-fluid py-4 px-4">

    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-semibold mb-1">
                Gestión de Barrios
            </h2>

            <p class="text-muted mb-0">
                Administración de barrios y sus localidades.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoBarrio">

            <i class="fa-solid fa-plus me-2"></i>
            Nuevo Barrio

        </button>

    </div>


    <!-- CONTENEDOR PRINCIPAL -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- TÍTULO Y BUSCADOR -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Lista de Barrios
                    </h5>

                    <p class="text-muted small mb-0">
                        Barrios registrados en el sistema.
                    </p>
                </div>


                <div style="width: 280px;">

                    <div class="input-group">

                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-magnifying-glass text-muted"></i>
                        </span>

                        <input
                            type="text"
                            id="buscarBarrio"
                            class="form-control"
                            placeholder="Buscar barrio...">

                    </div>

                </div>

            </div>


            <!-- TABLA -->
            <div class="table-responsive">

                <table
                    class="table table-hover align-middle mb-0"
                    id="tablaBarrios">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 80px;">
                                ID
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

                        <?php if ($lista_barrios && mysqli_num_rows($lista_barrios) > 0): ?>

                            <?php while ($barrio = $lista_barrios->fetch_assoc()): ?>

                                <?php

                                $idBarrio = $barrio['id_barrio'];
                                $idLocalidad = (int) $barrio['localidad_id_localidad'];

                                $nombreLocalidad = 'Sin localidad';
                                $nombreProvincia = 'Sin provincia';
                                $nombrePais = 'Sin país';

                                if (isset($localidades[$idLocalidad])) {

                                    $nombreLocalidad =
                                        $localidades[$idLocalidad]['nombre'];

                                    $nombreProvincia =
                                        $localidades[$idLocalidad]['provincia'];

                                    $nombrePais =
                                        $localidades[$idLocalidad]['pais'];
                                }

                                ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($idBarrio) ?>
                                    </td>

                                    <td>
                                        <span class="fw-semibold">
                                            <?= htmlspecialchars($barrio['nombre_barrio']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($nombreLocalidad) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($nombreProvincia) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($nombrePais) ?>
                                    </td>

                                    <td class="text-center">

                                        <!-- BOTÓN EDITAR -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarBarrio<?= $idBarrio ?>"
                                            title="Editar">

                                            <i class="fa-solid fa-pen"></i>

                                        </button>


                                        <!-- BOTÓN ELIMINAR -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger btnEliminarBarrio"
                                            data-id="<?= $idBarrio ?>"
                                            data-nombre="<?= htmlspecialchars($barrio['nombre_barrio'], ENT_QUOTES) ?>"
                                            title="Eliminar">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </td>

                                </tr>


                                <!-- MODAL EDITAR -->
                                <div
                                    class="modal fade"
                                    id="modalEditarBarrio<?= $idBarrio ?>"
                                    tabindex="-1"
                                    aria-hidden="true">

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 shadow rounded-4">

                                            <form
                                                method="POST"
                                                action="<?= $barrioControllerPath ?>">

                                                <div class="modal-header border-0">

                                                    <h5 class="modal-title fw-semibold">
                                                        Editar Barrio
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
                                                        name="id_barrio"
                                                        value="<?= $idBarrio ?>">


                                                    <!-- NOMBRE -->
                                                    <div class="mb-3">

                                                        <label
                                                            class="form-label fw-semibold">

                                                            Nombre del barrio

                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            name="nombre_barrio"
                                                            value="<?= htmlspecialchars($barrio['nombre_barrio']) ?>"
                                                            required>

                                                    </div>


                                                    <!-- LOCALIDAD -->
                                                    <div class="mb-3">

                                                        <label
                                                            class="form-label fw-semibold">

                                                            Localidad

                                                        </label>

                                                        <select
                                                            class="form-select"
                                                            name="localidad_id_localidad"
                                                            required>

                                                            <option value="">
                                                                Seleccionar localidad
                                                            </option>

                                                            <?php foreach ($localidades as $id => $localidad): ?>

                                                                <option
                                                                    value="<?= $id ?>"
                                                                    <?= ($id == $idLocalidad) ? 'selected' : '' ?>>

                                                                    <?= htmlspecialchars($localidad['nombre']) ?>

                                                                    <?php if ($localidad['provincia'] !== ''): ?>
                                                                        -
                                                                        <?= htmlspecialchars($localidad['provincia']) ?>
                                                                    <?php endif; ?>

                                                                    <?php if ($localidad['pais'] !== ''): ?>
                                                                        -
                                                                        <?= htmlspecialchars($localidad['pais']) ?>
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
                                    colspan="6"
                                    class="text-center text-muted py-4">

                                    No hay barrios registrados.

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
<!-- OFFCANVAS NUEVO BARRIO -->
<!-- ===================================================== -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoBarrio"
    aria-labelledby="offcanvasNuevoBarrioLabel">

    <div class="offcanvas-header">

        <h5
            class="offcanvas-title fw-semibold"
            id="offcanvasNuevoBarrioLabel">

            Nuevo Barrio

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
            action="<?= $barrioControllerPath ?>"
            id="formNuevoBarrio">

            <input
                type="hidden"
                name="action"
                value="insertar">


            <!-- NOMBRE -->
            <div class="mb-3">

                <label
                    for="nombre_barrio"
                    class="form-label fw-semibold">

                    Nombre del barrio

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_barrio"
                    name="nombre_barrio"
                    placeholder="Ingrese el nombre del barrio"
                    required>

            </div>


            <!-- LOCALIDAD -->
            <div class="mb-3">

                <label
                    for="localidad_id_localidad"
                    class="form-label fw-semibold">

                    Localidad

                </label>

                <select
                    class="form-select"
                    id="localidad_id_localidad"
                    name="localidad_id_localidad"
                    required>

                    <option value="">
                        Seleccionar localidad
                    </option>

                    <?php foreach ($localidades as $id => $localidad): ?>

                        <option value="<?= $id ?>">

                            <?= htmlspecialchars($localidad['nombre']) ?>

                            <?php if ($localidad['provincia'] !== ''): ?>
                                -
                                <?= htmlspecialchars($localidad['provincia']) ?>
                            <?php endif; ?>

                            <?php if ($localidad['pais'] !== ''): ?>
                                -
                                <?= htmlspecialchars($localidad['pais']) ?>
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
                    Guardar Barrio

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


    // -----------------------------------------------------
    // BUSCADOR
    // -----------------------------------------------------

    const buscador = document.getElementById("buscarBarrio");

    if (buscador) {

        buscador.addEventListener("keyup", function () {

            const texto = this.value.toLowerCase();

            const filas = document.querySelectorAll(
                "#tablaBarrios tbody tr"
            );

            filas.forEach(function (fila) {

                const contenido = fila.textContent.toLowerCase();

                fila.style.display =
                    contenido.includes(texto) ? "" : "none";

            });

        });

    }


    // -----------------------------------------------------
    // ELIMINAR BARRIO
    // -----------------------------------------------------

    const botonesEliminar =
        document.querySelectorAll(".btnEliminarBarrio");

    botonesEliminar.forEach(function (boton) {

        boton.addEventListener("click", function () {

            const id = this.dataset.id;
            const nombre = this.dataset.nombre;


            Swal.fire({

                title: "¿Eliminar barrio?",

                text:
                    "Se eliminará el barrio \"" +
                    nombre +
                    "\".",

                icon: "warning",

                showCancelButton: true,

                confirmButtonText: "Sí, eliminar",

                cancelButtonText: "Cancelar",

                reverseButtons: true

            }).then(function (resultado) {

                if (!resultado.isConfirmed) {
                    return;
                }


                fetch("<?= $barrioControllerPath ?>", {

                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/x-www-form-urlencoded"
                    },

                    body:
                        "action=eliminacion" +
                        "&id_barrio=" +
                        encodeURIComponent(id)

                })

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
                                "No se pudo eliminar el barrio."

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


    // -----------------------------------------------------
    // VALIDACIÓN NUEVO BARRIO
    // -----------------------------------------------------

    const formNuevo =
        document.getElementById("formNuevoBarrio");

    if (formNuevo) {

        formNuevo.addEventListener("submit", function (event) {

            const nombre =
                document.getElementById("nombre_barrio");

            const localidad =
                document.getElementById("localidad_id_localidad");


            if (nombre.value.trim() === "") {

                event.preventDefault();

                Swal.fire({

                    icon: "warning",

                    title: "Campo requerido",

                    text:
                        "Debe ingresar el nombre del barrio."

                });

                nombre.focus();

                return;

            }


            if (localidad.value === "") {

                event.preventDefault();

                Swal.fire({

                    icon: "warning",

                    title: "Campo requerido",

                    text:
                        "Debe seleccionar una localidad."

                });

                localidad.focus();

            }

        });

    }


    // -----------------------------------------------------
    // VALIDACIÓN FORMULARIOS DE EDICIÓN
    // -----------------------------------------------------

    const formulariosEdicion =
        document.querySelectorAll(
            'form[action="<?= $barrioControllerPath ?>"]'
        );

    formulariosEdicion.forEach(function (formulario) {

        if (formulario.id === "formNuevoBarrio") {
            return;
        }


        formulario.addEventListener("submit", function (event) {

            const nombre =
                formulario.querySelector(
                    'input[name="nombre_barrio"]'
                );

            const localidad =
                formulario.querySelector(
                    'select[name="localidad_id_localidad"]'
                );


            if (!nombre || nombre.value.trim() === "") {

                event.preventDefault();

                Swal.fire({

                    icon: "warning",

                    title: "Campo requerido",

                    text:
                        "Debe ingresar el nombre del barrio."

                });

                if (nombre) {
                    nombre.focus();
                }

                return;

            }


            if (!localidad || localidad.value === "") {

                event.preventDefault();

                Swal.fire({

                    icon: "warning",

                    title: "Campo requerido",

                    text:
                        "Debe seleccionar una localidad."

                });

                if (localidad) {
                    localidad.focus();
                }

            }

        });

    }

});

</script>


<script src="assets/js/validaciones/validaciones_controlador.js"></script>