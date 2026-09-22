$(function () {
  // =====================================================
  // INICIALIZAR SELECT2
  // =====================================================

  $(".select2-doctor").select2({
    width: "100%",
  });

  $(".select2-paciente").select2({
    width: "100%",
    placeholder: "Buscar paciente...",
  });

  $(".select2-turnos").select2({
    width: "100%",
  });

  $(".select2-obra-social").select2({
    width: "100%",
    placeholder: "Seleccione obra social",
  });

  // =====================================================
  // ELEMENTOS DEL FORMULARIO PRINCIPAL
  // =====================================================

  const $divDatetime = $("#div_datetime_input");
  const $divTurnos = $("#div_select_turnos");
  const $divDisponible = $("#div_disponible_manual");
  const $divPaciente = $("#div_select_paciente_manual");

  const $paciente = $("#select_pacientes_manual");
  const $selectTurnos = $("#select_turnos_disponibles");
  const $fechaHora = $("#input_fecha_hora");

  const $divObraSocialToggle = $("#div_obra_social_toggle");
  const $divSelectObraSocial = $("#div_select_obra_social");
  const $selectObraSocial = $("#select_obra_social");

  // =====================================================
  // ACTUALIZAR VISIBILIDAD COMPLETA DEL FORMULARIO
  // SEGÚN EL MODO (agregar / asignar) Y LA DISPONIBILIDAD
  // =====================================================
  // Esta es la ÚNICA función que decide qué se muestra.
  // Se llama tanto en los eventos "change" como una vez
  // al cargar la página, para que el estado inicial
  // quede sincronizado con lo que el usuario ve.
  // =====================================================

  function actualizarVisibilidadFormulario() {
    const modo = $('input[name="modo_turno"]:checked').val();

    // -------------------------------------------------
    // MODO ASIGNAR TURNO (DISPONIBLES)
    // -------------------------------------------------
    // - No se pide fecha/hora manual (se toma del turno)
    // - No aplica Disponible Sí/No (el turno ya está
    //   disponible por definición)
    // - El paciente SIEMPRE se muestra y es obligatorio
    // -------------------------------------------------

    if (modo === "asignar") {
      // Ocultar fecha/hora manual
      $divDatetime.addClass("d-none");

      $fechaHora.prop("disabled", true).prop("required", false);

      // Mostrar selector de turnos disponibles
      $divTurnos.removeClass("d-none");

      $selectTurnos.prop("disabled", false).prop("required", true);

      // Ocultar disponibilidad manual, no aplica acá
      // (el turno que se asigna ya está disponible por
      // definición, así que "disponible" siempre es
      // implícitamente 1 en este modo)
      $divDisponible.addClass("d-none");

      $('input[name="disponible"]').prop("disabled", true);

      // Mostrar paciente siempre, sin condición
      $divPaciente.removeClass("d-none");

      $paciente.prop("disabled", false).prop("required", true);

      $divObraSocialToggle.removeClass("d-none");
      // Cargar turnos si ya hay una agenda seleccionada
      const agendaId = $("#agendaSelect").val();

      if (agendaId) {
        cargarTurnosDisponibles(agendaId, $selectTurnos);
      } else {
        $selectTurnos
          .html('<option value="">Seleccione una agenda primero</option>')
          .trigger("change");
      }

      return;
    }

    // -------------------------------------------------
    // MODO AGREGAR TURNO (MANUAL)
    // -------------------------------------------------

    // Mostrar fecha/hora manual
    $divDatetime.removeClass("d-none");

    $fechaHora.prop("disabled", false).prop("required", true);

    // Ocultar selector de turnos existentes, no aplica acá
    $divTurnos.addClass("d-none");

    $selectTurnos
      .prop("disabled", true)
      .prop("required", false)
      .val(null)
      .trigger("change");

    // Mostrar disponibilidad manual (Sí / No)
    $divDisponible.removeClass("d-none");

    $('input[name="disponible"]').prop("disabled", false);

    const disponible = $('input[name="disponible"]:checked').val();

    // ---------------------------------------------
    // MANUAL + DISPONIBLE = SÍ
    // Todavía no hay paciente que asignar
    // ---------------------------------------------
    if (disponible === "1") {
      $divPaciente.addClass("d-none");

      $paciente
        .prop("disabled", true)
        .prop("required", false)
        .val(null)
        .trigger("change");

      $divObraSocialToggle.addClass("d-none");
      $divSelectObraSocial.addClass("d-none");

      $('input[name="con_obra_social"][value="0"]').prop("checked", true);

      $selectObraSocial.prop("disabled", true).val(null).trigger("change");

      return;
    }

    // ---------------------------------------------
    // MANUAL + DISPONIBLE = NO
    // Paciente obligatorio
    // ---------------------------------------------

    $divPaciente.removeClass("d-none");

    $paciente.prop("disabled", false).prop("required", true);

    $divObraSocialToggle.removeClass("d-none");
  }

  // =====================================================
  // CAMBIO DE MODO
  // =====================================================

  $('#formAgregarTurno input[name="modo_turno"]').on(
    "change",
    actualizarVisibilidadFormulario,
  );

  // =====================================================
  // CAMBIO DE DISPONIBILIDAD (FORMULARIO PRINCIPAL)
  // Solo tiene efecto en modo manual.
  // Escopado a #formAgregarTurno para no interferir con
  // los radios "disponible" que viven dentro de los
  // modales de edición.
  // =====================================================

  $(document).on(
    "change",
    '#formAgregarTurno input[name="disponible"]',
    function () {
      if ($('input[name="modo_turno"]:checked').val() === "agregar") {
        actualizarVisibilidadFormulario();
      }
    },
  );

  // =====================================================
  // CAMBIO DE DISPONIBILIDAD (MODAL "EDITAR TURNO")
  // Tabla "Turnos Disponibles" -> modalEditar<id>
  // Si se marca "No", debe aparecer el select de paciente
  // para poder asignarlo en el mismo paso.
  // =====================================================
  function actualizarPacienteModalEditar($modal) {
    const disponible = $modal.find('input[name="disponible"]:checked').val();

    const $divPacienteModal = $modal.find(".div-paciente-modal");

    const $pacienteModal = $modal.find(".select2-paciente-modal");

    const $divObraSocialToggleModal = $modal.find(
      ".div-obra-social-toggle-modal",
    );

    const $divSelectObraSocialModal = $modal.find(
      ".div-select-obra-social-modal",
    );

    const $selectObraSocialModal = $modal.find(".select2-obra-social-modal");

    if (disponible === "0") {
      $divPacienteModal.removeClass("d-none");

      $pacienteModal.prop("disabled", false).prop("required", true);

      $divObraSocialToggleModal.removeClass("d-none");
    } else {
      $divPacienteModal.addClass("d-none");

      $pacienteModal
        .prop("disabled", true)
        .prop("required", false)
        .val(null)
        .trigger("change");

      $divObraSocialToggleModal.addClass("d-none");
      $divSelectObraSocialModal.addClass("d-none");

      $selectObraSocialModal.prop("disabled", true).val(null).trigger("change");
    }
  }

  function actualizarSelectObraSocialModal($modal) {
    const conObraSocial = $modal
      .find('input[name="con_obra_social"]:checked')
      .val();

    const $divSelectObraSocialModal = $modal.find(
      ".div-select-obra-social-modal",
    );

    const $selectObraSocialModal = $modal.find(".select2-obra-social-modal");

    if (conObraSocial !== "1") {
      $divSelectObraSocialModal.addClass("d-none");

      $selectObraSocialModal
        .prop("disabled", true)
        .prop("required", false)
        .val(null)
        .trigger("change");

      return;
    }

    $divSelectObraSocialModal.removeClass("d-none");

    $selectObraSocialModal.prop("disabled", false).prop("required", true);

    // Doctor y Paciente pueden vivir en selects con distintas
    // clases según el modal ("editar disponible" vs "editar
    // asignado"), así que se buscan ambos patrones.
    const doctorId = $modal.find(".modal-doctor-select, .select2-doctor").val();

    const pacienteId = $modal
      .find(".select2-paciente-modal, .select2-pacientes")
      .val();

    // Preservar la obra social ya cargada (si la había) para
    // que no se pierda al reabrir el modal o al reinicializar
    // el listado de opciones.
    const selectedId =
      $selectObraSocialModal.val() ||
      $selectObraSocialModal.data("selected") ||
      "";

    cargarObrasSocialesCompatibles(
      doctorId,
      pacienteId,
      $selectObraSocialModal,
      selectedId,
    );
  }
  $(document).on("change", '.modal input[name="con_obra_social"]', function () {
    actualizarSelectObraSocialModal($(this).closest(".modal"));
  });

      $(document).on(
        "change",
        ".modal .modal-doctor-select, .modal .select2-paciente-modal, .modal .select2-doctor, .modal .select2-pacientes",
        function () {

            const $modal = $(this).closest(".modal");

            if ($modal.find('input[name="con_obra_social"]:checked').val() === "1") {
                actualizarSelectObraSocialModal($modal);
            }

        }
    );

  $(document).on("change", '.modal input[name="disponible"]', function () {
    const $modal = $(this).closest(".modal");

    actualizarPacienteModalEditar($modal);
  });

  // =====================================================
  // OBRA SOCIAL (FORMULARIO PRINCIPAL)
  // =====================================================

  function actualizarSelectObraSocial() {
    const conObraSocial = $(
      '#formAgregarTurno input[name="con_obra_social"]:checked',
    ).val();

    if (conObraSocial !== "1") {
      $divSelectObraSocial.addClass("d-none");

      $selectObraSocial
        .prop("disabled", true)
        .prop("required", false)
        .val(null)
        .trigger("change");

      return;
    }

    $divSelectObraSocial.removeClass("d-none");

    $selectObraSocial.prop("disabled", false).prop("required", true);

    cargarObrasSocialesCompatibles(
      $("#doctorSelect").val(),
      $paciente.val(),
      $selectObraSocial,
    );
  }

  $(document).on(
    "change",
    '#formAgregarTurno input[name="con_obra_social"]',
    actualizarSelectObraSocial,
  );

  // Si cambia el doctor o el paciente mientras "con_obra_social"
  // está en "Sí", hay que recargar las opciones compatibles.
  $(document).on("change", "#doctorSelect", function () {
    if (
      $('#formAgregarTurno input[name="con_obra_social"]:checked').val() === "1"
    ) {
      actualizarSelectObraSocial();
    }
  });

  $(document).on("change", "#select_pacientes_manual", function () {
    if (
      $('#formAgregarTurno input[name="con_obra_social"]:checked').val() === "1"
    ) {
      actualizarSelectObraSocial();
    }
  });

  // =====================================================
  // CARGAR AGENDAS POR DOCTOR
  // =====================================================

  function cargarAgendasParaDoctor(
    doctorId,
    $agendaSelect,
    selectAgendaId = null,
  ) {
    $agendaSelect.html('<option value="">Cargando...</option>');

    if (!doctorId) {
      $agendaSelect.html(
        '<option value="">Seleccione primero un doctor</option>',
      );

      return;
    }

    fetch(
      "controladores/turno/ajax_get_agenda.php?doctor_id=" +
        encodeURIComponent(doctorId),
    )
      .then((resp) => resp.json())

      .then((data) => {
        $agendaSelect.empty();

        $agendaSelect.append('<option value="">Seleccione una agenda</option>');

        if (data.data && Array.isArray(data.data)) {
          data.data.forEach(function (a) {
            const text =
              a.id_agenda +
              " - (" +
              a.fecha_desde +
              " / " +
              a.fecha_hasta +
              ") - (" +
              a.hora_desde +
              "-" +
              a.hora_hasta +
              ")";

            const opt = $("<option>")
              .val(a.id_agenda)
              .text(text)
              .attr("data-desde", a.hora_desde)
              .attr("data-hasta", a.hora_hasta)
              .attr("data-fecha", a.fecha_agenda);

            $agendaSelect.append(opt);
          });
        }

        if (selectAgendaId) {
          $agendaSelect.val(selectAgendaId).trigger("change");
        }
      })

      .catch((err) => {
        console.error("Error al cargar agendas:", err);

        $agendaSelect.html('<option value="">Error al cargar agendas</option>');
      });
  }

  // =====================================================
  // CARGAR TURNOS DISPONIBLES
  // SOLO PARA MODO ASIGNAR
  // =====================================================

  function cargarTurnosDisponibles(agendaId, $selectTurnosDestino) {
    $selectTurnosDestino.html('<option value="">Cargando...</option>');

    if (!agendaId) {
      $selectTurnosDestino.html(
        '<option value="">Seleccione una agenda primero</option>',
      );

      $selectTurnosDestino.trigger("change");

      return;
    }

    fetch(
      "controladores/turno/ajax_get_turnos_por_agenda.php?id_agenda=" +
        encodeURIComponent(agendaId),
    )
      .then((resp) => resp.json())

      .then((data) => {
        $selectTurnosDestino.empty();

        $selectTurnosDestino.append(
          '<option value="">Seleccione un turno</option>',
        );

        if (data.data && Array.isArray(data.data)) {
          data.data.forEach(function (t) {
            // Solo mostrar turnos disponibles
            if (parseInt(t.disponible) !== 1) {
              return;
            }

            const text = t.fecha_hora + " (" + t.minutos_turnos + " min)";

            const opt = $("<option>")
              .val(t.id_turnos)
              .text(text)
              .attr("data-fecha", t.fecha_hora)
              .attr("data-minutos", t.minutos_turnos)
              .attr("data-disponible", t.disponible);

            $selectTurnosDestino.append(opt);
          });
        }

        $selectTurnosDestino.trigger("change");
      })

      .catch((err) => {
        console.error("Error al cargar turnos:", err);

        $selectTurnosDestino.html(
          '<option value="">Error al cargar turnos</option>',
        );

        $selectTurnosDestino.trigger("change");
      });
  }

  // =====================================================
  // CARGAR OBRAS SOCIALES COMPATIBLES (doctor + paciente)
  // =====================================================
  function cargarObrasSocialesCompatibles(
    doctorId,
    pacienteId,
    $selectDestino,
    selectedId = null,
  ) {
    $selectDestino.html('<option value="">Cargando...</option>');

    if (!doctorId || !pacienteId) {
      $selectDestino.html(
        '<option value="">Seleccione paciente y doctor primero</option>',
      );

      $selectDestino.trigger("change");

      return;
    }

    fetch(
      "controladores/turno/ajax_get_obras_sociales_compatibles.php?doctor_id=" +
        encodeURIComponent(doctorId) +
        "&paciente_id=" +
        encodeURIComponent(pacienteId),
    )
      .then((resp) => resp.json())

      .then((data) => {
        $selectDestino.empty();

        if (data.data && Array.isArray(data.data) && data.data.length > 0) {
          $selectDestino.append(
            '<option value="">Seleccione una obra social</option>',
          );

          data.data.forEach(function (os) {
            const $opt = $("<option>")
              .val(os.id_obra_social)
              .text(os.nombre_obra_social);

            if (
              selectedId &&
              String(os.id_obra_social) === String(selectedId)
            ) {
              $opt.prop("selected", true);
            }

            $selectDestino.append($opt);
          });
        } else {
          $selectDestino.append(
            '<option value="">Sin obras sociales en común</option>',
          );
        }

        $selectDestino.trigger("change");
      })

      .catch((err) => {
        console.error("Error al cargar obras sociales:", err);

        $selectDestino.html(
          '<option value="">Error al cargar obras sociales</option>',
        );

        $selectDestino.trigger("change");
      });
  }
  // =====================================================
  // CAMBIO DE DOCTOR
  // =====================================================

  $("#doctorSelect").on("change", function () {
    const doctorId = $(this).val();

    cargarAgendasParaDoctor(doctorId, $("#agendaSelect"));

    // Si estamos asignando, limpiar turnos
    if ($('input[name="modo_turno"]:checked').val() === "asignar") {
      $selectTurnos
        .html('<option value="">Seleccione una agenda</option>')
        .trigger("change");
    }
  });

  // =====================================================
  // CAMBIO DE AGENDA
  // =====================================================

  $("#agendaSelect").on("change", function () {
    const agendaId = $(this).val();

    if ($('input[name="modo_turno"]:checked').val() === "asignar") {
      cargarTurnosDisponibles(agendaId, $selectTurnos);
    }
  });

  // =====================================================
  // SELECCIONAR TURNO EXISTENTE
  // MODO ASIGNAR
  // =====================================================

  $selectTurnos.on("change", function () {
    const $option = $(this).find("option:selected");

    const fecha = $option.attr("data-fecha") || "";

    const minutos = $option.attr("data-minutos") || "";

    // Copiar minutos
    if (minutos) {
      $("#minutos_turnos").val(minutos);
    }

    // Copiar fecha/hora
    if (fecha) {
      const fh = new Date(fecha);

      if (!isNaN(fh.getTime())) {
        const yyyy = fh.getFullYear();

        const mm = String(fh.getMonth() + 1).padStart(2, "0");

        const dd = String(fh.getDate()).padStart(2, "0");

        const hh = String(fh.getHours()).padStart(2, "0");

        const mi = String(fh.getMinutes()).padStart(2, "0");

        $fechaHora.val(`${yyyy}-${mm}-${dd}T${hh}:${mi}`);
      }
    }
  });

  $(document).on("click", ".btn-ver-historial", function () {
    const idTurno = $(this).data("id-turno");
    const $lista = $("#listaHistorial");

    $lista.html('<li class="list-group-item text-muted">Cargando...</li>');

    const modal = new bootstrap.Modal(
      document.getElementById("modalHistorial"),
    );

    modal.show();

    fetch(
      "controladores/turno/ajax_get_historial.php?id_turno=" +
        encodeURIComponent(idTurno),
    )
      .then((resp) => resp.json())
      .then((data) => {
        if (!data.success || !data.data || data.data.length === 0) {
          $lista.html(
            '<li class="list-group-item text-muted">Sin cambios registrados.</li>',
          );
          return;
        }

        $lista.empty();

        data.data.forEach(function (item) {
          const perfilTexto = item.usuario_perfil
            ? ` (${item.usuario_perfil})`
            : "";

          const cambioTexto = item.campo_modificado
            ? `<br><small><strong>${item.campo_modificado}:</strong> ${item.valor_anterior ?? "—"} → ${item.valor_nuevo ?? "—"}</small>`
            : "";

          const detalleTexto = item.detalle
            ? `<br><small class="text-muted">${item.detalle}</small>`
            : "";

          $lista.append(`
                    <li class="list-group-item">
                        <strong>${item.accion}</strong>
                        <span class="text-muted"> — ${item.usuario_nombre}${perfilTexto}</span>
                        ${cambioTexto}
                        ${detalleTexto}
                        <br>
                        <small class="text-muted">${item.fecha_registro}</small>
                    </li>
                `);
        });
      })
      .catch(() => {
        $lista.html(
          '<li class="list-group-item text-danger">Error al cargar el historial.</li>',
        );
      });
  });

  // =====================================================
  // SELECT2 EN MODALES
  // =====================================================

  $(document).on("show.bs.modal", ".modal", function () {
    const $modal = $(this);

    // Doctor
    $modal.find(".modal-doctor-select").each(function () {
      const $doc = $(this);

      $doc.select2({
        dropdownParent: $modal,
        width: "100%",
      });

      $doc.off("change.modalDoc").on("change.modalDoc", function () {
        const doctorId = $doc.val();

        const $agendaSelect = $modal.find(".modal-agenda-select");

        cargarAgendasParaDoctor(doctorId, $agendaSelect);
      });
    });

    // Agenda
    $modal.find(".modal-agenda-select").select2({
      dropdownParent: $modal,
      width: "100%",
    });

    // Paciente (modal "Editar Turno Asignado")
    $modal.find(".select2-pacientes").select2({
      dropdownParent: $modal,
      width: "100%",
      placeholder: "Buscar paciente...",
    });

    // Paciente (modal "Editar Turno" - tabla Disponibles)
    $modal.find(".select2-paciente-modal").select2({
      dropdownParent: $modal,
      width: "100%",
      placeholder: "Buscar paciente...",
    });

                // Obra Social (modal "Editar Turno" - tabla Disponibles,
            // y también modal "Editar Turno Asignado")
            $modal
                .find(".select2-obra-social-modal")
                .select2({
                    dropdownParent: $modal,
                    width: "100%",
                    placeholder: "Seleccione obra social"
                });

            // Sincronizar visibilidad según el valor de
            // "disponible" que ya trae el turno guardado
            // (no-op si el modal no tiene radios "disponible",
            // como el modal de Turnos Asignados)
            actualizarPacienteModalEditar($modal);

            // Sincronizar el select de obra social según el
            // radio "con_obra_social" que ya trae el turno
            // (no-op si el modal no tiene ese radio)
            if ($modal.find('input[name="con_obra_social"]').length) {
                actualizarSelectObraSocialModal($modal);
            }
    });

  // =====================================================
  // SELECT2 DEL FORMULARIO PRINCIPAL
  // =====================================================

  $("#agendaSelect").select2({
    width: "100%",
  });

  $selectTurnos.select2({
    width: "100%",
  });

  // =====================================================
  // ESTADO INICIAL
  // =====================================================
  // Se llama una sola vez acá para que, al cargar la
  // página, el formulario respete el modo por defecto
  // ("agregar") en vez de mostrar todo mezclado hasta
  // que el usuario toque algún radio.
  // =====================================================

  actualizarVisibilidadFormulario();
});
