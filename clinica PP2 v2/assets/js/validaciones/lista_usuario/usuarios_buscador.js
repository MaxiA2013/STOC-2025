
document.addEventListener("DOMContentLoaded", function () {
  const campo = document.getElementById("campo");
  const contenido = document.getElementById("contenido");
  const limpiarBusqueda = document.getElementById("limpiarBusqueda");
  const num_registros = document.getElementById("num_registros");
  const nav_paginacion = document.getElementById("nav_paginacion");

  // Página actual
  let paginaActual = 1;

  // cambio de cantidad
  num_registros.addEventListener("change", function () {
    paginaActual = 1;
    getData(paginaActual);
  });

  // buscador
  campo.addEventListener("input", function () {
    if (campo.value.length > 0) {
      limpiarBusqueda.classList.remove("d-none");
    } else {
      limpiarBusqueda.classList.add("d-none");
    }
    paginaActual = 1;
    getData(paginaActual);
  });

  // limpiar busqueda
  limpiarBusqueda.addEventListener("click", function () {
    campo.value = "";
    limpiarBusqueda.classList.add("d-none");
    paginaActual = 1;
    getData(paginaActual);
    campo.focus();
  });

  // paginacion
  nav_paginacion.addEventListener("click", function (e) {
    const boton = e.target.closest(".pagina");
    if (!boton) {
      return;
    }
    e.preventDefault();
    paginaActual = parseInt(boton.dataset.pagina);
    getData(paginaActual);
  });

  //funcon de eliminado de lista_usuarios
  document.addEventListener('estadoUsuarioActualizado', function () {

    console.log('EVENTO: estadoUsuarioActualizado RECIBIDO');

    getData(paginaActual);

});

  // carga inicial
  getData(paginaActual);
  function getData(pagina) {

    console.log('GETDATA EJECUTADO');
    console.log('Página:', pagina);

    let input = campo.value;
    let cantidad = num_registros.value;

    if (pagina != null) {
      paginaActual = pagina;
    }

    let formData = new FormData();

    formData.append("campo", input);
    formData.append("num_registros", cantidad);
    formData.append("pagina", paginaActual);
    fetch("controladores/usuarios/usuarios_buscador.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // Tabla
        console.log('DATOS RECIBIDOS NUEVAMENTE:', data);

    contenido.innerHTML = data.data;
        // Leyenda
        let total = parseInt(data.totalFiltro);
        let cantidad = parseInt(num_registros.value);
        let inicio = (paginaActual - 1) * cantidad + 1;
        let fin = paginaActual * cantidad;

        if (fin > total) {
          fin = total;
        }

        if (total > 0) {
          document.getElementById("lbl-total").innerHTML =
            "Mostrando " + fin + " de " + total + " registros";
        } else {
          document.getElementById("lbl-total").innerHTML =
            "No se encontraron registros";
        }

        // PAGINACIÓN
        nav_paginacion.innerHTML = data.paginacion;
      })
      .catch((error) => {
        console.error(error);
      });
  }
});
