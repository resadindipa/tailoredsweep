<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


$mail = new PHPMailer();

$mail ->isSMTP();
$mail ->Host ="smtp.gmail.com";
$mail ->SMTPAuth = true;
// $mail -> SMTPDebug= 3;
$mail ->Username = 'ventoraswebdesign@gmail.com';
$mail ->Password = 'yrkaawhnuazeeyyo';
//$mail ->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
$mail ->Port = '587';
$mail ->SMTPSecure ='tls';
$mail ->isHTML(true);

$mail ->setFrom('ventoraswebdesign@gmail.com','Ventoras');

$mail ->addAddress('mwresadindipa@gmail.com');
$mail ->Subject = 'HelloWorld';
$mail ->Body = 'a test email';


$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);



if ($mail->send()){
    echo "Message has been sent successfully";
}else{
    echo "Mailer Error: " . $mail->ErrorInfo;
}

//   $mail = new PHPMailer();
//   $mail->IsSMTP();

//   $mail->SMTPDebug  = 0;  
//   $mail->SMTPAuth   = true;
//   $mail->SMTPSecure = "tls";
//   $mail->Port       = 587;
//   $mail->SMTPKeepAlive = true;   
//   $mail->Host       = "smtp.gmail.com";
//   $mail->Username   = "ventoraswebdesign@gmail.com";
//   $mail->Password   = "kmwimbhdoeyizbtr";

//   $mail->IsHTML(true);
//   $mail->AddAddress("mwresadindipa@gmail.com", "Resad Indipa");
//   $mail->SetFrom("ventoraswebdesign@gmail.com", "Ventoras");
//   $mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
//   $content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";

//   $mail->MsgHTML($content); 
//   if(!$mail->Send()) {
//     echo "Error while sending Email.";
//     var_dump($mail);
//   } else {
//     echo "Email sent successfully";
//   }

// $servers = array(
//     array("smtp.gmail.com", 465),
//     array("smtp.gmail.com", 587),
// );

// foreach ($servers as $server) {
//     list($server, $port) = $server;
//     echo "<h1>Attempting connect to <tt>$server:$port</tt></h1>\n";
//     flush();
//     $socket = fsockopen($server, $port, $errno, $errstr, 10);
//     if(!$socket) {
//       echo "<p>ERROR: $server:$portsmtp - $errstr ($errno)</p>\n";
//     } else {
//       echo "<p>SUCCESS: $server:$port - ok</p>\n";
//     }
//     flush();
// }