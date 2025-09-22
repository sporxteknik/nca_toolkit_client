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

echo "Testing Add Caption functionality with only supported parameters...\n";
echo "Video URL: $videoUrl\n\n";

// Try the add caption function with minimal supported parameters
$options = [
    'settings' => [
        'position' => 'bottom_center',
        'font_size' => 18,
        'font_family' => 'Arial'
    ]
];

$result = $video->addCaption($videoUrl, $options);

if ($result['success']) {
    echo "Caption added successfully!\n";
    print_r($result);
} else {
    echo "Failed to add caption:\n";
    print_r($result);
}
?>