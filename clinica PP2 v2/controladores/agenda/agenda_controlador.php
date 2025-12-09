<?php
require_once "../../modelos/agenda.php";
require_once "../../modelos/turno.php";

$accion = $_POST["accion"] ?? "";

switch ($accion) {

    // ====================================================
    // LISTAR AGENDAS
    // ====================================================
    case "listar":
        $doctor = $_POST["doctor_id"] ?? "";
        $agenda = new Agenda();
        $datos = $agenda->listarAgendasFiltradas($doctor);
        echo json_encode($datos);
        break;

    // ====================================================
    // GUARDAR NUEVA AGENDA
    // ====================================================
    case "guardar":

        $agenda = new Agenda();
        $agenda->setDoctor_id_doctor($_POST['doctor_id']);
        $agenda->setFecha_desde($_POST['fecha_desde']);
        $agenda->setFecha_hasta($_POST['fecha_hasta']);
        $agenda->setHora_desde($_POST['hora_desde']);
        $agenda->setHora_hasta($_POST['hora_hasta']);
        $agenda->setEstados_id_estados($_POST['estados_id_estados']);

        // Validación de superposición
        if ($agenda->existeSuperposicion($_POST['doctor_id'], $_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['hora_desde'], $_POST['hora_hasta'])) {
            echo json_encode(["success" => false, "error" => "superposicion"]);
            exit;
        }

        $idAgenda = $agenda->guardar();

        if (!$idAgenda) {
            echo json_encode(["success" => false]);
            exit;
        }

        // Generar turnos
        $turno = new Turno();
        $turno->generarTurnosParaAgenda(
            $idAgenda,
            $_POST["fecha_desde"],
            $_POST["hora_desde"],
            $_POST["hora_hasta"],
            intval($_POST["minutos_turnos"])
        );

        echo json_encode(["success" => true]);
        break;

    // ====================================================
    // EDITAR AGENDA
    // ====================================================
    case "editar":

        $id = intval($_POST["id"]);

        $agenda = new Agenda();
        $agenda->setDoctor_id_doctor($_POST['doctor_id']);
        $agenda->setFecha_desde($_POST['fecha_desde']);
        $agenda->setFecha_hasta($_POST['fecha_hasta']);
        $agenda->setHora_desde($_POST['hora_desde']);
        $agenda->setHora_hasta($_POST['hora_hasta']);
        $agenda->setEstados_id_estados($_POST['estados_id_estados']);

        // Validación de superposición
        if ($agenda->existeSuperposicion(
            $_POST['doctor_id'],
            $_POST['fecha_desde'],
            $_POST['fecha_hasta'],
            $_POST['hora_desde'],
            $_POST['hora_hasta'],
            $id
        )) {
            echo json_encode(["success" => false, "error" => "superposicion"]);
            exit;
        }

        // 1) Guardar cambios normales de la agenda
        $agenda->modificar($id);

        // 2) LÓGICA DE "CERRAR" AGENDA
        //    Ajustá este valor al ID real del estado "Cerrado" en tu tabla `estados`
        $ID_ESTADO_CERRADO = 3;  // EJEMPLO

        $estadoNuevo = intval($_POST['estados_id_estados']);

        if ($estadoNuevo === $ID_ESTADO_CERRADO) {

            // 2.1) Eliminar turnos SIN paciente asignado de esa agenda
            $turno = new Turno();
            $turno->eliminarNoAsignadosPorAgenda($id);

            // 2.2) Forzar que la agenda quede con estado CERRADO 
            //      (por si llegó manipulado desde el front)
            $agenda->cambiarEstado($id, $ID_ESTADO_CERRADO);
        }

        echo json_encode(["success" => true]);
        break;


    // ====================================================
    // ELIMINAR AGENDA + TURNOS
    // ====================================================
    case "eliminar":


        $id = $_POST["id"];

        $turno = new Turno();
        $turno->eliminarPorAgendaAsignados($id);  // PRIMERO

        $agenda = new Agenda();
        $agenda->eliminar($id);          // DESPUÉS

        echo json_encode(["success" => true]);
        break;
}
