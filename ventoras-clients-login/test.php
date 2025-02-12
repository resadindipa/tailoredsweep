<?php

$reset_key = "dsdsds";
$user_email = "emil@ds.ds";
define('ALLOW_ACCESS', true); 

ob_start();
require 'pwd_reset_email/index.php';
$htmlString = ob_get_clean();
echo $htmlString;