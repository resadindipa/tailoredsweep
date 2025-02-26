<?php


// Include config file
require_once "php/config.php";


// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["li"])) {

    if ($_SESSION['li'] === true) {


        //verify the sesion id
        $stored_session_id = $_SESSION['si'];
        $stored_user_id = $_SESSION['ui'];

        $sql = "SELECT user_id FROM sessions WHERE session_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $stored_session_id);


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if session id exists, if yes then verify user_id
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $user_id);
                    if (mysqli_stmt_fetch($stmt)) {
                        if ($user_id == $stored_user_id) {
                            //all good, good to proceed to home.php
                            header("location: home.php");
                            exit;
                        } else {
                            //user id has been changed by client
                            logout();
                        }
                    } else {
                        //something wrong
                        logout();
                    }
                } else {
                    //no such session exists
                    logout();
                }
            } else {
                //something went wrong!
                logout();
            }
        }
    } else {
        //client has edited the session - 'li'
        logout();
    }
} 


// Define variables and initialize with empty values
$userinput = $password = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_err = "";

    if (!(empty($_POST['userinput']) || empty($_POST['password']))) {
        $userinput = trim($_POST["userinput"]);
        $password = trim($_POST["password"]);

        $email_entered = filter_var($userinput, FILTER_VALIDATE_EMAIL);

        if ($email_entered) {
            $sql = "SELECT id, username, password, email FROM users WHERE email = ?";
        } else {
            $sql = "SELECT id, username, password, email FROM users WHERE username = ?";
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
                    mysqli_stmt_bind_result($stmt, $user_id, $username, $hashed_password, $user_email);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {

                            //create a new session
                            $sql = "INSERT INTO sessions (session_id, user_id) VALUES (?, ?)";

                            if ($stmt_2 = mysqli_prepare($link, $sql)) {
                                //generating the random session key
                                $session_id = generate_random_string(15);
                                
                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt_2, "si", $session_id, $user_id);
                                if (mysqli_stmt_execute($stmt_2)) {

                                    // Store data in session variables
                                    session_start();

                                    $_SESSION["li"] = true;
                                    $_SESSION["ui"] = $user_id;
                                    $_SESSION["si"] = $session_id;

                                    // Redirect user to welcome page
                                    header("location: home.php");

                                    $login_err = "Login Success";
                                }
                                // Close statement
                                mysqli_stmt_close($stmt_2);
                            }
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {

        if (empty($_POST['userinput'])) {
            $login_err = "Username or Email Address";
        } else {
            $login_err = "Password";
        }
        $login_err .= " Can't be Empty.";
    }
}

function generate_random_string($length)
{
    return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / 62))), 0, $length);
}

function logout()
{

    
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to login page
    header("location: index.php");
    exit;
}

?>