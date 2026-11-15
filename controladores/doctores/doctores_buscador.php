<?php
require_once __DIR__ . '/../../modelos/doctor.php';

$doctor = new Doctor();

// COLUMNAS
$columnas = [
    "d.id_doctor",
    "d.numero_matricula_profesional",
    "p.nombre",
    "p.apellido",
    "u.nombre_usuario",
    "d.precio_consulta"
];

// BUSCADOR
$campo = isset($_POST['campo'])
    ? $_POST['campo']
    : '';

// LIMIT
$limit = isset($_POST['num_registros'])
    ? (int) $_POST['num_registros']
    : 10;


// PÁGINA
$pagina = isset($_POST['pagina'])
    ? (int) $_POST['pagina']
    : 1;

if ($pagina < 1) {

    $pagina = 1;
}

// INICIO
$inicio = ($pagina - 1) * $limit;

// LIMIT SQL
$sLimit = "LIMIT $inicio, $limit";


// CONSULTA
$resultado = $doctor->doctores_buscador(
    $columnas,
    $campo,
    $sLimit
);

$num_rows = $resultado->num_rows;


// TOTALES
$totalRegistros = $doctor->doctores_filtradosSinWhere();
$totalFiltro = $doctor->doctores_filtradosWhere($campo);

// OUTPUT
$output = [
    'totalRegistros' => $totalRegistros,
    'totalFiltro' => $totalFiltro,
    'data' => '',
    'paginacion' => ''

];

// TABLA
if ($num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $output['data'] .= '<tr>';

        // ID
        $output['data'] .= '
            <td>
                <span class="text-muted">
                    #' . $row['id_doctor'] . '
                </span>
            </td>';

        // MATRÍCULA
        $output['data'] .= '
            <td>
                <span class="fw-semibold">
                    ' . htmlspecialchars(
            $row['numero_matricula_profesional']
        ) . '
                </span>
            </td>';

        // NOMBRE
        $output['data'] .= '
            <td>
                ' . htmlspecialchars(
            $row['nombre']
        ) . '
            </td>';

        // APELLIDO
        $output['data'] .= '
            <td>
                ' . htmlspecialchars(
            $row['apellido']
        ) . '
            </td>';

        // USUARIO
        $output['data'] .= '
            <td>
                <span class="fw-semibold">
                    ' . htmlspecialchars(
            $row['nombre_usuario']
        ) . '
                </span>
            </td>';

        // PRECIO
        $output['data'] .= '
            <td>
                <span class="fw-semibold">
                    $ ' .
            number_format(
                $row['precio_consulta'],
                2,
                ',',
                '.'
            )
            . '
                </span>
            </td>';

        // ACCIONES
        $output['data'] .= '
            <td class="text-center">
                <div class="d-flex justify-content-center gap-2">

                    <!-- MODIFICAR -->
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                        title="Editar"
                        data-bs-toggle="modal"
                        data-bs-target="#modalEditar' .
            $row['id_doctor'] .
            '">

                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <!-- ELIMINAR -->
                    <form
                        action="controladores/doctores/doctor_controlador.php"
                        method="POST">
                        <input
                            type="hidden"
                            name="action"
                            value="eliminar_doctor">

                        <input
                            type="hidden"
                            name="id_doctor"
                            value="' .
            $row['id_doctor'] .
            '">

                        <button
                            type="submit"
                            class="btn btn-sm btn-outline-danger"
                            title="Eliminar"
                            onclick="return confirm(\'¿Está seguro de que desea eliminar este doctor?\');">

                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>';
        $output['data'] .= '</tr>';
    }
} else {
    $output['data'] = '
        <tr>
            <td
                colspan="7"
                class="text-center py-5">

                <div class="text-muted">
                    <i
                        class="fa-solid fa-user-doctor fa-2x mb-3">
                    </i>

                    <p class="mb-0">
                        No se encontraron doctores.
                    </p>
                </div>
            </td>
        </tr>';
}

// ==========================================
// PAGINACIÓN
// ==========================================

if ($totalFiltro > 0) {

    $totalPaginas = ceil(
        $totalFiltro / $limit
    );


    $output['paginacion'] .= '<nav>';

    $output['paginacion'] .= '
        <ul class="pagination justify-content-end">';


    // ======================================
    // PRIMERA PÁGINA
    // ======================================

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


    // ======================================
    // NÚMEROS
    // ======================================

    $numeroInicio = 1;

    if ($pagina - 4 > 1) {

        $numeroInicio =
            $pagina - 4;
    }


    $numeroFin =
        $numeroInicio + 8;


    if ($numeroFin > $totalPaginas) {

        $numeroFin =
            $totalPaginas;
    }


    for (
        $i = $numeroInicio;
        $i <= $numeroFin;
        $i++
    ) {

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


    // ======================================
    // ÚLTIMA PÁGINA
    // ======================================

    if ($pagina < $totalPaginas) {

        $output['paginacion'] .= '

            <li class="page-item">

                <a
                    href="#"
                    class="page-link pagina"
                    data-pagina="' .
            $totalPaginas .
            '"
                    title="Última página">

                    <i class="fa-solid fa-angles-right"></i>

                </a>

            </li>';
    }

    $output['paginacion'] .= '</ul>';
    $output['paginacion'] .= '</nav>';
}

// RESPUESTA JSON
echo json_encode(
    $output,
    JSON_UNESCAPED_UNICODE
);
