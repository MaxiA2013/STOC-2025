<?php

session_start();
require_once __DIR__ . "/../../modelos/notificacion.php";
require_once __DIR__ . "/../../config/pusher.php";

header('Content-Type: application/json; charset=utf-8');


// =====================================================
// VERIFICAR SESION
// =====================================================

if (!isset($_SESSION['id_usuario'])) {

    echo json_encode([
        'success' => false,
        'mensaje' => 'Usuario no autenticado'
    ]);

    exit;
}

$usuarioSesion = (int) $_SESSION['id_usuario'];


// =====================================================
// OBTENER ACCION
// =====================================================

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';


// =====================================================
// CREAR NOTIFICACION
// =====================================================

if ($accion === 'crear') {

    $usuario_id_usuario = isset($_POST['usuario_id_usuario'])
        ? (int) $_POST['usuario_id_usuario']
        : 0;

    $titulo = trim($_POST['titulo'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    $tipo = trim($_POST['tipo'] ?? 'general');
    $url = trim($_POST['url'] ?? '');


    if ($usuario_id_usuario <= 0) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'Usuario destinatario inválido'
        ]);

        exit;
    }


    if ($titulo === '' || $mensaje === '') {

        echo json_encode([
            'success' => false,
            'mensaje' => 'El título y el mensaje son obligatorios'
        ]);

        exit;
    }


    try {

        $notificacion = new Notificacion();

        $datosPusher = $notificacion->crearYNotificar(
            $usuario_id_usuario,
            $titulo,
            $mensaje,
            $tipo,
            $url
        );

        echo json_encode([
            'success' => true,
            'mensaje' => 'Notificación creada correctamente',
            'notificacion' => $datosPusher
        ]);

        exit;

    } catch (Exception $e) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al crear la notificación',
            'error' => $e->getMessage()
        ]);

        exit;
    }
}

// =====================================================
// LISTAR NOTIFICACIONES DEL USUARIO
// =====================================================

if ($accion === 'listar') {

    try {

        $notificacion = new Notificacion();

        $resultado = $notificacion
            ->consultarNotificacionesUsuario(
                $usuarioSesion
            );


        $notificaciones = [];


        if ($resultado) {

            while ($fila = mysqli_fetch_assoc($resultado)) {

                $notificaciones[] = $fila;
            }
        }


        echo json_encode([

            'success' => true,

            'notificaciones' => $notificaciones

        ]);

        exit;


    } catch (Exception $e) {

        echo json_encode([

            'success' => false,

            'mensaje' => $e->getMessage()

        ]);

        exit;
    }
}


// =====================================================
// LISTAR NOTIFICACIONES NO LEIDAS
// =====================================================

if ($accion === 'no_leidas') {

    try {

        $notificacion = new Notificacion();

        $resultado = $notificacion
            ->consultarNotificacionesNoLeidas(
                $usuarioSesion
            );


        $notificaciones = [];


        if ($resultado) {

            while ($fila = mysqli_fetch_assoc($resultado)) {

                $notificaciones[] = $fila;
            }
        }


        echo json_encode([

            'success' => true,

            'notificaciones' => $notificaciones,

            'cantidad' => count($notificaciones)

        ]);

        exit;


    } catch (Exception $e) {

        echo json_encode([

            'success' => false,

            'mensaje' => $e->getMessage()

        ]);

        exit;
    }
}


// =====================================================
// CONTAR NO LEIDAS
// =====================================================

if ($accion === 'contar_no_leidas') {

    try {

        $notificacion = new Notificacion();

        $resultado = $notificacion
            ->contarNotificacionesNoLeidas(
                $usuarioSesion
            );


        $cantidad = 0;


        if ($resultado) {

            $fila = mysqli_fetch_assoc($resultado);

            $cantidad = (int) ($fila['cantidad'] ?? 0);
        }


        echo json_encode([

            'success' => true,

            'cantidad' => $cantidad

        ]);

        exit;


    } catch (Exception $e) {

        echo json_encode([

            'success' => false,

            'mensaje' => $e->getMessage()

        ]);

        exit;
    }
}


// =====================================================
// MARCAR UNA NOTIFICACION COMO LEIDA
// =====================================================

if ($accion === 'marcar_leida') {

    $id_notificacion = isset($_POST['id_notificacion'])
        ? (int) $_POST['id_notificacion']
        : 0;


    if ($id_notificacion <= 0) {

        echo json_encode([

            'success' => false,

            'mensaje' => 'ID de notificación inválido'

        ]);

        exit;
    }


    try {

        $notificacion = new Notificacion();

        $notificacion->marcarComoLeidaUsuario(
            $id_notificacion,
            $usuarioSesion
        );


        echo json_encode([

            'success' => true,

            'mensaje' => 'Notificación marcada como leída'

        ]);

        exit;


    } catch (Exception $e) {

        echo json_encode([

            'success' => false,

            'mensaje' => $e->getMessage()

        ]);

        exit;
    }
}


// =====================================================
// MARCAR TODAS COMO LEIDAS
// =====================================================

if ($accion === 'marcar_todas') {

    try {

        $notificacion = new Notificacion();

        $notificacion->marcarTodasComoLeidas(
            $usuarioSesion
        );


        echo json_encode([

            'success' => true,

            'mensaje' => 'Todas las notificaciones fueron marcadas como leídas'

        ]);

        exit;


    } catch (Exception $e) {

        echo json_encode([

            'success' => false,

            'mensaje' => $e->getMessage()

        ]);

        exit;
    }
}


// =====================================================
// ELIMINAR NOTIFICACION
// =====================================================

if ($accion === 'eliminar') {

    $id_notificacion = isset($_POST['id_notificacion'])
        ? (int) $_POST['id_notificacion']
        : 0;


    if ($id_notificacion <= 0) {

        echo json_encode([

            'success' => false,

            'mensaje' => 'ID de notificación inválido'

        ]);

        exit;
    }


    try {

        /*
         * Primero verificamos que la notificación
         * pertenezca al usuario de la sesión.
         */

        $notificacion = new Notificacion();

        $resultado = $notificacion
            ->consultarNotificacion(
                $id_notificacion
            );


        if (!$resultado || mysqli_num_rows($resultado) === 0) {

            echo json_encode([

                'success' => false,

                'mensaje' => 'Notificación no encontrada'

            ]);

            exit;
        }


        $fila = mysqli_fetch_assoc($resultado);


        if ((int) $fila['usuario_id_usuario'] !== $usuarioSesion) {

            echo json_encode([

                'success' => false,

                'mensaje' => 'No tiene permisos para eliminar esta notificación'

            ]);

            exit;
        }


        $notificacion->setIdNotificacion(
            $id_notificacion
        );

        $notificacion->eliminarNotificacion();


        echo json_encode([

            'success' => true,

            'mensaje' => 'Notificación eliminada correctamente'

        ]);

        exit;


    } catch (Exception $e) {

        echo json_encode([

            'success' => false,

            'mensaje' => $e->getMessage()

        ]);

        exit;
    }
}


// =====================================================
// AUTORIZAR CANAL PRIVADO DE PUSHER
// =====================================================

if ($accion === 'autorizar_canal') {

    $socket_id = $_POST['socket_id'] ?? '';

    $channel_name = $_POST['channel_name'] ?? '';


    if ($socket_id === '' || $channel_name === '') {

        http_response_code(400);

        echo json_encode([

            'success' => false,

            'mensaje' => 'Datos de autorización incompletos'

        ]);

        exit;
    }


    // Canal esperado:
    // private-usuario-15

    $prefijo = 'private-usuario-';


    if (strpos($channel_name, $prefijo) !== 0) {

        http_response_code(403);

        echo json_encode([

            'success' => false,

            'mensaje' => 'Canal no permitido'

        ]);

        exit;
    }


    $idUsuarioCanal = (int) str_replace(
        $prefijo,
        '',
        $channel_name
    );


    // ---------------------------------------------
    // El usuario solamente puede autorizar
    // SU propio canal
    // ---------------------------------------------

    if ($idUsuarioCanal !== $usuarioSesion) {

        http_response_code(403);

        echo json_encode([

            'success' => false,

            'mensaje' => 'No tiene autorización para este canal'

        ]);

        exit;
    }


    try {

        $pusher = new ConexionPusher();
        $auth = $pusher->obtenerCliente()->authorizeChannel(
            $channel_name,
            $socket_id
        );

        header('Content-Type: application/json');
        echo $auth;
        exit;

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode([

            'success' => false,

            'mensaje' => $e->getMessage()

        ]);

        exit;
    }
}


// =====================================================
// ACCION NO RECONOCIDA
// =====================================================

echo json_encode([

    'success' => false,

    'mensaje' => 'Acción no reconocida'

]);

exit;