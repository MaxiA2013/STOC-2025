<?php
require_once "../modelos/agenda.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "guardar_agenda") {
        $fecha_agenda = $_POST["fecha_agenda"];
        $hora_desde = $_POST["hora_desde"];
        $hora_hasta = $_POST["hora_hasta"]; 
        $estados_id_estados = $_POST["estados_id_estados"];
        $doctor_id_doctor = $_POST["doctor_id_doctor"];

        $agenda = new Agenda(
            null,
            $fecha_agenda,
            $hora_desde,
            $hora_hasta,
            $estados_id_estados,
            $doctor_id_doctor
        );

        $resultado = $agenda->guardar();

        if ($resultado) {
            header("Location: ../index.php?page=lista_agenda&message=Agenda guardada correctamente&status=success");
            exit();
        } else {
            header("Location: ../index.php?page=lista_agenda&message=Agenda guardada correctamente&status=success");
            exit();
        }
    }
}
?>
