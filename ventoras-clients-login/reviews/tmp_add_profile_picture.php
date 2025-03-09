<?php


// Check if required POST variables are set and not empty
if (!isset($_FILES['profile_picture'])) {
    print_update_status(false, "error");
}

// Database connection
include '../php/config.php';

$profile_picture = $_FILES['profile_picture'];


$uploadDir = dirname(__DIR__) . '/uploads/tmp_profile_pictures/';
// $uploadDir = '../uploads/profile_pictures/';

// Ensure the directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileExt = strtolower(pathinfo($profile_picture['name'], PATHINFO_EXTENSION));
$allowedExts = ['jpg', 'jpeg', 'png'];

if (!in_array($fileExt, $allowedExts)) {
    print_update_status(false, "invalidtype");
}

// $newFileName = uniqid('profile_', true) . '.' . $fileExt;
// $filePath = $uploadDir . $newFileName;

$newFileName = generateRandomString() . '.' . "webp";
$filePath = $uploadDir . $newFileName;

// Move the uploaded file first
if (!move_uploaded_file($profile_picture['tmp_name'], $filePath)) {
    // Check for errors
    // if ($profile_picture['error'] !== UPLOAD_ERR_OK) {
    //     // Print the specific upload error
    //     echo "Error uploading file: " . $profile_picture['error'];
    // } else {
    //     // Print the last file system error
    //     echo "File upload failed. Last error: " . error_get_last()['message'];
    // }

    // echo "Temp File Exists: " . (file_exists($profile_picture['tmp_name']) ? "Yes" : "No") . "<br>";
    // echo "Destination Writable: " . (is_writable(dirname($filePath)) ? "Yes" : "No") . "<br>";

    print_update_status(false, "fileerror");
}

try {
    // Now process the file with Imagick
    $imagick = new Imagick($filePath);
    $imagick->autoOrient();
    $imagick->setImageFormat('webp');

    // Resize and crop to square
    // $dimensions = min($imagick->getImageWidth(), $imagick->getImageHeight());
    // $imagick->cropImage($dimensions, $dimensions, 0, 0);
    $width = $imagick->getImageWidth();
    $height = $imagick->getImageHeight();
    $dimensions = min($width, $height);
    $x = ($width - $dimensions) / 2;
    $y = ($height - $dimensions) / 2;
    $imagick->cropImage($dimensions, $dimensions, $x, $y);

    // $imagick->resizeImage(100, 100, Imagick::FILTER_LANCZOS, 1);
    $imagick->thumbnailImage($REVIEW_PROFILE_PICTURE_MAXIMUM_WIDTH_HEIGHT, $REVIEW_PROFILE_PICTURE_MAXIMUM_WIDTH_HEIGHT, true);

    // Reduce quality
    // $imagick->setImageCompression(Imagick::COMPRESSION_WEBP);
    // $imagick->setImageCompressionQuality($REVIEW_PROFILE_PICTURE_COMPRESSION_QUALITY);

    // Equivalent of Imagick::COMPRESSION_WEBP
    $imagick->setOption('webp:method', '6');    // Best compression quality (0-6)
    $imagick->setOption('webp:lossless', 'false'); // Use lossy compression
    $imagick->setOption('webp:quality', $REVIEW_PROFILE_PICTURE_COMPRESSION_QUALITY);   // Standard WebP quality (0-100)
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
print_update_status(true, "success", $newFileName, $TMP_PROFILE_PICTURE_LINK_BASE);


function print_update_status($success_status, $update_status, $image_name = '', $picturelinkbase = '')
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link' => $picturelinkbase . $image_name, 'image_name' => $image_name]);
    exit();
}
