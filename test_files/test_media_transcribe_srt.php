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

echo "Testing Media Transcribe to SRT...\n";
echo "URL: $mediaUrl\n\n";

// Try the transcribeToSrt function
$result = $media->transcribeToSrt($mediaUrl);

echo "API Response:\n";
print_r($result);
?>