$(function(){
    // Inicializar select2 para doctores y pacientes
    $(".select2-doctor").select2();
    $(".select2-paciente").select2({ width: '100%', placeholder: 'Buscar paciente...' });
    $(".select2-turnos").select2({ width: '100%' });

    // Cargar agendas por doctor (igual a tu función anterior)
    function cargarAgendasParaDoctor(doctorId, $agendaSelect, selectAgendaId = null) {
        $agendaSelect.html('<option value="">Cargando...</option>');
        if (!doctorId) {
            $agendaSelect.html('<option value="">Seleccione primero un doctor</option>');
            return;
        }
        fetch('controladores/turno/ajax_get_agenda.php?doctor_id=' + doctorId)
            .then(resp => resp.json())
            .then(data => {
                // Esperamos array de agendas: [{id_agenda, fecha_agenda, hora_desde, hora_hasta}]
                $agendaSelect.empty();
                $agendaSelect.append('<option value="">Seleccione una agenda</option>');
                data.data.forEach(function(a){
                    console.log(a);
                    const text = a.id_agenda + ' - ' +  '(' + a.fecha_desde + ' / ' + a.fecha_hasta + ')' + ' - ' + ' (' + a.hora_desde + '-' + a.hora_hasta + ')';
                    const opt = $('<option>').val(a.id_agenda).text(text)
                                             .attr('data-desde', a.hora_desde)
                                             .attr('data-hasta', a.hora_hasta)
                                             .attr('data-fecha', a.fecha_agenda);
                    $agendaSelect.append(opt);
                });
                if (selectAgendaId) $agendaSelect.val(selectAgendaId);
            })
            .catch(err => {
                console.error('Error al cargar agendas:', err);
                $agendaSelect.html('<option value="">Error al cargar agendas</option>');
            });
    }

    // Cargar turnos disponibles por agenda (retorna lista de objetos con id_turnos y fecha_hora)
    function cargarTurnosDisponibles(agendaId, $selectTurnos) {
        $selectTurnos.html('<option value="">Cargando...</option>');
        if (!agendaId) {
            $selectTurnos.html('<option value="">Seleccione una agenda primero</option>');
            return;
        }
        fetch('controladores/turno/ajax_get_turnos_por_agenda.php?id_agenda=' + agendaId)
            .then(resp => resp.json())
            .then(data => {
                console.log('turnos');
                console.log(data);
                // data: [{id_turnos, fecha_hora, minutos_turnos, disponible}, ...]
                $selectTurnos.empty();
                $selectTurnos.append('<option value="">Seleccione un turno</option>');
                data.data.forEach(function(t){
                    // mostrar texto legible con fecha y hora
                    const text = t.fecha_hora + ' (' + t.minutos_turnos + ' min) ' + (t.disponible == 1 ? '' : '[ocupado]');
                    const opt = $('<option>').val(t.id_turnos).text(text)
                                             .attr('data-fecha', t.fecha_hora)
                                             .attr('data-minutos', t.minutos_turnos)
                                             .attr('data-disponible', t.disponible);
                    $selectTurnos.append(opt);
                });
                // re-inicializar select2
                $selectTurnos.trigger('change');
            })
            .catch(err => {
                console.error('Error al cargar turnos:', err);
                $selectTurnos.html('<option value="">Error al cargar turnos</option>');
            });
    }

    // Cuando cambia doctor en formulario principal
    $('#doctorSelect').on('change', function(){
        const doctorId = $(this).val();
        cargarAgendasParaDoctor(doctorId, $('#agendaSelect'));
        // si está en modo asignar y ya hay agenda seleccionada cargar turnos
        if ($('input[name="modo_turno"]:checked').val() === 'asignar') {
            // esperar a que usuario seleccione agenda
            $('#select_turnos_disponibles').html('<option value="">Seleccione una agenda</option>');
        }
    });

    // Cuando cambia agenda en formulario principal
    $('#agendaSelect').on('change', function(){
        const agendaId = $(this).val();
        if ($('input[name="modo_turno"]:checked').val() === 'asignar') {
            cargarTurnosDisponibles(agendaId, $('#select_turnos_disponibles'));
        }
    });

    // Toggle entre agregar / asignar
    $('input[name="modo_turno"]').on('change', function(){
        const modo = $(this).val();
        if (modo === 'asignar') {
            $('#div_datetime_input').addClass('d-none');
            $('#div_select_turnos').removeClass('d-none');
            // cargar si hay agenda seleccionada
            const aid = $('#agendaSelect').val();
            cargarTurnosDisponibles(aid, $('#select_turnos_disponibles'));
        } else {
            $('#div_select_turnos').addClass('d-none');
            $('#div_datetime_input').removeClass('d-none');
        }
    });

    // Si el usuario elige un turno disponible, copiar su fecha/hora al input oculto (para enviar coherente)
    $('#select_turnos_disponibles').on('change', function(){
        const sel = $(this).find('option:selected');
        const fecha = sel.attr('data-fecha') || '';
        const minutos = sel.attr('data-minutos') || '';
        // Si queremos, asignamos al input datetime oculto (o usamos turno_existente_id en backend)
        if (fecha) {
            // convertir a formato datetime-local 'YYYY-MM-DDTHH:MM'
            const fh = new Date(fecha);
            if (!isNaN(fh)) {
                const yyyy = fh.getFullYear();
                const mm = String(fh.getMonth()+1).padStart(2,'0');
                const dd = String(fh.getDate()).padStart(2,'0');
                const hh = String(fh.getHours()).padStart(2,'0');
                const mi = String(fh.getMinutes()).padStart(2,'0');
                $('#input_fecha_hora').val(`${yyyy}-${mm}-${dd}T${hh}:${mi}`);
            }
            if (minutos) $('#minutos_turnos').val(minutos);
        }
    });

    // selección paciente
    $('#div_select_paciente').on('click', function(){
        // abrir modal y cargar pacientes por AJAX si está vacío
        const $select = $('#select_pacientes');
        if ($select.children().length <= 1) {
            $select.html('<option value="">Cargando...</option>');
            fetch('controladores/turno/ajax_get_pacientes.php')
                .then(resp => resp.json())
                .then(data => {
                    $select.empty();
                    $select.append('<option value="">Seleccione a un paciente</option>');
                    data.data.forEach(function(p){
                        const txt = p.nombre + ' ' + p.apellido + ' (' + (p.nombre_usuario || '') + ')';
                        $select.append($('<option>').val(p.id_paciente).text(txt));
                    });
                })
                .catch(err => {
                    console.error('Error al cargar pacientes:', err);
                    $select.html('<option value="">Error al cargar pacientes</option>');
                });
        }
    });

    $('#btnAceptarPaciente').on('click', function(){
        const $sel = $('#selectPaciente');
        const id = $sel.val();
        const text = $sel.find('option:selected').text();
        if (!id) {
            alert('Seleccione un paciente.');
            return;
        }
        $('#paciente_id').val(id);
        $('#paciente_label').val(text);
        $('#modalPaciente').modal('hide');
    });


    // SUBMIT formulario alta
    $('#formAgregarTurno').on('submit', function(e){
        e.preventDefault();

        const modo = $('input[name="modo_turno"]:checked').val();
        const data = $(this).serializeArray();

        // si modo asignar, asegurar que turno seleccionado o agenda + turno disponible
        if (modo === 'asignar') {
            const turnoSel = $('#select_turnos_disponibles').val();
            if (!turnoSel) {
                alert('Seleccione un turno disponible de la lista (Asignar turno).');
                return;
            }
        } else {
            // modo agregar: validar datetime
            const fh = $('#input_fecha_hora').val();
            if (!fh) { alert('Seleccione fecha y hora'); return; }
        }

        // enviar por AJAX (POST)
        $.ajax({
            url: 'controladores/turno/turno_controlador.php',
            method: 'POST',
            data: $(this).serialize(), 
            dataType: 'json',
            success: function(resp){
                console.log(resp);
                if (!resp) {
                    alert('Respuesta vacía del servidor.');
                    return;
                }
                if (resp.success) {
                    alert('Turno creado/actualizado correctamente.');
                    location.reload();
                } else {
                    alert('Error: ' + (resp.error || 'No se pudo crear turno.'));
                }
            },
            error: function(xhr, status, err){
                console.error('Error AJAX alta turno:', status, err, xhr.responseText);
                alert('Error al guardar (ver consola).');
            }
        });

    });

    // ============================
    // MODALES: cargar agendas al abrir modal editar y re-inicializar select2
    // ============================
    $(document).on('show.bs.modal', '.modal', function(e){
        const $modal = $(this);
        // si tiene doctor-select dentro -> inicializar select2 y poblar agendas cuando cambie
        $modal.find('.modal-doctor-select').each(function(){
            const $doc = $(this);
            $doc.select2({ dropdownParent: $modal, width: '100%' });
            $doc.off('change.modalDoc').on('change.modalDoc', function(){
                const doctorId = $doc.val();
                const $agendaSelect = $modal.find('.modal-agenda-select');
                cargarAgendasParaDoctor(doctorId, $agendaSelect);
            });
        });
        // los selects ya existentes de agenda pueden inicializar select2
        $modal.find('.modal-agenda-select').select2({ dropdownParent: $modal, width: '100%' });
    });

    // Inicialmente inicializamos select2 para selects que no son modal
    $('#agendaSelect').select2({ width: '100%' });
    $('#select_turnos_disponibles').select2({ width: '100%' });
});