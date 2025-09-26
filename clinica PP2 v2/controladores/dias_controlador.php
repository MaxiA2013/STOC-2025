<?php
require_once "../modelos/dias.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $dias = new Dias();
            $dias->setDescripcion($_POST['descripcion']);
            $dias->guardarDias();
            header('Location: ../index.php?page=dias_lista');
            break;
        case 'eliminacion':
            $dias = new Dias();
            $dias->setId_Dias($_POST['id_dias']);
            $dias->eliminarDias();
            header('Location: ../index.php?page=dias_lista');
            break;
        case 'actualizacion':
            $dias = new Dias();
            $dias->setId_Dias($_POST['id_dias']);
            $dias->setDescripcion($_POST['descripcion']);
            $dias->actualizarDias();
            header('Location: ../index.php?page=dias_lista');
            break;
    }
}
