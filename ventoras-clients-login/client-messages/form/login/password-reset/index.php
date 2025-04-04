<?php
include('db.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';


if (isset($_POST["email"]) && (!empty($_POST["email"]))) {
    $email = $_POST["email"];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $error .= "<p>Invalid email address please type a valid email address!</p>";
    } else {
        $sel_query = "SELECT * FROM `users` WHERE email='" . $email . "'";
        $results = mysqli_query($mysql, $sel_query);
        $row = mysqli_num_rows($results);
        if ($row == "") {
            $error .= "<p>No user is registered with this email address!</p>";
        }
    }
    if ($error != "") {
        echo "<div class='error'>" . $error . "</div>
   <br /><a href='javascript:history.go(-1)'>Go Back</a>";
    } else {
        // $expFormat = mktime(
        //     date("H"),
        //     date("i"),
        //     date("s"),
        //     date("m"),
        //     date("d") + 1,
        //     date("Y")
        // );
        // $expDate = date("Y-m-d H:i:s", $expFormat);
        $key = md5($email);
        $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
        $key = $key . $addKey;
        // Insert Temp Table
//         mysqli_query(
//             $mysql,
//             "INSERT INTO `password_reset_temp` (`email`, `key`, `expDate`)
// VALUES ('" . $email . "', '" . $key . "', '" . $expDate . "');"
//         );

        $sql = "INSERT INTO `password_reset_temp` (`email`, `key`)
VALUES ('" . $email . "', '" . $key . "');";

        if (mysqli_query($mysql, $sql)) {
            echo "New record created successfully. Last inserted ID is: " . mysqli_insert_id($mysql);
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($mysql);
        }

        $output = '<p>Dear user,</p>';
        $output .= '<p>Please click on the following link to reset your password.</p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p><a href="localhost/tailoredsweep/form/login/password-reset/reset-password.php?
key=' . $key . '&email=' . $email . '&action=reset" target="_blank">
localhost/tailoredsweep/form/login/password-reset/reset-password.php
?key=' . $key . '&email=' . $email . '&action=reset</a></p>';
        $output .= '<p>-------------------------------------------------------------</p>';
        $output .= '<p>Please be sure to copy the entire link into your browser.
The link will expire after 1 day for security reason.</p>';
        $output .= '<p>If you did not request this forgotten password email, no action 
is needed, your password will not be reset. However, you may want to log into 
your account and change your security password as someone may have guessed it.</p>';
        $output .= '<p>Thanks,</p>';
        $output .= '<p>AllPHPTricks Team</p>';

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

        $mail->setFrom('ventoraswebdesign@gmail.com', 'Ventoras Web Email');

        $mail->addAddress($email);
        $mail->Subject = 'Password Reset';
        $mail->Body = $output;


        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "<div class='error'>
<p>An email has been sent to you with instructions on how to reset your password.</p>
</div><br /><br /><br />";
        }


        // if ($mail->send()) {
        //     echo "success";
        // } else {
        //     echo "error";
        // }


//         $body = $output;
//         $subject = "Password Recovery - AllPHPTricks.com";

//         $email_to = $email;
//         $fromserver = "noreply@yourwebsite.com";
//         require("PHPMailer/PHPMailerAutoload.php");
//         $mail = new PHPMailer();
//         $mail->IsSMTP();
//         $mail->Host = "mail.yourwebsite.com"; // Enter your host here
//         $mail->SMTPAuth = true;
//         $mail->Username = "noreply@yourwebsite.com"; // Enter your email here
//         $mail->Password = "password"; //Enter your password here
//         $mail->Port = 25;
//         $mail->IsHTML(true);
//         $mail->From = "noreply@yourwebsite.com";
//         $mail->FromName = "AllPHPTricks";
//         $mail->Sender = $fromserver; // indicates ReturnPath header
//         $mail->Subject = $subject;
//         $mail->Body = $body;
//         $mail->AddAddress($email_to);
//         if (!$mail->Send()) {
//             echo "Mailer Error: " . $mail->ErrorInfo;
//         } else {
//             echo "<div class='error'>
// <p>An email has been sent to you with instructions on how to reset your password.</p>
// </div><br /><br /><br />";
//         }
    }
} else {
?>
    <form method="post" action="" name="reset"><br /><br />
        <label><strong>Enter Your Email Address:</strong></label><br /><br />
        <input type="email" name="email" placeholder="username@email.com" />
        <br /><br />
        <input type="submit" value="Reset Password" />
    </form>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
<?php } ?>