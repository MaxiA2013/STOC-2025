<?php
require_once "../../modelos/usuarios.php";
if (isset($_POST['action'])){
    $accion = $_POST['action'];
    switch ($accion) {
        case 'insertar':
            $usuario = new Usuario();
            
            break;
        case 'eliminacion':
            $usuario = new Usuario();
            $usuario->setId_usuario($_POST['id_usuario']);
            $usuario->eliminar();
            header('Location: ../../index.php?page=lista_usuario');
            break;
        }
    }


if (isset($_POST['usuarios']));