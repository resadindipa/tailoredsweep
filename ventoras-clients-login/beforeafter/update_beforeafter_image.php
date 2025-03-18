<?php

// Check if required POST variables are set and not empty
if (!(isset($_FILES['pair_image']) && isset($_POST['pair_section']) && isset($_POST['pair_num']) && isset($_POST['pair_beforeorafter']))) {
    print_update_status(false, "error1");
}

//check if the values are only postive integers
if (!(ctype_digit($_POST['pair_section'])) && !(ctype_digit($_POST['pair_num'])) && !(ctype_digit($_POST['pair_beforeorafter']))) {
    print_update_status(false, "error2");
}

if ($_POST['pair_beforeorafter'] != "1" && $_POST['pair_beforeorafter'] != "2") {
    print_update_status(false, "error3");
}
$pair_image = $_FILES['pair_image'];
$pair_section = $_POST['pair_section'];
$pair_section_id_int = intval($pair_section);

$pair_num = $_POST['pair_num'];
$pair_num_int = intval($pair_num);

if ($pair_image['size'] > 8388608) {
    print_update_status(false, "toolarge");
}

// Database connection
include '../php/config.php';

if (is_someone_logged_in() != true) {
    print_update_status(false, "error");
}


//get the user's website domain and check if the B&A Section actually exists for him
$userid_session = $_SESSION['ui'];
$pair_beforeorafter = $_POST['pair_beforeorafter'];

//verify if the user really has a b&a section and a pair as specified
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
        // $beforeafter_pairs_count_array = [];
        // if ($beforeafterimagepairs_string != '') {
        //     $beforeafter_pairs_count_array = explode(",", $beforeafterimagepairs_string);
        // }

        //(3), 3, 3
        // $current_sections_num_pairs = (int) $beforeafter_pairs_count_array[$pair_section_id_int - 1];
    } else {
        print_update_status(false, "error2");
    }
}


$upload_dir_base = dirname(__DIR__) . '/uploads/beforeafter/';
$uploadDir = $websitedomain . "/" . $pair_section_id_int . "/" . $pair_num . "/";

if(!file_exists($upload_dir_base . $uploadDir)){
    print_update_status(false, "error3");
}

// $uploadDirBase = dirname(__DIR__) . '/uploads/beforeafter_images/';

// $randomFileName = generateRandomString($BEFOREAFTER_IMAGE_FILE_NAME_RANDOM_NUM_CHARS) . ".webp";
$file_name = $pair_beforeorafter . ".webp";
$final_image_link = $BEFORE_AFTER_IMAGES_LINK_BASE . $uploadDir . $file_name;
$filePath = $upload_dir_base . $uploadDir . $file_name;

if(!file_exists($filePath)){
    print_update_status(false, "error4");
}

$fileExt = strtolower(pathinfo($pair_image['name'], PATHINFO_EXTENSION));
$allowedExts = ['jpg', 'jpeg', 'png'];

if (!in_array($fileExt, $allowedExts)) {
    print_update_status(false, "invalidtype");
}

// Move the uploaded file first
if (!move_uploaded_file($pair_image['tmp_name'], $filePath)) {
    print_update_status(false, "fileerror");
}

try {
    $aspectRatio = 16 / 9; // Example: 16:9 ratio


    // Now process the file with Imagick
    $imagick = new Imagick($filePath);
    $imagick->autoOrient();
    $imagick->setImageFormat('webp');

    // Get original dimensions
    $origWidth = $imagick->getImageWidth();
    $origHeight = $imagick->getImageHeight();

    // Calculate crop height based on aspect ratio
    $cropHeight = intval($origWidth / $aspectRatio);

    // Ensure the crop height does not exceed the original height
    if ($cropHeight > $origHeight) {
        $cropHeight = $origHeight;
        $cropWidth = intval($origHeight * $aspectRatio);
    } else {
        $cropWidth = $origWidth;
    }

    // Calculate Y offset to crop from the center
    $yOffset = intval(($origHeight - $cropHeight) / 2);

    // Crop the middle horizontal section
    $imagick->cropImage($cropWidth, $cropHeight, 0, $yOffset);

    // Optional: Resize if needed
    $dividingFactor = 4;
    $imagick->resizeImage(1920 / $dividingFactor, 1080 / $dividingFactor, Imagick::FILTER_LANCZOS, 1); // Example resize to 1920x1080

    // Convert to WebP format
    $imagick->setImageFormat('webp');

    // Apply a balanced compression level
    // $imagick->setImageCompression(Imagick::COMPRESSION_WEBP);
    // $imagick->setImageCompressionQuality($PROJECT_IMAGE_COMPRESSION_QUALITY); // Adjust this if needed

    // Equivalent of Imagick::COMPRESSION_WEBP
    $imagick->setOption('webp:method', '6');    // Best compression quality (0-6)
    $imagick->setOption('webp:lossless', 'false'); // Use lossy compression
    $imagick->setOption('webp:quality', $BEFOREAFTER_IMAGES_COMPRESSION_QUALITY);   // Standard WebP quality (0-100)
    $imagick->setOption('webp:alpha-quality', '90'); // For images with transparency
    $imagick->setOption('webp:filter-strength', '40'); // Similar to JPEG smoothing
    $imagick->setOption('webp:auto-filter', 'true');

    $imagick->writeImage($filePath);
    $imagick->clear();
    $imagick->destroy();
} catch (Exception $e) {
    print_update_status(false, "error");
}

//echo the final uploaded image url back to add.php
print_update_status(true, "success", $final_image_link);


function print_update_status($success_status, $update_status, $image_link = '')
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link' => $image_link]);
    exit();
}
