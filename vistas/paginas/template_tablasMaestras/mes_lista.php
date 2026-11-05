<?php
/*
 * Vista: meses_lista.php
 *
 * La gestión de los meses se realiza mediante AJAX.
 * Se mantienen las acciones existentes del controlador:
 * listar, insertar, actualizar y eliminar.
 */
?>

<div class="container-fluid py-4 px-4">

    <!-- ENCABEZADO-->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h3 class="fw-semibold mb-1">
                <i class="bi bi-calendar3 me-2"></i>
                Gestión de Meses
            </h3>

            <p class="text-muted mb-0">
                Administración de los meses disponibles para la gestión del sistema.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevoMes"
            aria-controls="offcanvasNuevoMes">

            <i class="bi bi-plus-lg me-1"></i>
            Nuevo Mes

        </button>

    </div>


    <!-- =====================================================
         CARD PRINCIPAL
    ====================================================== -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- Cabecera -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div>

                    <h5 class="fw-semibold mb-1">
                        Meses Registrados
                    </h5>

                    <p class="text-muted small mb-0">
                        Listado de meses disponibles en el sistema.
                    </p>

                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">

                    <i class="bi bi-calendar3 me-1"></i>

                    <span id="contadorMeses">0</span> registros

                </span>

            </div>


            <!-- Buscador -->
            <div class="mb-4">

                <div class="input-group">

                    <span class="input-group-text bg-white border-end-0">

                        <i class="bi bi-search text-muted"></i>

                    </span>

                    <input
                        type="text"
                        class="form-control border-start-0"
                        id="buscarMes"
                        placeholder="Buscar mes..."
                        autocomplete="off">

                </div>

            </div>


            <!-- Tabla -->
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th style="width: 90px;">
                                ID
                            </th>

                            <th>
                                Mes
                            </th>

                            <th
                                class="text-end"
                                style="width: 160px;">

                                Acciones

                            </th>

                        </tr>

                    </thead>

                    <tbody id="tbodyMes">

                        <tr>

                            <td
                                colspan="3"
                                class="text-center text-muted py-5">

                                <div
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status">
                                </div>

                                Cargando meses...

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     OFFCANVAS - NUEVO MES
========================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevoMes"
    aria-labelledby="offcanvasNuevoMesLabel">

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevoMesLabel">

                <i class="bi bi-calendar-plus me-2"></i>
                Nuevo Mes

            </h5>

            <small class="text-muted">
                Registra un nuevo mes en el sistema.
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

        <form id="formMes">

            <div class="mb-3">

                <label
                    for="nombre_mes_nuevo"
                    class="form-label">

                    Nombre del mes

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nombre_mes_nuevo"
                    placeholder="Ej.: Enero"
                    autocomplete="off">

            </div>


            <div class="d-flex justify-content-end gap-2 mt-4">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="offcanvas">

                    Cancelar

                </button>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-check-lg me-1"></i>
                    Guardar

                </button>

            </div>

        </form>

    </div>

</div>


<!-- =========================================================
     MODAL - EDITAR MES
========================================================= -->

<div
    class="modal fade"
    id="modalEditarMes"
    tabindex="-1"
    aria-labelledby="modalEditarMesLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-semibold"
                        id="modalEditarMesLabel">

                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Mes

                    </h5>

                    <small class="text-muted">
                        Modifica la información del mes seleccionado.
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
                    id="id_mes">

                <div class="mb-3">

                    <label
                        for="nombre_mes"
                        class="form-label">

                        Nombre del mes

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nombre_mes"
                        placeholder="Ej.: Enero"
                        autocomplete="off">

                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal">

                    Cancelar

                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="btnActualizarMes">

                    <i class="bi bi-check-lg me-1"></i>
                    Guardar cambios

                </button>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener("DOMContentLoaded", function () {

    cargarMeses();

    /*
     * Formulario de alta
     */
    document
        .getElementById("formMes")
        .addEventListener("submit", function (e) {

            e.preventDefault();

            guardarMes();

        });


    /*
     * Botón de actualización
     */
    document
        .getElementById("btnActualizarMes")
        .addEventListener("click", function () {

            actualizarMes();

        });


    /*
     * Buscador
     */
    configurarBuscadorMes();

});


/* =========================================================
   LISTAR MESES
========================================================= */

function cargarMeses() {

    const data = new FormData();

    data.append("action", "listar");


    fetch("controladores/mes_controlador.php", {

        method: "POST",
        body: data

    })

    .then(res => res.json())

    .then(meses => {

        const tbody =
            document.getElementById("tbodyMes");

        let tabla = "";


        if (!Array.isArray(meses) || meses.length === 0) {

            tbody.innerHTML = `

                <tr>

                    <td
                        colspan="3"
                        class="text-center text-muted py-5">

                        <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>

                        No hay meses registrados.

                    </td>

                </tr>

            `;

            actualizarContadorMeses(0);

            return;
        }


        meses.forEach(m => {

            tabla += `

                <tr>

                    <td class="fw-medium">

                        ${m.id_mes}

                    </td>


                    <td>

                        <div class="d-flex align-items-center gap-2">

                            <span
                                class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle"
                                style="width: 36px; height: 36px;">

                                <i class="bi bi-calendar3"></i>

                            </span>

                            <span class="fw-medium">

                                ${escapeHtml(m.nombre_mes)}

                            </span>

                        </div>

                    </td>


                    <td class="text-end">

                        <div
                            class="btn-group"
                            role="group">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                title="Editar"
                                onclick="editarMes(${m.id_mes}, ${JSON.stringify(m.nombre_mes)})">

                                <i class="bi bi-pencil"></i>

                            </button>


                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                title="Eliminar"
                                onclick="eliminarMes(${m.id_mes})">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </td>

                </tr>

            `;

        });


        tbody.innerHTML = tabla;

        actualizarContadorMeses(meses.length);

    })

    .catch(error => {

        console.error("Error al cargar meses:", error);

        document.getElementById("tbodyMes").innerHTML = `

            <tr>

                <td
                    colspan="3"
                    class="text-center text-danger py-5">

                    <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>

                    No se pudieron cargar los meses.

                </td>

            </tr>

        `;

        actualizarContadorMeses(0);

    });

}


/* =========================================================
   GUARDAR MES
========================================================= */

function guardarMes() {

    const nombre_mes =
        document
            .getElementById("nombre_mes_nuevo")
            .value
            .trim();


    if (nombre_mes === "") {

        Swal.fire({

            icon: "warning",
            title: "Campo requerido",
            text: "Debes ingresar el nombre del mes.",
            confirmButtonText: "Aceptar"

        });

        return;
    }


    const data = new FormData();

    data.append("action", "insertar");
    data.append("nombre_mes", nombre_mes);
    data.append("id_mes", "");


    fetch("controladores/mes_controlador.php", {

        method: "POST",
        body: data

    })

    .then(res => res.json())

    .then(resp => {

        if (resp.status === "ok") {

            Swal.fire({

                icon: "success",
                title: "Mes registrado",
                text: "El mes se registró correctamente.",
                confirmButtonText: "Aceptar",
                timer: 1800,
                timerProgressBar: true

            });


            document
                .getElementById("formMes")
                .reset();


            const offcanvasElement =
                document.getElementById("offcanvasNuevoMes");

            const offcanvas =
                bootstrap.Offcanvas.getInstance(offcanvasElement);

            if (offcanvas) {

                offcanvas.hide();

            }


            cargarMeses();

        } else {

            Swal.fire({

                icon: "error",
                title: "No se pudo registrar",
                text: resp.message || "Ocurrió un error al guardar el mes.",
                confirmButtonText: "Aceptar"

            });

        }

    })

    .catch(error => {

        console.error("Error:", error);

        Swal.fire({

            icon: "error",
            title: "Error",
            text: "No se pudo procesar la solicitud.",
            confirmButtonText: "Aceptar"

        });

    });

}


/* =========================================================
   EDITAR MES
========================================================= */

function editarMes(id, nombre) {

    document.getElementById("id_mes").value = id;

    document.getElementById("nombre_mes").value = nombre;


    const modalElement =
        document.getElementById("modalEditarMes");

    const modal =
        bootstrap.Modal.getOrCreateInstance(modalElement);

    modal.show();

}


/* =========================================================
   ACTUALIZAR MES
========================================================= */

function actualizarMes() {

    const id_mes =
        document.getElementById("id_mes").value;

    const nombre_mes =
        document
            .getElementById("nombre_mes")
            .value
            .trim();


    if (nombre_mes === "") {

        Swal.fire({

            icon: "warning",
            title: "Campo requerido",
            text: "Debes ingresar el nombre del mes.",
            confirmButtonText: "Aceptar"

        });

        return;
    }


    const data = new FormData();

    data.append("action", "actualizar");
    data.append("id_mes", id_mes);
    data.append("nombre_mes", nombre_mes);


    fetch("controladores/mes_controlador.php", {

        method: "POST",
        body: data

    })

    .then(res => res.json())

    .then(resp => {

        if (resp.status === "ok") {

            Swal.fire({

                icon: "success",
                title: "Mes actualizado",
                text: "Los cambios se guardaron correctamente.",
                confirmButtonText: "Aceptar",
                timer: 1800,
                timerProgressBar: true

            });


            const modalElement =
                document.getElementById("modalEditarMes");

            const modal =
                bootstrap.Modal.getInstance(modalElement);

            if (modal) {

                modal.hide();

            }


            cargarMeses();

        } else {

            Swal.fire({

                icon: "error",
                title: "No se pudo actualizar",
                text: resp.message || "Ocurrió un error al actualizar el mes.",
                confirmButtonText: "Aceptar"

            });

        }

    })

    .catch(error => {

        console.error("Error:", error);

        Swal.fire({

            icon: "error",
            title: "Error",
            text: "No se pudo procesar la solicitud.",
            confirmButtonText: "Aceptar"

        });

    });

}


/* =========================================================
   ELIMINAR MES
========================================================= */

function eliminarMes(id) {

    Swal.fire({

        title: "¿Eliminar mes?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",

        reverseButtons: true

    })
    .then(result => {

        if (!result.isConfirmed) {

            return;

        }


        const data = new FormData();

        data.append("action", "eliminar");
        data.append("id_mes", id);


        fetch("controladores/mes_controlador.php", {

            method: "POST",
            body: data

        })

        .then(res => res.json())

        .then(resp => {

            if (resp.status === "ok") {

                Swal.fire({

                    icon: "success",
                    title: "Mes eliminado",
                    text: "El mes fue eliminado correctamente.",
                    confirmButtonText: "Aceptar",
                    timer: 1800,
                    timerProgressBar: true

                });


                cargarMeses();

            } else {

                Swal.fire({

                    icon: "error",
                    title: "No se pudo eliminar",
                    text: resp.message || "Ocurrió un error al eliminar el mes.",
                    confirmButtonText: "Aceptar"

                });

            }

        })

        .catch(error => {

            console.error("Error:", error);

            Swal.fire({

                icon: "error",
                title: "Error",
                text: "No se pudo procesar la solicitud.",
                confirmButtonText: "Aceptar"

            });

        });

    });

}


/* =========================================================
   BUSCADOR
========================================================= */

function configurarBuscadorMes() {

    const buscador =
        document.getElementById("buscarMes");


    if (!buscador) {

        return;

    }


    if (buscador.dataset.configurado === "true") {

        return;

    }


    buscador.dataset.configurado = "true";


    buscador.addEventListener("input", function () {

        const texto =
            this.value
                .toLowerCase()
                .trim();


        /*
         * Las filas se buscan cada vez que cambia
         * el texto para que el filtro siga funcionando
         * después de una recarga AJAX.
         */
        const filas =
            document.querySelectorAll("#tbodyMes tr");


        filas.forEach(fila => {

            const contenido =
                fila.textContent.toLowerCase();


            fila.style.display =
                contenido.includes(texto)
                    ? ""
                    : "none";

        });

    });

}


/* =========================================================
   CONTADOR
========================================================= */

function actualizarContadorMeses(cantidad) {

    document.getElementById("contadorMeses").textContent = cantidad;

}


/* =========================================================
   ESCAPAR HTML
========================================================= */

function escapeHtml(texto) {

    const div =
        document.createElement("div");

    div.textContent =
        texto ?? "";

    return div.innerHTML;

}
</script>