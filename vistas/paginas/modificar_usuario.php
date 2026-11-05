<?php
require_once "modelos/usuarios.php";

if (!isset($_POST['id_usuario']) && !isset($_GET['id'])) {
    header("Location: index.php?page=lista_usuario");
    exit();
}

$id_usuario = $_POST['id_usuario'] ?? $_GET['id'];

$usuario = new Usuario();
$usuario->setId_usuario($id_usuario);

$datos = $usuario->buscar_usuario();

if ($datos->num_rows == 0) {
    header("Location: index.php?page=lista_usuario");
    exit();
}

$row = $datos->fetch_assoc();
?>

<div class="container mt-4">
    <h2>Modificar Usuario</h2>

    <form method="POST" action="controladores/usuarios/usuarios_controlador.php">

        <input type="hidden" name="action" value="actualizacion">
        <input type="hidden" name="id_usuario" value="<?php echo $row['id_usuario']; ?>">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input
                type="text"
                class="form-control"
                name="nombre"
                value="<?php echo $row['nombre']; ?>"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Apellido</label>
            <input
                type="text"
                class="form-control"
                name="apellido"
                value="<?php echo $row['apellido']; ?>"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Nacimiento</label>
            <input
                type="date"
                class="form-control"
                name="fecha_nacimiento"
                value="<?php echo $row['fecha_nacimiento']; ?>"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Sexo</label>
            <select class="form-select" name="sexo">

                <option value="1"
                    <?php if($row['sexo']==1) echo "selected"; ?>>
                    Masculino
                </option>

                <option value="2"
                    <?php if($row['sexo']==2) echo "selected"; ?>>
                    Femenino
                </option>

                <option value="3"
                    <?php if($row['sexo']==3) echo "selected"; ?>>
                    Otro
                </option>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre Usuario</label>
            <input
                type="text"
                class="form-control"
                name="nombre_usuario"
                value="<?php echo $row['nombre_usuario']; ?>"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input
                type="email"
                class="form-control"
                name="email"
                value="<?php echo $row['email']; ?>"
                required>
        </div>

        <h3>Resetear contraseña</h3>
        <form action="controladores/contrasena.controlador.php" method="POST">
            <input type="hidden" name="action" value="resetear_password">
            <button type="submit" class="btn btn-warning" onclick="return confirm('¿Estás seguro que quieres resetear la contraseña?')">
                Resetear a contraseña por defecto
            </button>
        </form>

        <button type="submit" class="btn btn-success">
            Actualizar
        </button>

        <a href="index.php?page=lista_usuario" class="btn btn-secondary">
            Cancelar
        </a>

    </form>
</div>