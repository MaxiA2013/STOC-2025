<?php
require_once "../../modelos/perfil.php";
if (isset($_POST['action'])) {
    $accion = $_POST['action'];
    switch ($accion) {
        case 'insertar':
            $perfil = new Perfil();
            $perfil->setNombre_perfil($_POST['nombre_perfil']);
            $perfil->setDescripcion($_POST['descripcion']);
            $perfil->guardaPerfil();
            header('Location: ../../index.php?page=perfiles');
            break;
        case 'eliminacion':
            $perfil = new Perfil();
            $perfil->setId_perfil($_POST['id_perfil']);
            $perfil->eliminarPerfil();
            header('Location: ../../index.php?page=perfiles');
            break;
        case 'actualizacion':
            $perfil = new Perfil();
            $perfil->setId_perfil($_POST['id_perfil']);
            $perfil->setNombre_perfil($_POST['nombre_perfil']);
            $perfil->setDescripcion($_POST['descripcion']);
            $perfil->actualizarPerfil();
            header('Location: ../../index.php?page=perfiles');
            break;
    }
}
