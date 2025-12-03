<?php
//ESTE ARCHIVO RECUPERA INFORMACIÓN DE TURNOS DE UNA DETERMINADA AGENDA Y LO ENVIA TURNO_LISTA
require_once "../../modelos/turno.php";
require_once "../../modelos/conexion.php";

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_GET["id_agenda"])) {
    echo json_encode([
        "status" => "error",
        "message" => "Falta el parámetro id_agenda"
    ]);
    exit;
}

$id_agenda = intval($_GET["id_agenda"]);

try {
    $turnos = new Turno();
    $turnosDisp = $turnos->listarTurnoXAgenda($id_agenda);

    $data = [];

    foreach ($turnosDisp as $t){
        $data[] = [
            'id_turnos' => $t['id_turnos'],
            'minutos_turnos' => $t['minutos_turnos'],
            'fecha_hora' => $t['fecha_hora'],
            'disponible' => $t['disponible'],
            'agenda_id_agenda' => $t['agenda_id_agenda'],
        ];
    }
   


    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudieron obtener los turnos.",
        "error" => $e->getMessage()
    ]);
}
?>
