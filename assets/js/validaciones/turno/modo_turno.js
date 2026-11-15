<?php
//este archivo trabaja con la pagina de informacion turnos "info_turno"
require_once "modelos/turno.php";
require_once "controladores/turno/info_turno_controlador.php";

$colores = ["purple", "red", "green"];
$i = 0;

// ---------------- PAGINADO ----------------
$porPagina = 20;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($pagina < 1) {
    $pagina = 1;
}

$offset = ($pagina - 1) * $porPagina;

$tur = new Turno();

// Obtener turnos paginados
$listaTurno = $tur->consultarTurnosDisponiblesPaginado($offset, $porPagina);

// Obtener total de turnos disponibles
$totalTurnos = $tur->contarTurnosDisponibles();
$totalPaginas = ceil($totalTurnos / $porPagina);
?>

<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #f8f9fc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* ==============================
       CONTENIDO PRINCIPAL
    ============================== */

    .main-content {
        flex-grow: 1;
        padding: 40px;
    }

    .turnos-titulo {
        margin-bottom: 30px;
    }

    .turnos-titulo h2 {
        font-weight: 700;
        color: #212529;
        margin-bottom: 5px;
    }

    .turnos-titulo p {
        color: #6c757d;
        margin-bottom: 0;
    }

    /* ==============================
       CONTENEDOR DE TARJETAS
    ============================== */

    .cards-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    /* ==============================
       TARJETA
    ============================== */

    .turno-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
        width: 300px;
        overflow: hidden;
        transition: 0.3s ease;
        position: relative;
    }

    .turno-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.09);
    }

    /* ==============================
       FRANJA GRISÁCEA DE LA CARD
    ============================== */

    .turno-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;

        background-color: #f1f1f1;

        padding: 15px 20px;

        min-height: 75px;

        border-radius: 10px 10px 0 0;
    }

    .date {
        text-align: left;
    }

    .day {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
    }

    .month {
        font-size: 16px;
        font-weight: 600;
        margin-top: 5px;
        text-transform: capitalize;
    }

    /* ==============================
       CUERPO DE TARJETA
    ============================== */

    .turno-card-body {
        padding: 20px;
    }

    .turno-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .turno-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 18px;
    }

    .turno-info {
        display: flex;
        align-items: flex-start;
        font-size: 14px;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .turno-info i {
        width: 20px;
        min-width: 20px;
        margin-right: 8px;
        margin-top: 2px;
    }

    .turno-info a {
        text-decoration: none;
        font-weight: 600;
    }

    .turno-info a:hover {
        text-decoration: underline;
    }

    /* ==============================
       BARRA INFERIOR
    ============================== */

    .footer-line {
        height: 5px;
        border-radius: 0 0 10px 10px;
    }

    /* ==============================
       COLORES
    ============================== */

    .purple .day,
    .purple .month,
    .purple .turno-title,
    .purple .turno-info {
        color: rgb(95, 163, 240);
    }

    .purple .footer-line {
        background-color: rgb(95, 163, 240);
    }

    .purple .turno-info a {
        color: rgb(95, 163, 240);
    }

    .red .day,
    .red .month,
    .red .turno-title,
    .red .turno-info {
        color: rgb(44, 92, 248);
    }

    .red .footer-line {
        background-color: rgb(44, 92, 248);
    }

    .red .turno-info a {
        color: rgb(44, 92, 248);
    }

    .green .day,
    .green .month,
    .green .turno-title,
    .green .turno-info {
        color: rgb(3, 14, 179);
    }

    .green .footer-line {
        background-color: rgb(3, 14, 179);
    }

    .green .turno-info a {
        color: rgb(3, 14, 179);
    }

    /* ==============================
       PAGINACIÓN
    ============================== */

    .turnos-paginacion {
        margin-top: 35px;
    }

    .turnos-paginacion .page-link {
        color: #024296;
        border-color: #dee2e6;
    }

    .turnos-paginacion .page-link:hover {
        background-color: #f0f4fa;
    }

    .turnos-paginacion .page-item.active .page-link {
        background-color: #024296;
        border-color: #024296;
        color: #fff;
    }

    /* ==============================
       SIN TURNOS
    ============================== */

    .sin-turnos {
        width: 100%;
        background: #fff;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
    }

    .sin-turnos i {
        font-size: 40px;
        color: #8999AE;
        margin-bottom: 15px;
    }

    .sin-turnos h5 {
        color: #343a40;
        margin-bottom: 5px;
    }

    .sin-turnos p {
        color: #6c757d;
        margin-bottom: 0;
    }

    /* ==============================
       RESPONSIVE
    ============================== */

    @media (max-width: 768px) {

        .main-content {
            padding: 20px;
        }

        .cards-container {
            justify-content: center;
        }

        .turno-card {
            width: 100%;
            max-width: 350px;
        }
    }
</style>

<div class="d-flex">

```
<!-- ==============================
     CONTENIDO PRINCIPAL
============================== -->

<div class="main-content">

    <div class="turnos-titulo">

        <h2>Turnos disponibles</h2>

        <p>
            Seleccioná un turno disponible para consultar sus detalles.
        </p>

    </div>


    <div class="cards-container">

        <?php if (!empty($listaTurno)): ?>

            <?php foreach ($listaTurno as $t): ?>

                <?php
                $color = $colores[$i % 3];
                $i++;

                $fecha = date("d", strtotime($t['fecha_hora']));
                $mes   = date("M", strtotime($t['fecha_hora']));
                $hora  = date("H:i", strtotime($t['fecha_hora']));

                $horaFin = date(
                    "H:i",
                    strtotime(
                        $t['fecha_hora'] . " + {$t['minutos_turnos']} minutes"
                    )
                );
                ?>


                <!-- ==============================
                     CARD DEL TURNO
                ============================== -->

                <div class="turno-card <?= $color ?>">

                    <!-- FRANJA GRISÁCEA -->

                    <div class="turno-card-header">

                        <div class="date">

                            <div class="day">
                                <?= htmlspecialchars($fecha) ?>
                            </div>

                            <div class="month">
                                <?= htmlspecialchars($mes) ?>
                            </div>

                        </div>

                    </div>


                    <!-- CUERPO -->

                    <div class="turno-card-body">

                        <!-- DOCTOR -->

                        <div class="turno-title">

                            <?= htmlspecialchars(
                                $t['nombre'] . " " . $t['apellido']
                            ) ?>

                        </div>


                        <!-- ESPECIALIDAD -->

                        <div class="turno-description">

                            <i class="fas fa-user-md me-1"></i>

                            Especialista en especialidad

                        </div>


                        <!-- HORARIO -->

                        <div class="turno-info">

                            <i class="far fa-clock"></i>

                            <span>
                                <?= htmlspecialchars($hora) ?>
                                -
                                <?= htmlspecialchars($horaFin) ?>
                            </span>

                        </div>


                        <!-- UBICACIÓN -->

                        <div class="turno-info">

                            <i class="fas fa-map-marker-alt"></i>

                            <span>
                                Dirección de la clínica
                            </span>

                        </div>


                        <!-- VER MÁS -->

                        <div class="turno-info">

                            <i class="fas fa-arrow-right"></i>

                            <a
                                href="index.php?page=info_turnos&id=<?= (int)$t['id_turnos'] ?>"
                            >
                                Ver más
                            </a>

                        </div>

                    </div>


                    <!-- BARRA INFERIOR -->

                    <div class="footer-line"></div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="sin-turnos">

                <i class="far fa-calendar-times"></i>

                <h5>No hay turnos disponibles</h5>

                <p>
                    Actualmente no existen turnos disponibles para mostrar.
                </p>

            </div>

        <?php endif; ?>

    </div>


    <!-- ==============================
         PAGINACIÓN
    ============================== -->

    <?php if ($totalPaginas > 1): ?>

        <nav
            aria-label="Page navigation"
            class="turnos-paginacion"
        >

            <ul class="pagination justify-content-center">

                <!-- ANTERIOR -->

                <li
                    class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>"
                >

                    <a
                        class="page-link"
                        href="index.php?page=turnos&pagina=<?= $pagina - 1 ?>"
                        aria-label="Anterior"
                    >

                        <i class="fas fa-chevron-left"></i>

                    </a>

                </li>


                <!-- NÚMEROS -->

                <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>

                    <li
                        class="page-item <?= ($p == $pagina) ? 'active' : '' ?>"
                    >

                        <a
                            class="page-link"
                            href="index.php?page=turnos&pagina=<?= $p ?>"
                        >
                            <?= $p ?>
                        </a>

                    </li>

                <?php endfor; ?>


                <!-- SIGUIENTE -->

                <li
                    class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>"
                >

                    <a
                        class="page-link"
                        href="index.php?page=turnos&pagina=<?= $pagina + 1 ?>"
                        aria-label="Siguiente"
                    >

                        <i class="fas fa-chevron-right"></i>

                    </a>

                </li>

            </ul>

        </nav>

    <?php endif; ?>

</div>

<?php include 'vistas/componentes/sidebar_filtros_turnos.php'; ?>
</div>
?>