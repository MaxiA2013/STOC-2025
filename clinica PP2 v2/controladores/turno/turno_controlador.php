<?php
// controladores/turno_controlador.php
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/turno.php";
require_once "../../modelos/agenda.php";
require_once "../../modelos/agenda_turno.php";
require_once "../../modelos/conexion.php";

$action = $_POST["action"] ?? $_GET["action"] ?? null;
/*
echo print_r($_POST) ; */
try {

    // GENERAR TURNOS : Usa la agenda 
    // GENERAR TURNOS : Usa la agenda 
    // GENERAR TURNOS : Usa la agenda 
    // GENERAR TURNOS : Usa la agenda 
    switch ($action) {

        case ($action === "generar_turnos"):

            $id_agenda = intval($_POST["id_agenda"] ?? 0);
            $min = intval($_POST["minutos"] ?? 0);

            if ($id_agenda <= 0 || $min <= 0) {
                echo json_encode(["success" => false, "error" => "Parámetros inválidos."]);
                exit;
            }

            $agenda = new Agenda();
            $datos = $agenda->obtenerPorId($id_agenda);

            if (!$datos) {
                echo json_encode(["success" => false, "error" => "Agenda no encontrada."]);
                exit;
            }

            // asumimos $datos['fecha_desde'], ['hora_desde'], ['hora_hasta']
            $start = strtotime($datos['fecha_desde'] . ' ' . $datos['hora_desde']);
            $end   = strtotime($datos['fecha_desde'] . ' ' . $datos['hora_hasta']); // genera para la fecha_desde

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
            exit;


            // INSERTAR UN TURNO
            // INSERTAR UN TURNO
            // INSERTAR UN TURNO
            // INSERTAR UN TURNO
        case ($action === "insertar"):
            //TABLA TURNO
            //TABLA TURNO
            //TABLA TURNO
            //se pregunta por el valor del check, si el modo es agregar, corresponde al registro de un turno fuera del 
            //horario del profesional
            $modo = $_POST['modo_turno'];

            if ($modo == 'agregar') {
                $minutos = intval($_POST["minutos_turnos"] ?? 0);
                $fecha_hora = trim($_POST["fecha_hora"] ?? "");
                $disponible = isset($_POST["disponible"]) ? intval($_POST["disponible"]) : 1;
                $agenda_id = intval($_POST["agenda_id_agenda"] ?? 0);

                if ($minutos <= 0 || $fecha_hora === "" || $agenda_id <= 0) {
                    echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios."]);
                    exit;
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
                exit;
            } else {
                //TABLA AGENDA_TURNO
                //TABLA AGENDA_TURNO
                //TABLA AGENDA_TURNO
                //si el valor del check es de asignar, entonces se asigna :3
                $paciente_id = intval($_POST["paciente_id"] ?? 0);
                $turno_id = intval($_POST["turno_id"] ?? $_POST["turno_existente_id"] ?? 0);
                $estado_id = intval($_POST["estados_id_estados"] ?? 2); // por defecto 2 si aplica

                if ($paciente_id <= 0 || $turno_id <= 0) {
                    echo json_encode(["success" => false, "error" => "Faltan parámetros (paciente o turno)."]);
                    exit;
                }

                // 1) verificar que turno existe y está disponible
                $t_model = new Turno();
                $res = $t_model->existeTurnoDisponible($turno_id);

                // Convertir resultado mysqli a array
                $t = $res->fetch_assoc();

                if (!$t) {
                    echo json_encode(["success" => false, "error" => "Turno no existe."]);
                    exit;
                }

                if (intval($t['disponible']) !== 1) {
                    echo json_encode(["success" => false, "error" => "Turno no disponible."]);
                    exit;
                }


                // 2) insertar agenda_turno
                $i = new AgendaTurno();
                $i->setPaciente_id_paciente($paciente_id);
                $i->setTurno_id_turnos($turno_id);
                $i->setEstados_id_estados($estado_id);
                $id = $i->insertar();

                if (!$id) {
                    echo json_encode(["success" => false, "error" => "No se pudo asignar el turno."]);
                    exit;
                }

                // 3) marcar turno como no disponible
                $p = new Turno();
                $pot = $p->actualizarDisponible($turno_id);

                echo json_encode(["success" => true, "id_agenda_turno" => $id]);
                exit;
            }
            
            // ACTUALIZAR UN TURNO
            // ACTUALIZAR UN TURNO
            // ACTUALIZAR UN TURNO
            // ACTUALIZAR UN TURNO

        case ($action === "actualizacion"):
            //actualizar de tabla turnos disponibles

            $id_turno = intval($_POST["id_turnos"] ?? 0);
            $minutos = intval($_POST["minutos_turnos"] ?? 0);
            $fecha_hora = trim($_POST["fecha_hora"] ?? "");
            $disponible = isset($_POST["disponible"]) ? intval($_POST["disponible"]) : 1;
            $agenda_id = intval($_POST["agenda_id_agenda"] ?? 0);

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
            
            header("Location: ../../index.php?page=turno_lista");
        exit();
        case ( $action === "editar"):
            //actualizar de tabla turnos asignados

            $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);
            $paciente_id = intval($_POST["paciente_id"] ?? 0);
            $turno_id_new = intval($_POST["turno_id"] ?? $_POST["turno_existente_id"] ?? 0);
            $estado_id = intval($_POST["estados_id_estados"] ?? 1);

            if ($id_agenda_turno <= 0 || $paciente_id <= 0 || $turno_id_new <= 0) {
                echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios."]);
                exit();
            }

            $con = new Conexion();

            // obtener la asignación actual
            $actual = $con->consultarArray("SELECT turno_id_turnos FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno");
            if (empty($actual)) {
                echo json_encode(["success" => false, "error" => "Asignación no encontrada."]);
                exit();
            }
            $turno_old = intval($actual[0]['turno_id_turnos']);

            // si cambió el turno: verificar disponibilidad del nuevo y actualizar disponibilidades
            if ($turno_old !== $turno_id_new) {
                $check = $con->consultarArray("SELECT disponible FROM turno WHERE id_turnos = $turno_id_new");
                if (empty($check)) {
                    echo json_encode(["success" => false, "error" => "Nuevo turno no existe."]);
                    exit();
                }
                if (intval($check[0]['disponible']) !== 1) {
                    echo json_encode(["success" => false, "error" => "Nuevo turno no disponible."]);
                    exit();
                }

                // marcar nuevo turno como ocupado (disponible=0) y liberar el viejo (disponible=1)
                $con->actualizar("UPDATE turno SET disponible = 1 WHERE id_turnos = $turno_old");
                $con->actualizar("UPDATE turno SET disponible = 0 WHERE id_turnos = $turno_id_new");
            }

            // actualizar registro agenda_turno
            $sqlUpd = "UPDATE agenda_turno SET
                        paciente_id_paciente = $paciente_id,
                        turno_id_turnos = $turno_id_new,
                        estados_id_estados = $estado_id
                    WHERE id_agenda_turno = $id_agenda_turno";

            $res = $con->actualizar($sqlUpd);
            if ($res !== false) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo actualizar la asignación."]);
            }
            header("Location: ../../index.php?page=turno_lista");

            exit();

            // ELIMINAR UN TURNO
            // ELIMINAR UN TURNO
            // ELIMINAR UN TURNO
            // ELIMINAR UN TURNO
        case ($action === "eliminacion"):
            //eliminar de tabla turnos disponibles

            $id_turno = intval($_POST["id_turnos"] ?? $_POST["id"] ?? 0);
            if ($id_turno <= 0) {
                echo json_encode(["success" => false, "error" => "ID inválido."]);
                exit;
            }

            $con = new Conexion();

            // Antes de eliminar, verificar si el turno está asignado en agenda_turno
            $check = $con->consultarArray("SELECT * FROM agenda_turno WHERE turno_id_turnos = $id_turno");
            if (!empty($check)) {
                // No permitimos eliminar si está asignado (alternativa: eliminar cascada)
                echo json_encode(["success" => false, "error" => "El turno está asignado a un paciente, primero desasignelo."]);
                exit;
            }

            $del = $con->eliminar("DELETE FROM turno WHERE id_turnos = $id_turno");
            if ($del) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo eliminar."]);
            }
            header("Location: ../../index.php?page=turno_lista");

        exit;
        case ($action === "eliminar"):
            //eliminar de tabla turnos asignados

        break;
    }
    // Acción no reconocida
    echo json_encode(["success" => false, "error" => "Acción desconocida o no proporcionada."]);
} catch (Exception $ex) {
    echo json_encode(["success" => false, "error" => $ex->getMessage()]);
}
