<?php
require_once "modelos/agenda.php";
require_once "modelos/doctor.php";
require_once "modelos/estados.php";

$agenda = new Agenda();
$lista = $agenda->listarAgendas();

$doctor = new Doctor();
$doctores = $doctor->all_doctores();

$estados = Estado::consultarVariosEstados();
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mt-4">

    <h3>Registrar Nueva Agenda</h3>

    <form id="formAgenda" class="border p-3 rounded">

        <input type="hidden" name="accion" value="guardar">

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Doctor</label>
                <select name="doctor_id" class="form-select select2" required>
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
                    <?php while ($e = $estados->fetch_assoc()): ?>
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

    <table class="table table-bordered mt-3" id="tablaAgendas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Doctor</th>
                <th>Estado</th>
                <th>Fecha Desde</th>
                <th>Fecha Hasta</th>
                <th>Hora Desde</th>
                <th>Hora Hasta</th>
                <th>Minutos Turno</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tbodyAgendas">
            <?php foreach ($lista as $fila): ?>
                <tr>
                    <td><?= $fila['id_agenda'] ?></td>
                    <td><?= $fila['doctor_nombre'] ?></td>
                    <td><?= $fila['estado_nombre'] ?></td>
                    <td><?= $fila['fecha_desde'] ?></td>
                    <td><?= $fila['fecha_hasta'] ?></td>
                    <td><?= $fila['hora_desde'] ?></td>
                    <td><?= $fila['hora_hasta'] ?></td>
                    <td><?= $fila['minutos_turnos'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm btnEditar"
                            data-id="<?= $fila['id_agenda'] ?>"
                            data-doctor="<?= $fila['doctor_id_doctor'] ?>"
                            data-estado="<?= $fila['estados_id_estados'] ?>"
                            data-fdesde="<?= $fila['fecha_desde'] ?>"
                            data-fhasta="<?= $fila['fecha_hasta'] ?>"
                            data-hdesde="<?= $fila['hora_desde'] ?>"
                            data-hhasta="<?= $fila['hora_hasta'] ?>"
                            data-minutos="<?= isset($fila['minutos_turnos']) ? $fila['minutos_turnos'] : "" ?>">
                            Editar
                        </button>

                        <button class="btn btn-danger btn-sm btnEliminar"
                            data-id="<?= $fila['id_agenda'] ?>">
                            Eliminar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<!-- MODAL EDITAR -->
<div class="modal" tabindex="-1" id="modalEditar">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditar">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Agenda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="accion" value="editar">
                    <input type="hidden" name="id" id="edit_id">

                    <label>Doctor</label>
                    <select name="doctor_id" id="edit_doctor" class="form-select select2" required>
                        <?php foreach ($doctores as $d): ?>
                            <option value="<?= $d['id_doctor'] ?>">
                                <?= $d['nombre'] ?> <?= $d['apellido'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Estado</label>
                    <select name="estados_id_estados" id="edit_estado" class="form-select" required>
                        <?php
                        $estados2 = Estado::consultarVariosEstados();
                        while ($e = $estados2->fetch_assoc()):
                        ?>
                            <option value="<?= $e['id_estados'] ?>">
                                <?= $e['tipo_estado'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <label>Fecha Desde</label>
                    <input type="date" id="edit_fdesde" name="fecha_desde" class="form-control" required>

                    <label>Fecha Hasta</label>
                    <input type="date" id="edit_fhasta" name="fecha_hasta" class="form-control" required>

                    <label>Hora Desde</label>
                    <input type="time" id="edit_hdesde" name="hora_desde" class="form-control" required>

                    <label>Hora Hasta</label>
                    <input type="time" id="edit_hhasta" name="hora_hasta" class="form-control" required>

                    <label>Duración del Turno (min)</label>
                    <input type="number" id="edit_minutos" name="minutos_turnos" class="form-control" min="1" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Guardar Cambios</button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    $(".select2").select2();

    $("#edit_doctor").select2({
        dropdownParent: $("#modalEditar")
    });

    // ==============================
    // GUARDAR NUEVA AGENDA
    // ==============================
    $("#formAgenda").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "controladores/agenda_controlador.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(r) {
                let resp = JSON.parse(r);

                if (resp.error === "superposicion") {
                    alert("❌ La agenda se superpone con otra existente.");
                    return;
                }

                if (resp.success) {
                    alert("Agenda registrada correctamente.");
                    location.reload();
                }
            }
        });
    });

    // ░░░░░░░░░░ BOTÓN EDITAR ░░░░░░░░░░
    $(document).on("click", ".btnEditar", function() {
        $("#edit_id").val($(this).data("id"));
        $("#edit_doctor").val($(this).data("doctor")).trigger("change");
        $("#edit_estado").val($(this).data("estado"));
        $("#edit_fdesde").val($(this).data("fdesde"));
        $("#edit_fhasta").val($(this).data("fhasta"));
        $("#edit_hdesde").val($(this).data("hdesde"));
        $("#edit_hhasta").val($(this).data("hhasta"));
        $("#edit_minutos").val($(this).data("minutos"));


        new bootstrap.Modal(document.getElementById("modalEditar")).show();
    });


    // ░░░░░░░░░░ GUARDAR EDICIÓN ░░░░░░░░░░
    $("#formEditar").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: "controladores/agenda_controlador.php",
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                if (!res.success) {
                    alert(res.error ?? "Error al modificar.");
                    return;
                }
                alert("Agenda modificada correctamente");
                location.reload();
            },
            error: function(xhr) {
                alert("Error en la solicitud:\n" + xhr.responseText);
            }
        });
    });



    // ░░░░░░░░░░ BOTÓN ELIMINAR ░░░░░░░░░░
    $(document).on("click", ".btnEliminar", function() {
        if (!confirm("¿Desea eliminar esta agenda? Se eliminarán también los turnos.")) return;

        $.ajax({
            url: "controladores/agenda_controlador.php",
            method: "POST",
            data: {
                accion: "eliminar",
                id: $(this).data("id")
            },
            dataType: "json",
            success: function(res) {
                if (!res.success) {
                    alert("No se pudo eliminar.");
                    return;
                }
                alert("Agenda eliminada correctamente");
                location.reload();
            }
        });
    });
</script>