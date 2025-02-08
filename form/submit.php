<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// ini_set('display_errors', 0);
ini_set('display_errors', 1); // for the development PC only
error_reporting(E_ALL);
// Check if the POST request contains the expected parameters
if (isset($_POST['name'], $_POST['phonenumber'], $_POST['message'])) {

    // Sanitize and extract data
    $name = htmlspecialchars($_POST['name']);
    $phonenumber = htmlspecialchars($_POST['phonenumber']);
    $message = htmlspecialchars($_POST['message']);

    sendEmail($name, $phonenumber, $message);
} else {
    echo 'Missing required parameters';
}

function sendEmail($name, $phonenumber, $message)
{

    $recipient_email = "mwresadindipa@gmail.com";
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    // $mail -> SMTPDebug= 3;
    $mail->Username = 'ventoraswebdesign@gmail.com';
    $mail->Password = 'yrkaawhnuazeeyyo';
    //$mail ->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = '587';
    $mail->SMTPSecure = 'tls';
    $mail->isHTML(true);

    $mail->setFrom('ventoraswebdesign@gmail.com', 'Ventoras');

    $mail->addAddress($recipient_email);
    $mail->Subject = 'New Message Received';
    $mail->Body = '<html>Name: '.$name.'<br>Phone number: '.$phonenumber.'<br>Message: '.$message.'</html>';


    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );



    if ($mail->send()) {
        echo "Message has been sent successfully";
    } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
