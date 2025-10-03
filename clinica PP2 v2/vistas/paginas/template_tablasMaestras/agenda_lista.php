<?php
require_once "modelos/agenda.php";

$agenda = new Agenda();
$agendas = $agenda->obtenerAgendas();
$doctores = $agenda->obtenerDoctores();
$estados = $agenda->obtenerEstados();
$mapaDoctores = $agenda->mapearDoctoresPorId();
$mapaEstados = $agenda->mapearEstadosPorId();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Agendas</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Registrar Agenda</h2>

    <form action="controladores/agenda_controlador.php" method="POST">
        <input type="hidden" name="action" value="guardar_agenda">

        <div class="row mb-3">
            <div class="col">
                <label for="fecha_agenda">Fecha</label>
                <input type="date" name="fecha_agenda" class="form-control" required>
            </div>
            <div class="col">
                <label for="hora_desde">Hora Desde</label>
                <input type="time" name="hora_desde" class="form-control" required>
            </div>
            <div class="col">
                <label for="hora_hasta">Hora Hasta</label>
                <input type="time" name="hora_hasta" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="estados_id_estados">Estado</label>
                <select name="estados_id_estados" class="form-control" required>
                    <option value="">Seleccione un estado</option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?= $estado['id_estados'] ?>"><?= $estado['tipo_estado'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label for="doctor_id_doctor">Doctor</label>
                <select name="doctor_id_doctor" class="form-control" required>
                    <option value="">Seleccione un doctor</option>
                    <?php foreach ($doctores as $doctor): ?>
                        <option value="<?= $doctor['id_doctor'] ?>">
                            Dr. <?= $doctor['nombre_persona'] ?>
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
                <th>Fecha</th>
                <th>Hora Desde</th>
                <th>Hora Hasta</th>
                <th>Estado</th>
                <th>Doctor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($agendas)) : ?>
                <?php foreach ($agendas as $fila) : ?>
                    <tr>
                        <td><?= $fila['id_agenda'] ?></td>
                        <td><?= $fila['fecha_agenda'] ?></td>
                        <td><?= $fila['hora_desde'] ?></td>
                        <td><?= $fila['hora_hasta'] ?></td>
                        <td><?= $mapaEstados[$fila['estados_id_estados']] ?? 'Desconocido' ?></td>
                        <td><?= $mapaDoctores[$fila['doctor_id_doctor']] ?? 'Desconocido' ?></td>
                        <td>
                           <a href="index.php?page=editar_agenda&id=<?= $fila['id_agenda'] ?>" class="btn btn-warning btn-sm">Modificar</a>

                            <!-- boton eliminar -->
                            <form action="controladores/agenda_controlador.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="eliminar_agenda">
                                <input type="hidden" name="id_agenda" value="<?= $fila['id_agenda'] ?>">
                                <input type="hidden" name="estado_inactivo_id" value="3"> <!-- ID del estado 'inactivo' -->
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">No hay agendas registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>