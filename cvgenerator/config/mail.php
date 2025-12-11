
<?php
use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../vendor/autoload.php';

function mailer(): PHPMailer {
  $mail = new PHPMailer(true);
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'maingusamweli@gmail.com';
  $mail->Password = 'fcmw jpjl mryn jciw'; // Use Gmail App Password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  $mail->setFrom('maingusamweli@gmail.com', 'CV Verification');
  $mail->isHTML(true);
  return $mail;
}