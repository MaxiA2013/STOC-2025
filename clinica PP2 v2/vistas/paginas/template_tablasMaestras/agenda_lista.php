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

    <!-- 🚀 Formulario con AJAX -->
    <form id="form-agenda">
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
                        <option value="<?= $doctor['id_doctor'] ?>"> Dr. <?= $doctor['nombre_persona'] ?> </option>
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

                        <form action="controladores/agenda_controlador.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="eliminar_agenda">
                            <input type="hidden" name="id_agenda" value="<?= $fila['id_agenda'] ?>">
                            <input type="hidden" name="estado_inactivo_id" value="3">
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

<!-- 🚀 Modal para elegir minutos -->
<div class="modal fade" id="modalMinutos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form-turnos">
        <div class="modal-header">
          <h5 class="modal-title">Configurar Duración de Turnos</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" name="action" value="generar_turnos">
          <input type="hidden" id="agenda_id" name="agenda_id">

          <label for="minutos_turnos">Duración de cada turno (minutos)</label>
          <input type="number" id="minutos_turnos" name="minutos_turnos" class="form-control" min="5" step="5" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Generar Turnos</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    // Interceptar envío de agenda
    $("#formAgenda").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
        url: "controladores/agenda_controlador.php",
        type: "POST",
        data: $(this).serialize() + "&action=insertar",
        dataType: "json", // 👈 MUY IMPORTANTE
        success: function (response) {
            if (response.success) {
                // 
                alert("Agenda guardada con ID: " + response.id_agenda);
                
                // Cerrar modal
                $("#modalAgenda").modal("hide");
                
                // Abrir modal de turnos (si corresponde)
                $("#modalTurno").modal("show");

                // También podrías refrescar lista
                cargarAgendas();
            } else {
                alert("Error: " + (response.error || "Error desconocido"));
            }
        },
        error: function (xhr, status, error) {
            console.error("Error AJAX:", error);
            alert("Error en el servidor. Revisa la consola.");
        }
    });
});

    // Interceptar envío de turnos
    $("#form-turnos").submit(function(e) {
        e.preventDefault();
        $.post("controladores/turno_controlador.php", $(this).serialize(), function(resp) {
            try {
                let data = JSON.parse(resp);
                if (data.success) {
                    alert("Turnos generados correctamente.");
                    location.reload();
                } else {
                    alert("Error al generar turnos.");
                }
            } catch(err) {
                alert("Respuesta inesperada: " + resp);
            }
        });
    });
});
</script>
</body>
</html>
