<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Video.php';

// Initialize API client
$apiClient = new NcaApiClient();
$video = new Video($apiClient);

// Test parameters
$videoUrl = 'https://storage.googleapis.com/maksimum-nca/transcribe.mp4';
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

echo "\nAPI Response:\n";
print_r($result);
?>