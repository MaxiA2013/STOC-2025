document.addEventListener("DOMContentLoaded", function () {
  // =====================================================
  // CONFIGURACION
  // =====================================================

  const ID_USUARIO = window.ID_USUARIO;

  const URL_CONTROLADOR =
    "controladores/notificaciones/notificacion_controlador.php";

  function cargarNotificaciones() {
    fetch(URL_CONTROLADOR + "?accion=listar")
      .then((response) => response.json())

      .then((data) => {
        if (!data.success) {
          console.error(data.mensaje);

          return;
        }

        const lista = document.getElementById("listaNotificaciones");

        if (!lista) {
          return;
        }

        [...data.notificaciones].reverse().forEach(function (notificacion) {
          agregarNotificacionAlDropdown(notificacion);
        });

        actualizarContador(
          data.notificaciones.filter((n) => parseInt(n.leida) === 0).length,
        );
      })

      .catch((error) => {
        console.error("Error al cargar notificaciones:", error);
      });
  }

  function actualizarContador(cantidad) {

    const contador =
        document.getElementById(
            'contadorNotificaciones'
        );


    if (!contador) {

        return;
    }


    contador.textContent =
        cantidad;


    if (cantidad > 0) {

        contador.style.display =
            'inline-block';

    } else {

        contador.style.display =
            'none';
    }
}
  // =====================================================
  // VERIFICAR USUARIO
  // =====================================================

  if (!ID_USUARIO) {
    console.warn("No se encontró el ID del usuario.");

    return;
  }

  // =====================================================
  // CONECTAR CON PUSHER
  // =====================================================

  const pusher = new Pusher("30891e0e5a67798ca12f", {
    cluster: "sa1",

    forceTLS: true,

    authEndpoint: URL_CONTROLADOR + "?accion=autorizar_canal",
  });

  // =====================================================
  // CANAL PRIVADO DEL USUARIO
  // =====================================================

  const nombreCanal = "private-usuario-" + ID_USUARIO;

  const channel = pusher.subscribe(nombreCanal);

  // =====================================================
  // NUEVA NOTIFICACION
  // =====================================================

  channel.bind("nueva-notificacion", function (data) {
    console.log("Nueva notificación recibida:", data);

    agregarNotificacionAlDropdown(data);

    incrementarContador();
  });

  // =====================================================
  // CONEXION PUSHER
  // =====================================================

  pusher.connection.bind("connected", function () {
    console.log("Pusher conectado correctamente.");

    console.log("Canal:", nombreCanal);
  });

  // =====================================================
  // ERROR DE PUSHER
  // =====================================================

  pusher.connection.bind("error", function (error) {
    console.error("Error de conexión con Pusher:", error);
  });

  // =====================================================
  // AGREGAR NOTIFICACION AL DROPDOWN
  // =====================================================

  function agregarNotificacionAlDropdown(notificacion) {
    const lista = document.getElementById("listaNotificaciones");

    if (!lista) {
      console.warn("No existe #listaNotificaciones");

      return;
    }

    // ---------------------------------------------
    // Ocultar mensaje de "sin notificaciones"
    // ---------------------------------------------

    const sinNotificaciones = document.getElementById("sinNotificaciones");

    if (sinNotificaciones) {
      sinNotificaciones.style.display = "none";
    }

    // ---------------------------------------------
    // Crear elemento
    // ---------------------------------------------

    const elemento = document.createElement("li");

    elemento.className = "notificacion no-leida";

    elemento.dataset.id = notificacion.id_notificacion;

    elemento.innerHTML = `

            <a
                href="${notificacion.url || "#"}"
                class="dropdown-item py-3"
                data-id-notificacion="${notificacion.id_notificacion}"
            >

                <div class="d-flex">

                    <div class="me-3">

                        <i class="bi bi-bell-fill"></i>

                    </div>

                    <div>

                        <div class="fw-semibold">
                            ${escapeHtml(notificacion.titulo)}
                        </div>

                        <div class="small text-muted">
                            ${escapeHtml(notificacion.mensaje)}
                        </div>

                        <div class="small text-muted mt-1">
                            ${notificacion.fecha_creacion}
                        </div>

                    </div>

                </div>

            </a>
        `;

    // ---------------------------------------------
    // Insertar justo después de la cabecera (no
    // arriba del todo, que quedaría por encima del
    // título "Notificaciones")
    // ---------------------------------------------

    const cabecera = lista.querySelector('.dropdown-header');
    const primerDivider = lista.querySelector('.dropdown-divider');

    if (primerDivider && primerDivider.closest('li')) {
      primerDivider.closest('li').after(elemento);
    } else {
      lista.prepend(elemento);
    }
  }

  // =====================================================
  // INCREMENTAR CONTADOR
  // =====================================================

    function incrementarContador() {
    const contador = document.getElementById("contadorNotificaciones");

    if (!contador) {
      return;
    }

    let cantidad = parseInt(contador.textContent) || 0;

    cantidad++;

    contador.textContent = cantidad;

    contador.style.display = "inline-block";
  }

  // =====================================================
  // DECREMENTAR CONTADOR
  // =====================================================

  function decrementarContador() {
    const contador = document.getElementById("contadorNotificaciones");

    if (!contador) {
      return;
    }

    let cantidad = parseInt(contador.textContent) || 0;

    cantidad = Math.max(0, cantidad - 1);

    contador.textContent = cantidad;

    contador.style.display = cantidad > 0 ? "inline-block" : "none";
  }

  // =====================================================
  // QUITAR LA MARCA "NO LEÍDA" DE UN ELEMENTO
  // =====================================================
  // La clase "no-leida" puede estar en el <li> (notificaciones
  // creadas dinámicamente) o en el <a> (las 3 de ejemplo que ya
  // venían hardcodeadas en el HTML del navbar), así que se limpia
  // en ambos por seguridad.
  // =====================================================

  function quitarMarcaNoLeida(li) {
    li.classList.remove("no-leida");

    const enlace = li.querySelector("a");

    if (enlace) {
      enlace.classList.remove("no-leida");
    }
  }

  // =====================================================
  // MARCAR UNA NOTIFICACIÓN COMO LEÍDA (backend)
  // =====================================================

  function marcarNotificacionComoLeida(idNotificacion, liElemento) {
    const formData = new FormData();

    formData.append("accion", "marcar_leida");
    formData.append("id_notificacion", idNotificacion);

    fetch(URL_CONTROLADOR, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          quitarMarcaNoLeida(liElemento);
          decrementarContador();
        } else {
          console.error("No se pudo marcar como leída:", data.mensaje);
        }
      })
      .catch((error) => {
        console.error("Error al marcar notificación como leída:", error);
      });
  }

  // =====================================================
  // ESCAPAR HTML
  // =====================================================

  function escapeHtml(texto) {
    const div = document.createElement("div");

    div.textContent = texto ?? "";

    return div.innerHTML;
  }

  function actualizarContador(cantidad) {

    const contador =
        document.getElementById(
            'contadorNotificaciones'
        );


    if (!contador) {

        return;
    }


    contador.textContent =
        cantidad;


    if (cantidad > 0) {

        contador.style.display =
            'inline-block';

    } else {

        contador.style.display =
            'none';
    }
}

  // Inicialización explícita del dropdown de la campana,
  // por si la delegación automática de Bootstrap no llega
  // a engancharse a tiempo (por orden de carga de scripts,
  // versiones duplicadas de Bootstrap, etc).
  const btnCampana = document.getElementById('btnNotificaciones');

    if (btnCampana && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
    bootstrap.Dropdown.getOrCreateInstance(btnCampana);
  } else if (btnCampana) {
    console.warn('Bootstrap JS no está disponible: la campana de notificaciones no va a desplegar.');
  }

  // =====================================================
  // MARCAR COMO LEÍDA AL HACER CLICK EN UNA NOTIFICACIÓN
  // =====================================================
  // Delegado sobre el <ul> completo porque las notificaciones
  // se agregan dinámicamente después de esta llamada.
  // =====================================================

  const listaNotificacionesEl = document.getElementById('listaNotificaciones');

  if (listaNotificacionesEl) {

    listaNotificacionesEl.addEventListener('click', function (e) {

      const enlace = e.target.closest('a[data-id-notificacion]');

      if (!enlace) {
        return;
      }

      const li = enlace.closest('li');

      const estaNoLeida =
        li.classList.contains('no-leida') ||
        enlace.classList.contains('no-leida');

      if (estaNoLeida) {
        marcarNotificacionComoLeida(enlace.dataset.idNotificacion, li);
      }

    });
  }

  // =====================================================
  // MARCAR TODAS COMO LEÍDAS
  // =====================================================

  const btnMarcarTodas = document.getElementById('marcarTodasLeidas');

  if (btnMarcarTodas) {

    btnMarcarTodas.addEventListener('click', function (e) {

      e.preventDefault();

      const formData = new FormData();

      formData.append('accion', 'marcar_todas');

      fetch(URL_CONTROLADOR, {
        method: 'POST',
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {

          if (!data.success) {
            console.error('No se pudieron marcar todas como leídas:', data.mensaje);
            return;
          }

          document
            .querySelectorAll('#listaNotificaciones li')
            .forEach(quitarMarcaNoLeida);

          actualizarContador(0);
        })
        .catch((error) => {
          console.error('Error al marcar todas como leídas:', error);
        });

    });
  }

  cargarNotificaciones();
});
