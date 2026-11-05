document.addEventListener("DOMContentLoaded", function () {
    getData(); // Carga la tabla automáticamente al entrar a la pantalla

    // Escuchar la barra de búsqueda mientras el usuario escribe
    document.getElementById("campo").addEventListener("keyup", function() {
        document.getElementById("limpiarBusqueda").classList.toggle("d-none", this.value === "");
        getData(1); // Reinicia a la página 1 en cada búsqueda
    });

    // Escuchar el selector de cantidad de registros a mostrar (5, 10, 50)
    document.getElementById("num_registros").addEventListener("change", function() {
        getData(1);
    });

    // Botón para limpiar por completo la barra de búsqueda
    document.getElementById("limpiarBusqueda").addEventListener("click", function() {
        document.getElementById("campo").value = "";
        this.classList.add("d-none");
        getData(1);
    });

    // Envío del Formulario de Alta (Panel lateral Offcanvas) mediante AJAX
    document.getElementById("formNuevaCobertura").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("controladores/obra_social_prestacion_controlador.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            alert(res.mensaje);
            if (res.success) {
                this.reset();
                // Cerrar el panel lateral automáticamente
                bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasNuevaCobertura")).hide();
                getData(); // Recargar la tabla con el nuevo registro
            }
        })
        .catch(err => console.error("Error al guardar cobertura:", err));
    });
});
let paginaActual = 1;

// Función principal para pedirle los datos filtrados al backend
function getData(pagina = 1) {
    paginaActual = pagina;
    const campo = document.getElementById("campo").value;
    const num_registros = document.getElementById("num_registros").value;

    const formData = new FormData();
    formData.append('campo', campo);
    formData.append('num_registros', num_registros);
    formData.append('pagina', pagina);

    // Ajustamos la dirección al controlador correcto que lee las coincidencias
    fetch("controladores/coberturas_buscador_controlador.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        document.getElementById("contenido").innerHTML = res.data;
        document.getElementById("nav_paginacion").innerHTML = res.pages;
        document.getElementById("lbl-total").innerText = res.totalFiltro;
    })
    .catch(err => console.error("Error al obtener coberturas:", err));
}

function cambiarPagina(numPagina) {
    getData(numPagina);
}

// Cargar datos en el Modal de Modificación al hacer click en Editar
function abrirModalEditar(idOs, idP, codigo, precio, estado) {
    document.getElementById("editar_id_obra_social").value = idOs;
    document.getElementById("editar_id_prestacion").value = idP;
    document.getElementById("editar_codigo").value = codigo;
    document.getElementById("editar_precio").value = precio;
    document.getElementById("editar_estado").value = estado;

    const modal = new bootstrap.Modal(document.getElementById("modalModificarCobertura"));
    modal.show();
}

// Función para la Baja Lógica mediante AJAX
function eliminarCobertura(idOs, idP) {
    if (confirm("¿Está seguro de que desea eliminar esta cobertura? ")) {
        const formData = new FormData();
        formData.append('action', 'eliminacion');
        formData.append('id_obra_social', idOs);
        formData.append('id_prestacion', idP);

        fetch("controladores/obra_social_prestacion_controlador.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            alert(res.mensaje);
            if (res.success) {
                getData(paginaActual); // Refresca la tabla en la página que estabas
            }
        })
        .catch(err => console.error("Error al borrar cobertura:", err));
    }
}

// Agregar el evento para enviar el formulario de edición al final de la carga
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("formModificarCobertura").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("controladores/obra_social_prestacion_controlador.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            alert(res.mensaje);
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById("modalModificarCobertura")).hide();
                getData(); // Recargar listado
            }
        })
        .catch(err => console.error("Error al actualizar cobertura:", err));
    });
});
