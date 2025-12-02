<?php
// vistas/paginas/turno_lista.php
require_once "modelos/turno.php";
require_once "modelos/agenda.php";

$turnoObj = new Turno();
$lista_turnos = $turnoObj->consultarVariosTurnos();

$agendaObj = new Agenda();
$doctores = $agendaObj->obtenerDoctores();
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
                        <?php foreach ($doctores as $doc): ?>
                            <option value="<?= $doc['id_doctor'] ?>">
                                <?= htmlspecialchars($doc['nombre_persona'] . " (" . $doc['nombre_usuario'] . ")") ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Agenda</label>
                    <select name="agenda_id_agenda" id="agendaSelect" class="form-control agenda-select" required>
                        <option value="">Seleccione primero un doctor</option>
                    </select>
                </div>

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
        </div>
    </div>
</div>

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
