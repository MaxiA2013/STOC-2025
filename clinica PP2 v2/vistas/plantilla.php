<?php
session_start();
// Si no hay sesión iniciada, se fuerza a que la página sea "login"
if (!isset($_SESSION['id_usuario'])) {
    $peges = 'index';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'vistas/componentes/nav_bar.php';
    // Incluye el navbar de usuario administrador/doctor si existe una session (se corrigió el uso de multiples nav a uno solo)
    if ((isset($_SESSION['id_usuario'])) && (($_SESSION['id_perfil']  == 2) or ($_SESSION['id_perfil'])  == 3)) {
        include 'vistas/componentes/sidebar.php'; //sidebar tiene un controlador para mostrar o no secciones
    }
    ?>

    <?php
    // Muestra un mensaje de alerta si viene por la URL
    if (isset($_GET['message'])) {
        echo '<div class="alert alert-' . $_GET['status'] . '" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                ' . $_GET['message'] . '
              </div>';
    }
    ?>

    <div>
        <?php
        // Aquí se define qué páginas puede ver cada perfil
        include 'vistas/componentes/breadcrumb.php';

        if (isset($_GET['page'])) {
            $peges = $_GET['page'];

            $paginas_publica = ['indexo', 'noticias', 'nosotros', 'biblioteca', 'turnos', 'info_turnos', 'doctores', 'areas', 'login', 'registro'];
            $paginas_comunes = ['mi_perfil', 'salida', 'mis_datos'];
            $paginas_admin = ['lista_usuario', 'lista_doctor', 'registro', 'modulos', 'perfiles', 'tablas', 'tablas_maestras', 'sintomas_lista', 'obra_social_lista', 'especialidad_lista', 'condicion_lista', 'contacto_lista', 'documento_lista', 'estados_lista', 'metodo_pago_lista','direccion_lista', 'familiar_lista', 'agenda_lista'];
            $listas = ['lista_usuario', 'lista_doctor','condicion_lista', 'contacto_lista', 'documento_lista', 'estados_lista', 'metodo_pago_lista','direccion_lista','sintomas_lista', 'obra_social_lista', 'especialidad_lista', 'familiar_lista', 'agenda_lista'];

            if (in_array($peges, $paginas_publica)) {
                include('vistas/paginas/' . $peges . '.php');
            } elseif (isset($_SESSION['nombre_usuario'])) {

                if (in_array($peges, $paginas_comunes)) {
                    include('vistas/paginas/' . $peges . '.php');
                } elseif (in_array($peges, $paginas_admin) && $_SESSION['nombre_perfil'] === 'administrador') {
                    // Ruta absoluta hacia vistas/paginas
                    $path = realpath(__DIR__ . '/paginas/' . $peges . '.php');

                    if ($path && is_file($path) && is_readable($path)) {
                        require_once $path; // se incluye solo si existe y es accesible
                    } else {
                        // Manejo de error si no se encuentra el archivo
                        error_log("Archivo no encontrado en paginas: " . $path);
                        http_response_code(404);
                        //echo "<p>Página no encontrada</p>";
                    }

                    // Si la página está en la lista de "listas", incluir la template asociada
                    if (in_array($peges, $listas)) {
                        $templatePath = realpath(__DIR__ . '/paginas/template_tablasMaestras/' . $peges . '.php');
                        //echo $templatePath;

                        if ($templatePath && is_file($templatePath) && is_readable($templatePath)) {
                            require_once $templatePath;
                        } else {
                            error_log("Template no encontrada: " . $templatePath);
                        }
                    }
                } else {
                    include('vistas/paginas/403.php');
                }
            } else {
                include('vistas/paginas/403.php');
            }
        } else {
            if (isset($_SESSION['nombre_usuario'])) {
                include('vistas/paginas/mi_perfil.php');
            } else {
                include 'vistas/paginas/login.php';
            }
        }

        ?>
    </div>

    <?php include 'componentes/footer.php'; ?>

    <!-- Scripts -->

</body>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/all.min.js"></script>
<!-- <script src="assets/js/controlador_perfil.js"></script>  hay que arreglar el por que no se estaría encontrando el archivo -->

</html>