<?php
include_once "modelos/franja_horaria.php";

$franja = new Franja();
$lista_franja = $franja->consultarVariasFranjas();
?>

<div class="container-fluid py-4">

    <!-- ========================================================= -->
    <!-- ENCABEZADO -->
    <!-- ========================================================= -->

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div>
            <h2 class="fw-semibold mb-1">
                Gestión de Franjas Horarias
            </h2>

            <p class="text-muted mb-0">
                Administración de los horarios disponibles para la gestión de turnos.
            </p>
        </div>

        <!-- Botón Nueva Franja -->
        <button
            type="button"
            class="btn btn-primary rounded-3 px-4"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasNuevaFranja"
            aria-controls="offcanvasNuevaFranja">

            <i class="fa-solid fa-plus me-2"></i>
            Nueva Franja Horaria

        </button>

    </div>


    <!-- ========================================================= -->
    <!-- CARD PRINCIPAL -->
    <!-- ========================================================= -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <!-- Cabecera -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div>
                    <h5 class="fw-semibold mb-1">
                        Franjas Horarias Registradas
                    </h5>

                    <p class="text-muted small mb-0">
                        Listado de las franjas horarias configuradas.
                    </p>
                </div>

                <span
                    class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2"
                    id="contadorFranjas">

                    <?php echo is_array($lista_franja) ? count($lista_franja) : 0; ?>
                    registradas

                </span>

            </div>


            <!-- ================================================= -->
            <!-- BUSCADOR -->
            <!-- ================================================= -->

            <div class="mb-4">

                <div class="input-group">

                    <span class="input-group-text bg-white border-end-0">
                        <i class="fa-solid fa-magnifying-glass text-muted"></i>
                    </span>

                    <input
                        type="text"
                        class="form-control border-start-0"
                        id="buscarFranja"
                        placeholder="Buscar franja horaria...">

                </div>

            </div>


            <!-- ================================================= -->
            <!-- TABLA -->
            <!-- ================================================= -->

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Tipo de Franja</th>
                            <th scope="col">Inicio</th>
                            <th scope="col">Fin</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>

                    </thead>

                    <tbody id="tbodyFranja">

                        <!--
                            Las filas se cargan mediante AJAX
                            desde cargarFranjas()
                        -->

                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">

                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>

                                Cargando franjas horarias...

                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- ============================================================= -->
<!-- OFFCANVAS: NUEVA FRANJA HORARIA -->
<!-- ============================================================= -->

<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasNuevaFranja"
    aria-labelledby="offcanvasNuevaFranjaLabel">

    <div class="offcanvas-header border-bottom">

        <div>

            <h5
                class="offcanvas-title fw-semibold"
                id="offcanvasNuevaFranjaLabel">

                Nueva Franja Horaria

            </h5>

            <small class="text-muted">
                Completa los datos para registrar una nueva franja.
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

        <form id="formFranja" onsubmit="return false;">

            <!-- Tipo -->
            <div class="mb-3">

                <label
                    for="tipo_franja"
                    class="form-label fw-semibold">

                    Tipo de Franja

                </label>

                <input
                    type="text"
                    class="form-control"
                    id="tipo_franja"
                    placeholder="Ej. Mañana, Tarde, Noche"
                    required>

                <div class="form-text">
                    Indique el nombre o tipo de la franja horaria.
                </div>

            </div>


            <!-- Inicio -->
            <div class="mb-3">

                <label
                    for="inicio_franja"
                    class="form-label fw-semibold">

                    Hora de Inicio

                </label>

                <input
                    type="time"
                    class="form-control"
                    id="inicio_franja"
                    required>

            </div>


            <!-- Fin -->
            <div class="mb-3">

                <label
                    for="fin_franja"
                    class="form-label fw-semibold">

                    Hora de Fin

                </label>

                <input
                    type="time"
                    class="form-control"
                    id="fin_franja"
                    required>

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
                    type="button"
                    class="btn btn-primary rounded-3"
                    onclick="guardarFranja()">

                    <i class="fa-solid fa-plus me-2"></i>
                    Guardar

                </button>

            </div>

        </form>

    </div>

</div>


<!-- ============================================================= -->
<!-- MODAL: EDITAR FRANJA -->
<!-- ============================================================= -->

<div
    class="modal fade"
    id="modalEditar"
    tabindex="-1"
    aria-labelledby="modalEditarLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header">

                <div>

                    <h5
                        class="modal-title fw-semibold"
                        id="modalEditarLabel">

                        Modificar Franja Horaria

                    </h5>

                    <small class="text-muted">
                        Actualiza los datos de la franja seleccionada.
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar">
                </button>

            </div>


            <div class="modal-body p-4">

                <input
                    type="hidden"
                    id="edit_id_franja">


                <!-- Tipo -->
                <div class="mb-3">

                    <label
                        for="edit_tipo_franja"
                        class="form-label fw-semibold">

                        Tipo de Franja

                    </label>

                    <input
                        type="text"
                        id="edit_tipo_franja"
                        class="form-control"
                        required>

                </div>


                <!-- Inicio -->
                <div class="mb-3">

                    <label
                        for="edit_inicio_franja"
                        class="form-label fw-semibold">

                        Hora de Inicio

                    </label>

                    <input
                        type="time"
                        id="edit_inicio_franja"
                        class="form-control"
                        required>

                </div>


                <!-- Fin -->
                <div class="mb-3">

                    <label
                        for="edit_fin_franja"
                        class="form-label fw-semibold">

                        Hora de Fin

                    </label>

                    <input
                        type="time"
                        id="edit_fin_franja"
                        class="form-control"
                        required>

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
                    type="button"
                    class="btn btn-primary rounded-3"
                    onclick="actualizarFranja()">

                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    Guardar cambios

                </button>

            </div>

        </div>

    </div>

</div>


<!-- ============================================================= -->
<!-- JAVASCRIPT -->
<!-- ============================================================= -->

<script>

/*
 * ============================================================
 * Helper: escapar valores utilizados dentro del HTML generado
 * ============================================================
 */

function escapeForInlineJs(str) {

    if (str === null || typeof str === 'undefined') {
        return '';
    }

    return String(str)
        .replace(/\\/g, '\\\\')
        .replace(/'/g, "\\'")
        .replace(/"/g, "&quot;");

}


/*
 * ============================================================
 * Cargar tabla
 * ============================================================
 */

document.addEventListener("DOMContentLoaded", function () {

    if (typeof bootstrap === 'undefined') {

        console.warn(
            'Bootstrap JS no detectado. Verifique que bootstrap.bundle.min.js esté cargado en el layout.'
        );

    }

    cargarFranjas();

});


function cargarFranjas() {

    let data = new FormData();

    data.append("action", "listar");


    fetch("controladores/franja_horaria_controlador.php", {

        method: "POST",
        body: data

    })

    .then(r => r.json())

    .then(franjas => {

        let filas = "";

        const tbody = document.getElementById("tbodyFranja");


        /*
         * Validar respuesta
         */

        if (!Array.isArray(franjas)) {

            filas = `
                <tr>
                    <td colspan="5" class="text-center py-5 text-danger">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        Error al cargar las franjas horarias.
                    </td>
                </tr>
            `;

            tbody.innerHTML = filas;

            actualizarContador(0);

            return;
        }


        /*
         * Sin registros
         */

        if (franjas.length === 0) {

            filas = `
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">

                        <div class="mb-2">
                            <i class="fa-regular fa-clock fa-2x"></i>
                        </div>

                        <p class="mb-0">
                            No hay franjas horarias registradas.
                        </p>

                    </td>
                </tr>
            `;

            tbody.innerHTML = filas;

            actualizarContador(0);

            return;
        }


        /*
         * Generar filas
         */

        franjas.forEach(f => {

            const tipoEsc = escapeForInlineJs(f.tipo_franja);
            const inicioEsc = escapeForInlineJs(f.inicio_franja);
            const finEsc = escapeForInlineJs(f.fin_franja);


            filas += `

                <tr>

                    <!-- ID -->
                    <td class="fw-semibold text-muted">
                        ${f.id_franja}
                    </td>


                    <!-- Tipo -->
                    <td>

                        <div class="d-flex align-items-center gap-2">

                            <div
                                class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center"
                                style="width: 38px; height: 38px;">

                                <i class="fa-regular fa-clock"></i>

                            </div>

                            <span class="fw-semibold">
                                ${f.tipo_franja}
                            </span>

                        </div>

                    </td>


                    <!-- Inicio -->
                    <td>

                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">

                            <i class="fa-solid fa-play me-1"></i>

                            ${formatearHora(f.inicio_franja)}

                        </span>

                    </td>


                    <!-- Fin -->
                    <td>

                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">

                            <i class="fa-solid fa-stop me-1"></i>

                            ${formatearHora(f.fin_franja)}

                        </span>

                    </td>


                    <!-- Acciones -->
                    <td class="text-center">

                        <div class="d-flex justify-content-center gap-2">


                            <!-- Editar -->
                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm rounded-3"
                                onclick="abrirModalEditar(
                                    ${f.id_franja},
                                    '${tipoEsc}',
                                    '${inicioEsc}',
                                    '${finEsc}'
                                )"
                                title="Editar">

                                <i class="fa-solid fa-pen"></i>

                            </button>


                            <!-- Eliminar -->
                            <button
                                type="button"
                                class="btn btn-outline-danger btn-sm rounded-3"
                                onclick="eliminarFranja(${f.id_franja})"
                                title="Eliminar">

                                <i class="fa-solid fa-trash"></i>

                            </button>

                        </div>

                    </td>

                </tr>

            `;

        });


        tbody.innerHTML = filas;

        actualizarContador(franjas.length);


        /*
         * Aplicar buscador nuevamente
         */

        configurarBuscador();

    })

    .catch(err => {

        console.error(err);

        document.getElementById("tbodyFranja").innerHTML = `

            <tr>

                <td colspan="5" class="text-center py-5 text-danger">

                    <i class="fa-solid fa-triangle-exclamation me-2"></i>

                    Error de conexión al cargar las franjas horarias.

                </td>

            </tr>

        `;

        actualizarContador(0);

    });

}


/*
 * ============================================================
 * Formatear hora
 * ============================================================
 */

function formatearHora(hora) {

    if (!hora) {
        return '';
    }

    return String(hora).slice(0, 5);

}


/*
 * ============================================================
 * Actualizar contador
 * ============================================================
 */

function actualizarContador(cantidad) {

    const contador = document.getElementById("contadorFranjas");

    if (contador) {

        contador.textContent =
            cantidad + (cantidad === 1 ? " registrada" : " registradas");

    }

}


/*
 * ============================================================
 * Buscador
 * ============================================================
 */

function configurarBuscador() {

    const buscador = document.getElementById("buscarFranja");

    const filas = document.querySelectorAll("#tbodyFranja tr");


    if (!buscador) {
        return;
    }


    /*
     * Evitar registrar múltiples eventos
     */

    if (buscador.dataset.configurado === "true") {
        return;
    }

    buscador.dataset.configurado = "true";


    buscador.addEventListener("keyup", function () {

        const texto = this.value.toLowerCase().trim();

        filas.forEach(function (fila) {

            const contenido =
                fila.textContent.toLowerCase();

            if (contenido.includes(texto)) {

                fila.style.display = "";

            } else {

                fila.style.display = "none";

            }

        });

    });

}


/*
 * ============================================================
 * Crear franja
 * ============================================================
 */

function guardarFranja() {

    let tipo =
        document.getElementById("tipo_franja").value.trim();

    let inicio =
        document.getElementById("inicio_franja").value;

    let fin =
        document.getElementById("fin_franja").value;


    /*
     * Validaciones
     */

    if (!tipo) {

        Swal.fire(
            "Atención",
            "Ingrese el tipo de franja",
            "warning"
        );

        return;

    }


    if (!inicio || !fin) {

        Swal.fire(
            "Atención",
            "Ingrese inicio y fin",
            "warning"
        );

        return;

    }


    if (inicio === fin) {

        Swal.fire(
            "Error",
            "La franja no puede durar 0 minutos",
            "error"
        );

        return;

    }


    /*
     * Preparar AJAX
     */

    let data = new FormData();

    data.append("action", "insertar");
    data.append("tipo_franja", tipo);
    data.append("inicio_franja", inicio);
    data.append("fin_franja", fin);


    fetch("controladores/franja_horaria_controlador.php", {

        method: "POST",
        body: data

    })

    .then(r => r.json())

    .then(resp => {

        if (resp.status === "ok") {


            Swal.fire({

                title: "Guardado",

                text: "Franja creada correctamente",

                icon: "success",

                timer: 1200,

                showConfirmButton: false

            });


            /*
             * Limpiar formulario
             */

            document.getElementById("formFranja").reset();


            /*
             * Cerrar offcanvas
             */

            const offcanvasEl =
                document.getElementById("offcanvasNuevaFranja");

            const offcanvas =
                bootstrap.Offcanvas.getInstance(offcanvasEl);

            if (offcanvas) {
                offcanvas.hide();
            }


            /*
             * Recargar tabla
             */

            cargarFranjas();

        }

        else {

            Swal.fire(
                "Error",
                resp.message || "Error al guardar",
                "error"
            );

        }

    })

    .catch(err => {

        console.error(err);

        Swal.fire(
            "Error",
            "Error de conexión",
            "error"
        );

    });

}


/*
 * ============================================================
 * Abrir modal editar
 * ============================================================
 */

function abrirModalEditar(id, tipo, inicio, fin) {

    document.getElementById("edit_id_franja").value = id;

    document.getElementById("edit_tipo_franja").value = tipo;

    document.getElementById("edit_inicio_franja").value =
        (inicio || '').slice(0, 5);

    document.getElementById("edit_fin_franja").value =
        (fin || '').slice(0, 5);


    const modalEl =
        document.getElementById("modalEditar");

    const modal =
        bootstrap.Modal.getOrCreateInstance(modalEl);

    modal.show();

}


/*
 * ============================================================
 * Actualizar franja
 * ============================================================
 */

function actualizarFranja() {

    let id =
        document.getElementById("edit_id_franja").value;

    let tipo =
        document.getElementById("edit_tipo_franja").value.trim();

    let inicio =
        document.getElementById("edit_inicio_franja").value;

    let fin =
        document.getElementById("edit_fin_franja").value;


    /*
     * Validaciones
     */

    if (!tipo) {

        Swal.fire(
            "Atención",
            "Ingrese el tipo de franja",
            "warning"
        );

        return;

    }


    if (!inicio || !fin) {

        Swal.fire(
            "Atención",
            "Ingrese inicio y fin",
            "warning"
        );

        return;

    }


    if (inicio === fin) {

        Swal.fire(
            "Error",
            "La franja no puede durar 0 minutos",
            "error"
        );

        return;

    }


    /*
     * Preparar AJAX
     */

    let data = new FormData();

    data.append("action", "actualizar");
    data.append("id_franja", id);
    data.append("tipo_franja", tipo);
    data.append("inicio_franja", inicio);
    data.append("fin_franja", fin);


    fetch("controladores/franja_horaria_controlador.php", {

        method: "POST",
        body: data

    })

    .then(r => r.json())

    .then(resp => {

        if (resp.status === "ok") {


            Swal.fire({

                title: "Actualizado",

                text: "Franja modificada correctamente",

                icon: "success",

                timer: 1200,

                showConfirmButton: false

            });


            /*
             * Cerrar modal
             */

            const modalEl =
                document.getElementById("modalEditar");

            const modal =
                bootstrap.Modal.getInstance(modalEl);

            if (modal) {
                modal.hide();
            }


            /*
             * Recargar tabla
             */

            cargarFranjas();

        }

        else {

            Swal.fire(
                "Error",
                resp.message || "Error al actualizar",
                "error"
            );

        }

    })

    .catch(err => {

        console.error(err);

        Swal.fire(
            "Error",
            "Error de conexión",
            "error"
        );

    });

}


/*
 * ============================================================
 * Eliminar franja
 * ============================================================
 */

function eliminarFranja(id) {

    Swal.fire({

        title: "¿Eliminar franja horaria?",

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


        let data = new FormData();

        data.append("action", "eliminar");

        data.append("id_franja", id);


        fetch("controladores/franja_horaria_controlador.php", {

            method: "POST",

            body: data

        })

        .then(r => r.json())

        .then(resp => {

            if (resp.status === "ok") {


                Swal.fire({

                    title: "Eliminado",

                    text: "Franja eliminada con éxito",

                    icon: "success",

                    timer: 1200,

                    showConfirmButton: false

                });


                cargarFranjas();

            }

            else {

                Swal.fire(
                    "Error",
                    resp.message || "Error al eliminar",
                    "error"
                );

            }

        })

        .catch(err => {

            console.error(err);

            Swal.fire(
                "Error",
                "Error de conexión",
                "error"
            );

        });

    });

}

</script>