<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreDoctor = $_SESSION['nombre_usuario'] ?? 'Doctor';
$fechaActual = date('d/m/Y');

require_once __DIR__ . "/../../modelos/doctor.php";
require_once __DIR__ . "/../../modelos/agenda_turno.php";
require_once __DIR__ . "/../../modelos/paciente.php";

$doctorModel = new Paciente();
$datosDoctor = $doctorModel->obtenerPorUsuario($_SESSION['id_usuario'] ?? 0);
$idDoctor = $datosDoctor['id_doctor'] ?? 0;

$agendaTurnoModel = new AgendaTurno();
$turnosDoctorRaw = $idDoctor > 0
    ? $agendaTurnoModel->obtenerTurnosPorDoctor($idDoctor)
    : [];

$hoy = date('Y-m-d');

$turnos = [];
$turnosAtendidos = 0;
$turnosPendientes = 0;
$turnosCancelados = 0;

foreach ($turnosDoctorRaw as $t) {

    $esHoy = substr($t['fecha_hora'], 0, 10) === $hoy;

    // Clasificar por estado (ajustá los nombres si tu tabla
    // "estados" usa otros textos distintos a estos).
    $estadoTexto = $t['tipo_estado'];

    if (in_array($estadoTexto, ['Atendido', 'Completado', 'Finalizado'])) {
        if ($esHoy) $turnosAtendidos++;
    } elseif (in_array($estadoTexto, ['Cancelado'])) {
        if ($esHoy) $turnosCancelados++;
    } else {
        if ($esHoy) $turnosPendientes++;
    }

    if ($esHoy) {
        $turnos[] = [
            'id_agenda_turno' => $t['id_agenda_turno'],
            'hora' => date('H:i', strtotime($t['fecha_hora'])),
            'paciente' => $t['paciente_nombre'],
            'tipo' => 'Turno médico',
            'estado' => $estadoTexto
        ];
    }
}

$turnosHoy = count($turnos);

$pacientesAtendidosMes = 0;
$agendasActivas = 0;        

$porcentajeAtencion = $turnosHoy > 0
    ? round(($turnosAtendidos / $turnosHoy) * 100)
    : 0;


$proximasAgendas = [
    [
        'dia' => 'Lunes',
        'fecha' => '29 Sep',
        'horario' => '08:00 - 13:00',
        'consultorio' => 'Consultorio 1',
        'turnos' => 10
    ],
    [
        'dia' => 'Martes',
        'fecha' => '30 Sep',
        'horario' => '14:00 - 19:00',
        'consultorio' => 'Consultorio 2',
        'turnos' => 10
    ],
    [
        'dia' => 'Jueves',
        'fecha' => '02 Oct',
        'horario' => '08:00 - 12:00',
        'consultorio' => 'Consultorio 1',
        'turnos' => 8
    ]
];

/*
|--------------------------------------------------------------------------
| Notificaciones
|--------------------------------------------------------------------------
*/

$notificaciones = [
    [
        'icono' => 'bi-calendar-check',
        'titulo' => 'Nuevo turno confirmado',
        'descripcion' => 'Se confirmó un turno para mañana.',
        'tiempo' => 'Hace 15 min',
        'tipo' => 'primary'
    ],
    [
        'icono' => 'bi-calendar-event',
        'titulo' => 'Agenda modificada',
        'descripcion' => 'La agenda del martes fue actualizada.',
        'tiempo' => 'Hace 1 h',
        'tipo' => 'info'
    ],
    [
        'icono' => 'bi-person-plus',
        'titulo' => 'Nuevo paciente',
        'descripcion' => 'Se registró un nuevo paciente.',
        'tiempo' => 'Hace 3 h',
        'tipo' => 'success'
    ]
];
?>

<?php
$estadisticasDashboard = [
    "turnosSemana" => [9,12,10,14,11,5,2],
    "estadosTurnos" => [78,24,11,7],
    "pacientesNuevos" => [18,21,16,24,28,31],
    "pacientesRecurrentes" => [42,48,51,55,62,67],
    "consultas" => [51,59,63,71,82,89],
    "turnosCompletados" => [47,54,58,66,76,78],
    "actividadDias" => [22,27,24,29,21],
    "ocupacion" => [74,79,76,84,82,88]
];
?>

<style>

/* ================================================================
   DASHBOARD DOCTOR
================================================================ */

.dashboard-doctor {
    --azul-oscuro: #000967;
    --azul: #024296;
    --azul-claro: #007DC6;
    --gris-azulado: #8999AE;
    --celeste: #AFCEDF;

    background: #f6f8fb;
    min-height: calc(100vh - 20px);
}


/* ================================================================
   ENCABEZADO
================================================================ */

.dashboard-header {
    margin-bottom: 1.5rem;
}

.dashboard-header h1 {
    color: var(--azul-oscuro);
    font-weight: 700;
    letter-spacing: -0.4px;
}

.dashboard-header p {
    color: #6c757d;
    margin-bottom: 0;
}

.dashboard-date {
    background: #fff;
    border: 1px solid #e9edf3;
    border-radius: 14px;
    padding: 10px 16px;
    color: #5f6b7a;
    font-size: .875rem;
    box-shadow: 0 3px 12px rgba(0, 9, 103, .04);
}


/* ================================================================
   TARJETAS DE ESTADÍSTICAS
================================================================ */

.dashboard-stat-card {
    position: relative;
    height: 100%;
    background: #fff;
    border: 0;
    border-radius: 18px;
    box-shadow: 0 4px 18px rgba(0, 9, 103, .06);
    overflow: hidden;
    transition: all .25s ease;
}

.dashboard-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 9, 103, .10);
}

.dashboard-stat-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--azul-claro);
}

.dashboard-stat-card.primary::before {
    background: var(--azul-oscuro);
}

.dashboard-stat-card.success::before {
    background: #198754;
}

.dashboard-stat-card.warning::before {
    background: #f0ad4e;
}

.dashboard-stat-card.danger::before {
    background: #dc3545;
}

.stat-content {
    padding: 1.25rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    margin-bottom: 1rem;
}

.stat-icon.primary {
    background: rgba(0, 9, 103, .08);
    color: var(--azul-oscuro);
}

.stat-icon.blue {
    background: rgba(0, 125, 198, .10);
    color: var(--azul-claro);
}

.stat-icon.success {
    background: rgba(25, 135, 84, .10);
    color: #198754;
}

.stat-icon.warning {
    background: rgba(240, 173, 78, .12);
    color: #b87912;
}

.stat-title {
    color: #8999AE;
    font-size: .82rem;
    font-weight: 600;
    margin-bottom: .2rem;
}

.stat-number {
    color: #202735;
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1.2;
}

.stat-description {
    color: #8999AE;
    font-size: .76rem;
    margin-top: .4rem;
}


/* ================================================================
   CARDS GENERALES
================================================================ */

.dashboard-card {
    background: #fff;
    border: 0;
    border-radius: 18px;
    box-shadow: 0 4px 18px rgba(0, 9, 103, .055);
    height: 100%;
}

.dashboard-card-header {
    padding: 1.25rem 1.4rem;
    border-bottom: 1px solid #edf0f4;
}

.dashboard-card-header h5 {
    margin: 0;
    color: #202735;
    font-size: 1rem;
    font-weight: 700;
}

.dashboard-card-header p {
    margin: .3rem 0 0;
    color: #8999AE;
    font-size: .78rem;
}

.dashboard-card-body {
    padding: 1.25rem 1.4rem;
}


/* ================================================================
   AGENDA DE HOY
================================================================ */

.turno-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 13px 0;
    border-bottom: 1px solid #f0f2f5;
}

.turno-item:last-child {
    border-bottom: 0;
}

.turno-hora {
    min-width: 55px;
    color: var(--azul-oscuro);
    font-weight: 700;
    font-size: .88rem;
}

.turno-avatar {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 12px;
    background: #edf5fa;
    color: var(--azul);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.turno-info {
    flex: 1;
    min-width: 0;
}

.turno-paciente {
    color: #252b35;
    font-size: .88rem;
    font-weight: 600;
    margin-bottom: 2px;
}

.turno-tipo {
    color: #8999AE;
    font-size: .75rem;
}

.estado-turno {
    font-size: .68rem;
    padding: 5px 9px;
    border-radius: 30px;
    font-weight: 600;
}

.estado-confirmado {
    color: #198754;
    background: rgba(25, 135, 84, .10);
}

.estado-espera {
    color: #b87912;
    background: rgba(240, 173, 78, .12);
}

.estado-pendiente {
    color: var(--azul);
    background: rgba(0, 66, 150, .08);
}


/* ================================================================
   ACCIONES RAPIDAS
================================================================ */

.quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border: 1px solid #edf0f4;
    border-radius: 13px;
    text-decoration: none;
    color: #303744;
    margin-bottom: 10px;
    transition: all .2s ease;
}

.quick-action:last-child {
    margin-bottom: 0;
}

.quick-action:hover {
    border-color: var(--celeste);
    background: #f8fbfd;
    color: var(--azul);
    transform: translateX(3px);
}

.quick-action-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 11px;
    background: rgba(0, 125, 198, .08);
    color: var(--azul-claro);
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-action-text {
    flex: 1;
}

.quick-action-title {
    display: block;
    font-size: .83rem;
    font-weight: 600;
}

.quick-action-description {
    display: block;
    color: #8999AE;
    font-size: .7rem;
    margin-top: 2px;
}

.quick-action-arrow {
    color: #b2bbc6;
}


/* ================================================================
   PERFIL PROFESIONAL
================================================================ */

.profile-status {
    background: linear-gradient(135deg, #000967 0%, #024296 100%);
    border-radius: 18px;
    color: #fff;
    overflow: hidden;
    position: relative;
}

.profile-status::after {
    content: "";
    position: absolute;
    width: 150px;
    height: 150px;
    right: -60px;
    top: -60px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}

.profile-status-content {
    padding: 1.35rem;
    position: relative;
    z-index: 1;
}

.profile-status-title {
    font-size: .8rem;
    opacity: .75;
}

.profile-status h4 {
    font-size: 1.05rem;
    margin: 4px 0 12px;
}

.profile-progress {
    height: 6px;
    background: rgba(255,255,255,.18);
    border-radius: 10px;
    overflow: hidden;
}

.profile-progress-bar {
    width: 82%;
    height: 100%;
    background: #fff;
    border-radius: 10px;
}


/* ================================================================
   ACTIVIDAD
================================================================ */

.activity-bar {
    margin-bottom: 1rem;
}

.activity-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
    font-size: .78rem;
}

.activity-label span:first-child {
    color: #596575;
    font-weight: 600;
}

.activity-label span:last-child {
    color: #8999AE;
}

.activity-progress {
    height: 8px;
    border-radius: 20px;
    background: #edf1f5;
    overflow: hidden;
}

.activity-progress div {
    height: 100%;
    border-radius: 20px;
    background: linear-gradient(90deg, #000967, #007DC6);
}


/* ================================================================
   AGENDAS
================================================================ */

.agenda-item {
    padding: 13px 0;
    border-bottom: 1px solid #edf0f4;
}

.agenda-item:last-child {
    border-bottom: 0;
}

.agenda-date {
    min-width: 68px;
    text-align: center;
    background: #f2f7fa;
    border-radius: 12px;
    padding: 8px 6px;
}

.agenda-day {
    color: var(--azul);
    font-size: .68rem;
    text-transform: uppercase;
    font-weight: 700;
}

.agenda-number {
    color: var(--azul-oscuro);
    font-size: 1.05rem;
    font-weight: 700;
}

.agenda-info h6 {
    margin: 0 0 3px;
    font-size: .83rem;
    color: #2c3440;
}

.agenda-info span {
    display: block;
    color: #8999AE;
    font-size: .72rem;
}


/* ================================================================
   NOTIFICACIONES
================================================================ */

.notification-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #edf0f4;
}

.notification-item:last-child {
    border-bottom: 0;
}

.notification-icon {
    width: 38px;
    height: 38px;
    min-width: 38px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-icon.primary {
    background: rgba(0, 9, 103, .08);
    color: var(--azul-oscuro);
}

.notification-icon.info {
    background: rgba(0, 125, 198, .09);
    color: var(--azul-claro);
}

.notification-icon.success {
    background: rgba(25, 135, 84, .10);
    color: #198754;
}

.notification-title {
    color: #303744;
    font-size: .8rem;
    font-weight: 600;
}

.notification-description {
    color: #8999AE;
    font-size: .72rem;
    margin-top: 2px;
}

.notification-time {
    color: #aab2bd;
    font-size: .67rem;
    margin-top: 4px;
}


/* ================================================================
   BOTONES
================================================================ */

.btn-dashboard-primary {
    background: var(--azul-oscuro);
    border-color: var(--azul-oscuro);
    color: #fff;
    border-radius: 10px;
    font-weight: 600;
    padding: 9px 15px;
    font-size: .82rem;
}

.btn-dashboard-primary:hover {
    background: var(--azul);
    border-color: var(--azul);
    color: #fff;
}

.btn-dashboard-light {
    background: #fff;
    border: 1px solid #e2e7ed;
    color: #5f6b7a;
    border-radius: 10px;
    font-size: .78rem;
    font-weight: 600;
}

.btn-dashboard-light:hover {
    border-color: var(--celeste);
    color: var(--azul);
}


/* ================================================================
   GRÁFICOS Y ESTADÍSTICAS
================================================================ */
.dashboard-chart-card{background:#fff;border:0;border-radius:18px;box-shadow:0 4px 18px rgba(0,9,103,.055);height:100%;overflow:hidden}
.dashboard-chart-header{padding:1.25rem 1.4rem;border-bottom:1px solid #edf0f4}
.dashboard-chart-header h5{margin:0;color:#202735;font-size:1rem;font-weight:700}
.dashboard-chart-header p{margin:.3rem 0 0;color:#8999AE;font-size:.78rem}
.dashboard-chart-body{position:relative;height:330px;padding:1.25rem 1.4rem}
.dashboard-chart-body.compact{height:285px}.dashboard-chart-body.tall{height:360px}
.dashboard-chart-filter{border:1px solid #e2e7ed;color:#5f6b7a;background:#fff;border-radius:9px;font-size:.75rem;padding:6px 10px;font-weight:600}
.chart-kpi{border:1px solid #edf0f4;border-radius:14px;padding:14px;height:100%}
.chart-kpi-label{color:#8999AE;font-size:.7rem;font-weight:600;text-transform:uppercase}.chart-kpi-value{color:#202735;font-size:1.35rem;font-weight:700;margin-top:3px}.chart-kpi-detail{color:#8999AE;font-size:.7rem;margin-top:2px}.chart-kpi-trend{color:#198754;font-size:.7rem;font-weight:600}
.chart-insight{border-radius:14px;background:#f5f9fc;border:1px solid #e5eff5;padding:13px 15px;color:#596575;font-size:.76rem}.chart-insight i{color:#007DC6}
.chart-progress-row{margin-bottom:1rem}.chart-progress-label{display:flex;justify-content:space-between;margin-bottom:6px;font-size:.75rem}.chart-progress-label span:first-child{color:#596575;font-weight:600}.chart-progress-label span:last-child{color:#202735;font-weight:700}.chart-progress{height:9px;border-radius:20px;background:#edf1f5;overflow:hidden}.chart-progress-bar{height:100%;border-radius:20px;background:linear-gradient(90deg,#000967,#007DC6)}
/* ================================================================
   RESPONSIVE
================================================================ */

@media (max-width: 991.98px) {

    .dashboard-header .dashboard-date {
        margin-top: 1rem;
    }

    .turno-item {
        flex-wrap: wrap;
    }

    .estado-turno {
        margin-left: 69px;
    }
}

@media (max-width: 575.98px) {

    .dashboard-doctor {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .dashboard-header h1 {
        font-size: 1.35rem;
    }

    .stat-number {
        font-size: 1.5rem;
    }
}

</style>

<div class="dashboard-doctor container-fluid py-4 px-4">
    <!-- ENCABEZADO -->
    <div class="dashboard-header">

        <div class="row align-items-center">

            <div class="col-lg-8">
                <h1 class="h3 mb-1">
                    Buenos días, Dr. <?= htmlspecialchars($nombreDoctor) ?>
                </h1>

                <p>
                    Aquí tiene un resumen de su actividad profesional y sus turnos de hoy.
                </p>

            </div>

            <div class="col-lg-4 d-flex justify-content-lg-end">

                <div class="dashboard-date">
                    <i class="bi bi-calendar3 me-2"></i>
                    <?= $fechaActual ?>
                </div>

            </div>

        </div>

    </div>

    <!-- =========================================================
         AGENDA + ACCIONES RAPIDAS
    ========================================================== -->

    <div class="row g-4 mb-4">

        <!-- =====================================================
             TURNOS DE HOY
        ====================================================== -->

        <div class="col-xl-8">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5>
                                <i class="bi bi-calendar2-week me-2"
                                   style="color:#007DC6;"></i>
                                Agenda de hoy
                            </h5>

                            <p>
                                Resumen de los pacientes programados para la jornada.
                            </p>

                        </div>

                        <a href="index.php?page=agenda_lista"
                           class="btn btn-dashboard-light">
                            Ver agenda
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>

                    </div>

                </div>


                <div class="dashboard-card-body">

                    <?php foreach ($turnos as $turno): ?>

                        <?php

                        $iniciales = '';

                        $partes = explode(' ', trim($turno['paciente']));

                        foreach (array_slice($partes, 0, 2) as $parte) {
                            $iniciales .= strtoupper(substr($parte, 0, 1));
                        }

                        $claseEstado = 'estado-pendiente';

                        if (in_array($turno['estado'], ['Atendido', 'Completado', 'Finalizado', 'Confirmado'])) {
                            $claseEstado = 'estado-confirmado';
                        }

                        if ($turno['estado'] === 'Activo') {
                            $claseEstado = 'estado-espera';
                        }

                        ?>

                        <div class="turno-item">

                            <div class="turno-hora">
                                <?= htmlspecialchars($turno['hora']) ?>
                            </div>

                            <div class="turno-avatar">
                                <?= htmlspecialchars($iniciales) ?>
                            </div>

                            <div class="turno-info">

                                <div class="turno-paciente">
                                    <?= htmlspecialchars($turno['paciente']) ?>
                                </div>

                                <div class="turno-tipo">
                                    <?= htmlspecialchars($turno['tipo']) ?>
                                </div>

                            </div>

                            <span class="estado-turno <?= $claseEstado ?>">
                                <?= htmlspecialchars($turno['estado']) ?>
                            </span>

                            <?php if ($turno['estado'] !== 'Cancelado'): ?>
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger ms-2 btn-cancelar-turno"
                                    data-id-agenda-turno="<?= $turno['id_agenda_turno'] ?>"
                                    title="Cancelar turno">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                    <?php if (empty($turnos)): ?>
                        <p class="text-muted text-center py-3 mb-0">
                            No tiene turnos programados para hoy.
                        </p>
                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- =====================================================
             ACCIONES RAPIDAS
        ====================================================== -->

        <div class="col-xl-4">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <h5>
                        <i class="bi bi-lightning-charge me-2"
                           style="color:#007DC6;"></i>
                        Acciones rápidas
                    </h5>

                    <p>
                        Accesos frecuentes para gestionar su actividad.
                    </p>

                </div>


                <div class="dashboard-card-body">

                    <a href="index.php?page=agenda_lista"
                       class="quick-action">

                        <div class="quick-action-icon">
                            <i class="bi bi-calendar-plus"></i>
                        </div>

                        <div class="quick-action-text">

                            <span class="quick-action-title">
                                Nueva agenda
                            </span>

                            <span class="quick-action-description">
                                Crear una nueva disponibilidad
                            </span>

                        </div>

                        <i class="bi bi-chevron-right quick-action-arrow"></i>

                    </a>


                    <a href="index.php?page=turnos"
                       class="quick-action">

                        <div class="quick-action-icon">
                            <i class="bi bi-calendar2-check"></i>
                        </div>

                        <div class="quick-action-text">

                            <span class="quick-action-title">
                                Mis turnos
                            </span>

                            <span class="quick-action-description">
                                Consultar y administrar turnos
                            </span>

                        </div>

                        <i class="bi bi-chevron-right quick-action-arrow"></i>

                    </a>


                    <a href="index.php?page=pacientes"
                       class="quick-action">

                        <div class="quick-action-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <div class="quick-action-text">

                            <span class="quick-action-title">
                                Mis pacientes
                            </span>

                            <span class="quick-action-description">
                                Consultar pacientes atendidos
                            </span>

                        </div>

                        <i class="bi bi-chevron-right quick-action-arrow"></i>

                    </a>


                    <a href="index.php?page=perfil_doctor"
                       class="quick-action">

                        <div class="quick-action-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>

                        <div class="quick-action-text">

                            <span class="quick-action-title">
                                Mi perfil profesional
                            </span>

                            <span class="quick-action-description">
                                Administrar información pública
                            </span>

                        </div>

                        <i class="bi bi-chevron-right quick-action-arrow"></i>

                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         ACTIVIDAD + PERFIL PROFESIONAL
    ========================================================== -->

    <div class="row g-4 mb-4">

        <!-- =====================================================
             ACTIVIDAD
        ====================================================== -->

        <div class="col-xl-8">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5>
                                <i class="bi bi-bar-chart-line me-2"
                                   style="color:#007DC6;"></i>
                                Actividad de atención
                            </h5>

                            <p>
                                Resumen de su actividad durante la jornada.
                            </p>

                        </div>

                        <span class="badge rounded-pill"
                              style="background:#eef5fa; color:#024296;">
                            Hoy
                        </span>

                    </div>

                </div>


                <div class="dashboard-card-body">

                    <div class="activity-bar">

                        <div class="activity-label">

                            <span>Turnos programados</span>

                            <span><?= $turnosHoy ?></span>

                        </div>

                        <div class="activity-progress">

                            <div style="width:100%;"></div>

                        </div>

                    </div>


                    <div class="activity-bar">

                        <div class="activity-label">

                            <span>Turnos atendidos</span>

                            <span><?= $turnosAtendidos ?></span>

                        </div>

                        <div class="activity-progress">

                            <div style="width:<?= $porcentajeAtencion ?>%;"></div>

                        </div>

                    </div>


                    <div class="activity-bar mb-0">

                        <div class="activity-label">

                            <span>Turnos pendientes</span>

                            <span><?= $turnosPendientes ?></span>

                        </div>

                        <div class="activity-progress">

                            <div style="width:<?= round(($turnosPendientes / max($turnosHoy, 1)) * 100) ?>%;"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             ESTADO PERFIL
        ====================================================== -->

        <div class="col-xl-4">

            <div class="profile-status h-100">

                <div class="profile-status-content">

                    <div class="profile-status-title">
                        PERFIL PROFESIONAL
                    </div>

                    <h4>
                        Su perfil está completo en un 82%
                    </h4>

                    <div class="profile-progress mb-3">

                        <div class="profile-progress-bar"></div>

                    </div>

                    <p class="small mb-3 opacity-75">
                        Complete la información profesional para ofrecer
                        a sus pacientes una mejor experiencia.
                    </p>

                    <a href="index.php?page=perfil_doctor"
                       class="btn btn-light btn-sm rounded-3 fw-semibold">

                        <i class="bi bi-pencil me-1"></i>
                        Completar perfil

                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         PROXIMAS AGENDAS + NOTIFICACIONES
    ========================================================== -->

    <div class="row g-4 mb-4">

        <!-- =====================================================
             PROXIMAS AGENDAS
        ====================================================== -->

        <div class="col-xl-7">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5>
                                <i class="bi bi-calendar-range me-2"
                                   style="color:#007DC6;"></i>
                                Próximas agendas
                            </h5>

                            <p>
                                Sus próximas jornadas de atención.
                            </p>

                        </div>

                        <a href="index.php?page=agenda_lista"
                           class="btn btn-dashboard-light">
                            Ver todas
                        </a>

                    </div>

                </div>


                <div class="dashboard-card-body">

                    <?php foreach ($proximasAgendas as $agenda): ?>

                        <div class="agenda-item">

                            <div class="d-flex align-items-center gap-3">

                                <div class="agenda-date">

                                    <div class="agenda-day">
                                        <?= htmlspecialchars($agenda['dia']) ?>
                                    </div>

                                    <div class="agenda-number">
                                        <?= htmlspecialchars($agenda['fecha']) ?>
                                    </div>

                                </div>


                                <div class="agenda-info flex-grow-1">

                                    <h6>
                                        <?= htmlspecialchars($agenda['consultorio']) ?>
                                    </h6>

                                    <span>
                                        <i class="bi bi-clock me-1"></i>
                                        <?= htmlspecialchars($agenda['horario']) ?>
                                    </span>

                                    <span>
                                        <i class="bi bi-people me-1"></i>
                                        <?= htmlspecialchars($agenda['turnos']) ?> turnos generados
                                    </span>

                                </div>


                                <span class="badge rounded-pill"
                                      style="background:rgba(25,135,84,.10); color:#198754;">

                                    Activa

                                </span>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>


        <!-- =====================================================
             NOTIFICACIONES
        ====================================================== -->

        <div class="col-xl-5">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5>
                                <i class="bi bi-bell me-2"
                                   style="color:#007DC6;"></i>
                                Notificaciones
                            </h5>

                            <p>
                                Actividad reciente de su cuenta.
                            </p>

                        </div>

                        <span class="badge bg-primary rounded-pill">
                            3
                        </span>

                    </div>

                </div>


                <div class="dashboard-card-body">

                    <?php foreach ($notificaciones as $notificacion): ?>

                        <div class="notification-item">

                            <div class="notification-icon <?= htmlspecialchars($notificacion['tipo']) ?>">

                                <i class="bi <?= htmlspecialchars($notificacion['icono']) ?>"></i>

                            </div>


                            <div>

                                <div class="notification-title">
                                    <?= htmlspecialchars($notificacion['titulo']) ?>
                                </div>

                                <div class="notification-description">
                                    <?= htmlspecialchars($notificacion['descripcion']) ?>
                                </div>

                                <div class="notification-time">
                                    <?= htmlspecialchars($notificacion['tiempo']) ?>
                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         ESTADÍSTICAS Y GRÁFICOS
    ========================================================== -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header d-flex justify-content-between align-items-center gap-3">
                    <div><h5><i class="bi bi-graph-up-arrow me-2" style="color:#007DC6;"></i>Evolución de turnos</h5><p>Comportamiento ilustrativo durante los últimos 7 días.</p></div>
                    <select class="dashboard-chart-filter" id="filtroEvolucionTurnos"><option>Últimos 7 días</option><option>Últimos 30 días</option><option>Este año</option></select>
                </div>
                <div class="dashboard-chart-body"><canvas id="graficoEvolucionTurnos"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header"><h5><i class="bi bi-pie-chart me-2" style="color:#007DC6;"></i>Estado de los turnos</h5><p>Distribución ilustrativa.</p></div>
                <div class="dashboard-chart-body compact"><canvas id="graficoEstadoTurnos"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-7">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header"><h5><i class="bi bi-people me-2" style="color:#007DC6;"></i>Pacientes nuevos y recurrentes</h5><p>Comparación ilustrativa de los últimos seis meses.</p></div>
                <div class="dashboard-chart-body"><canvas id="graficoPacientes"></canvas></div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header"><h5><i class="bi bi-speedometer2 me-2" style="color:#007DC6;"></i>Indicadores de atención</h5><p>Indicadores ilustrativos de rendimiento.</p></div>
                <div class="dashboard-card-body">
                    <div class="row g-3">
                        <div class="col-6"><div class="chart-kpi"><div class="chart-kpi-label">Ocupación</div><div class="chart-kpi-value">82%</div><div class="chart-kpi-trend"><i class="bi bi-arrow-up me-1"></i>5,4%</div></div></div>
                        <div class="col-6"><div class="chart-kpi"><div class="chart-kpi-label">Atención</div><div class="chart-kpi-value">28 min</div><div class="chart-kpi-detail">Tiempo promedio</div></div></div>
                        <div class="col-6"><div class="chart-kpi"><div class="chart-kpi-label">Cancelación</div><div class="chart-kpi-value">5,8%</div><div class="chart-kpi-detail">Sobre turnos</div></div></div>
                        <div class="col-6"><div class="chart-kpi"><div class="chart-kpi-label">Satisfacción</div><div class="chart-kpi-value">4,8/5</div><div class="chart-kpi-detail">Valoración media</div></div></div>
                    </div>
                    <div class="chart-insight mt-3"><i class="bi bi-lightbulb me-2"></i>La ocupación ilustrativa se mantiene por encima del 80%.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header"><h5><i class="bi bi-bar-chart-line me-2" style="color:#007DC6;"></i>Actividad profesional mensual</h5><p>Evolución ilustrativa de consultas y turnos completados.</p></div>
                <div class="dashboard-chart-body tall"><canvas id="graficoActividadMensual"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header"><h5><i class="bi bi-calendar3-week me-2" style="color:#007DC6;"></i>Actividad por día</h5><p>Atenciones ilustrativas por día.</p></div>
                <div class="dashboard-chart-body tall"><canvas id="graficoActividadSemanal"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-7">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header"><h5><i class="bi bi-calendar2-range me-2" style="color:#007DC6;"></i>Ocupación de agendas</h5><p>Porcentaje ilustrativo de utilización de turnos disponibles.</p></div>
                <div class="dashboard-chart-body compact"><canvas id="graficoOcupacion"></canvas></div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="dashboard-chart-card">
                <div class="dashboard-chart-header"><h5><i class="bi bi-clock-history me-2" style="color:#007DC6;"></i>Resumen operativo</h5><p>Indicadores de capacidad y atención.</p></div>
                <div class="dashboard-card-body">
                    <div class="chart-progress-row"><div class="chart-progress-label"><span>Ocupación semanal</span><span>82%</span></div><div class="chart-progress"><div class="chart-progress-bar" style="width:82%"></div></div></div>
                    <div class="chart-progress-row"><div class="chart-progress-label"><span>Turnos atendidos</span><span>78%</span></div><div class="chart-progress"><div class="chart-progress-bar" style="width:78%"></div></div></div>
                    <div class="chart-progress-row"><div class="chart-progress-label"><span>Confirmación de turnos</span><span>91%</span></div><div class="chart-progress"><div class="chart-progress-bar" style="width:91%"></div></div></div>
                    <div class="chart-progress-row mb-0"><div class="chart-progress-label"><span>Disponibilidad utilizada</span><span>76%</span></div><div class="chart-progress"><div class="chart-progress-bar" style="width:76%"></div></div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================
         RESUMEN MENSUAL
    ========================================================== -->

    <div class="row g-4">

        <div class="col-12">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5>
                                <i class="bi bi-graph-up-arrow me-2"
                                   style="color:#007DC6;"></i>
                                Resumen de actividad
                            </h5>

                            <p>
                                Indicadores generales de su actividad profesional.
                            </p>

                        </div>

                        <span class="text-muted small">
                            Últimos 30 días
                        </span>

                    </div>

                </div>


                <div class="dashboard-card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="d-flex align-items-center gap-3">

                                <div class="stat-icon primary mb-0">
                                    <i class="bi bi-people"></i>
                                </div>

                                <div>

                                    <div class="stat-title">
                                        PACIENTES ATENDIDOS
                                    </div>

                                    <div class="stat-number">
                                        <?= $pacientesAtendidosMes ?>
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="d-flex align-items-center gap-3">

                                <div class="stat-icon blue mb-0">
                                    <i class="bi bi-calendar-check"></i>
                                </div>

                                <div>

                                    <div class="stat-title">
                                        TURNOS COMPLETADOS
                                    </div>

                                    <div class="stat-number">
                                        78
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="d-flex align-items-center gap-3">

                                <div class="stat-icon success mb-0">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div>

                                    <div class="stat-title">
                                        HORAS DE ATENCIÓN
                                    </div>

                                    <div class="stat-number">
                                        42 h
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-cancelar-turno');
    if (!btn) return;

    const idAgendaTurno = btn.dataset.idAgendaTurno;

    Swal.fire({
        title: '¿Cancelar este turno?',
        text: 'El turno quedará disponible nuevamente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Volver'
    }).then((result) => {
        if (!result.isConfirmed) return;

        const formData = new FormData();
        formData.append('action', 'eliminar');
        formData.append('id_agenda_turno', idAgendaTurno);

        fetch('controladores/turno/turno_controlador.php', {
            method: 'POST',
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Cancelado', 'El turno fue cancelado.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error', data.error || 'No se pudo cancelar.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Error de red al cancelar el turno.', 'error');
            });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function(){
    if(typeof Chart==='undefined'){console.error('Chart.js no pudo cargarse.');return;}
    const C={dark:'#000967',blue:'#024296',light:'#007DC6',gray:'#8999AE',pale:'#AFCEDF'};
    Chart.defaults.font.size=11;Chart.defaults.color=C.gray;Chart.defaults.plugins.legend.labels.usePointStyle=true;
    Chart.defaults.plugins.tooltip.backgroundColor=C.dark;Chart.defaults.plugins.tooltip.padding=10;Chart.defaults.plugins.tooltip.cornerRadius=10;
    const common={x:{grid:{display:false},border:{display:false}},y:{beginAtZero:true,grid:{color:'#edf1f5'},border:{display:false},ticks:{precision:0}}};
    const meses=['Abr','May','Jun','Jul','Ago','Sep'];
    new Chart(document.getElementById('graficoEvolucionTurnos'),{type:'line',data:{labels:['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'],datasets:[{label:'Turnos',data:<?=json_encode($estadisticasDashboard['turnosSemana'])?>,borderColor:C.light,backgroundColor:'rgba(0,125,198,.10)',borderWidth:3,pointRadius:4,pointBackgroundColor:'#fff',pointBorderColor:C.light,fill:true,tension:.38}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:common}});
    new Chart(document.getElementById('graficoEstadoTurnos'),{type:'doughnut',data:{labels:['Atendidos','Confirmados','Pendientes','Cancelados'],datasets:[{data:<?=json_encode($estadisticasDashboard['estadosTurnos'])?>,backgroundColor:[C.dark,C.light,C.pale,C.gray],borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{position:'bottom',labels:{padding:12}}}}});
    new Chart(document.getElementById('graficoPacientes'),{type:'bar',data:{labels:meses,datasets:[{label:'Nuevos',data:<?=json_encode($estadisticasDashboard['pacientesNuevos'])?>,backgroundColor:C.light,borderRadius:7,maxBarThickness:24},{label:'Recurrentes',data:<?=json_encode($estadisticasDashboard['pacientesRecurrentes'])?>,backgroundColor:C.dark,borderRadius:7,maxBarThickness:24}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top',align:'end'}},scales:common}});
    new Chart(document.getElementById('graficoActividadMensual'),{type:'line',data:{labels:meses,datasets:[{label:'Consultas',data:<?=json_encode($estadisticasDashboard['consultas'])?>,borderColor:C.dark,backgroundColor:'rgba(0,9,103,.06)',borderWidth:3,pointRadius:4,pointBackgroundColor:'#fff',pointBorderColor:C.dark,fill:true,tension:.38},{label:'Turnos completados',data:<?=json_encode($estadisticasDashboard['turnosCompletados'])?>,borderColor:C.light,borderWidth:3,pointRadius:4,pointBackgroundColor:'#fff',pointBorderColor:C.light,fill:false,tension:.38}]},options:{responsive:true,maintainAspectRatio:false,interaction:{mode:'index',intersect:false},plugins:{legend:{position:'top',align:'end'}},scales:common}});
    new Chart(document.getElementById('graficoActividadSemanal'),{type:'bar',data:{labels:['Lun','Mar','Mié','Jue','Vie'],datasets:[{label:'Atenciones',data:<?=json_encode($estadisticasDashboard['actividadDias'])?>,backgroundColor:C.light,borderRadius:8,maxBarThickness:34}]},options:{indexAxis:'y',responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{beginAtZero:true,grid:{color:'#edf1f5'},border:{display:false},ticks:{precision:0}},y:{grid:{display:false},border:{display:false}}}}});
    new Chart(document.getElementById('graficoOcupacion'),{type:'line',data:{labels:['Semana 1','Semana 2','Semana 3','Semana 4','Semana 5','Semana 6'],datasets:[{label:'Ocupación',data:<?=json_encode($estadisticasDashboard['ocupacion'])?>,borderColor:C.dark,backgroundColor:'rgba(0,9,103,.07)',borderWidth:3,pointRadius:4,pointBackgroundColor:'#fff',pointBorderColor:C.dark,fill:true,tension:.35}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false},border:{display:false}},y:{min:0,max:100,grid:{color:'#edf1f5'},border:{display:false},ticks:{callback:v=>v+'%'}}}}});
    const filtro=document.getElementById('filtroEvolucionTurnos');if(filtro)filtro.addEventListener('change',()=>console.info('Período seleccionado:',filtro.value));
})();
</script>