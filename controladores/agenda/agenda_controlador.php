<?php

require_once "../../modelos/agenda.php";
require_once "../../modelos/turno.php";
require_once "../../modelos/notificacion.php";
require_once "../../modelos/agenda_turno.php";

$accion = $_POST["accion"] ?? "";

switch ($accion) {

    // =====================================================
    // LISTAR AGENDAS
    // =====================================================

    case "listar":

        $doctor = $_POST["doctor_id"] ?? "";

        $agenda = new Agenda();

        $datos = $agenda->listarAgendasFiltradas($doctor);

        echo json_encode($datos);

        break;


    // =====================================================
    // GUARDAR NUEVA AGENDA
    // =====================================================

    case "guardar":

        $agenda = new Agenda();

        $doctorId = intval($_POST["doctor_id"]);
        $fechaDesde = $_POST["fecha_desde"];
        $fechaHasta = $_POST["fecha_hasta"];
        $horaDesde = $_POST["hora_desde"];
        $horaHasta = $_POST["hora_hasta"];
        $estadoId = intval($_POST["estados_id_estados"]);
        $minutos = intval($_POST["minutos_turnos"]);


        // -------------------------------------------------
        // Validar superposición
        // -------------------------------------------------

        if (
            $agenda->existeSuperposicion(
                $doctorId,
                $fechaDesde,
                $fechaHasta,
                $horaDesde,
                $horaHasta
            )
        ) {

            echo json_encode([
                "success" => false,
                "error" => "superposicion"
            ]);

            exit;
        }


        // -------------------------------------------------
        // Cargar datos
        // -------------------------------------------------

        $agenda->setDoctor_id_doctor($doctorId);
        $agenda->setFecha_desde($fechaDesde);
        $agenda->setFecha_hasta($fechaHasta);
        $agenda->setHora_desde($horaDesde);
        $agenda->setHora_hasta($horaHasta);
        $agenda->setEstados_id_estados($estadoId);


        // -------------------------------------------------
        // Guardar agenda
        // -------------------------------------------------

        $idAgenda = $agenda->guardar();

        if (!$idAgenda) {

            echo json_encode([
                "success" => false,
                "error" => "No se pudo guardar la agenda."
            ]);

            exit;
        }


        // -------------------------------------------------
        // Generar turnos
        // -------------------------------------------------

        $turno = new Turno();

        $generados = $turno->generarTurnosParaAgenda(
            $idAgenda,
            $fechaDesde,
            $fechaHasta,
            $horaDesde,
            $horaHasta,
            $minutos
        );


        echo json_encode([
            "success" => true
        ]);

        break;


    // =====================================================
    // EDITAR AGENDA
    // =====================================================

    case "editar":

        $id = intval($_POST["id"]);

        $agenda = new Agenda();


        // -------------------------------------------------
        // 1. Obtener agenda anterior
        // -------------------------------------------------

        $agendaAnterior = $agenda->obtenerPorId($id);

        if (!$agendaAnterior) {

            echo json_encode([
                "success" => false,
                "error" => "La agenda no existe."
            ]);

            exit;
        }


        // -------------------------------------------------
        // 2. Obtener nuevos datos
        // -------------------------------------------------

        $doctorId = intval($_POST["doctor_id"]);

        $fechaDesde = $_POST["fecha_desde"];
        $fechaHasta = $_POST["fecha_hasta"];

        $horaDesde = $_POST["hora_desde"];
        $horaHasta = $_POST["hora_hasta"];

        $estadoId = intval($_POST["estados_id_estados"]);

        $minutos = intval($_POST["minutos_turnos"]);


        // -------------------------------------------------
        // 3. Validar superposición
        // -------------------------------------------------

        if (
            $agenda->existeSuperposicion(
                $doctorId,
                $fechaDesde,
                $fechaHasta,
                $horaDesde,
                $horaHasta,
                $id
            )
        ) {

            echo json_encode([
                "success" => false,
                "error" => "superposicion"
            ]);

            exit;
        }


        // -------------------------------------------------
        // 4. Si la agenda queda CERRADA
        // -------------------------------------------------

        $ID_ESTADO_CERRADO = 3;

        if ($estadoId === $ID_ESTADO_CERRADO) {

            $agendaTurno = new AgendaTurno();

            $turno = new Turno();

            /*
             * Primero eliminamos las asignaciones y
             * después los turnos.
             */

            $turno->eliminarPorAgendaAsignados($id);
        }


        // -------------------------------------------------
        // 5. Si la agenda queda abierta, sincronizar
        //    los turnos
        // -------------------------------------------------

        else {

            $turno = new Turno();

            $sincronizacion = $turno->sincronizarTurnosAgenda(
                $id,
                $fechaDesde,
                $fechaHasta,
                $horaDesde,
                $horaHasta,
                $minutos
            );


            if (!$sincronizacion["success"]) {

                echo json_encode([
                    "success" => false,
                    "error" => $sincronizacion["error"],
                    "turnos_asignados" =>
                        $sincronizacion["turnos_asignados"] ?? []
                ]);

                exit;
            }
        }


        // -------------------------------------------------
        // 6. Cargar nuevos datos en Agenda
        // -------------------------------------------------

        $agenda->setDoctor_id_doctor($doctorId);
        $agenda->setFecha_desde($fechaDesde);
        $agenda->setFecha_hasta($fechaHasta);
        $agenda->setHora_desde($horaDesde);
        $agenda->setHora_hasta($horaHasta);
        $agenda->setEstados_id_estados($estadoId);


        // -------------------------------------------------
        // 7. Actualizar agenda
        // -------------------------------------------------

        $resultado = $agenda->modificar($id);

        if (!$resultado) {

            echo json_encode([
                "success" => false,
                "error" => "No se pudo modificar la agenda."
            ]);

            exit;
        }


        echo json_encode([
            "success" => true
        ]);

        break;


    // =====================================================
    // ELIMINAR AGENDA
    // =====================================================

    case "eliminar":

        $id = intval($_POST["id"]);

        $agenda = new Agenda();
        $turno = new Turno();


        // -------------------------------------------------
        // 1. Verificar que exista
        // -------------------------------------------------

        $agendaExistente = $agenda->obtenerPorId($id);

        if (!$agendaExistente) {

            echo json_encode([
                "success" => false,
                "error" => "La agenda no existe."
            ]);

            exit;
        }


        // -------------------------------------------------
        // 2. Eliminar asignaciones + turnos
        // -------------------------------------------------

        $turnosEliminados = $turno->eliminarPorAgendaAsignados($id);

        if (!$turnosEliminados) {

            echo json_encode([
                "success" => false,
                "error" => "No se pudieron eliminar los turnos de la agenda."
            ]);

            exit;
        }


        // -------------------------------------------------
        // 3. Finalmente eliminar la agenda
        // -------------------------------------------------

        $agendaEliminada = $agenda->eliminar($id);

        if (!$agendaEliminada) {

            echo json_encode([
                "success" => false,
                "error" => "Los turnos fueron eliminados, pero no se pudo eliminar la agenda."
            ]);

            exit;
        }


        echo json_encode([
            "success" => true
        ]);

        break;


    // =====================================================
    // ACCIÓN DESCONOCIDA
    // =====================================================

    default:

        echo json_encode([
            "success" => false,
            "error" => "Acción no válida."
        ]);

        break;
}