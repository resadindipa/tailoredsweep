<?php

// function generateFolders($baseDir, $count) {
//     // Ensure the base directory exists
//     if (!is_dir($baseDir)) {
//         mkdir($baseDir, 0777, true);
//     }

//     // Create folders with incremental numbering
//     for ($i = 1; $i <= $count; $i++) {
//         $folderPath = $baseDir . DIRECTORY_SEPARATOR . $i;
//         if (!is_dir($folderPath)) {
//             mkdir($folderPath, 0777, true);
//             echo "Created folder: $i\n";
//         } else {
//             echo "Folder $i already exists, skipping...\n";
//         }
//     }
// }

function renumberFolders($baseDir, $deleteFolder) {
    // Ensure the base directory exists
    if (!is_dir($baseDir)) {
        echo "Error: Base directory does not exist.\n";
        return;
    }

    // Get all numerical folders
    $folders = array_filter(scandir($baseDir), function($folder) use ($baseDir) {
        return is_dir($baseDir . DIRECTORY_SEPARATOR . $folder) && is_numeric($folder);
    });

    // Convert folder names to integers and sort them in ascending order
    $folders = array_map('intval', $folders);
    sort($folders);

    // Check if the folder to delete exists
    if (!in_array($deleteFolder, $folders)) {
        echo "Error: Folder $deleteFolder does not exist.\n";
        return;
    }

    // Delete the specified folder
    $folderToDelete = $baseDir . DIRECTORY_SEPARATOR . $deleteFolder;
    if (!deleteFolder($folderToDelete)) {
        echo "Error: Failed to delete folder $deleteFolder.\n";
        return;
    }

    echo "Folder $deleteFolder deleted successfully.\n";

    // Re-index the remaining folders
    $newIndex = 1;
    foreach ($folders as $folder) {
        if ($folder == $deleteFolder) continue; // Skip deleted folder

        $oldPath = $baseDir . DIRECTORY_SEPARATOR . $folder;
        $newPath = $baseDir . DIRECTORY_SEPARATOR . $newIndex;

        if ($oldPath !== $newPath && rename($oldPath, $newPath)) {
            echo "Renamed folder $folder to $newIndex.\n";
        }

        $newIndex++;
    }
}

function deleteFolder($folderPath) {
    if (!is_dir($folderPath)) {
        return false;
    }
    
    $files = array_diff(scandir($folderPath), array('.', '..'));
    foreach ($files as $file) {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
        is_dir($filePath) ? deleteFolder($filePath) : unlink($filePath);
    }
    
    return rmdir($folderPath);
}

// Example usage:
$baseDirectory = 'folders'; // Path to your base directory

// Generate 10 folders named 1 to 10
// generateFolders($baseDirectory, 10);

// Delete folder 4 and renumber the rest
renumberFolders($baseDirectory, 4);

?>
