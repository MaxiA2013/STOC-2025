<?php
require_once "../modelos/familiar.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $fam = new Familiar();
            $fam->setRelacion($_POST['relacion']);
            $fam->setDescripcion($_POST['descripcion']);
            $fam->guardarFamiliar();
            header('Location: ../index.php?page=familiar_lista');
            break;
        case 'eliminacion':
            $fam = new Familiar();
            $fam->setId_familiar($_POST['id_familiar']);
            $fam->eliminarFamiliar();
            header('Location: ../index.php?page=familiar_lista');
            break;
        case 'actualizacion':
            $fam = new Familiar();
            $fam->setId_familiar($_POST['id_familiar']);
            $fam->setRelacion($_POST['relacion']);
            $fam->setDescripcion($_POST['descripcion']);
            $fam->actualizarFamiliar();
            header('Location: ../index.php?page=familiar_lista');
            break;
    }
}
