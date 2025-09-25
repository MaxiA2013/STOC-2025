<?php
require_once "../modelos/condicion.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $condicion = new Condicion();
            $condicion->setNombreCondicion($_POST['nombre_condicion']);
            $condicion->setDetalle($_POST['detalle']);
            $condicion->guardarCondicion();
            header('Location: ../index.php?page=condicion_lista');
            break;
        case 'eliminacion':
            $condicion = new Condicion();
            $condicion->setIdCondicion($_POST['id_condicion']);
            $condicion->eliminarCondicion();
            header('Location: ../index.php?page=condicion_lista');
            break;
        case 'actualizacion':
            $condicion = new Condicion();
            $condicion->setIdCondicion($_POST['id_condicion']);
            $condicion->setNombreCondicion($_POST['nombre_condicion']);
            $condicion->setDetalle($_POST['detalle']);
            $condicion->setNombreCondicion($_POST['nombre_condicion']);
            $condicion->eliminarCondicion();
            header('Location: ../index.php?page=condicion_lista');
            break;
    }
}
