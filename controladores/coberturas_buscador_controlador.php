<?php
require_once "../modelos/obraSocialPrestacion.php";

$osp = new ObraSocialPrestacion();

// Columnas que queremos consultar para armar la tabla
$columns = [
    'osp.`obra_social_id_obra-social` AS id_os',
    'osp.prestacion_id_prestacion AS id_p',
    'osp.codigo',
    'p.nombre_prestacion AS prestacion',
    'os.nombre_obra_social AS obra_social',
    'osp.precio',
    'osp.estado'
];

$campo = isset($_POST['campo']) ? $_POST['campo'] : '';

// Lógica de paginación idéntica a la que maneja tu sistema
$limit = isset($_POST['num_registros']) ? intval($_POST['num_registros']) : 5;
$pagina = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;
$inicio = ($pagina - 1) * $limit;
$sLimit = "LIMIT $inicio, $limit";

// Consultar los datos filtrados en la BD
$resultado = $osp->coberturas_buscador($columns, $campo, $sLimit);
$totalFiltro = $osp->coberturas_filtradosWhere($campo);

$html = '';

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $badgeColor = ($row['estado'] == 'activo') ? 'bg-success' : 'bg-secondary';
        $html .= '<tr>';
        $html .= '<td>' . $row['codigo'] . '</td>';
        $html .= '<td>' . $row['prestacion'] . '</td>';
        $html .= '<td>' . $row['obra_social'] . '</td>';
        $html .= '<td>' . number_format($row['precio'], 2, ',', '.') . '</td>';
        $html .= '<td><span class="badge ' . $badgeColor . '">' . ucfirst($row['estado']) . '</span></td>';
        $html .= '<td class="text-center">';
        $html .= '  <button class="btn btn-sm btn-outline-primary me-1" onclick="abrirModalEditar(' . $row['id_os'] . ', ' . $row['id_p'] . ', \'' . $row['codigo'] . '\', ' . $row['precio'] . ', \'' . $row['estado'] . '\')"><i class="bi bi-pencil-square"></i></button>';
        $html .= '  <button class="btn btn-sm btn-outline-danger" onclick="eliminarCobertura(' . $row['id_os'] . ', ' . $row['id_p'] . ')"><i class="bi bi-trash"></i></button>';
        $html .= '</td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="6" class="text-center text-muted">No se encontraron registros coincidentes.</td></tr>';
}

// Calcular páginas totales para dibujar los botones numéricos abajo
$totalPaginas = ceil($totalFiltro / $limit);
$paginacionHtml = '';

if ($totalPaginas > 1) {
    $paginacionHtml .= '<nav><ul class="pagination pagination-sm justify-content-end mb-0">';
    for ($i = 1; $i <= $totalPaginas; $i++) {
        $active = ($i == $pagina) ? 'active' : '';
        $paginacionHtml .= '<li class="page-item ' . $active . '"><a class="page-item page-link" href="#" onclick="cambiarPagina(' . $i . ')">' . $i . '</a></li>';
    }
    $paginacionHtml .= '</ul></nav>';
}

// Devolver la respuesta empaquetada en JSON directo para el JS
echo json_encode([
    'data' => $html,
    'pages' => $paginacionHtml,
    'totalFiltro' => "Mostrando de a $limit registros (Total filtrados: $totalFiltro)"
]);
