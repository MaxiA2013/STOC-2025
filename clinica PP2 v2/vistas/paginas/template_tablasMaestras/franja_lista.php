<?php
include_once "modelos/franja_horaria.php";

$franja = new Franja();
$lista_franja = $franja->consultarVariasFranjas();
?>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Bootstrap Bundle JS (incluye Popper) - NECESARIO para modales -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4">

    <h3 class="mb-3">Gestión de Franjas Horarias</h3>

    <!-- Formulario crear -->
    <form id="formFranja" onsubmit="return false;">
        <div class="row g-2">
            <div class="col-md-4">
                <label>Tipo de franja:</label>
                <input type="text" class="form-control" id="tipo_franja">
            </div>

            <div class="col-md-4">
                <label>Inicio:</label>
                <input type="time" class="form-control" id="inicio_franja">
            </div>

            <div class="col-md-4">
                <label>Fin:</label>
                <input type="time" class="form-control" id="fin_franja">
            </div>
        </div>

        <button type="button" class="btn btn-primary mt-3" onclick="guardarFranja()">Guardar</button>
    </form>

    <hr>

    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbodyFranja"></tbody>
    </table>
</div>


<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Editar Franja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit_id_franja">

                <label>Tipo:</label>
                <input type="text" id="edit_tipo_franja" class="form-control mb-2">

                <label>Inicio:</label>
                <input type="time" id="edit_inicio_franja" class="form-control mb-2">

                <label>Fin:</label>
                <input type="time" id="edit_fin_franja" class="form-control mb-2">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="actualizarFranja()">Actualizar</button>
            </div>

        </div>
    </div>
</div>


<script>
// =============================
//   Helper: escapar comillas para insertar en 'onclick' de forma segura
//   reemplaza ' por \', " por &quot; para no romper el HTML/JS inline
// =============================
function escapeForInlineJs(str) {
    if (str === null || typeof str === 'undefined') return '';
    return String(str).replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, "&quot;");
}

// =============================
//   Cargar tabla
// =============================
document.addEventListener("DOMContentLoaded", function() {
    // Asegurarnos que bootstrap esté disponible
    if (typeof bootstrap === 'undefined') {
        console.warn('Bootstrap JS no detectado. Asegúrate de incluir bootstrap.bundle.min.js');
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

        if (!Array.isArray(franjas)) {
            // Si algo salió mal, mostrar mensaje simple
            filas = '<tr><td colspan="5">Error al cargar datos</td></tr>';
            document.getElementById("tbodyFranja").innerHTML = filas;
            return;
        }

        if (franjas.length === 0) {
            filas = '<tr><td colspan="5">No hay franjas registradas</td></tr>';
            document.getElementById("tbodyFranja").innerHTML = filas;
            return;
        }

        franjas.forEach(f => {
            // escapar valores para no romper el onclick
            const tipoEsc = escapeForInlineJs(f.tipo_franja);
            const inicioEsc = escapeForInlineJs(f.inicio_franja);
            const finEsc = escapeForInlineJs(f.fin_franja);

            filas += `
                <tr>
                    <td>${f.id_franja}</td>
                    <td>${f.tipo_franja}</td>
                    <td>${f.inicio_franja}</td>
                    <td>${f.fin_franja}</td>
                    <td>
                        <button class="btn btn-warning btn-sm"
                            onclick="abrirModalEditar(${f.id_franja}, '${tipoEsc}', '${inicioEsc}', '${finEsc}')">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarFranja(${f.id_franja})">Eliminar</button>
                    </td>
                </tr>
            `;
        });

        document.getElementById("tbodyFranja").innerHTML = filas;
    })
    .catch(err => {
        console.error(err);
        document.getElementById("tbodyFranja").innerHTML = '<tr><td colspan="5">Error de conexión</td></tr>';
    });
}



// =============================
//   Crear franja
// =============================
function guardarFranja() {
    let tipo = document.getElementById("tipo_franja").value.trim();
    let inicio = document.getElementById("inicio_franja").value;
    let fin = document.getElementById("fin_franja").value;

    if (!tipo) {
        Swal.fire("Atención", "Ingrese el tipo de franja", "warning");
        return;
    }

    if (!inicio || !fin) {
        Swal.fire("Atención", "Ingrese inicio y fin", "warning");
        return;
    }

    if (inicio === fin) {
        Swal.fire("Error", "La franja no puede durar 0 minutos", "error");
        return;
    }

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

            document.getElementById("formFranja").reset();
            cargarFranjas();
        }
        else {
            Swal.fire("Error", resp.message || "Error al guardar", "error");
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire("Error", "Error de conexión", "error");
    });
}



// =============================
//   Abrir modal editar
// =============================
// inicializamos modal cuando se necesita (bootstrap debe estar cargado)
function abrirModalEditar(id, tipo, inicio, fin) {
    document.getElementById("edit_id_franja").value = id;
    document.getElementById("edit_tipo_franja").value = tipo;
    // input type=time espera hh:mm -> cortar segundos si vienen
    document.getElementById("edit_inicio_franja").value = (inicio || '').slice(0,5);
    document.getElementById("edit_fin_franja").value = (fin || '').slice(0,5);

    const modalEl = document.getElementById('modalEditar');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}



// =============================
//   Actualizar franja
// =============================
function actualizarFranja() {

    let id = document.getElementById("edit_id_franja").value;
    let tipo = document.getElementById("edit_tipo_franja").value.trim();
    let inicio = document.getElementById("edit_inicio_franja").value;
    let fin = document.getElementById("edit_fin_franja").value;

    if (!tipo) {
        Swal.fire("Atención", "Ingrese el tipo de franja", "warning");
        return;
    }

    if (!inicio || !fin) {
        Swal.fire("Atención", "Ingrese inicio y fin", "warning");
        return;
    }

    if (inicio === fin) {
        Swal.fire("Error", "La franja no puede durar 0 minutos", "error");
        return;
    }

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

            // ocultar modal
            const modalEl = document.getElementById('modalEditar');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            cargarFranjas();
        }
        else {
            Swal.fire("Error", resp.message || "Error al actualizar", "error");
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire("Error", "Error de conexión", "error");
    });
}



// =============================
//   Eliminar franja
// =============================
function eliminarFranja(id) {

    Swal.fire({
        title: "¿Eliminar franja?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then(result => {

        if (!result.isConfirmed) return;

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
            } else {
                Swal.fire("Error", resp.message || "Error al eliminar", "error");
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire("Error", "Error de conexión", "error");
        });
    });
}
</script>
