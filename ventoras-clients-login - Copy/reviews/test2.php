<?php 

// function generateRandomString($length = 20)
// {
//     $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
//     $randomString = '';

//     for ($i = 0; $i < $length; $i++) {
//         $randomString .= $characters[random_int(0, strlen($characters) - 1)];
//     }

//     return $randomString;
// }



$uploadDir = dirname(__DIR__) . '/uploads/tmp_profile_pictures/';
echo $uploadDir;