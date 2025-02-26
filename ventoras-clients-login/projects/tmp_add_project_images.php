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

$newFileName = generateRandomString() . '.' . "jpg";
$filePath = $uploadDir . $newFileName;

// Move the uploaded file first
if (!move_uploaded_file($project_image['tmp_name'], $filePath)) {
    print_update_status(false, "fileerror");
}

try {
    /// Now process the file with Imagick
    $imagick = new Imagick($filePath);
    $imagick->setImageFormat('jpg');

    // Resize and crop to square
    // $dimensions = min($imagick->getImageWidth(), $imagick->getImageHeight());
    // $imagick->cropImage($dimensions, $dimensions, 0, 0);
    $imagick->resizeImage(1000, 1000, Imagick::FILTER_LANCZOS, 1, true);

    // Reduce quality
    $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
    $imagick->setImageCompressionQuality(80);

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
