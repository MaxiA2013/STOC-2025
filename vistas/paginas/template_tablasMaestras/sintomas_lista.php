<?php

include_once "modelos/sintomas.php";

$sintoma = new Sintomas();
$lista_sintomas = $sintoma->consultarVariosSintomas();

?>

<div class="container-fluid py-4 px-4">

    <!-- Encabezado -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Gestión de Síntomas
            </h2>

            <p class="text-muted mb-0">
                Administra los síntomas registrados en el sistema.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoSintoma">

            <i class="fa-solid fa-plus me-2"></i>
            Nuevo síntoma

        </button>

    </div>


    <!-- Tarjeta principal -->
    <div class="card border-0 shadow-sm">

        <!-- Cabecera de la tarjeta -->
        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Síntomas registrados
                    </h5>

                    <small class="text-muted">
                        Listado de síntomas disponibles en el sistema.
                    </small>
                </div>


                <!-- Buscador -->
                <div class="position-relative" style="max-width: 320px; width: 100%;">

                    <i class="fa-solid fa-magnifying-glass position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>

                    <input
                        type="text"
                        id="buscarSintoma"
                        class="form-control ps-5"
                        placeholder="Buscar síntoma...">

                </div>

            </div>

        </div>


        <!-- Tabla -->
        <div class="card-body px-4 pb-4">

            <div class="table-responsive">

                <table class="table align-middle mb-0" id="tablaSintomas">

                    <thead class="table-light">

                        <tr>

                            <th class="text-muted small fw-semibold">
                                ID
                            </th>

                            <th class="text-muted small fw-semibold">
                                Síntoma
                            </th>

                            <th class="text-muted small fw-semibold">
                                Descripción
                            </th>

                            <th class="text-muted small fw-semibold text-end">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody id="bodyTabla">

                        <?php while ($fila = $lista_sintomas->fetch_assoc()): ?>

                            <tr id="fila<?= $fila["id_sintomas"] ?>" class="fila-sintoma">

                                <!-- ID -->
                                <td>

                                    <span class="text-muted fw-semibold">
                                        #<?= htmlspecialchars($fila["id_sintomas"]) ?>
                                    </span>

                                </td>


                                <!-- Síntoma -->
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div>

                                            <div class="fw-semibold text-dark">
                                                <?= htmlspecialchars($fila["nombre_sintomas"]) ?>
                                            </div>

                                            <small class="text-muted">
                                                Síntoma médico
                                            </small>

                                        </div>
                                    </div>
                                </td>


                                <!-- Descripción -->
                                <td>
                                    <span class="text-muted">

                                        <?= htmlspecialchars($fila["descripcion"]) ?>

                                    </span>

                                </td>


                                <!-- Acciones -->
                                <td class="text-end">

                                    <div class="d-flex justify-content-end gap-2">

                                        <!-- Editar -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Editar síntoma"
                                            onclick='editar(
                                                <?= json_encode($fila["id_sintomas"]) ?>,
                                                <?= json_encode($fila["nombre_sintomas"]) ?>,
                                                <?= json_encode($fila["descripcion"]) ?>
                                            )'>

                                            <i class="fa-solid fa-pen"></i>

                                        </button>


                                        <!-- Eliminar -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar síntoma"
                                            onclick="eliminar(<?= $fila['id_sintomas'] ?>)">

                                            <i class="fa-solid fa-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>


            <!-- Mensaje cuando no hay resultados -->
            <div
                id="sinResultados"
                class="text-center py-5 d-none">

                <div
                    class="d-flex align-items-center justify-content-center rounded-circle bg-light mx-auto mb-3"
                    style="width: 60px; height: 60px;">

                    <i class="fa-solid fa-magnifying-glass text-muted fs-4"></i>

                </div>

                <h6 class="fw-semibold">
                    No se encontraron síntomas
                </h6>

                <p class="text-muted mb-0">
                    Probá con otro término de búsqueda.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- OFFCANVAS - NUEVO SÍNTOMA -->
<!-- ========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoSintoma"
    aria-labelledby="offcanvasNuevoSintomaLabel">

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-bold"
                id="offcanvasNuevoSintomaLabel">

                <i class="fa-solid fa-notes-medical text-primary me-2"></i>

                Nuevo síntoma

            </h5>

            <small class="text-muted">
                Registrá un nuevo síntoma.
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

        <form id="formNuevoSintoma">

            <div class="mb-4">

                <label
                    for="nombre_sintomas_nuevo"
                    class="form-label fw-semibold">

                    Nombre del síntoma

                </label>

                <input
                    type="text"
                    id="nombre_sintomas_nuevo"
                    class="form-control"
                    placeholder="Ej. Dolor de cabeza"
                    required>

                <div class="form-text">
                    Ingresá el nombre del síntoma.
                </div>

            </div>


            <div class="mb-4">

                <label
                    for="descripcion_nuevo"
                    class="form-label fw-semibold">

                    Descripción

                </label>

                <textarea
                    id="descripcion_nuevo"
                    class="form-control"
                    rows="4"
                    placeholder="Descripción del síntoma..."
                    required></textarea>

                <div class="form-text">
                    Agregá una breve descripción.
                </div>

            </div>


            <div class="d-flex gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-primary flex-grow-1"
                    onclick="guardarNuevo()">

                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    Guardar síntoma

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


<!-- ========================================================= -->
<!-- MODAL - EDITAR SÍNTOMA -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalForm"
    tabindex="-1"
    aria-labelledby="modalFormLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="modalFormLabel">

                        <i class="fa-solid fa-pen-to-square text-primary me-2"></i>

                        Editar síntoma

                    </h5>

                    <small class="text-muted">
                        Modificá los datos del síntoma.
                    </small>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>

            </div>


            <div class="modal-body">

                <input
                    type="hidden"
                    id="id_sintomas">


                <div class="mb-3">

                    <label
                        for="nombre_sintomas"
                        class="form-label fw-semibold">

                        Nombre del síntoma

                    </label>

                    <input
                        type="text"
                        id="nombre_sintomas"
                        class="form-control"
                        required>

                </div>


                <div class="mb-3">

                    <label
                        for="descripcion"
                        class="form-label fw-semibold">

                        Descripción

                    </label>

                    <textarea
                        id="descripcion"
                        class="form-control"
                        rows="4"
                        required></textarea>

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
                    type="button"
                    class="btn btn-primary"
                    onclick="guardar()">

                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    Guardar cambios

                </button>

            </div>

        </div>

    </div>

</div>


<script>

/* =========================================================
   NUEVO SÍNTOMA
   ========================================================= */

function guardarNuevo() {

    let nombre = document.getElementById("nombre_sintomas_nuevo").value.trim();
    let descripcion = document.getElementById("descripcion_nuevo").value.trim();


    if (nombre === "") {

        alert("Ingresá el nombre del síntoma.");
        return;

    }


    if (descripcion === "") {

        alert("Ingresá la descripción del síntoma.");
        return;

    }


    let form = new FormData();

    form.append("nombre_sintomas", nombre);
    form.append("descripcion", descripcion);
    form.append("action", "insertar");


    fetch("controladores/sintomas_controlador.php", {

        method: "POST",
        body: form

    })

    .then(r => r.json())

    .then(data => {

        if (data.status === "ok") {

            location.reload();

        } else {

            alert(data.message);

        }

    })

    .catch(error => {

        console.error(error);

        alert("Ocurrió un error al registrar el síntoma.");

    });

}


/* =========================================================
   EDITAR
   ========================================================= */

function editar(id, nombre, descripcion) {

    document.getElementById("id_sintomas").value = id;

    document.getElementById("nombre_sintomas").value = nombre;

    document.getElementById("descripcion").value = descripcion;


    let modal = new bootstrap.Modal(
        document.getElementById("modalForm")
    );

    modal.show();

}


/* =========================================================
   GUARDAR EDICIÓN
   ========================================================= */

function guardar() {

    let id = document.getElementById("id_sintomas").value;

    let nombre = document.getElementById("nombre_sintomas").value.trim();

    let descripcion = document.getElementById("descripcion").value.trim();


    if (nombre === "") {

        alert("Ingresá el nombre del síntoma.");
        return;

    }


    if (descripcion === "") {

        alert("Ingresá la descripción del síntoma.");
        return;

    }


    let form = new FormData();

    form.append("nombre_sintomas", nombre);

    form.append("descripcion", descripcion);

    form.append("action", "actualizar");

    form.append("id_sintomas", id);


    fetch("controladores/sintomas_controlador.php", {

        method: "POST",
        body: form

    })

    .then(r => r.json())

    .then(data => {

        if (data.status === "ok") {

            location.reload();

        } else {

            alert(data.message);

        }

    })

    .catch(error => {

        console.error(error);

        alert("Ocurrió un error al actualizar el síntoma.");

    });

}


/* =========================================================
   ELIMINAR
   ========================================================= */

function eliminar(id) {

    if (!confirm("¿Estás seguro de que querés eliminar este síntoma?")) {

        return;

    }


    let form = new FormData();

    form.append("action", "eliminar");

    form.append("id_sintomas", id);


    fetch("controladores/sintomas_controlador.php", {

        method: "POST",
        body: form

    })

    .then(r => r.json())

    .then(data => {

        if (data.status === "ok") {

            let fila = document.getElementById("fila" + id);

            if (fila) {

                fila.remove();

            }

        } else {

            alert(data.message);

        }

    })

    .catch(error => {

        console.error(error);

        alert("Ocurrió un error al eliminar el síntoma.");

    });

}


/* =========================================================
   BUSCADOR
   ========================================================= */

document.getElementById("buscarSintoma").addEventListener("input", function () {

    let texto = this.value.toLowerCase().trim();

    let filas = document.querySelectorAll(".fila-sintoma");

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


    let sinResultados = document.getElementById("sinResultados");


    if (encontrados === 0) {

        sinResultados.classList.remove("d-none");

    } else {

        sinResultados.classList.add("d-none");

    }

});

</script>