<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); //sin esto no anda lo del historial
}

header('Content-Type: application/json; charset=utf-8');

/* Funcion para guardar datos de sesion de usuario: para el registro de historial de actividades */
function usuarioActual()
{
    return [
        'id'     => $_SESSION['id_usuario'] ?? null,
        'nombre' => $_SESSION['nombre_usuario'] ?? 'Sistema',
        'perfil' => $_SESSION['nombre_perfil'] ?? null

    ];
}

/**
 * Valida compatibilidad obra social-doctor
 * y el paciente (intersección real entre doctor_obra_social
 * y paciente_obra_social). Devuelve true si es válida o si
 * no se pidió obra social ($obra_social_id <= 0).
 */
function obraSocialEsValida($con, $id_doctor, $paciente_id, $obra_social_id)
{
    if ($obra_social_id <= 0) {
        return true;
    }

    $resultado = $con->consultarArray("
        SELECT dos.obra_social_id_obra_social
        FROM doctor_obra_social dos
        INNER JOIN paciente_obra_social pos
            ON pos.obra_social_id_obra_social = dos.obra_social_id_obra_social
        WHERE dos.doctor_id_doctor = $id_doctor
          AND pos.paciente_id_paciente = $paciente_id
          AND dos.obra_social_id_obra_social = $obra_social_id
        LIMIT 1
    ");

    return !empty($resultado);
}


require_once "../../modelos/turno.php";
require_once "../../modelos/agenda.php";
require_once "../../modelos/agenda_turno.php";
require_once "../../modelos/conexion.php";

$action = $_POST["action"] ?? $_GET["action"] ?? null;

try {

    switch ($action) {

        // ==========================================================
        // GENERAR TURNOS A PARTIR DE UNA AGENDA
        // ==========================================================
        case "generar_turnos":

            $id_agenda = intval($_POST["id_agenda"] ?? 0);
            $min       = intval($_POST["minutos"] ?? 0);

            if ($id_agenda <= 0 || $min <= 0) {
                echo json_encode([
                    "success" => false,
                    "error" => "Parámetros inválidos."
                ]);
                exit();
            }

            $agenda = new Agenda();
            $datos = $agenda->obtenerPorId($id_agenda);

            if (!$datos) {
                echo json_encode([
                    "success" => false,
                    "error" => "Agenda no encontrada."
                ]);
                exit();
            }

            $start = strtotime(
                $datos['fecha_desde'] . ' ' . $datos['hora_desde']
            );

            $end = strtotime(
                $datos['fecha_desde'] . ' ' . $datos['hora_hasta']
            );

            $turno = new Turno();

            while ($start < $end) {

                $turno->setAgenda_id_agenda($id_agenda);
                $turno->setMinutos_turnos($min);
                $turno->setFecha_hora(
                    date("Y-m-d H:i:s", $start)
                );
                $turno->setDisponible(1);

                $resultado = $turno->guardarTurno();

                if ($resultado === false) {
                    echo json_encode([
                        "success" => false,
                        "error" => "No se pudo generar uno de los turnos."
                    ]);
                    exit();
                }

                $start = strtotime("+{$min} minutes", $start);
            }

            echo json_encode([
                "success" => true
            ]);

            exit();


            // ==========================================================
            // INSERTAR
            //
            // modo_turno = agregar
            //      -> inserta un turno en tabla turno
            //
            // modo_turno = asignar
            //      -> inserta agenda_turno y ocupa el turno
            // ==========================================================
        case "insertar":

            $modo = $_POST['modo_turno'] ?? 'agregar';

            // MODO AGREGAR TURNO
            if ($modo === 'agregar') {

                $minutos = intval($_POST["minutos_turnos"] ?? 0);
                $fecha_hora = trim($_POST["fecha_hora"] ?? "");
                $disponible = isset($_POST["disponible"])
                    ? intval($_POST["disponible"])
                    : 1;

                $agenda_id = intval($_POST["agenda_id_agenda"] ?? 0);


                                $paciente_id = intval($_POST["paciente_id"] ?? 0);

                $estado_id = intval($_POST["estados_id_estados"] ?? 2);

                $con_obra_social = intval($_POST["con_obra_social"] ?? 0) === 1 ? 1 : 0;
                $obra_social_id  = intval($_POST["obra_social_id"] ?? 0);

                if ($con_obra_social !== 1) {
                    $obra_social_id = 0;
                }


                // ==========================================
                // VALIDACIONES
                // ==========================================

                if ($minutos <= 0 || $fecha_hora === "" || $agenda_id <= 0) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Faltan parámetros obligatorios."
                    ]);
                    exit();
                }

                $con = new Conexion();

                $agendaDoctor = $con->consultarArray("
                    SELECT doctor_id_doctor
                    FROM agenda
                    WHERE id_agenda = $agenda_id
                    LIMIT 1
                ");

                if (empty($agendaDoctor)) {
                    echo json_encode([
                        "success" => false,
                        "error" => "La agenda seleccionada no existe."
                    ]);
                    exit();
                }

                $id_doctor = intval($agendaDoctor[0]['doctor_id_doctor']);


                // Si el turno se crea como NO disponible, es obligatorio
                // que venga un paciente seleccionado (el form ya lo exige,
                // pero también se valida del lado del servidor).
                if ($disponible === 0 && $paciente_id <= 0) {
                    echo json_encode([
                        "success" => false,
                        "error" => "Debe seleccionar un paciente."
                    ]);
                    exit();
                }

                // El paciente se asigna automáticamente cuando el turno
                // se marca como NO disponible y hay un paciente elegido.
                // (antes dependía de un campo "asignar_paciente" que el
                // formulario nunca enviaba)
                $asignarPaciente = ($disponible === 0 && $paciente_id > 0) ? 1 : 0;


                                // Si se quiere asignar, debe existir paciente
                if ($asignarPaciente === 1 && $paciente_id <= 0) {

                    echo json_encode([
                        "success" => false,
                        "error" => "Debe seleccionar un paciente."
                    ]);

                    exit();
                }

                // Validar compatibilidad de obra social (solo aplica
                // si efectivamente se va a asignar un paciente CON obra social)
                if (
                    $asignarPaciente === 1 &&
                    $con_obra_social === 1 &&
                    !obraSocialEsValida($con, $id_doctor, $paciente_id, $obra_social_id)
                ) {

                    echo json_encode([
                        "success" => false,
                        "error" => "La obra social seleccionada no es válida para este doctor y paciente."
                    ]);

                    exit();
                }


                // CREAR / RECUPERAR TURNO
                $turno = new Turno();
                $turno->setAgenda_id_agenda($agenda_id);
                $turno->setMinutos_turnos($minutos);
                $turno->setFecha_hora($fecha_hora);

                                // Si se asignará paciente, debe quedar NO disponible
                $turno->setDisponible(
                    $asignarPaciente === 1 ? 0 : ($disponible ? 1 : 0)
                );

                // "con obra social" solo tiene sentido si además
                // hay un paciente al que asignarle el turno.
                $turno->setCon_obra_social(
                    $asignarPaciente === 1 ? $con_obra_social : 0
                );

                if ($asignarPaciente === 1 && $con_obra_social === 1 && $obra_social_id > 0) {
                    $turno->setObra_social_id_obra_social($obra_social_id);
                }

                $id_turno = $turno->guardarTurno();


                if (!$id_turno) {

                    echo json_encode([
                        "success" => false,
                        "error" => "No se pudo crear el turno."
                    ]);

                    exit();
                }


                // SI NO SE ASIGNA PACIENTE
                if ($asignarPaciente !== 1) {

                    $turno->registrarHistorial(
                        $id_turno,
                        usuarioActual(),
                        'creado',
                        null,
                        null,
                        null,
                        'Turno creado, disponible'
                    );

                    echo json_encode([
                        "success" => true,
                        "id_turno" => $id_turno,
                        "asignado" => false
                    ]);

                    exit();
                }

                // OBTENER INFORMACIÓN DEL TURNO
                $infoTurno = $con->consultarArray("
                    SELECT
                        t.id_turnos,
                        t.disponible,
                        a.id_agenda,
                        d.id_doctor
                    FROM turno t
                    INNER JOIN agenda a
                        ON t.agenda_id_agenda = a.id_agenda
                    INNER JOIN doctor d
                        ON a.doctor_id_doctor = d.id_doctor
                    WHERE t.id_turnos = $id_turno
                    LIMIT 1
                ");

                if (empty($infoTurno)) {

                    echo json_encode([
                        "success" => false,
                        "error" => "No se pudo obtener la información del turno."
                    ]);

                    exit();
                }

                $id_agenda = intval($infoTurno[0]["id_agenda"]);
                $id_doctor = intval($infoTurno[0]["id_doctor"]);


                // COMPROBAR DUPLICADO DEL PACIENTE
                $duplicado = $con->consultarArray("
                    SELECT at.id_agenda_turno
                    FROM agenda_turno at

                    INNER JOIN turno t2
                        ON at.turno_id_turnos = t2.id_turnos

                    INNER JOIN agenda a2
                        ON t2.agenda_id_agenda = a2.id_agenda

                    INNER JOIN doctor d2
                        ON a2.doctor_id_doctor = d2.id_doctor

                    INNER JOIN estados e
                        ON at.estados_id_estados = e.id_estados

                    WHERE at.paciente_id_paciente = $paciente_id
                    AND a2.id_agenda = $id_agenda
                    AND d2.id_doctor = $id_doctor
                    AND e.tipo_estado IN ('Pendiente', 'Activo')

                    LIMIT 1
                ");


                if (!empty($duplicado)) {

                    echo json_encode([
                        "success" => false,
                        "error" => "El paciente ya tiene un turno activo/pendiente con este doctor en esta agenda."
                    ]);

                    exit();
                }


                // CREAR AGENDA_TURNO
                $agendaTurno = new AgendaTurno();

                $agendaTurno->setPaciente_id_paciente($paciente_id);
                $agendaTurno->setTurno_id_turnos($id_turno);
                $agendaTurno->setEstados_id_estados($estado_id);

                $id_agenda_turno = $agendaTurno->insertar();


                if (!$id_agenda_turno) {

                    // Si falló la asignación, liberamos el turno
                    $con->actualizar("
                        UPDATE turno
                        SET disponible = 1
                        WHERE id_turnos = $id_turno
                    ");

                    echo json_encode([
                        "success" => false,
                        "error" => "No se pudo asignar el paciente al turno."
                    ]);

                    exit();
                }

                //registro de auditoría
                $usuarioAct = usuarioActual();

                $turno->registrarHistorial(
                    $id_turno,
                    $usuarioAct,
                    'creado',
                    null,
                    null,
                    null,
                    'Turno creado directamente como no disponible, para asignar a un paciente.'
                );

                                $turno->registrarHistorial(
                    $id_turno,
                    $usuarioAct,
                    'asignado',
                    'paciente_id',
                    null,
                    $paciente_id,
                    "Asignado a paciente #$paciente_id al momento de crear el turno."
                );

                if ($con_obra_social === 1 && $obra_social_id > 0) {
                    $turno->registrarHistorial(
                        $id_turno,
                        $usuarioAct,
                        'modificado',
                        'con_obra_social',
                        'No',
                        'Sí',
                        "Turno tomado con obra social #$obra_social_id."
                    );
                }

                // Mensaje json de exito
                echo json_encode([
                    "success" => true,
                    "id_turno" => $id_turno,
                    "id_agenda_turno" => $id_agenda_turno,
                    "asignado" => true
                ]);

                exit();
            }


            // MODO ASIGNAR TURNO A PACIENTE
            $paciente_id = intval(
                $_POST["paciente_id"] ?? 0
            );

            $turno_id = intval(
                $_POST["turno_id"] ??
                    $_POST["turno_existente_id"] ??
                    0
            );

                        $estado_id = intval(
                $_POST["estados_id_estados"] ?? 2
            );

            $con_obra_social = intval($_POST["con_obra_social"] ?? 0) === 1 ? 1 : 0;
            $obra_social_id  = intval($_POST["obra_social_id"] ?? 0);

            if ($con_obra_social !== 1) {
                $obra_social_id = 0;
            }


            if (
                $paciente_id <= 0 ||
                $turno_id <= 0
            ) {

                echo json_encode([
                    "success" => false,
                    "error" => "Faltan parámetros (paciente o turno)."
                ]);

                exit();
            }
            $con = new Conexion();

            // Verificar turno
            $turnoInfo = $con->consultarArray("
                SELECT
                    t.id_turnos,
                    t.disponible,
                    t.agenda_id_agenda,
                    a.doctor_id_doctor
                FROM turno t
                INNER JOIN agenda a
                    ON t.agenda_id_agenda = a.id_agenda
                WHERE t.id_turnos = $turno_id
                LIMIT 1
            ");


            if (empty($turnoInfo)) {

                echo json_encode([
                    "success" => false,
                    "error" => "El turno no existe."
                ]);

                exit();
            }


            if (
                intval($turnoInfo[0]['disponible']) !== 1
            ) {

                echo json_encode([
                    "success" => false,
                    "error" => "El turno no está disponible."
                ]);

                exit();
            }


            $id_agenda = intval(
                $turnoInfo[0]['agenda_id_agenda']
            );

                        $id_doctor = intval(
                $turnoInfo[0]['doctor_id_doctor']
            );

            if (
                $con_obra_social === 1 &&
                !obraSocialEsValida($con, $id_doctor, $paciente_id, $obra_social_id)
            ) {

                echo json_encode([
                    "success" => false,
                    "error" => "La obra social seleccionada no es válida para este doctor y paciente."
                ]);

                exit();
            }


            // Verificar duplicado paciente + doctor + agenda
            $turnoDuplicado = $con->consultarArray("
                SELECT at.id_agenda_turno
                FROM agenda_turno at

                INNER JOIN turno t2
                    ON at.turno_id_turnos = t2.id_turnos

                INNER JOIN agenda a2
                    ON t2.agenda_id_agenda = a2.id_agenda

                INNER JOIN doctor d2
                    ON a2.doctor_id_doctor = d2.id_doctor

                INNER JOIN estados e
                    ON at.estados_id_estados = e.id_estados

                WHERE at.paciente_id_paciente = $paciente_id
                  AND a2.id_agenda = $id_agenda
                  AND d2.id_doctor = $id_doctor
                  AND e.tipo_estado IN ('Pendiente', 'Activo')

                LIMIT 1
            ");


            if (!empty($turnoDuplicado)) {

                echo json_encode([
                    "success" => false,
                    "error" => "El paciente ya tiene un turno activo o pendiente con este doctor en esta agenda."
                ]);

                exit();
            }


            // Insertar agenda_turno
            $agendaTurno = new AgendaTurno();

            $agendaTurno->setPaciente_id_paciente(
                $paciente_id
            );

            $agendaTurno->setTurno_id_turnos(
                $turno_id
            );

            $agendaTurno->setEstados_id_estados(
                $estado_id
            );


            $id_agenda_turno = $agendaTurno->insertar();


            if (!$id_agenda_turno) {

                echo json_encode([
                    "success" => false,
                    "error" => "No se pudo asignar el turno."
                ]);

                exit();
            }


            // Marcar turno como ocupado
            $resultadoDisponible = $con->actualizar("
                UPDATE turno
                SET disponible = 0
                WHERE id_turnos = $turno_id
            ");


            if ($resultadoDisponible === false) {
                // Intentar eliminar la asignación creada
                $agendaTurno->eliminar(
                    $id_agenda_turno
                );

                echo json_encode([
                    "success" => false,
                    "error" => "No se pudo actualizar la disponibilidad del turno."
                ]);

                exit();
            }

                        $turno = new Turno();

            if ($con_obra_social === 1 && $obra_social_id > 0) {
                $con->actualizar("
                    UPDATE turno
                    SET con_obra_social = 1, obra_social_id_obra_social = $obra_social_id
                    WHERE id_turnos = $turno_id
                ");
            }

            $usuarioAct = usuarioActual();

            $turno->registrarHistorial(
                $turno_id,
                $usuarioAct,
                'asignado',
                'paciente_id',
                null,
                $paciente_id,
                "Turno existente asignado a paciente #$paciente_id"
            );

            if ($con_obra_social === 1 && $obra_social_id > 0) {
                $turno->registrarHistorial(
                    $turno_id,
                    $usuarioAct,
                    'modificado',
                    'con_obra_social',
                    'No',
                    'Sí',
                    "Turno tomado con obra social #$obra_social_id."
                );
            }

            // Terminar aquí el case insertar.
            echo json_encode([
                "success" => true,
                "id_agenda_turno" => $id_agenda_turno
            ]);

            exit();


            // ACTUALIZAR TURNO DISPONIBLE
            // Modifica un registro de la tabla turno.
        case "actualizar":

            $id_turno = intval(
                $_POST["id_turnos"] ?? 0
            );

            $minutos = intval(
                $_POST["minutos_turnos"] ?? 0
            );

            $fecha_hora = trim(
                $_POST["fecha_hora"] ?? ""
            );

            $disponible = isset($_POST["disponible"])
                ? intval($_POST["disponible"])
                : 1;

            $agenda_id = intval(
                $_POST["agenda_id_agenda"] ?? 0
            );

                        $paciente_id = intval(
                $_POST["paciente_id"] ?? 0
            );

            $estado_id = intval(
                $_POST["estados_id_estados"] ?? 2
            );

            $con_obra_social = intval($_POST["con_obra_social"] ?? 0) === 1 ? 1 : 0;
            $obra_social_id  = intval($_POST["obra_social_id"] ?? 0);

            if ($con_obra_social !== 1) {
                $obra_social_id = 0;
            }


            if (
                $id_turno <= 0 ||
                $minutos <= 0 ||
                $fecha_hora === "" ||
                $agenda_id <= 0
            ) {

                echo json_encode([
                    "success" => false,
                    "error" => "Faltan parámetros obligatorios para actualizar el turno."
                ]);

                exit();
            }


            // Si se marca como NO disponible, es obligatorio
            // elegir un paciente para asignarle el turno.
            if ($disponible === 0 && $paciente_id <= 0) {

                echo json_encode([
                    "success" => false,
                    "error" => "Debe seleccionar un paciente."
                ]);

                exit();
            }


            $con = new Conexion();


            // ------------------------------------------------------
            // Verificar que el turno exista
            // ------------------------------------------------------

            $turnoActual = $con->consultarArray("
                SELECT
                    id_turnos,
                    disponible,
                    agenda_id_agenda,
                    minutos_turnos,
                    fecha_hora
                FROM turno
                WHERE id_turnos = $id_turno
                LIMIT 1
            ");


            if (empty($turnoActual)) {

                echo json_encode([
                    "success" => false,
                    "error" => "El turno no existe."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // No permitir modificar un turno que YA está asignado
            // (eso se edita desde la pestaña "Turnos Asignados")
            // ------------------------------------------------------

            $asignado = $con->consultarArray("
                SELECT id_agenda_turno
                FROM agenda_turno
                WHERE turno_id_turnos = $id_turno
                LIMIT 1
            ");


            if (!empty($asignado)) {

                echo json_encode([
                    "success" => false,
                    "error" => "No se puede modificar directamente un turno que ya está asignado a un paciente."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Verificar agenda
            // ------------------------------------------------------

            $agendaExiste = $con->consultarArray("
                SELECT id_agenda
                FROM agenda
                WHERE id_agenda = $agenda_id
                LIMIT 1
            ");


            if (empty($agendaExiste)) {

                echo json_encode([
                    "success" => false,
                    "error" => "La agenda seleccionada no existe."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Evitar fecha/hora duplicada
            // ------------------------------------------------------

            $duplicado = $con->consultarArray("
                SELECT id_turnos
                FROM turno
                WHERE agenda_id_agenda = $agenda_id
                  AND fecha_hora = '$fecha_hora'
                  AND id_turnos <> $id_turno
                LIMIT 1
            ");


            if (!empty($duplicado)) {

                echo json_encode([
                    "success" => false,
                    "error" => "Ya existe otro turno para esa fecha y hora en la agenda seleccionada."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Actualizar datos base del turno
            // ------------------------------------------------------

            $turno = new Turno();

            $turno->setId_turnos(
                $id_turno
            );

            $turno->setMinutos_turnos(
                $minutos
            );

            $turno->setFecha_hora(
                $fecha_hora
            );

            // Si además se va a asignar un paciente en este mismo
            // paso, el turno debe terminar NO disponible sí o sí.
            $turno->setDisponible(
                ($disponible === 0 && $paciente_id > 0) ? 0 : ($disponible ? 1 : 0)
            );

                        $turno->setAgenda_id_agenda(
                $agenda_id
            );

            // Si además se asigna paciente en este mismo paso,
            // "con obra social" y la obra social elegida viajan
            // junto con el resto de los cambios.
            $seAsignaPacienteAhora = ($disponible === 0 && $paciente_id > 0);

            $turno->setCon_obra_social(
                $seAsignaPacienteAhora ? $con_obra_social : 0
            );

            if ($seAsignaPacienteAhora && $con_obra_social === 1 && $obra_social_id > 0) {
                $turno->setObra_social_id_obra_social($obra_social_id);
            }


            $resultado = $turno->actualizar();


            if ($resultado === false) {

                echo json_encode([
                    "success" => false,
                    "error" => "No se pudo actualizar el turno."
                ]);

                exit();
            }

            // Si no se pidió asignar paciente, termina acá
            if ($disponible !== 0 || $paciente_id <= 0) {

                $anterior = $turnoActual[0];
                $usuarioAct = usuarioActual();

                if (intval($anterior['minutos_turnos']) !== $minutos) {
                    $turno->registrarHistorial(
                        $id_turno,
                        $usuarioAct,
                        'modificado',
                        'minutos_turnos',
                        $anterior['minutos_turnos'],
                        $minutos
                    );
                }

                if ($anterior['fecha_hora'] !== $fecha_hora) {
                    $turno->registrarHistorial(
                        $id_turno,
                        $usuarioAct,
                        'modificado',
                        'fecha_hora',
                        $anterior['fecha_hora'],
                        $fecha_hora
                    );
                }

                if (intval($anterior['disponible']) !== $disponible) {
                    $turno->registrarHistorial(
                        $id_turno,
                        $usuarioAct,
                        'modificado',
                        'disponible',
                        $anterior['disponible'] ? 'Sí' : 'No',
                        $disponible ? 'Sí' : 'No'
                    );
                }

                if (intval($anterior['agenda_id_agenda']) !== $agenda_id) {
                    $turno->registrarHistorial(
                        $id_turno,
                        $usuarioAct,
                        'modificado',
                        'agenda_id_agenda',
                        $anterior['agenda_id_agenda'],
                        $agenda_id
                    );
                }

                echo json_encode([
                    "success" => true,
                    "id_turno" => $id_turno,
                    "asignado" => false
                ]);

                exit();
            }


            // ==========================================================
            // ASIGNAR PACIENTE AL MODIFICAR
            // Mismo patrón que en "insertar" (modo agregar):
            // validar duplicado paciente+doctor+agenda, crear
            // agenda_turno, y si algo falla, revertir disponible.
            // ==========================================================

            $infoTurno = $con->consultarArray("
                SELECT
                    t.id_turnos,
                    a.id_agenda,
                    d.id_doctor
                FROM turno t
                INNER JOIN agenda a
                    ON t.agenda_id_agenda = a.id_agenda
                INNER JOIN doctor d
                    ON a.doctor_id_doctor = d.id_doctor
                WHERE t.id_turnos = $id_turno
                LIMIT 1
            ");


            if (empty($infoTurno)) {

                echo json_encode([
                    "success" => false,
                    "error" => "El turno se actualizó, pero no se pudo obtener su información para asignar el paciente."
                ]);

                exit();
            }


            $id_agenda_info = intval($infoTurno[0]["id_agenda"]);
            $id_doctor_info = intval($infoTurno[0]["id_doctor"]);


            $duplicadoPaciente = $con->consultarArray("
                SELECT at.id_agenda_turno
                FROM agenda_turno at

                INNER JOIN turno t2
                    ON at.turno_id_turnos = t2.id_turnos

                INNER JOIN agenda a2
                    ON t2.agenda_id_agenda = a2.id_agenda

                INNER JOIN doctor d2
                    ON a2.doctor_id_doctor = d2.id_doctor

                INNER JOIN estados e
                    ON at.estados_id_estados = e.id_estados

                WHERE at.paciente_id_paciente = $paciente_id
                  AND a2.id_agenda = $id_agenda_info
                  AND d2.id_doctor = $id_doctor_info
                  AND e.tipo_estado IN ('Pendiente', 'Activo')

                LIMIT 1
            ");


                        if (!empty($duplicadoPaciente)) {

                // Revertir disponibilidad, ya que no se pudo asignar
                $con->actualizar("
                    UPDATE turno
                    SET disponible = 1
                    WHERE id_turnos = $id_turno
                ");

                echo json_encode([
                    "success" => false,
                    "error" => "El paciente ya tiene un turno activo/pendiente con este doctor en esta agenda."
                ]);

                exit();
            }

            if (
                $con_obra_social === 1 &&
                !obraSocialEsValida($con, $id_doctor_info, $paciente_id, $obra_social_id)
            ) {

                // Revertir disponibilidad y limpiar obra social
                $con->actualizar("
                    UPDATE turno
                    SET disponible = 1, con_obra_social = 0, obra_social_id_obra_social = NULL
                    WHERE id_turnos = $id_turno
                ");

                echo json_encode([
                    "success" => false,
                    "error" => "La obra social seleccionada no es válida para este doctor y paciente."
                ]);

                exit();
            }


            $agendaTurno = new AgendaTurno();

            $agendaTurno->setPaciente_id_paciente($paciente_id);
            $agendaTurno->setTurno_id_turnos($id_turno);
            $agendaTurno->setEstados_id_estados($estado_id);

            $id_agenda_turno = $agendaTurno->insertar();


            if (!$id_agenda_turno) {

                // Revertir disponibilidad
                $con->actualizar("
                    UPDATE turno
                    SET disponible = 1
                    WHERE id_turnos = $id_turno
                ");

                echo json_encode([
                    "success" => false,
                    "error" => "El turno se actualizó, pero no se pudo asignar el paciente."
                ]);

                exit();
            }

            $anterior = $turnoActual[0];
            $usuarioAct = usuarioActual();

            if (intval($anterior['minutos_turnos']) !== $minutos) {
                $turno->registrarHistorial(
                    $id_turno,
                    $usuarioAct,
                    'modificado',
                    'minutos_turnos',
                    $anterior['minutos_turnos'],
                    $minutos
                );
            }

            if ($anterior['fecha_hora'] !== $fecha_hora) {
                $turno->registrarHistorial(
                    $id_turno,
                    $usuarioAct,
                    'modificado',
                    'fecha_hora',
                    $anterior['fecha_hora'],
                    $fecha_hora
                );
            }

            if (intval($anterior['disponible']) !== 0) {
                $turno->registrarHistorial(
                    $id_turno,
                    $usuarioAct,
                    'modificado',
                    'disponible',
                    $anterior['disponible'] ? 'Sí' : 'No',
                    'No'
                );
            }

            if (intval($anterior['agenda_id_agenda']) !== $agenda_id) {
                $turno->registrarHistorial(
                    $id_turno,
                    $usuarioAct,
                    'modificado',
                    'agenda_id_agenda',
                    $anterior['agenda_id_agenda'],
                    $agenda_id
                );
            }

                       $turno->registrarHistorial(
                $id_turno,
                $usuarioAct,
                'asignado',
                'paciente_id',
                null,
                $paciente_id,
                "Asignado a paciente #$paciente_id al modificar el turno."
            );

            if ($con_obra_social === 1 && $obra_social_id > 0) {
                $turno->registrarHistorial(
                    $id_turno,
                    $usuarioAct,
                    'modificado',
                    'con_obra_social',
                    'No',
                    'Sí',
                    "Turno tomado con obra social #$obra_social_id."
                );
            }

            echo json_encode([
                "success" => true,
                "id_turno" => $id_turno,
                "id_agenda_turno" => $id_agenda_turno,
                "asignado" => true
            ]);

            exit();

            // EDITAR TURNO ASIGNADO
        case "editar_asignado":

            $id_agenda_turno = intval(
                $_POST["id_agenda_turno"] ?? 0
            );

            $paciente_id = intval(
                $_POST["paciente_id_paciente"] ?? 0
            );

            $turno_nuevo_id = intval(
                $_POST["turno_id"] ??
                    $_POST["turno_id_turnos"] ??
                    $_POST["turno_existente_id"] ??
                    0
            );

            $estado_id = intval(
                $_POST["estados_id_estados"] ?? 1
            );

            $con_obra_social = intval($_POST["con_obra_social"] ?? 0) === 1 ? 1 : 0;
            $obra_social_id  = intval($_POST["obra_social_id"] ?? 0);

            if ($con_obra_social !== 1) {
                $obra_social_id = 0;
            }


            if (
                $id_agenda_turno <= 0 ||
                $paciente_id <= 0 ||
                $turno_nuevo_id <= 0
            ) {

                echo json_encode([
                    "success" => false,
                    "error" => "Faltan parámetros obligatorios."
                ]);

                exit();
            }


            $con = new Conexion();


            // ------------------------------------------------------
            // Obtener asignación actual
            // ------------------------------------------------------

            $actual = $con->consultarArray("
                SELECT
                    id_agenda_turno,
                    turno_id_turnos,
                    paciente_id_paciente,
                    estados_id_estados
                FROM agenda_turno
                WHERE id_agenda_turno = $id_agenda_turno
                LIMIT 1
            ");


            if (empty($actual)) {

                echo json_encode([
                    "success" => false,
                    "error" => "La asignación no existe."
                ]);

                exit();
            }


            $turno_viejo_id = intval(
                $actual[0]["turno_id_turnos"]
            );


            // ------------------------------------------------------
            // Obtener información del nuevo turno
            // ------------------------------------------------------

                        $infoNuevo = $con->consultarArray("
                SELECT
                    t.id_turnos,
                    t.disponible,
                    t.agenda_id_agenda,
                    t.con_obra_social,
                    t.obra_social_id_obra_social,
                    a.doctor_id_doctor
                FROM turno t

                INNER JOIN agenda a
                    ON t.agenda_id_agenda = a.id_agenda

                WHERE t.id_turnos = $turno_nuevo_id

                LIMIT 1
            ");


            if (empty($infoNuevo)) {

                echo json_encode([
                    "success" => false,
                    "error" => "El nuevo turno no existe."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Si realmente cambió de turno, verificar disponibilidad
            // ------------------------------------------------------

            if ($turno_viejo_id !== $turno_nuevo_id) {

                if (
                    intval($infoNuevo[0]["disponible"]) !== 1
                ) {

                    echo json_encode([
                        "success" => false,
                        "error" => "El nuevo turno no está disponible."
                    ]);

                    exit();
                }
            }


            $id_agenda_n = intval(
                $infoNuevo[0]["agenda_id_agenda"]
            );

                        $id_doctor_n = intval(
                $infoNuevo[0]["doctor_id_doctor"]
            );

            if (
                $con_obra_social === 1 &&
                !obraSocialEsValida($con, $id_doctor_n, $paciente_id, $obra_social_id)
            ) {

                echo json_encode([
                    "success" => false,
                    "error" => "La obra social seleccionada no es válida para este doctor y paciente."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Verificar turno duplicado
            // ------------------------------------------------------

            $turnoDuplicadoEdit = $con->consultarArray("
                SELECT at.id_agenda_turno

                FROM agenda_turno at

                INNER JOIN turno t2
                    ON at.turno_id_turnos = t2.id_turnos

                INNER JOIN agenda a2
                    ON t2.agenda_id_agenda = a2.id_agenda

                INNER JOIN doctor d2
                    ON a2.doctor_id_doctor = d2.id_doctor

                INNER JOIN estados e
                    ON at.estados_id_estados = e.id_estados

                WHERE at.paciente_id_paciente = $paciente_id
                  AND a2.id_agenda = $id_agenda_n
                  AND d2.id_doctor = $id_doctor_n
                  AND e.tipo_estado IN ('Pendiente', 'Activo')
                  AND at.id_agenda_turno <> $id_agenda_turno

                LIMIT 1
            ");


            if (!empty($turnoDuplicadoEdit)) {

                echo json_encode([
                    "success" => false,
                    "error" => "El paciente ya tiene otro turno activo o pendiente con este doctor en esta agenda."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Primero modificar agenda_turno
            // ------------------------------------------------------

            $obj = new AgendaTurno();

            $obj->setId_agenda_turno(
                $id_agenda_turno
            );

            $obj->setPaciente_id_paciente(
                $paciente_id
            );

            $obj->setTurno_id_turnos(
                $turno_nuevo_id
            );

            $obj->setEstados_id_estados(
                $estado_id
            );


            $resultado = $obj->modificar();


            if ($resultado === false) {

                echo json_encode([
                    "success" => false,
                    "error" => "No se pudo actualizar la asignación."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Si cambió de turno, actualizar disponibilidades
            // ------------------------------------------------------

            if ($turno_viejo_id !== $turno_nuevo_id) {

                $liberar = $con->actualizar("
                    UPDATE turno
                    SET disponible = 1, con_obra_social = 0, obra_social_id_obra_social = NULL
                    WHERE id_turnos = $turno_viejo_id
                ");


                if ($liberar === false) {

                    echo json_encode([
                        "success" => false,
                        "error" => "La asignación fue modificada, pero no se pudo liberar el turno anterior."
                    ]);

                    exit();
                }


                $ocupar = $con->actualizar("
                    UPDATE turno
                    SET disponible = 0
                    WHERE id_turnos = $turno_nuevo_id
                ");


                if ($ocupar === false) {

                    // Intentar restaurar el turno anterior
                    $con->actualizar("
                        UPDATE turno
                        SET disponible = 0
                        WHERE id_turnos = $turno_viejo_id
                    ");

                    echo json_encode([
                        "success" => false,
                        "error" => "No se pudo ocupar el nuevo turno."
                    ]);

                                        exit();
                }
            }

            // Guardar (o limpiar) la obra social en el turno
            // vigente de la asignación, sin importar si el
            // turno específico cambió o no.
            $obraSocialUpdate = ($con_obra_social === 1 && $obra_social_id > 0)
                ? "con_obra_social = 1, obra_social_id_obra_social = $obra_social_id"
                : "con_obra_social = 0, obra_social_id_obra_social = NULL";

            $con->actualizar("
                UPDATE turno
                SET $obraSocialUpdate
                WHERE id_turnos = $turno_nuevo_id
            ");

            $turno = new Turno();
            $anteriorAsignacion = $actual[0];
            $usuarioAct = usuarioActual();

            if (intval($anteriorAsignacion['paciente_id_paciente']) !== $paciente_id) {
                $turno->registrarHistorial(
                    $turno_nuevo_id,
                    $usuarioAct,
                    'reasignado',
                    'paciente_id',
                    $anteriorAsignacion['paciente_id_paciente'],
                    $paciente_id
                );
            }

            if (intval($anteriorAsignacion['estados_id_estados']) !== $estado_id) {
                $turno->registrarHistorial(
                    $turno_nuevo_id,
                    $usuarioAct,
                    'modificado',
                    'estados_id_estados',
                    $anteriorAsignacion['estados_id_estados'],
                    $estado_id
                );
            }

                        if ($turno_viejo_id !== $turno_nuevo_id) {
                $turno->registrarHistorial(
                    $turno_nuevo_id,
                    $usuarioAct,
                    'reasignado',
                    'turno_id_turnos',
                    $turno_viejo_id,
                    $turno_nuevo_id,
                    'Se cambió el turno (fecha/hora) de la asignación.'
                );
            }

            $obraSocialAnterior = intval($infoNuevo[0]['con_obra_social']) === 1 ? 'Sí' : 'No';
            $obraSocialNueva = $con_obra_social === 1 ? 'Sí' : 'No';

            if ($obraSocialAnterior !== $obraSocialNueva) {
                $turno->registrarHistorial(
                    $turno_nuevo_id,
                    $usuarioAct,
                    'modificado',
                    'con_obra_social',
                    $obraSocialAnterior,
                    $obraSocialNueva,
                    $con_obra_social === 1 && $obra_social_id > 0
                        ? "Obra social #$obra_social_id"
                        : null
                );
            }

            echo json_encode([
                "success" => true
            ]);

            exit();


            // ELIMINAR TURNO DISPONIBLE
        case "eliminacion":

            $id_turno = intval(
                $_POST["id_turnos"] ??
                    $_POST["id"] ??
                    0
            );


            if ($id_turno <= 0) {

                echo json_encode([
                    "success" => false,
                    "error" => "ID inválido."
                ]);

                exit();
            }


            $con = new Conexion();


            // Verificar si está asignado
            $check = $con->consultarArray("
                SELECT id_agenda_turno
                FROM agenda_turno
                WHERE turno_id_turnos = $id_turno
                LIMIT 1
            ");


            if (!empty($check)) {

                echo json_encode([
                    "success" => false,
                    "error" => "El turno está asignado a un paciente. Primero debe desasignarlo."
                ]);

                exit();
            }


            $del = $con->eliminar("
                DELETE FROM turno
                WHERE id_turnos = $id_turno
            ");


            if ($del) {

                echo json_encode([
                    "success" => true
                ]);
            } else {

                echo json_encode([
                    "success" => false,
                    "error" => "No se pudo eliminar el turno."
                ]);
            }

            exit();


            // ==========================================================
            // ELIMINAR TURNO ASIGNADO
            //
            // 1. Obtiene turno
            // 2. Libera turno
            // 3. Elimina agenda_turno
            // ==========================================================
        case "eliminar":

            $id_agenda_turno = intval(
                $_POST["id_agenda_turno"] ?? 0
            );


            if ($id_agenda_turno <= 0) {

                echo json_encode([
                    "success" => false,
                    "error" => "ID de agenda_turno inválido."
                ]);

                exit();
            }


            $con = new Conexion();


            // ------------------------------------------------------
            // Obtener turno asociado
            // ------------------------------------------------------

            $fila = $con->consultarArray("
                SELECT turno_id_turnos
                FROM agenda_turno
                WHERE id_agenda_turno = $id_agenda_turno
                LIMIT 1
            ");


            if (empty($fila)) {

                echo json_encode([
                    "success" => false,
                    "error" => "La asignación no existe."
                ]);

                exit();
            }


            $turno_id = intval(
                $fila[0]["turno_id_turnos"]
            );


            // ------------------------------------------------------
            // Eliminar asignación primero
            // ------------------------------------------------------

            $agendaTurnoModel = new AgendaTurno();

            $del = $agendaTurnoModel->eliminar(
                $id_agenda_turno
            );


            if ($del === false) {

                echo json_encode([
                    "success" => false,
                    "error" => "No se pudo eliminar la asignación."
                ]);

                exit();
            }


            // ------------------------------------------------------
            // Liberar turno
            // ------------------------------------------------------

            $upd = $con->actualizar("
                UPDATE turno
                SET disponible = 1, con_obra_social = 0, obra_social_id_obra_social = NULL
                WHERE id_turnos = $turno_id
            ");

            if ($upd === false) {

                echo json_encode([
                    "success" => false,
                    "error" => "La asignación fue eliminada, pero no se pudo liberar el turno."
                ]);

                exit();
            }

            $turno = new Turno();

            $turno->registrarHistorial(
                $turno_id,
                usuarioActual(),
                'liberado',
                'disponible',
                'No',
                'Sí',
                'Se eliminó la asignación del paciente; el turno vuelve a estar disponible.'
            );

            echo json_encode([
                "success" => true
            ]);

            exit();

            // ==========================================================
            // ACCIÓN NO RECONOCIDA
            // ==========================================================
        default:

            echo json_encode([
                "success" => false,
                "error" => "Acción desconocida o no proporcionada."
            ]);

            exit();
    }
} catch (Exception $ex) {
    echo json_encode([
        "success" => false,
        "error" => $ex->getMessage()
    ]);
    exit();
}
