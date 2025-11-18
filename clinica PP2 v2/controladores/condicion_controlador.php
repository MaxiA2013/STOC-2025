<?php
require_once "../modelos/condicion.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validaciones previas al registro
            if (empty($_POST['nombre_condicion'])) {
                header('Location: ../index.php?page=condicion_lista&message=El nombre de la condicion es obligatorio&status=danger');
                exit();
            }

            //valida duplicados
            $condicionTemp = new Condicion();
            $condicionTemp->setNombreCondicion($_POST['nombre_condicion']);
            $existeCondicion = $condicionTemp->existeCondicion();
            if ($existeCondicion->num_rows > 0) {
                header('Location: ../index.php?page=condicion_lista&message=La condicion ya existe&status=danger');
                exit();
            }

            if (empty($_POST['detalle'])) {
                header('Location: ../index.php?page=condicion_lista&message=Complete el campo&status=danger');
                exit();
            }
            //Validaciones previas al registro

            //guarda la condicion
            $condicion = new Condicion();
            $condicion->setNombreCondicion($_POST['nombre_condicion']);
            $condicion->setDetalle($_POST['detalle']);
            $condicion->guardarCondicion();
            header('Location: ../index.php?page=condicion_lista');
            break;
        case 'eliminacion':
            $condicion = new Condicion();
            $condicion->setIdCondicion($_POST['id_condicion']);
            $condicion->eliminarCondicion();
            header('Location: ../index.php?page=condicion_lista');
            break;
        case 'actualizacion':

            //Validaciones previas antes de la actualizacion
            if (empty($_POST['nombre_condicion']) or (empty($_POST['detalle']))) {
                header('Location: ../index.php?page=condicion_lista&message=Complete los campos&status=danger');
                exit();
            }

            $condicion = new Condicion();
            $condicion->setIdCondicion($_POST['id_condicion']);
            $condicion->setNombreCondicion($_POST['nombre_condicion']);
            $condicion->setDetalle($_POST['detalle']);
            $condicion->setNombreCondicion($_POST['nombre_condicion']);
            $condicion->eliminarCondicion();
            header('Location: ../index.php?page=condicion_lista');
            break;
    }
}
