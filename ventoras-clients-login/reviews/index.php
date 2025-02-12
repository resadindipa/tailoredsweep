<?php
// Database connection
include '../php/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/reviews.css">
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Customer Reviews</h2>
        <div id="reviews-container">
            <!-- Reviews will be loaded here via AJAX -->
        </div>
        
        <button id="load-more" class="btn btn-primary mt-3">Load More</button>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/reviews.js"></script>
    
</body>

</html>
