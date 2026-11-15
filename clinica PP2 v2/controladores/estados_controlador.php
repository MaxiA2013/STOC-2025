<?php
require_once "../modelos/estados.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validacion previa al registro
            if (empty($_POST['tipo_estado'])){
                header('Location: ../index.php?page=estados_lista');
                exit();
            }

            //Validacion de duplicado
            $estadoTemp = new Estado();
            $estadoTemp->setTipo_estado($_POST['tipo_estado']);
            $existeEstado= $estadoTemp->existeEstado();
            if ($existeEstado->num_rows > 0) {
                header('Location: ../index.php?page=estados_lista');
                exit();
            }

            if (empty($_POST['descripcion'])){
                header('Location: ../index.php?page=estados_lista');
                exit();
            }
            //Validacion previa al registro 

            $estados = new Estado();
            $estados->setTipo_estado($_POST['tipo_estado']);
            $estados->setDescripcion($_POST['descripcion']);
            $estados->guardarEstado();
            header('Location: ../index.php?page=estados_lista');
            break;
        case 'eliminacion':
            $estados = new Estado();
            $estados->setId_estados($_POST['id_estados']);
            $estados->actualizarEstado();
            header('Location: ../index.php?page=estados_lista');
            break;
        case 'actualizacion':
            //Validacion de campo vacio
            if (empty($_POST['tipo_estado']) or empty($_POST['descripcion'])){
                header('Location: ../index.php?page=estados_lista');
                exit();
            }

            //validadcion de campo vacio

            $estados = new Estado();
            $estados->setId_estados($_POST['id_estados']);
            $estados->setTipo_estado($_POST['tipo_estado']);
            $estados->setDescripcion($_POST['descripcion']);
            $estados->eliminarEstado();
            header('Location: ../index.php?page=estados_lista');
            break;
    }
}
