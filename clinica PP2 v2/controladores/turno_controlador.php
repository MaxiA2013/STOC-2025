<?php
require_once "../modelos/turno.php";
require_once "../modelos/agenda.php";

if (isset($_POST['action'])) {
    $accion = $_POST['action'];
    switch ($accion) {
        case 'insertar':
            $turno = new Turno();
            $turno->setMinutos_turnos(intval($_POST['minutos_turnos']));
            $turno->setFecha_hora($_POST['fecha_hora']);
            $turno->setDisponible(intval($_POST['disponible'] ?? 0));
            $turno->setAgenda_id_agenda(intval($_POST['agenda_id_agenda']));
            $turno->guardarTurno();
            header('Location: ../index.php?page=turno_lista');
            exit;
        case 'eliminacion':
            $turno = new Turno();
            $turno->setId_turnos(intval($_POST['id_turnos']));
            $turno->eliminarTurno();
            header('Location: ../index.php?page=turno_lista');
            exit;
        case 'actualizacion':
            $turno = new Turno();
            $turno->setId_turnos(intval($_POST['id_turnos']));
            $turno->setMinutos_turnos(intval($_POST['minutos_turnos']));
            $turno->setFecha_hora($_POST['fecha_hora']);
            $turno->setDisponible(intval($_POST['disponible'] ?? 0));
            $turno->setAgenda_id_agenda(intval($_POST['agenda_id_agenda']));
            $turno->actualizarTurno();
            header('Location: ../index.php?page=turno_lista');
            exit;
        case 'generar_turnos';
            $id_agenda = intval($_POST['id_agenda']);
            $minutos = intval($_POST['minutos']);

            $agendaModel = new Agenda();
            $agenda = $agendaModel->obtenerPorId($id_agenda);

            if (!$agenda) {
                echo json_encode(["success" => false, "error" => "Agenda no encontrada"]);
                exit;
            }

            $horaInicio = strtotime($agenda['hora_desde']);
            $horaFin = strtotime($agenda['hora_hasta']);
            $fecha = $agenda['fecha_agenda'];

            while ($horaInicio < $horaFin) {
                $turno = new Turno();
                $turno->setMinutos_turnos($minutos);
                $turno->setFecha_hora($fecha . " " . date("H:i:s", $horaInicio));
                $turno->setDisponible(1);
                $turno->setAgenda_id_agenda($id_agenda);
                $turno->guardarTurno();

                $horaInicio = strtotime("+$minutos minutes", $horaInicio);
            }

            echo json_encode(["success" => true]);
    }
}
