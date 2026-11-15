<?php
require_once "../modelos/franja_horaria.php";
header("Content-Type: application/json");

$accion = $_POST['action'] ?? '';

switch ($accion) {

    case "listar":
        $f = new Franja();
        $res = $f->consultarVariasFranjas();

        $data = [];
        while ($row = $res->fetch_assoc()) $data[] = $row;

        echo json_encode($data);
        break;

    case "insertar":

        if ($_POST["inicio_franja"] === $_POST["fin_franja"]) {
            echo json_encode(["status" => "error", "message" => "La franja no puede durar 0 minutos"]);
            exit;
        }

        $f = new Franja();
        $f->setTipo_franja($_POST["tipo_franja"]);
        $f->setInicio_franja($_POST["inicio_franja"]);
        $f->setFin_franja($_POST["fin_franja"]);

        $dup = $f->existeFranja();
        if ($dup->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "Ya existe una franja con ese tipo"]);
            exit;
        }

        $f->guardarFranja();
        echo json_encode(["status" => "ok"]);
        break;


    case "actualizar":

        if ($_POST["inicio_franja"] === $_POST["fin_franja"]) {
            echo json_encode(["status" => "error", "message" => "La franja no puede durar 0 minutos"]);
            exit;
        }

        $f = new Franja();
        $f->setId_franja($_POST["id_franja"]);
        $f->setTipo_franja($_POST["tipo_franja"]);
        $f->setInicio_franja($_POST["inicio_franja"]);
        $f->setFin_franja($_POST["fin_franja"]);
        $f->actualizarFranja();

        echo json_encode(["status" => "ok"]);
        break;


    case "eliminar":
        $f = new Franja();
        $f->setId_franja($_POST["id_franja"]);
        $f->eliminarFranja();
        echo json_encode(["status" => "ok"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
}
