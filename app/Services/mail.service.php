<?php
require_once __DIR__ . '/../../public/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../../public/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../../public/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($destinataire, $prenom, $passwordTemporaire, $loginUrl)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fallousenghor@gmail.com';
        $mail->Password = 'mlqw aakj ojpk lomx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('fallousenghor@gmail.com', 'Fallou Senghor');
        $mail->addAddress($destinataire, $prenom);

        $mail->isHTML(true);
        $mail->Subject = 'Bienvenue sur la plateforme';
        $mail->Body = "
            Bonjour $prenom,<br><br>
            Voici vos informations de connexion :<br>
            Email : <strong>$destinataire</strong><br>
            Mot de passe temporaire : <strong>$passwordTemporaire</strong><br><br>
            Cliquez ici pour vous connecter : <a href='$loginUrl'>$loginUrl</a><br><br>
            <strong>Important :</strong> Vous devez changer ce mot de passe temporaire lors de votre première connexion.<br><br>
            Cordialement,<br>L'équipe.
        ";

        $mail->send();
        echo 'Email envoyé avec succès.';
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}");
        echo 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer plus tard.';
    }
}
?>