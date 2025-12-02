<?php
require_once "modelos/turno.php";
require_once "modelos/paciente.php";
require_once "modelos/agenda.php";
require_once "modelos/agenda_turno.php";

$turnoObj = new Turno();
$lista_turnos = $turnoObj->consultarVariosTurnos();

$agendaObj = new Agenda();
$doctores = $agendaObj->obtenerDoctores();

$pacien = new Paciente();
$lista_paciente = $pacien->listarPacientes();

$agendaTurnoObj = new AgendaTurno();
$turnos_pacientes = $agendaTurnoObj->listar(); // obtiene todos los turnos asignados/relacionados a pacientes
?>

<link href="assets/css/select2.min.css" rel="stylesheet" />

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
                        <button class="nav-link active" id="tab-turnos" data-bs-toggle="tab" data-bs-target="#panel-turnos" type="button" role="tab">
                            Turnos Disponibles
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-turnos-pacientes" data-bs-toggle="tab" data-bs-target="#panel-turnos-pacientes" type="button" role="tab">
                            Turnos Asignados a Pacientes
                        </button>
                    </li>
                </ul>

                <div class="tab-content" style="min-height:200px;">
                    <!-- PANEL 1 -->
                    <div class="tab-pane fade show active" id="panel-turnos" role="tabpanel">
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
                                        <td class="d-flex gap-1">
                                            <!-- Eliminar -->
                                            <form action="controladores/turno/turno_controlador.php" method="post" style="display:inline;">
                                                <input type="hidden" name="id_turnos" value="<?= $row['id_turnos'] ?>">
                                                <input type="hidden" name="action" value="eliminacion">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>

                                            <!-- Editar (mantengo tu modal generada más abajo) -->
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

                                    <!-- Modal edición por turno (igual al tuyo; se mantiene) -->
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
                    <div class="tab-pane fade" id="panel-turnos-pacientes" role="tabpanel">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Agenda Turno</th>
                                    <th>Paciente</th>
                                    <th>Fecha y Hora</th>
                                    <th>Minutos</th>
                                    <th>Estado</th>
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
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                </div> <!-- cierre tab-content -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para seleccionar paciente -->
<div class="modal fade" id="modalPaciente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formBuscarPaciente">
                <div class="modal-header">
                    <h5 class="modal-title">Seleccionar Paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Paciente</label>
                    <select id="selectPaciente" class="form-select select2-paciente" style="width:100%">
                        <option value="">Cargando...</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnAceptarPaciente" class="btn btn-primary">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="module" src="assets/js/turno/funciones_turno_lista.js"></script>
<script>

    function actualizarModoTurno() {
        const modoAgregar = document.getElementById("modo_agregar").checked;
        const divManual = document.getElementById("div_datetime_input");
        const divDisponibles = document.getElementById("div_select_turnos");
        const pacis = document.getElementById("div_select_paciente");

        if (modoAgregar) {
            divManual.style.display = "block";
            divDisponibles.style.display = "none";
            pacis.style.display = "none"; /*arreglar*/
        } else {
            divManual.style.display = "none";
            divDisponibles.style.display = "block";
            pacis.style.display = "block";
        }
    }

    // Ejecutar al cargar la página
    document.addEventListener("DOMContentLoaded", actualizarModoTurno);

    // Detectar cambios en los radio buttons
    document.getElementById("modo_agregar").addEventListener("change", actualizarModoTurno);
    document.getElementById("modo_asignar").addEventListener("change", actualizarModoTurno);

</script>