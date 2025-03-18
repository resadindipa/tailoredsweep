<?php

// Define the directory
$directory = __DIR__ . '/test_files/';

// Check if the directory exists
if (!is_dir($directory)) {
    die("Error: Directory not found.");
}

// Get all image files in the directory
$files = scandir($directory);

// Supported input image formats
$supportedFormats = ['jpg', 'jpeg', 'png', 'gif']; // Add more if needed

foreach ($files as $file) {
    $filePath = $directory . $file;

    // Skip directories and non-image files
    if (!is_file($filePath)) {
        continue;
    }

    // Get file extension
    $fileInfo = pathinfo($filePath);
    if (!isset($fileInfo['extension']) || !in_array(strtolower($fileInfo['extension']), $supportedFormats)) {
        continue;
    }

    // Define output file path with .webp extension
    $outputFile = __DIR__ . '/test_files/output/' . $fileInfo['filename'] . '.webp';

    try {
        // Load image with Imagick
        $imagick = new Imagick($filePath);
        $imagick->autoOrient();
        $imagick->setImageFormat('webp');

        // Get original dimensions
        $width = $imagick->getImageWidth();
        $height = $imagick->getImageHeight();

        // Resize only if the image is too large
        //true value in resizeImage(...) keeps the image's ratio the same
        if ($width > 1000 || $height > 1000) {
            $imagick->resizeImage(1000, 1000, Imagick::FILTER_LANCZOS, 1, true);
        }

        
        // Apply a balanced compression level
        $imagick->setImageCompression(Imagick::COMPRESSION_WEBP);
        $imagick->setImageCompressionQuality(75); // Adjust this if needed

        // Save the compressed WebP file
        $imagick->writeImage($outputFile);

        // Cleanup
        $imagick->clear();
        $imagick->destroy();

        echo "Processed: {$file} → " . $fileInfo['filename'] . ".webp\n";
    } catch (Exception $e) {
        echo "Error processing {$file}: " . $e->getMessage() . "\n";
    }
}

?>
