<?php

require_once '../php/config.php';
$someone_logged_in = is_someone_logged_in();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Before After Images</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/projects.css">
    <link rel="stylesheet" href="../styles/navbar.css">

    <style>
        .project-item {
            margin-bottom: 35px;
        }
    </style>
</head>

<body>
    <?php if ($someone_logged_in) { ?>
        <nav class="navbar-dark bg-dark">
            <div class="main-container-outer">
                <div class="main-container">
                    <div class="navbar">
                        <a class="navbar-brand text-white" href="../home.php">Home</a>
                        <button class="logout-btn">
                            <a href="../logout.php">
                                <span>Logout</span>
                                <img src="../content/logout.svg" alt="Logout">
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
                    <h2 class="main-container-title">Before & After Images</h2>
                    <div>
                        <br>
                        <div class="alert alert-danger" id="form-error" style="display: none;">Your website doesn't have any Before & After Image Sections</div>
                        <br>
                    </div>

                </div>

                <br>
                <div id="beforeafter-container"></div>


            </div>
        </div>

        <script src="../js/jquery-3.7.1.min.js"></script>
        <script src="../js/beforeafter.js"></script>
    <?php } else {
        readfile('../php/loginrequired.html');
    } ?>






</body>

</html>