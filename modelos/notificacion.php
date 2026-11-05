<?php
//se utilizan las tablas notificacion y suscripcion push de la bd
require_once "conexion.php";
require_once __DIR__ . '/../config/pusher.php';

class Notificacion
{
    private int $id_notificacion;
    private int $usuario_id_usuario;
    private string $titulo;
    private string $mensaje;
    private string $tipo;
    private string $url;
    private int $leida;
    private string $fecha_creacion;

    // Datos necesarios para Push
    private int $id_suscripcion;
    private string $endpoint;
    private string $p256dh;
    private string $auth;

    public function __construct(
        $usuario_id_usuario = 0,
        $titulo = '',
        $mensaje = '',
        $tipo = 'general',
        $url = ''
    ) {
        $this->usuario_id_usuario = $usuario_id_usuario;
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->url = $url;
        $this->leida = 0;
    }


    // ==========================================
    // GETTERS Y SETTERS
    // ==========================================

    public function getIdNotificacion(): int
    {
        return $this->id_notificacion;
    }

    public function setIdNotificacion($id_notificacion): self
    {
        $this->id_notificacion = $id_notificacion;
        return $this;
    }


    public function getUsuarioIdUsuario(): int
    {
        return $this->usuario_id_usuario;
    }

    public function setUsuarioIdUsuario($usuario_id_usuario): self
    {
        $this->usuario_id_usuario = $usuario_id_usuario;
        return $this;
    }


    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo($titulo): self
    {
        $this->titulo = $titulo;
        return $this;
    }


    public function getMensaje(): string
    {
        return $this->mensaje;
    }

    public function setMensaje($mensaje): self
    {
        $this->mensaje = $mensaje;
        return $this;
    }


    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo($tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }


    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl($url): self
    {
        $this->url = $url;
        return $this;
    }


    public function getLeida(): int
    {
        return $this->leida;
    }

    public function setLeida($leida): self
    {
        $this->leida = $leida;
        return $this;
    }


    public function getFechaCreacion(): string
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion($fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;
        return $this;
    }


    // ==========================================
    // CRUD NOTIFICACIONES
    // ==========================================

    // GUARDAR NOTIFICACION
    public function guardarNotificacion()
    {
        $con = new Conexion();

        $query = "INSERT INTO notificacion
                  (usuario_id_usuario, titulo, mensaje, tipo, url, leida)
                  VALUES
                  ('$this->usuario_id_usuario',
                   '$this->titulo',
                   '$this->mensaje',
                   '$this->tipo',
                   '$this->url',
                   '$this->leida')";

        $con->insertar($query);
    }

        // ==========================================
    // CREAR NOTIFICACIÓN + ENVIAR POR PUSHER
    // ==========================================
    // Punto único de entrada para notificar a un usuario.
    // Guarda en la tabla `notificacion` Y dispara el evento
    // en tiempo real por el canal privado del usuario.
    // Cualquier controlador (notificaciones, turnos, etc.)
    // llama a este método en vez de reimplementar la lógica.
    public function crearYNotificar(
        $usuario_id_usuario,
        $titulo,
        $mensaje,
        $tipo = 'general',
        $url = ''
    ) {
        $this->usuario_id_usuario = (int) $usuario_id_usuario;
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->url = $url;
        $this->leida = 0;

        // Valores por defecto: si algo falla más abajo (guardar
        // en BD o enviar por Pusher), igual devolvemos una
        // estructura consistente en vez de romper.
        $datosPusher = [
            'id_notificacion'    => null,
            'usuario_id_usuario' => $this->usuario_id_usuario,
            'titulo'             => $titulo,
            'mensaje'            => $mensaje,
            'tipo'               => $tipo,
            'url'                => $url,
            'leida'              => 0,
            'fecha_creacion'     => date('Y-m-d H:i:s')
        ];

        // TODO el proceso de notificar (guardar + enviar) va
        // envuelto en un único try/catch: una falla acá NUNCA
        // debe poder tumbar la operación principal que la
        // originó (crear un turno, asignarlo, etc).
        try {

            $this->guardarNotificacion();

            $resultado = $this->consultarUltimaNotificacionUsuario(
                $this->usuario_id_usuario
            );

            if ($resultado && mysqli_num_rows($resultado) > 0) {

                $datosNotificacion = mysqli_fetch_assoc($resultado);

                $datosPusher['id_notificacion'] =
                    $datosNotificacion['id_notificacion'] ?? null;

                $datosPusher['fecha_creacion'] =
                    $datosNotificacion['fecha_creacion']
                    ?? $datosPusher['fecha_creacion'];
            }

            $canal = 'private-usuario-' . $this->usuario_id_usuario;
            $pusher = new ConexionPusher();

            $pusher->obtenerCliente()->trigger(
                $canal,
                'nueva-notificacion',
                $datosPusher
            );

        } catch (\Throwable $e) {

            error_log(
                'Error al crear/enviar notificación: ' . $e->getMessage()
            );
        }

        return $datosPusher;
    }

    // ACTUALIZAR NOTIFICACION
    public function actualizarNotificacion()
    {
        $con = new Conexion();

        $query = "UPDATE notificacion SET
                    titulo = '$this->titulo',
                    mensaje = '$this->mensaje',
                    tipo = '$this->tipo',
                    url = '$this->url',
                    leida = '$this->leida'
                  WHERE id_notificacion = $this->id_notificacion";

        $con->actualizar($query);
    }


    // ELIMINAR NOTIFICACION
    public function eliminarNotificacion()
    {
        $con = new Conexion();

        $query = "DELETE FROM notificacion
                  WHERE id_notificacion = $this->id_notificacion";

        $con->eliminar($query);
    }


    // CONSULTAR TODAS LAS NOTIFICACIONES
    public function consultarVariasNotificaciones()
    {
        $con = new Conexion();

        $query = "SELECT *
                  FROM notificacion
                  ORDER BY fecha_creacion DESC";

        return $con->consultar($query);
    }


    // CONSULTAR UNA NOTIFICACION
    public function consultarNotificacion($id)
    {
        $con = new Conexion();

        $query = "SELECT *
                  FROM notificacion
                  WHERE id_notificacion = $id";

        return $con->consultar($query);
    }


    // ==========================================
    // NOTIFICACIONES POR USUARIO
    // ==========================================

    // CONSULTAR TODAS LAS NOTIFICACIONES DE UN USUARIO
    public function consultarNotificacionesUsuario($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "SELECT *
                  FROM notificacion
                  WHERE usuario_id_usuario = $usuario_id_usuario
                  ORDER BY fecha_creacion DESC";

        return $con->consultar($query);
    }


    // CONSULTAR NOTIFICACIONES NO LEIDAS
    public function consultarNotificacionesNoLeidas($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "SELECT *
                  FROM notificacion
                  WHERE usuario_id_usuario = $usuario_id_usuario
                  AND leida = 0
                  ORDER BY fecha_creacion DESC";

        return $con->consultar($query);
    }


    // CONTAR NOTIFICACIONES NO LEIDAS
    public function contarNotificacionesNoLeidas($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "SELECT COUNT(*) AS cantidad
                  FROM notificacion
                  WHERE usuario_id_usuario = $usuario_id_usuario
                  AND leida = 0";

        return $con->consultar($query);
    }


    // ==========================================
    // MARCAR NOTIFICACIONES COMO LEIDAS
    // ==========================================

    // MARCAR UNA NOTIFICACION COMO LEIDA
    public function marcarComoLeida($id)
    {
        $con = new Conexion();

        $query = "UPDATE notificacion
                  SET leida = 1
                  WHERE id_notificacion = $id";

        $con->actualizar($query);
    }


    // MARCAR TODAS LAS NOTIFICACIONES COMO LEIDAS
    public function marcarTodasComoLeidas($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "UPDATE notificacion
                  SET leida = 1
                  WHERE usuario_id_usuario = $usuario_id_usuario
                  AND leida = 0";

        $con->actualizar($query);
    }

// MARCAR UNA NOTIFICACION COMO LEIDA
// VERIFICANDO EL USUARIO

public function marcarComoLeidaUsuario(
    $id_notificacion,
    $usuario_id_usuario
) {
    $con = new Conexion();

    $query = "UPDATE notificacion
              SET leida = 1
              WHERE id_notificacion = $id_notificacion
              AND usuario_id_usuario = $usuario_id_usuario";

    return $con->actualizar($query);
}


    // ==========================================
    // SUSCRIPCIONES PUSH
    // ==========================================

    // GUARDAR SUSCRIPCION PUSH
    public function guardarSuscripcionPush(
        $usuario_id_usuario,
        $endpoint,
        $p256dh,
        $auth
    ) {
        $con = new Conexion();

        $query = "INSERT INTO suscripcion_push
                  (usuario_id_usuario, endpoint, p256dh, auth)
                  VALUES
                  ('$usuario_id_usuario',
                   '$endpoint',
                   '$p256dh',
                   '$auth')";

        $con->insertar($query);
    }


    // CONSULTAR SUSCRIPCIONES DE UN USUARIO
    public function consultarSuscripcionesPush($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "SELECT *
                  FROM suscripcion_push
                  WHERE usuario_id_usuario = $usuario_id_usuario";

        return $con->consultar($query);
    }


    // CONSULTAR UNA SUSCRIPCION POR ENDPOINT
    public function consultarSuscripcionPush($endpoint)
    {
        $con = new Conexion();

        $query = "SELECT *
                  FROM suscripcion_push
                  WHERE endpoint = '$endpoint'";

        return $con->consultar($query);
    }


    // ELIMINAR SUSCRIPCION PUSH
    public function eliminarSuscripcionPush($endpoint)
    {
        $con = new Conexion();

        $query = "DELETE FROM suscripcion_push
                  WHERE endpoint = '$endpoint'";

        $con->eliminar($query);
    }


    // ==========================================
    // FUNCIONES AUXILIARES
    // ==========================================

    // ELIMINAR TODAS LAS NOTIFICACIONES DE UN USUARIO
    public function eliminarNotificacionesUsuario($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "DELETE FROM notificacion
                  WHERE usuario_id_usuario = $usuario_id_usuario";

        $con->eliminar($query);
    }


    // CONSULTAR LAS ULTIMAS NOTIFICACIONES
    public function consultarUltimasNotificaciones(
        $usuario_id_usuario,
        $limite = 10
    ) {
        $con = new Conexion();

        $query = "SELECT *
                  FROM notificacion
                  WHERE usuario_id_usuario = $usuario_id_usuario
                  ORDER BY fecha_creacion DESC
                  LIMIT $limite";

        return $con->consultar($query);
    }

    // ==========================================
    // OBTENER ULTIMA NOTIFICACION DE UN USUARIO
    // ==========================================
    public function consultarUltimaNotificacionUsuario($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "SELECT *
                FROM notificacion
                WHERE usuario_id_usuario = $usuario_id_usuario
                ORDER BY id_notificacion DESC
                LIMIT 1";

        return $con->consultar($query);
    }


    // COMPROBAR SI EXISTEN NOTIFICACIONES NO LEIDAS
    public function existenNotificacionesNoLeidas($usuario_id_usuario)
    {
        $con = new Conexion();

        $query = "SELECT id_notificacion
                  FROM notificacion
                  WHERE usuario_id_usuario = $usuario_id_usuario
                  AND leida = 0
                  LIMIT 1";

        return $con->consultar($query);
    }
    
}
?>