<?php
require_once "../modelos/turno.php";
require_once "../modelos/agenda.php";

$action = $_POST["action"] ?? null;

if ($action === "generar_turnos") {

    $id_agenda = intval($_POST["id_agenda"]);
    $min = intval($_POST["minutos"]);

    $agenda = new Agenda();
    $datos = $agenda->obtenerPorId($id_agenda);

    $inicio = strtotime($datos['hora_desde']);
    $fin = strtotime($datos['hora_hasta']);

    while ($inicio < $fin) {

        $turno = new Turno();
        $turno->setAgenda_id_agenda($id_agenda);
        $turno->setMinutos_turnos($min);

        $turno->setFecha_hora($datos['fecha_desde'] . " " . date("H:i", $inicio));
        $turno->setDisponible(1);

        $turno->guardarTurno();

        $inicio = strtotime("+$min minutes", $inicio);
    }

    echo "ok";
    exit;
}
