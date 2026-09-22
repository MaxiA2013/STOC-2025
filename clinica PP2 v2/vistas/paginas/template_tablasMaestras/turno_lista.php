<?php
require_once "modelos/turno.php";
require_once "modelos/paciente.php";
require_once "modelos/agenda.php";
require_once "modelos/agenda_turno.php";
require_once "modelos/estados.php";

/**
 * Ruta al controlador de turnos vista desde el navegador.
 * Ajustá este valor si tu estructura cambia o si cuelga de una carpeta diferente.
 */
$turnoControllerPath = "controladores/turno/turno_controlador.php";

$turnoObj       = new Turno();
$lista_turnos   = $turnoObj->consultarVariosTurnos();

$agendaObj      = new Agenda();
$doctores       = $agendaObj->obtenerDoctores();

$pacien         = new Paciente();
$lista_paciente = $pacien->listarPacientes();
$pacientes      = $lista_paciente;

$agendaTurnoObj   = new AgendaTurno();
$turnos_pacientes = $agendaTurnoObj->listar(); // turnos asignados a pacientes

$est      = new Estado();
$estados  = $est->consultarVariosEstados();
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
        <!-- FORMULARIO PRINCIPAL: agregar/asignar -->
        <form id="formAgregarTurno"
            class="form-box"
            method="POST"
            action="<?= $turnoControllerPath ?>">
            <input type="hidden" name="action" value="insertar">

            <div class="row g-3">
                <!-- Doctor -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Doctor</label>
                    <select id="doctorSelect" name="doctor_id" class="form-select select2-doctor" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($doctores as $doc): ?>
                            <option value="<?= $doc['id_doctor'] ?>">
                                <?= htmlspecialchars($doc['nombre_persona'] . " (" . $doc['nombre_usuario'] . ")") ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Agenda -->
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Agenda</label>
                    <select id="agendaSelect" name="agenda_id_agenda" class="form-select" required>
                        <option value="">Seleccione primero un doctor</option>
                    </select>
                </div>

                <!-- Modo -->
                <div class="col-12 col-md-6 col-lg-3" id="div_modo_turno">
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

                <!--  Turnos disponibles -->
                <div class="col-12 col-md-6 col-lg-3" id="div_select_turnos">
                    <label class="form-label">Turnos disponibles (seleccione)</label>
                    <select id="select_turnos_disponibles" class="form-select select2-turnos" name="turno_existente_id">
                        <option value="">Seleccione una agenda primero</option>
                    </select>
                    <small class="form-text text-muted">Si elige un turno, se usará su fecha/hora.</small>
                </div>

                <!-- disponibilidad manual -->
                <div class="col-12 col-md-6 col-lg-3" id="div_disponible_manual">
                    <label class="form-label d-block">Disponible</label>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="disponible"
                            id="disponible_si"
                            value="1"
                            checked>
                        <label class="form-check-label" for="disponible_si">
                            Sí
                        </label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="disponible"
                            id="disponible_no"
                            value="0">
                        <label class="form-check-label" for="disponible_no">
                            No
                        </label>
                    </div>
                </div>

                <!-- Paciente: manual no-disponible / asignar turno existente -->
                <div class="col-12 col-md-6 col-lg-3 d-none" id="div_select_paciente_manual">
                    <label class="form-label">Paciente</label>

                    <select
                        name="paciente_id"
                        id="select_pacientes_manual"
                        class="form-select select2-paciente"
                        disabled>

                        <option value="">Seleccione un paciente</option>

                        <?php foreach ($pacientes as $p): ?>
                            <option value="<?= $p['id_paciente'] ?>">
                                <?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                    <small class="form-text text-muted">
                        Seleccione el paciente al que se asignará este turno.
                    </small>
                </div>

                <!-- ¿Con obra social? -->
                <div class="col-12 col-md-6 col-lg-3 d-none" id="div_obra_social_toggle">
                    <label class="form-label d-block">¿Con obra social?</label>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                            type="radio"
                            name="con_obra_social"
                            id="obra_social_no"
                            value="0"
                            checked>
                        <label class="form-check-label" for="obra_social_no">No</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                            type="radio"
                            name="con_obra_social"
                            id="obra_social_si"
                            value="1">
                        <label class="form-check-label" for="obra_social_si">Sí</label>
                    </div>
                </div>

                <!-- Elección de Obra Social -->
                <div class="col-12 col-md-6 col-lg-3 d-none" id="div_select_obra_social">
                    <label class="form-label">Obra Social</label>
                    <select
                        name="obra_social_id"
                        id="select_obra_social"
                        class="form-select select2-obra-social"
                        disabled>
                        <option value="">Seleccione paciente y doctor primero</option>
                    </select>
                    <small class="form-text text-muted">
                        Solo se muestran las obras sociales en común entre el doctor y el paciente elegidos.
                    </small>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fa-solid fa-floppy-disk me-1"></i>
                    Guardar turno
                </button>

            </div>
        </form>
    </div>

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

                <!-- PANEL 1: TURNOS DISPONIBLES -->
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
                                        <!-- Eliminar turno disponible -->
                                        <a>
                                            <form action="<?= $turnoControllerPath ?>" method="post" style="display:inline;">
                                                <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">
                                                <input type="hidden" name="action" value="eliminacion">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </a>
                                        <!-- Editar turno disponible -->
                                        <a>
                                            <button type="button"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditar<?= $row['id_turnos'] ?>">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </a>
                                        <!-- boton para historial -->
                                        <a>
                                            <button type="button"
                                                class="btn btn-info btn-sm btn-ver-historial"
                                                data-id-turno="<?= $row['id_turnos'] ?>">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal edición por turno disponible -->
                                <!-- Modal edición por turno disponible -->
                                <div class="modal fade" id="modalEditar<?= $row['id_turnos'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form class="formEditarTurno"
                                                id="formEditarTurno_<?= $row['id_turnos'] ?>"
                                                method="POST"
                                                action="<?= $turnoControllerPath ?>">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modificar Turno #<?= $row['id_turnos'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <input type="hidden" name="action" value="actualizar">
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
                                                        <input type="number"
                                                            class="form-control"
                                                            name="minutos_turnos"
                                                            value="<?= $row['minutos_turnos'] ?>"
                                                            required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Fecha y Hora</label>
                                                        <input type="datetime-local"
                                                            class="form-control"
                                                            name="fecha_hora"
                                                            value="<?= date('Y-m-d\TH:i', strtotime($row['fecha_hora'])) ?>"
                                                            required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Disponible</label><br>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input"
                                                                type="radio"
                                                                name="disponible"
                                                                id="disponible_no_<?= $row['id_turnos'] ?>"
                                                                value="0"
                                                                <?= $row['disponible'] == 0 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="disponible_no_<?= $row['id_turnos'] ?>">No</label>
                                                        </div>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input"
                                                                type="radio"
                                                                name="disponible"
                                                                id="disponible_si_<?= $row['id_turnos'] ?>"
                                                                value="1"
                                                                <?= $row['disponible'] == 1 ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="disponible_si_<?= $row['id_turnos'] ?>">Sí</label>
                                                        </div>
                                                    </div>

                                                    <!-- Paciente (aparece solo si Disponible = No) -->
                                                    <div class="mb-3 d-none div-paciente-modal">
                                                        <label class="form-label">Paciente</label>

                                                        <select
                                                            name="paciente_id"
                                                            class="form-select select2-paciente-modal"
                                                            disabled>

                                                            <option value="">Seleccione un paciente</option>

                                                            <?php foreach ($pacientes as $p): ?>
                                                                <option value="<?= $p['id_paciente'] ?>">
                                                                    <?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?>
                                                                </option>
                                                            <?php endforeach; ?>

                                                        </select>

                                                        <small class="form-text text-muted">
                                                            Seleccione el paciente al que se asignará este turno.
                                                        </small>
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
                <div class="tab-pane fade" id="panel-turnos-pacientes2" role="tabpanel">
                    <table class="table table-striped" id="panel-turnos-pacientes">
                        <thead>
                            <tr>
                                <th>ID Agenda Turno</th>
                                <th>ID Turno</th>
                                <th>Paciente</th>
                                <th>Doctor</th>
                                <th>Fecha y Hora</th>
                                <th>Minutos</th>
                                <th>Estado</th>
                                <th>Obra Social</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($turnos_pacientes as $t): ?>
                                <tr>
                                    <td><?= $t['id_agenda_turno'] ?></td>

                                    <td><?= $t['turno_id_turnos'] ?></td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $t['paciente_nombre'] . " " . $t['paciente_apellido']
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            $t['doctor_nombre'] . " " . $t['doctor_apellido']
                                        ) ?>
                                    </td>

                                    <td><?= $t['fecha_hora'] ?></td>

                                    <td><?= $t['minutos_turnos'] ?></td>

                                    <td><?= htmlspecialchars($t['tipo_estado']) ?></td>

                                    <td>
                                        <?= $t['con_obra_social']
                                            ? htmlspecialchars($t['nombre_obra_social'] ?? 'Obra social eliminada')
                                            : 'No' ?>
                                    </td>

                                    <td class="d-flex gap-1">

                                        <!-- Botón eliminar (turno asignado) -->
                                        <a>
                                            <form action="<?= $turnoControllerPath ?>" method="post" style="display:inline;">
                                                <input type="hidden" name="action" value="eliminar">
                                                <input type="hidden" name="id_agenda_turno" value="<?= $t['id_agenda_turno'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </a>

                                        <!-- Botón editar turno asignado -->
                                        <a>
                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarAsignado<?= $t['id_agenda_turno'] ?>">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </a>
                                        <a>
                                            <button type="button"
                                                class="btn btn-info btn-sm btn-ver-historial"
                                                data-id-turno="<?= $t['turno_id_turnos'] ?>">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                            </button>
                                        </a>
                                    </td>
                                </tr>

                                <!-- MODAL PARA EDITAR TURNO ASIGNADO A PACIENTE -->
                                <div class="modal fade"
                                    id="modalEditarAsignado<?= $t['id_agenda_turno'] ?>"
                                    tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form method="POST"
                                                id="formEditarTurnoAsignado_<?= $t['id_agenda_turno'] ?>"
                                                action="<?= $turnoControllerPath ?>">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Editar Turno Asignado #<?= $t['id_agenda_turno'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body row">

                                                    <input type="hidden" name="action" value="editar_asignado">
                                                    <input type="hidden" name="id_agenda_turno" value="<?= $t['id_agenda_turno'] ?>">

                                                    <!-- PACIENTE -->
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Paciente</label>
                                                        <select class="form-select select2-pacientes" name="paciente_id_paciente" required>
                                                            <option value="">Seleccione paciente</option>
                                                            <?php foreach ($pacientes as $p): ?>
                                                                <option value="<?= $p['id_paciente'] ?>"
                                                                    <?= ($p['id_paciente'] == $t['paciente_id_paciente']) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>

                                                    <!-- DOCTOR -->
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <label class="form-label">Doctor</label>
                                                        <select id="doctorSelect_<?= $t['id_agenda_turno'] ?>"
                                                            class="form-select select2-doctor"
                                                            name="doctor_id"
                                                            required>
                                                            <option value="">Seleccione</option>
                                                            <?php foreach ($doctores as $doc): ?>
                                                                <option value="<?= $doc['id_doctor'] ?>"
                                                                    <?= ($doc['id_doctor'] == $t['doctor_id']) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($doc['nombre_persona'] . " (" . $doc['nombre_usuario'] . ")") ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <!-- AGENDA -->
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <label class="form-label">Agenda</label>
                                                        <select id="agendaSelect_<?= $t['id_agenda_turno'] ?>"
                                                            class="form-select agenda-select-edit"
                                                            name="agenda_id_agenda"
                                                            required>
                                                            <option value="<?= $t['agenda_id_agenda'] ?>" selected>
                                                                <?= htmlspecialchars($t['agenda_desc']) ?>
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <!-- TURNOS DISPONIBLES -->
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label">Fecha y Hora (turnos disponibles)</label>

                                                        <select id="turnosSelect_<?= $t['id_agenda_turno'] ?>"
                                                            name="turno_id"
                                                            class="form-select select2-turnos"
                                                            required>

                                                            <option value="<?= $t['turno_id_turnos'] ?>" selected>
                                                                <?= date("d/m/Y H:i", strtotime($t['fecha_hora'])) ?> (actual)
                                                            </option>

                                                        </select>

                                                        <small class="text-muted">
                                                            Se mostrarán solo los turnos disponibles de la agenda seleccionada.
                                                        </small>
                                                    </div>

                                                    <!-- MINUTOS -->
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <label class="form-label">Minutos</label>
                                                        <input type="number"
                                                            class="form-control"
                                                            name="minutos_turnos"
                                                            value="<?= $t['minutos_turnos'] ?>"
                                                            required>
                                                    </div>

                                                    <!-- ESTADO -->
                                                    <div class="col-12 col-md-6 mb-3">
                                                        <label class="form-label">Estado</label>
                                                        <select class="form-select" name="estados_id_estados" required>
                                                            <?php foreach ($estados as $e): ?>
                                                                <option value="<?= $e['id_estados'] ?>"
                                                                    <?= ($e['id_estados'] == $t['estados_id_estados']) ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($e['tipo_estado']) ?>
                                                                </option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>

                                                    <!-- ¿CON OBRA SOCIAL? -->
                                                    <div class="col-12 col-md-6 mb-3 div-obra-social-toggle-modal">
                                                        <label class="form-label d-block">¿Con obra social?</label>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input"
                                                                type="radio"
                                                                name="con_obra_social"
                                                                id="obra_social_no_asig_<?= $t['id_agenda_turno'] ?>"
                                                                value="0"
                                                                <?= !$t['con_obra_social'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="obra_social_no_asig_<?= $t['id_agenda_turno'] ?>">No</label>
                                                        </div>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input"
                                                                type="radio"
                                                                name="con_obra_social"
                                                                id="obra_social_si_asig_<?= $t['id_agenda_turno'] ?>"
                                                                value="1"
                                                                <?= $t['con_obra_social'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="obra_social_si_asig_<?= $t['id_agenda_turno'] ?>">Sí</label>
                                                        </div>
                                                    </div>

                                                    <!-- OBRA SOCIAL -->
                                                    <div class="col-12 mb-3 div-select-obra-social-modal <?= $t['con_obra_social'] ? '' : 'd-none' ?>">
                                                        <label class="form-label">Obra Social</label>
                                                        <select
                                                            name="obra_social_id"
                                                            class="form-select select2-obra-social-modal"
                                                            <?= $t['con_obra_social'] ? '' : 'disabled' ?>>

                                                            <?php if ($t['con_obra_social'] && !empty($t['obra_social_id_obra_social'])): ?>
                                                                <option value="<?= $t['obra_social_id_obra_social'] ?>" selected>
                                                                    <?= htmlspecialchars($t['nombre_obra_social'] ?? '') ?>
                                                                </option>
                                                            <?php else: ?>
                                                                <option value="">Seleccione un paciente primero</option>
                                                            <?php endif; ?>

                                                        </select>
                                                        <small class="form-text text-muted">
                                                            Solo se muestran las obras sociales en común entre el doctor y el paciente elegidos.
                                                        </small>
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-success">
                                                        Guardar
                                                    </button>
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
    </div>
</div>

<div class="modal fade" id="modalHistorial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historial del turno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="listaHistorial">
                    <li class="list-group-item text-muted">Cargando...</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/sweetalert2@11.js"></script>
<script type="module" src="assets/js/validaciones/turno/funciones_turno_lista.js"></script>
<script type="module" src="assets/js/validaciones/turno/modalEditar_TurnosAsignados.js"></script>
<script type="module" src="assets/js/validaciones/turno/envio_turno_controlador.js"></script>