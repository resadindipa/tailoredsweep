<?php
include '../php/config.php';
$someone_logged_in = is_someone_logged_in();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/projects/add.css">
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/popup.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>

<body>
    <?php if ($someone_logged_in) { ?>
        <nav class="navbar-dark bg-dark">
            <div class="main-container-outer">
                <div class="main-container">
                    <div class="navbar">
                        <a class="navbar-brand text-white" href="index.php">Home</a>
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

        <div class="main-container-outer">
            <div class="main-container">
                <div class="main-container-header">
                    <h2 class="main-container-title">Add a Review</h2>
                </div>

                <form id="editReviewForm" method="post" action="add_review.php">



                    <!-- Name Field -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control form-text-char-count-input" maxlength="40" name="review_name" value="">
                        <p class="form-text-input-char-count">0/40</p>
                    </div>

                    <!-- Profile Picture -->
                    <label class="form-label">Client's picture</label>
                    <div class="mb-3">

                        <!-- <div class="rounded-circle" style="width: 80px; height: 80px; overflow: hidden;"> </div> -->
                        <div class="profile-picture-main-div">
                            <div id="profilepictureimg" class="rounded-circle" style="width: 80px; height: 80px; overflow: hidden;"></div>
    
                            <div class="profile-picture-action-btn-group">
                                <button type="button" id="profilePictureUploadFrontBtn" class="btn btn-secondary">Add Picture</button>
                                <button type="button" id="profilePictureRemoveBtn" class="btn btn-danger ms-3" style="display: none;">Remove Picture</button>
                            </div>
                        </div>
                        <input type="file" id="profilePictureUpload" accept="image/*" hidden>
                        <input type="hidden" id="profile_picture_link" name="review_profilepicture" value="">

                    </div>

                    <!-- Review Date (Only Input Field, No Text Displayed) -->
                    <div class="mb-3">
                        <label class="form-label">Review Date</label>
                        <input type="date" class="form-control" id="review_date_input" name="review_date" value="">
                    </div>

                    <!-- Review Text -->
                    <div class="mb-3">
                        <label class="form-label">Review</label>
                        <textarea class="form-control form-text-char-count-input" maxlength="300" name="review_desc" rows="4"></textarea>
                        <p class="form-text-input-char-count">0/300</p>
                    </div>

                    <div class="alert alert-danger" id="form-error" style="display: none;">All fields must be completed</div>
                    <!-- <label class="badge badge-danger"  id="form-error" style="font-size: 1.25rem; color: red;">All fields must be completed</label> -->

                    <!-- <label class="badge bg-warning text-dark" id="form-error" style="display: none;">All fields must be completed</label> -->

                    <button type="submit" id="submitbtn" class="btn btn-success">Add Review</button>

                    <!-- Buttons -->
                    <!-- <div class="d-flex justify-content-between">
                    <div class="d-flex gap-2">
                    </div>
                </div> -->
                </form>
            </div>
        </div>


        <script src="../js/reviews/add.js"></script>

    <?php } else {
        readfile('../php/loginrequired.html');
    } ?>
</body>

</html>