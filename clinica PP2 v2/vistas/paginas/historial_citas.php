<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Argentina/Buenos_Aires');

$fechaActual = date('d/m/Y');
$hoyYmd = date('Y-m-d');

require_once "modelos/doctor.php";
require_once "modelos/turno.php";
require_once "modelos/cita.php";

/* ================================================================
   DATOS REALES DEL DOCTOR EN SESIÓN
   Turnos asignados (desde 30 días atrás) con su confirmación y asistencia.
   DNI y teléfono todavía no se consultan: ver Cita::listarCitasDoctor().
================================================================ */

$doctorModel = new Doctor();
$doctorSesion = $doctorModel->obtenerPorUsuarioId((int)($_SESSION['id_usuario'] ?? 0));
$idDoctor = $doctorSesion ? (int)$doctorSesion['id_doctor'] : 0;

$citaModel = new Cita();
$turnosBD = $idDoctor > 0 ? $citaModel->listarCitasDoctor($idDoctor) : [];

if (!is_array($turnosBD)) {
    $turnosBD = [];
}

$turnos = [];

foreach ($turnosBD as $r) {

    $conObra = !empty($r['con_obra_social']);

    $turnos[] = [
        'id_agenda_turno'       => (int)$r['id_agenda_turno'],
        'id_turnos'             => (int)$r['id_turnos'],
        'fecha_hora'            => $r['fecha_hora'],
        'minutos_turnos'        => (int)$r['minutos_turnos'],
        'estados_id_estados'    => (int)$r['estados_id_estados'],
        'tipo_estado'           => $r['tipo_estado'],
        'id_paciente'           => (int)$r['id_paciente'],
        'paciente_nombre'       => $r['paciente_nombre'],
        'paciente_apellido'     => $r['paciente_apellido'],
        'paciente_dni'          => $r['paciente_dni'] ?? '',
        'paciente_telefono'     => $r['paciente_telefono'] ?? '',
        'cobertura'             => $conObra ? ($r['nombre_obra_social'] ?: 'Obra social') : 'Particular',
        'motivo'                => $r['razon'] ?? '',
        'hay_confirmacion'      => !empty($r['id_confirmacion']),
        'confirmacion_paciente' => $r['confirmacion_paciente'],
        'confirmacion_doctor'   => $r['confirmacion_doctor'],
        'asistencia_paciente'   => $r['asistencia_paciente'],
        'asistencia_doctor'     => $r['asistencia_doctor'],
    ];
}

$normSeg = function ($v) {
    return $v === null ? null : (int)$v;
};

/* Pastilla de estado para el seguimiento (Pac. / Dr.) */
$pillSeg = function ($etiqueta, $valor, $textos) {
    if ($valor === null) {
        $clase = 'pend';
        $icono = 'bi-hourglass-split';
        $t = $textos[2];
    } elseif ((int)$valor === 1) {
        $clase = 'si';
        $icono = 'bi-check-circle-fill';
        $t = $textos[0];
    } else {
        $clase = 'no';
        $icono = 'bi-x-circle-fill';
        $t = $textos[1];
    }
    return '<span class="seg-pill ' . $clase . '" title="' .
        htmlspecialchars($etiqueta . ': ' . $t) . '"><i class="bi ' . $icono . '"></i>' .
        htmlspecialchars($etiqueta) . '</span>';
};


/* ================================================================
   PREPARACIÓN DE DATOS PARA LA VISTA
================================================================ */

$estilosEstado = [
    'activo'     => ['clase' => 'activo',     'color' => '#198754'],
    'confirmado' => ['clase' => 'activo',     'color' => '#198754'],
    'pendiente'  => ['clase' => 'pendiente',  'color' => '#f0ad4e'],
    'cancelado'  => ['clase' => 'cancelado',  'color' => '#dc3545'],
    'finalizado' => ['clase' => 'finalizado', 'color' => '#8999AE'],
    'atendido'   => ['clase' => 'finalizado', 'color' => '#8999AE'],
];

$meses = [
    1 => 'Ene',
    2 => 'Feb',
    3 => 'Mar',
    4 => 'Abr',
    5 => 'May',
    6 => 'Jun',
    7 => 'Jul',
    8 => 'Ago',
    9 => 'Sep',
    10 => 'Oct',
    11 => 'Nov',
    12 => 'Dic'
];

$citas = [];
$eventos = [];
$citasJs = [];
$cantHoy = 0;
$cantProximos = 0;

foreach ($turnos as $t) {

    $inicio = new DateTime($t['fecha_hora']);
    $minutos = max(1, (int)$t['minutos_turnos']);
    $fin = (clone $inicio)->modify("+{$minutos} minutes");

    $estadoTexto = trim($t['tipo_estado'] ?? 'Pendiente');
    $estilo = $estilosEstado[mb_strtolower($estadoTexto)]
        ?? ['clase' => 'pendiente', 'color' => '#007DC6'];

    $nombreCompleto = trim($t['paciente_nombre'] . ' ' . $t['paciente_apellido']);

    $iniciales = mb_strtoupper(
        mb_substr($t['paciente_nombre'], 0, 1) .
            mb_substr($t['paciente_apellido'], 0, 1)
    );

    $esHoy = $inicio->format('Y-m-d') === $hoyYmd;

    $confPac = $normSeg($t['confirmacion_paciente'] ?? null);
    $confDoc = $normSeg($t['confirmacion_doctor'] ?? null);
    $asisPac = $normSeg($t['asistencia_paciente'] ?? null);
    $asisDoc = $normSeg($t['asistencia_doctor'] ?? null);

    $hayConf = !empty($t['hay_confirmacion']);   // el aviso del día ya creó la fila de confirmación

    $estadoId = (int)$t['estados_id_estados'];

    // Reglas de la interfaz (se ajustarán al flujo real):
    $enCurso = $esHoy && $estadoId === 1;              // se confirma y se registra asistencia el día del turno
    $fichaLista = ($asisPac === 1 && $asisDoc === 1);  // la ficha se habilita con ambas asistencias
    $mostrarSeg = $enCurso || mb_strtolower($estadoTexto) === 'finalizado';

    if ($esHoy) {
        $cantHoy++;
    } else {
        $cantProximos++;
    }

    $id = (int)$t['id_agenda_turno'];

    $citas[] = [
        'id'        => $id,
        'hora'      => $inicio->format('H:i'),
        'dia'       => $inicio->format('d') . ' ' . $meses[(int)$inicio->format('n')],
        'grupo'     => $esHoy ? 'hoy' : 'proximos',
        'paciente'  => $nombreCompleto,
        'iniciales' => $iniciales,
        'minutos'   => $minutos,
        'cobertura' => $t['cobertura'],
        'estado'    => $estadoTexto,
        'clase'     => $estilo['clase'],
        'estadoId'  => (int)$t['estados_id_estados'],
        'confPac'   => $confPac,
        'confDoc'   => $confDoc,
        'asisPac'   => $asisPac,
        'asisDoc'   => $asisDoc,
        'enCurso'   => $enCurso,
        'fichaLista' => $fichaLista,
        'mostrarSeg' => $mostrarSeg,
        'hayConf'   => $hayConf,
    ];

    $eventos[] = [
        'id'              => (string)$id,
        'title'           => $nombreCompleto,
        'start'           => $inicio->format('Y-m-d\TH:i:s'),
        'end'             => $fin->format('Y-m-d\TH:i:s'),
        'backgroundColor' => $estilo['color'],
        'borderColor'     => $estilo['color'],
        'textColor'       => '#ffffff',
    ];

    // Datos que consumen el modal "Ver turno" y el offcanvas de ficha médica
    $citasJs[$id] = [
        'paciente'  => $nombreCompleto,
        'iniciales' => $iniciales,
        'dni'       => $t['paciente_dni'],
        'telefono'  => $t['paciente_telefono'],
        'cobertura' => $t['cobertura'],
        'motivo'    => $t['motivo'],
        'fecha'     => $inicio->format('d/m/Y'),
        'horario'   => $inicio->format('H:i') . ' - ' . $fin->format('H:i') . ' hs',
        'duracion'  => $minutos . ' minutos',
        'estado'    => $estadoTexto,
        'confPac'   => $confPac,
        'confDoc'   => $confDoc,
        'asisPac'   => $asisPac,
        'asisDoc'   => $asisDoc,
        'fichaLista' => $fichaLista,
    ];
}

/* Pacientes para el buscador: todos los pacientes del doctor
   (Turno::obtenerPacientesDoctor) y, si tienen, su próxima cita. */
$pacientes = [];
$ahora = new DateTime();

$turnoModel = new Turno();
$listaPacientes = $idDoctor > 0 ? $turnoModel->obtenerPacientesDoctor($idDoctor) : [];

if (!is_array($listaPacientes)) {
    $listaPacientes = [];
}

foreach ($listaPacientes as $p) {

    $pid = (int)$p['id_paciente'];

    $pacientes[$pid] = [
        'id'        => $pid,
        'nombre'    => trim($p['nombre'] . ' ' . $p['apellido']),
        'iniciales' => mb_strtoupper(mb_substr($p['nombre'], 0, 1) . mb_substr($p['apellido'], 0, 1)),
        'dni'       => '',
        'telefono'  => '',
        'cobertura' => '',
        'cantidad'  => (int)$p['cantidad_turnos'],
        'proxima'   => null,
    ];
}

foreach ($turnos as $t) {

    $pid = (int)$t['id_paciente'];

    if (!isset($pacientes[$pid])) {
        continue;
    }

    $inicio = new DateTime($t['fecha_hora']);
    $estadoId = (int)$t['estados_id_estados'];

    if ($inicio >= $ahora && in_array($estadoId, [1, 2], true) && $pacientes[$pid]['proxima'] === null) {
        $pacientes[$pid]['proxima'] = [
            'id'    => (int)$t['id_agenda_turno'],
            'fecha' => $inicio->format('Y-m-d'),
            'texto' => $inicio->format('d/m') . ' · ' . $inicio->format('H:i'),
        ];
    }
}

$jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP;

$eventosJson = json_encode($eventos, $jsonFlags) ?: '[]';
$citasJson = json_encode($citasJs, $jsonFlags) ?: '{}';
$pacientesJson = json_encode(array_values($pacientes), $jsonFlags) ?: '[]';
?>

<head>
    <link rel="stylesheet" href="assets/css/historial_citas.css">
</head>

<div class="historial-doctor container-fluid py-4 px-4">

    <!-- ENCABEZADO -->
    <div class="dashboard-header mb-4">
        <div class="row align-items-center">

            <div class="col-lg-8">
                <h1 class="h3 mb-1">Historial de citas</h1>
                <p>Consulte los turnos del día y los próximos desde el calendario.</p>
            </div>

            <div class="col-lg-4 d-flex justify-content-lg-end">
                <div class="dashboard-date">
                    <i class="bi bi-calendar3 me-2"></i>
                    <?= htmlspecialchars($fechaActual) ?>
                </div>
            </div>

        </div>
    </div>

    <!-- BUSCADOR DE PACIENTES -->
    <div class="buscador-pacientes mb-4">

        <div class="buscador-caja">
            <i class="bi bi-search"></i>

            <input type="search"
                id="buscadorPacientes"
                class="buscador-input"
                placeholder="Buscar paciente por nombre, DNI o teléfono"
                autocomplete="off"
                role="combobox"
                aria-expanded="false"
                aria-controls="buscadorResultados"
                aria-label="Buscar paciente">

            <button type="button" class="buscador-limpiar" id="buscadorLimpiar"
                aria-label="Limpiar búsqueda" style="display:none;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="buscador-resultados" id="buscadorResultados" role="listbox" style="display:none;"></div>

    </div>

    <div class="row g-4">

        <!-- =====================================================
             CALENDARIO (aprox. 3/4)
        ====================================================== -->
        <div class="col-xl-9">

            <div class="dashboard-card">

                <div class="dashboard-card-header">
                    <h5>
                        <i class="bi bi-calendar3 me-2" style="color:#007DC6;"></i>
                        Calendario de citas
                    </h5>
                    <p>Seleccione una cita para ver los datos del paciente y del turno.</p>
                </div>

                <div class="calendario-body">

                    <div id="calendarioDoctor"></div>

                    <div class="calendario-leyenda">
                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot dot-activo"></span>
                            Activo / confirmado
                        </div>
                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot dot-pendiente"></span>
                            Pendiente
                        </div>
                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot dot-cancelado"></span>
                            Cancelado
                        </div>
                        <div class="calendario-leyenda-item">
                            <span class="calendario-leyenda-dot dot-finalizado"></span>
                            Finalizado
                        </div>
                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             COLUMNA DE CITAS (aprox. 1/4)
        ====================================================== -->
        <div class="col-xl-3">

            <div class="dashboard-card citas-card">

                <div class="dashboard-card-header">

                    <h5>
                        <i class="bi bi-list-check me-2" style="color:#007DC6;"></i>
                        Citas
                    </h5>
                    <p>Gestione cada turno desde esta lista.</p>

                    <div class="citas-filtros">
                        <button type="button" class="cita-filtro activo" data-filtro="hoy">
                            Hoy<span class="cita-filtro-n">(<?= $cantHoy ?>)</span>
                        </button>
                        <button type="button" class="cita-filtro" data-filtro="proximos">
                            Próximas<span class="cita-filtro-n">(<?= $cantProximos ?>)</span>
                        </button>
                        <button type="button" class="cita-filtro" data-filtro="todos">
                            Todas
                        </button>
                    </div>

                </div>

                <div class="citas-lista" id="citasLista">

                    <?php foreach ($citas as $c): ?>

                        <div class="cita-item estado-<?= $c['clase'] ?>"
                            data-id="<?= $c['id'] ?>"
                            data-grupo="<?= $c['grupo'] ?>">

                            <div class="cita-top">

                                <div class="cita-hora">
                                    <?= htmlspecialchars($c['hora']) ?>
                                    <small><?= htmlspecialchars($c['dia']) ?></small>
                                </div>

                                <div class="cita-avatar">
                                    <?= htmlspecialchars($c['iniciales']) ?>
                                </div>

                                <div class="cita-info">
                                    <div class="cita-paciente">
                                        <?= htmlspecialchars($c['paciente']) ?>
                                    </div>
                                    <div class="cita-detalle">
                                        <?= (int)$c['minutos'] ?> min · <?= htmlspecialchars($c['cobertura']) ?>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-2">
                                <span class="estado-turno estado-<?= $c['clase'] ?>">
                                    <?= htmlspecialchars($c['estado']) ?>
                                </span>
                            </div>

                            <?php if ($c['mostrarSeg']): ?>
                                <div class="cita-seguimiento">

                                    <div class="seg-fila">
                                        <span class="seg-titulo">Confirmación</span>
                                        <span class="seg-grupo">
                                            <?= $pillSeg('Pac.', $c['confPac'], ['Confirmó', 'No asistirá', 'Sin respuesta']) ?>
                                            <?= $pillSeg('Dr.', $c['confDoc'], ['Confirmado', 'No asistirá', 'Pendiente']) ?>
                                        </span>
                                    </div>

                                    <div class="seg-fila">
                                        <span class="seg-titulo">Asistencia</span>
                                        <span class="seg-grupo">
                                            <?= $pillSeg('Pac.', $c['asisPac'], ['Presente', 'Ausente', 'Sin registrar']) ?>
                                            <?= $pillSeg('Dr.', $c['asisDoc'], ['Presente', 'Ausente', 'Sin registrar']) ?>
                                        </span>
                                    </div>

                                </div>
                            <?php endif; ?>

                            <?php
                            $mostrarConfirmar = $c['enCurso'] && $c['hayConf'] && $c['confDoc'] === null;
                            $mostrarMiAsistencia = $c['enCurso'] && $c['asisDoc'] === null;
                            $mostrarAsistPaciente = $c['enCurso'] && $c['asisPac'] === null;
                            ?>

                            <?php if ($mostrarConfirmar || $mostrarMiAsistencia || $mostrarAsistPaciente): ?>
                                <div class="cita-paso">

                                    <?php if ($mostrarConfirmar): ?>
                                        <button type="button" class="btn-paso btn-paso-cita"
                                            data-paso="confirmar" data-id="<?= $c['id'] ?>">
                                            Confirmar turno
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($mostrarMiAsistencia): ?>
                                        <button type="button" class="btn-paso principal btn-paso-cita"
                                            data-paso="asistencia" data-id="<?= $c['id'] ?>">
                                            Marcar mi asistencia
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($mostrarAsistPaciente): ?>
                                        <button type="button" class="btn-paso ancho btn-paso-cita"
                                            data-paso="asistencia_paciente" data-id="<?= $c['id'] ?>">
                                            Registrar asistencia del paciente
                                        </button>
                                    <?php endif; ?>

                                </div>
                            <?php endif; ?>

                            <div class="cita-acciones">

                                <button type="button" class="btn-accion ver btn-ver-cita"
                                    data-id="<?= $c['id'] ?>" title="Ver turno">
                                    <i class="bi bi-eye"></i> Ver
                                </button>

                                <button type="button"
                                    class="btn-accion ficha btn-ficha-cita<?= $c['fichaLista'] ? '' : ' bloqueado' ?>"
                                    data-id="<?= $c['id'] ?>"
                                    title="<?= $c['fichaLista'] ? 'Ficha médica' : 'Se habilita cuando el paciente y usted registran su asistencia' ?>"
                                    <?= $c['fichaLista'] ? '' : 'aria-disabled="true"' ?>>
                                    <i class="bi <?= $c['fichaLista'] ? 'bi-file-earmark-medical' : 'bi-lock' ?>"></i> Ficha
                                </button>

                                <?php if ($c['estadoId'] === 1): ?>
                                    <button type="button" class="btn-accion finalizar btn-finalizar-cita"
                                        data-id="<?= $c['id'] ?>" title="Finalizar cita">
                                        <i class="bi bi-check2-circle"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if (in_array($c['estadoId'], [1, 2], true)): ?>
                                    <button type="button" class="btn-accion cancelar btn-cancelar-cita"
                                        data-id="<?= $c['id'] ?>" title="Cancelar cita">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                    <div class="citas-vacio" id="citasVacio" style="display:none;">
                        <i class="bi bi-calendar-x"></i>
                        No hay citas para mostrar en esta categoría.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     OFFCANVAS FICHA MÉDICA (2/4 de la pantalla)
========================================================== -->
<div class="offcanvas offcanvas-end offcanvas-ficha" tabindex="-1"
    id="offcanvasFichaMedica" aria-labelledby="offcanvasFichaMedicaLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasFichaMedicaLabel">
            <i class="bi bi-file-earmark-medical me-2"></i>
            Ficha médica
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
    </div>

    <div class="offcanvas-body">

        <form id="formFicha" autocomplete="off" onsubmit="return false;">

            <!-- Paciente -->
            <div class="ficha-paciente">
                <div class="ficha-avatar" id="fichaIniciales">--</div>
                <div>
                    <div class="ficha-nombre" id="fichaNombre">Paciente</div>
                    <div class="ficha-meta" id="fichaMeta">DNI · Cobertura</div>
                </div>
            </div>

            <div class="ficha-aviso">
                <i class="bi bi-info-circle-fill"></i>
                <span>Vista de ejemplo. Los datos reales se cargarán desde la base de datos.</span>
            </div>

            <!-- Cita actual (tabla cita) -->
            <div class="ficha-seccion">
                <div class="ficha-seccion-titulo">
                    <i class="bi bi-calendar2-check"></i> Cita actual
                </div>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="ficha-dato-label">Fecha</div>
                        <div class="ficha-dato-valor" id="fichaFecha">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="ficha-dato-label">Horario</div>
                        <div class="ficha-dato-valor" id="fichaHorario">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="ficha-dato-label">Duración</div>
                        <div class="ficha-dato-valor" id="fichaDuracion">-</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="ficha-dato-label">Estado</div>
                        <div class="ficha-dato-valor" id="fichaEstado">-</div>
                    </div>
                    <div class="col-12">
                        <label class="ficha-label" for="fichaMotivo">Motivo de consulta</label>
                        <textarea class="form-control" id="fichaMotivo" rows="2" maxlength="500"
                            placeholder="Motivo por el que el paciente consulta."></textarea>
                        <div class="ficha-ayuda">El paciente también puede cargar o modificar este motivo.</div>
                    </div>
                </div>
            </div>

            <!-- Antecedentes y alertas (datos de persona) -->
            <div class="ficha-seccion">
                <div class="ficha-seccion-titulo">
                    <i class="bi bi-clipboard2-pulse"></i> Antecedentes y alertas
                </div>

                <div id="fichaAntecedentes">
                    <span class="ficha-chip alerta">Alergia a la penicilina</span>
                    <span class="ficha-chip">Hipertensión</span>
                </div>

                <div class="ficha-ayuda">
                    Estos datos los completa el paciente desde su perfil.
                    Si no los cargó, puede agregarlos aquí.
                </div>

                <button type="button" class="ficha-toggle"
                    data-bs-toggle="collapse" data-bs-target="#fichaAntecedentesForm"
                    aria-expanded="false" aria-controls="fichaAntecedentesForm">
                    <i class="bi bi-plus-circle me-1"></i> Completar o editar antecedentes
                </button>

                <div class="collapse mt-3" id="fichaAntecedentesForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="ficha-label" for="fichaAlergias">Alergias</label>
                            <textarea class="form-control" id="fichaAlergias" rows="2">Alergia a la penicilina</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="ficha-label" for="fichaCondiciones">Condiciones o enfermedades</label>
                            <textarea class="form-control" id="fichaCondiciones" rows="2">Hipertensión</textarea>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn-ficha-primario" id="btnGuardarAntecedentes">
                            Guardar en el perfil del paciente
                        </button>
                    </div>
                </div>
            </div>

            <!-- Datos de la consulta (tablas ficha_medica y signos_vitales) -->
            <div class="ficha-seccion">
                <div class="ficha-seccion-titulo">
                    <i class="bi bi-heart-pulse"></i> Datos de la consulta
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="fichaUsaSignos">
                    <label class="form-check-label ficha-switch-label" for="fichaUsaSignos">
                        Registrar medidas y signos vitales en esta consulta
                    </label>
                </div>

                <div class="row g-3" id="fichaSignosBloque" style="display:none;">
                    <div class="col-6 col-md-3">
                        <label class="ficha-label" for="fichaAltura">Altura (cm)</label>
                        <input type="number" class="form-control" id="fichaAltura" min="30" max="250" step="1">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="ficha-label" for="fichaPeso">Peso (kg)</label>
                        <input type="number" class="form-control" id="fichaPeso" min="1" max="400" step="0.1">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="ficha-label" for="fichaImc">IMC</label>
                        <input type="text" class="form-control" id="fichaImc" readonly placeholder="Automático">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="ficha-label" for="fichaPresion">Presión (mmHg)</label>
                        <input type="text" class="form-control" id="fichaPresion" placeholder="120/80">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="ficha-label" for="fichaFc">Frec. cardíaca (lpm)</label>
                        <input type="number" class="form-control" id="fichaFc" min="20" max="250">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="ficha-label" for="fichaTemp">Temperatura (°C)</label>
                        <input type="number" class="form-control" id="fichaTemp" min="30" max="45" step="0.1">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="ficha-label" for="fichaSat">Saturación O₂ (%)</label>
                        <input type="number" class="form-control" id="fichaSat" min="50" max="100">
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-12">
                        <label class="ficha-label" for="fichaMedicacion">Medicación actual</label>
                        <textarea class="form-control" id="fichaMedicacion" rows="2" maxlength="500"
                            placeholder="Medicamentos que toma actualmente y su dosis."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="ficha-label" for="fichaObservaciones">Observaciones</label>
                        <textarea class="form-control" id="fichaObservaciones" rows="3" maxlength="500"
                            placeholder="Evolución y hallazgos de esta consulta."></textarea>
                    </div>
                </div>
            </div>

            <!-- Diagnóstico e indicaciones -->
            <div class="ficha-seccion">
                <div class="ficha-seccion-titulo">
                    <i class="bi bi-prescription2"></i> Diagnóstico e indicaciones
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="ficha-label" for="fichaDiagnostico">Diagnóstico</label>
                        <textarea class="form-control" id="fichaDiagnostico" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="ficha-label" for="fichaIndicaciones">Indicaciones</label>
                        <textarea class="form-control" id="fichaIndicaciones" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="ficha-label" for="fichaTratamiento">Tratamiento</label>
                        <textarea class="form-control" id="fichaTratamiento" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="ficha-label" for="fichaProximoControl">Próximo control</label>
                        <input type="date" class="form-control" id="fichaProximoControl">
                    </div>
                </div>
            </div>

            <!-- Historial -->
            <div class="ficha-seccion">
                <div class="ficha-seccion-titulo">
                    <i class="bi bi-clock-history"></i> Consultas anteriores
                </div>
                <div class="ficha-timeline" id="fichaAnteriores"></div>
                <div class="ficha-ayuda" id="fichaAnterioresVacio" style="display:none;">
                    Este paciente no tiene consultas anteriores registradas con usted.
                </div>
            </div>

        </form>

    </div>

    <div class="offcanvas-footer">
        <button type="button" class="btn-ficha-claro" data-bs-dismiss="offcanvas">Cerrar</button>
        <button type="button" class="btn-ficha-primario" id="btnGuardarFicha">
            <i class="bi bi-save me-1"></i> Guardar ficha
        </button>
        <button type="button" class="btn-ficha-exito" id="btnFinalizarDesdeFicha">
            <i class="bi bi-check2-circle me-1"></i> Finalizar cita
        </button>
    </div>

</div>


<!-- =========================================================
     MODAL VER TURNO
========================================================== -->
<div class="modal fade" id="modalVerCita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-cita">

            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="cita-modal-icon">
                        <i class="bi bi-calendar2-week"></i>
                    </div>
                    <div>
                        <h5 class="cita-modal-title">Detalle de la cita</h5>
                        <div class="cita-modal-subtitle" id="verSubtitulo">Datos del paciente y del turno</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">

                <div class="cita-modal-seccion">
                    <div class="cita-modal-seccion-titulo">
                        <i class="bi bi-person"></i> Paciente
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Nombre</div>
                                <div class="cita-dato-valor" id="verPaciente">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">DNI</div>
                                <div class="cita-dato-valor" id="verDni">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Teléfono</div>
                                <div class="cita-dato-valor" id="verTelefono">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Cobertura</div>
                                <div class="cita-dato-valor" id="verCobertura">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cita-modal-seccion">
                    <div class="cita-modal-seccion-titulo">
                        <i class="bi bi-calendar2-check"></i> Turno
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Fecha</div>
                                <div class="cita-dato-valor" id="verFecha">-</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Horario</div>
                                <div class="cita-dato-valor" id="verHorario">-</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Duración</div>
                                <div class="cita-dato-valor" id="verDuracion">-</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Estado</div>
                                <div class="cita-dato-valor" id="verEstado">-</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Motivo de consulta</div>
                                <div class="cita-dato-valor" id="verMotivo">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cita-modal-seccion">
                    <div class="cita-modal-seccion-titulo">
                        <i class="bi bi-patch-check"></i> Seguimiento de la cita
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Confirmación del paciente</div>
                                <div class="cita-dato-valor" id="verConfPac">-</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Su confirmación</div>
                                <div class="cita-dato-valor" id="verConfDoc">-</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Asistencia del paciente</div>
                                <div class="cita-dato-valor" id="verAsisPac">-</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="cita-dato">
                                <div class="cita-dato-label">Su asistencia</div>
                                <div class="cita-dato-valor" id="verAsisDoc">-</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-claro" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn-modal-primario" id="btnVerFicha">
                    <i class="bi bi-file-earmark-medical me-1"></i> Abrir ficha médica
                </button>
            </div>

        </div>
    </div>
</div>


<script src="assets/js/sweetalert2@11.js"></script>
<!-- FullCalendar local (mismo bundle que usa miperfil_paciente) -->
<script src="assets/vendor/fullcalendar/index.global.min.js"></script>
<script>
    (function() {

        const eventos = <?= $eventosJson ?>;
        const citas = <?= $citasJson ?>;
        const pacientes = <?= $pacientesJson ?>;

        let calendario = null;
        let citaActualId = null;

        const $ = function(id) {
            return document.getElementById(id);
        };

        function el(tag, clase, txt) {
            const n = document.createElement(tag);
            if (clase) {
                n.className = clase;
            }
            if (txt !== undefined) {
                n.textContent = txt;
            }
            return n;
        }

        function texto(id, valor) {
            const nodo = $(id);
            if (nodo) {
                nodo.textContent = valor || '-';
            }
        }

        function aviso(titulo, mensaje, icono) {
            if (typeof Swal !== 'undefined') {
                Swal.fire(titulo, mensaje, icono || 'info');
            }
        }

        /* ------------------------------------------------------------
           CALENDARIO
        ------------------------------------------------------------ */
        function iniciarCalendario() {

            const contenedor = $('calendarioDoctor');

            if (!contenedor || typeof FullCalendar === 'undefined' || calendario) {
                return;
            }

            calendario = new FullCalendar.Calendar(contenedor, {
                initialView: 'timeGridWeek',
                locale: 'es',
                firstDay: 1,
                timeZone: 'America/Argentina/Buenos_Aires',
                height: '100%',
                nowIndicator: true,
                allDaySlot: false,
                slotMinTime: '07:00:00',
                slotMaxTime: '21:00:00',
                slotDuration: '00:30:00',
                expandRows: true,
                dayMaxEvents: 3,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                },
                noEventsText: 'No hay citas para mostrar.',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                events: eventos,
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    abrirVerCita(info.event.id);
                }
            });

            calendario.render();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', iniciarCalendario);
        } else {
            iniciarCalendario();
        }

        /* ------------------------------------------------------------
           FILTROS DE LA COLUMNA (Hoy / Próximas / Todas)
        ------------------------------------------------------------ */
        function aplicarFiltro(filtro) {

            let visibles = 0;

            document.querySelectorAll('#citasLista .cita-item').forEach(function(item) {
                const mostrar = filtro === 'todos' || item.dataset.grupo === filtro;
                item.style.display = mostrar ? '' : 'none';
                if (mostrar) {
                    visibles++;
                }
            });

            $('citasVacio').style.display = visibles === 0 ? '' : 'none';

            document.querySelectorAll('.cita-filtro').forEach(function(btn) {
                btn.classList.toggle('activo', btn.dataset.filtro === filtro);
            });
        }

        document.querySelectorAll('.cita-filtro').forEach(function(btn) {
            btn.addEventListener('click', function() {
                aplicarFiltro(btn.dataset.filtro);
            });
        });

        aplicarFiltro('hoy');

        function resaltarCita(id) {

            const item = document.querySelector('#citasLista .cita-item[data-id="' + id + '"]');

            if (!item) {
                return;
            }

            item.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            item.classList.remove('resaltada');
            void item.offsetWidth;
            item.classList.add('resaltada');
        }

        /* ------------------------------------------------------------
           BUSCADOR DE PACIENTES
           Hoy filtra el arreglo "pacientes" en el navegador. Con datos
           reales, filtrarPacientes() se reemplaza por un fetch a un
           endpoint que devuelva solo los pacientes de este doctor.
        ------------------------------------------------------------ */
        const inputBusqueda = $('buscadorPacientes');
        const panelBusqueda = $('buscadorResultados');
        const btnLimpiar = $('buscadorLimpiar');

        let resultados = [];
        let indiceActivo = -1;

        function normalizar(valor) {
            return String(valor || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase();
        }

        function filtrarPacientes(consulta) {

            const palabras = normalizar(consulta).split(/\s+/).filter(Boolean);

            return pacientes.filter(function(p) {

                const pajar = normalizar(
                    p.nombre + ' ' + p.dni + ' ' + String(p.dni).replace(/\D/g, '') +
                    ' ' + p.telefono + ' ' + String(p.telefono).replace(/\D/g, '')
                );

                return palabras.every(function(w) {
                    return pajar.indexOf(w) !== -1;
                });

            }).slice(0, 8);
        }

        function cerrarPanel() {
            panelBusqueda.style.display = 'none';
            panelBusqueda.innerHTML = '';
            inputBusqueda.setAttribute('aria-expanded', 'false');
            resultados = [];
            indiceActivo = -1;
        }

        function marcarActivo() {
            panelBusqueda.querySelectorAll('.res-item').forEach(function(fila, i) {
                fila.classList.toggle('activo', i === indiceActivo);
                if (i === indiceActivo) {
                    fila.scrollIntoView({
                        block: 'nearest'
                    });
                }
            });
        }

        function pintarResultados(consulta) {

            const q = consulta.trim();

            panelBusqueda.innerHTML = '';
            indiceActivo = -1;

            if (q.length < 2) {
                cerrarPanel();
                return;
            }

            resultados = filtrarPacientes(q);

            if (resultados.length === 0) {
                panelBusqueda.appendChild(
                    el('div', 'res-vacio', 'No se encontró ningún paciente para «' + q + '».')
                );
            }

            resultados.forEach(function(p, i) {

                const fila = el('div', 'res-item');
                fila.setAttribute('role', 'option');
                fila.dataset.indice = i;

                fila.appendChild(el('div', 'res-avatar', p.iniciales));

                const info = el('div', 'res-info');
                info.appendChild(el('div', 'res-nombre', p.nombre));
                const partes = [];

                if (p.dni) {
                    partes.push('DNI ' + p.dni);
                }
                if (p.cobertura) {
                    partes.push(p.cobertura);
                }
                if (!partes.length) {
                    partes.push(p.cantidad + (p.cantidad === 1 ? ' turno' : ' turnos'));
                }

                info.appendChild(el('div', 'res-detalle', partes.join(' · ')));
                fila.appendChild(info);

                if (p.proxima) {
                    fila.appendChild(el('span', 'res-meta proxima', 'Próxima: ' + p.proxima.texto));
                } else {
                    fila.appendChild(el('span', 'res-meta', 'Sin citas próximas'));
                }

                panelBusqueda.appendChild(fila);
            });

            panelBusqueda.style.display = '';
            inputBusqueda.setAttribute('aria-expanded', 'true');
        }

        function elegirPaciente(p) {

            cerrarPanel();
            inputBusqueda.value = p.nombre;
            btnLimpiar.style.display = '';

            if (p.proxima) {
                aplicarFiltro('todos');

                if (calendario) {
                    calendario.gotoDate(p.proxima.fecha);
                }

                resaltarCita(p.proxima.id);
                return;
            }

            aviso(
                p.nombre,
                'No tiene citas próximas.' +
                (p.ultima ? ' Última visita: ' + p.ultima + '.' : '') +
                ' El historial completo del paciente se conectará más adelante.',
                'info'
            );
        }

        if (inputBusqueda) {

            inputBusqueda.addEventListener('input', function() {
                btnLimpiar.style.display = inputBusqueda.value ? '' : 'none';
                pintarResultados(inputBusqueda.value);
            });

            inputBusqueda.addEventListener('keydown', function(e) {

                if (e.key === 'Escape') {
                    cerrarPanel();
                    return;
                }

                if (!resultados.length) {
                    return;
                }

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    indiceActivo = (indiceActivo + 1) % resultados.length;
                    marcarActivo();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    indiceActivo = (indiceActivo - 1 + resultados.length) % resultados.length;
                    marcarActivo();
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    elegirPaciente(resultados[indiceActivo >= 0 ? indiceActivo : 0]);
                }
            });

            panelBusqueda.addEventListener('click', function(e) {
                const fila = e.target.closest('.res-item');
                if (fila) {
                    elegirPaciente(resultados[parseInt(fila.dataset.indice, 10)]);
                }
            });

            btnLimpiar.addEventListener('click', function() {
                inputBusqueda.value = '';
                btnLimpiar.style.display = 'none';
                cerrarPanel();
                inputBusqueda.focus();
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.buscador-pacientes')) {
                    cerrarPanel();
                }
            });
        }

        /* ------------------------------------------------------------
           SEGUIMIENTO (confirmación y asistencia)
        ------------------------------------------------------------ */
        const ETIQUETAS = {
            confPac: ['Confirmó', 'No asistirá', 'Sin respuesta'],
            confDoc: ['Confirmado', 'No asistirá', 'Pendiente'],
            asisPac: ['Presente', 'Ausente', 'Sin registrar'],
            asisDoc: ['Presente', 'Ausente', 'Sin registrar']
        };

        function etiqueta(clave, valor) {
            const e = ETIQUETAS[clave];
            return valor === 1 ? e[0] : (valor === 0 ? e[1] : e[2]);
        }

        /* ------------------------------------------------------------
           VER TURNO (modal)
        ------------------------------------------------------------ */
        function abrirVerCita(id) {

            const c = citas[id];

            if (!c || typeof bootstrap === 'undefined') {
                return;
            }

            citaActualId = id;

            texto('verPaciente', c.paciente);
            texto('verDni', c.dni);
            texto('verTelefono', c.telefono);
            texto('verCobertura', c.cobertura);
            texto('verFecha', c.fecha);
            texto('verHorario', c.horario);
            texto('verDuracion', c.duracion);
            texto('verEstado', c.estado);
            texto('verMotivo', c.motivo);

            texto('verConfPac', etiqueta('confPac', c.confPac));
            texto('verConfDoc', etiqueta('confDoc', c.confDoc));
            texto('verAsisPac', etiqueta('asisPac', c.asisPac));
            texto('verAsisDoc', etiqueta('asisDoc', c.asisDoc));

            $('btnVerFicha').classList.toggle('bloqueado', !c.fichaLista);

            bootstrap.Modal.getOrCreateInstance($('modalVerCita')).show();
        }

        /* ------------------------------------------------------------
   COMUNICACIÓN CON ficha_medica.php
------------------------------------------------------------ */
        function pedirFicha(parametros) {

            return fetch('controladores/ficha/ficha_medica_controlador.php', {
                    method: 'POST',
                    body: new URLSearchParams(parametros)
                })
                .then(function(resp) {
                    return resp.json();
                })
                .catch(function() {
                    return {
                        success: false,
                        error: 'Error de red. Intente nuevamente.'
                    };
                });
        }

        function numero(valor) {
            return (valor === null || valor === undefined || valor === '') ? '' : parseFloat(valor);
        }

        /* ------------------------------------------------------------
           FICHA MÉDICA (offcanvas)
        ------------------------------------------------------------ */
        function calcularImc() {

            const altura = parseFloat($('fichaAltura').value);
            const peso = parseFloat($('fichaPeso').value);

            $('fichaImc').value = (altura > 0 && peso > 0) ?
                (peso / Math.pow(altura / 100, 2)).toFixed(1) :
                '';
        }

        ['fichaAltura', 'fichaPeso'].forEach(function(id) {
            $(id).addEventListener('input', calcularImc);
        });

        function mostrarSignos(visible) {
            $('fichaUsaSignos').checked = visible;
            $('fichaSignosBloque').style.display = visible ? '' : 'none';
        }

        $('fichaUsaSignos').addEventListener('change', function() {
            mostrarSignos(this.checked);
        });

        /* lista de consultas anteriores del paciente */
        function pintarAnteriores(lista) {

            const contenedor = $('fichaAnteriores');

            contenedor.innerHTML = '';

            lista.forEach(function(a) {

                const item = el('div', 'ficha-timeline-item');

                item.appendChild(el('div', 'ficha-timeline-fecha', a.fecha));

                [
                    ['Motivo', a.motivo],
                    ['Diagnóstico', a.diagnostico],
                    ['Indicaciones', a.indicaciones],
                    ['Tratamiento', a.tratamiento],
                    ['Observaciones', a.observaciones]
                ].forEach(function(par) {

                    if (!par[1]) {
                        return;
                    }

                    const linea = el('div', 'ficha-timeline-texto');
                    linea.appendChild(el('strong', null, par[0] + ': '));
                    linea.appendChild(document.createTextNode(par[1]));
                    item.appendChild(linea);
                });

                contenedor.appendChild(item);
            });

            $('fichaAnterioresVacio').style.display = lista.length ? 'none' : '';
        }

        /* Pide al servidor la ficha de la cita y completa el formulario */
        function cargarFicha(id) {

            pedirFicha({
                action: 'obtener',
                id_agenda_turno: id
            }).then(function(data) {

                if (!data || !data.success) {
                    aviso(
                        'No se pudo cargar la ficha',
                        (data && data.error) ? data.error : 'Ocurrió un error inesperado.',
                        'error'
                    );
                    return;
                }

                // Evita pisar el formulario si ya se abrió otra cita
                if (citaActualId !== id) {
                    return;
                }

                const f = data.ficha || {};
                const s = data.signos;

                $('fichaMotivo').value = data.motivo || '';

                $('fichaAltura').value = numero(f.altura);
                $('fichaPeso').value = numero(f.peso);
                $('fichaMedicacion').value = f.medicacion_actual || '';
                $('fichaObservaciones').value = f.observaciones || '';
                $('fichaDiagnostico').value = f.diagnostico || '';
                $('fichaIndicaciones').value = f.indicaciones || '';
                $('fichaTratamiento').value = f.tratamiento || '';
                $('fichaProximoControl').value = f.proximo_control || '';

                if (s) {
                    $('fichaPresion').value = (s.presion_sistolica && s.presion_diastolica) ?
                        s.presion_sistolica + '/' + s.presion_diastolica :
                        '';
                    $('fichaFc').value = numero(s.frecuencia_cardiaca);
                    $('fichaTemp').value = numero(s.temperatura);
                    $('fichaSat').value = numero(s.saturacion_oxigeno);
                }

                pintarAnteriores(data.anteriores || []);

                calcularImc();
                mostrarSignos(!!s || !!f.altura || !!f.peso);
            });
        }

        function abrirFicha(id) {

            const c = citas[id];

            if (!c || typeof bootstrap === 'undefined') {
                return;
            }

            if (!c.fichaLista) {
                aviso(
                    'Ficha médica bloqueada',
                    'Se habilita cuando el paciente y usted registran su asistencia.',
                    'info'
                );
                return;
            }

            citaActualId = id;

            // Siempre se parte de un formulario limpio y se carga lo guardado
            $('formFicha').reset();
            mostrarSignos(false);
            calcularImc();
            $('fichaAnteriores').innerHTML = '';
            $('fichaAnterioresVacio').style.display = 'none';

            texto('fichaIniciales', c.iniciales);
            texto('fichaNombre', c.paciente);
            texto('fichaMeta', (c.dni ? 'DNI ' + c.dni + ' · ' : '') + c.cobertura);
            texto('fichaFecha', c.fecha);
            texto('fichaHorario', c.horario);
            texto('fichaDuracion', c.duracion);
            texto('fichaEstado', c.estado);

            $('fichaMotivo').value = c.motivo || '';

            bootstrap.Offcanvas.getOrCreateInstance($('offcanvasFichaMedica')).show();

            cargarFicha(id);
        }



        /* ------------------------------------------------------------
           ACCIONES (confirmación y asistencia conectadas;
           finalizar y cancelar siguen como ejemplo)
        ------------------------------------------------------------ */
        function confirmarAccion(opciones) {

            if (typeof Swal === 'undefined') {
                return;
            }

            Swal.fire({
                title: opciones.titulo,
                text: opciones.texto,
                icon: opciones.icono,
                showCancelButton: true,
                confirmButtonText: opciones.confirmar,
                cancelButtonText: 'Volver',
                reverseButtons: true
            }).then(function(r) {

                if (!r.isConfirmed) {
                    return;
                }

                // TODO: acá se conectará con el controlador.
                Swal.fire(
                    'Interfaz de ejemplo',
                    'La acción todavía no está conectada al servidor.',
                    'info'
                );
            });
        }

        /* Envía una acción a cita_controlador.php y recarga si salió bien */
        function enviarCita(parametros, mensajeExito) {

            const datos = new URLSearchParams(parametros);

            return fetch('controladores/cita/cita_controlador.php', {
                    method: 'POST',
                    body: datos
                })
                .then(function(resp) {
                    return resp.json();
                })
                .then(function(data) {

                    if (data && data.success) {
                        return Swal.fire('Listo', mensajeExito, 'success').then(function() {
                            location.reload();
                        });
                    }

                    aviso(
                        'No se pudo completar',
                        (data && data.error) ? data.error : 'Ocurrió un error inesperado.',
                        'error'
                    );
                })
                .catch(function() {
                    aviso('Error', 'Error de red. Intente nuevamente.', 'error');
                });
        }

        // Confirmación del doctor (la fila la crea el aviso diario)
        function pasoConfirmar(id) {

            const c = citas[id];

            if (!c || typeof Swal === 'undefined') {
                return;
            }

            Swal.fire({
                title: '¿Confirma su asistencia?',
                text: 'Cita de hoy, ' + c.horario + ', con ' + c.paciente + '.',
                icon: 'question',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Sí, asistiré',
                denyButtonText: 'No podré asistir',
                cancelButtonText: 'Volver'
            }).then(function(r) {

                if (r.isConfirmed) {
                    enviarCita({
                            action: 'confirmar',
                            id_agenda_turno: id,
                            respuesta: 1
                        },
                        'Su asistencia quedó confirmada.'
                    );
                } else if (r.isDenied) {
                    enviarCita({
                            action: 'confirmar',
                            id_agenda_turno: id,
                            respuesta: 0
                        },
                        'Se avisó al paciente que usted no podrá asistir.'
                    );
                }
            });
        }

        // Asistencia: parte = 'doctor' (la suya) o 'paciente' (cuando no hizo su check)
        function pasoAsistencia(id, parte) {

            const c = citas[id];

            if (!c || typeof Swal === 'undefined') {
                return;
            }

            if (parte === 'paciente') {

                Swal.fire({
                    title: 'Asistencia del paciente',
                    text: c.paciente + ' todavía no registró su asistencia. ¿Se presentó a la cita?',
                    icon: 'question',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Sí, está presente',
                    denyButtonText: 'No se presentó',
                    cancelButtonText: 'Volver'
                }).then(function(r) {

                    if (r.isConfirmed) {
                        enviarCita({
                                action: 'asistencia',
                                id_agenda_turno: id,
                                valor: 1,
                                parte: 'paciente'
                            },
                            'Se registró al paciente como presente.'
                        );
                    } else if (r.isDenied) {
                        enviarCita({
                                action: 'asistencia',
                                id_agenda_turno: id,
                                valor: 0,
                                parte: 'paciente'
                            },
                            'Se registró al paciente como ausente.'
                        );
                    }
                });

                return;
            }

            Swal.fire({
                title: '¿Registrar su asistencia?',
                text: 'Quedará registrado que usted está presente en esta cita.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, estoy presente',
                cancelButtonText: 'Volver'
            }).then(function(r) {

                if (r.isConfirmed) {
                    enviarCita({
                            action: 'asistencia',
                            id_agenda_turno: id,
                            valor: 1
                        },
                        'Su asistencia quedó registrada.'
                    );
                }
            });
        }

        function finalizarCita() {
            confirmarAccion({
                titulo: '¿Finalizar esta cita?',
                texto: 'El turno se marcará como Finalizado.',
                icono: 'question',
                confirmar: 'Sí, finalizar'
            });
        }

        function cancelarCita() {
            confirmarAccion({
                titulo: '¿Cancelar esta cita?',
                texto: 'El turno volverá a quedar disponible.',
                icono: 'warning',
                confirmar: 'Sí, cancelar'
            });
        }

        /* ------------------------------------------------------------
           EVENTOS DE LOS BOTONES
        ------------------------------------------------------------ */
        document.addEventListener('click', function(e) {

            const ver = e.target.closest('.btn-ver-cita');
            if (ver) {
                abrirVerCita(ver.dataset.id);
                return;
            }

            const ficha = e.target.closest('.btn-ficha-cita');
            if (ficha) {
                abrirFicha(ficha.dataset.id);
                return;
            }

            const paso = e.target.closest('.btn-paso-cita');
            if (paso) {
                const tipo = paso.dataset.paso;

                if (tipo === 'confirmar') {
                    pasoConfirmar(paso.dataset.id);
                } else if (tipo === 'asistencia_paciente') {
                    pasoAsistencia(paso.dataset.id, 'paciente');
                } else {
                    pasoAsistencia(paso.dataset.id, 'doctor');
                }
                return;
            }

            const fin = e.target.closest('.btn-finalizar-cita');
            if (fin) {
                finalizarCita();
                return;
            }

            const can = e.target.closest('.btn-cancelar-cita');
            if (can) {
                cancelarCita();
                return;
            }
        });

        // Desde el modal "Ver turno" se puede pasar a la ficha médica
        $('btnVerFicha').addEventListener('click', function() {

            const id = citaActualId;
            const c = citas[id];

            if (!c || typeof bootstrap === 'undefined') {
                return;
            }

            if (!c.fichaLista) {
                abrirFicha(id);
                return;
            }

            const modalEl = $('modalVerCita');

            modalEl.addEventListener('hidden.bs.modal', function abrir() {
                modalEl.removeEventListener('hidden.bs.modal', abrir);
                abrirFicha(id);
            });

            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        });

        // Desde la ficha médica
        $('btnFinalizarDesdeFicha').addEventListener('click', finalizarCita);

        $('btnGuardarFicha').addEventListener('click', function() {

            const id = citaActualId;
            const boton = $('btnGuardarFicha');

            if (!id) {
                return;
            }

            boton.disabled = true;

            pedirFicha({
                action: 'guardar',
                id_agenda_turno: id,
                usa_signos: $('fichaUsaSignos').checked ? 1 : 0,
                altura: $('fichaAltura').value,
                peso: $('fichaPeso').value,
                presion: $('fichaPresion').value,
                frecuencia_cardiaca: $('fichaFc').value,
                temperatura: $('fichaTemp').value,
                saturacion_oxigeno: $('fichaSat').value,
                medicacion_actual: $('fichaMedicacion').value,
                observaciones: $('fichaObservaciones').value,
                diagnostico: $('fichaDiagnostico').value,
                indicaciones: $('fichaIndicaciones').value,
                tratamiento: $('fichaTratamiento').value,
                proximo_control: $('fichaProximoControl').value,
                motivo: $('fichaMotivo').value
            }).then(function(data) {

                boton.disabled = false;

                if (data && data.success) {
                    // El modal "Ver turno" debe mostrar el motivo actualizado
                    if (citas[id]) {
                        citas[id].motivo = $('fichaMotivo').value.trim();
                    }

                    aviso('Listo', 'La ficha médica se guardó correctamente.', 'success');
                    return;
                }

                aviso(
                    'No se pudo guardar',
                    (data && data.error) ? data.error : 'Ocurrió un error inesperado.',
                    'error'
                );
            });
        });

        $('btnGuardarAntecedentes').addEventListener('click', function() {
            aviso('Interfaz de ejemplo', 'Los antecedentes se guardarán en el perfil del paciente más adelante.', 'info');
        });

    })();
</script>