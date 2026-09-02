<?php

require_once __DIR__ . '/../../modelos/usuarios.php';

$usuario = new Usuario();

$columnas = [
    "u.id_usuario",
    "u.nombre_usuario",
    "u.email",
    "p.id_persona",
    "p.nombre",
    "p.apellido",
    "u.estado"
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
$resultado = $usuario->usuarios_buscador(
    $columnas,
    $campo,
    $sLimit
);

$num_rows = $resultado->num_rows;


// TOTALES
$totalFiltro = $usuario->usuarios_filtradosWhere($campo);
$totalRegistros = $usuario->usuarios_filtradosSinWhere();


// RESPUESTA
$output = [];

$output['totalRegistros'] = $totalRegistros;
$output['totalFiltro'] = $totalFiltro;
$output['data'] = '';
$output['paginacion'] = '';

// TABLA
if ($num_rows > 0) {

    while ($row = $resultado->fetch_assoc()) {

        /*
    =====================================================
    BOTÓN SEGÚN EL ESTADO DEL USUARIO
    =====================================================
    */

        if ($row['estado'] == 1) {

            // USUARIO ACTIVO → BOTÓN ELIMINAR
            $botonEstado = '
            <form
                method="POST"
                class="formEstadoUsuario">

                <input
                    type="hidden"
                    name="action"
                    value="eliminacion">

                <input
                    type="hidden"
                    name="id_usuario"
                    value="' . $row['id_usuario'] . '">

                <button
                    type="submit"
                    class="btn btn-outline-danger btn-sm"
                    title="Desactivar">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </form>';
        } else {

            // USUARIO INACTIVO → BOTÓN ACTIVAR
            $botonEstado = '
            <form
                method="POST"
                class="formEstadoUsuario">

                <input
                    type="hidden"
                    name="action"
                    value="activacion">

                <input
                    type="hidden"
                    name="id_usuario"
                    value="' . $row['id_usuario'] . '">

                <button
                    type="submit"
                    class="btn btn-outline-success btn-sm"
                    title="Activar">

                    <i class="fa-solid fa-check"></i>

                </button>

            </form>';
        }


        /*
    =====================================================
    INICIO DE FILA
    =====================================================
    */

        $output['data'] .= '<tr>';


        /*
    =====================================================
    ID
    =====================================================
    */

        $output['data'] .= '
        <td>
            ' . $row['id_usuario'] . '
        </td>';


        /*
    =====================================================
    USUARIO
    =====================================================
    */

        $output['data'] .= '
        <td>
            <span class="fw-semibold">
                ' . $row['nombre_usuario'] . '
            </span>
        </td>';


        /*
    =====================================================
    EMAIL
    =====================================================
    */

        $output['data'] .= '
        <td>
            ' . $row['email'] . '
        </td>';


        /*
    =====================================================
    NOMBRE
    =====================================================
    */

        $output['data'] .= '
        <td>
            ' . $row['nombre'] . '
        </td>';


        /*
    =====================================================
    APELLIDO
    =====================================================
    */

        $output['data'] .= '
        <td>
            ' . $row['apellido'] . '
        </td>';


        /*
    =====================================================
    PERFIL
    =====================================================
    */

        $output['data'] .= '
        <td>
            <span class="badge bg-info-subtle text-dark border">
                Sin definir
            </span>
        </td>';


        /*
    =====================================================
    ESTADO
    =====================================================
    */

        if ($row['estado'] == 1) {

            $output['data'] .= '
            <td>
                <span class="badge bg-success">
                    Activo
                </span>
            </td>';
        } else {

            $output['data'] .= '
            <td>
                <span class="badge bg-danger">
                    Inactivo
                </span>
            </td>';
        }


        /*
    =====================================================
    FECHA / OTRO DATO
    =====================================================
    */

        $output['data'] .= '
        <td>
            <small class="text-muted">
                No disponible
            </small>
        </td>';


        /*
    =====================================================
    ACCIONES
    =====================================================
    */

        $output['data'] .= '
        <td class="text-center">

            <div class="d-flex justify-content-center gap-2">

                ' . $botonEstado . '

                <!-- MODIFICAR -->

                <button
                    type="button"
                    class="btn btn-outline-primary btn-sm btnModificarUsuario"

                    data-bs-toggle="modal"
                    data-bs-target="#modalModificarUsuario"

                    data-id="' . $row['id_usuario'] . '"

                    data-persona="' . $row['id_persona'] . '"

                    data-usuario="' . htmlspecialchars(
                            $row['nombre_usuario'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) . '"

                    data-email="' . htmlspecialchars(
                            $row['email'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) . '"

                    data-nombre="' . htmlspecialchars(
                            $row['nombre'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) . '"

                    data-apellido="' . htmlspecialchars(
                            $row['apellido'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) . '"

                    title="Modificar">

                    <i class="fa-solid fa-pen-to-square"></i>

                </button>



            </div>

        </td>';


        /*
    =====================================================
    FIN DE FILA
    =====================================================
    */

        $output['data'] .= '</tr>';
    }

    //Escrito de paginacion
    if ($output['totalFiltro'] > 0) {
        $totalPaginas = ceil(
            $output['totalFiltro'] / $limit
        );

        $output['paginacion'] .= '<nav>';
        $output['paginacion'] .= '<ul class="pagination justify-content-end">';


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

        // NÚMEROS DE PÁGINA
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

        $output['paginacion'] .= '</ul>';
        $output['paginacion'] .= '</nav>';
    }
}
echo json_encode(
    $output,
    JSON_UNESCAPED_UNICODE
);
