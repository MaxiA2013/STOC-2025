document.addEventListener('DOMContentLoaded', function () {

    console.log('==============================');
    console.log('LISTA_USUARIOS.JS CARGADO');
    console.log('==============================');


    /*
    =====================================================
    CAMBIO DE ESTADO DEL USUARIO
    =====================================================
    */

    document.addEventListener('submit', function (event) {

        /*
        Verificamos si el formulario enviado
        corresponde al cambio de estado
        */

        if (!event.target.classList.contains('formEstadoUsuario')) {
            return;
        }

        console.log('==============================');
        console.log('FORMULARIO DE ESTADO DETECTADO');
        console.log('==============================');


        /*
        Evitamos que el formulario
        redirija al controlador
        */

        event.preventDefault();


        /*
        Guardamos el formulario
        */

        const formulario = event.target;


        /*
        Creamos FormData a partir del formulario
        */

        const formData = new FormData(formulario);


        /*
        Obtenemos la acción:
        eliminacion o activacion
        */

        const accion = formData.get('action');


        /*
        Obtenemos el ID del usuario
        */

        const idUsuario = formData.get('id_usuario');


        console.log('Acción:', accion);
        console.log('ID usuario:', idUsuario);


        /*
        =================================================
        CONFIRMACIÓN
        =================================================
        */

        let mensaje = '';

        if (accion === 'eliminacion') {

            mensaje = '¿Está seguro de que desea desactivar este usuario?';

        } else if (accion === 'activacion') {

            mensaje = '¿Está seguro de que desea activar este usuario?';

        }


        if (!confirm(mensaje)) {
            return;
        }


        /*
        =================================================
        URL DEL CONTROLADOR
        =================================================

        IMPORTANTE:
        Esta variable contiene una STRING.
        No contiene ningún elemento HTML.
        */
        const url = 'controladores/usuarios/usuarios_controlador.php';
        console.log('URL:', url);


        /*
        =================================================
        PETICIÓN AJAX
        =================================================
        */

        fetch(url, {
            method: 'POST',
            body: formData
        })

        .then(response => {

            /*
            Primero verificamos si HTTP respondió correctamente
            */

            if (!response.ok) {
                throw new Error(
                    'Error HTTP: ' + response.status
                );
            }

            return response.json();

        })

        .then(data => {

            console.log('RESPUESTA DEL CONTROLADOR:', data);


            if (data.success) {

                console.log(data.mensaje);
                console.log('EVENTO: estadoUsuarioActualizado ENVIADO');
                    document.dispatchEvent(
                        new Event('estadoUsuarioActualizado')
                    );
            } else {

                alert(
                    data.mensaje || 'No se pudo cambiar el estado del usuario.'
                );

            }

        })

        .catch(error => {

            console.error(
                'Error en AJAX de cambio de estado:',
                error
            );

        });

    });

});


/*
=====================================================
MODIFICAR USUARIO
=====================================================
*/

document.addEventListener('click', function (event) {

    const boton = event.target.closest('.btnModificarUsuario');

    if (!boton) {
        return;
    }


    /*
    =============================================
    OBTENER DATOS DEL USUARIO
    =============================================
    */

    const id = boton.dataset.id;
    const persona = boton.dataset.persona;
    const usuario = boton.dataset.usuario;
    const email = boton.dataset.email;
    const nombre = boton.dataset.nombre;
    const apellido = boton.dataset.apellido;


    /*
    =============================================
    CARGAR DATOS EN EL MODAL
    =============================================
    */

    document.getElementById(
        'editar_id_usuario'
    ).value = id;


    document.getElementById(
        'editar_id_persona'
    ).value = persona;


    document.getElementById(
        'editar_nombre_usuario'
    ).value = usuario;


    document.getElementById(
        'editar_email'
    ).value = email;


    document.getElementById(
        'editar_nombre'
    ).value = nombre;


    document.getElementById(
        'editar_apellido'
    ).value = apellido;

});



/*
=====================================================
FORMULARIO MODIFICAR USUARIO
=====================================================
*/

document.addEventListener('submit', function (event) {

    if (
        !event.target.matches('#formModificarUsuario')
    ) {
        return;
    }


    event.preventDefault();


    const formulario = event.target;

    const formData = new FormData(formulario);


    fetch(
        'controladores/usuarios/usuarios_controlador.php',
        {
            method: 'POST',
            body: formData
        }
    )

    .then(response => {
        if (!response.ok) {
            throw new Error(
                'Error HTTP: ' + response.status
            );

        }
        return response.json();
    })

    .then(data => {
        console.log(
            'RESPUESTA MODIFICACIÓN:',
            data
        );

        if (data.success) {
            console.log(data.mensaje);
            document.dispatchEvent(
                        new Event('estadoUsuarioActualizado')
                    );

            const modalElemento =
                document.getElementById(
                    'modalModificarUsuario'
                );

            const modal =
                bootstrap.Modal.getInstance(
                    modalElemento
                );


            if (modal) {
                modal.hide();
            }


            /*
            =============================================
            ACTUALIZAR TABLA
            =============================================
            */

            if (
                typeof window.recargarTablaUsuarios ===
                'function'
            ) {

                window.recargarTablaUsuarios();

            }

        } else {

            alert(
                data.mensaje ||
                'No se pudo actualizar el usuario.'
            );

        }

    })

    .catch(error => {

        console.error(
            'Error AJAX modificación:',
            error
        );

    });

});

/*
=====================================================
ALTA DE USUARIO
=====================================================
*/

document.addEventListener('submit', function (event) {

    /*
    =============================================
    VERIFICAR SI ES EL FORMULARIO DE ALTA
    =============================================
    */

    if (!event.target.matches('#formNuevoUsuario')) {
        return;
    }

    console.log('==============================');
    console.log('FORMULARIO NUEVO USUARIO DETECTADO');
    console.log('==============================');

    /*
    =============================================
    EVITAR RECARGA DE LA PÁGINA
    =============================================
    */

    event.preventDefault();

    /*
    =============================================
    OBTENER FORMULARIO
    =============================================
    */

    const formulario = event.target;

    /*
    =============================================
    CREAR FORMDATA
    =============================================
    */

    const formData = new FormData(formulario);

    /*
    =============================================
    MOSTRAR DATOS EN CONSOLA
    =============================================
    */

    console.log('Datos del formulario:');

    for (const [nombre, valor] of formData.entries()) {
        console.log(nombre + ':', valor);
    }

    /*
    =============================================
    URL DEL CONTROLADOR
    =============================================
    */

    const url = 'controladores/usuarios/usuarios_controlador.php';

    console.log('URL:', url);

    /*
    =============================================
    PETICIÓN AJAX
    =============================================
    */

    fetch(url, {
        method: 'POST',
        body: formData
    })

    .then(response => {

        if (!response.ok) {
            throw new Error(
                'Error HTTP: ' + response.status
            );
        }

        return response.json();
    })

    .then(data => {

        console.log(
            'RESPUESTA ALTA USUARIO:',
            data
        );

        /*
        =============================================
        VERIFICAR RESULTADO
        =============================================
        */

        if (data.success) {

            console.log(data.mensaje);

            /*
            =============================================
            CERRAR OFFCANVAS
            =============================================
            */

            const offcanvasElemento =
                document.getElementById(
                    'offcanvasNuevoUsuario'
                );

            const offcanvas =
                bootstrap.Offcanvas.getInstance(
                    offcanvasElemento
                );

            if (offcanvas) {
                offcanvas.hide();
            }

            /*
            =============================================
            LIMPIAR FORMULARIO
            =============================================
            */

            formulario.reset();

            /*
            =============================================
            ACTUALIZAR TABLA
            =============================================
            */

            document.dispatchEvent(
                new Event('estadoUsuarioActualizado')
            );

        } else {

            alert(
                data.mensaje ||
                'No se pudo registrar el usuario.'
            );
        }
    })

    .catch(error => {

        console.error(
            'Error AJAX alta usuario:',
            error
        );

    });

});