<?php
require_once "../modelos/agenda.php";
require_once "../modelos/turno.php";

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

        $id = $_POST["id"];

        $agenda = new Agenda();
        $agenda->setDoctor_id_doctor($_POST['doctor_id']);
        $agenda->setFecha_desde($_POST['fecha_desde']);
        $agenda->setFecha_hasta($_POST['fecha_hasta']);
        $agenda->setHora_desde($_POST['hora_desde']);
        $agenda->setHora_hasta($_POST['hora_hasta']);
        $agenda->setEstados_id_estados($_POST['estados_id_estados']);

        if ($agenda->existeSuperposicion($_POST['doctor_id'], $_POST['fecha_desde'], $_POST['fecha_hasta'], $_POST['hora_desde'], $_POST['hora_hasta'], $id)) {
            echo json_encode(["success" => false, "error" => "superposicion"]);
            exit;
        }

        $agenda->modificar($id);

        echo json_encode(["success" => true]);
    break;

    // ====================================================
    // ELIMINAR AGENDA + TURNOS
    // ====================================================
    case "eliminar":

        $id = $_POST["id"];

        $turno = new Turno();
        $turno->eliminarPorAgenda($id);

        $agenda = new Agenda();
        $agenda->eliminar($id);

        echo json_encode(["success" => true]);
    break;

}
?>