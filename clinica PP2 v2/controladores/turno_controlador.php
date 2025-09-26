<?php
require_once "../modelos/turno.php";

if (isset($_POST['action'])) {
    $accion = $_POST['action'];

    switch ($accion) {
        case 'insertar':
            $turno = new Turno();
            $turno->setMinutos_turnos($_POST['minutos_turnos']);
            $turno->setFecha_hora($_POST['fecha_hora']);
            $turno->setDisponible($_POST['disponible'] ?? 0);
            $turno->setAgenda_id_agenda($_POST['agenda_id_agenda']);
            $turno->guardarTurno();
            header('Location: ../index.php?page=turno_lista');
            break;

        case 'eliminacion':
            $turno = new Turno();
            $turno->setId_turnos($_POST['id_turnos']);
            $turno->eliminarTurno();
            header('Location: ../index.php?page=turno_lista');
            break;

        case 'actualizacion':
            $turno = new Turno();
            $turno->setId_turnos($_POST['id_turnos']);
            $turno->setMinutos_turnos($_POST['minutos_turnos']);
            $turno->setFecha_hora($_POST['fecha_hora']);
            $turno->setDisponible($_POST['disponible'] ?? 0);
            $turno->setAgenda_id_agenda($_POST['agenda_id_agenda']);
            $turno->actualizarTurno();
            header('Location: ../index.php?page=turno_lista');
            break;
    }
}
?>
