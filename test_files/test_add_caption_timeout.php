<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client with a longer timeout
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Test URL - using the provided video URL
$videoUrl = 'https://storage.googleapis.com/maksimum-nca/transcribe.mp4';

echo "Testing Add Caption functionality with longer timeout...
";
echo "Video URL: $videoUrl

";

// Set a longer timeout for this test
$apiClient->setTimeout(60); // 1 minute

// Try the add caption function with just the video URL
$result = $video->addCaption($videoUrl);

if ($result['success']) {
    echo "Caption added successfully!
";
    print_r($result);
} else {
    echo "Failed to add caption:
";
    print_r($result);
}
?>