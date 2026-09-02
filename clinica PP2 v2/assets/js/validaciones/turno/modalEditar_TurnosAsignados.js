// ---- Manejo AJAX para modal de editar turnos asignados ----
//lo tuve que separar porque me costo horrores que ande, no andaba pero con esto anduvo

(function($){
    let modal = $(this);

    let doctorSelect = modal.find(".select2-doctor");
    let agendaSelect = modal.find(".agenda-select-edit");
    let turnoSelect = modal.find(".select2-turnos");


    // Función para poblar agendas en un select <select> con array de agendas
    function poblarAgendas($agendaSelect, agendas, selectedId) {
        $agendaSelect.empty();
        $agendaSelect.append('<option value="">Seleccione una agenda</option>');
        agendas.forEach(a => 
            {
            // Ajusta propiedades según lo que devuelva tu ajax (id_agenda, fecha_desde, hora_desde...)
            const text = (a.agenda_desc) ? a.agenda_desc : (a.id_agenda + ' - ' + (a.fecha_desde || ''));
            const $opt = $('<option>').val(a.id_agenda || a.id_agenda).text(text);
            if (String(a.id_agenda) === String(selectedId)) $opt.prop('selected', true);
            $agendaSelect.append($opt);
        });
        // trigger change para que cargue turnos si hace falta
        $agendaSelect.trigger('change');
    }

    // Función para poblar turnos en select
    function poblarTurnos($turnoSelect, turnos, selectedId) {
        $turnoSelect.empty();
        $turnoSelect.append('<option value="">Seleccione un turno</option>');
        turnos.forEach(t => 
            {
            const text = (t.fecha_hora_fmt) ? t.fecha_hora_fmt : (t.fecha_hora);
            const $opt = $('<option>').val(t.id_turnos || t.id_turnos).text(text);
            if (String(t.id_turnos) === String(selectedId)) $opt.prop('selected', true);
            $turnoSelect.append($opt);
        });
        $turnoSelect.trigger('change');
    }

    // Detectar apertura de cualquier modal dinámico (los tuyos usan .modal)
    $(document).on('shown.bs.modal', '.modal', function (e) {
        const $modal = $(this);

        // Sólo actuar si el modal contiene selects que necesitamos
        const $doctorSelect = $modal.find('.select2-doctor');
        const $agendaSelect = $modal.find('.agenda-select-edit, .agenda-select');
        const $turnoSelect  = $modal.find('.select2-turnos');

        // Inicializar select2 dentro del modal si no está inicializado
        if ($doctorSelect.length) {
            $doctorSelect.each(function(){
                // dropdownParent evita que select2 salga detrás del modal
                if (!$(this).hasClass('select2-initialized')) 
                    {
                    $(this).select2({ dropdownParent: $modal, width: '100%' });
                    $(this).addClass('select2-initialized');
                }
            });
        }
        if ($agendaSelect.length) {
            $agendaSelect.each(function(){
                if (!$(this).hasClass('select2-initialized')) 
                    {
                    $(this).select2({ dropdownParent: $modal, width: '100%' });
                    $(this).addClass('select2-initialized');
                }
            });
        }
        if ($turnoSelect.length) 
            {
            $turnoSelect.each(function(){
                if (!$(this).hasClass('select2-initialized')) 
                    {
                    $(this).select2({ dropdownParent: $modal, width: '100%' });
                    $(this).addClass('select2-initialized');
                }
            });
        }

        // --- Si hay doctorSelect: cuando cambie, cargar agendas ---
        $doctorSelect.off('change.modalDoctor').on('change.modalDoctor', function(){
            const doctorId = $(this).val();
            if (!doctorId) {
                // limpiar agendas y turnos
                $agendaSelect.empty().append('<option value="">Seleccione agenda</option>').trigger('change');
                $turnoSelect.empty().append('<option value="">Seleccione turno</option>').trigger('change');
                return;
            }

            // ajustar ruta según tu estructura: aquí usamos controladores/turno/ajax_get_agenda.php
            $.getJSON('controladores/turno/ajax_get_agenda.php', { doctor_id: doctorId })
                .done(function(res){
                    // tu ajax devuelve {status:'success', data: [...] } o {data: [...]}
                    const agendas = res.data || res;
                    // Si agendaSelect tiene ya una opción seleccionada (del turno actual), intenta respetarla
                    const existingAgenda = $agendaSelect.data('selected') || $agendaSelect.find('option[selected]').val();
                    poblarAgendas($agendaSelect, agendas, existingAgenda);
                })
                .fail(function(xhr, status, err){
                    handleAjaxError($modal, 'No se pudo cargar agendas (ver consola).', err);
                });
        });

        // --- Si hay agendaSelect: cuando cambie, cargar turnos de esa agenda ---
        $agendaSelect.off('change.modalAgenda').on('change.modalAgenda', function(){
            const agendaId = $(this).val();
            if (!agendaId) {
                $turnoSelect.empty().append('<option value="">Seleccione turno</option>').trigger('change');
                return;
            }
            $.getJSON('controladores/turno/ajax_get_turnos_por_agenda.php', { id_agenda: agendaId })
                .done(function(res){
                    const turnos = (res.data) ? res.data : (res);
                    // formatea fecha si tu endpoint no ya lo hace
                    const formatted = turnos.map(t => {
                        return {
                            id_turnos: t.id_turnos || t.id_turnos,
                            fecha_hora: t.fecha_hora,
                            fecha_hora_fmt: (function(f){
                                try {
                                    const d = new Date(f);
                                    if (isNaN(d)) return f;
                                    // dd/mm/YYYY HH:MM
                                    const dd = String(d.getDate()).padStart(2,'0');
                                    const mm = String(d.getMonth()+1).padStart(2,'0');
                                    const yyyy = d.getFullYear();
                                    const hh = String(d.getHours()).padStart(2,'0');
                                    const min = String(d.getMinutes()).padStart(2,'0');
                                    return `${dd}/${mm}/${yyyy} ${hh}:${min}`;
                                } catch(e){ return t.fecha_hora; }
                            })(t.fecha_hora)
                        };
                    });
                    // si el modal tenía un turno seleccionado, respetarlo:
                    const existingTurno = $turnoSelect.data('selected') || $turnoSelect.find('option[selected]').val();
                    poblarTurnos($turnoSelect, formatted, existingTurno);
                })
                .fail(function(xhr, status, err){
                    handleAjaxError($modal, 'No se pudieron obtener los turnos. Ver consola.', err);
                });
        });

        //Al abrir el modal: disparar carga inicial si el doctor/agenda ya está presente
        //Si el modal trae doctor seleccionado, se fuerza para cargar agendas
        const initialDoctor = $doctorSelect.val();
        if (initialDoctor) {
            //marcar agendaSelect con su id actual (para que la función de carga lo respete)
            const initialAgenda = $agendaSelect.find('option[selected]').val() || $agendaSelect.find('option').val();
            if (initialAgenda) $agendaSelect.data('selected', initialAgenda);
            //disparar carga agendas -> esto rellenará agendaSelect y disparará cambio que cargará turnos
            $doctorSelect.trigger('change');
        } else {
            //si doctor no seleccionado, igualmente podríamos cargar todos los doctores pero en tu HTML ya están
            //en caso de necesitarlo, podríamos hacer AJAX aquí.
        }
    });

})(jQuery);