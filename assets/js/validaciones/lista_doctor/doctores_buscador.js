document.addEventListener("DOMContentLoaded", function () {
  const campo = document.getElementById("campo");
  const contenidoDoc = document.getElementById("contenidoDoc");
  const limpiarBusqueda = document.getElementById("limpiarBusqueda");
  const num_registros = document.getElementById("num_registros");
  const nav_paginacion = document.getElementById("nav_paginacion");

  // PÁGINA ACTUAL
  let paginaActual = 1;

  // CAMBIAR CANTIDAD
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

    // Volver a primera página
    paginaActual = 1;
    getData(paginaActual);
  });

  // LIMPIAR BÚSQUEDA
  limpiarBusqueda.addEventListener("click", function () {
    campo.value = "";
    limpiarBusqueda.classList.add("d-none");
    paginaActual = 1;
    getData(paginaActual);
    campo.focus();
  });

  // PAGINACIÓN
  nav_paginacion.addEventListener("click", function (e) {
    const boton = e.target.closest(".pagina");

    if (!boton) {
      return;
    }

    e.preventDefault();
    paginaActual = parseInt(boton.dataset.pagina);
    getData(paginaActual);
  });

  // CARGA INICIAL
  getData(paginaActual);

  // GET DATA
  function getData(pagina) {
    let input = campo.value;
    let cantidad = num_registros.value;

    if (pagina != null) {
      paginaActual = pagina;
    }

    let formData = new FormData();

    formData.append("campo", input);
    formData.append("num_registros", cantidad);
    formData.append("pagina", paginaActual);

    fetch("controladores/doctores/doctores_buscador.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        // TABLA
        contenidoDoc.innerHTML = data.data;

        // LEYENDA
        let total = parseInt(data.totalFiltro);

        let cantidad = parseInt(num_registros.value);

        let inicio = (paginaActual - 1) * cantidad + 1;

        let fin = paginaActual * cantidad;

        if (fin > total) {
          fin = total;
        }

        if (total > 0) {
          document.getElementById("lbl-total").innerHTML =
            "Mostrando " + inicio + "-" + fin + " de " + total + " registros";
        } else {
          document.getElementById("lbl-total").innerHTML =
            "No se encontraron registros";
        }

        // PAGINACIÓN
        nav_paginacion.innerHTML = data.paginacion;
      })

      .catch((error) => {
        console.error("Error:", error);
      });
  }
});
