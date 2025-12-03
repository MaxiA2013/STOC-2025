<?php /*
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

<div
 class="container-fluid">

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
*/


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Turnos</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<?php
$colores = ["purple", "red", "green"];
$i = 0;
$porpag = 20;
$offset = 0;
require_once "modelos/turno.php";
require_once "controladores/turno/info_turno_controlador.php";
$tur = new Turno();
$listaTurno = $tur->consultarTurnosDisponiblesPaginado($i, $porpag);

?>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-content { flex-grow: 1; padding: 40px; }
    .cards-container { display: flex; flex-wrap: wrap; gap: 20px; }

    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
      width: 300px;
      transition: 0.3s ease;
    }
    .card:hover { transform: translateY(-5px); }

    .day { font-size: 32px; font-weight: 700; }
    .month { font-size: 16px; font-weight: 600; }

    .footer-line { height: 5px; border-radius: 0 0 10px 10px; }

    .purple .day, .purple .month, .purple .title, .purple .info { color: rgb(95, 163, 240); }
    .purple .footer-line { background-color: rgb(95, 163, 240); }

    .red .day, .red .month, .red .title, .red .info { color: rgb(44, 92, 248); }
    .red .footer-line { background-color: rgb(44, 92, 248); }

    .green .day, .green .month, .green .title, .green .info { color: rgb(3, 14, 179); }
    .green .footer-line { background-color: rgb(3, 14, 179); }
  </style>
</head>
<body>

<div class="container py-4">
    <h2 class="mb-4">Turnos disponibles</h2>

    <div class="cards-container">

        <?php foreach ($listaTurno as $t):
            $color = $colores[$i % 3];
            $i++;

            $fecha = date("d", strtotime($t['fecha_hora']));
            $mes = date("M", strtotime($t['fecha_hora']));
            $hora = date("H:i", strtotime($t['fecha_hora']));
            $horaFin = date("H:i", strtotime($t['fecha_hora'] . " + {$t['minutos_turnos']} minutes"));
        ?>

        <div class="card <?= $color ?>">
            <div class="card-header d-flex justify-content-between">
                <div class="date">
                    <div class="day"><?= $fecha ?></div>
                    <div class="month"><?= $mes ?></div>
                </div>
            </div>

            <div class="card-body">
                <div class="title"><?= $t['nombre'] . " " . $t['apellido'] ?></div>
                <div class="info"><i class="far fa-clock"></i> <?= $hora ?> - <?= $horaFin ?></div>
                <div class="info"><i class="fas fa-arrow-right"></i>
                    <a href="index.php?page=info_turnos&id=<?= $t['id_turnos'] ?>">Ver más</a>
                </div>
            </div>

            <div class="footer-line"></div>
        </div>

        <?php endforeach; ?>

    </div>

    <!-- PAGINADO -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                <li class="page-item <?= ($p == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?page=turnos&pagina=<?= $p ?>">
                        <?= $p ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>

</body>
</html>

