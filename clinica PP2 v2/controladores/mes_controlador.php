<?php
require_once "../modelos/mes.php";

header("Content-Type: application/json");

$accion = $_POST['action'] ?? '';

switch ($accion) {

    case 'listar':
        $mes = new Mes();
        $result = $mes->consultarVariosMes();

        $data = [];
        while ($fila = $result->fetch_assoc()) {
            $data[] = $fila;
        }

        echo json_encode($data);
        break;

    case 'insertar':
        if (empty($_POST['nombre_mes'])) {
            echo json_encode(["status" => "error", "message" => "Complete el campo"]);
            exit;
        }

        $temp = new Mes();
        $temp->setNombre_mes($_POST['nombre_mes']);
        $dup = $temp->existeMes();

        if ($dup->num_rows > 0) {
            echo json_encode(["status" => "error", "message" => "El mes ya existe"]);
            exit;
        }

        $mes = new Mes();
        $mes->setNombre_mes($_POST['nombre_mes']);
        $mes->guardarMes();

        echo json_encode(["status" => "ok"]);
        break;

    case 'eliminar':
        $mes = new Mes();
        $mes->setId_mes($_POST['id_mes']);
        $mes->eliminarMes();

        echo json_encode(["status" => "ok"]);
        break;

    case 'actualizar':

        if (empty($_POST['nombre_mes'])) {
            echo json_encode(["status" => "error", "message" => "Complete el campo"]);
            exit;
        }

        $mes = new Mes();
        $mes->setId_mes($_POST['id_mes']);
        $mes->setNombre_mes($_POST['nombre_mes']);
        $mes->actualizarMes();

        echo json_encode(["status" => "ok"]);
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Accion no valida"]);
}
