<?php
require_once "../../modelos/perfil.php";

if (isset($_POST['action'])) {
    $accion = $_POST['action'];
    $perfil = new Perfil();
    $modulos = $_POST['modulos'] ?? [];

    switch ($accion) {
        case 'insertar':
            $perfil->setNombre_perfil($_POST['nombre_perfil']);
            $perfil->setDescripcion($_POST['descripcion']);
            // guardaPerfil() devuelve el id insertado
            $id_perfil = $perfil->guardaPerfil();

            // asignar módulos seleccionados
            foreach ($modulos as $id_modulo) {
                $perfil->asignarModulo($id_perfil, $id_modulo);
            }

            header('Location: ../../index.php?page=perfiles');
            break;

        case 'actualizacion':
            $id = $_POST['id_perfil'];
            $perfil->setId_perfil($id);
            $perfil->setNombre_perfil($_POST['nombre_perfil']);
            $perfil->setDescripcion($_POST['descripcion']);
            $perfil->actualizarPerfil();

            // actualizar asignaciones: desasignar luego reasignar
            $perfil->desasignarModulos($id);
            foreach ($modulos as $id_modulo) {
                $perfil->asignarModulo($id, $id_modulo);
            }

            header('Location: ../../index.php?page=perfiles');
            break;

        case 'eliminacion':
            $perfil->setId_perfil($_POST['id_perfil']);
            $perfil->eliminarPerfil();
            header('Location: ../../index.php?page=perfiles');
            break;
    }
}
?>