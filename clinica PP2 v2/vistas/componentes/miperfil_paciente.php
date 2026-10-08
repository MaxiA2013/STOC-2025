<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once __DIR__ . "/../../modelos/paciente.php";
require_once __DIR__ . "/../../modelos/agenda_turno.php";
require_once __DIR__ . "/../../modelos/agenda.php";
require_once __DIR__ . "/../../modelos/estados.php";

$turnoControllerPath = "controladores/turno/turno_controlador.php";

/* DATOS DEL PACIENTE */
$nombrePaciente = $_SESSION['nombre_usuario'] ?? 'Paciente';
$fechaActual = date('d/m/Y');
$idUsuario = intval($_SESSION['id_usuario'] ?? 0);

$pacienteModel = new Paciente();
$datosPaciente = $pacienteModel->obtenerPorUsuario($idUsuario);

if (!is_array($datosPaciente)) {
    $datosPaciente = [];
}

$idPaciente = intval($datosPaciente['id_paciente'] ?? 0);

$email = $datosPaciente['email'] ?? '';
$nombre = $datosPaciente['nombre'] ?? '';
$apellido = $datosPaciente['apellido'] ?? '';

/* TURNOS DEL PACIENTE */
$agendaTurnoModel = new AgendaTurno();

$turnosRaw = $idPaciente > 0
    ? $agendaTurnoModel->obtenerTurnosDetalladosPorPaciente($idPaciente)
    : [];

if (!is_array($turnosRaw)) {
    $turnosRaw = [];
}

/* DOCTORES */
$agendaModel = new Agenda();
$doctores = $agendaModel->obtenerDoctores();

if (!is_array($doctores)) {
    $doctores = [];
}

/* ESTADOS */
$estadoModel = new Estado();
$estados = $estadoModel->consultarVariosEstados();

if (!is_array($estados)) {
    $estados = [];
}

/* CLASIFICACIÓN DE TURNOS */
$zonaHoraria = new DateTimeZone('America/Argentina/Buenos_Aires');

$ahora = new DateTime('now', $zonaHoraria);

$proximosTurnos = [];
$historialTurnos = [];

foreach ($turnosRaw as $t) {
    if (empty($t['fecha_hora'])) {
        continue;
    }

    try {
        $fechaTurno = new DateTime(
            $t['fecha_hora'],
            $zonaHoraria
        );

        if ($fechaTurno >= $ahora) {
            $proximosTurnos[] = $t;
        } else {
            $historialTurnos[] = $t;
        }
    } catch (Exception $e) {
        continue;
    }
}

/* CONTADORES */
$turnosProximosCount = count($proximosTurnos);
$turnosHistorialCount = count($historialTurnos);

/* TURNOS DEL MES ACTUAL */
$inicioMes = new DateTime(
    date('Y-m-01') . ' 00:00:00',
    $zonaHoraria
);

$finMes = new DateTime(
    date('Y-m-t') . ' 23:59:59',
    $zonaHoraria
);

$turnosMesCount = 0;

foreach ($turnosRaw as $t) {
    if (empty($t['fecha_hora'])) {
        continue;
    }

    try {
        $fechaTurno = new DateTime(
            $t['fecha_hora'],
            $zonaHoraria
        );

        if (
            $fechaTurno >= $inicioMes &&
            $fechaTurno <= $finMes
        ) {
            $turnosMesCount++;
        }
    } catch (Exception $e) {
        continue;
    }
}

/* PRÓXIMO TURNO DESTACADO */
$proximoTurnoDestacado = null;

if (!empty($proximosTurnos)) {
    usort(
        $proximosTurnos,
        function ($a, $b) {
            return strtotime($a['fecha_hora']) <=> strtotime($b['fecha_hora']);
        }
    );
    $proximoTurnoDestacado = $proximosTurnos[0];
}

/* PORCENTAJE DE COMPLETITUD DE CUENTA Se calcula utilizando los datos que actualmente devuelve
| obtenerPorUsuario(). */

$camposPerfil = [
    'nombre',
    'apellido',
    'email',
    'telefono',
    'dni',
    'fecha_nacimiento',
    'sexo',
    'direccion'
];

$camposPerfilDisponibles = 0;
$camposPerfilCompletos = 0;

foreach ($camposPerfil as $campo) {

    if (array_key_exists($campo, $datosPaciente)) {

        $camposPerfilDisponibles++;

        if (
            isset($datosPaciente[$campo]) &&
            trim((string)$datosPaciente[$campo]) !== ''
        ) {
            $camposPerfilCompletos++;
        }
    }
}

if ($camposPerfilDisponibles > 0) {

    $porcentajePerfil = (int) round(
        ($camposPerfilCompletos / $camposPerfilDisponibles) * 100
    );
} else {

    /* Si todavía no conocemos todos los campos del modelo, calculamos el porcentaje utilizando los datos básicos. */
    $basicos = [
        $nombre,
        $apellido,
        $email
    ];

    $completosBasicos = 0;

    foreach ($basicos as $valor) {

        if (trim((string)$valor) !== '') {
            $completosBasicos++;
        }
    }

    $porcentajePerfil = (int) round(
        ($completosBasicos / count($basicos)) * 100
    );
}

$porcentajePerfil = max(
    0,
    min(100, $porcentajePerfil)
);

/* EVENTOS PARA FULLCALENDAR */
$eventosCalendario = [];

foreach ($turnosRaw as $t) {

    if (empty($t['fecha_hora'])) {
        continue;
    }

    try {

        $fechaInicio = new DateTime(
            $t['fecha_hora'],
            $zonaHoraria
        );

        $minutos = intval($t['minutos_turnos'] ?? 30);

        if ($minutos <= 0) {
            $minutos = 30;
        }

        $fechaFin = clone $fechaInicio;

        $fechaFin->modify("+{$minutos} minutes");

        $estado = trim(
            $t['tipo_estado'] ?? 'Pendiente'
        );

        /*
         * Colores según estado.
         */
        switch (mb_strtolower($estado)) {

            case 'activo':
            case 'confirmado':

                $colorEvento = '#198754';
                $colorTexto = '#ffffff';

                break;

            case 'pendiente':

                $colorEvento = '#f0ad4e';
                $colorTexto = '#ffffff';

                break;

            case 'cancelado':

                $colorEvento = '#dc3545';
                $colorTexto = '#ffffff';

                break;

            case 'finalizado':
            case 'realizado':
            case 'atendido':

                $colorEvento = '#8999AE';
                $colorTexto = '#ffffff';

                break;

            default:

                $colorEvento = '#007DC6';
                $colorTexto = '#ffffff';

                break;
        }

        $doctorNombre = trim(
            $t['doctor_nombre'] ?? 'Profesional'
        );

        $eventosCalendario[] = [

            'id' => (string)($t['id_agenda_turno'] ?? uniqid()),

            'title' => 'Dr. ' . $doctorNombre,

            'start' => $fechaInicio->format(
                'Y-m-d\TH:i:s'
            ),

            'end' => $fechaFin->format(
                'Y-m-d\TH:i:s'
            ),

            'backgroundColor' => $colorEvento,

            'borderColor' => $colorEvento,

            'textColor' => $colorTexto,

            'extendedProps' => [

                'idAgendaTurno' =>
                intval($t['id_agenda_turno'] ?? 0),

                'idTurno' =>
                intval($t['turno_id_turnos'] ?? 0),

                'doctor' =>
                $doctorNombre,

                'estado' =>
                $estado,

                'minutos' =>
                $minutos,

                'obraSocial' =>
                $t['nombre_obra_social'] ?? '',

                'conObraSocial' =>
                intval($t['con_obra_social'] ?? 0),

                'agenda' =>
                $t['agenda_desc'] ?? ''

            ]
        ];
    } catch (Exception $e) {

        continue;
    }
}

$eventosCalendarioJson = json_encode(
    $eventosCalendario,
    JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
);

if ($eventosCalendarioJson === false) {
    $eventosCalendarioJson = '[]';
}

?>
<head>
    <link rel="stylesheet" href="assets/css/miperfil_paciente.css">
</head>

<div class="dashboard-paciente container-fluid py-4 px-4">
    <!-- ENCABEZADO -->
    <div class="dashboard-header mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h3 mb-1">
                    Bienvenido/a,
                    <?= htmlspecialchars($nombrePaciente) ?>
                </h1>

                <p>
                    Gestione sus turnos y datos personales desde aquí.
                </p>
            </div>

            <div class="col-lg-4 d-flex justify-content-lg-end">
                <div class="dashboard-date">
                    <i class="bi bi-calendar3 me-2"></i>
                    <?= htmlspecialchars($fechaActual) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- INDICADORES -->
    <div class="row g-3 mb-4">
        <!-- PRÓXIMOS TURNOS -->
        <div class="col-6 col-xl-3">
            <div class="dashboard-stat-card primary">
                <div class="stat-content">
                    <div class="stat-icon primary">
                        <i class="bi bi-calendar2-check"></i>
                    </div>

                    <div class="stat-title">
                        Próximos turnos
                    </div>

                    <div class="stat-number">
                        <?= $turnosProximosCount ?>
                    </div>

                    <div class="stat-description">
                        Turnos programados
                    </div>
                </div>
            </div>
        </div>

        <!-- HISTORIAL -->
        <div class="col-6 col-xl-3">
            <div class="dashboard-stat-card success">
                <div class="stat-content">
                    <div class="stat-icon success">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div class="stat-title">
                        Historial
                    </div>

                    <div class="stat-number">
                        <?= $turnosHistorialCount ?>
                    </div>

                    <div class="stat-description">
                        Turnos anteriores
                    </div>
                </div>
            </div>
        </div>

        <!-- TURNOS DEL MES -->
        <div class="col-6 col-xl-3">
            <div class="dashboard-stat-card warning">
                <div class="stat-content">
                    <div class="stat-icon warning">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div class="stat-title">
                        Este mes
                    </div>

                    <div class="stat-number">
                        <?= $turnosMesCount ?>
                    </div>

                    <div class="stat-description">
                        Turnos registrados
                    </div>
                </div>
            </div>
        </div>

        <!-- ESTADO DE CUENTA -->
        <div class="col-6 col-xl-3">
            <div class="profile-card">
                <div class="profile-card-top">
                    <div>
                        <div class="profile-card-title">
                            Estado de mi cuenta
                        </div>
                    </div>

                    <div class="profile-card-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>

                <div class="profile-card-name">
                    <?= htmlspecialchars( trim($nombre . ' ' . $apellido) ) ?: htmlspecialchars($nombrePaciente) ?>
                </div>

                <div class="profile-card-email">
                    <?= htmlspecialchars( $email ?: 'Correo no registrado' ) ?>
                </div>


                <div class="profile-progress">
                    <div class="profile-progress-bar" style="width: <?= $porcentajePerfil ?>%;"></div>
                </div>


                <div class="profile-progress-label">
                    <span>
                        Información completada
                    </span>

                    <strong>
                        <?= $porcentajePerfil ?>%
                    </strong>
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="button" class="btn-mis-datos" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMisDatos">
                        <i class="bi bi-pencil-square me-1"></i>
                        Mis datos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CALENDARIO + PROXIMOS TURNOS -->
    <div class="row g-4 mb-4">
        <!-- CALENDARIO -->
        <div class="col-xl-7">
            <div class="calendario-paciente-wrapper">
                <div class="calendario-paciente-header">
                    <div>
                        <h5>
                            <i class="bi bi-calendar3 me-2" style="color:#007DC6;"></i>
                            Mi agenda
                        </h5>

                        <p>
                            Consulte sus turnos directamente desde el calendario.
                        </p>
                    </div>
                </div>

                <div class="calendario-paciente-body">
                    <div id="calendarioPaciente"></div>

                    <div class="calendario-leyenda">

                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot activo"></span>
                            Activo / confirmado
                        </div>

                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot pendiente"></span>
                            Pendiente
                        </div>

                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot cancelado"></span>
                            Cancelado
                        </div>

                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot finalizado"></span>
                            Finalizado
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- PROXIMOS TURNOS -->
        <div class="col-xl-5">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h5>
                                <i class="bi bi-calendar2-week me-2" style="color:#007DC6;"></i>
                                Próximos turnos
                            </h5>

                            <p>
                                Sus próximos turnos médicos.
                            </p>
                        </div>

                        <a href="index.php?page=turnos" class="btn btn-dashboard-primary">
                            <i class="bi bi-plus-lg me-1"></i>
                            Sacar turno
                        </a>
                    </div>

                </div>

                <div class="dashboard-card-body">
                    <!-- PROXIMO DESTACADO -->
                    <?php if ($proximoTurnoDestacado): ?>

                        <?php
                        $fechaDestacada = new DateTime(
                            $proximoTurnoDestacado['fecha_hora'],
                            $zonaHoraria
                        );

                        $doctorDestacado =
                            $proximoTurnoDestacado['doctor_nombre']
                            ?? 'Profesional';

                        $estadoDestacado =
                            $proximoTurnoDestacado['tipo_estado']
                            ?? 'Pendiente';

                        ?>

                        <div class="proximo-turno-card">

                            <div class="d-flex align-items-center gap-3">

                                <div class="proximo-turno-date">

                                    <div class="proximo-turno-day">
                                        <?= $fechaDestacada->format('d') ?>
                                    </div>

                                    <div class="proximo-turno-month">
                                        <?= strtoupper(
                                            $fechaDestacada->format('M')
                                        ) ?>
                                    </div>

                                </div>


                                <div class="proximo-turno-info">

                                    <div class="proximo-turno-doctor">
                                        Dr.
                                        <?= htmlspecialchars(
                                            $doctorDestacado
                                        ) ?>
                                    </div>

                                    <div class="proximo-turno-details">
                                        <i class="bi bi-clock me-1"></i>
                                        <?= $fechaDestacada->format('H:i') ?>
                                        hs
                                        ·
                                        <?= intval(
                                            $proximoTurnoDestacado['minutos_turnos'] ?? 30
                                        ) ?>
                                        min
                                    </div>


                                    <span class="proximo-turno-status">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <?= htmlspecialchars(
                                            $estadoDestacado
                                        ) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- LISTA DE TURNOS -->
                    <?php foreach (
                        array_slice($proximosTurnos, 0, 5)
                        as $t
                    ): ?>

                        <?php

                        $iniciales = '';

                        $partes = explode(
                            ' ',
                            trim(
                                $t['doctor_nombre']
                                    ?? 'Dr'
                            )
                        );

                        foreach (
                            array_slice($partes, 0, 2)
                            as $parte
                        ) {

                            $iniciales .= strtoupper(
                                substr($parte, 0, 1)
                            );
                        }

                        $claseEstado =
                            'estado-pendiente';

                        if (
                            in_array(
                                $t['tipo_estado'] ?? '',
                                [
                                    'Activo',
                                    'Confirmado'
                                ]
                            )
                        ) {

                            $claseEstado =
                                'estado-confirmado';
                        }

                        if (
                            ($t['tipo_estado'] ?? '')
                            === 'Cancelado'
                        ) {

                            $claseEstado =
                                'estado-cancelado';
                        }

                        ?>

                        <div class="turno-item">
                            <div class="turno-hora">
                                <?= date(
                                    'd/m H:i',
                                    strtotime(
                                        $t['fecha_hora']
                                    )
                                ) ?>
                            </div>

                            <div class="turno-avatar">
                                <?= htmlspecialchars( $iniciales ) ?>
                            </div>

                            <div class="turno-info">
                                <div class="turno-paciente">
                                    Dr.<?= htmlspecialchars($t['doctor_nombre'] ?? 'Profesional' ) ?>
                                </div>

                                <div class="turno-tipo">
                                    <?= intval( $t['minutos_turnos'] ?? 30 ) ?>
                                    min

                                    <?php if ( !empty($t['con_obra_social']) ): ?>
                                        ·
                                        <?= htmlspecialchars( $t['nombre_obra_social'] ?? 'Obra social' ) ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <span class="estado-turno
                            <?= $claseEstado ?>">
                                <?= htmlspecialchars($t['tipo_estado'] ?? 'Pendiente' ) ?>
                            </span>

                            <!-- Botones -->
                            <?php $estadoTurnoId = intval($t['estados_id_estados'] ?? 0); ?>

                            <?php if ($estadoTurnoId === 1): ?>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary btn-editar-turno"
                                    data-id-agenda-turno="<?= intval($t['id_agenda_turno']) ?>"
                                    data-turno="<?= intval($t['turno_id_turnos']) ?>"
                                    title="Modificar turno">
                                    <i class="bi bi-pencil-square"></i>
                                    Modificar
                                </button>
                            <?php endif; ?>

                            <?php if (in_array($estadoTurnoId, [1, 2], true)): ?>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger btn-cancelar-turno"
                                    data-id-agenda-turno="<?= intval($t['id_agenda_turno']) ?>"
                                    title="Cancelar turno">
                                    <i class="bi bi-x-circle"></i>
                                    Cancelar
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($proximosTurnos)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x" style=" font-size:2rem; color:#AFCEDF; "></i>
                            <p class="text-muted mt-2 mb-0">
                                No tiene turnos próximos.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ACCIONES RAPIDAS -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5>
                        <i class="bi bi-lightning-charge me-2" style="color:#007DC6;"></i>
                        Acciones rápidas
                    </h5>

                    <p>
                        Gestione sus turnos y perfil.
                    </p>
                </div>

                <div class="dashboard-card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="index.php?page=turnos" class="quick-action">
                                <div class="quick-action-icon">
                                    <i class="bi bi-calendar-plus"></i>
                                </div>

                                <div class="quick-action-text">
                                    <span class="quick-action-title">
                                        Sacar un turno
                                    </span>

                                    <span class="quick-action-description">
                                        Reservar con un doctor
                                    </span>
                                </div>

                                <i class="bi bi-chevron-right quick-action-arrow"></i>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <button type="button" class="quick-action w-100 text-start bg-white" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMisDatos">

                                <div class="quick-action-icon">
                                    <i class="bi bi-person-vcard"></i>
                                </div>

                                <div class="quick-action-text">
                                    <span class="quick-action-title">
                                        Mis datos
                                    </span>

                                    <span class="quick-action-description">
                                        Actualizar información personal
                                    </span>
                                </div>

                                <i class="bi bi-chevron-right quick-action-arrow"></i>
                            </button>
                        </div>


                        <div class="col-md-4">
                            <a href="#historialTurnos" class="quick-action">
                                <div class="quick-action-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div class="quick-action-text">
                                    <span class="quick-action-title">
                                        Historial
                                    </span>

                                    <span class="quick-action-description">
                                        Ver turnos anteriores
                                    </span>
                                </div>

                                <i class="bi bi-chevron-right quick-action-arrow"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5>
                        <i class="bi bi-person-check me-2" style="color:#007DC6;"></i>
                        Estado de la cuenta
                    </h5>

                    <p>
                        Mantenga sus datos actualizados.
                    </p>
                </div>

                <div class="dashboard-card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span style=" color:#8999AE; font-size:.75rem;">
                            Información completada
                        </span>

                        <strong style="color:#024296; font-size:.75rem; ">
                            <?= $porcentajePerfil ?>%
                        </strong>
                    </div>

                    <div class="profile-progress">
                        <div class="profile-progress-bar" style=" width: <?= $porcentajePerfil ?> %;"></div>
                    </div>

                    <button type="button" class="btn btn-dashboard-primary w-100 mt-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMisDatos">
                        <i class="bi bi-pencil-square me-1"></i>
                        Completar / modificar mis datos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORIAL -->
    <div class="row g-4" id="historialTurnos">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5>
                        <i class="bi bi-clock-history me-2" style="color:#007DC6;"></i>
                        Historial de turnos
                    </h5>

                    <p>
                        Turnos ya realizados o finalizados.
                    </p>
                </div>

                <div class="dashboard-card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Médico</th>
                                    <th>Fecha</th>
                                    <th>Duración</th>
                                    <th>Obra Social</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ( $historialTurnos as $t ): ?>
                                    <tr>
                                        <td>
                                            Dr.<?= htmlspecialchars($t['doctor_nombre'] ?? 'Profesional' ) ?>
                                        </td>

                                        <td>
                                            <?= date('d/m/Y H:i', strtotime($t['fecha_hora']) ) ?>
                                        </td>

                                        <td>
                                            <?= intval($t['minutos_turnos'] ?? 30 ) ?>
                                            min
                                        </td>

                                        <td>
                                            <?= !empty($t['con_obra_social']) ? htmlspecialchars($t['nombre_obra_social'] ?? 'Sí') :'No' ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars( $t['tipo_estado'] ?? '' ) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if ( empty($historialTurnos) ): ?>
                                    <tr>
                                        <td colspan="5" class="text-muted text-center py-4">
                                            <i class="bi bi-clock-history d-block mb-2" style=" font-size:1.5rem; color:#AFCEDF; "></i>
                                            Aún no tiene turnos en su historial.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- OFFCANVAS MIS DATOS -->
<div class="offcanvas offcanvas-end offcanvas-mis-datos" tabindex="-1" id="offcanvasMisDatos" aria-labelledby="offcanvasMisDatosLabel">
    <div class="offcanvas-header">
        <div>
            <h5 class="offcanvas-title" id="offcanvasMisDatosLabel">
                <i class="bi bi-person-vcard me-2"></i>
                Mis datos personales
            </h5>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>

    <div class="offcanvas-body">
        <div class="offcanvas-profile-header">
            <div class="offcanvas-profile-avatar">

                <?php $inicialesPaciente = '';
                if ($nombre !== '') {
                    $inicialesPaciente .=
                        strtoupper(
                            substr($nombre, 0, 1)
                        );
                }

                if ($apellido !== '') {
                    $inicialesPaciente .=
                        strtoupper(
                            substr($apellido, 0, 1)
                        );
                }

                if ($inicialesPaciente === '') {
                    $inicialesPaciente = 'P';
                } ?>

                <?= htmlspecialchars( $inicialesPaciente ) ?>
            </div>


            <div>
                <div class="offcanvas-profile-name">
                    <?= htmlspecialchars(trim( $nombre . ' ' . $apellido ) ) ?: htmlspecialchars($nombrePaciente) ?>
                </div>

                <div class="offcanvas-profile-email">
                    <?= htmlspecialchars($email ?: 'Correo no registrado' ) ?>
                </div>
            </div>

        </div>

        <div class="mis-datos-placeholder">
            <div class="mis-datos-placeholder-icon">
                <i class="bi bi-person-lines-fill"></i>
            </div>

            <h6>
                Actualizá tu información personal
            </h6>

            <p>
                Desde aquí podrás mantener actualizados
                tus datos personales y de contacto.
            </p>

            <a href="index.php?page=mis_datos" class="btn btn-dashboard-primary">
                <i class="bi bi-pencil-square me-1"></i>
                Abrir mis datos
            </a>
        </div>

        <div class="mt-3 p-3 bg-white border rounded-4">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-info-circle-fill" style=" color:#007DC6; margin-top:2px; "></i>
                <div>
                    <strong style=" color:#303744; font-size:.76rem; ">
                        ¿Por qué mantenerlos actualizados?
                    </strong>

                    <p class="mb-0 mt-1" style="color:#8999AE; font-size:.7rem; line-height:1.5;">
                        Sus datos permiten identificarlo correctamente
                        y facilitan la gestión de sus turnos y atención
                        médica.
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- MODAL ÚNICO PARA MODIFICAR TURNO -->
<div class="modal fade" id="modalModificarTurno" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-turno-paciente">

            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="turno-modal-icon">
                        <i class="bi bi-calendar2-check"></i>
                    </div>

                    <div>
                        <h5 class="turno-modal-title">
                            Modificar mi turno
                        </h5>

                        <div class="turno-modal-subtitle">
                            Actualizá los datos de tu próximo turno
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <form id="formEditarTurnoAsignado">
                <div class="modal-body">

                    <input type="hidden" name="action" value="editar_asignado">
                    <input type="hidden" name="id_agenda_turno" id="modal_id_agenda_turno" value="">
                    <input type="hidden" name="paciente_id_paciente" id="modal_paciente_id" value="<?= $idPaciente ?>">

                    <div class="turno-modal-section">
                        <div class="turno-modal-section-title">
                            <i class="bi bi-calendar2-week"></i>
                            <span>
                                Datos del turno
                            </span>
                        </div>

                        <!-- DOCTOR -->
                        <div class="mb-3">
                            <label class="turno-modal-label">
                                <i class="bi bi-person-badge"></i>
                                Profesional
                            </label>

                            <select id="modal_doctor_select" class="form-select select2-doctor-paciente" disabled>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <!-- AGENDA  -->
                        <div class="mb-3">
                            <label class="turno-modal-label">
                                <i class="bi bi-journal-medical"></i>
                                Agenda
                            </label>

                            <select name="agenda_id_agenda" id="modal_agenda_id_agenda" class="form-select agenda-select-edit-paciente">
                                <option value="">Agenda seleccionada</option>
                            </select>
                        </div>

                        <!-- TURNO (horario disponible) -->
                        <div class="mb-3">
                            <label class="turno-modal-label">
                                <i class="bi bi-clock-history"></i>
                                Horario disponible
                            </label>

                            <select name="turno_id_turnos" id="modal_turno_id_turnos" class="form-select select2-turnos-paciente">
                                <option value="">Horario actual</option>
                            </select>
                            <small class="text-muted">
                                Se mostrarán solo los turnos disponibles de la agenda seleccionada.
                            </small>
                        </div>

                        <!-- DURACIÓN: solo -->
                        <div class="mb-3">
                            <label class="turno-modal-label">
                                <i class="bi bi-hourglass-split"></i>
                                Duración de la consulta
                            </label>

                            <input type="text" id="modal_minutos_turnos" class="form-control" value="" readonly>
                        </div>
                    </div>

                    <!-- COBERTURA -->
                    <div class="turno-modal-section div-obra-social-toggle-modal">
                        <div class="turno-modal-section-title">
                            <i class="bi bi-credit-card-2-front"></i>
                            <span>
                                Cobertura
                            </span>
                        </div>

                        <div class="turno-cobertura-card">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="turno-cobertura-option">
                                        <input class="form-check-input" type="radio" name="con_obra_social" id="modal_particular" value="0" checked>
                                        <label for="modal_particular">Atención particular</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="turno-cobertura-option">
                                        <input class="form-check-input" type="radio" name="con_obra_social" id="modal_obra_social" value="1">
                                        <label for="modal_obra_social">Obra social</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Oculto por defecto: lo muestra/oculta el mismo
                                 JS genérico que ya usa turno_lista.php -->
                            <div class="mt-3 d-none div-select-obra-social-modal">
                                <label class="turno-modal-label">
                                    <i class="bi bi-shield-check"></i>
                                    Obra social
                                </label>

                                <select
                                    name="obra_social_id_obra_social"
                                    id="modal_obra_social_id"
                                    class="form-select">

                                    <option value="">
                                        Seleccioná una obra social
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="turno-modal-notice">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>
                            Revisá los datos antes de confirmar.
                            Los cambios se aplicarán a tu turno
                            cuando selecciones
                            <strong>Guardar cambios</strong>.
                        </span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-cancelar-turno-modal" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary btn-guardar-turno-modal">
                        <i class="bi bi-check2-circle me-1"></i>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/sweetalert2@11.js"></script>
<!--
     FullCalendar local.
     Colocá los archivos de FullCalendar en:
     assets/vendor/fullcalendar/
     Este archivo corresponde al bundle estándar.
-->
<script src="assets/vendor/fullcalendar/index.global.min.js"></script>
<script type="module" src="assets/js/validaciones/turno/funciones_turno_lista.js"></script>
<script type="module">
        // ============================================================
    // MODAL MODIFICAR TURNO - PERFIL DEL PACIENTE
    // Este código pertenece EXCLUSIVAMENTE a miperfil_paciente.php
    // No modifica ni interfiere con turno_lista.php
    // ============================================================

    $(function () {

        const $modal = $('#modalModificarTurno');

        if (!$modal.length) {
            return;
        }

        const $doctor = $('#modal_doctor_select');
        const $agenda = $('#modal_agenda_id_agenda');
        const $turno = $('#modal_turno_id_turnos');
        const $minutos = $('#modal_minutos_turnos');

        // FORMATEAR FECHA/HORA
        function formatearTurno(fechaHora) {

            if (!fechaHora) {
                return '';
            }

            const fecha = new Date(
                String(fechaHora).replace(' ', 'T')
            );

            if (isNaN(fecha.getTime())) {
                return fechaHora;
            }

            return fecha.toLocaleDateString('es-AR') +
                ' ' +
                fecha.toLocaleTimeString('es-AR', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
        }

        // INICIALIZAR SELECT2
        function inicializarSelect2() {

            if (
                $doctor.length &&
                !$doctor.hasClass('select2-hidden-accessible')
            ) {
                $doctor.select2({
                    dropdownParent: $modal,
                    width: '100%'
                });
            }

            if (
                $agenda.length &&
                !$agenda.hasClass('select2-hidden-accessible')
            ) {
                $agenda.select2({
                    dropdownParent: $modal,
                    width: '100%'
                });
            }

            if (
                $turno.length &&
                !$turno.hasClass('select2-hidden-accessible')
            ) {
                $turno.select2({
                    dropdownParent: $modal,
                    width: '100%'
                });
            }
        }


        // ========================================================
        // CARGAR AGENDAS DEL DOCTOR
        // ========================================================

        function cargarAgendasPaciente(
            doctorId,
            agendaSeleccionada
        ) {

            $agenda
                .empty()
                .append(
                    $('<option>')
                        .val('')
                        .text('Cargando agendas...')
                )
                .trigger('change');


            if (!doctorId) {
                $agenda
                    .empty()
                    .append(
                        $('<option>')
                            .val('')
                            .text('Seleccione una agenda')
                    )
                    .trigger('change');
                return Promise.resolve();
            }

            return fetch(
                'controladores/turno/ajax_get_agenda.php?doctor_id=' +
                encodeURIComponent(doctorId)
            )

            .then(function (response) {

                if (!response.ok) {
                    throw new Error(
                        'HTTP ' + response.status
                    );
                }

                return response.json();
            })

            .then(function (data) {

                const agendas =
                    Array.isArray(data.data)
                        ? data.data
                        : (
                            Array.isArray(data)
                                ? data
                                : []
                        );


                $agenda.empty();

                $agenda.append(
                    $('<option>')
                        .val('')
                        .text('Seleccione una agenda')
                );


                agendas.forEach(function (agenda) {

                    const id =
                        agenda.id_agenda;

                    let texto = '';

                    if (agenda.agenda_desc) {

                        texto =
                            agenda.agenda_desc;

                    } else {

                        texto =
                            id +
                            ' - (' +
                            (agenda.fecha_desde || '') +
                            ' / ' +
                            (agenda.fecha_hasta || '') +
                            ') - (' +
                            (agenda.hora_desde || '') +
                            ' - ' +
                            (agenda.hora_hasta || '') +
                            ')';
                    }


                    const $option =
                        $('<option>')
                            .val(id)
                            .text(texto);


                    if (
                        agendaSeleccionada &&
                        String(id) ===
                        String(agendaSeleccionada)
                    ) {

                        $option.prop(
                            'selected',
                            true
                        );
                    }


                    $agenda.append(
                        $option
                    );
                });

                return $agenda.val();
            })

            .catch(function (error) {

                console.error(
                    'Error al cargar agendas del paciente:',
                    error
                );

                $agenda
                    .empty()
                    .append(
                        $('<option>')
                            .val('')
                            .text('Error al cargar agendas')
                    )
                    .trigger('change');

                throw error;
            });
        }


        // ========================================================
        // CARGAR TODOS LOS TURNOS DE LA AGENDA
        // IMPORTANTE:
        // No usamos cargarTurnosDisponibles() porque el turno
        // actual del paciente puede tener disponible = 0.
        // Este modal necesita mostrar también el turno actual.
        // ========================================================

        function cargarTurnosPaciente(
            agendaId,
            turnoSeleccionado
        ) {

            $turno
                .empty()
                .append(
                    $('<option>')
                        .val('')
                        .text('Cargando horarios...')
                )
                .trigger('change');


            if (!agendaId) {

                $turno
                    .empty()
                    .append(
                        $('<option>')
                            .val('')
                            .text('Seleccione una agenda primero')
                    )
                    .trigger('change');

                $minutos.val('');

                return Promise.resolve();
            }


            return fetch(
                'controladores/turno/ajax_get_turnos_por_agenda.php?id_agenda=' +
                encodeURIComponent(agendaId)
            )

            .then(function (response) {

                if (!response.ok) {
                    throw new Error(
                        'HTTP ' + response.status
                    );
                }

                return response.json();
            })

            .then(function (data) {

                const turnos =
                    Array.isArray(data.data)
                        ? data.data
                        : (
                            Array.isArray(data)
                                ? data
                                : []
                        );


                $turno.empty();

                $turno.append(
                    $('<option>')
                        .val('')
                        .text('Seleccione un horario')
                );


                let turnoEncontrado = null;


                turnos.forEach(function (turno) {

                    const id =
                        turno.id_turnos;

                    const fechaHora =
                        turno.fecha_hora || '';

                    const minutos =
                        parseInt(
                            turno.minutos_turnos || 30
                        );


                    const $option =
                        $('<option>')
                            .val(id)
                            .text(
                                formatearTurno(
                                    fechaHora
                                ) +
                                ' (' +
                                minutos +
                                ' min)'
                            )
                            .attr(
                                'data-fecha',
                                fechaHora
                            )
                            .attr(
                                'data-minutos',
                                minutos
                            )
                            .attr(
                                'data-disponible',
                                turno.disponible
                            );


                    if (
                        turnoSeleccionado &&
                        String(id) ===
                        String(turnoSeleccionado)
                    ) {

                        $option.prop(
                            'selected',
                            true
                        );

                        turnoEncontrado =
                            turno;
                    }

                    /*
                     * Mostramos los turnos disponibles.
                     * EXCEPCIÓN:
                     * También mostramos el turno que el paciente ya tiene asignado.
                     */
                    if (
                        parseInt(turno.disponible) === 1 ||
                        (
                            turnoSeleccionado &&
                            String(id) ===
                            String(turnoSeleccionado)
                        )
                    ) {

                        $turno.append(
                            $option
                        );
                    }
                });
                $turno.trigger('change');

                // ------------------------------------------------
                // ACTUALIZAR DURACIÓN
                // ------------------------------------------------

                if (turnoEncontrado) {
                    $minutos.val(
                        (
                            parseInt(
                                turnoEncontrado.minutos_turnos ||
                                30
                            )
                        ) +
                        ' minutos'
                    );
                } else {

                    const $seleccionado =
                        $turno.find(
                            'option:selected'
                        );

                    const minutos =
                        $seleccionado.attr(
                            'data-minutos'
                        );

                    if (minutos) {
                        $minutos.val(
                            minutos +
                            ' minutos'
                        );
                    }
                }
                return turnoEncontrado;
            })

            .catch(function (error) {

                console.error(
                    'Error al cargar turnos del paciente:',
                    error
                );

                $turno
                    .empty()
                    .append(
                        $('<option>')
                            .val('')
                            .text('Error al cargar horarios')
                    )
                    .trigger('change');
                $minutos.val('');
                throw error;
            });
        }


        // ========================================================
        // CUANDO CAMBIA LA AGENDA
        // ========================================================

        $agenda.on(
            'change.perfilPaciente',
            function () {

                const agendaId =
                    $(this).val();


                /*
                 * Si el usuario cambia manualmente de agenda,
                 * ya no debemos conservar el turno anterior.
                 */
                if (
                    window._cambiandoAgendaPaciente
                ) {
                    return;
                }


                cargarTurnosPaciente(
                    agendaId,
                    null
                );
            }
        );


        // ========================================================
        // CUANDO CAMBIA EL TURNO
        // ========================================================

        $turno.on(
            'change.perfilPaciente',
            function () {

                const $option =
                    $(this).find(
                        'option:selected'
                    );

                const minutos =
                    $option.attr(
                        'data-minutos'
                    );


                if (minutos) {

                    $minutos.val(
                        minutos +
                        ' minutos'
                    );

                } else {

                    $minutos.val('');
                }
            }
        );


        // ========================================================
        // AL MOSTRAR EL MODAL
        // ========================================================

        $modal.on(
            'shown.bs.modal.perfilPaciente',
            function () {

                inicializarSelect2();


                /*
                 * El objeto turnoPacienteActual es creado por
                 * abrirModalTurno().
                 */
                const turnoActual =
                    window.turnoPacienteActual;

                if (!turnoActual) {
                    return;
                }


                const doctorId =
                    turnoActual.doctor_id || '';


                const agendaId =
                    turnoActual.agenda_id_agenda || '';


                const turnoId =
                    turnoActual.turno_id_turnos || '';


                /*
                 * El doctor ya fue cargado por abrirModalTurno().
                 */
                $doctor
                    .val(doctorId)
                    .trigger('change');


                /*
                 * Cargamos las agendas del doctor y, cuando
                 * termina, cargamos los turnos de esa agenda.
                 */
                cargarAgendasPaciente(
                    doctorId,
                    agendaId
                )
                .then(function () {

                    const agendaSeleccionada =
                        $agenda.val() ||
                        agendaId;


                    return cargarTurnosPaciente(
                        agendaSeleccionada,
                        turnoId
                    );
                })
                .catch(function (error) {

                    console.error(
                        'No se pudo inicializar el selector de turnos:',
                        error
                    );
                });
            }
        );

    });

    /* DATOS DEL CALENDARIO */
    const eventosCalendarioPaciente =
        <?= $eventosCalendarioJson ?>;

    /* VARIABLES */
    let calendarioPaciente = null;
    let modalModificarTurno = null;

    /* ============================================================
   INICIALIZAR CALENDARIO DEL PACIENTE
   ============================================================ */

function inicializarCalendarioPaciente() {

    const calendarioEl =
        document.getElementById('calendarioPaciente');

    if (!calendarioEl) {
        console.warn(
            'No se encontró #calendarioPaciente.'
        );
        return;
    }

    if (typeof FullCalendar === 'undefined') {
        console.warn(
            'FullCalendar no está disponible.'
        );
        return;
    }

    /* Evitar inicializarlo dos veces */
    if (calendarioPaciente) {
        return;
    }

    calendarioPaciente =
        new FullCalendar.Calendar(
            calendarioEl,
            {
                initialView: 'dayGridMonth',

                locale: 'es',

                firstDay: 1,

                timeZone:
                    'America/Argentina/Buenos_Aires',

                height: 'auto',

                contentHeight: 'auto',

                dayMaxEvents: 3,

                moreLinkText: function(num) {
                    return '+' + num + ' más';
                },

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right:
                        'dayGridMonth,timeGridWeek,listWeek'
                },

                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    list: 'Lista'
                },

                noEventsText:
                    'No hay turnos para mostrar.',

                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },

                events:
                    eventosCalendarioPaciente,

                eventClick: function(info) {

                    info.jsEvent.preventDefault();

                    const evento =
                        info.event;

                    abrirModalTurno(evento);
                }
            }
        );

    calendarioPaciente.render();
}


/*
 * Si el DOM todavía se está cargando,
 * esperamos a DOMContentLoaded.
 *
 * Si esta vista fue cargada cuando el DOM
 * ya estaba listo, inicializamos inmediatamente.
 */
if (document.readyState === 'loading') {

    document.addEventListener(
        'DOMContentLoaded',
        inicializarCalendarioPaciente
    );

} else {

    inicializarCalendarioPaciente();

}


    /* ABRIR MODAL DE TURNO */
    function abrirModalTurno(evento) {
        const props = evento.extendedProps || {};
        const idAgendaTurno = props.idAgendaTurno || evento.id;

        const turnosPaciente =
            <?= json_encode(
                $turnosRaw,
                JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
            ) ?>;

        const turno = turnosPaciente.find(function(item) {
                return String(item.id_agenda_turno) === String(idAgendaTurno);
            });

        if (!turno) {
            Swal.fire(
                'Turno',
                'No se pudieron cargar los datos del turno.',
                'warning'
            );
            return;
        }
        // Solo los turnos Activos (estado 1) pueden modificarse.
        if (parseInt(turno.estados_id_estados) !== 1) {

            const esPendiente = parseInt(turno.estados_id_estados) === 2;

            Swal.fire({
                icon: 'info',
                title: esPendiente ? 'Turno pendiente' : 'Turno no modificable',
                text: esPendiente
                    ? 'Este turno todavía no fue confirmado, por lo que no se puede modificar. Si ya no lo necesitás, podés cancelarlo desde "Próximos turnos".'
                    : 'Este turno ya no admite modificaciones.'
            });

            return;
        }
        window.turnoPacienteActual = turno;

        // ID de la asignación
        document.getElementById('modal_id_agenda_turno').value =
            turno.id_agenda_turno || '';

        // Paciente (hidden, usado por el JS de obra social)
        document.getElementById('modal_paciente_id').value =
            <?= (int) $idPaciente ?>;

        // DOCTOR: un único option fijo, de solo lectura.
        // Se arma dinámicamente porque el select está deshabilitado
        // y no necesita (ni debe) ofrecer otras opciones.
        const doctorSelect =
            document.getElementById('modal_doctor_select');

        if (doctorSelect) {
            doctorSelect.innerHTML = '';

            const opt = document.createElement('option');
            opt.value = turno.doctor_id || '';
            opt.textContent = 'Dr. ' + (turno.doctor_nombre || 'Profesional');
            opt.selected = true;

            doctorSelect.appendChild(opt);
        }

        // AGENDA: option inicial; modalEditar_TurnosAsignados.js la
        // reemplaza por el listado real del doctor al abrir el modal,
        // preservando esta selección.
        const agendaSelect =
            document.getElementById('modal_agenda_id_agenda');

        if (agendaSelect) {
            agendaSelect.innerHTML = '';

            const opt = document.createElement('option');
            opt.value = turno.agenda_id_agenda || '';
            opt.textContent = turno.agenda_desc || 'Agenda seleccionada';
            opt.selected = true;

            agendaSelect.appendChild(opt);
        }

        // TURNO (horario): option inicial; se reemplaza igual que
        // la agenda, en cascada, por el listado real de turnos
        // disponibles de esa agenda.
        const turnoSelect =
            document.getElementById('modal_turno_id_turnos');

        if (turnoSelect) {
            turnoSelect.innerHTML = '';

            const opt = document.createElement('option');
            opt.value = turno.turno_id_turnos || '';
            opt.textContent = turno.fecha_hora ?
                formatearFechaHora(turno.fecha_hora) :
                'Horario actual';
            opt.setAttribute('data-minutos', turno.minutos_turnos || '');
            opt.selected = true;

            turnoSelect.appendChild(opt);
        }

        // DURACIÓN: solo lectura, se completa con el turno actual
        // y se actualiza sola si el usuario elige otro horario.
        const minutosInput =
            document.getElementById('modal_minutos_turnos');

        if (minutosInput) {
            minutosInput.value =
                (turno.minutos_turnos || 30) + ' minutos';
        }

        // COBERTURA
        const conObraSocial = parseInt(turno.con_obra_social || 0);

        const radioParticular = document.getElementById('modal_particular');
        const radioObraSocial = document.getElementById('modal_obra_social');

        if (conObraSocial === 1) {
            radioObraSocial.checked = true;
        } else {
            radioParticular.checked = true;
        }

        /* OBRA SOCIAL: option inicial con la obra social actual (si la hay). El toggle de visibilidad y la recarga de opciones compatibles las maneja el JS compartido de funciones_turno_lista.js, igual que en turno_lista.php. */
        const obraSocialSelect =
            document.getElementById('modal_obra_social_id');

        if (obraSocialSelect) {
            obraSocialSelect.innerHTML = '';

            const opt = document.createElement('option');
            opt.value = turno.obra_social_id_obra_social || '';
            opt.textContent = turno.nombre_obra_social || 'Obra social actual';
            opt.selected = true;

            obraSocialSelect.appendChild(opt);
        }

        // Abrir modal
        const modalElement =
            document.getElementById('modalModificarTurno');

        if (modalElement && typeof bootstrap !== 'undefined') {
            modalModificarTurno =
                bootstrap.Modal.getOrCreateInstance(modalElement);
            modalModificarTurno.show();
        }
    }
    /* FORMATEAR FECHA */
    function formatearFechaHora(fechaHora) {
        if (!fechaHora) {
            return 'Horario actual';
        }

        const fecha = new Date(fechaHora.replace( ' ', 'T' ) );
        if (isNaN(fecha.getTime())) {
            return fechaHora;
        }

        return fecha.toLocaleDateString('es-AR') + ' ' + fecha.toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit'} );
    }

    /*  CANCELAR TURNO */
    document.addEventListener(
        'click',
        function(e) {
            const btn = e.target.closest('.btn-cancelar-turno');

            if (!btn) {
                return;
            }

            const idAgendaTurno =  btn.dataset.idAgendaTurno;

            Swal.fire({
                title: '¿Cancelar este turno?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'Volver',
                reverseButtons: true
            }).then(
                function(result) {
                    if (!result.isConfirmed) {
                        return;
                    }

                    const formData =
                        new FormData();

                    formData.append(
                        'action',
                        'eliminar'
                    );

                    formData.append(
                        'id_agenda_turno',
                        idAgendaTurno
                    );


                    fetch('controladores/turno/turno_controlador.php', {method: 'POST', body: formData})
                        .then(
                            function(r) {
                                return r.json();
                            }
                        )
                        .then(
                            function(data) {
                                if (data.success) {
                                    Swal.fire(
                                        'Cancelado',
                                        'Su turno fue cancelado.',
                                        'success'
                                    ).then(
                                        function() {
                                            location.reload();
                                        }
                                    );
                                } else {
                                    Swal.fire(
                                        'Error',
                                        data.error ||
                                        'No se pudo cancelar.',
                                        'error'
                                    );
                                }

                            }
                        )
                        .catch(
                            function() {
                                Swal.fire(
                                    'Error',
                                    'Error de red al cancelar el turno.',
                                    'error'
                                );
                            }
                        );
                }
            );
        }
    );

    /*  BOTONES "MODIFICAR" DE LA LISTA */
    document.addEventListener(
        'click',
        function(e) {
            const btn = e.target.closest( '.btn-editar-turno' );
            if (!btn) {
                return;
            }

            const id = btn.dataset.idAgendaTurno;

            const evento =
                calendarioPaciente ?
                calendarioPaciente.getEventById(
                    String(id)
                ) :
                null;

            if (evento) {
                abrirModalTurno(
                    evento
                );
                return;
            }

            /* Si por algún motivo no está disponible en el calendario, buscamos directamente en los datos. */
            const turnosPaciente =
                <?= json_encode(
                    $turnosRaw,
                    JSON_UNESCAPED_UNICODE |
                        JSON_UNESCAPED_SLASHES
                ) ?>;

            const turno =
                turnosPaciente.find(
                    function(item) {
                        return String(
                            item.id_agenda_turno
                        ) === String(id);
                    }
                );

            if (!turno) {
                return;
            }

            /* Crear un objeto compatible con abrirModalTurno(). */
            abrirModalTurno({
                id: String(id),
                extendedProps: {
                    idAgendaTurno: id
                }
            });

        }
    );

    /*  ENVÍO DEL FORMULARIO DE MODIFICACIÓN */
    document.addEventListener(
        'submit',
        function(e) {
            const form =
                e.target;
            if (
                form.id !==
                'formEditarTurnoAsignado'
            ) {
                return;
            }

            e.preventDefault();

            const formData =
                new FormData(form);

            fetch(
                    'controladores/turno/turno_controlador.php', {
                        method: 'POST',
                        body: formData
                    }
                )
                .then(
                    function(r) {
                        return r.json();
                    }
                )
                .then(
                    function(data) {
                        if (data.success) {
                            Swal.fire(
                                'Listo',
                                'El turno fue actualizado.',
                                'success'
                            ).then(
                                function() {
                                    location.reload();
                                }
                            );

                        } else {
                            Swal.fire(
                                'Error',
                                data.error ||
                                'No se pudo actualizar el turno.',
                                'error'
                            );
                        }

                    }
                )
                .catch(
                    function(error) {
                        console.error(
                            error
                        );

                        Swal.fire(
                            'Error',
                            'Error de red al actualizar el turno.',
                            'error'
                        );

                    }
                );

        }
    );

    /* SINCRONIZAR DURACIÓN AL CAMBIAR DE HORARIO */
    document.addEventListener('change', function(e) {
        if (!e.target || e.target.id !== 'modal_turno_id_turnos') {
            return;
        }

        const seleccionado = e.target.options[e.target.selectedIndex];
        const minutos = seleccionado ?
            seleccionado.getAttribute('data-minutos') :
            null;

        const minutosInput =
            document.getElementById('modal_minutos_turnos');

        if (minutosInput && minutos) {
            minutosInput.value = minutos + ' minutos';
        }
    });
</script>