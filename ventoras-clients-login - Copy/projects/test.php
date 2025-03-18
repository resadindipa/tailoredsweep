<?php

function isValidFilename($filename) {
    $allowedExts = ['jpg', 'jpeg', 'png'];
    // Ensure only letters, numbers, and a single dot
    if (!preg_match('/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?$/', $filename)) {
        return "Invalid filename format!";
    }

    // Extract the file extension
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Check if the extension is in the allowed list
    if (!in_array($ext, $allowedExts)) {
        return "Invalid file format!";
    }

    return "Valid filename and file format!";
}

// Example Usage:
// $filename = "image.png"; // Change this to test different filenames
// echo isValidFilename($filename);

function arrayDifference(array $a, array $b): array {
    return array_values(array_diff($b, $a));
}

// Example usage
$a = ["dsdssd", "dsdsdssdgf", "gfghhggh", "fdsdfsdffsd"];
$b = ["dsdssd", "dsdsdssdgf", "gfghhggh"];

$result = arrayDifference($b, $b);
print_r($result); // Output: [5, 6]

?>
