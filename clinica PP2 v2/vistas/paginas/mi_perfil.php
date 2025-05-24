<?php
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$nombre_usuario = $_SESSION['nombre_usuario'];
$email = $_SESSION['email'];
$perfil = $_SESSION['nombre_perfil']; // nuevo
?>

<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="content">
    <h1>Bienvenido, <?php echo $nombre_usuario; ?></h1>
    <p>Email: <?php echo $email; ?></p>
    <p>Perfil: <?php echo $perfil; ?></p>

    <?php
    if ($perfil == 'Administrador') {
        echo '
        <div class="modulos">
            <h3>Módulos de administración</h3>
            <a href="index.php?page=lista_usuario">Lista de Usuario</a><br>
            <a href="index.php?page=lista_doctor">Lista de Doctores</a><br>
            <a href="vistas/paginas/salida.php">Cerrar Sesión</a>
        </div>';
    } elseif ($perfil == 'Doctor') {
        echo '
        <div class="modulos">
            <h3>Área de Doctores</h3>
            <p>Aquí puedes ver tus horarios y datos médicos.</p>
            <a href="index.php?page=lista_pacientes">Ver Pacientes</a><br>
            <a href="vistas/paginas/salida.php">Cerrar Sesión</a>
        </div>';
    } elseif ($perfil == 'Paciente') {
        echo '
        <div class="modulos">
            <h3>Área del Paciente</h3>
            <p>Aquí puedes ver tus citas y tu historial médico.</p>
            <a href="index.php?page=mis_citas">Mis Citas</a><br>
            <a href="vistas/paginas/salida.php">Cerrar Sesión</a>
        </div>';
    } else {
        echo '<p style="color:red;">Perfil no reconocido.</p>';
    }
    ?>
</div>

// Esta línea muestra la ruta completa del archivo actual (útil para saber en qué carpeta estoy)
// echo __FILE__;
<hr>
<?php if ($perfil == 'Paciente'): ?>
    <h3>Cambiar Contraseña</h3>
    <form action="controladores/contrasena.controlador.php" method="POST">
        <input type="hidden" name="action" value="cambiar_password">
        <div class="mb-3">
            <label for="actual" class="form-label">Contraseña Actual:</label>
            <input type="password" name="actual" id="actual" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nueva" class="form-label">Nueva Contraseña:</label>
            <input type="password" name="nueva" id="nueva" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirmar" class="form-label">Confirmar Nueva Contraseña:</label>
            <input type="password" name="confirmar" id="confirmar" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
    </form>
<?php else: ?>
    <h3>Resetear mi contraseña</h3>
    <form action="controladores/contrasena.controlador.php" method="POST">
        <input type="hidden" name="action" value="resetear_password">
        <button type="submit" class="btn btn-warning" onclick="return confirm('¿Estás seguro que quieres resetear tu contraseña?')">
            Resetear a contraseña por defecto
        </button>
    </form>
<?php endif; ?>


<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
