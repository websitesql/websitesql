<?php declare(strict_types=1);

namespace WebsiteSQL\WebsiteSQL\Providers;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use WebsiteSQL\WebsiteSQL\App;

class MailProvider
{
    /*
     * This object holds the Medoo database connection
     * 
     * @var Medoo
     */
    private App $app;

    /*
     * Constructor
     * 
     * @param string $realm
     * @param Medoo $database
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /*
     * This method sends an email
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function send(string $to, string $subject, string $message): bool
    {
        // Get the mail configuration
        $mailDriver = $this->app->getEnv('MAIL_DRIVER');
        
        // Check if the mail driver is set
        if ($mailDriver === null) {
            return false;
        }

        // Switch between mail drivers
        switch ($mailDriver) {
            case 'mail':
                return $this->sendMail($to, $subject, $message);
            case 'smtp':
                return $this->sendSmtp($to, $subject, $message);
            case 'log':
                return $this->sendLog($to, $subject, $message);
            default:
                return false;
        }
    }

    /*
     * This method sends an email using the mail() function
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    private function sendMail(string $to, string $subject, string $message): bool
    {
        $headers  = "From: " . $this->app->getCustomization()->getApplicationName(false) . " <" . strip_tags($this->app->getEnv('MAIL_FROM')) . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        return mail($to, $subject, $message, $headers);
    }

    /*
     * This method sends an email using SMTP
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    private function sendSmtp(string $to, string $subject, string $message): bool
    {
        // Get the SMTP configuration
        $smtpFrom = $this->app->getEnv('SMTP_FROM');
        $smtpHost = $this->app->getEnv('SMTP_HOST');
        $smtpPort = $this->app->getEnv('SMTP_PORT');
        $smtpUsername = $this->app->getEnv('SMTP_USERNAME');
        $smtpPassword = $this->app->getEnv('SMTP_PASSWORD');

        // Check if the SMTP configuration is set
        if ($smtpHost === null || $smtpPort === null || $smtpUsername === null || $smtpPassword === null) {
            return false;
        }

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP

            if ($this->app->getEnv('DEBUG') === 'true') {
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                  //Enable verbose debug output
            }
            
            $mail->Host       = $smtpHost;                              //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $smtpUsername;                          //SMTP username
            $mail->Password   = $smtpPassword;                          //SMTP password
            $mail->Port       = $smtpPort;                              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            switch($smtpPort) {
                case 465:
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    //Enable implicit TLS encryption
                    break;
                case 587:
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable explicit TLS encryption
                    break;
            }
        
            //Recipients
            $mail->setFrom($smtpFrom, $this->app->getCustomization()->getApplicationName());

            $mail->addAddress($to);                                   //Add a recipient
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
        
            $mail->send();
            
            return true;
        } catch (Exception $e) {
            throw new Exception("Message could not be sent. Mailer Error: " . $e->getMessage());
        }
    }

    /*
     * This method logs the email to the log file
     * 
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return bool
     */
    private function sendLog(string $to, string $subject, string $message): bool
    {
        $logMessage = "To: $to\nSubject: $subject\nMessage: $message\n\n";

        return error_log($logMessage);
    }
}