/* validarDoctor.js 
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formDoctor");

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    // 1. Validar campos vacíos
    const campos = form.querySelectorAll("input, select, textarea");
    let camposVacios = [];

    campos.forEach((campo) => {
      if (campo.value.trim() === "") {
        campo.classList.add("is-invalid");
        camposVacios.push(campo);
      } else {
        campo.classList.remove("is-invalid");
      }
    });

    if (camposVacios.length > 0) {
      alert("Por favor complete todos los campos obligatorios.");
      return;
    }

    // 2. Validar duplicados de matrícula y usuario vía AJAX
    const matricula = document.getElementById("numero_matricula_profesional").value;
    const usuario = document.getElementById("usuario_id_usuario").value;

    try {
      const respuesta = await fetch("controladores/doctor_controlador.php?action=validarDuplicados", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ matricula, usuario })
      });

      const data = await respuesta.json();

      if (data.matriculaDuplicada) {
        alert("El número de matrícula profesional ya está registrado.");
        document.getElementById("numero_matricula_profesional").classList.add("is-invalid");
        return;
      }

      if (data.usuarioDuplicado) {
        alert("El usuario ya tiene un doctor asignado.");
        document.getElementById("usuario_id_usuario").classList.add("is-invalid");
        return;
      }

      // 3. Si todo está OK, enviamos el formulario
      form.submit();

    } catch (error) {
      console.error("Error en la validación:", error);
      alert("Ocurrió un error al validar los datos.");
    }
  });
}); */
