// ---- Manejo AJAX para modal de editar turnos asignados ----
//lo tuve que separar porque me costo horrores que ande, no andaba pero con esto anduvo
(function ($) {

    'use strict';


    // ==========================================================
    // FORMATEAR FECHA
    // ==========================================================

    function formatearFechaHora(fecha) {

        if (!fecha) {
            return '';
        }

        const d = new Date(fecha);

        if (isNaN(d.getTime())) {
            return fecha;
        }

        const dd = String(d.getDate()).padStart(2, '0');
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const yyyy = d.getFullYear();

        const hh = String(d.getHours()).padStart(2, '0');
        const min = String(d.getMinutes()).padStart(2, '0');

        return `${dd}/${mm}/${yyyy} ${hh}:${min}`;
    }


    // ==========================================================
    // TRAER AGENDAS
    // ==========================================================
    function poblarAgendas($agendaSelect, agendas, selectedId) {

        $agendaSelect.empty();

        $agendaSelect.append(
            '<option value="">Seleccione una agenda</option>'
        );

        if (!Array.isArray(agendas)) {
            return;
        }

        agendas.forEach(function (agenda) {

            const id = agenda.id_agenda;

            const texto = agenda.agenda_desc
                ? agenda.agenda_desc
                : (
                    id +
                    ' - ' +
                    (agenda.fecha_desde || '')
                );

            const $option = $('<option>')
                .val(id)
                .text(texto);

            if (
                selectedId &&
                String(id) === String(selectedId)
            ) {
                $option.prop('selected', true);
            }

            $agendaSelect.append($option);
        });

        $agendaSelect.trigger('change');
    }


    // ==========================================================
    // TRAER TURNOS
    // ==========================================================
    function poblarTurnos($turnoSelect, turnos, selectedId) {

        $turnoSelect.empty();

        $turnoSelect.append(
            '<option value="">Seleccione un turno</option>'
        );

        if (!Array.isArray(turnos)) {
            return;
        }

        turnos.forEach(function (turno) {

            const id = turno.id_turnos;

            const texto = turno.fecha_hora_fmt
                ? turno.fecha_hora_fmt
                : formatearFechaHora(turno.fecha_hora);

            const $option = $('<option>')
                .val(id)
                .text(texto);

            if (
                selectedId &&
                String(id) === String(selectedId)
            ) {
                $option.prop('selected', true);
            }

            $turnoSelect.append($option);
        });

        $turnoSelect.trigger('change');
    }


    // ==========================================================
    // MODAL ABIERTO
    // ==========================================================

    $(document).on('shown.bs.modal', '.modal', function () {

        const $modal = $(this);


        // ======================================================
        // SELECTS DEL MODAL
        // ======================================================

        const $doctorSelect = $modal.find('.select2-doctor');

        const $agendaSelect = $modal.find(
            '.agenda-select-edit, .agenda-select'
        );

        const $turnoSelect = $modal.find('.select2-turnos');


        // ======================================================
        // SELECT2 DOCTOR
        // ======================================================

        $doctorSelect.each(function () {

            const $select = $(this);

            if (!$select.hasClass('select2-hidden-accessible')) {

                $select.select2({
                    dropdownParent: $modal,
                    width: '100%'
                });
            }
        });


        // ======================================================
        // SELECT2 AGENDA
        // ======================================================

        $agendaSelect.each(function () {

            const $select = $(this);

            if (!$select.hasClass('select2-hidden-accessible')) {

                $select.select2({
                    dropdownParent: $modal,
                    width: '100%'
                });
            }
        });


        // ======================================================
        // SELECT2 TURNO
        // ======================================================

        $turnoSelect.each(function () {

            const $select = $(this);

            if (!$select.hasClass('select2-hidden-accessible')) {

                $select.select2({
                    dropdownParent: $modal,
                    width: '100%'
                });
            }
        });


        // ======================================================
        // DOCTOR → AGENDAS
        // ======================================================

        $doctorSelect
            .off('change.modalDoctor')
            .on('change.modalDoctor', function () {

                const doctorId = $(this).val();

                if (!doctorId) {

                    $agendaSelect
                        .empty()
                        .append(
                            '<option value="">Seleccione una agenda</option>'
                        )
                        .trigger('change');

                    $turnoSelect
                        .empty()
                        .append(
                            '<option value="">Seleccione un turno</option>'
                        )
                        .trigger('change');

                    return;
                }


                // Guardar la agenda actual antes de reemplazarla
                const selectedAgenda =
                    $agendaSelect.val() ||
                    $agendaSelect.data('selected') ||
                    '';


                $.getJSON(
                    'controladores/turno/ajax_get_agenda.php',
                    {
                        doctor_id: doctorId
                    }
                )

                .done(function (response) {

                    const agendas = response.data || response;

                    poblarAgendas(
                        $agendaSelect,
                        agendas,
                        selectedAgenda
                    );
                })

                .fail(function (xhr) {

                    console.error(
                        'Error al cargar agendas:',
                        xhr.responseText
                    );

                    if (typeof handleAjaxError === 'function') {

                        handleAjaxError(
                            $modal,
                            'No se pudieron cargar las agendas.',
                            xhr.statusText
                        );
                    }
                });
            });


        // ======================================================
        // AGENDA → TURNOS
        // ======================================================

        $agendaSelect
            .off('change.modalAgenda')
            .on('change.modalAgenda', function () {

                const agendaId = $(this).val();

                if (!agendaId) {

                    $turnoSelect
                        .empty()
                        .append(
                            '<option value="">Seleccione un turno</option>'
                        )
                        .trigger('change');

                    return;
                }


                // Guardar turno actual
                const selectedTurno =
                    $turnoSelect.val() ||
                    $turnoSelect.data('selected') ||
                    '';


                $.getJSON(
                    'controladores/turno/ajax_get_turnos_por_agenda.php',
                    {
                        id_agenda: agendaId
                    }
                )

                .done(function (response) {

                    const turnos = response.data || response;

                    if (!Array.isArray(turnos)) {

                        console.error(
                            'Respuesta inesperada de turnos:',
                            response
                        );

                        return;
                    }


                    const turnosFormateados = turnos.map(
                        function (turno) {

                            return {
                                id_turnos: turno.id_turnos,
                                fecha_hora: turno.fecha_hora,
                                fecha_hora_fmt:
                                    formatearFechaHora(
                                        turno.fecha_hora
                                    )
                            };
                        }
                    );


                    poblarTurnos(
                        $turnoSelect,
                        turnosFormateados,
                        selectedTurno
                    );
                })

                .fail(function (xhr) {

                    console.error(
                        'Error al cargar turnos:',
                        xhr.responseText
                    );

                    if (typeof handleAjaxError === 'function') {

                        handleAjaxError(
                            $modal,
                            'No se pudieron cargar los turnos.',
                            xhr.statusText
                        );
                    }
                });
            });


        // ======================================================
        // CARGA INICIAL
        // ======================================================

        const initialDoctor = $doctorSelect.val();

        if (initialDoctor) {

            const initialAgenda =
                $agendaSelect.val() ||
                $agendaSelect.find('option:selected').val() ||
                '';

            const initialTurno =
                $turnoSelect.val() ||
                $turnoSelect.find('option:selected').val() ||
                '';


            if (initialAgenda) {
                $agendaSelect.data(
                    'selected',
                    initialAgenda
                );
            }

            if (initialTurno) {
                $turnoSelect.data(
                    'selected',
                    initialTurno
                );
            }


            $doctorSelect.trigger('change');
        }

    });


})(jQuery);