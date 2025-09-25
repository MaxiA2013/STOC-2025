<?php
require_once "modelos/conexion.php";
require_once "modelos/agenda.php";

// Obtener las agendas existentes
$agenda = new Agenda("", "", "", "", "", "", "", "");
$agendas = $agenda->all_agendas();

// Obtener los doctores para el select
$conn = new Conexion();
$sqlDoctores = "SELECT d.id_doctor, p.nombre, p.apellido
                FROM doctor d
                JOIN usuario u ON d.usuario_id_usuario = u.id_usuario
                JOIN persona p ON u.persona_id_persona = p.id_persona
                JOIN usuario_has_perfil up ON u.id_usuario = up.usuario_id_usuario
                WHERE up.perfil_id_perfil = 2"; 
$doctores = $conn->consultar($sqlDoctores);

// Obtener los días para el select
$sqlDias = "SELECT id_dias, descripcion FROM dias";
$dias = $conn->consultar($sqlDias);

// Crear arrays para mapear doctor y día por ID
$mapaDoctores = [];
foreach ($doctores as $doc) {
    $mapaDoctores[$doc['id_doctor']] = $doc['nombre'] . ' ' . $doc['apellido'];
}

$mapaDias = [];
foreach ($dias as $dia) {
    $mapaDias[$dia['id_dias']] = $dia['descripcion'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Agendas</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Registrar Agenda</h2>
    <form action="../../controladores/agenda_controlador.php" method="POST">
        <input type="hidden" name="action" value="guardar_agenda">

        <div class="row mb-3">
            <div class="col">
                <label for="fecha_desde">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="form-control" required>
            </div>
            <div class="col">
                <label for="fecha_hasta">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="hora_desde">Hora Desde</label>
                <input type="time" name="hora_desde" class="form-control" required>
            </div>
            <div class="col">
                <label for="hora_hasta">Hora Hasta</label>
                <input type="time" name="hora_hasta" class="form-control" required>
            </div>
            <div class="col">
                <label for="minutos_turnos">Duración del turno (minutos)</label>
                <input type="number" name="minutos_turnos" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="dias_id_dias">Día</label>
                <select name="dias_id_dias" class="form-control" required>
                    <option value="">Seleccione un día</option>
                    <?php foreach ($dias as $dia): ?>
                        <option value="<?= $dia['id_dias'] ?>"><?= $dia['descripcion'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label for="doctor_id_doctor">Doctor</label>
                <select name="doctor_id_doctor" class="form-control" required>
                    <option value="">Seleccione un doctor</option>
                    <?php foreach ($doctores as $doctor): ?>
                        <option value="<?= $doctor['id_doctor'] ?>">
                            Dr. <?= $doctor['nombre'] . ' ' . $doctor['apellido'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Agenda</button>
    </form>

    <hr class="my-5">

    <h3>Agendas Registradas</h3>
    <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Fecha Desde</th>
                <th>Fecha Hasta</th>
                <th>Hora Desde</th>
                <th>Hora Hasta</th>
                <th>Minutos por Turno</th>
                <th>Día</th>
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($agendas)) : ?>
                <?php foreach ($agendas as $fila) : ?>
                    <tr>
                        <td><?= $fila['id_agenda'] ?></td>
                        <td><?= $fila['fecha_desde'] ?></td>
                        <td><?= $fila['fecha_hasta'] ?></td>
                        <td><?= $fila['hora_desde'] ?></td>
                        <td><?= $fila['hora_hasta'] ?></td>
                        <td><?= $fila['minutos_turnos'] ?></td>
                        <td><?= $mapaDias[$fila['dias_id_dias']] ?? 'Desconocido' ?></td>
                        <td><?= $mapaDoctores[$fila['doctor_id_doctor']] ?? 'Desconocido' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="text-center">No hay agendas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
