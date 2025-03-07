<?php

// 143.198.148.168 -- droplet's ip

// 112.134.203.56 -- my laptop's old ip
// 112.134.201.78 - new lap's ip
$password = 'anna';
$generated_hash = password_hash($password, PASSWORD_DEFAULT);

echo $generated_hash;

echo password_verify('anna', $generated_hash); //Returns true
$hash_from_db = '$2y$10$H8RHSBFpuaT4ioAGG6GVE.eFZtFoOmN6OF2Ly3U8Tp8kCDfs/yHQq';
$ds = password_verify('anna', $hash_from_db); //Returns false

var_dump($ds);

// $hostname = '143.198.148.168';
// $username = 'indipa';
// $password = 'W3L1v1ngL1fe3!indipa';
// $dbname = 'ventoras';

// $mysql = mysqli_connect($hostname, $username, $password) or die('Unable to connect to database! Please try again later.');
// mysqli_select_db($mysql, $dbname);

// $query = 'SELECT * FROM users';
// $result = mysqli_query($mysql, $query);
// if ($result) {
//     while ($row = mysqli_fetch_array($result)) {
//         $name = $row['email'];
//         echo 'Username: ' . $name . '<br>';
//     }
// } else {

//     print "Database NOT Found ";
//     mysqli_close($db_handle);
// }
