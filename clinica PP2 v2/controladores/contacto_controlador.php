<?php
require_once "../modelos/contacto.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validacion previa al registro 

            //Validacion de campo vacio
            if (empty($_POST['tipo_contacto'])) {
                header('Location: ../index.php?page=contacto_lista');
                exit();
            }
            //Validacion de duplicado

            $contactoTemp = new Contacto();
            $contactoTemp->setTipoContacto($_POST['tipo_contacto']);
            $existeContacto= $contactoTemp->existeContacto();
            if ($existeContacto->num_rows > 0) {
                header('Location: ../index.php?page=contacto_lista');
                exit();
            }

            //Validacion de campo vacio
            if (empty($_POST['descripcion'])) {
                header('Location: ../index.php?page=contacto_lista');
                exit();
            }

            //Validacion previa al registro
            $contacto = new Contacto();
            $contacto->setTipoContacto($_POST['tipo_contacto']);
            $contacto->setDescripcion($_POST['descripcion']);
            $contacto->guardarContacto();
            header('Location: ../index.php?page=contacto_lista');
            break;
        case 'eliminacion':
            $contacto = new Contacto();
            $contacto->setIdContacto($_POST['id_contacto']);
            $contacto->eliminarContacto();
            header('Location: ../index.php?page=contacto_lista');
            break;
        case 'actualizacion':
            //Validacion de campo vacio
            if (empty($_POST['tipo_contacto']) or empty($_POST['descripcion'])) {
                header('Location: ../index.php?page=contacto_lista');
                exit();
            }
            //Validacion de campo vacio
            $contacto = new Contacto();
            $contacto->setIdContacto($_POST['id_contacto']);
            $contacto->setTipoContacto($_POST['tipo_contacto']);
            $contacto->setDescripcion($_POST['descripcion']);
            $contacto->actualizarContacto();
            header('Location: ../index.php?page=contacto_lista');
            break;
    }
}
