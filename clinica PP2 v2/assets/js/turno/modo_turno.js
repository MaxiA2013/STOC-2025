$(document).ready(function() {
        $('#panel-turnos').DataTable();
        $('#panel-turnos-pacientes').DataTable();
    });

    function actualizarModoTurno() {
        const modoAgregar = document.getElementById("modo_agregar").checked;
        const divManual = document.getElementById("div_datetime_input");
        const divDisponibles = document.getElementById("div_select_turnos");
        const pacis = document.getElementById("div_select_paciente");

        if (modoAgregar) {
            divManual.style.display = "block";
            divDisponibles.style.display = "none";
            pacis.style.display = "none"; /*arreglar*/
        } else {
            divManual.style.display = "none";
            divDisponibles.style.display = "block";
            pacis.style.display = "block";
        }
    }

    // Ejecutar al cargar la página
    document.addEventListener("DOMContentLoaded", actualizarModoTurno);

    // Detectar cambios en los radio buttons
    document.getElementById("modo_agregar").addEventListener("change", actualizarModoTurno);
    document.getElementById("modo_asignar").addEventListener("change", actualizarModoTurno);
