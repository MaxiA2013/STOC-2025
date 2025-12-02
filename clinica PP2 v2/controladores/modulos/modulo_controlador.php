<?php
require_once "../../modelos/modulos.php";

if (isset($_POST['action'])) {
    $accion = $_POST['action'];
    $modulo = new Modulos();
    $tablas = $_POST['tablas'] ?? [];

    switch ($accion) {
        case 'insertar':
            $modulo->setNombre($_POST['nombre_modulo']);
            // guardar y obtener id
            $id_modulo = $modulo->guardarModulo();

            // asignar tablas seleccionadas
            foreach ($tablas as $id_tabla) {
                $modulo->asignarTabla($id_modulo, $id_tabla);
            }

            header('Location: ../../index.php?page=modulos');
            break;

        case 'actualizacion':
            $id_mod = $_POST['id_modulos'];
            $modulo->setIdModulos($id_mod);
            $modulo->setNombre($_POST['nombre_modulo']);
            $modulo->actualizarModulo();

            // desasigna y reasigna las tablas
            $modulo->desasignarTablas($id_mod);
            foreach ($tablas as $id_tabla) {
                $modulo->asignarTabla($id_mod, $id_tabla);
            }

            header('Location: ../../index.php?page=modulos');
            break;

        case 'eliminacion':
            $modulo->setIdModulos($_POST['id_modulos']);
            $modulo->eliminarModulo();
            header('Location: ../../index.php?page=modulos');
            break;
    }
}
?>