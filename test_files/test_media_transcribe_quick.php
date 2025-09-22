<?php
require_once 'config.php';
require_once 'api/NcaApiClient.php';
require_once 'api/endpoints/Media.php';

// Initialize API client
$apiClient = new NcaApiClient();

// Initialize media endpoint
$media = new Media($apiClient);

// Test URL
$mediaUrl = 'https://storage.googleapis.com/maksimum-nca/Dolara_Dikkat.mp4';

echo "Testing Media Transcribe to get SRT URL...\n";
echo "URL: $mediaUrl\n\n";

// Try the transcribe function with a short timeout to see what the API returns immediately
$apiClient->setTimeout(10); // 10 seconds
$result = $media->getTranscriptionSrt($mediaUrl);

echo "API Response:\n";
print_r($result);
?>