<?php

require_once "../../modelos/usuarios.php";
require_once "../../modelos/persona.php";
require_once "../../modelos/conexion.php";

if (isset($_POST['action'])) {

    $accion = $_POST['action'];

    switch ($accion) {

       /* INSERTAR USUARIO */
case 'insertar':

    /*
    =====================================================
    VALIDAR CAMPOS VACÍOS
    =====================================================
    */

    if (
        empty($_POST['nombre']) ||
        empty($_POST['apellido']) ||
        empty($_POST['fecha_nacimiento']) ||
        empty($_POST['sexo']) ||
        empty($_POST['nombre_usuario']) ||
        empty($_POST['email']) ||
        empty($_POST['password'])
    ) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'Todos los campos obligatorios deben completarse.'
        ]);

        exit();
    }


    /*
    =====================================================
    VALIDAR EDAD
    =====================================================
    */

    try {

        $fecha_nac = new DateTime($_POST['fecha_nacimiento']);
        $hoy = new DateTime();

        $edad = $hoy->diff($fecha_nac)->y;

        if ($edad < 18) {

            echo json_encode([
                'success' => false,
                'mensaje' => 'El usuario debe ser mayor de 18 años.'
            ]);

            exit();
        }

    } catch (Exception $e) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'La fecha de nacimiento no es válida.'
        ]);

        exit();
    }


    /*
    =====================================================
    VALIDAR USUARIO DUPLICADO
    =====================================================
    */

    $usuarioTemp = new Usuario();

    $usuarioTemp->setNombre_usuario(
        $_POST['nombre_usuario']
    );

    if ($usuarioTemp->usuarioExiste()->num_rows > 0) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'El nombre de usuario ya existe.'
        ]);

        exit();
    }


    /*
    =====================================================
    VALIDAR EMAIL DUPLICADO
    =====================================================
    */

    $usuarioTemp->setEmail(
        $_POST['email']
    );

    if ($usuarioTemp->buscar_cohincidencias()->num_rows > 0) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'El email ya está registrado.'
        ]);

        exit();
    }


    /*
    =====================================================
    VALIDAR PERSONA DUPLICADA
    =====================================================
    */

    $persona = new Persona();

    $persona->setNombre(
        $_POST['nombre']
    );

    $persona->setApellido(
        $_POST['apellido']
    );

    $persona->setSexo(
        $_POST['sexo']
    );

    $persona->setFecha_nacimiento(
        $_POST['fecha_nacimiento']
    );


    if ($persona->validar_persona()->num_rows > 0) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'La persona ya se encuentra registrada.'
        ]);

        exit();
    }


    /*
    =====================================================
    GUARDAR PERSONA
    =====================================================
    */

    $id_persona = $persona->guardar();


    if (!$id_persona) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'No se pudo guardar la persona.'
        ]);

        exit();
    }


    /*
    =====================================================
    GUARDAR USUARIO
    =====================================================
    */

    $usuario = new Usuario();

    $usuario->setNombre_usuario(
        $_POST['nombre_usuario']
    );

    $usuario->setEmail(
        $_POST['email']
    );

    $usuario->setPassword(
        $_POST['password']
    );

    $usuario->setPersona_id_persona(
        $id_persona
    );


    $id_usuario = $usuario->guardarUsuario();


    if (!$id_usuario) {

        echo json_encode([
            'success' => false,
            'mensaje' => 'No se pudo guardar el usuario.'
        ]);

        exit();
    }


    /*
    =====================================================
    ASIGNAR PERFIL
    =====================================================
    */

    $conn = new Conexion();

    $perfil_id = 3;


    if (
        isset($_POST['perfil_id_perfil']) &&
        in_array(
            $_POST['perfil_id_perfil'],
            ['1', '2', '3']
        )
    ) {

        $perfil_id = $_POST['perfil_id_perfil'];
    }


    $resultadoPerfil = $conn->insertar("
        INSERT INTO usuario_has_perfil
        (
            usuario_id_usuario,
            perfil_id_perfil
        )
        VALUES
        (
            $id_usuario,
            $perfil_id
        )
    ");


    /*
    =====================================================
    RESPUESTA FINAL AJAX
    =====================================================
    */

    echo json_encode([
        'success' => true,
        'mensaje' => 'Usuario registrado correctamente'
    ]);

    exit();

break;
        /* ELIMINAR */
        case 'eliminacion':

            $usuario = new Usuario();

            $usuario->setId_usuario($_POST['id_usuario']);

            $usuario->eliminar();

            echo json_encode([
                'success' => true,
                'mensaje' => 'Usuario eliminado correctamente'
            ]);

            exit();

            break;

        case 'activacion':

            $usuario = new Usuario();

            $usuario->setId_usuario($_POST['id_usuario']);

            $usuario->activar();

            echo json_encode([
                'success' => true,
                'mensaje' => 'Usuario activado correctamente'
            ]);

            exit();

            break;

        /* ACTUALIZAR */
        /* ACTUALIZAR */

        case 'actualizacion':

            /*
    =============================================
    VALIDAR CAMPOS
    =============================================
    */

            if (
                empty($_POST['id_usuario']) ||
                empty($_POST['id_persona']) ||
                empty($_POST['nombre_usuario']) ||
                empty($_POST['email']) ||
                empty($_POST['nombre']) ||
                empty($_POST['apellido'])
            ) {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'Todos los campos son obligatorios.'
                ]);

                exit();
            }


            /*
    =============================================
    ACTUALIZAR USUARIO
    =============================================
    */

            $usuario = new Usuario();

            $usuario->setId_usuario(
                $_POST['id_usuario']
            );

            $usuario->setNombre_usuario(
                $_POST['nombre_usuario']
            );

            $usuario->setEmail(
                $_POST['email']
            );


            $resultadoUsuario =
                $usuario->actualizarDatosUsuario();


            /*
    =============================================
    ACTUALIZAR PERSONA
    =============================================
    */

            $persona = new Persona();

            $persona->setId_persona(
                $_POST['id_persona']
            );

            $persona->setNombre(
                $_POST['nombre']
            );

            $persona->setApellido(
                $_POST['apellido']
            );


            $resultadoPersona = $persona->actualizarNombreApellido();


            /*
    =============================================
    RESPUESTA AJAX
    =============================================
    */

            if ($resultadoUsuario && $resultadoPersona) {

                echo json_encode([
                    'success' => true,
                    'mensaje' => 'Usuario actualizado correctamente'
                ]);
            } else {

                echo json_encode([
                    'success' => false,
                    'mensaje' => 'No se pudo actualizar el usuario.'
                ]);
            }

            exit();

            break;
    }
}
