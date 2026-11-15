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
require_once "../../modelos/cita.php";
require_once "../../modelos/ficha_medica.php";
require_once "../../modelos/signos_vitales.php";

const MAX_MOTIVO = 500;


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

/* Valida un número dentro de un rango. Vacío => '' (se guarda como NULL) */
function numeroOVacio($valor, $min, $max, $nombre)
{
    $valor = str_replace(',', '.', trim((string) ($valor ?? '')));

    if ($valor === '') {
        return '';
    }

    if (!is_numeric($valor) || $valor < $min || $valor > $max) {
        fallar("$nombre debe estar entre $min y $max.");
    }

    return $valor;
}

/* Separa una presión "120/80" en [sistólica, diastólica]. Vacío => ['', ''] */
function separarPresion($valor)
{
    $valor = trim((string) ($valor ?? ''));

    if ($valor === '') {
        return ['', ''];
    }

    if (!preg_match('/^(\d{2,3})\s*\/\s*(\d{2,3})$/', $valor, $m)) {
        fallar("La presión debe tener el formato 120/80.");
    }

    $sistolica = intval($m[1]);
    $diastolica = intval($m[2]);

    if (
        $sistolica < 50 || $sistolica > 300 ||
        $diastolica < 30 || $diastolica > 200 ||
        $diastolica >= $sistolica
    ) {
        fallar("La presión ingresada no es válida.");
    }

    return [$sistolica, $diastolica];
}

/* Valida el largo del motivo de consulta */
function validarMotivo($motivo)
{
    if (mb_strlen($motivo) > MAX_MOTIVO) {
        fallar("El motivo de consulta no puede superar los " . MAX_MOTIVO . " caracteres.");
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

    $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);

    if ($id_agenda_turno <= 0) {
        fallar("Parámetros inválidos.");
    }

    $citaModel = new Cita();

    $ctx = $citaModel->obtenerContextoAgendaTurno($id_agenda_turno);

    if (!$ctx) {
        fallar("El turno asignado no existe.");
    }

    $rol = rolEnTurno($ctx);

    if ($rol === null) {
        fallar("No tenés permiso para acceder a este turno.");
    }

    switch ($action) {

        // ==========================================================
        // OBTENER LA FICHA DE LA CITA (solo el doctor del turno)
        // ==========================================================
        case "obtener":

            if ($rol !== 'doctor') {
                fallar("Solo el doctor del turno puede ver la ficha médica.");
            }

            $cita = $citaModel->obtenerPorAgendaTurno($id_agenda_turno);

            if (!$cita || !$citaModel->ambosAsistieron($cita['id_cita'])) {
                fallar("La ficha médica se habilita cuando el paciente y el doctor registran su asistencia.");
            }

            $fichaModel = new FichaMedica();
            $ficha = $fichaModel->obtenerPorCita($cita['id_cita']);

            $signos = null;

            if ($ficha) {
                $signosModel = new SignosVitales();
                $signos = $signosModel->obtenerPorFicha($ficha['id_ficha_medica']);
            }

                        // Consultas anteriores del paciente con este doctor
            $listaAnteriores = $fichaModel->listarAnterioresPaciente(
                $ctx['paciente_id_paciente'],
                $ctx['doctor_id_doctor'],
                $cita['id_cita']
            );

            if (!is_array($listaAnteriores)) {
                $listaAnteriores = [];
            }

            $anteriores = [];

            foreach ($listaAnteriores as $a) {

                $anteriores[] = [
                    "fecha" => date('d/m/Y', strtotime($a['fecha_hora'])),
                    "motivo" => $a['razon'] ?? '',
                    "diagnostico" => $a['diagnostico'] ?? '',
                    "indicaciones" => $a['indicaciones'] ?? '',
                    "tratamiento" => $a['tratamiento'] ?? '',
                    "observaciones" => $a['observaciones'] ?? ''
                ];
            }

            echo json_encode([
                "success" => true,
                "motivo" => $cita['razon'] ?? '',
                "ficha" => $ficha,
                "signos" => $signos,
                "anteriores" => $anteriores
            ]);

            exit();


        // ==========================================================
        // GUARDAR LA FICHA (solo el doctor del turno)
        //
        // usa_signos = 0 => se ignoran altura, peso y signos vitales:
        // la fila de signos_vitales se elimina y altura/peso quedan NULL.
        // ==========================================================
        case "guardar":

            if ($rol !== 'doctor') {
                fallar("Solo el doctor del turno puede guardar la ficha médica.");
            }

            $cita = $citaModel->obtenerPorAgendaTurno($id_agenda_turno);

            if (!$cita || !$citaModel->ambosAsistieron($cita['id_cita'])) {
                fallar("La ficha médica se habilita cuando el paciente y el doctor registran su asistencia.");
            }

            $usaSignos = intval($_POST["usa_signos"] ?? 0) === 1;

            $altura = '';
            $peso = '';
            $sistolica = '';
            $diastolica = '';
            $frecuencia = '';
            $temperatura = '';
            $saturacion = '';

            if ($usaSignos) {

                $altura = numeroOVacio($_POST["altura"] ?? '', 30, 250, "La altura");
                $peso = numeroOVacio($_POST["peso"] ?? '', 1, 400, "El peso");

                list($sistolica, $diastolica) = separarPresion($_POST["presion"] ?? '');

                $frecuencia = numeroOVacio($_POST["frecuencia_cardiaca"] ?? '', 20, 250, "La frecuencia cardíaca");
                $temperatura = numeroOVacio($_POST["temperatura"] ?? '', 30, 45, "La temperatura");
                $saturacion = numeroOVacio($_POST["saturacion_oxigeno"] ?? '', 50, 100, "La saturación de oxígeno");
            }

            $motivo = trim($_POST["motivo"] ?? '');
            validarMotivo($motivo);

            // 1) Ficha médica
            $fichaModel = new FichaMedica();

            $fichaModel->setCita_id_cita($cita['id_cita']);
            $fichaModel->setAltura($altura);
            $fichaModel->setPeso($peso);
            $fichaModel->setMedicacion_actual($_POST["medicacion_actual"] ?? '');
            $fichaModel->setObservaciones($_POST["observaciones"] ?? '');
            $fichaModel->setDiagnostico($_POST["diagnostico"] ?? '');
            $fichaModel->setIndicaciones($_POST["indicaciones"] ?? '');
            $fichaModel->setTratamiento($_POST["tratamiento"] ?? '');
            $fichaModel->setProximo_control($_POST["proximo_control"] ?? '');

            $id_ficha = $fichaModel->guardarOActualizar();

            if ($id_ficha === false) {
                fallar("No se pudo guardar la ficha médica.");
            }

            // 2) Signos vitales (si todos están vacíos, se elimina la fila)
            $signosModel = new SignosVitales();

            $signosModel->setFicha_medica_id_ficha_medica($id_ficha);
            $signosModel->setPresion_sistolica($sistolica);
            $signosModel->setPresion_diastolica($diastolica);
            $signosModel->setFrecuencia_cardiaca($frecuencia);
            $signosModel->setTemperatura($temperatura);
            $signosModel->setSaturacion_oxigeno($saturacion);

            if (!$signosModel->guardarOActualizar()) {
                fallar("La ficha se guardó, pero no se pudieron guardar los signos vitales.");
            }

            // 3) Motivo de consulta (cita.razon)
            if (($cita['razon'] ?? '') !== $motivo) {

                $citaModel->actualizarRazon($cita['id_cita'], $motivo);

                registrarHistorialSeguro(
                    $ctx['id_turnos'],
                    'razon',
                    $cita['razon'] ?? '',
                    $motivo,
                    'Motivo de consulta modificado por el doctor desde la ficha médica.'
                );
            }

            echo json_encode([
                "success" => true,
                "id_ficha_medica" => intval($id_ficha)
            ]);

            exit();


        // ==========================================================
        // MOTIVO DE CONSULTA (paciente o doctor del turno)
        // ==========================================================
        case "motivo":

            if (!in_array(intval($ctx['estados_id_estados']), [1, 2], true)) {
                fallar("Solo se puede modificar el motivo de turnos Activos o Pendientes.");
            }

            $motivo = trim($_POST["motivo"] ?? '');
            validarMotivo($motivo);

            $cita = $citaModel->obtenerOCrearPorAgendaTurno($id_agenda_turno);

            if (!$cita) {
                fallar("No se pudo abrir la cita.");
            }

            $anterior = $cita['razon'] ?? '';

            $resultado = $citaModel->actualizarRazon($cita['id_cita'], $motivo);

            if ($resultado === false) {
                fallar("No se pudo guardar el motivo de consulta.");
            }

            registrarHistorialSeguro(
                $ctx['id_turnos'],
                'razon',
                $anterior,
                $motivo,
                $rol === 'paciente'
                    ? 'Motivo de consulta cargado por el paciente.'
                    : 'Motivo de consulta cargado por el doctor.'
            );

            echo json_encode([
                "success" => true,
                "motivo" => $motivo
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