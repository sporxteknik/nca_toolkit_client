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
$timestamp = 10.5; // 10.5 seconds

echo "Testing Extract Thumbnail functionality...\n";
echo "Video URL: $videoUrl\n";
echo "Timestamp: $timestamp seconds\n\n";

// Try the extract thumbnail function
$result = $video->extractThumbnail($videoUrl, $timestamp);

if ($result['success']) {
    echo "Thumbnail extracted successfully!\n";
    print_r($result);
} else {
    echo "Failed to extract thumbnail:\n";
    print_r($result);
}

echo "\n" . str_repeat("-", 50) . "\n\n";

// Try without timestamp
echo "Testing Extract Thumbnail without timestamp...\n";
$result = $video->extractThumbnail($videoUrl);

if ($result['success']) {
    echo "Thumbnail extracted successfully!\n";
    print_r($result);
} else {
    echo "Failed to extract thumbnail:\n";
    print_r($result);
}
?>