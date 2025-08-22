<?php
require_once "../../modelos/modulos.php";

if (isset($_POST['action'])) {
    $accion = $_POST['action'];
    $modulo = new Modulos();

    switch ($accion) {
        case 'insertar':
            // Guardar el módulo
            $modulo->setNombre($_POST['nombre_modulo']);
            $idModulo = $modulo->guardarModuloConRetorno(); // devuelve el ID con mysqli_insert_id

            // Guardar las tablas seleccionadas
            if (!empty($_POST['tablas'])) {
                foreach ($_POST['tablas'] as $idTabla) {
                    $modulo->asignarTablaAModulo($idModulo, $idTabla);
                }
            }

            header('Location: ../../index.php?page=modulos');
            break;

        case 'actualizacion':
            // Actualizar módulo
            $modulo->setIdModulos($_POST['id_modulos']);
            $modulo->setNombre($_POST['nombre_modulo']);
            $modulo->actualizarModulo();

            // Actualizar tablas
            $modulo->eliminarTablasDeModulo($_POST['id_modulos']); // borramos las relaciones viejas
            if (!empty($_POST['tablas'])) {
                foreach ($_POST['tablas'] as $idTabla) {
                    $modulo->asignarTablaAModulo($_POST['id_modulos'], $idTabla);
                }
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
