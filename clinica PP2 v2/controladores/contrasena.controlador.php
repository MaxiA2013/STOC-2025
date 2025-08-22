<?php
session_start();
require_once '../modelos/usuarios.php';

if (isset($_POST['action']) && $_POST['action'] == 'cambiar_password') {
    $usuario = new Usuario();
    $usuario->setId_usuario($_SESSION['id_usuario']);
    $resultado = $usuario->traer_usuario_por_id();

    if ($resultado && $resultado->num_rows > 0) {
        $datos = $resultado->fetch_assoc();
        $password_actual_hash = $datos['password'];

        if (!password_verify($_POST['actual'], $password_actual_hash)) {
            header("Location: ../index.php?page=mi_perfil&message=Contraseña actual incorrecta&status=danger");
            exit();
        }

        if ($_POST['nueva'] != $_POST['confirmar']) {
            header("Location: ../index.php?page=mi_perfil&message=La nueva contraseña no coincide con la confirmación&status=warning");
            exit();
        }

        $nueva_hash = password_hash($_POST['nueva'], PASSWORD_DEFAULT);
        $usuario->setPassword($nueva_hash);
        $resultado_update = $usuario->actualizar_password();

        if ($resultado_update) {
            header("Location: ../index.php?page=mi_perfil&message=Contraseña actualizada correctamente&status=success");
        } else {
            header("Location: ../index.php?page=mi_perfil&message=Error al actualizar contraseña&status=danger");
        }
    }
}

// BLOQUE PARA RESETEAR CONTRASEÑA
if (isset($_POST['action']) && $_POST['action'] == 'resetear_password') {
    $usuario = new Usuario();
    $usuario->setId_usuario($_SESSION['id_usuario']);
    
    $nueva_hash = password_hash('123456', PASSWORD_DEFAULT);
    $usuario->setPassword($nueva_hash);
    $resultado_reset = $usuario->actualizar_password();

    if ($resultado_reset) {
        header("Location: ../index.php?page=mi_perfil&message=Tu contraseña fue reseteada&status=success");
    } else {
        header("Location: ../index.php?page=mi_perfil&message=Error al resetear la contraseña&status=danger");
    }
}
?>
