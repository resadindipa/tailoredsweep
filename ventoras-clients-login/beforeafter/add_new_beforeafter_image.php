<?php

// Check if required POST variables are set and not empty
if (!(isset($_FILES['pair_image']) && isset($_POST['pair_section']) && isset($_POST['pair_beforeorafter']))) {
    print_update_status(false, "error1");
}

//check if the values are only postive integers
if (!(ctype_digit($_POST['pair_section'])) && !(ctype_digit($_POST['pair_beforeorafter']))) {
    print_update_status(false, "error2");
}

if ($_POST['pair_beforeorafter'] != "1" && $_POST['pair_beforeorafter'] != "2") {
    print_update_status(false, "error3");
}
$pair_image = $_FILES['pair_image'];

if($pair_image['size'] > 8388608){
    print_update_status(false, "toolarge");
}

// Database connection
include '../php/config.php';

if (is_someone_logged_in() != true) {
    print_update_status(false, "error");
}


//get the user's website domain and check if the B&A Section actually exists for him
$userid_session = $_SESSION['ui'];



$uploadDirBase = dirname(__DIR__) . '/uploads/tmp_beforeafter_images/';

$randomFileName = generateRandomString($BEFOREAFTER_IMAGE_FILE_NAME_RANDOM_NUM_CHARS) . ".webp";
$filePath = $uploadDirBase . $randomFileName;
$final_image_links = $TMP_BEFORE_AFTER_IMAGES_LINK_BASE . $randomFileName;


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
print_update_status(true, "success", $final_image_links, $randomFileName);


function print_update_status($success_status, $update_status, $image_link = '', $image_name='')
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link' => $image_link, 'image_name' => $image_name]);
    exit();
}