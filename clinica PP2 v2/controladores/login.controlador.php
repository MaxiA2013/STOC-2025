<?php
require_once ('../modelos/usuarios.php');
require_once ('../modelos/persona.php');
require_once ('../modelos/perfil.php');
require_once ('../modelos/doctor.php');

if (isset($_POST['action'])) {
    $login_controlador = new LoginControlador();
    if ($_POST['action'] == 'login') {
        $login_controlador->ingresar();
    } elseif ($_POST['action'] == 'registro') {
        $login_controlador->registrar();
    }
}

class LoginControlador {

    public function ingresar() {
        if (empty($_POST['nombre_usuario']) || empty($_POST['password'])) {
            header('Location: ../index.php?message=Por favor, complete todos los campos&status=danger');
            exit();
        }

        $usuario = new Usuario();
        $usuario->setNombre_usuario($_POST['nombre_usuario']);
        $resultado = $usuario->validar_usuario();

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                if (password_verify($_POST['password'], $row['password'])) {
                    session_start();
                    $_SESSION['id_usuario'] = $row['id_usuario'];
                    $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
                    $_SESSION['email'] = $row['email'];

                    $perfil = new Perfil();
                    $resultado_perfil = $perfil->traer_perfil_por_usuario($row['id_usuario']);
                    if ($resultado_perfil->num_rows > 0) {
                        while ($row_perfil = $resultado_perfil->fetch_assoc()) {
                            $_SESSION['id_perfil'] = $row_perfil['id_perfil'];
                            $_SESSION['nombre_perfil'] = $row_perfil['nombre_perfil'];
                        }
                    }

                    if ($_SESSION['nombre_perfil'] == 'Administrador') {
                        header('Location: ../index.php?page=lista_usuario');
                    } elseif ($_SESSION['nombre_perfil'] == 'Doctor') {
                        header('Location: ../index.php?page=mi_perfil');
                    } elseif ($_SESSION['nombre_perfil'] == 'Paciente') {
                        header('Location: ../index.php?page=mi_perfil');
                    } else {
                        header('Location: ../index.php?message=Perfil no reconocido&status=warning');
                    }

                } else {
                    header('Location: ../index.php?message=Contraseña no coincide con la de la base de datos&status=danger');
                }
            }
        } else {
            echo 'nombre_usuario: ' . $_POST['nombre_usuario'] . '<br>';
            echo 'password: ' . $_POST['password'];
        }
    }

    public function registrar() {
        $persona = new Persona();
        $persona->setNombre($_POST['nombre']);
        $persona->setApellido($_POST['apellido']);
        $persona->setSexo($_POST['sexo']);
        $persona->setFecha_nacimiento($_POST['fecha_nacimiento']);

        $resultado = $persona->validar_persona();
        if ($resultado->num_rows > 0) {
            header('Location: ../index.php?message=La persona ya está registrada&status=danger');
        } else {
            $id_persona = $persona->guardar();
            if ($id_persona) {
                $usuario = new Usuario();
                $usuario->setNombre_usuario($_POST['nombre_usuario']);
                $usuario->setEmail($_POST['email']);
                $usuario->setPassword($_POST['password']);
                $usuario->setPersona_id_persona($id_persona);
                $id_usuario = $usuario->guardarUsuario();

                if ($id_usuario) {
                    $perfil_id = $_POST['perfil_id_perfil'];
                    $conn = new mysqli("localhost", "root", "", "clinica");
                    $sql = "INSERT INTO usuario_has_perfil (usuario_id_usuario, perfil_id_perfil) VALUES ($id_usuario, $perfil_id)";
                    $conn->query($sql);

                    // Si el perfil es Doctor (ID 2), insertar también en la tabla doctor
                    if ($perfil_id == 2) {
                        $numero_matricula_profesional = isset($_POST['numero_matricula_profesional']) ? $_POST['numero_matricula_profesional'] : '';
                        $salario = isset($_POST['salario']) ? floatval($_POST['salario']) : 0;

                        $doctor = new Doctor( $numero_matricula_profesional, 'Activo', $id_usuario, $salario);
                        $doctor-> setNumero_matricula_profesional($_POST['numero_matricula_profesional']);
                        $doctor->guardar();
                    }

                    // Si el perfil es Paciente (ID 3), insertar también en la tabla paciente
                    if ($perfil_id == 3) {
                        $conn->query("INSERT INTO paciente (usuario_id_usuario) VALUES ($id_usuario)");
                    }


                    header('Location: ../index.php?message=Usuario registrado correctamente&status=success');
                } else {
                    header('Location: ../index.php?message=Error al registrar usuario&status=danger');
                }
            } else {
                header('Location: ../index.php?message=Error al registrar persona&status=danger');
            }
        }
    }
}