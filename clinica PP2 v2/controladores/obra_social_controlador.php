<?php
require_once "../modelos/obra_social.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $obra = new Obra_Social();
            $obra->setNombreObraSocial($_POST['nombre_obra_social']);
            $obra->setDetalle($_POST['detalle']);
            $obra->guardarObraSocial();
            header('Location: ../index.php?page=obra_social_lista');
            break;
        case 'eliminacion':
            $obra = new Obra_Social();
            $obra->setIdObraSocial($_POST['id_obra_social']);
            $obra->eliminarObraSocial();
            header('Location: ../index.php?page=obra_social_lista');
            break;
        case 'actualizacion':
            $obra = new Obra_Social();
            $obra->setIdObraSocial($_POST['id_obra_social']);
            $obra->setDetalle($_POST['detalle']);
            $obra->setNombreObraSocial($_POST['nombre_obra_social']);
            $obra->actualizarObraSocial();
            header('Location: ../index.php?page=obra_social_lista');
            break;
    }
}
