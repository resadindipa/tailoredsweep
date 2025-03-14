<?php



// Check if required POST variables are set and not empty
if (!(isset($_POST['pair_section'])) && isset($_POST['pair_before']) && isset($_POST['pair_after'])) {
    print_update_status(false, "error1");
}

// Database connection
include '../php/config.php';

$BEFOREAFTER_IMAGE_FILE_NAME_RANDOM_NUM_CHARS = 20;

//verify if the before & after image names are valid
if (!(isValidWebpFilename($_POST['pair_before'], $BEFOREAFTER_IMAGE_FILE_NAME_RANDOM_NUM_CHARS) && isValidWebpFilename($_POST['pair_after'], $BEFOREAFTER_IMAGE_FILE_NAME_RANDOM_NUM_CHARS))) {
    print_update_status(false, "error3");
}

//check if the values are only postive integers
if (!(ctype_digit($_POST['pair_section']))) {
    print_update_status(false, "error2");
}


if (is_someone_logged_in() != true) {
    print_update_status(false, "error");
}

function isValidWebpFilename($filename, $chars_length)
{
    return preg_match('/^[a-z0-9]{' . $chars_length . '}\.webp$/', $filename);
}

//get the user's website domain and check if the B&A Section actually exists for him
$userid_session = $_SESSION['ui'];

$pair_section_id = $_POST['pair_section'];
$pair_section_id_int = (int) $pair_section_id;

$tmp_images_array = [$_POST['pair_before'], $_POST['pair_after']];
$image_final_array = [];
// Secure SQL query using prepared statements
$query = "SELECT beforeaftersections,beforeafterimagepairs,website FROM users WHERE id = ? LIMIT 1 ";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "s", $userid_session);

mysqli_stmt_execute($stmt);
// Store result to get the number of rows
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    //ventoras.com/v-c-l/uploads/beforeafter/{$websitedomain}/{$beforeaftersectionsnumber}/{$beforeafterimagepairs}/{$pair_image_ba}.webp
    mysqli_stmt_bind_result($stmt, $beforeaftersectionsnumber, $beforeafterimagepairs_string, $websitedomain);

    mysqli_stmt_fetch($stmt);

    //check if the Before&After Image section actually exists for the user
    if ($pair_section_id_int <= $beforeaftersectionsnumber) {

        //Check in that section, if there already exists a image pair
        $beforeafter_pairs_count_array = [];
        if ($beforeafterimagepairs_string != '') {
            $beforeafter_pairs_count_array = explode(",", $beforeafterimagepairs_string);
        }

        //(3), 3, 3
        $current_sections_num_pairs = (int) $beforeafter_pairs_count_array[$pair_section_id_int - 1];

        //(3) -> 3 + 1 = 4 --> (4), 3, 3
        $new_sections_pair_id = $current_sections_num_pairs + 1;

        // Check if the pair number is within the allowed number of pairs per a section
        if ($current_sections_num_pairs < $MAXIMUM_IMAGE_PAIRS_PER_BEFORE_AFTER_SECTIONS) {

            //Prepare the updated 'beforeafterimagepairs' string value to be updated in MySQL Database
            //array[0] = (4)
            $beforeafter_pairs_count_array[$pair_section_id_int - 1] = strval($new_sections_pair_id);
            $final_beforeafterimagepairs_updated_string = implode(",", $beforeafter_pairs_count_array);
        } else {
            print_update_status(false, "error6");
        }
    } else {
        print_update_status(false, "error5");
    }
} else {
    print_update_status(false, "error4");
}



$tmp_upload_dir_base = dirname(__DIR__) . '/uploads/tmp_beforeafter_images/';
$upload_dir_base = dirname(__DIR__) . '/uploads/beforeafter/';
$uploadDir = $websitedomain . "/" . $pair_section_id_int . "/" . $new_sections_pair_id . "/";
// $beforeImage =  "test1" . ".webp";
// $afterImage =  "test2" . ".webp";

//Check if the two test1.webp and test2.webp files exists in the right folder
for ($i = 1; $i < 3; $i++) {
    $tmp_file_path = $tmp_upload_dir_base . $tmp_images_array[$i - 1];
    $new_file_path = $upload_dir_base . $uploadDir . $i . ".webp";

    // $newNamePath = $uploadDirBase . $uploadDir . $i . ".webp";

    $image_final_array[$i] = $BEFORE_AFTER_IMAGES_LINK_BASE . $uploadDir . $i . ".webp";

    if (!(file_exists($tmp_file_path))) {
        print_update_status(false, "error10");
    }

    if (!file_exists($upload_dir_base . $uploadDir)) {
        mkdir($upload_dir_base . $uploadDir, 0777, true);
    }

    if (file_exists($upload_dir_base . $uploadDir)) {
        //Move onto renaming them
        if (!(rename($tmp_file_path, $new_file_path))) {
            print_update_status(false, "error11");
        }
    } else {
        print_update_status(false, "error12");
    }

    //Update the mySQL database's beforeafterimagepairs value
    // Prepare the update query
    $query = "UPDATE users SET beforeafterimagepairs = ? WHERE id = ?";
    $stmt = mysqli_prepare($link, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $final_beforeafterimagepairs_updated_string, $userid_session);

        // echo $project_highlighted_image;
        //works even when no column is getting updated, checks for any errors of the statement execution instead of affected rows
        if (mysqli_stmt_execute($stmt) != true) {
            print_update_status(false, "error12");
            // print_update_status_basic_layout(true, "success");
        }

        mysqli_stmt_close($stmt);
    }
}

//echo the final uploaded image url back to add.php
// print_update_status(true, "success", $image_final_array[1], $image_final_array[2]);
?>
<div class="beforeafter-image-pair-div" data-pair-number="<?php echo $new_sections_pair_id; ?>">

    <div class="main-container-sub-header">
        <h2 class="main-container-sub-title">Pair - 0<?php echo $new_sections_pair_id; ?></h2>


        <button class="btn btn-danger beforeafter-pair-item-delete">
            <!-- <img src="../content/add.svg" alt="Plus Icon" width="16" height="16"> -->
            Delete Pair
        </button>


    </div>

    <div class="row photos">
        <div class="row photos">
            <div class="col-sm-6 col-md-4 col-lg-3 item">
                <div>
                    <div class="beforeafter-image-image-div" style="background-image: url('<?php echo $image_final_array[1]; ?>');"></div>
                    <!-- <div class="beforeafter-image-image-div"></div> -->
                    <br>
                    <div class="d-flex justify-content-between align-items-center dataphotolinkclassitem" data-pair-image="<?php echo $new_sections_pair_id . "/1.webp"; ?>">
                        <p class="beforeafter-image-name">Before Image</p>
                        <button type="button" class="btn btn-secondary replace-pair-image-btn" data-pair-image-before-after="1">Replace</button>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 item">
                <div>
                    <div class="beforeafter-image-image-div" style="background-image: url('<?php echo $image_final_array[2]; ?>');"></div>
                    <br>
                    <div class="d-flex justify-content-between align-items-center dataphotolinkclassitem" data-pair-image="<?php echo $new_sections_pair_id . "/2.webp"; ?>">
                        <p class="beforeafter-image-name">After Image</p>
                        <button type="button" class="btn btn-secondary replace-pair-image-btn" data-pair-image-before-after="2">Replace</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php

function print_update_status($success_status, $update_status, $image_link_1 = '', $image_link_2 = '')
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link_1' => $image_link_1, 'image_link_2' => $image_link_2]);
    exit();
}
