<?php

require_once '../php/config.php';
$someone_logged_in = is_someone_logged_in();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/reviews.css">
    <link rel="stylesheet" href="../styles/navbar.css">

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
                    <h2 class="main-container-title">Reviews</h2>
                    <button class="btn btn-primary">
                        <img src="../content/add.svg" alt="Plus Icon" width="16" height="16">
                        <a href="add.php">Add a New Review</a>
                    </button>
                </div>

                <br>
                <div id="reviews-container"></div>

                <div class="text-center">
                    <button id="load-more" class="btn btn-primary">Load More</button>

                </div>
            </div>
        </div>

        <script src="../js/jquery-3.7.1.min.js"></script>
        <script src="../js/reviews.js"></script>
    <?php } else {
        readfile('../php/loginrequired.html');
    } ?>






</body>

</html>