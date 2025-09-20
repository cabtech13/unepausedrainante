<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';
require __DIR__ . '/phpmailer/Exception.php';

function sendMail($to, $subject, $message, $fromEmail = "contact@unepausedrainante.fr", $fromName = "Une Pause Drainante") {
    $mail = new PHPMailer(true);

    try {
        // Debug SMTP (affiché dans les logs PHP ou sur la page si tu veux tester)
        // $mail->SMTPDebug = 2;             // 0 = off, 1 = erreurs, 2 = détails complet
        // $mail->Debugoutput = 'html';      // 'error_log' ou 'html'

        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // SMTP IONOS
        $mail->isSMTP();
        $mail->Host       = 'smtp.ionos.fr';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contact@unepausedrainante.fr'; // ⚠️ ton adresse email complète
        $mail->Password   = 'Auriol13390@';                   // ⚠️ mot de passe exact de cette boîte
        $mail->SMTPSecure = 'ssl';                           // 'ssl' ou 'tls'
        $mail->Port       = 465;                             // 465 (SSL) ou 587 (TLS)

        // Expéditeur
        $mail->setFrom($fromEmail, $fromName);

        // Destinataire
        $mail->addAddress($to);

        // Contenu
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;

    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo; // affichage direct pour debug
        return false;
    }
}
