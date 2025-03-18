<?php
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['domain'])) {
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['domain'])) {
    // $domain = $_GET['domain'];
    $domain = $_POST['domain'];
    
    // Validate domain name (only letters, numbers, and a single dot allowed)
    if (!preg_match('/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?$/', $domain)) {
        die(json_encode(["error" => "Invalid domain name format."]));
    }
    
    $basePath = dirname(__DIR__) . "/uploads/beforeafter" . "/$domain";
    if (!is_dir($basePath)) {
        die(json_encode(["error" => "Directory does not exist." . $basePath]));
    }
    
    $result = [];
    $sections = scandir($basePath);
    
    foreach ($sections as $section) {
        if ($section === '.' || $section === '..' || !is_dir("$basePath/$section") || !ctype_digit($section)) {
            continue;
        }
        
        $pairsPath = "$basePath/$section";
        $pairs = scandir($pairsPath);
        $pairCount = 0;
        
        foreach ($pairs as $pair) {
            if ($pair === '.' || $pair === '..' || !is_dir("$pairsPath/$pair") || !ctype_digit($pair)) {
                continue;
            }
            $pairCount++;
        }
        
        $result[] = ["pairs" => $pairCount];
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
} else {
    echo json_encode(["error" => "Invalid request."]);
}
?>
