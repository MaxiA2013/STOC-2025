<?php
require_once "../modelos/agenda.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    if ($action == "guardar_agenda") {
        $fecha_desde = $_POST["fecha_desde"];
        $fecha_hasta = $_POST["fecha_hasta"];
        $hora_desde = $_POST["hora_desde"];
        $hora_hasta = $_POST["hora_hasta"]; 
        $minutos_turnos = $_POST["minutos_turnos"];
        $dias_id_dias = $_POST["dias_id_dias"];
        $doctor_id_doctor = $_POST["doctor_id_doctor"];

        $agenda = new Agenda(
            null,
            $fecha_desde,
            $fecha_hasta,
            $hora_desde,
            $hora_hasta,
            $minutos_turnos,
            $dias_id_dias,
            $doctor_id_doctor
        );

        $resultado = $agenda->guardar();

        if ($resultado) {
            header("Location: ../vistas/paginas/lista_agenda.php?mensaje=ok");
            exit();
        } else {
            header("Location: ../vistas/paginas/lista_agenda.php?mensaje=error");
            exit();
        }
    }
}
?>