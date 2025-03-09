<?php
// Include config file
require_once "php/config.php";
$someone_logged_in = is_someone_logged_in();

$user_allowed_to_stay = false;



if ($someone_logged_in) {
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page - Ventoras Clients Management</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css"> -->

</head>

<body>

    <?php if ($someone_logged_in == true && $user_allowed_to_stay == true) { ?>
        <nav class="navbar-dark bg-dark">
            <div class="main-container-outer">
                <div class="main-container">
                    <div class="navbar">
                        <a class="navbar-brand text-white" href="home.php">Home</a>
                        <button class="logout-btn">
                            <a href="logout.php">
                                <span>Logout</span>
                                <img src="content/logout.svg" alt="Logout">
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        <!-- <br> -->
        <div class="main-container-outer">
            <div class="main-container">
                <div class="main-container-header">
                    <h2 class="main-container-title">Content Management</h2>

                </div>

                <br>

                <ul class="list-unstyled">
                    <li class="mb-3">
                        <h4 class="section-item-title"><a href="reviews/">Manage Reviews</a></h4>
                        <p class="section-item-desc text-muted">View, edit, and manage customer reviews.</p>
                    </li>
                    <hr>
                    <li class="mt-3">
                        <h4 class="section-item-title"><a href="projects/">Manage Projects</a></h4>
                        <p class="section-item-desc text-muted">View, edit, and manage Projects</p>
                    </li>
                </ul>
            </div>
        </div>


        <script src="js/jquery-3.7.1.min.js"></script>
        <!-- <script src="../js/reviews.js"></script> -->

    <?php } else {
        readfile('php/loginrequired.html');
    } ?>

</body>

</html>