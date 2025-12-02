<?php
require_once "../modelos/familiar.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validacion previa al registro

            //Validacion de campo vacio
            if (empty($_POST['relacion'])) {
                header('Location: ../index.php?page=familiar_lista');
                exit();
            }

            //Validacion de duplicado
            $familiarTemp= new Familiar();
            $familiarTemp->setRelacion($_POST['relacion']);
            $existeFamiliar = $familiarTemp->existeFamiliar();
            if ($existeFamiliar->num_rows > 0) {
                header('Location: ../index.php?page=familiar_lista');
                exit();
            }

            //Validacion de campo vacio
            if (empty($_POST['descripcion'])) {
                header('Location: ../index.php?page=familiar_lista');
                exit();
            }

            //Validacion previa al registro
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
            //validacion previa 

            //Validacion previa
            $fam = new Familiar();
            $fam->setId_familiar($_POST['id_familiar']);
            $fam->setRelacion($_POST['relacion']);
            $fam->setDescripcion($_POST['descripcion']);
            $fam->actualizarFamiliar();
            header('Location: ../index.php?page=familiar_lista');
            break;
    }
}
