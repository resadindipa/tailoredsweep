<?php

// $password_string = '$2y$10$s4knbytSwwTyooNSLgsenO7rTab1NbuOOvNY1J2ZSpK6B24V51VoS';
// echo password_verify("engnurdrage1", $password_string);

// require_once 'php/config.php';
// $current_date = new DateTime();
// echo $current_date->format('Y-m-d H:i:s');

function generateRandomString($length = 20) {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    $maxIndex = strlen($characters) - 1;

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $maxIndex)];
    }

    return $randomString;
}

echo generateRandomString(20);