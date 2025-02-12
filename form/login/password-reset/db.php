<?php

define('TIMEZONE', 'Asia/Colombo');
date_default_timezone_set(TIMEZONE);


$hostname = '143.198.148.168';
$username = 'resad';
$password = 'W3L1v1ngL1fe3!MySQL';
$dbname = 'demo';

$mysql = mysqli_connect($hostname, $username, $password) or die('Unable to connect to database! Please try again later.');
mysqli_select_db($mysql, $dbname);



$now = new DateTime();
$mins = $now->getOffset() / 60;
$sgn = ($mins < 0 ? -1 : 1);
$mins = abs($mins);
$hrs = floor($mins / 60);
$mins -= $hrs * 60;
$offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);

$query = mysqli_query(
    $mysql,
    "SET time_zone='$offset';"
);

$error = "";
