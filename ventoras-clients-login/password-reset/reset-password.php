<?php
// require_once "../php/config.php";
// $valid_link = false;
// $expired_link = true;
// $user_id = "";
// $user_email = "";

// //Only shows the 'Enter a new password' form if the reset key is valid and hasn't expired
// if (isset($_GET['action']) && isset($_GET['key'])) {
//     // echo "1";
//     if (($_GET['action']) == "reset") {
//         $valid_link = true;
//         //verify the reset key
//         $reset_key_url = $_GET['key'];
//         $sql = "SELECT user_id, email, created_date FROM password_reset_links WHERE reset_key = ?";


//         if ($stmt = mysqli_prepare($link, $sql)) {
//             // Bind variables to the prepared statement as parameters
//             mysqli_stmt_bind_param($stmt, "s", $reset_key_url);


//             // Attempt to execute the prepared statement
//             if (mysqli_stmt_execute($stmt)) {
//                 // Store result
//                 mysqli_stmt_store_result($stmt);

//                 // Check if username exists, if yes then verify password
//                 if (mysqli_stmt_num_rows($stmt) == 1) {
//                     // Bind result variables
//                     mysqli_stmt_bind_result($stmt, $user_id, $user_email, $created_date);
//                     if (mysqli_stmt_fetch($stmt)) {


//                         //Check if the reset link is expired
//                         $expired_link = is_link_expired($created_date);
//                         // if ($expired_link) {
//                         //     echo "expired_link->true";
//                         // } else {
//                         //     echo "expired_link->false";
//                         // }
//                     }
//                 }
//             }
//         }
//     } else {
//         $valid_link = false;
//     }
// }


// function is_link_expired($created_date)
// {
//     $minutes_the_link_is_valid = 60;
//     $created_date = new DateTime($created_date);
//     $current_date = new DateTime();

//     $interval = $current_date->diff($created_date);
//     $minutesDifference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i; // Convert to minutes

//     // echo $minutesDifference . "----" . $current_date->format('Y-m-d H:i:s');
//     // Check if the second date is 60 or more minutes into the past
//     if ($created_date < $current_date && $minutesDifference >= $minutes_the_link_is_valid) {
//         return true;
//     } else {
//         return false;
//     }
// }

?>

<!DOCTYPE html>
<html lang="en">
<!-- https://www.pornhub.com/view_video.php?viewkey=642c9fb599afc
 https://www.pornhub.com/view_video.php?viewkey=6538e809c18c6
 https://www.pornhub.com/view_video.php?viewkey=662018a0d3941
 -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/index.css">
</head>

<body>


    <div class="wrapper">
        <h2>Reset your Password</h2>
        <p>Please type a new password below!</p>
        <div class="alert alert-danger" id="form-error-div" style="display: none;" role="alert">
            <p id="form-error"></p>
        </div>

        <div class="alert alert-success" style="display: none;" id="form-success-div" role="alert">
            <p id="form-success">Password Changed Successfully.</p>
        </div>
        <div id="form-success-next" style="display: none;">
            <button type="button" class="btn btn-success"><a style="color: white!important; text-decoration: none!important;" href="../index.php">Go back to Login</a></button>
        </div>
        <form action="#" id="password-form" method="post">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" id="password1" name="password1" class="form-control">
            </div>
            <br>
            <div class="form-group">
                <label>Repeat it again</label>
                <input type="password" id="password2" name="password2" class="form-control">

            </div>
            <br><br>
            <div class="form-group">
                <input type="submit" id="submit-form" class="btn btn-primary" value="Change Password">
            </div>
            <br>
            <!-- <p>Don't have an account? <a href="register.php">Sign up now</a>.</p> -->
        </form>
    </div>

</body>

<script src="../js/jquery-3.7.1.min.js"></script>
<script src="../js/reset-password.js"></script>

</html>