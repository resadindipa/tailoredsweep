<?php
// Include database connection
include '../php/config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../styles/projects/add.css"> -->
    <link rel="stylesheet" href="../styles/projects/edit.css">
    <link rel="stylesheet" href="../styles/popup.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>

<body class="container mt-4">
    <h2>Add a new Project</h2>

    <form id="editReviewForm" method="post" action="#">

        <br><br>
        <div class="alert alert-primary" id="form-error" style="display: none;"></div>
        <br><br>

        <!-- Name Field -->
        <div class="mb-3">
            <label class="form-label">Project Title</label>
            <input type="text" class="form-control" name="project_title">
        </div>

        <!-- Review Date (Only Input Field, No Text Displayed) -->
        <div class="mb-3">
            <label class="form-label">Project Date</label>
            <input type="date" class="form-control" id="project_date_input" name="project_date">
        </div>


        <!-- Description Field -->
        <div class="mb-3">
            <label class="form-label">Project Descriptions</label>
            <textarea name="project_desc" class="form-control" id="project_desc"></textarea>
        </div>

    </form>

    <br><br><br>
    <h2>Add Photos</h2>
    <input type="hidden" id="project_highlighted_image" name="project_highlighted_image" value="">
    <input type="hidden" id="max_images_allowed_per_project" name="max_images_allowed_per_project" value="<?php echo $MAXIMUM_IMAGES_PER_PROJECT; ?>">

    <br><br>
    <div id="addnewimagediv">
        <!-- <label for="image">Choose Image:</label> -->
        <input type="file" name="new_image" id="addnewimageinput" accept="image/*" hidden>
        <button class="btn btn-primary" id="addnewimagebtn">Add Image</button>
    </div>

    <!-- <input type="file" id="addnewimageinput" accept="image/*" hidden>
        <button type="button" id="addnewimagebtn" class="btn btn-primary">Add Picture</button> -->
    <br><br>
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






    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script src="../js/projects/add.js"></script>
</body>

</html>