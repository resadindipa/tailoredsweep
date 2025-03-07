<?php

// Function to generate test files with backdated modification times
function generateTestFiles($dir, $numFiles = 5) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    for ($i = 0; $i < $numFiles; $i++) {
        $filePath = $dir . "/test_file_" . $i . ".txt";
        file_put_contents($filePath, "Test file content " . $i);

        // Set a random modification time (between 1 to 5 hours ago)
        $randomHoursAgo = rand(1, 20);
        $modTime = time() - ($randomHoursAgo * 3600);
        touch($filePath, $modTime);
        
        echo "Created: $filePath (Modified $randomHoursAgo hours ago)\n";
    }
}

// Function to delete files older than the specified hours
function deleteOldFiles($dir, $hours) {
    if (!is_dir($dir)) {
        echo "Directory does not exist.\n" . $dir;
        return;
    }

    $now = time();
    $cutoff = $now - ($hours * 3600);
    
    $files = glob($dir . "/*");
    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < $cutoff) {
            unlink($file);
            // echo "Deleted: $file\n";
        }
    }
}

// Testing
$testDirArray = [
    dirname(__DIR__) . '/uploads/tmp_profile_pictures/', 
    dirname(__DIR__) . '/uploads/tmp_project_images/'
];
// $testDirArray = [dirname(__DIR__) . '/uploads/tmp_profile_pictures/'];
$deleteAfterHours = 12; // Change to 2 or 3 as needed

// Generate test files
// generateTestFiles($testDirArray[0], 10);

for ($i=0; $i < sizeof($testDirArray); $i++) { 
    // Delete old files
    deleteOldFiles($testDirArray[$i], $deleteAfterHours);
}

?>
