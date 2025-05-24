<?php
session_start();

// Si no hay sesión iniciada, se fuerza a que la página sea "login"
if (!isset($_SESSION['id_usuario'])) {
    $peges = 'login';
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
    <header>
        <?php include 'componentes/nav_bar.php'; ?>
    </header>

    <?php
    // Incluye el navbar correcto según el perfil del usuario
    if (isset($_SESSION['nombre_perfil'])) {
        switch ($_SESSION['nombre_perfil']) {
            case 'Administrador':
                include 'vistas/componentes/navbar.php';
                break;
            case 'Doctor':
                include 'vistas/componentes/doctor.navbar.php';
                break;
            case 'Paciente':
                include 'vistas/componentes/cliente.navbar.php';
                break;
        }
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

    <div class="container col-xl-10 col-xxl-8 px-4 py-5">
        <?php
        // Aquí se define qué páginas puede ver cada perfil

        if (isset($_GET['page'])) {
            $peges = $_GET['page'];

            // Si hay sesión iniciada
            if (isset($_SESSION['nombre_usuario'])) {

                // Páginas accesibles para cualquier usuario con sesión activa
                $paginas_comunes = ['mi_perfil', 'salida'];

                // Páginas exclusivas de administrador
                $paginas_admin = ['lista_usuario', 'lista_doctor', 'registro'];

                if (in_array($peges, $paginas_comunes)) {
                    include('vistas/paginas/' . $peges . '.php');
                } elseif (in_array($peges, $paginas_admin) && $_SESSION['nombre_perfil'] === 'Administrador') {
                    include('vistas/paginas/' . $peges . '.php');
                } else {
                    // Si intenta acceder a una página no permitida
                    include('vistas/paginas/403.php');
                }

            } else {
                // Si no tiene sesión, mostramos acceso denegado
                include 'vistas/paginas/403.php';
            }

        } else {
            // Página principal según sesión iniciada o no
            if (isset($_SESSION['nombre_usuario'])) {
                include('vistas/paginas/mi_perfil.php'); // Por defecto lo manda al perfil
            } else {
                include 'vistas/paginas/login.php';
            }
        }
        ?>
    </div>

    <?php include 'componentes/footer.php'; ?>

    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/all.min.js"></script>
</body>
</html>
