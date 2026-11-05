<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| MODELOS
|--------------------------------------------------------------------------
*/

require_once "modelos/paciente.php";
require_once "modelos/agenda.php";
require_once "modelos/obra_social.php";
require_once "modelos/paciente_obra_social.php";
require_once "modelos/doctor_obra_social.php";
require_once "modelos/doctor_Dias.php";
require_once "modelos/franja_horaria.php";


/*
|--------------------------------------------------------------------------
| SESIÓN
|--------------------------------------------------------------------------
*/

$idUsuario = intval($_SESSION['id_usuario'] ?? 0);

$perfil = strtolower(trim($_SESSION['nombre_perfil'] ?? ''));

$esDoctor = ($perfil === 'doctor');
$esPaciente = ($perfil === 'paciente');
$esAdministrador = ($perfil === 'administrador');


/*
|--------------------------------------------------------------------------
| FUNCIONES AUXILIARES
|--------------------------------------------------------------------------
|
| Como la consulta puede devolver nombres de campos ligeramente
| diferentes según el JOIN del modelo, buscamos varias alternativas.
|
*/

function obtenerValor(array $datos, array $claves, $default = '')
{
    foreach ($claves as $clave) {

        if (
            array_key_exists($clave, $datos) &&
            $datos[$clave] !== null &&
            $datos[$clave] !== ''
        ) {
            return $datos[$clave];
        }
    }

    return $default;
}


/*
|--------------------------------------------------------------------------
| DATOS DEL USUARIO / PACIENTE
|--------------------------------------------------------------------------
*/

$datosPaciente = [];

if ($idUsuario > 0) {

    $pacienteModel = new Paciente();

    $datosPaciente = $pacienteModel->obtenerPorUsuario($idUsuario);

    if (!is_array($datosPaciente)) {
        $datosPaciente = [];
    }
}


/*
|--------------------------------------------------------------------------
| DATOS PERSONALES
|--------------------------------------------------------------------------
*/

$usuario = [

    'nombre' => obtenerValor(
        $datosPaciente,
        [
            'nombre',
            'nombre_persona',
            'nombre_usuario'
        ],
        $_SESSION['nombre_usuario'] ?? ''
    ),

    'apellido' => obtenerValor(
        $datosPaciente,
        [
            'apellido',
            'apellido_persona',
            'apellidos'
        ],
        ''
    ),

    'dni' => obtenerValor(
        $datosPaciente,
        [
            'dni',
            'documento',
            'numero_documento'
        ],
        ''
    ),

    'fecha_nacimiento' => obtenerValor(
        $datosPaciente,
        [
            'fecha_nacimiento',
            'fechaNacimiento',
            'nacimiento'
        ],
        ''
    ),

    'telefono' => obtenerValor(
        $datosPaciente,
        [
            'telefono',
            'telefono_persona',
            'celular',
            'telefono_usuario'
        ],
        ''
    ),

    'email' => obtenerValor(
        $datosPaciente,
        [
            'email',
            'correo',
            'correo_electronico'
        ],
        $_SESSION['email'] ?? ''
    ),

    'direccion' => obtenerValor(
        $datosPaciente,
        [
            'direccion',
            'domicilio'
        ],
        ''
    ),

    'localidad' => obtenerValor(
        $datosPaciente,
        [
            'localidad',
            'ciudad'
        ],
        ''
    ),

    'foto' => obtenerValor(
        $datosPaciente,
        [
            'foto',
            'imagen',
            'foto_perfil',
            'avatar'
        ],
        'assets/images/logo/captura_de_pantalla_2.png'
    )
];


/*
|--------------------------------------------------------------------------
| ID DEL PACIENTE
|--------------------------------------------------------------------------
*/

$idPaciente = intval(
    obtenerValor(
        $datosPaciente,
        ['id_paciente'],
        0
    )
);


/*
|--------------------------------------------------------------------------
| PROFESIONALES
|--------------------------------------------------------------------------
|
| Esta información NO debe estar hardcodeada.
| Se obtiene desde Agenda::obtenerDoctores(), igual que en
| miperfil_paciente.php.
|
*/

$agendaModel = new Agenda();

$doctores = $agendaModel->obtenerDoctores();

if (!is_array($doctores)) {
    $doctores = [];
}


/*
|--------------------------------------------------------------------------
| OBRAS SOCIALES
|--------------------------------------------------------------------------
*/

$obraSocial = new Obra_Social();

$todasObras = $obraSocial->consultarVariasObrasSociales();

if (!is_array($todasObras)) {
    $todasObras = [];
}


$obrasDelUsuario = [];


if ($esPaciente && $idPaciente > 0) {

    $po = new Paciente_Obra_Social();

    $obrasDelUsuario = $po->consultarPorPaciente($idUsuario);

    if (!is_array($obrasDelUsuario)) {
        $obrasDelUsuario = [];
    }
}


/*
|--------------------------------------------------------------------------
| DATOS DEL DOCTOR
|--------------------------------------------------------------------------
|
| Solamente se cargan si el usuario conectado es doctor.
|
*/

$doctor = [];

if ($esDoctor) {

    require_once "modelos/doctor.php";

    $doctorModel = new Doctor();

    /*
     * Intentamos obtener el doctor asociado al usuario.
     *
     * El modelo Doctor actual utiliza el usuario_id_usuario
     * para relacionarlo con la cuenta.
     */

    $doctoresUsuario = $doctorModel->todos_docs();

    if (is_array($doctoresUsuario)) {

        foreach ($doctoresUsuario as $doc) {

            $idUsuarioDoctor = intval(
                $doc['usuario_id_usuario'] ?? 0
            );

            if ($idUsuarioDoctor === $idUsuario) {

                $doctor = $doc;

                break;
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| DÍAS DEL DOCTOR
|--------------------------------------------------------------------------
*/

$diasAsignados = [];

$diasConFranjas = [];


if ($esDoctor) {

    $dd = new Doctor_Dias();

    $idDoctor = $dd->obtenerIdDoctorPorUsuario($idUsuario);

    if ($idDoctor !== null) {

        $diasAsignados = $dd->consultarDiasPorDoctor($idDoctor);

        if (!is_array($diasAsignados)) {
            $diasAsignados = [];
        }
    }
}


/*
|--------------------------------------------------------------------------
| DÍAS DE LA SEMANA
|--------------------------------------------------------------------------
*/

$diasSemana = [
    'lunes',
    'martes',
    'miercoles',
    'jueves',
    'viernes'
];


/*
|--------------------------------------------------------------------------
| FRANJAS HORARIAS
|--------------------------------------------------------------------------
*/

$franjaModel = new Franja();

$todasFranjas = $franjaModel->consultarVariasFranjas();

if (!is_array($todasFranjas)) {
    $todasFranjas = [];
}


$mapFranjas = [];


foreach ($todasFranjas as $f) {

    $mapFranjas[$f['id_franja']] =
        ($f['tipo_franja'] ?? '') .
        ' (' .
        ($f['inicio_franja'] ?? '') .
        ' - ' .
        ($f['fin_franja'] ?? '') .
        ')';
}
?>

<div class="container-fluid py-4 px-4">
    <!-- ENCABEZADO -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-person-circle fs-3 text-primary"></i>

                <h2 class="fw-bold mb-0">
                    Mis Datos
                </h2>
            </div>

            <p class="text-muted mb-0">
                Administrá tu información personal, datos de contacto y configuración de tu cuenta.
            </p>
        </div>

    </div>


    <!-- ALERTAS -->
    <?php if (isset($_GET['success']) && $_GET['success'] === 'obras_actualizadas'): ?>

        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3">
            <i class="bi bi-check-circle me-2"></i>
            Obras sociales actualizadas correctamente.

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>

    <?php elseif (isset($_GET['success']) && $_GET['success'] === 'dias_actualizados'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3">
            <i class="bi bi-check-circle me-2"></i>
            Días laborales actualizados correctamente.

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>

    <?php endif; ?>


    <!-- PERFIL + CUENTA -->
    <div class="row g-4 mb-4">

        <!-- PERFIL -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">

                        <!-- FOTO -->
                        <div class="text-center">

                            <div class="position-relative">

                                <img
                                    src="<?= htmlspecialchars($usuario['foto']) ?>"
                                    alt="Foto de perfil"
                                    class="rounded-circle shadow-sm"
                                    style="width:120px;height:120px;object-fit:cover;"
                                >

                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0"
                                    title="Cambiar foto"
                                >
                                    <i class="bi bi-camera"></i>
                                </button>

                            </div>

                        </div>


                        <!-- INFORMACIÓN PRINCIPAL -->
                        <div class="flex-grow-1 text-center text-md-start">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-2">
                                <i class="bi bi-person-badge me-1"></i>

                                <?php
                                if ($esDoctor) {
                                    echo 'Doctor';
                                } elseif ($esPaciente) {
                                    echo 'Paciente';
                                } else {
                                    echo 'Administrador';
                                }
                                ?>
                            </span>

                            <?php if ($esDoctor): ?>

                                <h4 class="fw-bold mb-1">
                                    <?= htmlspecialchars($doctor['nombre']) ?>
                                </h4>

                                <p class="text-primary fw-semibold mb-1">
                                    <?= htmlspecialchars($doctor['especialidad']) ?>
                                </p>

                                <p class="text-muted mb-0">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <?= htmlspecialchars($doctor['ubicacion']) ?>
                                </p>

                            <?php else: ?>

                                <h4 class="fw-bold mb-1">
                                    <?= htmlspecialchars(
                                        trim($usuario['nombre'] . ' ' . $usuario['apellido'])
                                    ) ?>
                                </h4>

                                <p class="text-muted mb-1">
                                    <?= ucfirst($perfil) ?>
                                </p>

                                <p class="text-muted mb-0">
                                    <i class="bi bi-envelope me-1"></i>
                                    <?= htmlspecialchars($usuario['email']) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- CUENTA -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body p-4">

                    <div class="d-flex align-items-center gap-2 mb-3">

                        <div class="bg-primary-subtle text-primary rounded-3 p-2">
                            <i class="bi bi-shield-lock fs-5"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-0">
                                Cuenta
                            </h5>

                            <small class="text-muted">
                                Información de acceso
                            </small>
                        </div>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Usuario
                        </small>

                        <span class="fw-semibold">
                            <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario') ?>
                        </span>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Correo electrónico
                        </small>

                        <span class="fw-semibold">
                            <?= htmlspecialchars($usuario['email']) ?>
                        </span>

                    </div>

                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Perfil
                        </small>

                        <span class="badge bg-light text-dark border rounded-pill">
                            <?= ucfirst($perfil) ?>
                        </span>

                    </div>

                    <button
                        type="button"
                        class="btn btn-outline-primary w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#modalContrasena"
                    >
                        <i class="bi bi-key me-1"></i>
                        Cambiar contraseña
                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- INFORMACIÓN PERSONAL -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-person-vcard text-primary me-2"></i>
                        Información personal
                    </h5>

                    <p class="text-muted small mb-0">
                        Actualizá tus datos personales y de contacto.
                    </p>

                </div>

            </div>


            <!-- IMPORTANTE:
                El action se conectará posteriormente con el controlador
                correspondiente cuando tengamos definido el backend.-->

            <form method="post" action="#">
                <input
                    type="hidden"
                    name="id_usuario"
                    value="<?= htmlspecialchars($_SESSION['id_usuario']) ?>"
                >

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Nombre
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="nombre"
                            value="<?= htmlspecialchars($usuario['nombre']) ?>"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Apellido
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="apellido"
                            value="<?= htmlspecialchars($usuario['apellido']) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            DNI
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="dni"
                            value="<?= htmlspecialchars($usuario['dni']) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Fecha de nacimiento
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            name="fecha_nacimiento"
                            value="<?= htmlspecialchars($usuario['fecha_nacimiento']) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="telefono"
                            value="<?= htmlspecialchars($usuario['telefono']) ?>"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            name="email"
                            value="<?= htmlspecialchars($usuario['email']) ?>"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Dirección
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="direccion"
                            value="<?= htmlspecialchars($usuario['direccion']) ?>"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Localidad
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="localidad"
                            value="<?= htmlspecialchars($usuario['localidad']) ?>"
                        >

                    </div>

                </div>

                <div class="d-flex justify-content-end mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- =========================================================
         INFORMACIÓN ESPECÍFICA DEL PACIENTE
    ========================================================== -->

    <?php if ($esPaciente): ?>

        <div class="row g-4">

            <!-- COBERTURA -->

            <div class="col-lg-7">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-2 mb-4">

                            <div class="bg-primary-subtle text-primary rounded-3 p-2">
                                <i class="bi bi-heart-pulse fs-5"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Cobertura médica
                                </h5>

                                <small class="text-muted">
                                    Obras sociales asociadas
                                </small>
                            </div>

                        </div>


                        <?php if (!empty($obrasDelUsuario)): ?>

                            <div class="mb-4">

                                <?php foreach ($obrasDelUsuario as $obra): ?>

                                    <div class="border rounded-3 p-3 mb-2">

                                        <div class="d-flex align-items-center">

                                            <i class="bi bi-hospital text-primary fs-4 me-3"></i>

                                            <div>

                                                <div class="fw-semibold">
                                                    <?= htmlspecialchars($obra['nombre_obra_social']) ?>
                                                </div>

                                                <small class="text-muted">
                                                    <?= htmlspecialchars($obra['detalle']) ?>
                                                </small>

                                            </div>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php else: ?>

                            <div class="text-center py-4">

                                <i class="bi bi-heart-pulse fs-1 text-muted"></i>

                                <p class="text-muted mt-2 mb-0">
                                    No tenés obras sociales asociadas.
                                </p>

                            </div>

                        <?php endif; ?>


                        <hr class="my-4">


                        <form
                            method="post"
                            action="controladores/obra_social_usuario_controlador.php"
                        >

                            <input
                                type="hidden"
                                name="perfil"
                                value="paciente"
                            >

                            <input
                                type="hidden"
                                name="id_usuario"
                                value="<?= htmlspecialchars($_SESSION['id_usuario']) ?>"
                            >

                            <label class="form-label fw-semibold">
                                Actualizar obras sociales
                            </label>

                            <small class="text-muted d-block mb-3">
                                Seleccioná las obras sociales que utilizás actualmente.
                            </small>

                            <?php foreach ($todasObras as $obra): ?>

                                <?php

                                $checked = false;

                                foreach ($obrasDelUsuario as $asignada) {

                                    if (
                                        $asignada['id_obra_social']
                                        == $obra['id_obra_social']
                                    ) {
                                        $checked = true;
                                        break;
                                    }

                                }

                                ?>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="obras[]"
                                        value="<?= $obra['id_obra_social'] ?>"
                                        id="obra<?= $obra['id_obra_social'] ?>"
                                        <?= $checked ? 'checked' : '' ?>
                                    >

                                    <label
                                        class="form-check-label"
                                        for="obra<?= $obra['id_obra_social'] ?>"
                                    >
                                        <?= htmlspecialchars($obra['nombre_obra_social']) ?>

                                        <?php if (!empty($obra['detalle'])): ?>

                                            <span class="text-muted">
                                                - <?= htmlspecialchars($obra['detalle']) ?>
                                            </span>

                                        <?php endif; ?>

                                    </label>

                                </div>

                            <?php endforeach; ?>


                            <button
                                type="submit"
                                class="btn btn-primary mt-3"
                            >
                                <i class="bi bi-check-lg me-1"></i>
                                Guardar cobertura
                            </button>

                        </form>

                    </div>

                </div>

            </div>


            <!-- CONTACTO DE EMERGENCIA -->

            <div class="col-lg-5">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-2 mb-4">

                            <div class="bg-primary-subtle text-primary rounded-3 p-2">
                                <i class="bi bi-person-plus fs-5"></i>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Contacto de emergencia
                                </h5>

                                <small class="text-muted">
                                    Persona de contacto
                                </small>
                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Nombre completo
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Ej. María González"
                                >

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Relación
                                </label>

                                <select class="form-select">

                                    <option value="">
                                        Seleccionar
                                    </option>

                                    <option>Madre</option>
                                    <option>Padre</option>
                                    <option>Pareja</option>
                                    <option>Hijo/a</option>
                                    <option>Hermano/a</option>
                                    <option>Otro</option>

                                </select>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Teléfono
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Ej. 3704..."
                                >

                            </div>

                        </div>


                        <div class="d-flex justify-content-end mt-4">

                            <button
                                type="button"
                                class="btn btn-outline-primary"
                            >
                                <i class="bi bi-check-lg me-1"></i>
                                Guardar contacto
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- INFORMACIÓN ADICIONAL -->

        <div class="card border-0 shadow-sm rounded-4 mt-4">

            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-2 mb-4">

                    <div class="bg-primary-subtle text-primary rounded-3 p-2">
                        <i class="bi bi-clipboard2-pulse fs-5"></i>
                    </div>

                    <div>

                        <h5 class="fw-bold mb-1">
                            Información adicional
                        </h5>

                        <small class="text-muted">
                            Información que puede ayudar durante la atención.
                        </small>

                    </div>

                </div>


                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Grupo sanguíneo
                        </label>

                        <select class="form-select">

                            <option value="">
                                Seleccionar
                            </option>

                            <option>A+</option>
                            <option>A-</option>
                            <option>B+</option>
                            <option>B-</option>
                            <option>AB+</option>
                            <option>AB-</option>
                            <option>O+</option>
                            <option>O-</option>

                        </select>

                    </div>

                    <div class="col-md-8">

                        <label class="form-label fw-semibold">
                            Alergias declaradas
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            placeholder="Ej. Penicilina, frutos secos..."
                        >

                    </div>

                </div>


                <div class="alert alert-info border-0 rounded-3 mt-4 mb-0">

                    <i class="bi bi-info-circle me-2"></i>

                    Esta información es complementaria y no reemplaza
                    la historia clínica ni el diagnóstico realizado por
                    un profesional.

                </div>

            </div>

        </div>


    <?php endif; ?>


    <!-- =========================================================
         INFORMACIÓN ESPECÍFICA DEL ADMINISTRADOR
    ========================================================== -->

    <?php if ($esAdministrador): ?>

        <div class="row g-4">

            <!-- ESTADO DE CUENTA -->

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-2 mb-4">

                            <div class="bg-primary-subtle text-primary rounded-3 p-2">
                                <i class="bi bi-shield-check fs-5"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Seguridad de la cuenta
                                </h5>

                                <small class="text-muted">
                                    Estado y configuración de acceso.
                                </small>

                            </div>

                        </div>


                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                            <span class="text-muted">
                                Estado
                            </span>

                            <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                Activo
                            </span>

                        </div>


                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                            <span class="text-muted">
                                Perfil
                            </span>

                            <span class="fw-semibold">
                                Administrador
                            </span>

                        </div>


                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                            <span class="text-muted">
                                Último acceso
                            </span>

                            <span class="fw-semibold">
                                No disponible
                            </span>

                        </div>


                        <div class="d-flex justify-content-between align-items-center py-3">

                            <span class="text-muted">
                                Verificación de cuenta
                            </span>

                            <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                Verificada
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ACTIVIDAD -->

            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-2 mb-4">

                            <div class="bg-primary-subtle text-primary rounded-3 p-2">
                                <i class="bi bi-clock-history fs-5"></i>
                            </div>

                            <div>

                                <h5 class="fw-bold mb-1">
                                    Actividad de la cuenta
                                </h5>

                                <small class="text-muted">
                                    Información de seguridad.
                                </small>

                            </div>

                        </div>


                        <div class="p-3 bg-light rounded-3 mb-3">

                            <div class="d-flex align-items-center gap-3">

                                <i class="bi bi-box-arrow-in-right text-primary fs-4"></i>

                                <div>

                                    <div class="fw-semibold">
                                        Último inicio de sesión
                                    </div>

                                    <small class="text-muted">
                                        Información pendiente de integrar.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div class="p-3 bg-light rounded-3">

                            <div class="d-flex align-items-center gap-3">

                                <i class="bi bi-person-check text-primary fs-4"></i>

                                <div>

                                    <div class="fw-semibold">
                                        Cuenta activa
                                    </div>

                                    <small class="text-muted">
                                        Tu cuenta tiene acceso al sistema.
                                    </small>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="alert alert-warning border-0 rounded-4 mt-4">

            <i class="bi bi-exclamation-triangle me-2"></i>

            <strong>Información administrativa:</strong>
            los datos relacionados con el perfil, permisos y nivel de acceso
            deben administrarse desde la sección de Seguridad y no desde
            esta página.

        </div>

    <?php endif; ?>


    <!-- =========================================================
         INFORMACIÓN ESPECÍFICA DEL DOCTOR
    ========================================================== -->

    <?php if ($esDoctor): ?>

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-person-workspace text-primary me-2"></i>
                    Información profesional
                </h5>

                <p class="text-muted small mb-4">
                    Información que se mostrará a los pacientes al consultar tu perfil profesional.
                </p>


                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Especialidad
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($doctor['especialidad']) ?>"
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Subespecialidad
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($doctor['subespecialidad']) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Matrícula profesional
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($doctor['matricula']) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Experiencia
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($doctor['experiencia']) ?>"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Consultorio
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($doctor['direccion']) ?>"
                        >

                    </div>

                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Descripción profesional
                        </label>

                        <textarea
                            class="form-control"
                            rows="4"
                        ><?= htmlspecialchars($doctor['biografia']) ?></textarea>

                    </div>

                </div>


                <div class="d-flex justify-content-end mt-4">

                    <button
                        type="button"
                        class="btn btn-primary px-4"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Guardar información
                    </button>

                </div>

            </div>

        </div>


        <!-- =====================================================
             TABS DEL DOCTOR
        ====================================================== -->

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <ul class="nav nav-tabs border-bottom mb-4"
                    id="doctorTabs"
                    role="tablist">

                    <li class="nav-item">
                        <button
                            class="nav-link active"
                            data-bs-toggle="tab"
                            data-bs-target="#doctorObras"
                            type="button"
                        >
                            <i class="bi bi-hospital me-1"></i>
                            Obras sociales
                        </button>
                    </li>

                    <li class="nav-item">
                        <button
                            class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#doctorDias"
                            type="button"
                        >
                            <i class="bi bi-calendar-week me-1"></i>
                            Días laborales
                        </button>
                    </li>

                    <li class="nav-item">
                        <button
                            class="nav-link"
                            data-bs-toggle="tab"
                            data-bs-target="#doctorServicios"
                            type="button"
                        >
                            <i class="bi bi-cash-coin me-1"></i>
                            Servicios y precios
                        </button>
                    </li>

                </ul>


                <div class="tab-content">

                    <!-- OBRAS -->

                    <div
                        class="tab-pane fade show active"
                        id="doctorObras"
                    >

                        <h6 class="fw-bold mb-3">
                            Obras sociales aceptadas
                        </h6>

                        <?php if (!empty($obrasDelUsuario)): ?>

                            <div class="row g-2 mb-4">

                                <?php foreach ($obrasDelUsuario as $obra): ?>

                                    <div class="col-md-6">

                                        <div class="border rounded-3 p-3">

                                            <i class="bi bi-hospital text-primary me-2"></i>

                                            <strong>
                                                <?= htmlspecialchars($obra['nombre_obra_social']) ?>
                                            </strong>

                                            <small class="text-muted d-block ms-4">
                                                <?= htmlspecialchars($obra['detalle']) ?>
                                            </small>

                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php else: ?>

                            <p class="text-muted">
                                No tenés obras sociales asignadas.
                            </p>

                        <?php endif; ?>


                        <form
                            method="post"
                            action="controladores/obra_social_usuario_controlador.php"
                        >

                            <input
                                type="hidden"
                                name="perfil"
                                value="doctor"
                            >

                            <input
                                type="hidden"
                                name="id_usuario"
                                value="<?= htmlspecialchars($_SESSION['id_usuario']) ?>"
                            >

                            <label class="form-label fw-semibold">
                                Actualizar obras sociales
                            </label>

                            <?php foreach ($todasObras as $obra): ?>

                                <?php

                                $checked = false;

                                foreach ($obrasDelUsuario as $asignada) {

                                    if (
                                        $asignada['id_obra_social']
                                        == $obra['id_obra_social']
                                    ) {
                                        $checked = true;
                                        break;
                                    }

                                }

                                ?>

                                <div class="form-check mb-2">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="obras[]"
                                        value="<?= $obra['id_obra_social'] ?>"
                                        id="doctorObra<?= $obra['id_obra_social'] ?>"
                                        <?= $checked ? 'checked' : '' ?>
                                    >

                                    <label
                                        class="form-check-label"
                                        for="doctorObra<?= $obra['id_obra_social'] ?>"
                                    >
                                        <?= htmlspecialchars($obra['nombre_obra_social']) ?>
                                    </label>

                                </div>

                            <?php endforeach; ?>


                            <button
                                type="submit"
                                class="btn btn-primary mt-3"
                            >
                                <i class="bi bi-check-lg me-1"></i>
                                Guardar obras sociales
                            </button>

                        </form>

                    </div>


                    <!-- DÍAS -->

                    <div
                        class="tab-pane fade"
                        id="doctorDias"
                    >

                        <h6 class="fw-bold mb-1">
                            Días laborales
                        </h6>

                        <p class="text-muted small mb-4">
                            Configurá los días y franjas horarias en los que atendés.
                        </p>


                        <form
                            method="post"
                            action="controladores/dias_laborales_controlador.php"
                        >

                            <input
                                type="hidden"
                                name="id_usuario"
                                value="<?= htmlspecialchars($_SESSION['id_usuario']) ?>"
                            >


                            <?php foreach ($diasSemana as $dia): ?>

                                <?php

                                $checked = in_array($dia, $diasAsignados);

                                $selectedFranja = $diasConFranjas[$dia] ?? '';

                                ?>

                                <div class="border rounded-3 p-3 mb-2">

                                    <div class="row align-items-center g-3">

                                        <div class="col-md-4">

                                            <div class="form-check">

                                                <input
                                                    class="form-check-input dia-checkbox"
                                                    type="checkbox"
                                                    name="dias[]"
                                                    value="<?= $dia ?>"
                                                    id="dia_<?= $dia ?>"
                                                    <?= $checked ? 'checked' : '' ?>
                                                >

                                                <label
                                                    class="form-check-label fw-semibold"
                                                    for="dia_<?= $dia ?>"
                                                >
                                                    <?= ucfirst($dia) ?>
                                                </label>

                                            </div>

                                        </div>


                                        <div class="col-md-8">

                                            <select
                                                name="franjas[<?= $dia ?>]"
                                                id="franja_<?= $dia ?>"
                                                class="form-select"
                                                <?= $checked ? '' : 'disabled' ?>
                                            >

                                                <option value="">
                                                    Seleccioná franja horaria
                                                </option>

                                                <?php foreach ($todasFranjas as $fr): ?>

                                                    <option
                                                        value="<?= $fr['id_franja'] ?>"
                                                        <?= ($selectedFranja == $fr['id_franja']) ? 'selected' : '' ?>
                                                    >
                                                        <?= htmlspecialchars($fr['tipo_franja']) ?>
                                                        (
                                                        <?= htmlspecialchars($fr['inicio_franja']) ?>
                                                        -
                                                        <?= htmlspecialchars($fr['fin_franja']) ?>
                                                        )
                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>


                            <div class="d-flex justify-content-end mt-4">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-check-lg me-1"></i>
                                    Guardar días laborales
                                </button>

                            </div>

                        </form>

                    </div>


                    <!-- SERVICIOS -->

                    <div
                        class="tab-pane fade"
                        id="doctorServicios"
                    >

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="border rounded-3 p-4">

                                    <div class="d-flex align-items-center gap-3 mb-3">

                                        <div class="bg-primary-subtle text-primary rounded-3 p-2">
                                            <i class="bi bi-cash-coin fs-5"></i>
                                        </div>

                                        <div>

                                            <h6 class="fw-bold mb-0">
                                                Consulta particular
                                            </h6>

                                            <small class="text-muted">
                                                Precio de atención
                                            </small>

                                        </div>

                                    </div>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            $
                                        </span>

                                        <input
                                            type="number"
                                            class="form-control"
                                            value="5000"
                                        >

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="border rounded-3 p-4">

                                    <div class="d-flex align-items-center gap-3 mb-3">

                                        <div class="bg-primary-subtle text-primary rounded-3 p-2">
                                            <i class="bi bi-heart-pulse fs-5"></i>
                                        </div>

                                        <div>

                                            <h6 class="fw-bold mb-0">
                                                Atención con obra social
                                            </h6>

                                            <small class="text-muted">
                                                Modalidad disponible
                                            </small>

                                        </div>

                                    </div>

                                    <div class="form-check form-switch">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            checked
                                        >

                                        <label class="form-check-label">
                                            Ofrecer atención con obra social
                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="d-flex justify-content-end mt-4">

                            <button
                                type="button"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-check-lg me-1"></i>
                                Guardar servicios
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>


<!-- =============================================================
     MODAL CAMBIAR CONTRASEÑA
============================================================== -->

<div
    class="modal fade"
    id="modalContrasena"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4">

            <div class="modal-header border-0 px-4 pt-4">

                <div>

                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-key text-primary me-2"></i>
                        Cambiar contraseña
                    </h5>

                    <small class="text-muted">
                        Actualizá la contraseña de acceso a tu cuenta.
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form method="post" action="#">

                <div class="modal-body px-4">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Contraseña actual
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Nueva contraseña
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            required
                        >

                    </div>

                    <div>

                        <label class="form-label fw-semibold">
                            Confirmar nueva contraseña
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            required
                        >

                    </div>

                </div>


                <div class="modal-footer border-0 px-4 pb-4">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Actualizar contraseña
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | HABILITAR / DESHABILITAR FRANJA SEGÚN DÍA
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.dia-checkbox').forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            const dia = this.id.replace('dia_', '');

            const select = document.getElementById('franja_' + dia);

            if (select) {

                select.disabled = !this.checked;

                if (!this.checked) {
                    select.value = '';
                }

            }

        });

    });

});

</script>