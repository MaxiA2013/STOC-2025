// SELECT2 + AJAX
(function () {
  /* Inicializar Select2*/
  $("#usuario_id_usuario").select2({
    placeholder: "Buscar usuario por nombre o perfil...",
    width: "100%",
    allowClear: true,
  });

  /*Abrir modal para nuevo usuario*/
  $("#usuario_id_usuario").on("change", function () {
    var val = $(this).val();
    if (val === "new_user") {
      var myModal = new bootstrap.Modal(
        document.getElementById("modalNewUser"),
      );
      myModal.show();

      $(this).val(null).trigger("change");
    }
  });

  /*Registro completo: Persona, Usuario, Doctor mediante AJAX.*/
  $("#modalNewUser").on("shown.bs.modal", function () {
    var $registroForm = $("#registroForm");

    /* Evitamos múltiples eventos*/
    $registroForm.off("submit.registrarDoctorModal");

    $registroForm.on("submit.registrarDoctorModal", function (e) {
      /*Se mantiene la lógica original*/
      var formData = new FormData(this);

      formData.set("action", "registrarCompleto");

      formData.append("from_lista_doctor", "1");

      /*Datos del doctor*/
      formData.append(
        "numero_matricula_profesional",
        $("#numero_matricula_profesional").val(),
      );

      formData.append("precio_consulta", $("#precio_consulta").val());

      /*Enviar al controlador AJAX*/
      fetch("controladores/doctor_ajax_controlador.php", {
        method: "POST",
        body: formData,
      })
        .then((r) => r.json())

        .then((resp) => {
          if (resp.status === "ok") {
            Swal.fire({
              title: "Guardado",
              text: "Usuario doctor creado correctamente.",
              icon: "success",
              timer: 1400,
              showConfirmButton: false,
            });

            /*Cerrar modal*/
            var myModalEl = document.getElementById("modalNewUser");
            var modal = bootstrap.Modal.getInstance(myModalEl);
            modal.hide();

            /*Actualizar listado*/
            setTimeout(function () {
              location.reload();
            }, 600);
          } else {
            Swal.fire("Error", resp.message || "Error en servidor", "error");
          }
        })

        .catch((err) => {
          console.error(err);
          Swal.fire("Error", "Error de red", "error");
        });
    });
  });
})();
