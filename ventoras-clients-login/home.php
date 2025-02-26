<?php
// Include config file
require_once "php/config.php";

$user_allowed_to_stay = false;

// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['si']) && !empty($_SESSION['si']) && isset($_SESSION['ui']) && !empty($_SESSION['ui'])) {
    //verify the sesion id
    $stored_session_id = $_SESSION['si'];
    $stored_user_id = $_SESSION['ui'];

    $sql = "SELECT session_userid FROM sessions WHERE session_id = ?";

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
                        //all good, keep the user in home.php
                        $user_allowed_to_stay = true;
                    }
                }
            }
        }
    }
}

if ($user_allowed_to_stay == false) {
    redirect_to_login();
}


function redirect_to_login()
{
    // echo "redirecting";
    // Redirect to login page
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Home Button -->
            <a class="navbar-brand" href="home.php">Home</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <!-- Logout Button -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h3 class="mb-4">Content Management</h3>
        <br>
        <ul class="list-unstyled">
            <li class="mb-3">
                <h4><a href="reviews/" class="text-decoration-none">Manage Reviews</a></h4>
                <p class="text-muted">View, edit, and manage customer reviews.</p>
            </li>
            <hr>
            <li class="mt-3">
                <h4><a href="projects/" class="text-decoration-none">Manage Projects</a></h4>
                <p class="text-muted">View, edit, and manage Projects</p>
            </li>
        </ul>
    </div>

</body>

</html>