document.addEventListener("DOMContentLoaded", () => {

    //este js pertenece a las tablas 
    // Activar el select2 si lo usas
    if ($(".select2-pacientes").length) {
        $(".select2-pacientes").select2({
            dropdownParent: $("#modalTurno") 
        });
    }

    // Evento submit del formulario de turno
    document.getElementById("formAgregarTurno").addEventListener("submit", function (e) {
        e.preventDefault();
        guardarTurno();
    });

});

// assets/js/turno/envio_turno_controlador.js
$(document).ready(function () {

    // ==========================================================
    // Helpers: Toast de Bootstrap + SweetAlert2 de error
    // ==========================================================

    /**
     * Muestra un toast de Bootstrap en la esquina inferior derecha
     * @param {string} message
     * @param {'success'|'danger'|'warning'|'info'} type
     */
    function showToast(message, type = 'success') {
        // Contenedor general de toasts
        let $container = $('#toastContainer');
        if ($container.length === 0) {
            $container = $('<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
            $('body').append($container);
        }

        // Crear toast
        const toastId = 'toast_' + Date.now();
        const $toast = $(`
            <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);

        $container.append($toast);

        const toastEl = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }

    /**
     * Muestra un SweetAlert2 de error
     * @param {string} message
     */
    function showErrorAlert(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message || 'Ocurrió un error al procesar la operación.'
        });
    }

    /**
     * Función genérica para enviar formularios por AJAX hacia turno_controlador.php
     * @param {jQuery} $form
     * @param {object} options
     *   - successMessage: texto del toast en caso de éxito
     *   - onSuccess(resp, $form): callback adicional en caso de éxito
     */
    function enviarFormularioAjax($form, options = {}) {
        const successMessage = options.successMessage || 'Operación realizada correctamente.';
        const onSuccess = options.onSuccess;

        $.ajax({
            url: 'controladores/turno/turno_controlador.php',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function (resp) {
                console.log('Respuesta controlador:', resp);

                if (!resp) {
                    showErrorAlert('Respuesta vacía del servidor.');
                    return;
                }

                if (resp.success) {
                    // Toast de éxito
                    showToast(successMessage, 'success');

                    if (typeof onSuccess === 'function') {
                        onSuccess(resp, $form);
                    }
                } else {
                    showErrorAlert(resp.error || 'Operación no realizada.');
                }
            },
            error: function (xhr, status, err) {
                console.error('Error AJAX:', status, err, xhr.responseText);
                showErrorAlert('Error al procesar la petición. Revisa la consola para más detalles.');
            }
        });
    }

    // ==========================================================
    // 1) FORMULARIO PRINCIPAL (AGREGAR / ASIGNAR)
    // ==========================================================
    $('#formAgregarTurno').on('submit', function (e) {
        e.preventDefault();
        const $form = $(this);

        enviarFormularioAjax($form, {
            successMessage: 'Turno creado/actualizado correctamente.',
            onSuccess: function (resp, $f) {
                // Opcional: limpiar formulario después de crear
                $f[0].reset();
                // Aquí podrías agregar la nueva fila a la tabla si quieres,
                // pero para mantenerlo simple solo mostramos el toast.
            }
        });
    });

    // ==========================================================
    // 2) EDITAR TURNOS DISPONIBLES (tabla turno)
    //    Formularios dentro de los modales: <form class="formEditarTurno" ...>
    // ==========================================================
    $(document).on('submit', 'form.formEditarTurno', function (e) {
        e.preventDefault();
        const $form = $(this);

        enviarFormularioAjax($form, {
            successMessage: 'Turno disponible actualizado correctamente.',
            onSuccess: function (resp, $f) {
                // 1) Obtener ID de turno y buscar su fila en la tabla
                const idTurno = $f.find("input[name='id_turnos']").val();

                const $fila = $("#panel-turnos tbody tr").filter(function () {
                    return $(this).find("td").eq(0).text().trim() === idTurno;
                });

                if ($fila.length) {
                    // 2) Tomar valores del formulario
                    const minutos    = $f.find("input[name='minutos_turnos']").val();
                    const fechaHora  = $f.find("input[name='fecha_hora']").val();
                    const disponible = $f.find("input[name='disponible']:checked").val();
                    const disponibleTexto = (disponible === "1") ? "Sí" : "No";

                    const agendaTexto = $f.find("select[name='agenda_id_agenda'] option:selected").text();
                    const doctorTexto = $f.find("select[name='doctor_id_modal'] option:selected").text();

                    // 3) Actualizar columnas de la fila
                    const $td = $fila.find("td");
                    //   0: ID
                    //   1: Minutos
                    //   2: Fecha y Hora
                    //   3: Disponible
                    //   4: Agenda (Fecha)
                    //   5: Doctor
                    $td.eq(1).text(minutos);
                    $td.eq(2).text(fechaHora);
                    $td.eq(3).text(disponibleTexto);
                    $td.eq(4).text(agendaTexto);
                    $td.eq(5).text(doctorTexto);
                }

                // 4) Cerrar modal
                const modalEl = $f.closest('.modal')[0];
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                }
            }
        });
    });

    // ==========================================================
    // 3) EDITAR TURNOS ASIGNADOS A PACIENTES (tabla agenda_turno)
    //    Formularios con id="formEditarTurnoAsignado_..."
    // ==========================================================
    $(document).on('submit', "form[id^='formEditarTurnoAsignado_']", function (e) {
        e.preventDefault();
        const $form = $(this);

        enviarFormularioAjax($form, {
            successMessage: 'Turno asignado actualizado correctamente.',
            onSuccess: function (resp, $f) {
                const idAgendaTurno = $f.find("input[name='id_agenda_turno']").val();

                const $fila = $("#panel-turnos-pacientes tbody tr").filter(function () {
                    return $(this).find("td").eq(0).text().trim() === idAgendaTurno;
                });

                if ($fila.length) {
                    const pacienteTexto = $f.find("select[name='paciente_id_paciente'] option:selected").text();
                    const minutos       = $f.find("input[name='minutos_turnos']").val();
                    const estadoTexto   = $f.find("select[name='estados_id_estados'] option:selected").text();
                    const turnoTexto    = $f.find("select[name='turno_id'] option:selected").text();

                    const $td = $fila.find("td");
                    //   0: ID Agenda Turno
                    //   1: Paciente
                    //   2: Fecha y Hora
                    //   3: Minutos
                    //   4: Estado
                    $td.eq(1).text(pacienteTexto);
                    $td.eq(2).text(turnoTexto);
                    $td.eq(3).text(minutos);
                    $td.eq(4).text(estadoTexto);
                }

                const modalEl = $f.closest('.modal')[0];
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();
                }
            }
        });
    });

    // ==========================================================
    // 4) ELIMINAR
    //    - Turno disponible -> action = eliminacion
    //    - Turno asignado  -> action = eliminar
    //    Confirmamos con SweetAlert2 y, si ok, borramos la fila y mostramos toast.
    // ==========================================================
    $(document).on('submit', 'form', function (e) {
        const $form = $(this);
        const accion = $form.find("input[name='action']").val();

        if (accion === 'eliminacion' || accion === 'eliminar') {
            e.preventDefault();

            const esAsignado = (accion === 'eliminar');

            Swal.fire({
                title: '¿Estás seguro?',
                text: esAsignado
                    ? 'Se eliminará el turno asignado y el turno quedará disponible.'
                    : 'Se eliminará este turno disponible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                enviarFormularioAjax($form, {
                    successMessage: esAsignado
                        ? 'Turno asignado eliminado correctamente.'
                        : 'Turno disponible eliminado correctamente.',
                    onSuccess: function (resp, $f) {
                        const $fila = $f.closest('tr');
                        if ($fila.length) {
                            $fila.remove();
                        }
                    }
                });
            });
        }
    });

});
