<?php
require_once "../modelos/obra_social.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $obra = new obra_social();
            $obra->setNombreObraSocial($_POST['nombre_obra_social']);
            $obra->guardarObraSocial();
            header('Location: ../../index.php?page=obra_social_lista');
            break;
        case 'eliminacion':
            $obra = new obra_social();
            $obra->setIdObraSocial($_POST['id_obra_social']);
            $obra->eliminarObraSocial();
            header('Location: ../../index.php?page=obra_social_lista');
            break;
        case 'actualizacion':
            $obra = new obra_social();
            $obra->setIdObraSocial($_POST['id_obra_social']);
            $obra->setNombreObraSocial($_POST['nombre_obra_social']);
            $obra->actualizarObraSocial();
            header('Location: ../../index.php?page=obra_social_lista');
            break;
    }
}
