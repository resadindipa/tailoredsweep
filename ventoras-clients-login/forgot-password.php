<?php


// Include config file
require_once "php/config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, otherwise redirect to login page
if (isset($_SESSION["li"])) {
    header("location: home.php");
    exit;
}

// Define variables and initialize with empty values
$userinput = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_err = "";

    if (!(empty($_POST['userinput']))) {
        $userinput = trim($_POST["userinput"]);

        $email_entered = filter_var($userinput, FILTER_VALIDATE_EMAIL);

        if ($email_entered) {
            $sql = "SELECT id,email FROM users WHERE email = ?";
        } else {
            $sql = "SELECT id,email FROM users WHERE username = ?";
        }

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $userinput;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then proceed to send the reset link
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $user_id, $user_email);
                    if (mysqli_stmt_fetch($stmt)) {

                        // Generating the random reset key with the help of user's email and randomly generated number, both of them transformed through md5()
                        $reset_key = md5($user_email);
                        $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
                        $reset_key = $reset_key . $addKey;

                        $sql = "INSERT INTO `password_reset_links` (`email`, `reset_key`, `user_id`) VALUES ('" . $user_email . "', '" . $reset_key . "', '" . $user_id . "');";

                        //Creating a new record in database
                        if (mysqli_query($link, $sql)) {
                            //Start sending the email

                            //making the email template file accessible only for this function (this doesn't work when after this code runs)
                            define('ALLOW_ACCESS', true);

                            ob_start();

                            require 'pwd_reset_email/index.php';
                            $mail_body = ob_get_clean();


                            //Sending the Actual Email
                            $mail = new PHPMailer();

                            $mail->isSMTP();
                            $mail->Host = "smtp.gmail.com";
                            $mail->SMTPAuth = true;

                            // $mail -> SMTPDebug= 3;
                            $mail->Username = 'ventoraswebdesign@gmail.com';
                            $mail->Password = 'yrkaawhnuazeeyyo';

                            $mail->Port = '587';
                            $mail->SMTPSecure = 'tls';
                            $mail->isHTML(true);

                            $mail->setFrom('ventoraswebdesign@gmail.com', 'Ventoras Web Email');

                            $mail->addAddress($user_email);
                            $mail->Subject = 'Password Reset Key';
                            $mail->Body = $mail_body;


                            $mail->SMTPOptions = array(
                                'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                )
                            );

                            if (!$mail->Send()) {
                                $login_err = "Oops! Something wrong with Email, Please try again.";
                            } else {
                                //Successfully sent the email
                                header("location: password-reset/email-sent.html");
                                exit;
                            }
                        } else {
                            $login_err = $sql;
                            // $login_err = "Oops! Something went wrong, Please try again later. ";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "No account is registed with this";
                    if ($email_entered) {
                        $login_err .= " Email";
                    } else {
                        $login_err .= " Username";
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
        $login_err = "Username or Email Address Can't be Empty.";
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventoras Content Management System</title>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>

    <div class="main-universe">
        <div class="main-container">
            <div class="wrapper">
                <h2>Forgot Your Password?</h2>
                <p>Don't worry, we got your back!</p>
                <br>
                <?php
                if (!empty($login_err)) {
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }
                ?>

                <form action="forgot-password.php" method="post">
                    <div class="form-group">
                        <label>Username or Email Address</label>
                        <input type="text" name="userinput" class="form-control">
                    </div>
                    <br>

                    <div class="form-group">
                        <input type="submit" id="submitbtn" class="btn btn-primary" value="Continue">
                    </div>
                    <!-- <p>Don't have an account? <a href="register.php">Sign up now</a>.</p> -->
                </form>
            </div>
        </div>
    </div>

    <script src="js/main.js"></script>
</body>

</html>