<?php
// Introduce a delay (simulates loading time)
// sleep(1);


if(isset($_GET['testerror'])){
    if (rand(0, 1) === 0) {
        http_response_code(500); // Internal Server Error
        // echo json_encode(["error" => "Simulated server failure. Try again."]);
        exit;
    }
    
    // If success
    http_response_code(200);
}

// Get parameters from the URL
$domain = isset($_GET['domain']) ? preg_replace('/[^a-zA-Z0-9.-]/', '', $_GET['domain']) : ''; // Sanitize domain
$section = isset($_GET['section']) ? intval($_GET['section']) : 0;
$pair = isset($_GET['pair']) ? intval($_GET['pair']) : 0;
$side = isset($_GET['side']) ? intval($_GET['side']) : 0;

// Validate that domain is not empty
if (empty($domain)) {
    // http_response_code(400); // Bad request
    exit("Invalid domain");
}

// Define image path dynamically based on the provided domain
$imagePath = "{$domain}/{$section}/{$pair}/{$side}.webp";

// Check if the file exists before serving
if (!file_exists($imagePath)) {
    http_response_code(404);
    exit; // Stop execution if file doesn't exist
}

// Set correct headers for WebP images
header("Content-Type: image/webp");
// header("Cache-Control: max-age=86400, public"); // Cache for 1 day

// Serve the image
readfile($imagePath);
exit;
