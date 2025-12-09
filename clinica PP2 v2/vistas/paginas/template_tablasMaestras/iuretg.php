<?php /*
require_once "modelos/turno.php";
require_once "modelos/agenda.php";
require_once "modelos/agenda_turno.php";

$turnoObj = new Turno();
$lista_turnos = $turnoObj->consultarVariosTurnos();

$agendaObj = new Agenda();
$doctores = $agendaObj->obtenerDoctores();

$agendaTurnoObj = new AgendaTurno();
$turnos_pacientes = $agendaTurnoObj->listar(); // obtiene todos los turnos asignados/relacionados a pacientes
?>

<link href="assets/css/select2.min.css" rel="stylesheet"/>

<style>
    .form-box { border:1px solid #e3e3e3; padding:18px; border-radius:8px; }
    .select2-container { width:100% !important; }
</style>
*/

<?php
$colores = ["purple", "red", "green"];
$i = 0;
$porpag = 20;
$offset = 0;
require_once "modelos/turno.php";
require_once "controladores/turno/info_turno_controlador.php";
$tur = new Turno();
$listaTurno = $tur->consultarTurnosDisponiblesPaginado($i, $porpag);

?>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f8f9fc;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-content { flex-grow: 1; padding: 40px; }
    .cards-container { display: flex; flex-wrap: wrap; gap: 20px; }

    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.05);
      width: 300px;
      transition: 0.3s ease;
    }
    .card:hover { transform: translateY(-5px); }

    .day { font-size: 32px; font-weight: 700; }
    .month { font-size: 16px; font-weight: 600; }

    .footer-line { height: 5px; border-radius: 0 0 10px 10px; }

    .purple .day, .purple .month, .purple .title, .purple .info { color: rgb(95, 163, 240); }
    .purple .footer-line { background-color: rgb(95, 163, 240); }

    .red .day, .red .month, .red .title, .red .info { color: rgb(44, 92, 248); }
    .red .footer-line { background-color: rgb(44, 92, 248); }

    .green .day, .green .month, .green .title, .green .info { color: rgb(3, 14, 179); }
    .green .footer-line { background-color: rgb(3, 14, 179); }
  </style>
</head>
<body>

<div class="container py-4">
    <h2 class="mb-4">Turnos disponibles</h2>

    <div class="cards-container">

        <?php foreach ($listaTurno as $t):
            $color = $colores[$i % 3];
            $i++;

            $fecha = date("d", strtotime($t['fecha_hora']));
            $mes = date("M", strtotime($t['fecha_hora']));
            $hora = date("H:i", strtotime($t['fecha_hora']));
            $horaFin = date("H:i", strtotime($t['fecha_hora'] . " + {$t['minutos_turnos']} minutes"));
        ?>

        <div class="card <?= $color ?>">
            <div class="card-header d-flex justify-content-between">
                <div class="date">
                    <div class="day"><?= $fecha ?></div>
                    <div class="month"><?= $mes ?></div>
                </div>
            </div>

            <div class="card-body">
                <div class="title"><?= $t['nombre'] . " " . $t['apellido'] ?></div>
                <div class="info"><i class="far fa-clock"></i> <?= $hora ?> - <?= $horaFin ?></div>
                <div class="info"><i class="fas fa-arrow-right"></i>
                    <a href="index.php?page=info_turnos&id=<?= $t['id_turnos'] ?>">Ver más</a>
                </div>
            </div>

            <div class="footer-line"></div>
        </div>

        <?php endforeach; ?>

    </div>

    <!-- PAGINADO -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                <li class="page-item <?= ($p == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?page=turnos&pagina=<?= $p ?>">
                        <?= $p ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

</div>

</body>
</html>

<?php
header('Content-Type: application/json; charset=utf-8');

require_once "../../modelos/turno.php";
require_once "../../modelos/agenda.php";
require_once "../../modelos/agenda_turno.php";
require_once "../../modelos/conexion.php";

$action = $_POST["action"] ?? $_GET["action"] ?? null;

try {

    // GENERAR TURNOS : Usa la agenda 
    // GENERAR TURNOS : Usa la agenda 
    // GENERAR TURNOS : Usa la agenda 
    // GENERAR TURNOS : Usa la agenda 
    switch ($action) {

        case ($action === "generar_turnos"):

            $id_agenda = intval($_POST["id_agenda"] ?? 0);
            $min = intval($_POST["minutos"] ?? 0);

            if ($id_agenda <= 0 || $min <= 0) {
                echo json_encode(["success" => false, "error" => "Parámetros inválidos."]);
                exit();
            }

            $agenda = new Agenda();
            $datos = $agenda->obtenerPorId($id_agenda);

            if (!$datos) {
                echo json_encode(["success" => false, "error" => "Agenda no encontrada."]);
                exit();
            }

            // asumimos $datos['fecha_desde'], ['hora_desde'], ['hora_hasta']
            $start = strtotime($datos['fecha_desde'] . ' ' . $datos['hora_desde']);
            $end   = strtotime($datos['fecha_desde'] . ' ' . $datos['hora_hasta']); // genera para la fecha_desde

            $turno = new Turno();

            while ($start < $end) {
                $turno->setAgenda_id_agenda($id_agenda);
                $turno->setMinutos_turnos($min);

                $turno->setFecha_hora(date("Y-m-d H:i:s", $start));
                $turno->setDisponible(1);

                $turno->guardarTurno();

                $start = strtotime("+{$min} minutes", $start);
            }

            echo json_encode(["success" => true]);


            break;
        // INSERTAR UN TURNO
        // INSERTAR UN TURNO
        // INSERTAR UN TURNO
        // INSERTAR UN TURNO
        case ($action === "insertar"):
            //TABLA TURNO
            //TABLA TURNO
            //TABLA TURNO
            //se pregunta por el valor del check, si el modo es agregar, corresponde al registro de un turno fuera del 
            //horario del profesional
            $modo = $_POST['modo_turno'];

            if ($modo == 'agregar') {
                $minutos = intval($_POST["minutos_turnos"] ?? 0);
                $fecha_hora = trim($_POST["fecha_hora"] ?? "");
                $disponible = isset($_POST["disponible"]) ? intval($_POST["disponible"]) : 1;
                $agenda_id = intval($_POST["agenda_id_agenda"] ?? 0);

                if ($minutos <= 0 || $fecha_hora === "" || $agenda_id <= 0) {
                    echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios."]);
                    exit();
                }

                $turno = new Turno();
                $turno->setAgenda_id_agenda($agenda_id);
                $turno->setMinutos_turnos($minutos);
                $turno->setFecha_hora($fecha_hora);
                $turno->setDisponible($disponible ? 1 : 0);

                $id = $turno->guardarTurno();

                if ($id) {
                    echo json_encode(["success" => true, "id_turno" => $id]);
                } else {
                    echo json_encode(["success" => false, "error" => "No se pudo insertar el turno."]);
                }
                exit();
            } else {
                //TABLA AGENDA_TURNO
                //TABLA AGENDA_TURNO
                //TABLA AGENDA_TURNO
                //si el valor del check es de asignar, entonces se asigna :3
                $paciente_id = intval($_POST["paciente_id"] ?? 0);
                $turno_id = intval($_POST["turno_id"] ?? $_POST["turno_existente_id"] ?? 0);
                $estado_id = intval($_POST["estados_id_estados"] ?? 2); // por defecto 2 si aplica

                if ($paciente_id <= 0 || $turno_id <= 0) {
                    echo json_encode(["success" => false, "error" => "Faltan parámetros (paciente o turno)."]);
                    exit();
                }

                // 1) verificar que turno existe y está disponible
                $t_model = new Turno();
                $res = $t_model->existeTurnoDisponible($turno_id);

                // Convertir resultado mysqli a array
                $t = $res->fetch_assoc();

                if (!$t) {
                    echo json_encode(["success" => false, "error" => "Turno no existe."]);
                    exit();
                }

                if (intval($t['disponible']) !== 1) {
                    echo json_encode(["success" => false, "error" => "Turno no disponible."]);
                    exit();
                }


                // 2) insertar agenda_turno
                $i = new AgendaTurno();
                $i->setPaciente_id_paciente($paciente_id);
                $i->setTurno_id_turnos($turno_id);
                $i->setEstados_id_estados($estado_id);
                $id = $i->insertar();

                if (!$id) {
                    echo json_encode(["success" => false, "error" => "No se pudo asignar el turno."]);
                    exit();
                }

                // 3) marcar turno como no disponible
                $p = new Turno();
                $pot = $p->actualizarDisponible($turno_id);

                echo json_encode(["success" => true, "id_agenda_turno" => $id]);
            }

            break;
        // ACTUALIZAR UN TURNO
        // ACTUALIZAR UN TURNO
        // ACTUALIZAR UN TURNO
        // ACTUALIZAR UN TURNO

        case ($action === "actualizacion"):
            //actualizar de tabla turnos disponibles (turnos que no estan asignados a ningún paciente)

            $id_turno = intval($_POST["id_turnos"] ?? 0);
            $minutos = intval($_POST["minutos_turnos"] ?? 0);
            $fecha_hora = trim($_POST["fecha_hora"] ?? "");
            $disponible = isset($_POST["disponible"]) ? intval($_POST["disponible"]) : 1;
            $agenda_id = intval($_POST["agenda_id_agenda"] ?? 0);

            if ($id_turno <= 0 || $minutos <= 0 || $fecha_hora === "" || $agenda_id <= 0) {
                echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios."]);
                exit();
            }

            $re = new Turno();
            $re->setId_turnos($id_turno);
            $re->setMinutos_turnos($minutos);
            $re->setFecha_hora($fecha_hora);
            $re->setDisponible($disponible);
            $re->setAgenda_id_agenda($agenda_id);
            $res = $re->actualizar();

            if ($res !== false) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo actualizar el turno."]);
            }

            break;

        //actualizar de tabla turnos asignados
        //actualizar de tabla turnos asignados
        case ($action === "editar_asignado"):

            // --------- 1) Recibir datos del formulario (normalizados) ---------
            $id_agenda_turno = intval($_POST["id_agenda_turno"] ?? 0);
            $paciente_id     = intval($_POST["paciente_id_paciente"] ?? 0);

            // aceptar varios nombres posibles enviados por el formulario/modal
            $turno_nuevo_id  = intval(
                $_POST["turno_id"] ??
                    $_POST["turno_id_turnos"] ??
                    $_POST["turno_existente_id"] ??
                    0
            );

            $estado_id  = intval($_POST["estados_id_estados"] ?? 1);

            // validación básica
            if ($id_agenda_turno <= 0 || $paciente_id <= 0) {
                echo json_encode(["success" => false, "error" => "Faltan parámetros obligatorios (id_agenda_turno o paciente)."]);
                exit();
            }

            $con = new Conexion();

            // Si no llegó turno_nuevo_id, tomamos el turno actual asignado (evita fallo)
            if ($turno_nuevo_id <= 0) {
                $actualTmp = $con->consultarArray("SELECT turno_id_turnos FROM agenda_turno WHERE id_agenda_turno = $id_agenda_turno");
                if (empty($actualTmp)) {
                    echo json_encode(["success" => false, "error" => "Asignación no encontrada (para obtener turno actual)."]);
                    exit();
                }
                $turno_nuevo_id = intval($actualTmp[0]['turno_id_turnos']);
            }

            // ahora sí validamos que turno_nuevo_id sea > 0
            if ($turno_nuevo_id <= 0) {
                echo json_encode(["success" => false, "error" => "ID de turno inválido."]);
                exit();
            }

            // --------- 2) Obtener turno actual asignado ---------
            $actual = $con->consultarArray("
                SELECT turno_id_turnos 
                FROM agenda_turno 
                WHERE id_agenda_turno = $id_agenda_turno
            ");

            if (empty($actual)) {
                echo json_encode(["success" => false, "error" => "Asignación no encontrada."]);
                exit();
            }

            $turno_viejo_id = intval($actual[0]["turno_id_turnos"]);

            // --------- 3) Si se cambió el turno, verificar disponibilidad y actualizar disponibilidades ---------
            if ($turno_viejo_id !== $turno_nuevo_id) {

                $check = $con->consultarArray("SELECT disponible FROM turno WHERE id_turnos = $turno_nuevo_id");
                if (empty($check)) {
                    echo json_encode(["success" => false, "error" => "El nuevo turno no existe."]);
                    exit();
                }
                if (intval($check[0]['disponible']) !== 1) {
                    echo json_encode(["success" => false, "error" => "El nuevo turno no está disponible."]);
                    exit();
                }

                // liberar viejo y ocupar nuevo (atomicidad simple: dos queries)
                $con->actualizar("UPDATE turno SET disponible = 1 WHERE id_turnos = $turno_viejo_id");
                $con->actualizar("UPDATE turno SET disponible = 0 WHERE id_turnos = $turno_nuevo_id");
            }

            // --------- 4) Actualizar agenda_turno usando el modelo ---------

            $obj = new AgendaTurno();
            // asegurarse de que setters existen con estos nombres
            $obj->setId_agenda_turno($id_agenda_turno);
            $obj->setPaciente_id_paciente($paciente_id);
            $obj->setTurno_id_turnos($turno_nuevo_id);
            $obj->setEstados_id_estados($estado_id);

            $resultado = $obj->modificar();

            // --------- 5) Respuesta JSON (y opcional redirect si no es AJAX) ---------
            if ($resultado !== false) {
                // si viene por AJAX (fetch/jQuery), devolvemos JSON
                echo json_encode(["success" => true]);
                exit();
            } else {
                echo json_encode(["success" => false, "error" => "Error al actualizar el turno (BD)."]);
                exit();
            }

        break;


        // ELIMINAR UN TURNO
        // ELIMINAR UN TURNO
        // ELIMINAR UN TURNO
        // ELIMINAR UN TURNO
        case ($action === "eliminacion"):
            //eliminar de tabla turnos disponibles

            $id_turno = intval($_POST["id_turnos"] ?? $_POST["id"] ?? 0);
            if ($id_turno <= 0) {
                echo json_encode(["success" => false, "error" => "ID inválido."]);
                exit;
            }

            $con = new Conexion();

            // Antes de eliminar, verificar si el turno está asignado en agenda_turno
            $check = $con->consultarArray("SELECT * FROM agenda_turno WHERE turno_id_turnos = $id_turno");
            if (!empty($check)) {
                // No permitimos eliminar si está asignado (alternativa: eliminar cascada)
                echo json_encode(["success" => false, "error" => "El turno está asignado a un paciente, primero desasignelo."]);
                exit;
            }

            $del = $con->eliminar("DELETE FROM turno WHERE id_turnos = $id_turno");
            if ($del) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "No se pudo eliminar."]);
            }

        break;

        case ($action === "eliminar"):
            //eliminar de tabla turnos asignados

        break;
    }
    // Acción no reconocida
    echo json_encode(["success" => false, "error" => "Acción desconocida o no proporcionada."]);
} catch (Exception $ex) {
    echo json_encode(["success" => false, "error" => $ex->getMessage()]);
}
