<?php
require_once "../modelos/especialidad.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validaciones previas al registro
            if (empty($_POST['nombre_especialidad'])) {
                header('Location: ../index.php?page=especialidad_lista');
                exit();
            }

            //Validacion de duplicados
            $especialidadTemp = new Especialidad();
            $especialidadTemp->setNombreEspecialidad($_POST['nombre_especialidad']);
            $existeEspecialidad = $especialidadTemp->existeEspecialidad();
            if ($existeEspecialidad->num_rows > 0) {
                header('Location: ../index.php?page=especialidad_lista');
                exit();
            }
            //Validaciones previas al registro

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
            //Validacion de campo vacio
            if (empty($_POST['nombre_especialidad'])) {
                header('Location: ../index.php?page=especialidad_lista');
                exit();
            }
            //Validacion de campo vacio

            $especialidades = new Especialidad();
            $especialidades->setIdEspecialidad($_POST['id_especialidad']);
            $especialidades->setNombreEspecialidad($_POST['nombre_especialidad']);
            $especialidades->actualizarEspecialidad();
            header('Location: ../index.php?page=especialidad_lista');
            break;
    }
}
