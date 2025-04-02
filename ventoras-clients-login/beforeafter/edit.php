<?php
// Include database connection
include '../php/config.php';

$someone_logged_in = is_someone_logged_in();
$no_such_beforeaftersection = true;


// Check if an ID is provided
if ($someone_logged_in) {
    if (isset($_GET['id'])) {
        $id = $_GET['id']; // Convert to integer for security

        if($id <= 0){
            print_update_status_basic_layout(false, "error");
        }


        $userid_session = $_SESSION['ui'];

        // Fetch review details using prepared statements
        $query = "SELECT beforeaftersections,beforeafterimagepairs,website FROM users WHERE id = ?";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "s", $userid_session);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $numofbeforeaftersections, $beforeafterimagepairs_string, $websitedomain);
        mysqli_stmt_fetch($stmt);

        //verify the section actually exists
        if ($id > 0 &&  $id <= $numofbeforeaftersections) {

            //there is an actual before & after image section with this id
            $no_such_beforeaftersection = false;

            $total_pairs_count_array = [];
            if ($beforeafterimagepairs_string != '') {
                $total_pairs_count_array = explode(",", $beforeafterimagepairs_string);
            }
            
            $current_sections_pairs_count = $total_pairs_count_array[$id - 1];
            if ($current_sections_pairs_count == null) {
                $current_sections_pairs_count = 0;
            }
        }
    }
}
// else {
// Redirect if no ID is provided
// header("Location: index.php");
//     $no_such_project = true;
// }

//Change this to switch from 'limited num of pairs' to 'unlimited num of pairs' for before & after images
$current_sections_pairs_count = $LIMITED_NUM_OF_PAIRS_PER_SECTION;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Before & After Section</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/projects/edit.css">
    <link rel="stylesheet" href="../styles/beforeafter/edit.css">
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
                <?php if ($no_such_beforeaftersection == false) { ?>
                    <div class="main-container-header">
                        <h2 class="main-container-title">Before & After Section - <?php echo $id; ?></h2>

                    </div>

                    <br>
                    <div class="section-item-main">
                        <div class="photo-gallery main-section">

                            <div class="beforeafter-image-pair-div beforeafter-image-add-new-pair" id="beforeafter-image-add-new-pair-div" style="display: none;">

                                <div class="main-container-sub-header">
                                    <h2 class="main-container-sub-title">New Image Pair</h2>
                                </div>

                                <div>
                                    <br>
                                    <div class="alert alert-primary mb-0" id="photo-error-2" style="display: none;">You've added 0 out of 10 images per project.</div>
                                    <br>
                                </div>
                                <div class="beforeafter-image-divs">

                                    <div class="row photos">

                                        <div class="col-sm-6 col-md-4 col-lg-3 item">
                                            <div>
                                                <!-- <div class="beforeafter-image-image-div" style="background-image: url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/tailoredsweep.com/1/1/1.webp');"></div> -->
                                                <div class="beforeafter-image-image-div" id="beforeafter-image-image-div-before" data-image-name=""></div>
                                                <br>
                                                <div class="d-flex justify-content-between align-items-center dataphotolinkclassitem" data-photo-link="">
                                                    <p class="beforeafter-image-name">Before Image</p>
                                                    <button type="button" class="btn btn-secondary replace-pair-image-btn add-new-image-before-btn" id="pair-new-image-before" data-pair-image-before-after="1">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-md-4 col-lg-3 item">
                                            <div>
                                                <!-- <div class="beforeafter-image-image-div" style="background-image: url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/tailoredsweep.com/1/1/2.webp');"></div> -->
                                                <div class="beforeafter-image-image-div" id="beforeafter-image-image-div-after" data-image-name=""></div>
                                                <br>
                                                <div class="d-flex justify-content-between align-items-center dataphotolinkclassitem" data-photo-link="">
                                                    <p class="beforeafter-image-name">After Image</p>
                                                    <button type="button" class="btn btn-secondary replace-pair-image-btn add-new-image-after-btn" id="pair-new-image-after" data-pair-image-before-after="2">Upload</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form>
                                    <input type="file" name="add_new_image_1" id="addnewpairimage_1" accept="image/*" hidden>
                                    <input type="file" name="add_new_image_2" id="addnewpairimage_2" accept="image/*" hidden>
                                </form>

                                <br><br>
                                <div class="beforeafter-new-image-action-btns">
                                    <div class="action-btn-group justify-content-end">
                                        <div class="save-changes-btn-group">
                                            <button id="addImagePair" class="btn btn-success" disabled>Add Image Pair</button>
                                            <button id="cancelAddingImagePair" class="btn btn-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- <div> -->
                                <!-- <br>
                                <div class="alert alert-primary mb-0" id="photo-error-1" style="display: none;">You've added 0 out of 10 images per project.</div>
                                <div class="alert alert-primary mb-0" id="photo-error">You've added 0 out of 10 images per project.</div>
                                <br>
                            </div> -->


                            <div class="row photos" id="beforeafter-pairs-row">
                                <?php
                                //printing each pair of before & after images
                                for ($i = 1; $i <= $current_sections_pairs_count; $i++) { ?>
                                    <div class="beforeafter-image-pair-div" data-pair-number="<?php echo $i; ?>">

                                        <div class="main-container-sub-header">
                                            <h2 class="main-container-sub-title">Pair - 0<?php echo $i; ?></h2>
                                        </div>

                                        <div class="row photos">
                                            <div class="col-sm-6 col-md-4 col-lg-3 item">
                                                <div>
                                                    <div class="beforeafter-image-image-div" style="background-image: url('<?php echo $BEFORE_AFTER_IMAGES_LINK_BASE . $websitedomain . "/" . $id . "/" . $i . "/1.webp?v=" . time(); ?>');"></div>
                                                    <!-- <div class="beforeafter-image-image-div"></div> -->
                                                    <br>
                                                    <div class="d-flex justify-content-between align-items-center dataphotolinkclassitem" data-pair-image="<?php echo $i . "/1.webp"; ?>">
                                                        <p class="beforeafter-image-name">Before Image</p>
                                                        <button type="button" class="btn btn-secondary replace-pair-image-btn" data-pair-image-before-after="1">Replace</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-4 col-lg-3 item">
                                                <div>
                                                    <div class="beforeafter-image-image-div" style="background-image: url('<?php echo $BEFORE_AFTER_IMAGES_LINK_BASE . $websitedomain . "/" . $id . "/" . $i . "/2.webp?v=" . time(); ?>');"></div>
                                                    <br>
                                                    <div class="d-flex justify-content-between align-items-center dataphotolinkclassitem" data-pair-image="<?php echo $i . "/2.webp"; ?>">
                                                        <p class="beforeafter-image-name">After Image</p>
                                                        <button type="button" class="btn btn-secondary replace-pair-image-btn" data-pair-image-before-after="2">Replace</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                <?php } ?>

                            </div>
                        </div>
                    </div>

                    <br>
                    
                    <!-- Buttons -->
                    <!-- <div class="action-btn-group">
                        <div class="save-changes-btn-group">
                            <button id="saveChanges" class="btn btn-success">Save Changes</button>
                            <a id="cancelEdit" class="btn btn-secondary" href="index.php">Cancel</a>
                        </div>
                    </div> -->
                    <br>

                    <form action="" id="beforeafter_image_pairs_form">
                        <input type="hidden" id="beforeafter_section_id" name="beforeafter_section_id" value="<?php echo $id; ?>">
                        <input type="hidden" id="max_beforeafter_image_pairs_allowed" name="max_beforeafter_image_pairs_allowed" value="<?php echo $MAXIMUM_IMAGE_PAIRS_PER_BEFORE_AFTER_SECTIONS; ?>">
                        <input type="file" name="new_image" id="addnewpairimage" accept="image/*" hidden>
                    </form>
                <?php } else { ?>
                    <div class="main-container-header">
                        <h2 class="main-container-title">No Such Before & After Section</h2>
                    </div>
                <?php } ?>
            </div>
        </div>

        <script src="../js/beforeafter/edit.js"></script>
    <?php } else {
        readfile('../php/loginrequired.html');
    } ?>



</body>

</html>