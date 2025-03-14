<?php
require_once 'php/config.php';
$is_someone_logged_in = is_someone_logged_in();

// if (isset($_SESSION['si']) && !empty($_SESSION['si']) && isset($_SESSION['ui']) && !empty($_SESSION['ui'])) {
//     //verify the sesion id
//     $stored_session_id = $_SESSION['si'];
//     $stored_user_id = $_SESSION['ui'];

//     $sql = "SELECT session_userid FROM sessions WHERE session_id = ?";

//     if ($stmt = mysqli_prepare($link, $sql)) {
//         // Bind variables to the prepared statement as parameters
//         mysqli_stmt_bind_param($stmt, "s", $stored_session_id);


//         // Attempt to execute the prepared statement
//         if (mysqli_stmt_execute($stmt)) {
//             // Store result
//             mysqli_stmt_store_result($stmt);

//             // Check if session id exists, if yes then verify user_id
//             if (mysqli_stmt_num_rows($stmt) == 1) {
//                 // Bind result variables
//                 mysqli_stmt_bind_result($stmt, $user_id);
//                 if (mysqli_stmt_fetch($stmt)) {
//                     if ($user_id == $stored_user_id) {
//                         //all good, good to proceed to home.php
//                         $user_is_logged_in = true;
//                     }
//                 }
//             }
//         }
//     }
// }

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
        <div class="main-container-index">
            <div class="wrapper">
                <?php if ($is_someone_logged_in == false) { ?>
                    <h2>Login</h2>
                    <p>Please fill in your credentials to login.</p>
                    <?php
                    // if (!empty($login_err)) {
                    //     echo '<div class="alert alert-danger">' . $login_err . '</div>';
                    // }
                    ?>

                    <div class="alert alert-danger" id="form-error" style="display: none;">No username or email was entered!</div>


                    <form action="#" method="post" id="login-form">
                        <div class="form-group">
                            <label>Username or Email Address</label>
                            <input type="text" name="userinput" id="usernameemail" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control">

                        </div>
                        <br><br>
                        <input type="button" id="submitbtn" class="btn btn-primary" value="Login">
                        <br><br>
                        <p>Forgot Password? <a href="forgot-password.php">Reset it now</a>.</p>
                        <!-- <p>Don't have an account? <a href="register.php">Sign up now</a>.</p> -->
                    </form>

                <?php } else {
                    readfile('php/alreadyloggedin.html');
                } ?>
            </div>
        </div>
    </div>


    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>