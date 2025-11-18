<?php
require_once "../modelos/metodo_pago.php";
if (isset($_POST['action'])){
    $accion= $_POST['action'];
    switch ($accion){
        case 'insertar':
            //Validacion previa al registro
            //Validacion de campo vacio
            if (empty($_POST['nombre_metodo'])) {
                header('Location: ../index.php?page=metodo_pago_lista');
                exit();
            }

            //Validacion de duplicado
            $metodoTemp = new Metodo_Pago();
            $metodoTemp->setNombre_metodo($_POST['nombre_metodo']);
            $existeMetodo= $metodoTemp->existeMetodoPago();
            if ($existeMetodo->num_rows > 0) {
                header('Location: ../index.php?page=metodo_pago_lista');
                exit();
            }

            //Validacion previa al registro
            $metodoPago = new Metodo_Pago();
            $metodoPago->setNombre_metodo($_POST['nombre_metodo']);
            $metodoPago->guardarMetodoPago();
            header('Location: ../index.php?page=metodo_pago_lista');
            break;
        case 'eliminacion':
            $metodoPago = new Metodo_Pago();
            $metodoPago->setId_metodo_pago($_POST['id_metodo_pago']);
            $metodoPago->eliminarMetodoPago();
            header('Location: ../index.php?page=metodo_pago_lista');
            break;
        case 'actualizacion':
            //Validacion de campo vacio
            if (empty($_POST['nombre_metodo'])) {
                header('Location: ../index.php?page=metodo_pago_lista');
                exit();
            }
            //Validacion de campo vacio
            $metodoPago = new Metodo_Pago();
            $metodoPago->setId_metodo_pago($_POST['id_metodo_pago']);
            $metodoPago->setNombre_metodo($_POST['nombre_metodo']);
            $metodoPago->actualizarMetodoPago();
            header('Location: ../index.php?page=metodo_pago_lista');
            break;
    }
}
