<?php
require_once "../modelos/obraSocialPrestacion.php";
require_once "../modelos/conexion.php";

if (isset($_POST['action'])) {

    $accion = $_POST['action'];
    $osp = new ObraSocialPrestacion();

    switch ($accion) {

        /* INSERTAR / ALTA DE COBERTURA */
        case 'insertar':
            // Validar que no vengan campos vacíos
            if (
                empty($_POST['obra_social_id']) ||
                empty($_POST['prestacion_id']) ||
                empty($_POST['codigo']) ||
                empty($_POST['precio']) ||
                empty($_POST['estado'])
            ) {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Todos los campos obligatorios deben completarse.'
                ]);
                exit();
            }

            // Asignamos las variables al objeto
            $osp->obra_social_id = $_POST['obra_social_id'];
            $osp->prestacion_id = $_POST['prestacion_id'];
            $osp->codigo = $_POST['codigo'];
            $osp->precio = $_POST['precio'];
            $osp->estado = $_POST['estado'];

            // Guardamos en la base de datos
            $resultado = $osp->guardarCobertura();

            // Sabiendo que conexion.php devuelve el id de inserción (y da 0 al no ser autoincremental),
            // cualquier respuesta que no sea estrictamente un error (false) es un éxito rotundo.
            if ($resultado !== false && $resultado !== null) {
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Cobertura registrada correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No se pudo guardar la cobertura. Verifique si ya existe.'
                ]);
            }
            exit();
            break;

        /* ACTUALIZAR / MODIFICACIÓN DE COBERTURA */
        case 'actualizacion':
            // Validar campos obligatorios para la edición
            if (
                empty($_POST['id_obra_social']) ||
                empty($_POST['id_prestacion']) ||
                empty($_POST['codigo']) ||
                empty($_POST['precio']) ||
                empty($_POST['estado'])
            ) {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Todos los campos son obligatorios.'
                ]);
                exit();
            }

            $osp->obra_social_id = $_POST['id_obra_social'];
            $osp->prestacion_id = $_POST['id_prestacion'];
            $osp->codigo = $_POST['codigo'];
            $osp->precio = $_POST['precio'];
            $osp->estado = $_POST['estado'];

            $resultado = $osp->actualizarCobertura();

            if ($resultado === true) {
                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Cobertura actualizada correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No se pudieron actualizar los datos.'
                ]);
            }
            exit();
            break;

        /* ELIMINAR / BAJA LÓGICA */
        case 'eliminacion':
            if (empty($_POST['id_obra_social']) || empty($_POST['id_prestacion'])) {
                echo json_encode([
                    'success' => false, 
                    'mensaje' => 'Identificadores inválidos.'
                ]);
                exit();
            }

            $osp->obra_social_id = $_POST['id_obra_social'];
            $osp->prestacion_id = $_POST['id_prestacion'];

            $osp->eliminarLogico(); // Cambia visible a 0 en la BD

            echo json_encode([
                'success' => true,
                'mensaje' => 'Cobertura eliminada correctamente'
            ]);
            exit();
            break;
    }
}
