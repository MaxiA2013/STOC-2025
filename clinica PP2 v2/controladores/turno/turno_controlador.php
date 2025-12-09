<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/turno.php";
require_once "../../modelos/agenda.php";
require_once "../../modelos/agenda_turno.php";
require_once "../../modelos/conexion.php";

$action = $_POST["action"] ?? $_GET["action"] ?? null;

try {

    switch ($action) {

        // ==========================================================
        // GENERAR TURNOS (a partir de una agenda)
        // ==========================================================
        case "generar_turnos":

            $id_agenda = intval($_POST["id_agenda"] ?? 0);
            $min       = intval($_POST["minutos"] ?? 0);

            if ($id_agenda <= 0 || $min <= 0) {
                echo json_encode(["success" => false, "error" => "Parámetros inválidos."]);
                exit();
            }

            $agenda = new Agenda();
            $datos  = $agenda->obtenerPorId($id_agenda);

            if (!$datos) {
                echo json_encode(["success" => false, "error" => "Agenda no encontrada."]);
                exit();
            }

            $start = strtotime($datos['fecha_desde'] . ' ' . $datos['hora_desde']);
            $end   = strtotime($datos['fecha_desde'] . ' ' . $datos['hora_hasta']);

            $turno = new Turno();

            while ($start < $end) {
                $turno->setAgenda_id_agenda($id_agenda);
                $turno->setMinutos_turnos($min);
                $turno->setFecha_hora(date("Y-m-d H:i:s", $start));
                $turno->setDisponible(1);
                $turno->guardarTurno();

                $start = strtotime("+{$min} minutes", $start);
            }

            echo json_encode(["success" => true]);
            exit();


        // ==========================================================
        // INSERTAR: puede ser AGREGAR turno o ASIGNAR turno
        // ==========================================================
        case "insertar":

            $modo = $_POST['modo_turno'] ?? 'agregar';

            // -------------------- MODO AGREGAR (solo tabla turno) --------------------
            if ($modo === 'agregar') {

                $minutos    = intval($_POST["minutos_turnos"] ?? 0);
                $fecha_hora = trim($_POST["fecha_hora"] ?? "");
                $disponible = isset($_POST["disponible"]) ? intval($_POST["disponible"]) : 1;
                $agenda_id  = intval($_POST["agenda_id_agenda"] ?? 0);

                if ($minutos <= 0 || $fecha_hora === "" || $agenda_id <= 0) {
                    echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios."]);
                    exit();
                }

                $turno = new Turno();
                $turno->setAgenda_id_agenda($agenda_id);
                $turno->setMinutos_turnos($minutos);
                $turno->setFecha_hora($fecha_hora);
                $turno->setDisponible($disponible ? 1 : 0);

                $id = $turno->guardarTurno();

                if ($id) {
                    echo json_encode(["success" => true, "id_turno" => $id]);
                } else {
                    echo json_encode(["success" => false, "error" => "No se pudo insertar el turno."]);
                }
                exit();
            }

            // -------------------- MODO ASIGNAR (tabla agenda_turno) --------------------
            $paciente_id = intval($_POST["paciente_id"] ?? 0);
            $turno_id    = intval($_POST["turno_id"] ?? $_POST["turno_existente_id"] ?? 0);
            $estado_id   = intval($_POST["estados_id_estados"] ?? 2); // por defecto 2

            if ($paciente_id <= 0 || $turno_id <= 0) {
                echo json_encode(["success" => false, "error" => "Faltan parámetros (paciente o turno)."]);
                exit();
            }

            // 1) verificar que turno existe y está disponible
            $t_model = new Turno();
            $res     = $t_model->existeTurnoDisponible($turno_id);
            $t       = $res->fetch_assoc();

            if (!$t) {
                echo json_encode(["success" => false, "error" => "Turno no existe."]);
                exit();
            }

            if (intval($t['disponible']) !== 1) {
                echo json_encode(["success" => false, "error" => "Turno no disponible."]);
                exit();
            }

            // 2) VALIDACIÓN: un paciente solo puede tener 1 turno activo/pendiente
            //    con un doctor en una agenda
            $con = new Conexion();

            // 2.1 Obtener agenda y doctor del turno seleccionado
            $infoTurno = $con->consultarArray("
                SELECT a.id_agenda, d.id_doctor
                FROM turno t
                INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
                INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
                WHERE t.id_turnos = $turno_id
                LIMIT 1
            ");

            if (empty($infoTurno)) {
                echo json_encode(["success" => false, "error" => "No se pudo obtener la agenda/doctora del turno."]);
                exit();
            }

            $id_agenda = intval($infoTurno[0]['id_agenda']);
            $id_doctor = intval($infoTurno[0]['id_doctor']);

            // 2.2 Buscar si el paciente ya tiene un turno ACTIVO o PENDIENTE
            //     con ese mismo doctor y en esa misma agenda.
            //     Ajusta los textos 'Pendiente' y 'Activo' según tu tabla estados.tipo_estado.
            $turnoDuplicado = $con->consultarArray("
                SELECT at.id_agenda_turno
                FROM agenda_turno at
                INNER JOIN turno t2   ON at.turno_id_turnos = t2.id_turnos
                INNER JOIN agenda a2  ON t2.agenda_id_agenda = a2.id_agenda
                INNER JOIN doctor d2  ON a2.doctor_id_doctor = d2.id_doctor
                INNER JOIN estados e  ON at.estados_id_estados = e.id_estados
                WHERE at.paciente_id_paciente = $paciente_id
                  AND a2.id_agenda = $id_agenda
                  AND d2.id_doctor = $id_doctor
                  AND e.tipo_estado IN ('Pendiente','Activo')
                LIMIT 1
            ");

            if (!empty($turnoDuplicado)) {
                echo json_encode([
                    "success" => false,
                    "error"   => "El paciente ya tiene un turno activo/pendiente con este doctor en esta agenda."
                ]);
                exit();
            }

            // 3) insertar agenda_turno
            $i = new AgendaTurno();
            $i->setPaciente_id_paciente($paciente_id);
            $i->setTurno_id_turnos($turno_id);
            $i->setEstados_id_estados($estado_id);
            $id = $i->insertar();

            if (!$id) {
                echo json_encode(["success" => false, "error" => "No se pudo asignar el turno."]);
                exit();
            }

            // 4) marcar turno como no disponible
            $p   = new Turno();
            $p->actualizarDisponible($turno_id);

            echo json_encode(["success" => true, "id_agenda_turno" => $id]);
            exit();


        // ==========================================================
        // ACTUALIZAR turno DISPONIBLE (tabla turno)
        // ==========================================================
        case "actualizacion":

            $id_turno   = intval($_POST["id_turnos"] ?? 0);
            $minutos    = intval($_POST["minutos_turnos"] ?? 0);
            $fecha_hora = trim($_POST["fecha_hora"] ?? "");
            $disponible = isset($_POST["disponible"]) ? intval($_POST["disponible"]) : 1;
            $agenda_id  = intval($_POST["agenda_id_agenda"] ?? 0);

            if ($id_turno <= 0 || $minutos <= 0 || $fecha_hora === "" || $agenda_id <= 0) {
                echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios."]);
                exit();
            }

            $re = new Turno();
            $re->setId_turnos($id_turno);
            $re->setMinutos_turnos($minutos);
            $re->setFecha_hora($fecha_hora);
            $re->setDisponible($disponible);
            $re->setAgenda_id_agenda($agenda_id);
            $res = $re->actualizar();

            if ($res !== false) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo actualizar el turno."]);
            }
            exit();


        // ==========================================================
        // ACTUALIZAR turno ASIGNADO (tabla agenda_turno)
        // ==========================================================
        case "editar_asignado":

            $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);
            $paciente_id     = intval($_POST["paciente_id_paciente"] ?? 0);

            $turno_nuevo_id  = intval(
                $_POST["turno_id"] ??
                $_POST["turno_id_turnos"] ??
                $_POST["turno_existente_id"] ??
                0
            );

            $estado_id  = intval($_POST["estados_id_estados"] ?? 1);

            if ($id_agenda_turno <= 0 || $paciente_id <= 0) {
                echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios (id_agenda_turno o paciente)."]);
                exit();
            }

            $con = new Conexion();

            if ($turno_nuevo_id <= 0) {
                $actualTmp = $con->consultarArray("SELECT turno_id_turnos FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno");
                if (empty($actualTmp)) {
                    echo json_encode(["success" => false, "error" => "Asignación no encontrada (para obtener turno actual)."]);
                    exit();
                }
                $turno_nuevo_id = intval($actualTmp[0]['turno_id_turnos']);
            }

            if ($turno_nuevo_id <= 0) {
                echo json_encode(["success" => false, "error" => "ID de turno inválido."]);
                exit();
            }

            // turno actualmente asignado
            $actual = $con->consultarArray("
                SELECT turno_id_turnos 
                FROM agenda_turno 
                WHERE id_agenda_turno = $id_agenda_turno
            ");

            if (empty($actual)) {
                echo json_encode(["success" => false, "error" => "Asignación no encontrada."]);
                exit();
            }

            $turno_viejo_id = intval($actual[0]["turno_id_turnos"]);

            // ======================================================
            // VALIDACIÓN: 1 turno activo/pendiente por paciente+doctor+agenda
            // (también en la edición)
            // ======================================================
            // Obtener agenda y doctor del NUEVO turno
            $infoNuevo = $con->consultarArray("
                SELECT a.id_agenda, d.id_doctor
                FROM turno t
                INNER JOIN agenda a ON t.agenda_id_agenda = a.id_agenda
                INNER JOIN doctor d ON a.doctor_id_doctor = d.id_doctor
                WHERE t.id_turnos = $turno_nuevo_id
                LIMIT 1
            ");

            if (empty($infoNuevo)) {
                echo json_encode(["success" => false, "error" => "No se pudo obtener la agenda/doctor del nuevo turno."]);
                exit();
            }

            $id_agenda_n = intval($infoNuevo[0]['id_agenda']);
            $id_doctor_n = intval($infoNuevo[0]['id_doctor']);

            // Buscar si existe OTRO turno activo/pendiente con ese doctor y agenda para el paciente
            $turnoDuplicadoEdit = $con->consultarArray("
                SELECT at.id_agenda_turno
                FROM agenda_turno at
                INNER JOIN turno t2   ON at.turno_id_turnos = t2.id_turnos
                INNER INNER JOIN agenda a2  ON t2.agenda_id_agenda = a2.id_agenda
                INNER JOIN doctor d2  ON a2.doctor_id_doctor = d2.id_doctor
                INNER JOIN estados e  ON at.estados_id_estados = e.id_estados
                WHERE at.paciente_id_paciente = $paciente_id
                  AND a2.id_agenda = $id_agenda_n
                  AND d2.id_doctor = $id_doctor_n
                  AND e.tipo_estado IN ('Pendiente','Activo')
                  AND at.id_agenda_turno <> $id_agenda_turno
                LIMIT 1
            ");

            if (!empty($turnoDuplicadoEdit)) {
                echo json_encode([
                    "success" => false,
                    "error"   => "El paciente ya tiene otro turno activo/pendiente con este doctor en esta agenda."
                ]);
                exit();
            }

            // ======================================================
            // FIN VALIDACIÓN
            // ======================================================

            // Si se cambió el turno, actualizar disponibilidades
            if ($turno_viejo_id !== $turno_nuevo_id) {

                $check = $con->consultarArray("SELECT disponible FROM turno WHERE id_turnos = $turno_nuevo_id");
                if (empty($check)) {
                    echo json_encode(["success" => false, "error" => "El nuevo turno no existe."]);
                    exit();
                }
                if (intval($check[0]['disponible']) !== 1) {
                    echo json_encode(["success" => false, "error" => "El nuevo turno no está disponible."]);
                    exit();
                }

                $con->actualizar("UPDATE turno SET disponible = 1 WHERE id_turnos = $turno_viejo_id");
                $con->actualizar("UPDATE turno SET disponible = 0 WHERE id_turnos = $turno_nuevo_id");
            }

            $obj = new AgendaTurno();
            $obj->setId_agenda_turno($id_agenda_turno);
            $obj->setPaciente_id_paciente($paciente_id);
            $obj->setTurno_id_turnos($turno_nuevo_id);
            $obj->setEstados_id_estados($estado_id);

            $resultado = $obj->modificar();

            if ($resultado !== false) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al actualizar el turno (BD)."]);
            }
            exit();


        // ==========================================================
        // ELIMINAR turno DISPONIBLE (tabla turno)
        // ==========================================================
        case "eliminacion":

            $id_turno = intval($_POST["id_turnos"] ?? $_POST["id"] ?? 0);
            if ($id_turno <= 0) {
                echo json_encode(["success" => false, "error" => "ID inválido."]);
                exit();
            }

            $con = new Conexion();

            $check = $con->consultarArray("SELECT * FROM agenda_turno WHERE turno_id_turnos = $id_turno");
            if (!empty($check)) {
                echo json_encode(["success" => false, "error" => "El turno está asignado a un paciente, primero desasignelo."]);
                exit();
            }

            $del = $con->eliminar("DELETE FROM turno WHERE id_turnos = $id_turno");
            if ($del) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo eliminar."]);
            }
            exit();


        // ==========================================================
        // ELIMINAR turno ASIGNADO (tabla agenda_turno)
        //  - Libera el turno en tabla turno (disponible = 1)
        //  - Elimina la fila en agenda_turno
        // ==========================================================
        case "eliminar":

            $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);

            if ($id_agenda_turno <= 0) {
                echo json_encode(["success" => false, "error" => "ID de agenda_turno inválido."]);
                exit();
            }

            $con = new Conexion();

            // 1) Obtener el turno asociado
            $fila = $con->consultarArray("
                SELECT turno_id_turnos 
                FROM agenda_turno 
                WHERE id_agenda_turno = $id_agenda_turno
            ");

            if (empty($fila)) {
                echo json_encode(["success" => false, "error" => "Asignación no encontrada."]);
                exit();
            }

            $turno_id = intval($fila[0]["turno_id_turnos"]);

            // 2) Liberar el turno (marcar como disponible = 1)
            $upd = $con->actualizar("UPDATE turno SET disponible = 1 WHERE id_turnos = $turno_id");
            if ($upd === false) {
                echo json_encode(["success" => false, "error" => "No se pudo liberar el turno."]);
                exit();
            }

            // 3) Eliminar la asignación en agenda_turno usando el modelo
            $agendaTurnoModel = new AgendaTurno();
            $del = $agendaTurnoModel->eliminar($id_agenda_turno);

            if ($del) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo eliminar la asignación de agenda_turno."]);
            }
            exit();


        // ==========================================================
        // Acción no reconocida
        // ==========================================================
        default:
            echo json_encode(["success" => false, "error" => "Acción desconocida o no proporcionada."]);
            exit();
    }

} catch (Exception $ex) {
    echo json_encode(["success" => false, "error" => $ex->getMessage()]);
    exit();
}
