<?php
require_once "../modelos/sintomas.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $sintom = new Sintomas();
            $sintom->setNombreSintomas($_POST['nombre_sintomas']);
            $sintom->setDescripcion($_POST['descripcion']);
            $sintom->guardarsintoma();
            header('Location: ../index.php?page=sintomas_lista');
            break;
        case 'eliminacion':
            $sintom = new Sintomas();
            $sintom->setIdSintomas($_POST['id_sintomas']);
            $sintom->eliminarSintoma();
            header('Location: ../index.php?page=sintomas_lista');
            break;
        case 'actualizacion':
            $sintom = new Sintomas();
            $sintom->setIdSintomas($_POST['id_sintomas']);
            $sintom->setNombreSintomas($_POST['nombre_sintomas']);
            $sintom->setDescripcion($_POST['descripcion']);
            $sintom->actualizarSintoma();
            header('Location: ../index.php?page=sintomas_lista');
            break;
    }
}
