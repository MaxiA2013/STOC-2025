<?php

require_once "../../modelos/pais.php";

header('Content-Type: application/json; charset=utf-8');

try {

    $accion = $_POST['accion'] ?? '';

    switch ($accion) {

        /* INSERTAR */
        case 'insertar':
            $nombre_pais = trim(
                $_POST['nombre_pais'] ?? ''
            );

            if ($nombre_pais === '') {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'El nombre del país es obligatorio.'
                ]);

                exit;
            }

            $pais = new Pais();

            $pais->setNombre_pais($nombre_pais);

            $resultado = $pais->guardarPais();

            if ($resultado) {

                echo json_encode([
                    'success' => true,
                    'mensaje' => 'País registrado correctamente.'
                ]);

            } else {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No se pudo registrar el país.'
                ]);
            }

            break;


        /* ACTUALIZAR */
        case 'actualizacion':

            $id = (int) ($_POST['id_pais'] ?? 0);

            $nombre_pais = trim(
                $_POST['nombre_pais'] ?? ''
            );

            if ($id <= 0) {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'El país seleccionado no es válido.'
                ]);

                exit;
            }

            if ($nombre_pais === '') {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'El nombre del país es obligatorio.'
                ]);

                exit;
            }

            $pais = new Pais();

            $pais->setId_pais($id);
            $pais->setNombre_pais($nombre_pais);

            $resultado = $pais->actualizarPais();

            echo json_encode([
                'success' => true,
                'mensaje' => 'País actualizado correctamente.'
            ]);

            break;


        /* ELIMINAR */
        case 'eliminacion':

            $id = (int) ($_POST['id_pais'] ?? 0);
            if ($id <= 0) {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'El país seleccionado no es válido.'
                ]);

                exit;
            }

            $pais = new Pais();
            $pais->setId_pais($id);
            $resultado = $pais->eliminarPais();

            if ($resultado !== false) {

                echo json_encode([
                    'success' => true,
                    'mensaje' => 'País eliminado correctamente.'
                ]);

            } else {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No se pudo eliminar el país.'
                ]);
            }

            break;

        default:
            echo json_encode([
                'success' => false,
                'mensaje' => 'Acción no válida.'
            ]);

            break;
    }

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'mensaje' => 'Ocurrió un error al procesar la solicitud.'
    ]);
}