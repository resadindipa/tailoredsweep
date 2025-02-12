<?php
// Include database connection
include '../php/config.php';

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Convert to integer for security

    // Fetch review details using prepared statements
    $query = "SELECT id, name, review, profilepicture, review_date FROM reviews WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Convert date format for the input field
        $date_value = date("Y-m-d", strtotime($row['review_date']));
    } else {
        // Redirect if no review found
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect if no ID is provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/reviews/edit.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>

<body class="container mt-4">
    <h2>Edit Review</h2>

    <form id="editReviewForm" method="post" action="update_review.php">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <!-- Name Field -->
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
        </div>

        <!-- Profile Picture -->
        <div class="mb-3 d-flex align-items-center">
            <div class="rounded-circle border d-flex justify-content-center align-items-center" style="width: 80px; height: 80px; overflow: hidden; background: <?php echo $row['profilepicture'] ? 'none' : '#ccc'; ?>;">
                <?php if ($row['profilepicture']) { ?>
                    <img src="<?php echo htmlspecialchars($row['profilepicture']); ?>" alt="Profile Picture" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                <?php } ?>
            </div>
            <input type="file" id="profilePictureUpload" accept="image/*">
            <input type="hidden" id="profilePictureURL" name="profile_picture" value="<?= htmlspecialchars($row['profilepicture']) ?>">

            <button type="button" class="btn btn-secondary ms-3">Change Picture</button>
        </div>

        <!-- Review Date (Only Input Field, No Text Displayed) -->
        <div class="mb-3">
            <label class="form-label">Review Date</label>
            <input type="date" class="form-control" name="review_date" value="<?php echo $date_value; ?>">
        </div>

        <!-- Review Text -->
        <div class="mb-3">
            <label class="form-label">Review</label>
            <textarea class="form-control" name="review" rows="4"><?php echo htmlspecialchars($row['review']); ?></textarea>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between">
            <button type="button" id="deleteReview" class="btn btn-danger">Delete Review</button>
            <div class="d-flex gap-2">
                <button type="submit" id="saveChanges" class="btn btn-success">Save Changes</button>
                <a id="cancelEdit" class="btn btn-secondary" href="index.php">Cancel</a>
            </div>
        </div>
    </form>
    <script src="../js/reviews/edit.js"></script>
</body>

</html>