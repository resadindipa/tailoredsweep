<?php

include('db.php');

$query = mysqli_query(
    $mysql,
    "SELECT * FROM `password_reset_temp` WHERE `email`='mwresadindipa@gmail.com';"
);

$row = mysqli_fetch_assoc($query);
$created_date = new DateTime($row['created_date']);


// $created_date = new DateTime("2025-02-10 18:19:00");
// $current_date = new DateTime("2025-02-10 19:18:00");




function is_link_expired($created_date){
    $minutes_the_link_is_valid = 60;
    $current_date = new DateTime();

    $interval = $current_date->diff($created_date);
    $minutesDifference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i; // Convert to minutes
    
    // Check if the second date is 60 or more minutes into the past
    if ($created_date < $current_date && $minutesDifference >= $minutes_the_link_is_valid) {
        return true;
    } else {
        return false;
    }
}