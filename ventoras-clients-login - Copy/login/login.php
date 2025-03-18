<?php


// Include config file
require_once "../php/config.php";

// $user_is_logged_in = false;

// Initialize the session
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }


// $_SESSION['si'] --> Session ID --> String
// $_SESSION['ui'] --> User ID --> String


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






// Processing form data when form is submitted
if (isset($_POST['usernameemail']) && isset($_POST['password'])) {

    // Define variables and initialize with empty values
    $userinput = $password = "";


    if (!(empty($_POST['usernameemail']) || empty($_POST['password']))) {
        $userinput = trim($_POST["usernameemail"]);
        $password = trim($_POST["password"]);

        $email_entered = filter_var($userinput, FILTER_VALIDATE_EMAIL);

        if ($email_entered) {
            $sql = "SELECT id, username, password FROM users WHERE email = ?";
        } else {
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
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

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $user_id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {

                            //create a new session
                            $sql = "INSERT INTO sessions (session_id, session_userid) VALUES (?, ?)";

                            if ($stmt_2 = mysqli_prepare($link, $sql)) {
                                //generating the random session key
                                $session_id = generate_random_string(20);

                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt_2, "ss", $session_id, $user_id);
                                if (mysqli_stmt_execute($stmt_2)) {

                                    // Store data in session variables
                                    if (session_status() === PHP_SESSION_NONE) {
                                        session_start();
                                    }

                                    $_SESSION["ui"] = $user_id;
                                    $_SESSION["si"] = $session_id;

                                    // Redirect user to welcome page
                                    // header("location: home.php");

                                    print_update_status_basic_layout(true, "success");
                                }
                                // Close statement
                                mysqli_stmt_close($stmt_2);
                            }
                        } else {
                            // Password is not valid, display a generic error message
                            print_update_status_basic_layout(false, "Invalid Username / Email or Password.");
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    print_update_status_basic_layout(false, "Invalid Username / Email or Password.");
                }
            } else {
                print_update_status_basic_layout(false, "Something went wrong. Try again.");
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {

        if (empty($_POST['userinput'])) {
            print_update_status_basic_layout(false, "Username / Email can't be empty");
        } else {
            print_update_status_basic_layout(false, "Password can't be empty.");
        }
    }
} else {
    print_update_status_basic_layout(false, "Missing Params.");
}

function generate_random_string($length)
{
    return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / 62))), 0, $length);
}
