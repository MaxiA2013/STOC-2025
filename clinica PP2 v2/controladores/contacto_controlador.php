<?php
require_once "../modelos/contacto.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
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
            $contacto = new Contacto();
            $contacto->setIdContacto($_POST['id_contacto']);
            $contacto->setTipoContacto($_POST['tipo_contacto']);
            $contacto->setDescripcion($_POST['descripcion']);
            $contacto->actualizarContacto();
            header('Location: ../index.php?page=contacto_lista');
            break;
    }
}
