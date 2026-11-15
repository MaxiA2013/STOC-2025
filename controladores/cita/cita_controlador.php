<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once "../../modelos/conexion.php";
require_once "../../modelos/turno.php";
require_once "../../modelos/paciente.php";
require_once "../../modelos/doctor.php";
require_once "../../modelos/confirmacion.php";
require_once "../../modelos/cita.php";
require_once "../../modelos/notificacion.php";
require_once "../../config/citas.php";

// Páginas a las que llevan las notificaciones (ajustar al nombre real de cada página)
const URL_NOTIF_PACIENTE = 'index.php?page=miperfil_paciente';
const URL_NOTIF_DOCTOR   = 'index.php?page=historial_citas';


/* ==========================================================
   FUNCIONES AUXILIARES
========================================================== */

/* Responde con un error en formato JSON y corta la ejecución */
function fallar($error)
{
    echo json_encode([
        "success" => false,
        "error" => $error
    ]);
    exit();
}

/* Datos de la sesión del usuario: para el registro de historial */
function usuarioActual()
{
    return [
        'id'     => $_SESSION['id_usuario'] ?? null,
        'nombre' => $_SESSION['nombre_usuario'] ?? 'Sistema',
        'perfil' => $_SESSION['nombre_perfil'] ?? null
    ];
}

/* Paciente asociado al usuario logueado (o null) */
function pacienteSesion()
{
    try {

        $idUsuario = intval($_SESSION['id_usuario'] ?? 0);

        if ($idUsuario <= 0) {
            return null;
        }

        $pacienteModel = new Paciente();

        return $pacienteModel->obtenerPorUsuario($idUsuario);
    } catch (\Throwable $e) {

        error_log('Error al obtener paciente de sesión: ' . $e->getMessage());

        return null;
    }
}

/* Doctor asociado al usuario logueado (o null) */
function doctorSesion()
{
    try {

        $idUsuario = intval($_SESSION['id_usuario'] ?? 0);

        if ($idUsuario <= 0) {
            return null;
        }

        $doctorModel = new Doctor();

        return $doctorModel->obtenerPorUsuarioId($idUsuario);
    } catch (\Throwable $e) {

        error_log('Error al obtener doctor de sesión: ' . $e->getMessage());

        return null;
    }
}

/* Rol del usuario logueado respecto de ESTE turno:
 * 'paciente' si es el paciente del turno, 'doctor' si es su doctor,
 * null si no tiene relación con el turno. */
function rolEnTurno($ctx)
{
    $paciente = pacienteSesion();

    if (
        $paciente &&
        intval($paciente['id_paciente']) === intval($ctx['paciente_id_paciente'])
    ) {
        return 'paciente';
    }

    $doctor = doctorSesion();

    if (
        $doctor &&
        intval($doctor['id_doctor']) === intval($ctx['doctor_id_doctor'])
    ) {
        return 'doctor';
    }

    return null;
}

/* El turno debe estar Activo (estado 1) y ser de hoy */
function validarTurnoActivoDeHoy($ctx)
{
    if (intval($ctx['estados_id_estados']) !== 1) {
        fallar("Solo los turnos Activos admiten esta acción.");
    }

    if (date('Y-m-d', strtotime($ctx['fecha_hora'])) !== date('Y-m-d')) {
        fallar("Esta acción solo está disponible el día del turno.");
    }
}

/* La asistencia se registra a partir de unos minutos antes del turno */
function validarVentanaAsistencia($ctx)
{
    $desde = strtotime($ctx['fecha_hora']) - (MINUTOS_ANTICIPACION_ASISTENCIA * 60);

    if (time() < $desde) {
        fallar(
            "Todavía no se puede registrar la asistencia. Se habilita desde las " .
            date('H:i', $desde) . "."
        );
    }
}

function aEntero($valor)
{
    return $valor === null ? null : intval($valor);
}

function textoConfirmacion($valor)
{
    if ($valor === null) {
        return 'Sin respuesta';
    }

    return intval($valor) === 1 ? 'Confirmó' : 'No asistirá';
}

function textoAsistencia($valor)
{
    if ($valor === null) {
        return 'Sin registrar';
    }

    return intval($valor) === 1 ? 'Presente' : 'Ausente';
}

/* Registra el cambio en el historial del turno sin romper el flujo si falla */
function registrarHistorialSeguro($id_turno, $campo, $anterior, $nuevo, $detalle = null)
{
    try {

        $turno = new Turno();

        $turno->registrarHistorial(
            $id_turno,
            usuarioActual(),
            'modificado',
            $campo,
            $anterior,
            $nuevo,
            $detalle
        );
    } catch (\Throwable $e) {

        error_log('Error al registrar historial: ' . $e->getMessage());
    }
}

/* Notifica a un usuario sin romper el flujo si falla */
function notificarUsuario($usuario_id, $titulo, $mensaje, $tipo, $url)
{
    try {

        if (intval($usuario_id) <= 0) {
            return;
        }

        $notificacion = new Notificacion();

        $notificacion->crearYNotificar(
            $usuario_id,
            $titulo,
            $mensaje,
            $tipo,
            $url
        );
    } catch (\Throwable $e) {

        error_log('Error al notificar: ' . $e->getMessage());
    }
}


/* ==========================================================
   ACCIONES
========================================================== */

$action = $_POST["action"] ?? $_GET["action"] ?? null;

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        fallar("Método no permitido.");
    }

    switch ($action) {

        // CONFIRMAR ASISTENCIA (día del turno: solo funciona el dia del turno)
        //
        // Paciente: responde su propia confirmación.
        // Doctor:   responde la suya.
        // La fila de "confirmacion" la crea el aviso diario
        // (enviar_confirmaciones.php).
        
        case "confirmar":

            $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);
            $respuesta = intval($_POST["respuesta"] ?? -1);

            if ($id_agenda_turno <= 0 || !in_array($respuesta, [0, 1], true)) {
                fallar("Parámetros inválidos.");
            }

            $citaModel = new Cita();

            $ctx = $citaModel->obtenerContextoAgendaTurno($id_agenda_turno);

            if (!$ctx) {
                fallar("El turno asignado no existe.");
            }

            $rol = rolEnTurno($ctx);

            if ($rol === null) {
                fallar("No tenés permiso para responder sobre este turno.");
            }

            validarTurnoActivoDeHoy($ctx);

            $confModel = new Confirmacion();

            $conf = $confModel->obtenerPorAgendaTurno($id_agenda_turno);

            if (!$conf) {
                fallar("La confirmación se habilita cuando se envía el aviso del día.");
            }

            $esPaciente = ($rol === 'paciente');

            $campo = $esPaciente
                ? 'confirmacion_paciente'
                : 'confirmacion_doctor';

            $anterior = $conf[$campo];

                        // El paciente puede cambiar una respuesta ya dada hasta cierta hora antes del turno
            if ($esPaciente && $anterior !== null) {

                $limiteCambio = strtotime($ctx['fecha_hora'])
                    - (MINUTOS_LIMITE_CAMBIO_CONFIRMACION * 60);

                if (time() > $limiteCambio) {
                    fallar(
                        "Ya no se puede cambiar la respuesta. El límite era a las " .
                        date('H:i', $limiteCambio) . "."
                    );
                }
            }

            $resultado = $confModel->actualizarConfirmacion(
                $conf['id_confirmacion'],
                $esPaciente,
                $respuesta
            );

            if ($resultado === false) {
                fallar("No se pudo registrar la respuesta.");
            }

            registrarHistorialSeguro(
                $ctx['id_turnos'],
                $campo,
                textoConfirmacion($anterior),
                textoConfirmacion($respuesta),
                $esPaciente
                    ? 'Respuesta del paciente al aviso del día.'
                    : 'Respuesta del doctor al aviso del día.'
            );

            // Si alguien indica que no asistirá, se avisa a la otra parte
            if ($respuesta === 0) {

                $hora = date('H:i', strtotime($ctx['fecha_hora']));

                if ($esPaciente) {

                    notificarUsuario(
                        $ctx['doctor_usuario_id'],
                        'Un paciente no asistirá',
                        trim($ctx['paciente_nombre'] . ' ' . $ctx['paciente_apellido']) .
                            ' indicó que no asistirá a la cita de las ' . $hora . '.',
                        'confirmacion_turno',
                        URL_NOTIF_DOCTOR
                    );
                } else {

                    notificarUsuario(
                        $ctx['paciente_usuario_id'],
                        'Tu doctor no podrá asistir',
                        'El Dr. ' . trim($ctx['doctor_apellido']) .
                            ' indicó que no podrá asistir a la cita de las ' . $hora . '.',
                        'confirmacion_turno',
                        URL_NOTIF_PACIENTE
                    );
                }
            }

            $confActual = $confModel->obtenerPorId($conf['id_confirmacion']);

            echo json_encode([
                "success" => true,
                "confirmacion_paciente" => aEntero($confActual['confirmacion_paciente']),
                "confirmacion_doctor"   => aEntero($confActual['confirmacion_doctor'])
            ]);

            exit();


        // ==========================================================
        // REGISTRAR ASISTENCIA (check a la hora de la cita)
        //
        // Paciente: marca su propia asistencia.
        // Doctor:   marca la suya, o la del paciente cuando este no
        //           hizo el check (parte = paciente).
        // Cuando ambas son "presente", la ficha médica queda habilitada.
        // ==========================================================
        case "asistencia":

            $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);
            $valor = intval($_POST["valor"] ?? -1);
            $parte = $_POST["parte"] ?? '';

            if ($id_agenda_turno <= 0 || !in_array($valor, [0, 1], true)) {
                fallar("Parámetros inválidos.");
            }

            $citaModel = new Cita();

            $ctx = $citaModel->obtenerContextoAgendaTurno($id_agenda_turno);

            if (!$ctx) {
                fallar("El turno asignado no existe.");
            }

            $rol = rolEnTurno($ctx);

            if ($rol === null) {
                fallar("No tenés permiso para registrar la asistencia de este turno.");
            }

            validarTurnoActivoDeHoy($ctx);
            validarVentanaAsistencia($ctx);

            // A quién corresponde la asistencia que se está registrando
            if ($rol === 'paciente') {

                $esPaciente = true;
                $porDoctor = false;
            } else {

                $esPaciente = ($parte === 'paciente');
                $porDoctor = $esPaciente;
            }

            $cita = $citaModel->obtenerOCrearPorAgendaTurno($id_agenda_turno);

            if (!$cita) {
                fallar("No se pudo abrir la cita.");
            }

            // El doctor solo marca al paciente si este no hizo su propio check
            if (
                $porDoctor &&
                $cita['asistencia_paciente'] !== null &&
                intval($cita['asistencia_paciente_por_doctor']) !== 1
            ) {
                fallar("El paciente ya registró su propia asistencia.");
            }

            $campo = $esPaciente
                ? 'asistencia_paciente'
                : 'asistencia_doctor';

            $anterior = $cita[$campo];

            $resultado = $citaModel->registrarAsistencia(
                $cita['id_cita'],
                $esPaciente,
                $valor,
                $porDoctor
            );

            if ($resultado === false) {
                fallar("No se pudo registrar la asistencia.");
            }

            registrarHistorialSeguro(
                $ctx['id_turnos'],
                $campo,
                textoAsistencia($anterior),
                textoAsistencia($valor),
                $porDoctor
                    ? 'Asistencia del paciente marcada por el doctor.'
                    : 'Asistencia registrada por la propia parte.'
            );

            // Aviso a la otra parte cuando alguien registra presencia
            if ($valor === 1) {

                $hora = date('H:i', strtotime($ctx['fecha_hora']));

                if ($esPaciente && !$porDoctor) {

                    notificarUsuario(
                        $ctx['doctor_usuario_id'],
                        'Paciente presente',
                        trim($ctx['paciente_nombre'] . ' ' . $ctx['paciente_apellido']) .
                            ' registró su asistencia a la cita de las ' . $hora . '.',
                        'asistencia_turno',
                        URL_NOTIF_DOCTOR
                    );
                } elseif ($porDoctor) {

                    notificarUsuario(
                        $ctx['paciente_usuario_id'],
                        'Asistencia registrada',
                        'El Dr. ' . trim($ctx['doctor_apellido']) .
                            ' registró tu asistencia a la cita de las ' . $hora . '.',
                        'asistencia_turno',
                        URL_NOTIF_PACIENTE
                    );
                } else {

                    notificarUsuario(
                        $ctx['paciente_usuario_id'],
                        'Tu doctor ya está en la consulta',
                        'El Dr. ' . trim($ctx['doctor_apellido']) .
                            ' registró su asistencia a la cita de las ' . $hora . '.',
                        'asistencia_turno',
                        URL_NOTIF_PACIENTE
                    );
                }
            }

            $citaActual = $citaModel->obtenerPorId($cita['id_cita']);

            $asistePaciente = aEntero($citaActual['asistencia_paciente']);
            $asisteDoctor = aEntero($citaActual['asistencia_doctor']);

            echo json_encode([
                "success" => true,
                "id_cita" => intval($citaActual['id_cita']),
                "asistencia_paciente" => $asistePaciente,
                "asistencia_doctor" => $asisteDoctor,
                "asistencia_paciente_por_doctor" => intval($citaActual['asistencia_paciente_por_doctor']),
                "ficha_habilitada" => ($asistePaciente === 1 && $asisteDoctor === 1)
            ]);

            exit();


        default:

            fallar("Acción no válida.");
    }
} catch (\Throwable $ex) {

    echo json_encode([
        "success" => false,
        "error" => $ex->getMessage()
    ]);
    exit();
}