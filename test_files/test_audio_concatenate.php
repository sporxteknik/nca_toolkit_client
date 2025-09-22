<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Audio.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize audio endpoint
$audio = new Audio($apiClient);

// Test audio URLs - using the correct format with objects
$audioUrls = [
    ['audio_url' => 'https://download.samplelib.com/mp3/sample-6s.mp3'],
    ['audio_url' => 'https://download.samplelib.com/mp3/sample-12s.mp3']
];

echo "Testing Audio Concatenate with URL objects:\n";
foreach ($audioUrls as $urlObj) {
    echo "- " . $urlObj['audio_url'] . "\n";
}

// Try the concatenate function
$result = $audio->concatenate($audioUrls);

echo "\nAPI Response:\n";
echo json_encode($result, JSON_PRETTY_PRINT);
?>