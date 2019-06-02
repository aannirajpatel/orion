<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require '../includes/db.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
if (isset($_POST['email_contact']) || isset($_POST['phone_contact'])) {
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output set 2 for verbose
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'bmc581998@gmail.com';                     // SMTP username
            $mail->Password = '8140019001';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('noreply@orionpublication.com', 'Orion Publication');
            $mail->addAddress('bmc581998@gmail.com', 'Bhavesh Chauhan');     // Add a recipient
            $mail->addReplyTo($_POST['email_contact'], $_POST['name_contact']." ".$_POST['lastname_contact']);
            /*$mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');*/

            // Attachments
            /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Contact Request from Orion E-Learning';
            $mail->Body = "Requester: ".$_POST['name_contact']." ".$_POST['lastname_contact'].", Email: ".$_POST['email_contact'].", Phone: ".$_POST['phone_contact']."<br>Message:<br>".$_POST['message_contact'];
            $mail->send();
            echo "
            <h3>Message Sent!</h3>
            ";
        } catch (Exception $e) {
            echo "
            <h3>
            Message could not be sent. Try refreshing the page and sending again. Mailer Error: {$mail->ErrorInfo}
            </h3>
            ";
        }
}
?>