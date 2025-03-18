<?php


// $image = new Imagick();
// $image->newImage(1, 1, new ImagickPixel('#ffffff'));
// $image->setImageFormat('png');
// $pngData = $image->getImagesBlob();
// echo strpos($pngData, "\x89PNG\r\n\x1a\n") === 0 ? 'Ok' : 'Failed';

// $newFileName = "profile_67addb7a6e71f5.22467407.jpg";
// $uploadDir = dirname(__DIR__) . '/uploads/profile_pictures/';

// echo $uploadDir;


include 'test2.php';
echo generateRandomString();

// $old_profile_picture_link = $uploadDir . $newFileName;

// if (file_exists($old_profile_picture_link)) {
//     unlink($old_profile_picture_link);
// }