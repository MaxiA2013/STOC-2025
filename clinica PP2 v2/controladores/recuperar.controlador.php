<?php
session_start();
require_once '../modelos/conexion.php'; // Ajustá la ruta según tu proyecto
require '../vendor/autoload.php'; // Incluye PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$con = new Conexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['action'] ?? '';

    // 1) Solicitar recuperación
    if ($accion === 'solicitar_recuperacion') {
        $email = trim($_POST['email_recuperar'] ?? '');

        if ($email === '') {
            $_SESSION['msg'] = 'Por favor ingresá un correo válido.';
            header('Location: ../index.php?page=recuperar');
            exit;
        }

        // Buscar usuario por email
        $resultado = $con->consultar("SELECT id_usuario FROM usuario WHERE email = '$email' LIMIT 1");
        $usuario = $resultado ? $resultado->fetch_assoc() : null;

        // Generar token
        $token = bin2hex(random_bytes(32));
        $fechaVencimiento = date('Y-m-d H:i:s', time() + 3600); // 1 hora

        if ($usuario) {
            // Guardar token en tabla recuperar_contrasena
            $query = "INSERT INTO recuperar_contrasena (id_usuario, token_recuperar, fecha_vencimiento, usado) 
                      VALUES ('{$usuario['id_usuario']}', '$token', '$fechaVencimiento', 0)";
            $con->insertar($query);

            // Crear enlace de recuperación
            $recoveryLink = "http://localhost/STOC-2025-mi-ramita/clinica PP2 v2/index.php?page=resetear&email={$email}&token=".$token;

            // Configuración de PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'lukascolman88@gmail.com'; // tu correo
                $mail->Password = 'ndct qydk xgsa nfsd'; // tu contraseña de aplicación
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                // Remitente y destinatario
                $mail->setFrom('lukascolman88@gmail.com', 'STOC - Sistema de Turnos Online de Clinicas');
                $mail->addAddress($email);

                // Contenido
                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de Contraseña';
                $mail->Body = "<p>Hola,</p>
                               <p>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el enlace a continuación para continuar:</p>
                               <p><a href='{$recoveryLink}'>Recuperar Contraseña</a></p>
                               <p>Si no has solicitado un restablecimiento de contraseña, ignora este correo.</p>";

                $mail->send();
                $_SESSION['msg'] = 'Si el correo está registrado, te enviamos un enlace de recuperación.';
            } catch (Exception $e) {
                $_SESSION['msg'] = "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            }
        } else {
            // Mensaje neutro
            $_SESSION['msg'] = 'Si el correo está registrado, te enviamos un enlace de recuperación.';
        }

        header('Location: ../index.php?page=recuperar');
        exit;
    }

    // 2) Resetear con token
    if ($accion === 'resetear_con_token') {
        $email = trim($_POST['email'] ?? '');
        $token = trim($_POST['token'] ?? '');
        $nueva = trim($_POST['nueva'] ?? '');
        $confirmar = trim($_POST['confirmar'] ?? '');

        if ($nueva === '' || $confirmar === '' || $token === '') {
            $_SESSION['msg'] = 'Datos incompletos.';
            header('Location: ../index.php?page=resetear&token=' . urlencode($token));
            exit;
        }

        if ($nueva !== $confirmar) {
            $_SESSION['msg'] = 'Las contraseñas no coinciden.';
            header('Location: ../index.php?page=resetear&token=' . urlencode($token));
            exit;
        }

        // Buscar token válido
        $resultado = $con->consultar("SELECT id_recuperar, id_usuario, fecha_vencimiento, usado 
                                      FROM recuperar_contrasena 
                                      WHERE token_recuperar = '$token' LIMIT 1");
        $row = $resultado ? $resultado->fetch_assoc() : null;

        if (!$row) {
            $_SESSION['msg'] = 'Token inválido.';
            header('Location: ../index.php?page=recuperar');
            exit;
        }

        if ($row['usado']) {
            $_SESSION['msg'] = 'El enlace ya fue usado.';
            header('Location: ../index.php?page=recuperar');
            exit;
        }

        if (strtotime($row['fecha_vencimiento']) < time()) {
            $_SESSION['msg'] = 'El enlace ya venció.';
            header('Location: ../index.php?page=recuperar');
            exit;
        }

        $id = $con->consultar("SELECT id_usuario FROM clinica.usuario WHERE email = '$email'");
        

        // Actualizar contraseña en tabla usuario
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        // echo "UPDATE usuario SET password = '$hash' WHERE id_usuario = $id";
        foreach ($id as $i){
            $id = $i['id_usuario'];
        }

        $con->actualizar("UPDATE usuario SET password = '$hash' WHERE id_usuario = $id");

        // Marcar token como usado
        $con->actualizar("UPDATE recuperar_contrasena SET usado = 1 WHERE id_recuperar = '{$row['id_recuperar']}'");

        $_SESSION['msg'] = 'Tu contraseña fue actualizada. Ahora podés iniciar sesión.';
        header('Location: ../index.php?page=login');
        exit;
    }
}
