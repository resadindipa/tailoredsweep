<?php

define('TIMEZONE', 'Asia/Colombo');
date_default_timezone_set(TIMEZONE);


/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', '143.198.148.168');
define('DB_USERNAME', 'resad');
define('DB_PASSWORD', 'W3L1v1ngL1fe3!MySQL');
define('DB_NAME', 'demo');



/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
