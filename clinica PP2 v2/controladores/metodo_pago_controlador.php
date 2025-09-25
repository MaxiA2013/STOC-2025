<?php
require_once "../modelos/metodo_pago.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $obra = new Metodo_Pago();
            $obra->setNombre_metodo($_POST['nombre_metodo']);
            $obra->guardarMetodoPago();
            header('Location: ../index.php?page=metodo_pago_lista');
            break;
        case 'eliminacion':
            $obra = new Metodo_Pago();
            $obra->setId_metodo_pago($_POST['id_metodo_pago']);
            $obra->eliminarMetodoPago();
            header('Location: ../index.php?page=metodo_pago_lista');
            break;
        case 'actualizacion':
            $obra = new Metodo_Pago();
            $obra->setId_metodo_pago($_POST['id_metodo_pago']);
            $obra->setNombre_metodo($_POST['nombre_metodo']);
            $obra->actualizarMetodoPago();
            header('Location: ../index.php?page=metodo_pago_lista');
            break;
    }
}
