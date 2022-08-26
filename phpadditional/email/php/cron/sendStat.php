<?php

require 'D:/OpenServerBackup/domains/phpprojects/email/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$dbName = 'mod4';
$dbPassword = 'root';
$dbUsername = 'root';
$dbHost = '127.0.0.1';
$dialect = 'mysql';

$sendTo = 'baiduk.dima@yandex.ru';

$dbConnection = new PDO($dialect . ':host=' . $dbHost .
    ';dbname=' . $dbName, $dbUsername, $dbPassword);

$name = 'STATISTICS';

$data = $dbConnection->prepare('SELECT COUNT(*) FROM emails');
$data->execute();
$row = $data->fetch(PDO::FETCH_ASSOC);
$message = $row['COUNT(*)'];

$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.mail.ru';                               //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                     //Enable SMTP authentication
    $mail->Username = 'dzzzmitro@mail.ru';                      //SMTP username
    $mail->Password = 'f0cScWkKN0HZKZsqxsa2';                   //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port = 465;                                          //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('dzzzmitro@mail.ru', $name);
    $mail->addAddress($sendTo);

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'SMTP finally here!!!';

    $mail->Body = $message;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}