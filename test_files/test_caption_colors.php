<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize video endpoint
$video = new Video($apiClient);

// Test URL - using the video URL you mentioned
$videoUrl = 'https://storage.googleapis.com/maksimum-nca/transcribe.mp4';

echo "Testing Add Caption functionality with color parameters...\n";
echo "Video URL: $videoUrl\n\n";

// Try the add caption function with color parameters
$options = [
    'settings' => [
        'font_size' => 20,
        'style' => 'karaoke',
        'word_color' => '#DD0000',
        'line_color' => '#FFFFFF',
        'outline_color' => '#000000'
    ]
];

echo "Sending request with options:\n";
print_r($options);

$result = $video->addCaption($videoUrl, $options);

if ($result['success']) {
    echo "Caption added successfully!\n";
    print_r($result);
} else {
    echo "Failed to add caption:\n";
    print_r($result);
}
?>