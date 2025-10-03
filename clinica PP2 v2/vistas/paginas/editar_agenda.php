<?php
require_once "modelos/agenda.php";

$id_agenda = $_GET['id'] ?? null;
$agenda = new Agenda();
$datos = $agenda->obtenerPorId($id_agenda);
$doctores = $agenda->obtenerDoctores();
$estados = $agenda->obtenerEstados();
?>

<div class="container mt-5">
    <h2 class="mb-4">Modificar Agenda</h2>
    <form action="controladores/agenda_controlador.php" method="POST">
        <input type="hidden" name="action" value="modificar_agenda">
        <input type="hidden" name="id_agenda" value="<?= $datos['id_agenda'] ?>">

        <div class="row mb-3">
            <div class="col">
                <label for="fecha_agenda">Fecha</label>
                <input type="date" name="fecha_agenda" class="form-control" value="<?= $datos['fecha_agenda'] ?>" required>
            </div>
            <div class="col">
                <label for="hora_desde">Hora Desde</label>
                <input type="time" name="hora_desde" class="form-control" value="<?= $datos['hora_desde'] ?>" required>
            </div>
            <div class="col">
                <label for="hora_hasta">Hora Hasta</label>
                <input type="time" name="hora_hasta" class="form-control" value="<?= $datos['hora_hasta'] ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="estados_id_estados">Estado</label>
                <select name="estados_id_estados" class="form-control" required>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?= $estado['id_estados'] ?>" <?= $estado['id_estados'] == $datos['estados_id_estados'] ? 'selected' : '' ?>>
                            <?= $estado['tipo_estado'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label for="doctor_id_doctor">Doctor</label>
                <select name="doctor_id_doctor" class="form-control" required>
                    <?php foreach ($doctores as $doctor): ?>
                        <option value="<?= $doctor['id_doctor'] ?>" <?= $doctor['id_doctor'] == $datos['doctor_id_doctor'] ? 'selected' : '' ?>>
                            Dr. <?= $doctor['nombre_persona'] ?> (<?= $doctor['nombre_usuario'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-warning">Guardar Cambios</button>
    </form>
</div>
