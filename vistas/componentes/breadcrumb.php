<?php
// Obtener la ruta actual desde GET
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Dividir la ruta en segmentos
$segments = explode('/', $page);

// Mapeo de nombres legibles
$pagesMap = [
    'home' => 'Inicio',
    'mi_perfil' => 'Mi Perfil',
    'mis_datos' => 'Mis Datos',
    'lista_sintomas' => 'Lista Síntomas',
    'lista_medicamentos' => 'Lista Medicamentos',
    'lista_usuarios' => 'Lista Usuarios',
    'especialidad_lista' => 'Especialidad Lista',
    'nosotros' => 'Nosotros',
    'biblioteca' => 'Biblioteca Médica',
    'salud' => 'Salud',
];

// Función para obtener nombre legible
function getPageName($segment, $pagesMap) {
    return $pagesMap[$segment] ?? ucfirst(str_replace("_", " ", $segment));
}
?>

<!-- Breadcrumb con Bootstrap -->
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
  <ol class="breadcrumb" style="margin-left: 7pc;">
    <!-- Siempre Inicio -->
    <li class="breadcrumb-item">
      <a href="index.php?page=indexo">Inicio</a>
    </li>

    <?php
    $path = "";
    $lastIndex = count($segments) - 1;

    foreach ($segments as $index => $segment) {
        $path .= ($index === 0 ? "" : "/") . $segment;
        $name = getPageName($segment, $pagesMap);

        if ($index === $lastIndex) {
            // Último segmento (activo)
            echo '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($name) . '</li>';
        } else {
            // Segmentos intermedios
            echo '<li class="breadcrumb-item"><a href="index.php?page=' . urlencode($path) . '">' . htmlspecialchars($name) . '</a></li>';
        }
    }
    ?>
  </ol>
</nav>
