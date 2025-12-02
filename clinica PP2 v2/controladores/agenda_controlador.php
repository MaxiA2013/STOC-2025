<?php
require_once "../modelos/agenda.php";
require_once "../modelos/turno.php";

$accion = $_POST["accion"] ?? "";

switch ($accion) {

    case "guardar":

        $agenda = new Agenda();
        $agenda->setFecha_desde($_POST['fecha_desde']);
        $agenda->setFecha_hasta($_POST['fecha_hasta']);
        $agenda->setHora_desde($_POST['hora_desde']);
        $agenda->setHora_hasta($_POST['hora_hasta']);
        $agenda->setDoctor_id_doctor($_POST['doctor_id']);
        $agenda->setEstados_id_estados($_POST['estados_id_estados']);

        $minutos = $_POST["minutos_turnos"];

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
            $minutos
        );

        echo json_encode(["success" => true]);
        break;


    case "eliminar":

        $id = $_POST["id"];

        $turno = new Turno();
        $turno->eliminarPorAgenda($id);

        $agenda = new Agenda();
        $agenda->eliminar($id);

        echo json_encode(["success" => true]);
        break;


    case "editar":

        $id = $_POST["id"];

        $agenda = new Agenda();
        $agenda->setFecha_desde($_POST['fecha_desde']);
        $agenda->setFecha_hasta($_POST['fecha_hasta']);
        $agenda->setHora_desde($_POST['hora_desde']);
        $agenda->setHora_hasta($_POST['hora_hasta']);
        $agenda->setDoctor_id_doctor($_POST['doctor_id']);
        $agenda->setEstados_id_estados($_POST['estados_id_estados']);

        $agenda->modificar($id);

        echo json_encode(["success" => true]);
        break;
}
