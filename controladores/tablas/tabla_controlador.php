<?php
require_once "../../modelos/tablas.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $tabla = new Tablas();
            $tabla->setNombreTabla($_POST['nombre_tabla']);
            $tabla->guardarTabla();
            header('Location: ../../index.php?page=tablas');
            break;
        case 'eliminacion':
            $tabla = new Tablas();
            $tabla->setIdTablas($_POST['id_tablas']);
            $tabla->eliminarTabla();
            header('Location: ../../index.php?page=tablas');
            break;
        case 'actualizacion':
            $tabla = new Tablas();
            $tabla->setIdTablas($_POST['id_tablas']);
            $tabla->setNombreTabla($_POST['nombre_tabla']);
            $tabla->actualizarTabla();
            header('Location: ../../index.php?page=tablas');
            break;
    }
}
