<?php
namespace App\Service;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class Messagerie
{
    public function sendMail(string $login, string $mdp, string $srecepMail, string $subject, string $body){
        require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
                
try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $login;                     //SMTP username
    $mail->Password   =$mdp;                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($login, 'Admin');
    $mail->addAddress($srecepMail, 'Destinataire');     //Add a recipient
    $mail->addAddress('ellen@example.com');               //Name is optional
   

    

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
   

    $mail->send();
    return 'Le message a bien été envoyé';
} catch (Exception $e) {
    echo "Le message n'a pas été envoyé: {$mail->ErrorInfo}";
}
    }
}
