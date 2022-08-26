<?php

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class EmailWorker{
    protected string $name;
    protected string $email;
    protected string $message;
    protected string $timestamp;
    protected string $files;
    protected PDO $dbConnection;
    protected string $subject;

    /**
     * EmailWorker constructor.
     * @param $name
     * @param $email
     * @param $message
     * @param $sent_at
     */
    public function __construct($name, $email, $message, $subject = 'SMTP here')
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->timestamp = '' . date("Y-m-d H:i:s");
        $this->files = '{"files":[]}';
        $this->subject = $subject;
    }

    public function connectDatabase($dbName, $dbPassword = 'root', $dbUsername = 'root',
                                    $dbHost = '127.0.0.1', $dialect = 'mysql')
    {
        $this->dbConnection = new PDO($dialect . ':host=' . $dbHost .
            ';dbname=' . $dbName, $dbUsername, $dbPassword);

        $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function insert()
    {
        $data = $this->dbConnection->prepare('INSERT INTO emails(name, email, message, sent_at, files)
                                                    VALUES (:name, :email, :message, :sent_at, :files)');
        $data->bindValue('name', $this->name);
        $data->bindValue('email', $this->email);
        $data->bindValue('message', $this->message);
        $data->bindValue('sent_at', $this->timestamp);
        $data->bindValue('files', $this->files);
        $data->execute();
    }

    public function getTimestamp(): string
    {
        return '' . $this->timestamp;
    }

    public function addFile($filename)
    {
        $openedStr = mb_substr($this->files, 0, strlen($this->files) - 2);
        $openedStr .= '"' . $filename . '"]}';
        $this->files = $openedStr;
    }

    public function sendEmail($insert = false, $template = false){
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
            $mail->setFrom('dzzzmitro@mail.ru', $this->name);
            $mail->addAddress($this->email);

            $files = json_decode(($this->files))->files;
            if($files) {
                foreach ($files as $file) {
                    $path = mb_substr(getcwd(), 0, strripos(getcwd(), '\\'));
                    $path = mb_substr($path, 0, strripos($path, '\\'));
                    $mail->addAttachment($path . '\\public\\files\\' . $file);
                }
            }
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'SMTP finally here!!!';
            if($template) {
                $mail->Body = '<body>
                            <header> 
                                look it is header 
                            </header>
                            <h1>' . $this->message . '</h1>
                            <img src="https://p.kindpng.com/picc/s/227-2270137_link-svg-icon-google-link-icon-png-transparent.png" style="max-height: 50px; max-width: 50px;">
                            <footer>
                                any links here
                            </footer>
                        </body>';
            } else {
                $mail->Body = $this->message;
            }
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if($insert) {
                $this->insert();
            }

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

}