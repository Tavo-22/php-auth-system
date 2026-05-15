<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendResetEmail(string $toEmail, string $toName, string $resetLink): bool
{
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'correo@gmail.com';      // tu correo Gmail
        $mail->Password   = '
        ';   // contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Remitente y destinatario
        $mail->setFrom('correo@gmail.com', 'Mi App');
        $mail->addAddress($toEmail, $toName);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Recupera tu contraseña';
        $mail->Body    = "
            <p>Hola <strong>$toName</strong>,</p>
            <p>Haz clic en el enlace para restablecer tu contraseña (válido 60 min):</p>
            <a href='$resetLink'>$resetLink</a>
            <p>Si no solicitaste esto, ignora este correo.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Mailer error: ' . $mail->ErrorInfo);
        return false;
    }
}