<?php
// get Composer autoloader so we can access dotenv library
require_once '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Check for empty fields
if (
  empty($_POST['name'])      ||
  empty($_POST['email'])     ||
  empty($_POST['phone'])     ||
  empty($_POST['message'])   ||
  !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
) {
  echo "No arguments Provided!";
  return false;
}

$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$phone = strip_tags(htmlspecialchars($_POST['phone']));
$message = strip_tags(htmlspecialchars($_POST['message']));


require "../vendor/PHPMailer-master/src/PHPMailer.php";
require "../vendor/PHPMailer-master/src/Exception.php";
require "../vendor/PHPMailer-master/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = $_ENV['HOST'];
$mail->SMTPAuth = true;
$mail->Username = $_ENV['USERNAME'];
$mail->Password = $_ENV['SMTP_PASSWORD'];
$mail->SMTPSecure = $_ENV['SMTP_SECURITY_METHOD'];
$mail->Port = $_ENV['PORT_NUMBER'];

$mail->setFrom('patrick@saigonbikerentals.com');
$mail->addAddress('dragonbiketoursvn@gmail.com');
$mail->addReplyTo($email_address, $name);
$mail->Subject = "Dragon Bike Tours Contact Form:  $name";
$mail->Body = "You have received a new message from your website contact form.
\n\n" . "Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";

if (!$mail->send()) {

  echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
  echo 'Heronymous Douche!';
}
