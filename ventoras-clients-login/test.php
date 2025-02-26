<?php

// $reset_key = "dsdsds";
// $user_email = "emil@ds.ds";
// define('ALLOW_ACCESS', true); 

// ob_start();
// require 'pwd_reset_email/index.php';
// $htmlString = ob_get_clean();
// echo $htmlString;

// require 'php/config.php';

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// if(isset($_GET['vardump'])){
//     var_dump($_SESSION);
// } else {
//     $_SESSION['si'] = "fml09t1gl39v8qxxf0d0";
//     $_SESSION['ui'] = "8upzqc0c8zacsk4wg5ua";
// }


// for ($i=0; $i < 10; $i++) { 
//     echo $i . "---" . $i%3 . "<br>";
// }


// // $_SESSION['ui'] = "";
// // $_SESSION['si'] = "";

// $_SESSION = array();
// var_dump($_SESSION);


// Load the Imagick extension
$imagePath = dirname(__DIR__) . '/ventoras-clients-login/uploads/beforeafter/1.jpg'; // Replace with your image path
$outputPath = dirname(__DIR__) . '/ventoras-clients-login/uploads/beforeafter/output1.jpg'; // Replace with your desired output path

// Define desired width-to-height ratio
$aspectRatio = 16 / 9; // Example: 16:9 ratio

// Load the image
$image = new Imagick($imagePath);

// Get original dimensions
$origWidth = $image->getImageWidth();
$origHeight = $image->getImageHeight();

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
$image->cropImage($cropWidth, $cropHeight, 0, $yOffset);

// Optional: Resize if needed
$dividingFactor = 2.5;
$image->resizeImage(1920 / $dividingFactor, 1080 / $dividingFactor, Imagick::FILTER_LANCZOS, 1); // Example resize to 1920x1080
// $image->resizeImage(480, 270, Imagick::FILTER_LANCZOS, 1); // Example resize to 1920x1080

// **1. Set Compression Quality (Lower = Smaller File, but More Loss)**
// $image->setImageCompression(Imagick::COMPRESSION_JPEG);
// $image->setImageCompressionQuality(75); // 70-80 is a good balance

// **2. Strip Metadata (EXIF, ICC, etc. - Reduces File Size)**
$image->stripImage();

// **3. Reduce Image Depth (8-bit is usually enough)**
// $image->setImageDepth(8);

// Save the output image
$image->writeImage($outputPath);

// Clean up
$image->destroy();

echo "Image cropped and saved successfully!";
?>
