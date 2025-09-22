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

echo "Testing Add Caption functionality...\n";
echo "Video URL: $videoUrl\n\n";

// Try the add caption function with just the video URL
$result = $video->addCaption($videoUrl);

if ($result['success']) {
    echo "Caption added successfully!\n";
    print_r($result);
} else {
    echo "Failed to add caption:\n";
    print_r($result);
}
?>