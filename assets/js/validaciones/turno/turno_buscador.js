document.addEventListener("DOMContentLoaded", function () 
{
    const campo = document.getElementById("campoTurno");
    const contenido = document.getElementById("contenidoTurnos");
    const limpiarBusqueda = document.getElementById("limpiarBusquedaTurno");
    const num_registros = document.getElementById("num_registros_turnos");
    const nav_paginacion = document.getElementById("nav_paginacion_turnos");

    if (
        !campo ||
        !contenido ||
        !limpiarBusqueda ||
        !num_registros ||
        !nav_paginacion
    ) {
        return;
    }

    // Página actual
    let paginaActual = 1;

    // CAMBIO DE CANTIDAD
    num_registros.addEventListener("change", function () {
        paginaActual = 1;
        getData(paginaActual);
    });

    // BUSCADOR
    campo.addEventListener("input", function () {

        if (campo.value.length > 0) {
            limpiarBusqueda.classList.remove("d-none");
        } else {
            limpiarBusqueda.classList.add("d-none");
        }


        paginaActual = 1;

        getData(paginaActual);

    });


    // ==========================================
    // LIMPIAR BÚSQUEDA
    // ==========================================

    limpiarBusqueda.addEventListener("click", function () {

        campo.value = "";

        limpiarBusqueda.classList.add("d-none");

        paginaActual = 1;

        getData(paginaActual);

        campo.focus();

    });


    // ==========================================
    // PAGINACIÓN
    // ==========================================

    nav_paginacion.addEventListener("click", function (e) {

        const boton = e.target.closest(".pagina");

        if (!boton) {
            return;
        }

        e.preventDefault();

        paginaActual = parseInt(
            boton.dataset.pagina
        );

        getData(paginaActual);

    });


    // ==========================================
    // CARGA INICIAL
    // ==========================================

    getData(paginaActual);


    // ==========================================
    // FUNCIÓN AJAX
    // ==========================================

    function getData(pagina) {

        console.log("GETDATA TURNOS EJECUTADO");

        console.log("Página:", pagina);


        let input = campo.value;

        let cantidad = num_registros.value;


        if (pagina != null) {

            paginaActual = pagina;
        }


        let formData = new FormData();

        formData.append(
            "campo",
            input
        );

        formData.append(
            "num_registros",
            cantidad
        );

        formData.append(
            "pagina",
            paginaActual
        );


        fetch(
            "controladores/turno/turno_buscador.php",
            {
                method: "POST",
                body: formData
            }
        )

        .then(response => response.json())

        .then(data => {

            console.log(
                "DATOS DE TURNOS RECIBIDOS:",
                data
            );


            // ==================================
            // TABLA
            // ==================================

            contenido.innerHTML = data.data;


            // ==================================
            // TOTAL
            // ==================================

            let total = parseInt(
                data.totalFiltro
            );


            let cantidadActual = parseInt(
                num_registros.value
            );


            let inicio =
                (paginaActual - 1)
                * cantidadActual + 1;


            let fin =
                paginaActual
                * cantidadActual;


            if (fin > total) {
                fin = total;
            }


            if (total > 0) {

                document.getElementById(
                    "lbl-total-turnos"
                ).innerHTML =
                    "Mostrando "
                    + inicio
                    + " - "
                    + fin
                    + " de "
                    + total
                    + " registros";

            } else {

                document.getElementById(
                    "lbl-total-turnos"
                ).innerHTML =
                    "No se encontraron registros";
            }


            // ==================================
            // PAGINACIÓN
            // ==================================

            nav_paginacion.innerHTML =
                data.paginacion;

        })

        .catch(error => {

            console.error(
                "Error al cargar los turnos:",
                error
            );

        });
    }

});