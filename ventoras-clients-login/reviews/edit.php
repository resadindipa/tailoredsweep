<?php
// Include database connection
include '../php/config.php';

$someone_logged_in = is_someone_logged_in();;
$no_such_review = true;

//Check if the user is logged in


if ($someone_logged_in == true) {

    // Check if an ID is provided
    if (isset($_GET['id'])) {
        $id = $_GET['id']; // Convert to integer for security

        // Fetch review details using prepared statements
        $query = "SELECT id, review_name, review_desc, review_profilepicture, review_date FROM reviews WHERE id = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Convert date format for the input field
            $date_value = date("Y-m-d", strtotime($row['review_date']));
            $no_such_review = false;
        }
    }
}



// else {
// Redirect if no ID is provided
// header("Location: index.php");
// $no_such_review = true;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/reviews/edit.css">
    <link rel="stylesheet" href="../styles/navbar.css">
    <link rel="stylesheet" href="../styles/popup.css">

    <script src="../js/jquery-3.7.1.min.js"></script>
</head>

<body>

    <?php if ($someone_logged_in == true) { ?>
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

        <div class="main-container-outer">
            <div class="main-container">
                <?php if ($no_such_review == false) { ?>
                    <div class="main-container-header">
                        <h2 class="main-container-title">Edit Review</h2>
                    </div>


                    <form id="editReviewForm" method="post" action="update_review.php">
                        <input type="hidden" name="review_id" value="<?php echo $row['id']; ?>">

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="review_name" value="<?php echo htmlspecialchars($row['review_name']); ?>">
                        </div>

                        <!-- Profile Picture -->
                        <div class="mb-3 d-flex align-items-center">
                            <div class="rounded-circle border d-flex justify-content-center align-items-center" style="width: 80px; height: 80px; overflow: hidden; background: <?php echo $DEFAULT_PROFILE_PICTURE_BG_COLOR; ?>;">

                                <img <?php if ($row['review_profilepicture']) {
                                            echo 'src="' . $PROFILE_PICTURE_LINK_BASE .  htmlspecialchars($row['review_profilepicture']) . '"';
                                        } ?> id="profilepictureimg" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">

                            </div>
                            <input type="file" id="profilePictureUpload" accept="image/*">
                            <input type="hidden" id="profilePictureURL" name="review_profilepicture" value="<?php echo htmlspecialchars($row['review_profilepicture']); ?>">

                            <button type="button" id="profilePictureUploadFrontBtn" class="btn btn-secondary ms-3">Change Picture</button>
                        </div>

                        <!-- Review Date (Only Input Field, No Text Displayed) -->
                        <div class="mb-3">
                            <label class="form-label">Review Date</label>
                            <input type="date" class="form-control" name="review_date" value="<?php echo $date_value; ?>">
                        </div>

                        <!-- Review Text -->    
                        <div class="mb-3">
                            <label class="form-label">Review</label>
                            <textarea class="form-control" name="review_desc" rows="4"><?php echo htmlspecialchars($row['review_desc']); ?></textarea>
                        </div>

                        <div class="alert alert-primary" id="form-error" style="display: none;"></div>

                    </form>


                    <br><br>
                    <!-- Buttons -->
                    <div class="action-btn-group">
                        <div class="save-changes-btn-group">
                            <button id="saveChanges" class="btn btn-success">Save Changes</button>
                            <a id="cancelEdit" class="btn btn-secondary" href="index.php">Cancel</a>
                        </div>
                        <button type="button" id="deleteReview" class="btn btn-danger">Delete Project</button>
                    </div>
                    <br>
                <?php } else { ?>
                    <div class="main-container-header">
                        <h2 class="main-container-title">No Such Review</h2>
                    </div>
                <?php } ?>
            </div>

        </div>


        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script> -->
        <script src="../js/reviews/edit.js"></script>
    <?php } else {
        readfile('../php/loginrequired.html');
    } ?>




</body>

</html>