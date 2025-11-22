<?php
require_once "../modelos/sintomas.php";

header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Acción no válida"];

if (!isset($_POST['action'])) {
    echo json_encode($response);
    exit;
}

$accion = $_POST['action'];

switch ($accion) {

    case "insertar":

        if (empty($_POST["nombre_sintomas"]) || empty($_POST["descripcion"])) {
            echo json_encode(["status" => "error", "message" => "Complete los campos"]);
            exit;
        }

        $temp = new Sintomas();
        $temp->setNombreSintomas($_POST["nombre_sintomas"]);
        $existe = $temp->existeSintoma();

        if ($existe->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "El síntoma ya existe"]);
            exit;
        }

        $s = new Sintomas();
        $s->setNombreSintomas($_POST["nombre_sintomas"]);
        $s->setDescripcion($_POST["descripcion"]);
        $s->guardarSintoma();

        echo json_encode(["status" => "ok"]);
        break;


    case "actualizar":

        if (empty($_POST["nombre_sintomas"]) || empty($_POST["descripcion"])) {
            echo json_encode(["status" => "error", "message" => "Complete los campos"]);
            exit;
        }

        $s = new Sintomas();
        $s->setIdSintomas($_POST["id_sintomas"]);
        $s->setNombreSintomas($_POST["nombre_sintomas"]);
        $s->setDescripcion($_POST["descripcion"]);
        $s->actualizarSintoma();

        echo json_encode(["status" => "ok"]);
        break;


    case "eliminar":

        $s = new Sintomas();
        $s->setIdSintomas($_POST["id_sintomas"]);
        $s->eliminarSintoma();

        echo json_encode(["status" => "ok"]);
        break;

}

