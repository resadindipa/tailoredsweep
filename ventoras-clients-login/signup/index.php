<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/signup/index.css">
    <link rel="stylesheet" href="../styles/popup.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>

<body class="container mt-4">
    <h2>Sign Up</h2>

    <form id="editReviewForm" method="post" action="update_review.php">

        <!-- Name Field -->
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="user_username">
        </div>


        <!-- Name Field -->
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="user_email">
        </div>


        <!-- Name Field -->
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="user_password1">
        </div>


        <!-- Name Field -->
        <div class="mb-3">
            <label class="form-label">Repeat Password</label>
            <input type="password" class="form-control" name="user_password1">
        </div>


        <div class="alert alert-primary" id="form-error" style="display: none;"></div>

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