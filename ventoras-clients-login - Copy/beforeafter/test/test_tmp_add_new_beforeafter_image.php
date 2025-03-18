<?php

// Check if required POST variables are set and not empty
if (!(isset($_FILES['pair_image1']) && isset($_POST['pair_section']))) {
    print_update_status(false, "error1");
}

//check if the values are only postive integers
if (!(ctype_digit($_POST['pair_section']))) {
    print_update_status(false, "error2");
}

// Database connection
include '../php/config.php';

if (is_someone_logged_in() != true) {
    print_update_status(false, "error");
}


//get the user's website domain and check if the B&A Section actually exists for him
$userid_session = $_SESSION['ui'];

$pair_image_1 = $_FILES['pair_image1'];
$pair_image_2 = $_FILES['pair_image2'];
$pair_images = [$pair_image_1, $pair_image_2];

$pair_section_id = $_POST['pair_section'];

$pair_section_id_int = (int) $pair_section_id;
$new_created_pair_id = 0;

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

        //get the number of existing pairs for this before&after image section
        $current_num_of_pairs_for_section = $beforeafter_pairs_count_array[$pair_section_id_int - 1];

        if ($current_num_of_pairs_for_section == null) {
            print_update_status(false, "error8");
        }
        //Check if the pair number is within the allowed number of pairs per a section
        if ($current_num_of_pairs_for_section <= $MAXIMUM_IMAGE_PAIRS_PER_BEFORE_AFTER_SECTIONS) {
            $new_created_pair_id = sizeof($beforeafter_pairs_count_array);
        } else {
            //User has already added the maximum number of image pairs
            print_update_status(false, "error6");
        }
    } else {
        print_update_status(false, "error5");
    }
} else {
    print_update_status(false, "error4");
}



$uploadDirBase = dirname(__DIR__) . '/uploads/beforeafter/';
$uploadDir = $websitedomain . "/" . $pair_section_id_int . "/" . $new_created_pair_id . "/";

$final_image_links_array = [];
for ($i = 0; $i < 2; $i++) {
    $uploadImageFinal = ($i + 1) . ".webp";
    $filePath = $uploadDirBase . $uploadDir . $uploadImageFinal;
    $final_image_links_array[$i] = $BEFORE_AFTER_IMAGES_LINK_BASE . $uploadDir . ($i + 1) . ".webp";


    // Ensure the directory exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileExt = strtolower(pathinfo($pair_images[$i]['name'], PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png'];

    if (!in_array($fileExt, $allowedExts)) {
        print_update_status(false, "invalidtype");
    }

    // $newFileName = generateRandomString() . '.' . "webp";
    // $filePath = $uploadDir . $newFileName;
    // $filePath = $uploadDir;

    // Move the uploaded file first
    if (!move_uploaded_file($pair_images[$i]['tmp_name'], $filePath)) {
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
        $dividingFactor = 2.5;
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
}

// echo $uploadDir;
// echo "!!!_-----!!!!" . $filePath;
// $uploadDir = '../uploads/profile_pictures/';




//echo the final uploaded image url back to add.php
print_update_status(true, "success", $final_image_links_array[0], $final_image_links_array[1]);


function print_update_status($success_status, $update_status, $image_link_1 = '', $image_link_2 = '')
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link_1' => $image_link_1, 'image_link_2' => $image_link_2]);
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
