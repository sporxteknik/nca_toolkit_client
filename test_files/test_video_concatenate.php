<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Test video URLs
$videoUrls = [
    'https://samplelib.com/lib/ffmpeg/sample_1280x720.mp4',
    'https://samplelib.com/lib/ffmpeg/sample_960x540.mp4'
];

echo "Testing Video Concatenate with URLs:\n";
foreach ($videoUrls as $url) {
    echo "- $url\n";
}

// Try the concatenate function
$result = $video->concatenate($videoUrls);

echo "\nAPI Response:\n";
echo json_encode($result, JSON_PRETTY_PRINT);
?>