<?php
require_once "../modelos/obra_social.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validaciones antes de guardar
            if (empty($_POST['nombre_obra_social'])) {
                header('Location: ../index.php?page=obra_social_lista');
                exit;
            }

            //valida campo vacio
            $obraTemp = new Obra_Social();
            $obraTemp->setNombreObraSocial($_POST['nombre_obra_social']);
            $existeObra = $obraTemp->existeObraSocial();
            if ($existeObra->num_rows > 0) {
                header('Location: ../index.php?page=obra_social_lista');
                exit;
            }
            //valida duplicado

            if (empty($_POST['detalle'])) {
                header('Location: ../index.php?page=obra_social_lista');
                exit;
            }
            //Validaciones antes de guardar

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

            //Validacion de campos vacios
            if (empty($_POST['nombre_obra_social']) or empty($_POST['detalle']) ) {
                header('Location: ../index.php?page=obra_social_lista');
                exit;
            }

            $obra = new Obra_Social();
            $obra->setIdObraSocial($_POST['id_obra_social']);
            $obra->setDetalle($_POST['detalle']);
            $obra->setNombreObraSocial($_POST['nombre_obra_social']);
            $obra->actualizarObraSocial();
            header('Location: ../index.php?page=obra_social_lista');
            break;
    }
}
