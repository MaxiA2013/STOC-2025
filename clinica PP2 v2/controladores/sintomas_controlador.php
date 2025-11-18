<?php
require_once "../modelos/sintomas.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validacion previa al registro

            //Validacion de campos vacios
            if (empty($_POST['nombre_sintomas'])) {
                header('Location: ../index.php?page=sintomas_lista');
                exit();
            }

            //Validacion de duplicado
            $sintomaTemp = new Sintomas();
            $sintomaTemp->setNombreSintomas($_POST['nombre_sintomas']);
            $existeSintoma = $sintomaTemp->existeSintoma();
            if ($existeSintoma->num_rows > 0) {
                header('Location: ../index.php?page=sintomas_lista');
                exit();
            }

            //Validacion previa al registro

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
            //validacion de campos vacios
            if (empty($_POST['nombre_sintomas']) or empty($_POST['descripcion'])) {
                header('Location: ../index.php?page=sintomas_lista');
                exit();
            }
            //Validacion de campos vacios
            $sintom = new Sintomas();
            $sintom->setIdSintomas($_POST['id_sintomas']);
            $sintom->setNombreSintomas($_POST['nombre_sintomas']);
            $sintom->setDescripcion($_POST['descripcion']);
            $sintom->actualizarSintoma();
            header('Location: ../index.php?page=sintomas_lista');
            break;
    }
}
