<?php
//etse el archivo para la funcionalidad de suditoria de la tabla turno_lista
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/turno.php";

$id_turno = intval($_GET["id_turno"] ?? 0);

if ($id_turno <= 0) {
    echo json_encode(["success" => false, "error" => "ID inválido."]);
    exit();
}

$turno = new Turno();
$historial = $turno->obtenerHistorial($id_turno, 4);

echo json_encode([
    "success" => true,
    "data" => $historial
]);