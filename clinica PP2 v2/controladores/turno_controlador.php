<?php
require_once "../modelos/turno.php";
require_once "../modelos/agenda.php";

// si viene por POST
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

        case 'generar_turnos':
            $agenda_id = intval($_POST['agenda_id']);
            $minutos = intval($_POST['minutos_turnos']);

            $agenda = new Agenda();
            $datos = $agenda->obtenerPorId($agenda_id);

            if ($datos) {
                $inicio = strtotime($datos['fecha_agenda'] . " " . $datos['hora_desde']);
                $fin = strtotime($datos['fecha_agenda'] . " " . $datos['hora_hasta']);

                while ($inicio < $fin) {
                    $turno = new Turno();
                    $turno->setMinutos_turnos($minutos);
                    $turno->setFecha_hora(date("Y-m-d H:i:s", $inicio));
                    $turno->setDisponible(1);
                    $turno->setAgenda_id_agenda($agenda_id);
                    $turno->guardarTurno();

                    $inicio = strtotime("+{$minutos} minutes", $inicio);
                }
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Agenda no encontrada"]);
            }
            exit;
    }
}

// si no viene action -> redirigir
header('Location: ../index.php?page=turno_lista');
exit;
