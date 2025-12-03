<?php
//ESTE ARCHIVO RECUPERA INFORMACION DE PACIENTES Y LO DEVUELVE A TURNO_LISTA
require_once "../../modelos/paciente.php";

header("Content-Type: application/json; charset=UTF-8");

try {
    $paciente = new Paciente();
    $lista = $paciente->listarPacientes();
    $data = [];
    foreach ($lista as $l ){
        $data[] = [
            'id_paciente'=> $l['id_paciente'],
            'nombre'=> $l['nombre'],
            'apellido'=> $l['apellido'],
            'nombre_usuario'=> $l['nombre_usuario'],
        ];
    }

    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "No se pudieron obtener los pacientes.",
        "error" => $e->getMessage()
    ]);
}
?>
