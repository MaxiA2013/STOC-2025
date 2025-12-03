<?php
require_once "../modelos/doctor.php";
require_once "../modelos/persona.php";
require_once "../modelos/usuarios.php";
require_once "../modelos/conexion.php";

header('Content-Type: application/json');

/* ============================================================
   OBJETO CONEXIÓN
   ============================================================ */
$db = new Conexion();

/* ============================================================
   FUNCIÓN SEGURO PARA LIMPIAR INPUTS (USANDO getConexion())
   ============================================================ */
function limpiar($valor) {
    if ($valor === null) return '';

    $temp = new Conexion();
    $con = $temp->getConexion();   // abre conexión
    $safe = $con->real_escape_string($valor);
    $temp->desconectar();          // cierra conexión
    return $safe;
}

$action = $_POST['action'] ?? '';

switch ($action) {


/* ============================================================
   1) REGISTRAR DOCTOR (usuario ya existe)
   ============================================================ */
case "registrarDoctor":

    $numero_matricula_profesional = limpiar($_POST['numero_matricula_profesional'] ?? '');
    $precio_consulta              = limpiar($_POST['precio_consulta'] ?? '');
    $id_usuario                   = limpiar($_POST['usuario_id_usuario'] ?? '');

    if ($numero_matricula_profesional === '' || $precio_consulta === '' || $id_usuario === '') {
        echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
        exit;
    }

    $doctor = new Doctor('', $numero_matricula_profesional, $id_usuario, $precio_consulta);
    $id = $doctor->guardar();

    echo json_encode(
        $id
        ? ["status" => "ok", "message" => "Doctor registrado correctamente", "id" => $id]
        : ["status" => "error", "message" => "No se pudo registrar el doctor"]
    );
    break;





/* ============================================================
   2) REGISTRO COMPLETO: PERSONA + USUARIO + PERFIL DOCTOR + DOCTOR
   ============================================================ */
case "registrarCompleto":

    // PERSONA
    $nombre      = limpiar($_POST['nombre'] ?? '');
    $apellido    = limpiar($_POST['apellido'] ?? '');
    $sexo        = limpiar($_POST['sexo'] ?? '');
    $fecha_nac   = limpiar($_POST['fecha_nacimiento'] ?? '');

    // USUARIO
    $usuario     = limpiar($_POST['nombre_usuario'] ?? '');
    $email       = limpiar($_POST['email'] ?? '');
    $password    = $_POST['password'] ?? '';  // no se escapa aquí

    // DOCTOR
    $numero_matricula_profesional = limpiar($_POST['numero_matricula_profesional'] ?? '');
    $precio_consulta              = limpiar($_POST['precio_consulta'] ?? '');

    // VALIDACIÓN GENERAL
    if ($nombre==='' || $apellido==='' || $usuario==='' || $email==='' || 
        $password==='' || $numero_matricula_profesional==='' || $precio_consulta==='') {

        echo json_encode(["status" => "error", "message" => "Faltan datos obligatorios"]);
        exit;
    }

    // 1) Guardar persona
    $p = new Persona('', $nombre, $apellido, $sexo, $fecha_nac);
    $id_persona = $p->guardar();
    if (!$id_persona) {
        echo json_encode(["status" => "error", "message" => "Error al guardar persona"]);
        exit;
    }

    // 2) Guardar usuario
    $u = new Usuario('', $usuario, $email, $password, $id_persona);
    $id_usuario = $u->guardarUsuario();
    if (!$id_usuario) {
        echo json_encode(["status" => "error", "message" => "Error al guardar usuario"]);
        exit;
    }

    // 3) Asociar perfil (2 = doctor)
    $sqlPerfil = "INSERT INTO usuario_has_perfil (usuario_id_usuario, perfil_id_perfil) 
                  VALUES ($id_usuario, 2)";
    $db->consultar($sqlPerfil);

    // 4) Guardar doctor
    $d = new Doctor('', $numero_matricula_profesional, $id_usuario, $precio_consulta);
    $id_doctor = $d->guardar();

    echo json_encode(
        $id_doctor
        ? ["status" => "ok", "message" => "Usuario, persona y doctor creados correctamente", "id_doctor" => $id_doctor]
        : ["status" => "error", "message" => "Error al guardar doctor"]
    );
    break;





/* ============================================================
   3) EDITAR DOCTOR
   ============================================================ */
case "editar":

    $id_doctor = limpiar($_POST['id_doctor'] ?? '');
    $numero_matricula_profesional = limpiar($_POST['numero_matricula_profesional'] ?? '');
    $precio_consulta = limpiar($_POST['precio_consulta'] ?? '');

    if ($id_doctor === '') {
        echo json_encode(["status" => "error", "message" => "ID inválido"]);
        exit;
    }

    $doctor = new Doctor();
    $doctor->setId_doctor($id_doctor);
    $doctor->setNumero_matricula_profesional($numero_matricula_profesional);
    $doctor->setPrecio_Consulta($precio_consulta);

    $ok = $doctor->actualizar();

    echo json_encode(
        $ok
        ? ["status" => "ok", "message" => "Doctor actualizado correctamente"]
        : ["status" => "error", "message" => "No se pudo actualizar el doctor"]
    );
    break;





/* ============================================================
   4) ELIMINAR DOCTOR
   ============================================================ */
case "eliminar":

    $id = limpiar($_POST['id_doctor'] ?? '');
    if ($id === '') {
        echo json_encode(["status" => "error", "message" => "ID no válido"]);
        exit;
    }

    $doctor = new Doctor();
    $doctor->eliminar($id);

    echo json_encode(["status" => "ok", "message" => "Doctor eliminado correctamente"]);
    break;





/* ============================================================
   ACCIÓN NO VÁLIDA
   ============================================================ */
default:
    echo json_encode(["status" => "error", "message" => "Acción no válida"]);
    break;
}

exit;
<<<<<<< HEAD
?>
=======
?>
>>>>>>> origin/mi-ramita
