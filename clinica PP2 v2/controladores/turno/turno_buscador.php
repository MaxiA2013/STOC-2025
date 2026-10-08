<?php

require_once __DIR__ . '/../../modelos/turno.php';

$turno = new Turno();

$columnas = [
    "t.id_turnos",
    "t.minutos_turnos",
    "t.fecha_hora",
    "t.disponible",
    "t.agenda_id_agenda",
    "a.fecha_desde",
    "d.id_doctor",
    "per.nombre",
    "per.apellido"
];


// BUSCADOR
$campo = isset($_POST['campo'])
    ? $_POST['campo']
    : '';


// PAGINACIÓN
$limit = isset($_POST['num_registros'])
    ? (int) $_POST['num_registros']
    : 10;

$pagina = isset($_POST['pagina'])
    ? (int) $_POST['pagina']
    : 1;


// Evitar valores inválidos
if ($pagina < 1) {
    $pagina = 1;
}

if ($limit < 1) {
    $limit = 10;
}


// Calcular desde dónde comenzar
$inicio = ($pagina - 1) * $limit;


// LIMIT para SQL
$sLimit = "LIMIT $inicio, $limit";


// CONSULTA
$resultado = $turno->turnos_buscador(
    $columnas,
    $campo,
    $sLimit
);


$num_rows = $resultado ? $resultado->num_rows : 0;


// TOTALES
$totalFiltro = $turno->turnos_filtradosWhere($campo);

$totalRegistros = $turno->turnos_filtradosSinWhere();


// RESPUESTA
$output = [];

$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paginacion'] = '';
$output['modales'] = '';


// TABLA
if ($num_rows > 0) {

    while ($row = $resultado->fetch_assoc()) {

        $idTurno = (int) $row['id_turnos'];

        $disponible = (int) $row['disponible'];

        $fechaHora = htmlspecialchars(
            $row['fecha_hora'],
            ENT_QUOTES,
            'UTF-8'
        );

        $agenda = htmlspecialchars(
            $row['agenda_id'] . " - " . $row['fecha_agenda'],
            ENT_QUOTES,
            'UTF-8'
        );

        $doctor = htmlspecialchars(
            $row['nombre_doctor'] . " " . $row['apellido'],
            ENT_QUOTES,
            'UTF-8'
        );


        // ==========================================
        // FILA
        // ==========================================

        $output['data'] .= '
        <tr>

            <td>
                ' . $idTurno . '
            </td>

            <td>
                ' . htmlspecialchars(
                    $row['minutos_turnos'],
                    ENT_QUOTES,
                    'UTF-8'
                ) . '
            </td>

            <td>
                ' . $fechaHora . '
            </td>

            <td>';

        if ($disponible == 1) {

            $output['data'] .= '
                <span class="badge bg-success">
                    Sí
                </span>';

        } else {

            $output['data'] .= '
                <span class="badge bg-danger">
                    No
                </span>';
        }

        $output['data'] .= '
            </td>

            <td>
                ' . $agenda . '
            </td>

            <td>
                ' . $doctor . '
            </td>

            <td class="d-flex gap-1">

                <!-- ELIMINAR -->

                <form
                    action="controladores/turno/turno_controlador.php"
                    method="post"
                    style="display:inline;">

                    <input
                        type="hidden"
                        name="id_turnos"
                        value="' . $idTurno . '">

                    <input
                        type="hidden"
                        name="action"
                        value="eliminacion">

                    <button
                        type="submit"
                        class="btn btn-danger btn-sm"
                        title="Eliminar">

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </form>


                <!-- EDITAR -->

                <button
                    type="button"
                    class="btn btn-warning btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditar' . $idTurno . '"
                    title="Editar">

                    <i class="fa-solid fa-pen"></i>

                </button>


                <!-- HISTORIAL -->

                <button
                    type="button"
                    class="btn btn-info btn-sm btn-ver-historial"
                    data-id-turno="' . $idTurno . '"
                    title="Historial">

                    <i class="fa-solid fa-clock-rotate-left"></i>

                </button>

            </td>

        </tr>';
    }
}


// ==========================================
// PAGINACIÓN
// ==========================================

if ($output['totalFiltro'] > 0) {

    $totalPaginas = ceil(
        $output['totalFiltro'] / $limit
    );

    $output['paginacion'] .= '
    <nav>
        <ul class="pagination justify-content-end">';


    // PRIMERA PÁGINA
    if ($pagina > 1) {

        $output['paginacion'] .= '
            <li class="page-item">

                <a
                    href="#"
                    class="page-link pagina"
                    data-pagina="1"
                    title="Primera página">

                    <i class="fa-solid fa-angles-left"></i>

                </a>

            </li>';
    }


    // NÚMEROS
    $numeroInicio = 1;

    if ($pagina - 4 > 1) {
        $numeroInicio = $pagina - 4;
    }


    $numeroFin = $numeroInicio + 8;

    if ($numeroFin > $totalPaginas) {
        $numeroFin = $totalPaginas;
    }


    for ($i = $numeroInicio; $i <= $numeroFin; $i++) {

        if ($pagina == $i) {

            $output['paginacion'] .= '
                <li class="page-item active">

                    <a
                        href="#"
                        class="page-link pagina"
                        data-pagina="' . $i . '">

                        ' . $i . '

                    </a>

                </li>';

        } else {

            $output['paginacion'] .= '
                <li class="page-item">

                    <a
                        href="#"
                        class="page-link pagina"
                        data-pagina="' . $i . '">

                        ' . $i . '

                    </a>

                </li>';
        }
    }


    // ÚLTIMA PÁGINA
    if ($pagina < $totalPaginas) {

        $output['paginacion'] .= '
            <li class="page-item">

                <a
                    href="#"
                    class="page-link pagina"
                    data-pagina="' . $totalPaginas . '"
                    title="Última página">

                    <i class="fa-solid fa-angles-right"></i>

                </a>

            </li>';
    }


    $output['paginacion'] .= '
        </ul>
    </nav>';
}


echo json_encode(
    $output,
    JSON_UNESCAPED_UNICODE
);