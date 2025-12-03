<?php
<<<<<<< HEAD
// vistas/paginas/turno_lista.php
require_once "modelos/turno.php";
require_once "modelos/agenda.php";
=======
require_once "modelos/turno.php";
require_once "modelos/paciente.php";
require_once "modelos/agenda.php";
require_once "modelos/agenda_turno.php";
>>>>>>> origin/mi-ramita

$turnoObj = new Turno();
$lista_turnos = $turnoObj->consultarVariosTurnos();

$agendaObj = new Agenda();
$doctores = $agendaObj->obtenerDoctores();
<<<<<<< HEAD
?>

<div class="py-5 container">
    <div class="row">
        <div class="col">
            <h2>Turnos</h2>
            <p>Agregar un nuevo turno</p>
        </div>
    </div>
    <div class="row">
        <!-- Formulario de alta -->
        <div class="col">
            <form id="formAgregarTurno" method="post" action="controladores/turno_controlador.php">
                <input type="hidden" name="action" value="insertar">

                <div class="mb-3">
                    <label class="form-label">Doctor</label>
                    <select id="doctorSelect" class="form-control doctor-select" name="doctor_id" required>
                        <option value="">Seleccione un doctor</option>
=======

$pacien = new Paciente();
$lista_paciente = $pacien->listarPacientes();

$agendaTurnoObj = new AgendaTurno();
$turnos_pacientes = $agendaTurnoObj->listar(); // obtiene todos los turnos asignados/relacionados a pacientes
?>
<style>
    .form-box {
        border: 1px solid #e3e3e3;
        padding: 18px;
        border-radius: 8px;
    }

    .select2-container {
        width: 100% !important;
    }
</style>

<div class="py-5 container">
    <div class="row mb-3">
        <div class="col">
            <h2>Turnos</h2>
            <p>Agregar o asignar un turno</p>
        </div>
    </div>

    <div class="row">
        <form id="formAgregarTurno" class="form-box">
            <input type="hidden" name="action" value="insertar">

            <div class="row g-3">
                <!-- Doctor -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Doctor</label>
                    <select id="doctorSelect" name="doctor_id" class="form-select select2-doctor" required>
                        <option value="">Seleccione</option>
>>>>>>> origin/mi-ramita
                        <?php foreach ($doctores as $doc): ?>
                            <option value="<?= $doc['id_doctor'] ?>">
                                <?= htmlspecialchars($doc['nombre_persona'] . " (" . $doc['nombre_usuario'] . ")") ?>
                            </option>
<<<<<<< HEAD
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Agenda</label>
                    <select name="agenda_id_agenda" id="agendaSelect" class="form-control agenda-select" required>
=======
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Agenda -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Agenda</label>
                    <select id="agendaSelect" name="agenda_id_agenda" class="form-select" required>
>>>>>>> origin/mi-ramita
                        <option value="">Seleccione primero un doctor</option>
                    </select>
                </div>

<<<<<<< HEAD
                <div class="mb-3">
                    <label class="form-label">Minutos del Turno</label>
                    <input type="number" class="form-control" name="minutos_turnos" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha y Hora</label>
                    <input type="datetime-local" class="form-control" name="fecha_hora" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Disponible</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disponible" value="0" checked>
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="disponible" value="1">
                        <label class="form-check-label">Sí</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>

        <!-- Tabla -->
        <div class="col">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Minutos</th>
                        <th>Fecha y Hora</th>
                        <th>Disponible</th>
                        <th>Agenda (Fecha)</th>
                        <th>Doctor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($lista_turnos as $row): ?>
                    <tr>
                        <td><?= $row['id_turnos'] ?></td>
                        <td><?= $row['minutos_turnos'] ?></td>
                        <td><?= $row['fecha_hora'] ?></td>
                        <td><?= $row['disponible'] ? 'Sí' : 'No' ?></td>
                        <td><?= htmlspecialchars($row['agenda_id'] . " - " . $row['fecha_agenda']) ?></td>
                        <td><?= htmlspecialchars($row['nombre_doctor'] . " " . $row['apellido']) ?></td>
                        <td>
                            <!-- Eliminar -->
                            <form action="controladores/turno_controlador.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">
                                <input type="hidden" name="action" value="eliminacion">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                            <!-- Botón Editar: paso datos por data- attributes -->
                            <button type="button"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?= $row['id_turnos'] ?>"
                                    data-turno-id="<?= $row['id_turnos'] ?>"
                                    data-doctor-id="<?= $row['doctor_id'] ?? '' ?>"
                                    data-agenda-id="<?= $row['agenda_id'] ?>"
                                    data-fecha-hora="<?= htmlspecialchars($row['fecha_hora']) ?>">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal edición (único por turno, con selects dinámicos) -->
                    <div class="modal fade" id="modalEditar<?= $row['id_turnos'] ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form class="formEditarTurno" method="post" action="controladores/turno_controlador.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modificar Turno #<?= $row['id_turnos'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="actualizacion">
                                        <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">

                                        <div class="mb-3">
                                            <label class="form-label">Doctor</label>
                                            <select class="form-control doctor-select" name="doctor_id_modal" required>
                                                <option value="">Seleccione un doctor</option>
                                                <?php foreach ($doctores as $doc): ?>
                                                    <option value="<?= $doc['id_doctor'] ?>" <?= (isset($row['doctor_id']) && $row['doctor_id']==$doc['id_doctor']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($doc['nombre_persona'] . " (" . $doc['nombre_usuario'] . ")") ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Agenda</label>
                                            <select class="form-control agenda-select" name="agenda_id_agenda" required>
                                                <!-- se llenará por AJAX; colocamos la opción actual como fallback -->
                                                <option value="<?= $row['agenda_id'] ?>"><?= $row['agenda_id'] . " - " . $row['fecha_agenda'] ?></option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Minutos del Turno</label>
                                            <input type="number" class="form-control" name="minutos_turnos" value="<?= $row['minutos_turnos'] ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Fecha y Hora</label>
                                            <input type="datetime-local" class="form-control" name="fecha_hora" value="<?= date('Y-m-d\TH:i', strtotime($row['fecha_hora'])) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Disponible</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="disponible" value="0" <?= $row['disponible'] == 0 ? 'checked' : '' ?>>
                                                <label class="form-check-label">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="disponible" value="1" <?= $row['disponible'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label">Sí</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endforeach ?>
                </tbody>
            </table>
=======
                <!-- Modo -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Modo</label>
                    <div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="modo_turno" id="modo_agregar" value="agregar" checked>
                            <label class="form-check-label" for="modo_agregar">Agregar turno (manual)</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="modo_turno" id="modo_asignar" value="asignar">
                            <label class="form-check-label" for="modo_asignar">Asignar turno (disponibles)</label>
                        </div>
                    </div>
                </div>

                <!-- Minutos -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Minutos del Turno</label>
                    <input type="number" class="form-control" name="minutos_turnos" id="minutos_turnos" min="1" required>
                </div>

                <!-- Fecha y hora manual -->
                <div class="col-12 col-md-6 col-lg-3" id="div_datetime_input">
                    <label class="form-label">Fecha y Hora (manual)</label>
                    <input type="datetime-local" class="form-control" name="fecha_hora" id="input_fecha_hora">
                </div>

                <!--  Fecha y hora Turnos disponibles -->
                <div class="col-12 col-md-6 col-lg-3" id="div_select_turnos">
                    <label class="form-label">Turnos disponibles (seleccione)</label>
                    <select id="select_turnos_disponibles" class="form-select select2-turnos" name="turno_existente_id">
                        <option value="">Seleccione una agenda primero</option>
                    </select>
                    <small class="form-text text-muted">Si elige un turno, se usará su fecha/hora.</small>
                </div>

                <!-- Paciente -->
                <div class="col-12 col-md-6 col-lg-3" id="div_select_paciente">
                    <label class="form-label">Paciente</label>
                    <select name="paciente_id" id="select_pacientes" class="form-select select2-paciente">
                        <option value="">Seleccione a un paciente</option>
                    </select>
                    <small class="form-text text-muted">Seleccione un paciente para asignarle un turno.</small>
                </div>

                <!-- Botón -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100">
                        Agregar / Asignar
                    </button>
                </div>

            </div>
        </form>

        <!-- PANEL de tablas -->
        <div class="row">
            <div class="col-12 mt-4 mt-md-0">
                <ul class="nav nav-tabs mb-3" id="turnoTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-turnos" data-bs-toggle="tab" data-bs-target="#panel-turnos1" type="button" role="tab">
                            Turnos Disponibles
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-turnos-pacientes" data-bs-toggle="tab" data-bs-target="#panel-turnos-pacientes2" type="button" role="tab">
                            Turnos Asignados a Pacientes
                        </button>
                    </li>
                </ul>

                <div class="tab-content" style="min-height:200px;">
                    <!-- PANEL 1 -->
                    <!-- PANEL 1 -->
                    <!-- PANEL 1 -->
                    <!-- PANEL 1 -->
                    <!-- PANEL 1 -->
                    <div class="tab-pane fade show active" id="panel-turnos1" role="tabpanel">
                        <table class="table table-striped" id="panel-turnos">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Minutos</th>
                                    <th>Fecha y Hora</th>
                                    <th>Disponible</th>
                                    <th>Agenda (Fecha)</th>
                                    <th>Doctor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_turnos as $row): ?>
                                    <tr>
                                        <td><?= $row['id_turnos'] ?></td>
                                        <td><?= $row['minutos_turnos'] ?></td>
                                        <td><?= $row['fecha_hora'] ?></td>
                                        <td><?= $row['disponible'] ? 'Sí' : 'No' ?></td>
                                        <td><?= htmlspecialchars($row['agenda_id'] . " - " . $row['fecha_agenda']) ?></td>
                                        <td><?= htmlspecialchars($row['nombre_doctor'] . " " . $row['apellido']) ?></td>
                                        <td class="d-flex gap-1">
                                            <!-- Eliminar -->
                                            <form action="controladores/turno/turno_controlador.php" method="post" style="display:inline;">
                                                <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">
                                                <input type="hidden" name="action" value="eliminacion">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                            <!-- Editar -->
                                            <button type="button"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditar<?= $row['id_turnos'] ?>"
                                                data-turno-id="<?= $row['id_turnos'] ?>"
                                                data-doctor-id="<?= $row['doctor_id'] ?? '' ?>"
                                                data-agenda-id="<?= $row['agenda_id'] ?>"
                                                data-fecha-hora="<?= htmlspecialchars($row['fecha_hora']) ?>">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal edición por turno -->
                                    <div class="modal fade" id="modalEditar<?= $row['id_turnos'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form class="formEditarTurno" method="post" action="controladores/turno/turno_controlador.php">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Modificar Turno #<?= $row['id_turnos'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="actualizacion">
                                                        <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label">Doctor</label>
                                                            <select class="form-control doctor-select modal-doctor-select" name="doctor_id_modal" required>
                                                                <option value="">Seleccione un doctor</option>
                                                                <?php foreach ($doctores as $doc): ?>
                                                                    <option value="<?= $doc['id_doctor'] ?>" <?= (isset($row['doctor_id']) && $row['doctor_id'] == $doc['id_doctor']) ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($doc['nombre_persona'] . " (" . $doc['nombre_usuario'] . ")") ?>
                                                                    </option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Agenda</label>
                                                            <select class="form-control agenda-select modal-agenda-select" name="agenda_id_agenda" required>
                                                                <option value="<?= $row['agenda_id'] ?>"><?= $row['agenda_id'] . " - " . $row['fecha_agenda'] ?></option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Minutos del Turno</label>
                                                            <input type="number" class="form-control" name="minutos_turnos" value="<?= $row['minutos_turnos'] ?>" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Fecha y Hora</label>
                                                            <input type="datetime-local" class="form-control" name="fecha_hora" value="<?= date('Y-m-d\TH:i', strtotime($row['fecha_hora'])) ?>" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Disponible</label><br>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="disponible" value="0" <?= $row['disponible'] == 0 ? 'checked' : '' ?>>
                                                                <label class="form-check-label">No</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="disponible" value="1" <?= $row['disponible'] == 1 ? 'checked' : '' ?>>
                                                                <label class="form-check-label">Sí</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PANEL 2: TURNOS ASIGNADOS A PACIENTES -->
                    <!-- PANEL 2: TURNOS ASIGNADOS A PACIENTES -->
                    <!-- PANEL 2: TURNOS ASIGNADOS A PACIENTES -->
                    <!-- PANEL 2: TURNOS ASIGNADOS A PACIENTES -->
                    <div class="tab-pane fade" id="panel-turnos-pacientes2" role="tabpanel">
                        <table class="table table-striped" id="panel-turnos-pacientes">
                            <thead>
                                <tr>
                                    <th>ID Agenda Turno</th>
                                    <th>Paciente</th>
                                    <th>Fecha y Hora</th>
                                    <th>Minutos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($turnos_pacientes as $t): ?>
                                    <tr>
                                        <td><?= $t['id_agenda_turno'] ?></td>
                                        <td><?= htmlspecialchars($t['paciente_nombre'] . " " . $t['paciente_apellido']) ?></td>
                                        <td><?= $t['fecha_hora'] ?></td>
                                        <td><?= $t['minutos_turnos'] ?></td>
                                        <td><?= htmlspecialchars($t['tipo_estado']) ?></td>

                                        <td class="d-flex gap-1">

                                            <!-- Botón eliminar -->
                                            <form action="controladores/turno/turno_controlador.php" method="post" style="display:inline;">
                                                <input type="hidden" name="action" value="eliminar">
                                                <input type="hidden" name="id_agenda_turno" value="<?= $t['id_agenda_turno'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                            <!-- Botón editar -->
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarAsignado<?= $t['id_agenda_turno'] ?>">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- MODAL PARA EDITAR TURNO ASIGNADO A PACIENTE -->
                                    <div class="modal fade" id="modalEditarAsignado<?= $t['id_agenda_turno'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="controladores/turno/turno_controlador.php">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Turno Asignado #<?= $t['id_agenda_turno'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="editar">
                                                        <input type="hidden" name="id_agenda_turno" value="<?= $t['id_agenda_turno'] ?>">

                                                        <!-- Paciente -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Paciente</label>
                                                            <select class="form-control select2-pacientes" name="paciente_id_paciente" required>
                                                                <option value="">Seleccione paciente</option>
                                                                <?php foreach ($pacientes as $p): ?>
                                                                    <option value="<?= $p['id_paciente'] ?>"
                                                                        <?= ($p['id_paciente'] == $t['paciente_id_paciente']) ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($p['nombre'] . " " . $p['apellido']) ?>
                                                                    </option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                        <!-- Fecha y hora del turno asignado -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Fecha y Hora</label>
                                                            <input type="datetime-local" name="fecha_hora" class="form-control"
                                                                value="<?= date('Y-m-d\TH:i', strtotime($t['fecha_hora'])) ?>" required>
                                                        </div>

                                                        <!-- Minutos -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Minutos</label>
                                                            <input type="number" name="minutos_turnos" class="form-control"
                                                                value="<?= $t['minutos_turnos'] ?>" required>
                                                        </div>

                                                        <!-- Estado -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Estado</label>
                                                            <select class="form-control" name="estados_id_estados" required>
                                                                <?php foreach ($estados as $e): ?>
                                                                    <option value="<?= $e['id_estados'] ?>"
                                                                        <?= ($e['id_estados'] == $t['estados_id_estados']) ? 'selected' : '' ?>>
                                                                        <?= htmlspecialchars($e['tipo_estado']) ?>
                                                                    </option>
                                                                <?php endforeach ?>
                                                            </select>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" class="btn btn-success">Guardar</button>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>


                </div> <!-- cierre tab-content -->
            </div>
>>>>>>> origin/mi-ramita
        </div>
    </div>
</div>

<<<<<<< HEAD
<!-- Scripts: jQuery (si lo usas), Bootstrap y JS propio -->
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>

<script>
// Cargar agendas para un doctor (uso fetch para controladores/get_agendas.php)
function cargarAgendasParaDoctor(doctorId, targetSelect, selectAgendaId = null) {
    targetSelect.innerHTML = '<option value="">Cargando...</option>';
    if (!doctorId) {
        targetSelect.innerHTML = '<option value="">Seleccione primero un doctor</option>';
        return;
    }

    // 👇 corrección: ruta correcta al archivo existente
    fetch('controladores/ajax_get_agenda.php?doctor_id=' + doctorId)
        .then(resp => resp.json())
        .then(data => {
            if (data.error) {
                console.error('Error en respuesta:', data.error);
                targetSelect.innerHTML = '<option value="">Error al cargar agendas</option>';
                return;
            }
            targetSelect.innerHTML = '<option value="">Seleccione una agenda</option>';
            data.forEach(function(a) {
                const opt = document.createElement('option');
                opt.value = a.id_agenda;
                opt.dataset.desde = a.hora_desde;
                opt.dataset.hasta = a.hora_hasta;
                opt.textContent = a.id_agenda + ' - ' + a.fecha_agenda;
                targetSelect.appendChild(opt);
            });
            if (selectAgendaId) {
                targetSelect.value = selectAgendaId;
            }
        })
        .catch(err => {
            console.error('Error cargando agendas:', err);
            targetSelect.innerHTML = '<option value="">Error al cargar agendas</option>';
        });
}

// ALTA: cuando el select de doctor cambie, cargar agendas en formulario principal
document.getElementById('doctorSelect').addEventListener('change', function() {
    const doctorId = this.value;
    const agendaSelect = document.getElementById('agendaSelect');
    cargarAgendasParaDoctor(doctorId, agendaSelect);
});

// VALIDACIÓN creación: comprobar fecha coincida con fecha_agenda y hora en rango
document.getElementById('formAgregarTurno').addEventListener('submit', function(e) {
    const agendaSelect = this.querySelector('.agenda-select');
    const fechaTurno = this.querySelector('input[name="fecha_hora"]').value; // 'YYYY-MM-DDTHH:MM'
    if (!agendaSelect.value) {
        alert('Seleccione una agenda.');
        e.preventDefault();
        return;
    }
    const selectedOption = agendaSelect.options[agendaSelect.selectedIndex];
    const fechaAgenda = selectedOption.text.split(' - ')[1]; // formato 'YYYY-MM-DD' (según get_agendas)
    const horaDesde = selectedOption.dataset.desde; // 'HH:MM'
    const horaHasta = selectedOption.dataset.hasta; // 'HH:MM'
    if (!fechaTurno) { e.preventDefault(); return; }

    // extraer fecha y hora de fechaTurno
    const parts = fechaTurno.split('T');
    const fecha = parts[0];
    const hora = parts[1].slice(0,5); // 'HH:MM'

    if (fecha !== fechaAgenda) {
        alert('La fecha del turno debe coincidir con la fecha de la agenda seleccionada ('+fechaAgenda+').');
        e.preventDefault();
        return;
    }
    if (hora < horaDesde || hora > horaHasta) {
        alert('La hora del turno debe estar entre ' + horaDesde + ' y ' + horaHasta + '.');
        e.preventDefault();
        return;
    }
});

// MODAL: cargar agendas cuando se abra cada modal y seleccionar la agenda actual
// Usamos el evento show.bs.modal y e.relatedTarget para obtener el botón que disparó el modal
document.querySelectorAll('.modal').forEach(function(modalEl) {
    modalEl.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button) return;
        const turnoId = button.getAttribute('data-turno-id');
        const doctorId = button.getAttribute('data-doctor-id');
        const agendaId = button.getAttribute('data-agenda-id');
        const fechaHora = button.getAttribute('data-fecha-hora');

        const modal = modalEl;
        // Encontrar selects dentro del modal
        const doctorSelect = modal.querySelector('.doctor-select');
        const agendaSelect = modal.querySelector('.agenda-select');
        const fechaInput = modal.querySelector('input[name="fecha_hora"]');

        // setear fecha/hora
        if (fechaHora && fechaInput) {
            // transformar a formato datetime-local
            const fh = new Date(fechaHora);
            const yyyy = fh.getFullYear();
            const mm = String(fh.getMonth()+1).padStart(2,'0');
            const dd = String(fh.getDate()).padStart(2,'0');
            const hh = String(fh.getHours()).padStart(2,'0');
            const mi = String(fh.getMinutes()).padStart(2,'0');
            fechaInput.value = `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
        }

        // setear doctor y cargar agendas; luego seleccionar agendaId
        if (doctorSelect) {
            doctorSelect.value = doctorId || '';
            // Llamar a la función para cargar agendas y pasar agendaId como selectAgendaId
            cargarAgendasParaDoctor(doctorSelect.value, agendaSelect, agendaId);
        }
    });

    // Validación en envío del formulario dentro del modal
    modalEl.addEventListener('submit', function(e) {
        // detecta si el submit viene de un formulario de edición
        const form = e.target;
        // sólo validar si es un formEditarTurno (por seguridad)
        if (!form.classList.contains('formEditarTurno')) return;
        const agendaSelect = form.querySelector('.agenda-select');
        const fechaTurno = form.querySelector('input[name="fecha_hora"]').value;
        if (!agendaSelect.value) {
            alert('Seleccione una agenda.');
            e.preventDefault();
            return;
        }
        const selectedOption = agendaSelect.options[agendaSelect.selectedIndex];
        const fechaAgenda = selectedOption.text.split(' - ')[1];
        const horaDesde = selectedOption.dataset.desde;
        const horaHasta = selectedOption.dataset.hasta;

        const parts = fechaTurno.split('T');
        const fecha = parts[0];
        const hora = parts[1].slice(0,5);

        if (fecha !== fechaAgenda) {
            alert('La fecha del turno debe coincidir con la fecha de la agenda seleccionada ('+fechaAgenda+').');
            e.preventDefault();
            return;
        }
        if (hora < horaDesde || hora > horaHasta) {
            alert('La hora del turno debe estar entre ' + horaDesde + ' y ' + horaHasta + '.');
            e.preventDefault();
            return;
        }
    }, true);
});

// Además: cuando el doctor dentro de un modal cambie, recargar sus agendas para ese modal específico
document.addEventListener('change', function(ev) {
    if (!ev.target.classList.contains('doctor-select')) return;
    const doctorId = ev.target.value;
    // encontrar agenda select asociado: asumimos que agenda-select está en el mismo form/modal
    let parent = ev.target.closest('.modal') || document;
    const agendaSelect = parent.querySelector('.agenda-select');
    if (agendaSelect) {
        cargarAgendasParaDoctor(doctorId, agendaSelect);
    }
});
</script>
=======

<script type="module" src="assets/js/turno/funciones_turno_lista.js"></script>
<script type="module">

    $(document).ready( function () {
        $('#panel-turnos').DataTable();
        $('#panel-turnos-pacientes').DataTable();
    } );

    function actualizarModoTurno() {
    const modoAgregar=document.getElementById("modo_agregar").checked;
    const divManual=document.getElementById("div_datetime_input");
    const divDisponibles=document.getElementById("div_select_turnos");
    const pacis=document.getElementById("div_select_paciente");

    if (modoAgregar) {
    divManual.style.display="block" ;
    divDisponibles.style.display="none" ;
    pacis.style.display="none" ; /*arreglar*/
    } else {
    divManual.style.display="none" ;
    divDisponibles.style.display="block" ;
    pacis.style.display="block" ;
    }
    }

    // Ejecutar al cargar la página
    document.addEventListener("DOMContentLoaded", actualizarModoTurno);

    // Detectar cambios en los radio buttons
    document.getElementById("modo_agregar").addEventListener("change", actualizarModoTurno);
    document.getElementById("modo_asignar").addEventListener("change", actualizarModoTurno);
    </script>
>>>>>>> origin/mi-ramita
