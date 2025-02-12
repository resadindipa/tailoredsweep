<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $uploadDir = '../uploads/profile_pictures/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png'];
    
    if (!in_array($fileExt, $allowedExts)) {
        echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPG and PNG allowed.']);
        exit;
    }
    
    $newFileName = uniqid('profile_', true) . '.' . $fileExt;
    $filePath = $uploadDir . $newFileName;
    
    try {
        $imagick = new Imagick($file['tmp_name']);
        $imagick->setImageFormat('jpeg');
        
        // Resize and crop to square
        $dimensions = min($imagick->getImageWidth(), $imagick->getImageHeight());
        $imagick->cropImage($dimensions, $dimensions, 0, 0);
        $imagick->resizeImage(200, 200, Imagick::FILTER_LANCZOS, 1);
        
        // Reduce quality
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality(80);
        
        $imagick->writeImage($filePath);
        $imagick->clear();
        $imagick->destroy();
        
        echo json_encode(['success' => true, 'file_url' => $filePath]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Image processing failed.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No file uploaded.']);
}
