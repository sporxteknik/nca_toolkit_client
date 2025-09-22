<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Test URL - using a sample video URL
$videoUrl = 'https://storage.googleapis.com/maksimum-nca/Dolara_Dikkat.mp4';
$captionText = 'This is a test caption';

echo "Testing Add Caption functionality with different parameter names...\n";
echo "Video URL: $videoUrl\n";
echo "Caption Text: $captionText\n\n";

// Try different parameter names
$parameterNames = ['caption', 'text', 'caption_text', 'subtitle', 'subtitles'];

foreach ($parameterNames as $paramName) {
    echo "Trying parameter name: $paramName\n";
    
    // Try the add caption function with this parameter name
    $result = $video->addCaption($videoUrl, [$paramName => $captionText]);
    
    if ($result['success']) {
        echo "SUCCESS with parameter name: $paramName\n";
        print_r($result);
        break;
    } else {
        echo "Failed with parameter name: $paramName\n";
        echo "Error: " . ($result['data']['message'] ?? $result['error'] ?? 'Unknown error') . "\n\n";
    }
}

// Also try with no additional parameters
echo "Trying with no additional parameters...\n";
$result = $video->addCaption($videoUrl);

if ($result['success']) {
    echo "SUCCESS with no additional parameters\n";
    print_r($result);
} else {
    echo "Failed with no additional parameters\n";
    echo "Error: " . ($result['data']['message'] ?? $result['error'] ?? 'Unknown error') . "\n\n";
}
?>