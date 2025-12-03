<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php'; // Ajustá la ruta según tu proyecto

function enviarNotificacionLogin($email, $nombre_usuario) {
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';      // Servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'lukascolman88@gmail.com'; // Usuario SMTP
        $mail->Password = 'ndct qydk xgsa nfsd';            // Contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                    // Seguridad (tls/ssl)
        $mail->Port = 587;                            // Puerto SMTP
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
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Notificación de inicio de sesión';
        $mail->Body = "
            Hola <strong>$nombre_usuario</strong>,<br><br>
            Se detectó un inicio de sesión en tu cuenta el <strong>" . date('d/m/Y H:i') . "</strong>.<br>
            Si no fuiste vos, por favor comunicate con soporte.<br><br>
            <em>Este es un mensaje automático, no respondas este correo.</em>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar notificación de login: " . $mail->ErrorInfo);
        return false;
    }
}
