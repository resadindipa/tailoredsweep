<?php
// Database connection
include '../php/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/projects.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Home Button -->
            <a class="navbar-brand" href="../home.php">Home</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <!-- Logout Button -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h2 class="mb-4">Projects</h2>
        <button class="btn btn-primary mt-3">
            <a href="add.php">Add a New Review</a>
        </button>
        <br><br>
        <div id="projects-container">
            <!-- Reviews will be loaded here via AJAX -->
        </div>

        <button id="load-more" style="display: none;" class="btn btn-primary mt-3">Load More</button>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/projects.js"></script>

</body>

</html>