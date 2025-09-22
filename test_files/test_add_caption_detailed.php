<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Test URL - using the provided video URL
$videoUrl = 'https://storage.googleapis.com/maksimum-nca/transcribe.mp4';

echo "Testing Add Caption functionality...\n";
echo "Video URL: $videoUrl\n\n";

// Try the add caption function with just the video URL
echo "Trying with just video URL...\n";
$result = $video->addCaption($videoUrl);

if ($result['success']) {
    echo "Caption added successfully!\n";
    print_r($result);
} else {
    echo "Failed to add caption:\n";
    print_r($result);
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Try with different parameter names
$captionText = "This is a test caption";

$parametersToTry = ['text', 'caption', 'caption_text', 'subtitle'];

foreach ($parametersToTry as $paramName) {
    echo "Trying with parameter '$paramName'...\n";
    $result = $video->addCaption($videoUrl, [$paramName => $captionText]);
    
    if ($result['success']) {
        echo "SUCCESS with parameter '$paramName'!\n";
        print_r($result);
        break;
    } else {
        echo "Failed with parameter '$paramName':\n";
        if (isset($result['data']['message'])) {
            echo "  Error: " . $result['data']['message'] . "\n";
        } else {
            print_r($result);
        }
    }
    echo "\n";
}
?>
