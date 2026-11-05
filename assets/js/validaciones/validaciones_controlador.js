// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()

// 👁️ Toggle de visibilidad de contraseña
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
      const target = document.getElementById(this.dataset.target);
      if (!target) return; // seguridad: si no encuentra el input, no hace nada

      if (target.type === "password") {
        target.type = "text";
        this.textContent = "🙈"; // cambia el ícono
      } else {
        target.type = "password";
        this.textContent = "👁️";
      }
    });
  });
});
