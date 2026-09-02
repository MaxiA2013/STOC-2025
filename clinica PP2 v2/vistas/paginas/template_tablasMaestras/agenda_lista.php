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
<link href="assets/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="assets/js/select2.min.js"></script>

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

<script src="assets/js/sweetalert2@11.js"></script>
<script>
    // Select2 inicial
    $(".select2").select2();

    $("#edit_doctor").select2({
        dropdownParent: $("#modalEditar")
    });

    // datatables
    $(document).ready(function() {
        $('#tablaAgendas').DataTable();
    });

    // Helpers: Toast + SweetAlert2
    function showToast(message, type = 'success') {
        let $container = $('#toastContainer');
        if ($container.length === 0) {
            $container = $('<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
            $('body').append($container);
        }

        const id = 'toast_' + Date.now();
        const $toast = $(`
            <div id="${id}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);

        $container.append($toast);

        const toastEl = document.getElementById(id);
        const toast = new bootstrap.Toast(toastEl, {
            delay: 3000
        });
        toast.show();
    }

    //SweetAlert2 de error
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message || 'Ocurrió un error al procesar la operación.'
        });
    }

    // GUARDAR NUEVA AGENDA
    $("#formAgenda").submit(function(e) {
        e.preventDefault();

        $.ajax({
            url: "controladores/agenda/agenda_controlador.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",

            success: function(res) {

                console.log("resp editar agenda:", res);

                if (!res) {
                    showErrorAlert("Respuesta vacía del servidor.");
                    return;
                }

                // -----------------------------------------
                // Superposición
                // -----------------------------------------

                if (res.error === "superposicion") {

                    Swal.fire({
                        icon: "error",
                        title: "Agenda superpuesta",
                        text: "La agenda se superpone con otra existente para ese doctor."
                    });

                    return;
                }


                // -----------------------------------------
                // Turnos asignados que quedarían fuera
                // -----------------------------------------

                if (
                    res.error ===
                    "Hay turnos asignados que quedarían fuera de la nueva configuración."
                ) {

                    let mensaje =
                        "No se puede modificar la agenda porque los siguientes turnos ya tienen pacientes asignados:";

                    if (
                        res.turnos_asignados &&
                        res.turnos_asignados.length > 0
                    ) {

                        mensaje += "\n\n";

                        res.turnos_asignados.forEach(function(fecha) {
                            mensaje += "• " + fecha + "\n";
                        });
                    }

                    Swal.fire({
                        icon: "warning",
                        title: "Hay turnos asignados",
                        text: mensaje
                    });

                    return;
                }


                // -----------------------------------------
                // Cualquier otro error
                // -----------------------------------------

                if (!res.success) {

                    showErrorAlert(
                        res.error || "Error al modificar la agenda."
                    );

                    return;
                }


                // -----------------------------------------
                // Todo correcto
                // -----------------------------------------

                const modalEl = document.getElementById("modalEditar");

                const modal =
                    bootstrap.Modal.getInstance(modalEl) ||
                    new bootstrap.Modal(modalEl);

                modal.hide();

                showToast(
                    "Agenda modificada correctamente.",
                    "success"
                );

                setTimeout(function() {
                    location.reload();
                }, 800);
            },
            error: function(xhr, status, err) {
                console.error("Error AJAX guardar agenda:", status, err, xhr.responseText);
                showErrorAlert("Error al guardar la agenda. Revisá la consola para más detalles.");
            }
        });
    });

    // BOTÓN EDITAR → abrir modal
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

    // GUARDAR EDICIÓN DE AGENDA
    $("#formEditar").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: "controladores/agenda/agenda_controlador.php",
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res) {
                console.log('resp editar agenda:', res);

                if (!res) {
                    showErrorAlert("Respuesta vacía del servidor.");
                    return;
                }

                if (res.error === "superposicion") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Agenda superpuesta',
                        text: 'La agenda se superpone con otra existente para ese doctor.'
                    });
                    return;
                }

                if (!res.success) {
                    showErrorAlert(res.error ?? "Error al modificar la agenda.");
                    return;
                }

                // Cerrar modal
                const modalEl = document.getElementById("modalEditar");
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();

                showToast("Agenda modificada correctamente.", "success");
                setTimeout(() => location.reload(), 800);
            },
            error: function(xhr, status, err) {
                console.error("Error AJAX editar agenda:", status, err, xhr.responseText);
                showErrorAlert("Error en la solicitud al modificar la agenda.");
            }
        });
    });

    // ELIMINAR AGENDA (SweetAlert2)
    $(document).on("click", ".btnEliminar", function() {
        const id = $(this).data("id");
        const $row = $(this).closest("tr");

        Swal.fire({
            title: '¿Desea eliminar esta agenda?',
            text: 'Se eliminarán también sus turnos y asignaciones relacionadas.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "controladores/agenda/agenda_controlador.php",
                method: "POST",
                data: {
                    accion: "eliminar",
                    id: id
                },
                dataType: "json",
                success: function(res) {
                    console.log('resp eliminar agenda:', res);

                    if (!res) {
                        showErrorAlert("Respuesta vacía del servidor.");
                        return;
                    }

                    if (!res.success) {
                        showErrorAlert(res.error || "No se pudo eliminar la agenda.");
                        return;
                    }

                    // Quitar fila de la tabla sin recargar
                    if ($row.length) {
                        $row.remove();
                    }

                    showToast("Agenda eliminada correctamente.", "success");
                },
                error: function(xhr, status, err) {
                    console.error("Error AJAX eliminar agenda:", status, err, xhr.responseText);
                    showErrorAlert("Error al eliminar la agenda. Revisá la consola para más detalles.");
                }
            });
        });
    });
</script>