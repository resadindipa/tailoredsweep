<?php
// ini_set('display_errors', 0);
ini_set('display_errors',1); // for the development PC only
error_reporting(E_ALL); 
// Check if the POST request contains the expected parameters
if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {

    date_default_timezone_set("Asia/Colombo");
    // Sanitize and extract data
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Invalid email format');
    }
    
    // 43.225.54.244
    // Create a timestamp for the message
    $timestamp = date('Y-m-d H:i:s');

    // Format the message data
    $data = "Date/Time: $timestamp\n";
    $data .= "Name: $name\n";
    $data .= "Email: $email\n";
    $data .= "Message:\n$message\n\n\n"; // Two newlines to separate messages

    // Append data to messages.txt file
    $filename = 'messages.txt';
    $file = fopen($filename, 'a'); // Open file in append mode
    if ($file) {
        fwrite($file, $data); // Write data to the file
        fclose($file); // Close the file
        echo 'success';
    } else {
  
        echo 'Unable to open file for writing.';
    }
} else {
    print_r($_POST);
    print_r($_GET);
    echo 'Missing one or more required parameters (name, email, message).';
}
?>
