<?php
require_once "../modelos/dias.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validaciones previas al registro

            //Validacion de campo vacio
            if (empty($_POST['descripcion'])) {
                header('Location: ../index.php?page=dias_lista');
                exit();
            }

            //Validacion de duplicados
            $diaTemp = new Dias();
            $diaTemp->setDescripcion($_POST['descripcion']);
            $existDia = $diaTemp->existeDia();
            if ($existDia->num_rows > 0) {
                header('Location: ../index.php?page=dias_lista');
                exit();
            }

            //Validaciones previas al registro

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
            //Validacion de campos vacios
            if (empty($_POST['descripcion'])) {
                header('Location: ../index.php?page=dias_lista');
                exit();
            }
            //Validacion de campos vacios

            $dias = new Dias();
            $dias->setId_Dias($_POST['id_dias']);
            $dias->setDescripcion($_POST['descripcion']);
            $dias->actualizarDias();
            header('Location: ../index.php?page=dias_lista');
            break;
    }
}
