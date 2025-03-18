<?php
// Include database connection
include '../php/config.php';
$someone_logged_in = is_someone_logged_in();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../styles/projects/add.css"> -->
    <link rel="stylesheet" href="../styles/projects/edit.css">
    <link rel="stylesheet" href="../styles/popup.css">
    <link rel="stylesheet" href="../styles/navbar.css">

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
                <div class="main-container-header">
                    <h2 class="main-container-title">Add Project</h2>
                </div>

                <form id="editReviewForm" method="post" action="#">


                    <!-- Name Field -->
                    <div class="mb-3">
                        <label class="form-label">Project Title</label>
                        <input type="text" class="form-control form-text-char-count-input" maxlength="50" name="project_title">
                        <p class="form-text-input-char-count">0/50</p>
                    </div>

                    <!-- Review Date (Only Input Field, No Text Displayed) -->
                    <div class="mb-3">
                        <label class="form-label">Project Date</label>
                        <input type="date" class="form-control" id="project_date_input" name="project_date">
                    </div>


                    <!-- Description Field -->
                    <div class="mb-3">
                        <label class="form-label">Project Descriptions</label>
                        <textarea name="project_desc" class="form-control form-text-char-count-input" maxlength="300" id="project_desc"></textarea>
                        <p class="form-text-input-char-count">0/300</p>
                    </div>

                    <div class="alert alert-primary" id="form-error" style="display: none;"></div>
                    <!-- <br> -->
                </form>

                <br>
                <div class="main-container-header">
                    <h2 class="main-container-title">Add Images</h2>
                </div>

                <input type="hidden" id="project_highlighted_image" name="project_highlighted_image" value="">
                <input type="hidden" id="max_images_allowed_per_project" name="max_images_allowed_per_project" value="<?php echo $MAXIMUM_IMAGES_PER_PROJECT; ?>">
                
                <!-- <br><br> -->
                <div id="addnewimagediv">
                    <!-- <label for="image">Choose Image:</label> -->
                    <input type="file" name="new_image" id="addnewimageinput" accept="image/*" hidden>
                    <button class="btn btn-primary" id="addnewimagebtn">Add Image</button>
                </div>

                <!-- <input type="file" id="addnewimageinput" accept="image/*" hidden>
        <button type="button" id="addnewimagebtn" class="btn btn-primary">Add Picture</button> -->
                <br>
                <div class="alert alert-primary" id="photo-error" style="display: none;">You've added 0 out of 10 images per project.</div>
                <br><br>

                <div class="section-item-main">
                    <div class="photo-gallery main-section">

                        <div class="row photos" id="project-photos-row">

                        </div>
                    </div>
                </div>

                <br><br><br>
                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <div class="d-flex gap-2">
                        <button type="button" id="saveChanges" class="btn btn-success">Add Project</button>
                    </div>
                </div>

            </div>
        </div>

        <script src="../js/projects/add.js"></script>

    <?php } else {
        readfile('../php/loginrequired.html');
    } ?>

</body>

</html>