<?php 
require_once "modelos/agenda.php";
require_once "modelos/doctor.php";
require_once "modelos/estados.php";
require_once "modelos/turno.php";

$agenda = new Agenda();
$lista = $agenda->listarAgendas();

$doctor = new Doctor();
$doctores = $doctor->all_doctores();

$estados = Estado::consultarVariosEstados();
?>

<div class="container mt-4">
    <h3>Registrar Nueva Agenda</h3>

    <form id="formAgenda" class="border p-3 rounded">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Doctor</label>
                <select name="doctor_id" class="form-select" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($doctores as $d): ?>
                        <option value="<?= $d['id_doctor'] ?>">
                            <?= $d['nombre'] ?> <?= $d['apellido'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Estado</label>
                <select name="estados_id_estados" class="form-select" required>
                    <option value="">Seleccione</option>
                    <?php while($e = $estados->fetch_assoc()): ?>
                        <option value="<?= $e['id_estados'] ?>">
                            <?= $e['tipo_estado'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Duración del Turno (min)</label>
                <input type="number" class="form-control" name="minutos_turnos" min="1" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <label>Fecha Desde</label>
                <input type="date" name="fecha_desde" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Hora Desde</label>
                <input type="time" name="hora_desde" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label>Hora Hasta</label>
                <input type="time" name="hora_hasta" class="form-control" required>
            </div>
        </div>

        <button class="btn btn-primary mt-2">Guardar</button>
    </form>


    <hr>

    <h3>Agendas Registradas</h3>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Doctor</th>
                <th>Estado</th>
                <th>Fecha Desde</th>
                <th>Fecha Hasta</th>
                <th>Hora Desde</th>
                <th>Hora Hasta</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista as $fila): ?>
            <tr>
                <td><?= $fila['id_agenda'] ?></td>
                <td><?= $fila['doctor_nombre'] ?></td>
                <td><?= $fila['estado_nombre'] ?></td>
                <td><?= $fila['fecha_desde'] ?></td>
                <td><?= $fila['fecha_hasta'] ?></td>
                <td><?= $fila['hora_desde'] ?></td>
                <td><?= $fila['hora_hasta'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById("formAgenda").addEventListener("submit", function(e){
    e.preventDefault();

    let datos = new FormData(this);

    fetch("controladores/agenda_controlador.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert("Error al registrar agenda");
            return;
        }

        alert("Agenda registrada correctamente y turnos generados.");
        location.reload();
    })
    .catch(err => {
        console.log("Error AJAX:", err);
        alert("Error inesperado");
    });
});
</script>
