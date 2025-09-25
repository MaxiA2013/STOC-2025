<?php
require_once "../modelos/especialidad.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            $especialidades = new Especialidad();
            $especialidades->setNombreEspecialidad($_POST['nombre_especialidad']);
            $especialidades->guardarEspecialidad();
            header('Location: ../index.php?page=especialidad_lista');
            break;
        case 'eliminacion':
            $especialidades = new Especialidad();
            $especialidades->setIdEspecialidad($_POST['id_especialidad']);
            $especialidades->eliminarEspecialidad();
            header('Location: ../index.php?page=especialidad_lista');
            break;
        case 'actualizacion':
            $especialidades = new Especialidad();
            $especialidades->setIdEspecialidad($_POST['id_especialidad']);
            $especialidades->setNombreEspecialidad($_POST['nombre_especialidad']);
            $especialidades->actualizarEspecialidad();
            header('Location: ../index.php?page=especialidad_lista');
            break;
    }
}
