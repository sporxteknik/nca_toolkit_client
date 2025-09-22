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
$start = 0;
$end = 10;

echo "Testing Trim Video functionality...\n";
echo "Video URL: $videoUrl\n";
echo "Start: $start seconds\n";
echo "End: $end seconds\n\n";

// Try the trim video function
$result = $video->trim($videoUrl, $start, $end);

if ($result['success']) {
    echo "Video trimmed successfully!\n";
    print_r($result);
} else {
    echo "Failed to trim video:\n";
    print_r($result);
}
?>