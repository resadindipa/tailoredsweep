<?php


// Check if required POST variables are set and not empty
if (!isset($_FILES['project_image'])) {
    print_update_status(false, "error");
}

// Database connection
include '../php/config.php';

$project_image = $_FILES['project_image'];


$uploadDir = dirname(__DIR__) . '/uploads/tmp_project_images/';
// $uploadDir = '../uploads/profile_pictures/';

// Ensure the directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileExt = strtolower(pathinfo($project_image['name'], PATHINFO_EXTENSION));
$allowedExts = ['jpg', 'jpeg', 'png'];

if (!in_array($fileExt, $allowedExts)) {
    print_update_status(false, "invalidtype");
}

$newFileName = generateRandomString() . '.' . "webp";
$filePath = $uploadDir . $newFileName;

// Move the uploaded file first
if (!move_uploaded_file($project_image['tmp_name'], $filePath)) {
    print_update_status(false, "fileerror");
}

try {
    // Now process the file with Imagick
    $imagick = new Imagick($filePath);
    $imagick->autoOrient();
    $imagick->setImageFormat('webp');

    // Get original dimensions
    $width = $imagick->getImageWidth();
    $height = $imagick->getImageHeight();

    // Resize only if the image is too large
    //true value in resizeImage(...) keeps the image's ratio the same
    if ($width > $PROJECT_IMAGES_MAXIMUM_WIDTH_HEIGHT || $height > $PROJECT_IMAGES_MAXIMUM_WIDTH_HEIGHT) {
        $imagick->resizeImage(1200, 1200, Imagick::FILTER_LANCZOS, 1, true);
    }

    // Apply a balanced compression level
    // $imagick->setImageCompression(Imagick::COMPRESSION_WEBP);
    // $imagick->setImageCompressionQuality($PROJECT_IMAGE_COMPRESSION_QUALITY); // Adjust this if needed

    // Equivalent of Imagick::COMPRESSION_WEBP
    $imagick->setOption('webp:method', '6');    // Best compression quality (0-6)
    $imagick->setOption('webp:lossless', 'false'); // Use lossy compression
    $imagick->setOption('webp:quality', $PROJECT_IMAGE_COMPRESSION_QUALITY);   // Standard WebP quality (0-100)
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
print_update_status(true, "success", $newFileName, $TMP_PROJECT_IMAGES_LINK_BASE);


function print_update_status($success_status, $update_status, $image_name = '', $picturelinkbase = '')
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link' => $picturelinkbase . $image_name, 'image_name' => $image_name]);
    exit();
}

// function generateRandomString($length = 20)
// {
//     $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
//     $randomString = '';

//     for ($i = 0; $i < $length; $i++) {
//         $randomString .= $characters[random_int(0, strlen($characters) - 1)];
//     }

//     return $randomString;
// }
