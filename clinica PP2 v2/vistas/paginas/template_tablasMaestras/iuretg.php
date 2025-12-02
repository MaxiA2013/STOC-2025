<?php
require_once "modelos/turno.php";
require_once "modelos/agenda.php";
require_once "modelos/agenda_turno.php";

$turnoObj = new Turno();
$lista_turnos = $turnoObj->consultarVariosTurnos();

$agendaObj = new Agenda();
$doctores = $agendaObj->obtenerDoctores();

$agendaTurnoObj = new AgendaTurno();
$turnos_pacientes = $agendaTurnoObj->listar(); // obtiene todos los turnos asignados/relacionados a pacientes
?>

<link href="assets/css/select2.min.css" rel="stylesheet"/>

<style>
    .form-box { border:1px solid #e3e3e3; padding:18px; border-radius:8px; }
    .select2-container { width:100% !important; }
</style>

<div class="container-fluid">

    <h4 class="mt-3 mb-4">Gestión de Turnos</h4>

    <!-- ==============================
        OPCIÓN / MODO DE OPERACIÓN
    =============================== -->
    <div class="card mb-4">
        <div class="card-body">
            <label for="modo_operacion"><strong>Modo de operación:</strong></label>
            <select id="modo_operacion" class="form-select w-auto">
                <option value="agregar">Agregar turno (manual)</option>
                <option value="asignar">Asignar turno (elegir horario disponible)</option>
            </select>
        </div>
    </div>

    <!-- =====================================================================
        FORMULARIO A – REGISTRAR TURNO MANUAL  (Tabla TURNOS)
    ====================================================================== -->
    <div id="form_agregar" class="card mb-4">
        <div class="card-header bg-primary text-white">Registrar turno manual</div>
        <div class="card-body">

            <form method="POST" action="controladores/turno/turno_controlador.php?op=registrar">

                <div class="row mb-3">

                    <div class="col-md-4">
                        <label class="form-label">Doctor</label>
                        <select name="doctor_id" id="doctor_id_manual" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Agenda</label>
                        <select name="agenda_id" id="agenda_id_manual" class="form-select" required>
                            <option value="">Seleccione un doctor primero</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha del turno</label>
                        <input type="date" name="fecha_turno" class="form-control" required>
                    </div>

                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Hora desde</label>
                        <input type="time" name="hora_desde" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Hora hasta</label>
                        <input type="time" name="hora_hasta" class="form-control" required>
                    </div>

                </div>

                <button class="btn btn-success" type="submit">Registrar turno</button>

            </form>

        </div>
    </div>

    <!-- ======================================================================
        FORMULARIO B – ASIGNAR TURNO A PACIENTE (Tabla AGENDA_TURNO)
    ======================================================================= -->
    <div id="form_asignar" class="card mb-4" style="display:none;">
        <div class="card-header bg-success text-white">Asignar turno a paciente</div>
        <div class="card-body">

            <form method="POST" action="controladores/turno/agenda_turno_controlador.php?op=asignar">

                <div class="row mb-3">

                    <div class="col-md-4">
                        <label class="form-label">Doctor</label>
                        <select name="doctor_id" id="doctor_id_asignar" class="form-select" required>
                            <option value="">Seleccione</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Agenda</label>
                        <select name="agenda_id" id="agenda_id_asignar" class="form-select" required>
                            <option value="">Seleccione un doctor primero</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Paciente</label>
                        <select name="paciente_id" id="paciente_id" class="form-select" required>
                            <option value="">Cargando...</option>
                        </select>
                    </div>

                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Horarios disponibles</label>
                        <select name="turno_id" id="turno_disponible" class="form-select" required>
                            <option value="">Seleccione agenda</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <select name="estado_id" class="form-select" required>
                            <option value="1">Pendiente</option>
                            <option value="2">Confirmado</option>
                            <option value="3">Cancelado</option>
                        </select>
                    </div>

                </div>

                <button class="btn btn-primary" type="submit">Asignar turno</button>

            </form>

        </div>
    </div>


    <!-- ==========================================
        TABLA DE TURNOS DISPONIBLES (TURNOS)
    =========================================== -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">Turnos disponibles (manuales)</div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="tabla_turnos">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Agenda</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Doctor</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="tbody_turnos">
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ======================================================
    JAVASCRIPT – CARGA DE DATOS Y FUNCIONES
====================================================== -->
<script>
// =====================================================
// CAMBIAR MODO
// =====================================================
document.getElementById("modo_operacion").addEventListener("change", function() {
    const modo = this.value;

    document.getElementById("form_agregar").style.display = (modo === "agregar") ? "block" : "none";
    document.getElementById("form_asignar").style.display = (modo === "asignar") ? "block" : "none";
});

// =====================================================
// CARGAR DOCTORES EN AMBOS FORMULARIOS
// =====================================================
function cargarDoctores() {
    fetch("controladores/turno/ajax_get_pacientes.php?doctores=1") 
    .then(r => r.json())
    .then(data => {
        document.querySelectorAll("#doctor_id_manual, #doctor_id_asignar").forEach(sel => {
            sel.innerHTML = '<option value="">Seleccione</option>';
            data.data.forEach(d => {
                sel.innerHTML += `<option value="${d.id_doctor}">${d.nombre}</option>`;
            });
        });
    });
}
cargarDoctores();

// =====================================================
// CARGAR AGENDAS EN AMBOS FORMULARIOS
// =====================================================
function cargarAgendas(doctorId, select) {
    fetch("controladores/turno/ajax_get_agenda.php?doctor_id=" + doctorId)
    .then(r => r.json())
    .then(data => {
        select.innerHTML = '<option value="">Seleccione agenda</option>';
        data.data.forEach(a => {
            select.innerHTML += `
                <option value="${a.id_agenda}">
                    ${a.id_agenda} - (${a.fecha_desde} a ${a.fecha_hasta})
                </option>
            `;
        });
    });
}

document.querySelector("#doctor_id_manual").addEventListener("change", function() {
    cargarAgendas(this.value, document.getElementById("agenda_id_manual"));
});
document.querySelector("#doctor_id_asignar").addEventListener("change", function() {
    cargarAgendas(this.value, document.getElementById("agenda_id_asignar"));
});

// =====================================================
// CARGAR PACIENTES
// =====================================================
function cargarPacientes() {
    fetch("controladores/turno/ajax_get_pacientes.php")
    .then(r => r.json())
    .then(data => {
        const sel = document.getElementById("paciente_id");
        sel.innerHTML = '<option value="">Seleccione</option>';
        data.data.forEach(p => {
            sel.innerHTML += `<option value="${p.id_paciente}">${p.nombre}</option>`;
        });
    });
}
cargarPacientes();

// =====================================================
// CARGAR TURNOS DISPONIBLES POR AGENDA
// =====================================================
document.querySelector("#agenda_id_asignar").addEventListener("change", function() {
    const idAgenda = this.value;
    const sel = document.getElementById("turno_disponible");

    fetch("controladores/turno/ajax_get_turnos_por_agenda.php?id_agenda=" + idAgenda)
    .then(r => r.json())
    .then(data => {
        sel.innerHTML = '<option value="">Seleccione</option>';
        data.data.forEach(t => {
            sel.innerHTML += `<option value="${t.id_turno}">
                ${t.hora_desde} - ${t.hora_hasta}
            </option>`;
        });
    });
});

// =====================================================
// CARGAR TABLA DE TURNOS
// =====================================================
function cargarTablaTurnos() {
    fetch("controladores/turno/ajax_get_turnos_por_agenda.php?all=1")
    .then(r => r.json())
    .then(data => {
        let html = "";
        data.data.forEach(t => {
            html += `
                <tr>
                    <td>${t.id_turno}</td>
                    <td>${t.agenda_id}</td>
                    <td>${t.fecha_turno}</td>
                    <td>${t.hora_desde} - ${t.hora_hasta}</td>
                    <td>${t.doctor}</td>
                    <td>
                        <a href="controladores/turno_controlador.php?op=editar&id=${t.id_turno}" class="btn btn-sm btn-warning">Editar</a>
                        <a href="controladores/turno_controlador.php?op=eliminar&id=${t.id_turno}" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
            `;
        });
        document.getElementById("tbody_turnos").innerHTML = html;
    });
}
cargarTablaTurnos();

</script>

