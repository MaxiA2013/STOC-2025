<?php
require_once('../modelos/usuarios.php');
require_once('../modelos/persona.php');
require_once('../modelos/perfil.php');
require_once('../modelos/doctor.php');
require_once('../modelos/conexion.php');
require_once('../controladores/funciones_control.php');

if (isset($_POST['action'])) {
    $login_controlador = new LoginControlador();
    if ($_POST['action'] == 'login') {
        $login_controlador->ingresar();
    } elseif ($_POST['action'] == 'registro') {
        $login_controlador->registrar();
    }
}

class LoginControlador
{
    public function ingresar()
    {
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

                    if ($_SESSION['nombre_perfil'] == 'administrador') {
                        header('Location: ../index.php?page=mi_perfil');
                    } elseif ($_SESSION['nombre_perfil'] == 'doctor' || $_SESSION['nombre_perfil'] == 'paciente') {
                        header('Location: ../index.php?page=mi_perfil');
                    } else {
                        header('Location: ../index.php?message=Perfil no reconocido&status=warning');
                    }
                } else {
                    header('Location: ../index.php?message=Contraseña incorrecta&status=danger');
                }
            }
        } else {
            header('Location: ../index.php?message=Usuario no encontrado&status=danger');
            //aca hay que agregar mensaje sweetalert
        }
    }

    public function registrar()
    {
        // ------------------------------------------------------
        // VALIDACIONES PREVIAS A LA INSERCIÓN
        // ------------------------------------------------------

        // Validar que exista la fecha de nacimiento y calcular edad correctamente
        if (!isset($_POST['fecha_nacimiento']) || empty($_POST['fecha_nacimiento'])) {
            header('Location: ../index.php?message=La fecha de nacimiento es obligatoria&status=danger');
            exit();
        }

        try {
            $fecha_nac = new DateTime($_POST['fecha_nacimiento']);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nac)->y;
        } catch (Exception $e) {
            header('Location: ../index.php?message=Fecha de nacimiento inválida&status=danger');
            exit();
        }

        if ($edad < 18) {
            header('Location: ../index.php?message=La persona debe ser mayor de 18 años&status=danger');
            exit();
        }

        // Validar formato de nombre_usuario
        if (verificar_cadenas('/^[a-zA-Z0-9_]{3,16}$/', $_POST['nombre_usuario'])) {
            header('Location: ../index.php?message=El nombre de usuario debe tener entre 3 y 20 caracteres alfanuméricos&status=danger');
            exit();
        }

        // Validar que el nombre de usuario sea único
        $usuarioTemp = new Usuario();
        $usuarioTemp->setNombre_usuario($_POST['nombre_usuario']);
        $existeUsuario = $usuarioTemp->usuarioExiste();
        if ($existeUsuario->num_rows > 0) {
            header('Location: ../index.php?message=El nombre de usuario ya está en uso&status=danger');
            //aca hay que agregar mensaje sweetalert
            exit();
        }

        // Validar formato de correo
        if (!verificar_cadenas('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $_POST['email'])) {
            header('Location: ../index.php?message=El correo electrónico no tiene un formato válido&status=danger');
            exit();
        }

        // Validar correo único
        $usuarioTemp->setEmail($_POST['email']);
        $existeEmail = $usuarioTemp->buscar_cohincidencias();
        if ($existeEmail->num_rows > 0) {
            header('Location: ../index.php?message=El correo electrónico ya está registrado&status=danger');
            //aca hay que agregar mensaje sweetalert
            exit();
        }

        // ------------------------------------------------------
        // GUARDAR PERSONA
        // ------------------------------------------------------
        $persona = new Persona();
        $persona->setNombre($_POST['nombre']);
        $persona->setApellido($_POST['apellido']);
        $persona->setSexo($_POST['sexo']);
        $persona->setFecha_nacimiento($_POST['fecha_nacimiento']);

        $resultado = $persona->validar_persona();
        if ($resultado->num_rows > 0) {
            header('Location: ../index.php?message=La persona ya está registrada&status=danger');
            //aca hay que agregar mensaje sweetalert
            exit();
        }

        $id_persona = $persona->guardar();
        if (!$id_persona) {
            header('Location: ../index.php?message=Error al registrar persona&status=danger');
            //aca hay que agregar mensaje sweetalert
            exit();
        }

        // ------------------------------------------------------
        // GUARDAR USUARIO
        // ------------------------------------------------------
        $usuario = new Usuario();
        $usuario->setNombre_usuario($_POST['nombre_usuario']);
        $usuario->setEmail($_POST['email']);
        $usuario->setPassword($_POST['password']);
        $usuario->setPersona_id_persona($id_persona);

        $id_usuario = $usuario->guardarUsuario();
        if (!$id_usuario) {
            header('Location: ../index.php?message=Error al registrar usuario&status=danger');
            //aca hay que agregar mensaje sweetalert
            exit();
        }

        // ------------------------------------------------------
        // DETERMINAR PERFIL
        // ------------------------------------------------------
        $conn = new Conexion();
        $checkUsers = $conn->consultar("SELECT COUNT(*) AS total FROM usuario");
        $rowCount = $checkUsers->fetch_assoc()['total'];

        // Caso 1: Primer usuario → Administrador automáticamente (caso form multipasos publico)
        if ($rowCount == 1) {
            $perfil_id = 1; // Administrador
        } else {
            // Caso 2: Usuario registrado por admin desde panel
            if (isset($_POST['perfil_id_perfil']) && in_array($_POST['perfil_id_perfil'], ['1', '2', '3'])) {
                $perfil_id = $_POST['perfil_id_perfil'];
            } else {
                // Caso 3: Registro público = Paciente por defecto
                $perfil_id = 3;
            }
        }

        // ------------------------------------------------------
        // RELACIÓN USUARIO - PERFIL
        // ------------------------------------------------------
        $conn->insertar("INSERT INTO usuario_has_perfil (usuario_id_usuario, perfil_id_perfil) VALUES ($id_usuario, $perfil_id)");

        // ------------------------------------------------------
        // PERFIL ESPECÍFICO: DOCTOR O PACIENTE
        // ------------------------------------------------------
        if ($perfil_id == 2) {
            $numero_matricula_profesional = $_POST['numero_matricula_profesional'] ?? '';
            $salario = floatval($_POST['salario'] ?? 0);
            $doctor = new Doctor($numero_matricula_profesional, 'Activo', $id_usuario, $salario);
            $doctor->guardar();
        }

        if ($perfil_id == 3) {
            $conn->insertar("INSERT INTO paciente (usuario_id_usuario) VALUES ($id_usuario)");
        }

        header('Location: ../index.php?message=Usuario registrado correctamente&status=success');
        //aca hay que agregar mensaje sweetalert
    }
}
?>
