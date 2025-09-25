<?php
require_once "../modelos/estados.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
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
            $estados = new Estado();
            $estados->setId_estados($_POST['id_estados']);
            $estados->setTipo_estado($_POST['tipo_estado']);
            $estados->setDescripcion($_POST['descripcion']);
            $estados->eliminarEstado();
            header('Location: ../index.php?page=estados_lista');
            break;
    }
}
