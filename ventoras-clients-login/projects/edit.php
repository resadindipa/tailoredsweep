<?php
// Include database connection
include '../php/config.php';

$someone_logged_in = is_someone_logged_in();
$no_such_project = true;

// Check if an ID is provided
if ($someone_logged_in) {
    if (isset($_GET['id'])) {
        $id = $_GET['id']; // Convert to integer for security

        // Fetch review details using prepared statements
        $query = "SELECT id, project_title, project_date, project_desc, project_highlighted_image, project_images FROM projects WHERE id = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Convert date format for the input field
            $date_value = date("Y-m-d", strtotime($row['project_date']));
            $project_images_string_from_db = $row['project_images'];

            $project_highlighted_image = $row['project_highlighted_image'];
            $project_images_array = [];
            if ($project_images_string_from_db != '') {
                $project_images_array = explode(",", $project_images_string_from_db);
            }

            $no_such_project = false;
        }
    }
}
// else {
// Redirect if no ID is provided
// header("Location: index.php");
//     $no_such_project = true;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/projects/edit.css">
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
        <!-- <br> -->
        <div class="main-container-outer">
            <div class="main-container">
                <?php if ($no_such_project == false) { ?>
                    <div class="main-container-header">
                        <h2 class="main-container-title">Edit Project</h2>
                    </div>

                    <form id="editReviewForm" method="post" action="update_review.php">
                        <input type="hidden" name="project_id" value="<?php echo $row['id']; ?>">

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label class="form-label">Project Title</label>
                            <input type="text" class="form-control" name="project_title" value="<?php echo htmlspecialchars($row['project_title']); ?>">
                        </div>

                        <!-- Review Date (Only Input Field, No Text Displayed) -->
                        <div class="mb-3">
                            <label class="form-label">Project Date</label>
                            <input type="date" class="form-control" name="project_date" value="<?php echo $date_value; ?>">
                        </div>


                        <!-- Description Field -->
                        <div class="mb-3">
                            <label class="form-label">Project Descriptions</label>
                            <textarea name="project_desc" class="form-control" id="project_desc"><?php echo htmlspecialchars($row['project_desc']); ?></textarea>
                        </div>


                        <div class="alert alert-primary" id="form-error" style="display: none;"></div>

                        <br>

                    </form>

                    <div class="main-container-header">
                        <h2 class="main-container-title">Edit Photos</h2>
                    </div>

                    <input type="hidden" name="project_images" value="<?php echo $row['project_images']; ?>">
                    <input type="hidden" id="project_highlighted_image" name="project_highlighted_image" value="<?php echo htmlspecialchars($row['project_highlighted_image']); ?>">
                    <input type="hidden" id="max_images_allowed_per_project" name="max_images_allowed_per_project" value="<?php echo $MAXIMUM_IMAGES_PER_PROJECT; ?>">

                    <div id="addnewimagediv">
                            <!-- <label for="image">Choose Image:</label> -->
                        <input type="file" name="new_image" id="addnewimageinput" accept="image/*" hidden>
                        <br>
                        <button class="btn btn-primary" id="addnewimagebtn" style="display: block;">Add Image</button>
                        <br>
                    </div>



                    <div class="alert alert-primary" id="photo-error">You've added 0 out of 10 images per project.</div>

                    <?php if (sizeof($project_images_array) < $MAXIMUM_IMAGES_PER_PROJECT) {
                        // echo "none";
                    } else {
                        // echo "block";
                    } ?>

                    <br>
                    <div class="section-item-main">
                        <div class="photo-gallery main-section">

                            <div class="row photos" id="project-photos-row">
                                <?php for ($i = 0; $i < sizeof($project_images_array); $i++) {  ?>


                                    <div class="col-sm-6 col-md-4 col-lg-3 item">
                                        <a href="<?php echo $PROJECT_IMAGES_LINK_BASE . $project_images_array[$i]; ?>" data-lightbox="photos">
                                            <img class="img-fluid" src="<?php echo $PROJECT_IMAGES_LINK_BASE . $project_images_array[$i]; ?>">
                                        </a>
                                        <br><br>
                                        <div class="d-flex justify-content-between dataphotolinkclassitem" data-photo-link="<?php echo $project_images_array[$i]; ?>">
                                            <button type="button" class="btn btn-danger delete-photo-btn">Delete</button>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="highlight-photo-btn">
                                                    <?php if ($project_highlighted_image == $project_images_array[$i]) { ?>
                                                        <img src="../content/starfilled.svg" class="highlight-photo-btn-img">
                                                    <?php } else { ?>
                                                        <img src="../content/star.svg" class="highlight-photo-btn-img">
                                                    <?php } ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>

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
                        <h2 class="main-container-title">No Such Project</h2>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script src="../js/projects/edit.js"></script>
    <?php } else {
        readfile('../php/loginrequired.html');
    } ?>



</body>

</html>